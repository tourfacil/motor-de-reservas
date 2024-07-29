<?php namespace App\Http\Controllers\Servicos;

use Illuminate\Http\Request;
use App\Http\Requests\Servico\StoreSubstituicaoAgendaRequest;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\AgendaEnum;
use TourFacil\Core\Models\AgendaServico;

/**
 * Class SubstituicoesAgendaController
 * @package App\Http\Controllers\Servicos
 */
class SubstituicoesAgendaController extends Controller
{
    /**
     * Detalhes de uma substituicao
     *
     * @param $agenda_id
     * @return array
     */
    public function view($agenda_id)
    {
        // Recupera os dados da agenda
        $agenda = AgendaServico::find($agenda_id);

        // Tipo da alteracao
        $tipo = request()->get('tipo');

        // Index da alteracao
        $index = request()->get('index');

        // Substituicoes na agenda
        $substituicoes = $agenda->substituicoes_agenda;

        return [
            'tipo' => $tipo,
            'from' => $index,
            'agenda_id' => $agenda->id,
            'tipo_alteracao' => AgendaEnum::SUBSTITUICOES_AGENDA[$tipo],
            'to' => $substituicoes[$tipo][$index]
        ];
    }

    /**
     * Cadastra uma nova substituicao na agenda
     *
     * @param StoreSubstituicaoAgendaRequest $request
     * @return array
     */
    public function store(StoreSubstituicaoAgendaRequest $request)
    {
        // Recupera os dados da agenda
        $agenda = AgendaServico::find($request->get('agenda_id'));

        // Tipo de alteracao
        $tipo_substituicao = $request->get('tipo_alteracao');

        // Substituicoes na agenda
        $substituicoes = $agenda->substituicoes_agenda;

        // Configuracoes da agenda
        $configuracoes_agenda = $agenda->configuracoes;

        // Valores
        $from = str_replace(",", ".", str_replace(".", "", $request->get('from')));
        $to = str_replace(",", ".", str_replace(".", "", $request->get('to')));

        // Verifica se já existe modificacao do mesmo tipo
        if(isset($substituicoes[$tipo_substituicao])) {
            // Verifica se existe alteracao do mesmo valor
            if(!isset($substituicoes[$tipo_substituicao][$from])) {
                $substituicoes[$tipo_substituicao][$from] = $to;
            } else {
                return $this->autoResponseJson(false, 'Substituição duplicada', 'Já existe uma substituição para este valor, tente novamente!');
            }
        } else {
            // Coloca uma nova modificacao para o tipo
            $substituicoes[$tipo_substituicao] = [$from => $to];
        }

        // Atualiza as substituicoes da agenda
        $configuracoes_agenda[AgendaEnum::SUBSTITUICAO_AGENDA] = $substituicoes;

        $update = $agenda->update(['configuracoes' => $configuracoes_agenda]);

        if($update) {
            return $this->autoResponseJson(true, 'Substituição cadastrada', 'A substituição na agenda foi cadastrada com sucesso!');
        }

        return $this->autoResponseJson(false, 'Substituição não cadastrada', 'Não foi possível cadastrar a substituição na agenda, tente novamente!');
    }

    /**
     * Atualiza ou exclui uma substituição na agenda
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        // Recupera os dados da agenda
        $agenda = AgendaServico::find($request->get('agenda_id'));

        // Tipo da alteracao
        $tipo = request()->get('tipo_alteracao');

        // Index da alteracao
        $index = request()->get('index');

        // Valores
        $from = str_replace(",", ".", str_replace(".", "", $request->get('from')));
        $to = str_replace(",", ".", str_replace(".", "", $request->get('to')));

        // Substituicoes na agenda
        $substituicoes = $agenda->substituicoes_agenda;

        // Configuracoes da agenda
        $configuracoes_agenda = $agenda->configuracoes;

        // Apaga a substituicao anterior
        unset($substituicoes[$tipo][$index]);

        // Caso seja para deletar a substituicao
        if($request->get('delete_substituicao') == "on") {

            // Retira o tipo de alteração caso nao tenha mais
            if(sizeof($substituicoes[$tipo]) == 0) {
                unset($substituicoes[$tipo]);
            }

            // Array com as substituicoes da agenda
            $configuracoes_agenda[AgendaEnum::SUBSTITUICAO_AGENDA] = $substituicoes;

            // Caso nao possua mais alteracoes
            if(sizeof($substituicoes) == 0) {
                $configuracoes_agenda = null;
            }

            // Atualiza a agenda
            $update = $agenda->update(['configuracoes' => $configuracoes_agenda]);

            if($update) {
                return $this->autoResponseJson(true, "Substituição excluida", "A substituição da agenda foi exculida com sucesso!");
            }

            return $this->autoResponseJson(false, "Não foi possível excluir", "Não foi possível excluir a substituição da agenda, tente novamente!");
        }

        // Cria uma nova casa com a substituição
        $substituicoes[$tipo][$from] = $to;

        // Atualiza as substituicoes da agenda
        $configuracoes_agenda[AgendaEnum::SUBSTITUICAO_AGENDA] = $substituicoes;

        // Atualiza a agenda
        $update = $agenda->update(['configuracoes' => $configuracoes_agenda]);

        if($update) {
            return $this->autoResponseJson(true, "Substituição atualizada", "A substituição da agenda foi atualizada com sucesso!");
        }

        return $this->autoResponseJson(false, "Não foi possível atualizar", "Não foi possível atualizar a substituição da agenda, tente novamente!");
    }
}
