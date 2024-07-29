<?php namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Moip\Auth\BasicAuth;
use Moip\Auth\Connect;
use Moip\Moip;
use Moip\Resource\Orders;
use TourFacil\Core\Models\Cliente;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Services\Pagamento\CieloCheckout;

class WireCardController extends Controller
{
    /**
     * Cancelamento de transacao
     *
     * @throws \Cielo\API30\Ecommerce\Request\CieloRequestException
     */
    public function cancelar()
    {
        CieloCheckout::cancel('221abc8b-292f-427e-9413-1e1854887b08', 100);
    }

    public function connect()
    {
        $endpoint_connect = (env('APP_ENV') == 'local')
            ? Connect::ENDPOINT_SANDBOX : Connect::ENDPOINT_PRODUCTION;


        $redirect_uri = 'https://tourfacil.test/back';
        $client_id = 'APP-M6UU5497OWCG';
        $scope = true;
        $connect = new Connect($redirect_uri, $client_id, $scope, $endpoint_connect);
        $connect->setScope(Connect::RECEIVE_FUNDS)
            ->setScope(Connect::REFUND)
            ->setScope(Connect::MANAGE_ACCOUNT_INFO)
            ->setScope(Connect::RETRIEVE_FINANCIAL_INFO)
            ->setScope(Connect::TRANSFER_FUNDS)
            ->setScope(Connect::DEFINE_PREFERENCES);

        header('Location: '.$connect->getAuthUrl());
    }

    public function back()
    {
        if(request()->has('code')) {

            $endpoint_connect = (env('APP_ENV') == 'local')
                ? Connect::ENDPOINT_SANDBOX : Connect::ENDPOINT_PRODUCTION;

            $redirect_uri = 'https://tourfacil.test/back';
            $client_id = 'APP-M6UU5497OWCG';
            $scope = true;
            $connect = new Connect($redirect_uri, $client_id, $scope, $endpoint_connect);
            $client_secret = '83861258dbba4e76a7f01d4a90974cd8';
            $connect->setClientSecret($client_secret);
            $code = request()->get('code');
            $connect->setCode($code);
            $auth = $connect->authorize();

            //dd($auth);
        }

        //dd(request()->all());
    }

    public function wirecard()
    {
        $token = env('WIRECARD_TOKEN');
        $key = env('WIRECARD_KEY');
        $endpoint = (env('APP_ENV') == 'local')
            ? Moip::ENDPOINT_SANDBOX : Moip::ENDPOINT_PRODUCTION;

        $cliente = Cliente::find(10);
        $telefone = somenteNumero($cliente->telefone);
        $pedido = Pedido::with([
            'reservas.servico'
        ])->where('cliente_id', $cliente->id)->first();

        //dd($pedido);

        // Wirecard
        $moip = new Moip(new BasicAuth($token, $key), $endpoint);

        // Comprador
        $customer = $moip->customers()
            ->setOwnId($cliente->uuid)
            ->setFullname($cliente->nome)
            ->setEmail($cliente->email)
            ->setBirthDate($cliente->nascimento)
            ->setTaxDocument(somenteNumero($cliente->cpf))
            ->setPhone((int) substr($telefone, 0, 2), (int) substr($telefone, 2, 11));

        $order = $moip->orders()->setOwnId($pedido->codigo);

        $valor_net = 0;

        foreach ($pedido->reservas as $reserva) {
            $cents = toCents($reserva->valor_total);
            $order->addItem($reserva->servico->nome,1, $reserva->servico->uuid, $cents);

            $valor_net += $reserva->valor_net;
        }

        // Juros do parcelamento
        $order->setAddition(toCents($pedido->juros));

        // Cliente
        $order->setCustomer($customer);

        // Coloca o recebedor TourFacil
        $order->addReceiver("MPA-06A92A00A4BF", Orders::RECEIVER_TYPE_PRIMARY);

        // Coloca o recebedor Parceiro
        $order->addReceiver("MPA-7E2027CB28EA", Orders::RECEIVER_TYPE_SECONDARY, toCents($valor_net), null, false);

        // Cria o pedido na wirecard
        $order = $order->create();

        // Pagador
        $holder = $moip->holders()->setFullname($cliente->nome)
            ->setBirthDate($cliente->nascimento)
            ->setTaxDocument(somenteNumero($cliente->cpf), 'CPF')
            ->setPhone((int) substr($telefone, 0, 2), (int) substr($telefone, 2, 11));

        // Pagamento
        $hash = "kGO1UqUNaSLLLCh1FslEIIZdLxn42D3g5ubOLQQlLYfXgoVspaXVPraJG1G1ru71h02NUixP9CVD2/TAlCkFTcB0f5xl1CNswOEPy+IAD5H9ytIeRe8VYQ9kIxpyB5nEbBX5xxk0hjmDc6EmUWq2UpPiPb7dz+5/fs3l/VFSZm6M3KNPetjEJ8t1R6NWAKr4mE2kfpWS6cnyqfG6JDCE1uoPvZcl5xJKb+z30kzNrZKRiBDKJC9mE68Ksw3qIsZBdGNLDhblk04R6SHXE15cDJTqqqX/PadVqiM8wPHbrxbpIxeKxRyILOmirm95Kr7Br9gKYM3atCvVyECrGhohAA==";

        $payment = $order->payments()
            ->setCreditCardHash($hash, $holder, false)
            ->setInstallmentCount(2)
            ->setStatementDescriptor(env('WIRECARD_DESCRIPTOR'))
            ->execute();

        dd($payment);
    }
}
