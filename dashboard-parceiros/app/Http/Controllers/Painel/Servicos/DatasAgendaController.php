<?php namespace App\Http\Controllers\Painel\Servicos;

use App\Http\Requests\Servico\RemoveDataAgendaRequest;
use App\Http\Requests\Servico\StoreDataAgendaRequest;
use App\Http\Requests\Servico\UpdateDataAgendaRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\AgendaDataServico;
use TourFacil\Core\Models\AgendaServico;
use TourFacil\Core\Services\AgendaService;

/**
 * Class DatasAgendaController
 * @package App\Http\Controllers\Servicos
 */
class DatasAgendaController extends Controller
{
    /**
     * Datas da agenda para o calendario administrativo
     *
     * @param $agenda_id
     * @return array
     */
    public function datasCalendario($agenda_id)
    {
        return AgendaService::calendarioAdmin($agenda_id);
    }

    /**
     * Post para cadastro das datas na agenda
     *
     * @param StoreDataAgendaRequest $request
     * @return array
     */
    public function store(StoreDataAgendaRequest $request)
    {
        // Recupera os dados da agenda
        $agenda = AgendaServico::find($request->get('agenda_id'));

        // Cadastra as datas na agenda
        $result = AgendaService::storeDatasAgenda($agenda, $request->all());

        if($result) {
            return $this->autoResponseJson(true, "Datas cadastradas", "As datas foram salvas na agenda do serviço.");
        }

        return $this->autoResponseJson(false, "Datas não cadastradas", "Não foi possível salvas as datas na agenda, tente novamente!");
    }

    /**
     * Detalhes da data no administrativo
     *
     * @param $data_id
     * @return array
     */
    public function view($data_id)
    {
        return AgendaService::detalhesDataAdmin($data_id);
    }

    /**
     * Atualiza as datas
     *
     * @param UpdateDataAgendaRequest $request
     * @return array
     */
    public function update(UpdateDataAgendaRequest $request)
    {
        // Recupera os dados da agenda
        $agenda = AgendaServico::find($request->get('agenda_id'));

        // Atualiza as datas na agenda
        $result = AgendaService::updateDatasAgenda($agenda, $request->all());

        if($result['update']) {
            return $this->autoResponseJson(true, "Datas atualizadas", $result['message']);
        }

        return $this->autoResponseJson(false, "Datas não atualizadas", $result['message']);
    }

    /**
     * Atualiza uma data no administrativo
     *
     * @param Request $request
     * @return array
     */
    public function updateOne(Request $request)
    {
        $result = AgendaService::atualizarDataAdmin($request->all());

        if($result['update']) {
            return $this->autoResponseJson(true, "Data atualizada", "As informações sobre a data foram salvas com sucesso!");
        }

        return $this->autoResponseJson(false, "Data não atualizada", "Não foi possível atualizar a data, tente novamente!");
    }

    /**
     * Remove datas agenda
     *
     * @param RemoveDataAgendaRequest $request
     * @return array
     */
    public function remove(RemoveDataAgendaRequest $request)
    {
        // Recupera os dados da agenda
        $agenda = AgendaServico::find($request->get('agenda_id'));

        // Atualiza as datas na agenda
        $result = AgendaService::removeDatasAgenda($agenda, $request->all());

        if($result['remove']) {
            return $this->autoResponseJson(true, "Datas removidas", "A disponibilidade das datas foram reduzidas a zero!");
        }

        return $this->autoResponseJson(false, "Datas não removidas", "Não foi possível alterar a disponibilidade, tente novamente");
    }
}
