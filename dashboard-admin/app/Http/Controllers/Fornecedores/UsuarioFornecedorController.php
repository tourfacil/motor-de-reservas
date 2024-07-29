<?php namespace App\Http\Controllers\Fornecedores;

use App\Http\Requests\Fornecedor\StoreUsuarioRequest;
use App\Http\Requests\Fornecedor\UpdateUsuarioRequest;
use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\UsuarioFornecedor;

/**
 * Class UsuarioFornecedorController
 * @package App\Http\Controllers\Fornecedores
 */
class UsuarioFornecedorController extends Controller
{
    /**
     * Detalhes do usuario
     *
     * @param $id
     * @return mixed
     */
    public function view($id)
    {
        return UsuarioFornecedor::findOrFail($id);
    }

    /**
     * POST para cadastrar um novo usuario
     *
     * @param StoreUsuarioRequest $request
     * @return array
     */
    public function store(StoreUsuarioRequest $request)
    {
        // Recupera os dados
        $dados = $request->all();

        // Faz o hash da senha informada
        $dados['password'] = Hash::make($dados['password']);

        // Cadastra o usuario
        $usuario = UsuarioFornecedor::create($dados);

        if(is_object($usuario)) {
            return $this->autoResponseJson(true, "Usuário cadastrado", "O usuário para o fornecedor foi cadastrado com sucesso!");
        }

        return $this->autoResponseJson(false, "Usuário não cadastrado", "Não foi possível cadastrar o usuário, tente novamente!");
    }

    /**
     * PUT para desativar, alterar senha e dados do usuario
     *
     * @param UpdateUsuarioRequest $request
     * @return array
     */
    public function update(UpdateUsuarioRequest $request)
    {
        // Recupera as informacoes do usuario
        $usuario = UsuarioFornecedor::withTrashed()->find($request->get('usuario_id'));

        // Caso seja para desativar
        if ($request->get('desativar_usuario') == "on") {

            // Desativa o usuario
            if ($usuario->delete()) {
                return $this->autoResponseJson(true, "Usuário desativado", "O usuário foi desativado com sucesso!");
            }

            return $this->autoResponseJson(false, "Usuário não desativado", "Não foi possível desativar o usuário, tente novamente!");
        }

        // Caso seja para alterar a senha de acesso
        if (strlen($request->get('password')) >= 3) {

            // Atualiza a senha
            $update = $usuario->update(['password' => Hash::make($request->get('password'))]);

            if ($update) {
                return $this->autoResponseJson(true, "Senha alterada", "Senha de acesso alterada com sucesso!");
            }

            return $this->autoResponseJson(false, "Senha não alterada", "Não foi possível alterar a senha, tente novamente!");
        }

        // Atualiza os dados do usuario
        $update = $usuario->update([
            "nome" => $request->get('nome'),
            "email" => $request->get('email'),
            "level" => $request->get("level")
        ]);

        if ($update) {
            return $this->autoResponseJson(true, "Usuário atualizado", "As informações do usuário foram atualizadas!");
        }

        return $this->autoResponseJson(false, "Usuário não atualizado", "Não foi possível atualizar os dados do usuário, tente novamente!");
    }

    /**
     * Reativa o cadastro de um usuario
     *
     * @param Request $request
     * @return array
     */
    public function restore(Request $request)
    {
        // Recupera as informacoes do usuario
        $usuario = UsuarioFornecedor::withTrashed()->find($request->get('usuario'));

        // Reativar o usuario
        if($usuario->restore()) {
            return $this->autoResponseJson(true, "Usuário reativado", "O usuário foi reativado com sucesso!");
        }

        return $this->autoResponseJson(false, "Usuário não reativado", "Não foi possível reativar o usuário, tente novamente!");
    }
}
