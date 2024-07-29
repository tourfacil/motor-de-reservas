<?php namespace App\Http\Controllers\Validador;

use App\Http\Requests\ValidarVoucherRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\StatusReservaEnum;
use TourFacil\Core\Enum\ValidacaoEnum;
use TourFacil\Core\Models\ReservaPedido;

/**
 * Class ValidadorController
 * @package App\Http\Controllers\Validador
 */
class ValidadorController extends Controller
{
    /**
     * Página do validador
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Validacao por codigo
        $validacao_codigo = ValidacaoEnum::VALIDACAO_CODIGO;

        // Validacao por CPF
        $validacao_cpf = ValidacaoEnum::VALIDACAO_CPF;

        // Tipo de validacao atual
        $tipo_validacao = strtoupper(request()->get('tipo', $validacao_codigo));

        return view('validador.validador', compact(
            'tipo_validacao',
            'validacao_codigo',
            'validacao_cpf'
        ));
    }

    /**
     * Detalhes do ticket
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function view()
    {
        // Validacao por codigo
        $validacao_codigo = ValidacaoEnum::VALIDACAO_CODIGO;

        // Validacao por CPF
        $validacao_cpf = ValidacaoEnum::VALIDACAO_CPF;

        // Tipo de validacao atual
        $tipo_validacao = strtoupper(request()->get('validacao', $validacao_codigo));

        // Codigo do ticket
        $codigo_ticket = request()->get('ticket');

        // CPF do comprador
        $cpf_comprador = request()->get('cpf');

        if(is_null($codigo_ticket) && is_null($cpf_comprador)) {
            return redirect()->route('app.validador.index');
        }

        // Caso a validacao seja por codigo
        if($tipo_validacao == $validacao_codigo) {

            // Recupera os dados da reserva pelo codigo
            return $this->buscaReservaCodigo($codigo_ticket, $validacao_codigo, $validacao_cpf, $tipo_validacao);
        }

        // Recupera os dados da reserva pelo CPF
        return $this->buscaReservasCpf($cpf_comprador, $validacao_codigo, $validacao_cpf, $tipo_validacao);
    }

    /**
     * Busca os detalhes da reserva pelo codigo
     *
     * @param $codigo_ticket
     * @param $validacao_codigo
     * @param $validacao_cpf
     * @param $tipo_validacao
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    private function buscaReservaCodigo($codigo_ticket, $validacao_codigo, $validacao_cpf, $tipo_validacao)
    {
        // Recupera os dados da reserva
        $reserva = ReservaPedido::with([
            'servico',
            'validacao',
            'pedido.cliente',
            'agendaDataServico',
            'quantidadeReserva.variacaoServico'
        ])->where('voucher', $codigo_ticket)->first();

        // Caso nao encontre a reserva
        if(is_null($reserva)) {
            return redirect()->back()->with(['aviso' => "Reserva não encontrada, tente novamente!"]);
        }

        // Reserva valida para uso
        $reserva_valida = in_array($reserva->status, [
            StatusReservaEnum::ATIVA, StatusReservaEnum::FINALIZAR
        ]);

        // Caso a reserva esteja cancelada
        $reserva_cancelada = ($reserva->status == StatusReservaEnum::CANCELADO);

        // Caso a reserva ja foi utilizada
        $reserva_utilizada = ($reserva->status == StatusReservaEnum::UTILIZADO);

        // Caso a reserva esteja pendente de pagamento
        $reserva_pendente = ($reserva->status == StatusReservaEnum::AGUARDANDO);

        return view('validador.detalhe-ticket', compact(
            'reserva',
            'validacao_codigo',
            'validacao_cpf',
            'tipo_validacao',
            'reserva_valida',
            'reserva_cancelada',
            'reserva_utilizada',
            'reserva_pendente'
        ));
    }

    /**
     * Lista as vendas por CPF
     *
     * @param $cpf_comprador
     * @param $validacao_codigo
     * @param $validacao_cpf
     * @param $tipo_validacao
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    private function buscaReservasCpf($cpf_comprador, $validacao_codigo, $validacao_cpf, $tipo_validacao)
    {
        // Valida o CPF
        if(strlen($cpf_comprador) < 14) {
            return redirect()->route('app.validador.index', ['tipo' => $validacao_cpf])->with([
                'aviso' => 'O CPF deve conter 11 dígitos, tente novamente'
            ])->withInput(['cpf' => $cpf_comprador]);
        }

        // Recupera os dados da reserva
        $reservas = ReservaPedido::with([
            'servico',
            'validacao',
            'pedido.cliente',
            'agendaDataServico',
        ])->whereHas('pedido.cliente', function ($q) use ($cpf_comprador) {
            return $q->where('cpf', 'LIKE', $cpf_comprador);
        })->latest()->get();

        // Quantidade encontrada
        $quantidade_reservas = $reservas->count();

        // Caso nao encontre nenhuma reserva
        if($quantidade_reservas == 0) {
            return redirect()->route('app.validador.index', ['tipo' => $validacao_cpf])->with([
                'aviso' => 'Nenhum resultado encontrado para este CPF'
            ])->withInput(['cpf' => $cpf_comprador]);
        }

        return view('validador.lista-cpf', compact(
            'reservas',
            'cpf_comprador',
            'validacao_codigo',
            'validacao_cpf',
            'tipo_validacao',
            'quantidade_reservas'
        ));
    }

    /**
     * Autentica o voucher da reserva
     *
     * @param ValidarVoucherRequest $request
     * @return array
     */
    public function autenticarVoucher(ValidarVoucherRequest $request)
    {
        // Recupera os dados da reserva
        $reserva = ReservaPedido::with('validacao')->find($request->get('reserva'));

        // Tipo da validacao CPF ou Codigo
        $tipo_validacao = $request->get('tipo_validacao');

        // Verifica se já nao foi validada
        if(is_null($reserva->validacao)) {

            // Coloca a reserva como utilizada
            $reserva->update(['status' => StatusReservaEnum::UTILIZADO]);

            // Salva a validacao da reserva
            $reserva->validacao()->create([
                "pedido_id" => $reserva->pedido_id,
                "validado" => Carbon::now(),
                "observacoes" => [
                    'tipo_validacao' => $tipo_validacao,
                    'ip_user' => getUserIP(),
                    'local' => 'Painel fornecedor temporário'
                ],
            ]);

            return $this->autoResponseJson(true, "Voucher autenticado com sucesso", "Este voucher foi validado com sucesso!");
        }

        return $this->autoResponseJson(false, "Voucher já utilizado", "Este voucher já foi utilizado!");
    }
}
