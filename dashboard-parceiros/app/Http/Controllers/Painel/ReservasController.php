<?php namespace App\Http\Controllers\Painel;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\StatusReservaEnum;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Services\FornecedorService;

/**
 * Class ReservasController
 * @package App\Http\Controllers\Painel
 */
class ReservasController extends Controller
{
    /**
     * Reservas do fornecedor
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Fornecedor id do usuario
        $fornecedor_id = auth()->user()->fornecedor_id;

        // Datas para pesquisa
        $periodo_pesquisa = periodoPesquisa();

        // Preset do filtro selecionado
        $filtro_selecionado = request()->get('periodo', 'ultimos_7');

        // Data de inicio do filtro
        $pp_start = request()->get('inicio', $periodo_pesquisa[$filtro_selecionado]['start'] ?? null);

        // Data final do filtro
        $pp_end = request()->get('final', $periodo_pesquisa[$filtro_selecionado]['end'] ?? null);

        // Para as datas que estão na URl
        if(! is_object($pp_start) || ! is_object($pp_end)) {
            // Converte as datas para a Carbon
            $pp_start = Carbon::createFromFormat('d-m-Y H:i:s', $pp_start . " 00:00:00");
            // Converte as datas para a Carbon
            $pp_end = Carbon::createFromFormat('d-m-Y H:i:s', $pp_end . " 23:59:59");
        }

        // Recupera as reservas do fornecedor
        $dados_vendas = $this->reservasFornecedor($fornecedor_id, $pp_start, $pp_end);

        $enum_cancelada = StatusReservaEnum::CANCELADO;

        return view('paginas.vendas.reservas', compact(
            'periodo_pesquisa',
            'filtro_selecionado',
            'pp_start',
            'pp_end',
            'dados_vendas',
            'enum_cancelada'
        ));
    }

    /**
     * Detalhes da comissao
     *
     * @param $voucher_id
     * @return mixed
     */
    public function view($voucher_id)
    {
        // Fornecedor id do usuario
        $fornecedor_id = auth()->user()->fornecedor_id;

        // Recupera a reserva
        $reserva = ReservaPedido::with([
            'pedido.cliente',
            'servico.fotoPrincipal',
            'agendaDataServico',
            'historicoReservaPedido',
            'campoAdicionalReservaPedido',
            'dadoClienteReservaPedido',
            'quantidadeReserva'
        ])->where([
            'voucher' => $voucher_id,
            'fornecedor_id' => $fornecedor_id
        ])->first();

        // Caso nao encontre a reserva
        if(is_null($reserva)) return redirect()->route('app.reservas.index');

        // Enums da reserva
        $enum_cancelada = StatusReservaEnum::CANCELADO;
        $enum_ativa = StatusReservaEnum::ATIVA;
        $enum_utilizado = StatusReservaEnum::UTILIZADO;

        // Quantidade de campos adicionais do servico
        $qtd_campos_adicionais = $reserva->campoAdicionalReservaPedido->count();

        // Numero de colunas no layout
        $colunas_adicionais = ($qtd_campos_adicionais >= 4) ? 4 : [0 => 0, 1 => 12, 2 => 6, 3 => 4][$qtd_campos_adicionais];

        return view('paginas.vendas.detalhes-reserva', compact(
            'reserva',
            'enum_cancelada',
            'enum_ativa',
            'enum_utilizado',
            'qtd_campos_adicionais',
            'colunas_adicionais'
        ));
    }

    /**
     * Dados para a listagem das vendas
     *
     * @param $fornecedor
     * @param $pp_start
     * @param $pp_end
     * @return array
     */
    private function reservasFornecedor($fornecedor, $pp_start, $pp_end)
    {
        $return = [
            'valor_vendido' => 0, 'valor_estornado' => 0, 'quantidade' => 0,
        ];

        // Reservas do canal de venda
        $return['reservas'] = FornecedorService::reservasFornecedor($fornecedor, $pp_start, $pp_end);

        // Soma os valores das reservas
        foreach ($return['reservas'] as $reserva) {
            // Verifica se a reserva esta valida
            if(in_array($reserva->status, StatusReservaEnum::RESERVAS_VALIDAS)) {
                $return['valor_vendido'] += $reserva->valor_net;
                $return['quantidade'] += $reserva->quantidade;
            }
            // Verifica se a reserva esta cancelada
            if(in_array($reserva->status, [StatusReservaEnum::CANCELADO])) {
                $return['valor_estornado'] += $reserva->valor_net;
            }
        }

        return $return;
    }

    /**
     * Pesquisa a reserva pelo codigo
     *
     * @return array
     */
    public function searchReserva()
    {
        // Fornecedor id do usuario
        $fornecedor_id = auth()->user()->fornecedor_id;

        // Pesquisa
        $search = mb_strtolower(request()->get('q'));

        // Busca a reserva
        $reservas = FornecedorService::searchReservaFornecedor($search, $fornecedor_id);

        return [
            'reservas' => $reservas,
            'view' => route('app.reservas.view')
        ];
    }

    /**
     * Pesquisa a reserva pelo cliente
     *
     * @return array
     */
    public function searchCliente()
    {
        // Fornecedor id do usuario
        $fornecedor_id = auth()->user()->fornecedor_id;

        // Pesquisa
        $search = mb_strtolower(request()->get('q'));

        // CPF formatado
        $cpf = mask(preg_replace("/[^0-9]/", "", $search), "###.###.###-##");

        // Para evitar cpf vazio
        $cpf = (strlen($cpf)) ? $cpf : $search;

        // Busca a reserva
        $reservas = ReservaPedido::with([
            'servico' => function($q) {
                return $q->select(['id', 'nome']);
            },
        ])->whereHas('pedido.cliente', function ($q) use($search, $cpf) {
            return $q->whereLike(['email', 'nome'], $search)->orWhere('cpf', "LIKE", "%$cpf%");
        })->where('fornecedor_id', $fornecedor_id)
            ->latest()->limit(10)->get(['id', 'servico_id', 'voucher', 'quantidade']);

        return [
            'reservas' => $reservas,
            'view' => route('app.reservas.view')
        ];
    }

    /**
     * POST para autenticar a reserva
     *
     * @param Request $request
     * @return array
     */
    public function validar(Request $request)
    {
        // Fornecedor id do usuario
        $user = auth()->user();

        // Recupera os dados da reserva
        $reserva = ReservaPedido::with('validacao')->where([
            'fornecedor_id' => $user->fornecedor_id,
            'id' => $request->get('reserva')
        ])->first();

        // Verifica se encontrou a reserva
        if(is_object($reserva)) {

            // Verifica se já nao foi validada
            if(is_null($reserva->validacao)) {

                // Coloca a reserva como utilizada
                $reserva->update(['status' => StatusReservaEnum::UTILIZADO]);

                // Salva a validacao da reserva
                $reserva->validacao()->create([
                    "pedido_id" => $reserva->pedido_id,
                    "validado" => Carbon::now(),
                    "observacoes" => [
                        'usuario_id' => $user->id,
                        'usuario' => $user->nome,
                        'ip_user' => getUserIP(),
                    ],
                ]);

                return $this->autoResponseJson(true, "Reserva autenticada", "A reserva foi autenticada sucesso!");
            }

            return $this->autoResponseJson(false, "Reserva já autenticada", "Essa reserva já foi autenticada!");
        }

        return $this->autoResponseJson(false, "Reserva não localizada", "Não foi possível localizar a reserva informada!");
    }
}
