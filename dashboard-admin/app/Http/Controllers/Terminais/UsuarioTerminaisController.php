<?php namespace App\Http\Controllers\Terminais;

use App\Http\Requests\Terminais\StoreTerminaisRequest;
use App\Http\Requests\Terminais\StoreUsuarioTerminaisRequest;
use App\Http\Requests\Terminais\UpdateTerminaisRequest;
use App\Http\Requests\Terminais\UpdateUsuarioTerminaisRequest;
use App\Notifications\NewUserTerminaisNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\CanaisVendaEnum;
use TourFacil\Core\Enum\EstadosEnum;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Models\Terminal;
use TourFacil\Core\Models\UsuarioTerminais;

/**
 * Class UsuarioTerminaisController
 * @package App\Http\Controllers\Terminais
 */
class UsuarioTerminaisController extends Controller
{
    /**
     * Post para cadastro de um usuario
     *
     * @param StoreUsuarioTerminaisRequest $request
     * @return array
     */
    public function store(StoreUsuarioTerminaisRequest $request)
    {
        // Senha randomica
        $password = str_random(5);

        // Cadastra o usuario
        $user = UsuarioTerminais::create([
            "terminal_id" => $request->get('terminal_id'),
            "nome" => $request->get('nome'),
            "email" => $request->get('email'),
            "password" => bcrypt($password),
        ]);

        // Verifica se cadastrou o usuario
        if(is_object($user)) {

            // Envia o email para o usuario
            // $user->notify(new NewUserTerminaisNotification($password));

            return $this->autoResponseJson(true, "Usuário cadastrado", "Senha de acesso: $password");
        }

        return $this->autoResponseJson(false, "Terminal não cadastrado", "Não foi possível cadastrar o terminal, tente novamente!");
    }

    /**
     * Detalhes do usuario
     *
     * @param $id_usuario
     * @return mixed
     */
    public function view($id_usuario)
    {
        // Recupera as informacoes do usuario
        return UsuarioTerminais::withTrashed()->findOrFail($id_usuario);
    }

    /**
     * Atualiza, desativa ou gera uma nova senha para o usuario
     *
     * @param UpdateUsuarioTerminaisRequest $request
     * @return array
     */
    public function update(UpdateUsuarioTerminaisRequest $request)
    {
        // Recupera as informacoes do usuario
        $usuario = UsuarioTerminais::withTrashed()->find($request->get('usuario_id'));

        // Caso seja para desativar
        if($request->get('desativar_usuario') == "on") {

            // Desativa o usuario
            $desativar = $usuario->delete();

            if($desativar) {
                return $this->autoResponseJson(true, "Usuário desativado", "O usuário foi desativado com sucesso!");
            }

            return $this->autoResponseJson(false, "Usuário não desativado", "Não foi possível desativar o usuário, tente novamente!");
        }

        // Caso seja para alterar a senha de acesso
        if($request->get('new_password') == "on") {

            // Nova senha randomica
            $password = str_random(5);

            // Atualiza a senha
            $update = $usuario->update(['password' => bcrypt($password)]);

            if($update) {
                return $this->autoResponseJson(true, "Senha alterada", "Nova senha de acesso: $password");
            }

            return $this->autoResponseJson(false, "Senha não alterada", "Não foi possível alterar a senha, tente novamente!");
        }

        // Atualiza os dados do usuario
        $update = $usuario->update([
            "nome" => $request->get('nome'),
            "email" => $request->get('email'),
        ]);

        if($update) {
            return $this->autoResponseJson(true, "Dados atualizados", "As informações do usuário foram atualizadas!");
        }

        return $this->autoResponseJson(false, "Usuário não atualizado", "Não foi possível atualizar os dados do usuário, tente novamente!");
    }

    /**
     * Put para reativar o usuario
     *
     * @param Request $request
     * @return array
     */
    public function restore(Request $request)
    {
        // Recupera as informacoes do usuario
        $usuario = UsuarioTerminais::withTrashed()->find($request->get('usuario_id'));

        // Reativar o usuário
        $reactivate = $usuario->restore();

        if($reactivate) {
            return $this->autoResponseJson(true, "Usuário reativado", "O usuário foi reativado com sucesso!");
        }

        return $this->autoResponseJson(false, "Usuário não reativado", "Não foi possível reativar o usuário, tente novamente!");
    }
}
