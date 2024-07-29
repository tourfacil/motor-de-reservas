<?php namespace App\Http\Controllers\Cliente;

use App\Http\Requests\Cliente\UpdateDadosClienteRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class MeusDadosController
 * @package App\Http\Controllers\Cliente
 */
class MeusDadosController extends Controller
{
    /**
     * Página de meus dados
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Recupera os dados do cliente
        $cliente = auth('clientes')->user();

        // Destino na sessao
        $destino = $this->getDestinoSession()['destino'];

        return view('paginas.cliente.meus-dados', compact(
            'cliente',
            'destino'
        ));
    }

    /**
     * PUT para atualizaçao de cadastro do cliente
     *
     * @param UpdateDadosClienteRequest $request
     * @return array
     */
    public function update(UpdateDadosClienteRequest $request)
    {
        // Recupera os dados do cliente
        $cliente = auth('clientes')->user();

        // Atualiza os dados do cliente
        $update = $cliente->update([
            'nome' => $request->get('nome'),
            'cpf' => $request->get('cpf'),
            'nascimento' => $request->get('nascimento'),
            'telefone' => $request->get('telefone'),
            'email' => $request->get('email')
        ]);

        if($update) {
            return $this->autoResponseJson(true, "Dados atualizados", "Suas informações foram salvas com sucesso!");
        }

        return $this->autoResponseJson(false, "Não foi possível atualizar", "Não conseguimos atualizar seus dados, tente novamente!");
    }
}
