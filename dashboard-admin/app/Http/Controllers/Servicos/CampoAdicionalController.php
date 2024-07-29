<?php namespace App\Http\Controllers\Servicos;

use App\Http\Requests\StoreCampoAdicionalServicoRequest;
use App\Http\Requests\UpdateCampoAdicionalServicoRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\CampoAdicionalServico;
use TourFacil\Core\Models\Servico;

/**
 * Class CampoAdicionalController
 * @package App\Http\Controllers\Servicos
 */
class CampoAdicionalController extends Controller
{
    /**
     * Detalhes do campo
     *
     * @param $campo_id
     * @return mixed
     */
    public function view($campo_id)
    {
        return CampoAdicionalServico::withTrashed()->find($campo_id);
    }

    /**
     * Post para reativar o campo adicional
     *
     * @param Request $request
     * @return array
     */
    public function reactivate(Request $request)
    {
        // Cadastra o campo adicional
        $campo_adicional = CampoAdicionalServico::withTrashed()->find($request->get('campo_id'));

        // Caso encontre o campo
        if(is_object($campo_adicional)) {

            // Reativa o campo
            $campo_adicional->restore();

            return $this->autoResponseJson(true, "Campo reativado", "O campo foi reativado com sucesso!");
        }

        return $this->autoResponseJson(false, "Campo não encontrado", "O campo adicional não foi encontrado, tente novamente!");
    }

    /**
     * Atualiza os dados dos campos adicionais
     *
     * @param UpdateCampoAdicionalServicoRequest $request
     * @return array
     */
    public function update(UpdateCampoAdicionalServicoRequest $request)
    {
        // Cadastra o campo adicional
        $campo_adicional = CampoAdicionalServico::withTrashed()->find($request->get('campo_id'));

        // caso seja para deletar o campo
        $delete_campo = $request->get('delete_campo');

        // Verifica se é para atualizar o campo
        if($delete_campo == "off") {

            // Atualiza os dados
            $update = $campo_adicional->update($request->all());

            if($update) {
                return $this->autoResponseJson(true, "Campo atualizado", "O campo foi atualizado com sucesso!");
            }

            return $this->autoResponseJson(false, "Não foi possível atualizar", "As informações não salvas, tente novamente!");

        } else {

            // Desativa o campo
            $campo_adicional->delete();

            return $this->autoResponseJson(true, "Campo desativado", "O campo foi desativado com sucesso!");
        }
    }

    /**
     * Cadastra um novo campo adicional ao serviço
     *
     * @param StoreCampoAdicionalServicoRequest $request
     * @return array
     */
    public function store(StoreCampoAdicionalServicoRequest $request)
    {
        // Cadastra o campo adicional
        $campo_adicional = CampoAdicionalServico::create($request->all());

        // Verifica se cadastrou
        if(is_object($campo_adicional)) {
            return $this->autoResponseJson(true, "Campo adicional cadastrado", "O campo " . $campo_adicional->nome . " foi cadastrado com sucesso!");
        }

        return $this->autoResponseJson(false, 'Campo adicional não cadastrado', 'Não foi possível cadastrar o campo, tente novamente!');
    }
}
