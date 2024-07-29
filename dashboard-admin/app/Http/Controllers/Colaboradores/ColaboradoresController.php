<?php namespace App\Http\Controllers\Colaboradores;

use App\Enum\UserLevelEnum;
use App\Http\Requests\Colaboradores\StoreColaboradorRequest;
use App\Http\Requests\Colaboradores\UpdateColaboradorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class ColaboradoresController
 * @package App\Http\Controllers\Colaboradores
 */
class ColaboradoresController extends Controller
{
    /**
     * Listagem dos colaboradores
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Todos os colaboradores
        $colaboradores = User::withTrashed()->get();

        // Niveis de acesso
        $nivels_acesso = UserLevelEnum::LEVELS;

        return view('paginas.colaboradores.colaboradores', compact(
            'colaboradores',
            'nivels_acesso'
        ));
    }

    /**
     * Post para cadastrar o colaborador
     *
     * @param StoreColaboradorRequest $request
     * @return array
     */
    public function store(StoreColaboradorRequest $request)
    {
        // Senha randomica
        $password = str_random(5);
        // Cadastra o usuario
        $user = User::create([
            "name" => $request->get('name'),
            "email" => $request->get('email'),
            "level" => $request->get('level'),
            "afiliado_id" => $request->get('afiliado_id'),
            "password" => bcrypt($password),
        ]);

        // Verifica se cadastrou o usuario
        if(is_object($user)) {
            return $this->autoResponseJson(true, "Colaborador cadastrado", "Senha de acesso: $password");
        }

        return $this->autoResponseJson(false, "Colaborador não cadastrado", "Não foi possível cadastrar o colaborador, tente novamente!");
    }

    /**
     * Detalhes do colaborador
     *
     * @param $colaborador_id
     * @return mixed
     */
    public function view($colaborador_id)
    {
        return User::withTrashed()->findOrFail($colaborador_id);
    }

    /**
     * Atualiza, desativa ou gera uma nova senha para o colaborador
     *
     * @param UpdateColaboradorRequest $request
     * @return array
     */
    public function update(UpdateColaboradorRequest $request)
    {
        // Recupera as informacoes do colaborador
        $colaborador = User::withTrashed()->find($request->get('colaborador_id'));

        // Caso seja para desativar
        if ($request->get('desativar_colaborador') == "on") {

            // Desativa o colaborador
            $desativar = $colaborador->delete();

            if ($desativar) {
                return $this->autoResponseJson(true, "Colaborador desativado", "O colaborador foi desativado com sucesso!");
            }

            return $this->autoResponseJson(false, "Colaborador não desativado", "Não foi possível desativar o colaborador, tente novamente!");
        }

        // Atualiza os dados do colaborador
        $update = $colaborador->update([
            "name" => $request->get('name'),
            "email" => $request->get('email'),
            "level" => $request->get("level"),
            "afiliado_id" => $request->get("afiliado_id"),
        ]);

        // Caso seja para alterar a senha de acesso
        if ($request->get('new_password') == "on") {

            // Nova senha randomica
            $password = str_random(5);

            // Atualiza a senha
            $update = $colaborador->update(['password' => bcrypt($password)]);

            if ($update) {
                return $this->autoResponseJson(true, "Senha alterada", "Nova senha de acesso: $password");
            }

            return $this->autoResponseJson(false, "Senha não alterada", "Não foi possível alterar a senha, tente novamente!");
        }

        if ($update) {
            return $this->autoResponseJson(true, "Colaborador atualizado", "As informações do colaborador foram atualizadas!");
        }

        return $this->autoResponseJson(false, "Colaborador não atualizado", "Não foi possível atualizar os dados do colaborador, tente novamente!");
    }

    /**
     * Reativa o cadastro de um colaborador
     *
     * @param Request $request
     * @return array
     */
    public function restore(Request $request)
    {
        // Recupera as informacoes do colaborador
        $colaborador = User::withTrashed()->find($request->get('colaborador'));

        // Reativar o colaborador
        $reactivate = $colaborador->restore();

        if($reactivate) {
            return $this->autoResponseJson(true, "Colaborador reativado", "O colaborador foi reativado com sucesso!");
        }

        return $this->autoResponseJson(false, "Colaborador não reativado", "Não foi possível reativar o colaborador, tente novamente!");
    }
}
