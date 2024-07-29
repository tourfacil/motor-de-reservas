<?php namespace App\Http\Controllers\Painel;

use App\Http\Requests\UpdateDadosUsuarioRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Models\UsuarioFornecedor;

/**
 * Class UsuarioController
 * @package App\Http\Controllers\Painel
 */
class UsuarioController extends Controller
{
    use ResetsPasswords;

    /**
     * Dados do usuario logado
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Dados do usuario logado
        $dados = auth()->user();

        // Dados do fornecedor ligado ao usuario
        $fornecedor = Fornecedor::find($dados->fornecedor_id);

        return view('paginas.usuarios.meus-dados', compact(
            'dados',
            'fornecedor'
        ));
    }

    /**
     * Atualiza os dados do usuario logado
     *
     * @param UpdateDadosUsuarioRequest $request
     * @return array
     */
    public function update(UpdateDadosUsuarioRequest $request)
    {
        // Recupera o usuario logado
        $usuario = UsuarioFornecedor::find(auth()->user()->id);

        if($usuario->update($request->all())) {
            return $this->autoResponseJson(true, "Dados atualizados", "Seus dados foram salvos com sucesso!");
        }

        return $this->autoResponseJson(false, "Dados não atualizados", "Não foi possível atualizar seus dados, tente novamente!");
    }

    /**
     * View para alterar a senha do usuario
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewAlterarSenha()
    {
        // Dados do usuario logado
        $dados = auth()->user();

        return view('paginas.usuarios.alterar-senha', compact(
            'dados'
        ));
    }

    /**
     * Atualiza a senha de acesso do usuario
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateSenha(Request $request)
    {
        // Valida o formulário
        $this->validate($request, ['password' => 'required|confirmed|min:6']);

        // Recupera o usuario logado
        $usuario = UsuarioFornecedor::find(auth()->user()->id);

        // Atualiza a senha do usuario
        $this->resetPassword($usuario, $request->get('password'));

        return $this->autoResponseJson(true, "Senha alterada!", "Sua senha de acesso foi alterada com sucesso!");
    }
}
