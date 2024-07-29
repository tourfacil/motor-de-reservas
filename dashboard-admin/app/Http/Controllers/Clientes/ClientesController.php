<?php namespace App\Http\Controllers\Clientes;

use App\Http\Requests\Clientes\UpdateDadosClienteRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use TourFacil\Core\Models\Cliente;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Models\TransacaoPedido;

/**
 * Class ClientesController
 * @package App\Http\Controllers\Clientes
 */
class ClientesController extends Controller
{
    /**
     * Listagem dos clientes
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $clientes = Cliente::latest()->limit(100)->get();

        return view('paginas.clientes.clientes', compact(
            'clientes'
        ));
    }

    /**
     * Detalhes do cliente
     *
     * @param $cliente_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($cliente_id)
    {
        $cliente = Cliente::with([
            'canalVenda',
            'reservas.servico',
            'reservas.agendaDataServico',
        ])->withTrashed()->find($cliente_id);

        return view('paginas.clientes.detalhes-cliente', compact(
            'cliente'
        ));
    }

    /**
     * Pesquisa de cliente
     *
     * @return array
     */
    public function search()
    {
        // Pesquisa
        $search = mb_strtolower(request()->get('q'));

        // CPF formatado
        $cpf = mask(preg_replace("/[^0-9]/", "", $search), "###.###.###-##");

        // Para evitar cpf vazio
        $cpf = (strlen($cpf)) ? $cpf : $search;

        // resultado
        $clientes = Cliente::whereLike(['email', 'nome'], $search)->orWhere('cpf', "LIKE", "%$cpf%")->latest()->get();

        return ['clientes' => $clientes, 'view' => route('app.clientes.view')];
    }

    /**
     * Pesquisa pelo TID ou numero de cartao de credito
     *
     * @return array
     */
    public function searchTid()
    {
        // Pesquisa
        $search = mb_strtolower(request()->get('q'));

        // Busca o pedido pelo TID ou numero do cartao
        $transacoes = TransacaoPedido::with([
            'pedido' => function($q) {
                return $q->select(['id', 'codigo', 'juros', 'valor_total']);
            }
        ])->select([
            'pedido_id', 'transacao'
        ])->whereLike(['transacao'], $search)->latest()->get();

        return [
            'pedidos' => $transacoes,
            'view' => route('app.pedidos.view')
        ];
    }

    /**
     * Pesquisa pelo TID ou numero de cartao de credito
     *
     * @return array
     */
    public function searchCodPedido()
    {
        // Pesquisa
        $search = mb_strtolower(request()->get('q'));

        // Colunas da tabela pedidos
        $select_pedido = [
            'id', 'codigo', 'juros', 'valor_total'
        ];

        // Busca o pedido pelo codigo
        $pedidos = Pedido::select($select_pedido)
            ->whereLike(['codigo'], $search)->latest()->get();

        // Caso nao encontre pelo pedido
        if($pedidos->isEmpty()) {
            // Busca a reserva
            $pedidos = ReservaPedido::with([
                'pedido' => function($q) use ($select_pedido) {
                    return $q->select($select_pedido);
                }
            ])->select('pedido_id')->whereLike(['voucher'], $search)->latest()->get();
        }

        return [
            'pedidos' => $pedidos,
            'view' => route('app.pedidos.view')
        ];
    }

    /**
     * Atualiza os dados do cliente
     *
     * @param UpdateDadosClienteRequest $request
     * @return array
     */
    public function update(UpdateDadosClienteRequest $request)
    {
        // Recupera os dados do cliente
        $cliente = Cliente::find($request->get('cliente_id'));

        // Verifica se nao tem nenhum cliente com o mesmo email para o mesmo canal
        $has_clientes = Cliente::where([
            'email' => $request->get('email'),
            'canal_venda_id' => $cliente->canal_venda_id
        ])->where('id', '<>', $cliente->id)->get();

        // Caso nao tenha nenhum cliente com mesmos dados
        if($has_clientes->count() == 0) {

            // Atualiza os dados do cliente
            $update = $cliente->update($request->all());

            if($update) {
                return $this->autoResponseJson(true, "Cliente atualizado", "As informações do cliente foram atualizadas com sucesso!");
            }

            return $this->autoResponseJson(false, "Cliente não atualizado", "Não foi possível atualizar o cliente, tente novamente!");
        }

        return $this->autoResponseJson(false, "Este e-mail já está em uso", "o e-mail {$request->get('email')} já está sendo usado!");
    }

    /**
     * Delete para desativar o cliente
     *
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        // Recupera os dados do cliente
        $cliente = Cliente::find($request->get('cliente_id'));

        // Desativa o cliente
        $delete = $cliente->delete();

        if($delete) {
            return $this->autoResponseJson(true, "Cliente desativado", "O cliente foi desativado com sucesso!");
        }

        return $this->autoResponseJson(false, "Não foi possível desativar", "Não foi possível desativar este cliente!");
    }

    /**
     * Reativa o cadastro do cliente
     *
     * @param Request $request
     * @return array
     */
    public function restore(Request $request)
    {
        // Recupera os dados do cliente
        $cliente = Cliente::withTrashed()->find($request->get('cliente_id'));

        // Reativa o cliente
        $restore = $cliente->restore();

        if($restore) {
            return $this->autoResponseJson(true, "Cliente reativado", "O cliente foi reativado com sucesso!");
        }

        return $this->autoResponseJson(false, "Não foi possível reativar", "Não foi possível reativar este cliente!");
    }

    /**
     * Reseta a senha do cliente
     *
     * @param Request $request
     * @return array
     */
    public function resetarSenha(Request $request) 
    {

        // Caso o usuário não seja admin. Retorna msg de permissão insuficiente
        if(userIsAdmin() == false) {
            return response(['status' => false, 'info' => 'Permissão insuficiente'], 200);
        }

        // Caracteres para gerar a nova senha
        $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; 
        $mi = "abcdefghijklmnopqrstuvyxwz"; 
        $nu = "0123456789"; 
        $si = "!@#$%";
        $tamanho = 10; 

        $senha = "";

        // Geração da nova senha embaralhando os caracteres acima
        $senha .= str_shuffle($ma);
        $senha .= str_shuffle($mi);
        $senha .= str_shuffle($nu);
        $senha .= str_shuffle($si);
        $senha = substr(str_shuffle($senha),0,$tamanho);

        // Array de dados a serem retornadados
        $dados = [
            'senha' => $senha,
        ];

        // Atualizar a senha do cliente
        $cliente_id = $request->get('cliente_id');
        Cliente::find($cliente_id)->update(['password' => Hash::make($senha)]);

        // Retorna
        return response($dados, 200);
    }
}
