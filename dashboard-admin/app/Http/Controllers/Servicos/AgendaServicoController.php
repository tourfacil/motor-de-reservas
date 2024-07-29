<?php namespace App\Http\Controllers\Servicos;

use App\Http\Requests\Servico\StoreAgendaServicoRequest;
use App\Http\Requests\Servico\UpdateAgendaServicoRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\AgendaEnum;
use TourFacil\Core\Models\AgendaServico;
use TourFacil\Core\Models\Servico;

/**
 * Class AgendaServicoController
 * @package App\Http\Controllers\Servicos
 */
class AgendaServicoController extends Controller
{
    /**
     * Listagem das agendas
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Agenda serviços
        $servicos = Servico::with([
            'agendaServico', 'destino',
            'fornecedor' => function($q) {
                return $q->select(['id', 'nome_fantasia']);
            }
        ])->whereHas('agendaServico')->where('canal_venda_id', $canal_venda->id)->get([
            'id', 'fornecedor_id', 'destino_id', 'nome'
        ]);

        // Enum para agenda sem disponibilidade
        $sem_disponibilidade = AgendaEnum::SEM_DISPONIBILIDADE;

        // Enum para agenda com disponibilidade
        $com_disponivel = AgendaEnum::COM_DISPONIBILIDADE;

        // Enum para agenda com baixa disponibilidade
        $baixa_disponibilidade = AgendaEnum::BAIXA_DISPONIBILIDADE;

        return view('paginas.servicos.agenda.agenda-servico', compact(
            'canal_venda',
            'servicos',
            'sem_disponibilidade',
            'com_disponivel',
            'baixa_disponibilidade'
        ));
    }

    /**
     * View para detalhes da agenda
     *
     * @param $agenda_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($agenda_id)
    {
        // Recupera a agenda
        $agenda = AgendaServico::with('servicos.canalVenda', 'servicos.destino')->findOrFail($agenda_id);

        // Enum agenda comparilhada
        $compartilha_agenda = AgendaEnum::COMPARTILHA;

        // Enum agenda nao compartilhada
        $nao_compartilha = AgendaEnum::NAO_COMPARTILHA;

        // Dias da semana
        $dias_semanas = AgendaEnum::DIAS_SEMANA;

        // Disponibilidade baixa informada na agenda
        $dispo_baixa = $agenda->disponibilidade_minima;

        // Disponibilidade media e dividido por 2 e somado a baixa
        $dispo_media = (int) ($dispo_baixa / 2 + $dispo_baixa);

        // Disponibilidade normal
        $dispo_normal = $dispo_media + 1;

        // Alteracoes na agenda
        $e_substituicao_agenda = AgendaEnum::SUBSTITUICOES_AGENDA;

        // Substituicoes na agenda
        $substituicoes_agenda = $agenda->substituicoes_agenda;

        return view('paginas.servicos.agenda.detalhes-agenda', compact(
            'agenda',
            'compartilha_agenda',
            'nao_compartilha',
            'dias_semanas',
            'dispo_media',
            'dispo_normal',
            'dispo_baixa',
            'e_substituicao_agenda',
            'substituicoes_agenda'
        ));
    }

    /**
     * View para cadastrar uma nova agenda
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Enum agenda comparilhada
        $compartilha_agenda = AgendaEnum::COMPARTILHA;

        // Enum agenda nao compartilhada
        $nao_compartilha = AgendaEnum::NAO_COMPARTILHA;

        // Serviços que nao possuem agenda
        $servicos_sem_agenda = Servico::whereDoesntHave('agendaServico')
            ->where('canal_venda_id', $canal_venda->id)->get();

        // Agendas compartilhadas
        $agendas_compartilhadas = AgendaServico::with('servicos')
            ->where('compartilhada', $compartilha_agenda)->get();

        // Dias da semana
        $dias_semanas = AgendaEnum::DIAS_SEMANA;

        return view('paginas.servicos.agenda.nova-agenda', compact(
            'canal_venda',
            'servicos_sem_agenda',
            'compartilha_agenda',
            'nao_compartilha',
            'agendas_compartilhadas',
            'dias_semanas'
        ));
    }

    /**
     * Cria uma nova agenda ou amarra um servico a outra agenda
     *
     * @param StoreAgendaServicoRequest $request
     * @return array
     */
    public function store(StoreAgendaServicoRequest $request)
    {
        // Recupera o servico
        $servico = Servico::with('agendaServico')->find($request->get('servico_id'));

        // Id da agenda para ligar ao servico
        $create_agenda = $request->get('agenda_id');

        // Caso o serviço já possua agenda
        if($servico->agendaServico->count() > 0) {
            return $this->autoResponseJson(false, 'O serviço já possui agenda', 'Este serviço já possui uma agenda ativa!');
        }

        // Caso for para criar a agenda
        if(is_null($create_agenda)) {

            // Cria uma nova agenda para o servico
            $new_agenda = AgendaServico::create([
                'disponibilidade_minima' => $request->get('disponibilidade_minima'),
                'compartilhada' => $request->get('compartilhada'),
                'dias_semana' => $request->get('dias_semana'),
                'status' => AgendaEnum::SEM_DISPONIBILIDADE,
            ]);

            // Coloca o servico na agenda
            $new_agenda->servicos()->attach($servico->id);

            // Caso criou a agenda
            if($new_agenda) {
                return $this->autoResponseJson(true, 'Agenda criada', 'A agenda foi criada com sucesso', route('app.agenda.view', $new_agenda->id));
            }

            return $this->autoResponseJson(false, 'Falha ao criar a agenda', 'Não foi possível criar a agenda, tente novamente!');
        }

        // Recupera a agenda selecionada
        $agenda = AgendaServico::find($create_agenda);

        // Coloca o servico na agenda
        $agenda->servicos()->attach($servico->id);

        return $this->autoResponseJson(true, 'Agendas compartilhadas', "A agenda foi compartilhada com {$servico->nome}", route('app.agenda.view', $agenda->id));
    }

    /**
     * Atualiza as configurações da agenda
     *
     * @param UpdateAgendaServicoRequest $request
     * @return array
     */
    public function update(UpdateAgendaServicoRequest $request)
    {
        // Recupera a agenda selecionada
        $agenda = AgendaServico::find($request->get('agenda_id'));

        // Atualiza os dados da agenda
        $update = $agenda->update($request->all());

        // Caso atualizou
        if($update) {
            return $this->autoResponseJson(true, 'Agenda atualizada', 'As configurações da agenda foram salvas com sucesso!');
        }

        return $this->autoResponseJson(true, 'Agenda não atualizada', 'Não foi possível atualizar as configurações da agenda, tente novamente!');
    }
}
