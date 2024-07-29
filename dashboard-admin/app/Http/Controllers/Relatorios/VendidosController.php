<?php namespace App\Http\Controllers\Relatorios;

use App\Enum\LogoCanalVendaEnum;
use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use PDF;
use TourFacil\Core\Enum\StatusReservaEnum;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Services\Relatorios\IngressosVendidoService;
use TourFacil\Core\Services\Relatorios\ReservasAutenticadaService;

/**
 * Class VendidosController
 * @package App\Http\Controllers\Relatorios
 */
class VendidosController extends Controller
{
    /**
     * Lista dos ingressos vendidos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Fornecedor id na URL
        $fornecedor_id = request()->get('fornecedor', 1);

        // Mes de pesquisa
        $mes_pesquisa = request()->get('periodo', date('m-Y'));

        // Servicos na URL
        $servicos_url = request()->get('servicos');

        $tipo_data = request()->get('tipo_data');

        $tipo_relatorio = request()->get('tipo_relatorio', 'WEB');

        // Datas para pesquisa
        if($mes_pesquisa == "custom") {
            $pp_start = Carbon::createFromFormat("d-m-Y", request()->get('inicio'))->startOfDay();
            $pp_end = Carbon::createFromFormat("d-m-Y", request()->get('final'))->endOfDay();
        } else {
            $pp_start = Carbon::createFromFormat("d-m-Y", "01-$mes_pesquisa")->startOfDay();
            $pp_end = $pp_start->copy()->endOfMonth();
        }

        // Lista de fornecedores
        $fornecedores = Fornecedor::select(['id', 'nome_fantasia'])->orderBy('nome_fantasia')->get();

        // Fornecedor atual
        $fornecedor = $fornecedores->first(function ($fornecedor) use ($fornecedor_id) {
            return ($fornecedor->id == $fornecedor_id);
        });

        // Recupera as reservas do fornecedor
        $reservas = IngressosVendidoService::relatorioVendidoFornecedor($fornecedor->id, $pp_start, $pp_end, $canal_venda->id, $servicos_url, [], false, $tipo_data);

        // Soma os valores totais das reservas
        $valores_reservas = $this->somarValoresVendidos($reservas);

        // Soma dos valores
        $total_vendido = $valores_reservas['vendido'];
        $total_net = $valores_reservas['net'];
        $total_quantidade = $valores_reservas['quantidade'];

        $dados = [
            'pp_start' => $pp_start,
            'pp_end' => $pp_end,
            'reservas' => $reservas,
            'fornecedor' => $fornecedor,
            'total_vendido' => $total_vendido,
            'total_net' => $total_net,
            'total_quantidade' => $total_quantidade,
            'fornecedores' => $fornecedores,
            'canal_venda' => $canal_venda,
            'servicos_url' => $servicos_url,
            'tipo_data' => $tipo_data,
        ];

        if($tipo_relatorio == 'PDF') {

            $view_name = "paginas.relatorios.download.vendidos-pdf";
            $dados['inline_pdf'] = true;
            $dados['logo_path'] = LogoCanalVendaEnum::LOGOS[$canal_venda->id];

            return PDF::loadView($view_name, $dados)
            ->inline("Vendidos {$fornecedor->nome_fantasia} - {$canal_venda->nome}.pdf");

        } else if($tipo_relatorio == 'XLS') {

            $view_name = "paginas.relatorios.download.vendidos-xls";
             
            return (new RelatorioVendasTerminaisExport($view_name, $dados))
            ->download("Vendidos {$fornecedor->nome_fantasia} - {$canal_venda->nome}.xlsx");    

        } else {
            
            return view('paginas.relatorios.vendidos', $dados);

        }
    }


    public function index2(Request $request)
    {

        // Pega as datas inicial e final de vendas
        $data_inicial_venda = $request->get('data_inicial_venda');
        $data_final_venda = $request->get('data_final_venda');

        // Pega as datas inicial e final de utilização
        $data_inicial_utilizacao = $request->get('data_inicial_utilizacao');
        $data_final_utilizacao = $request->get('data_final_utilizacao');



        // Caso sejam informadas datas de venda, converte para Carbon
        if($data_inicial_venda && $data_final_venda) {
            $data_inicial_venda = Carbon::parse($data_inicial_venda);
            $data_final_venda = Carbon::parse($data_final_venda);
        }

        // Caso sejam informadas datas de utilização, converte para Carbon
        if($data_inicial_utilizacao && $data_final_utilizacao) {
            $data_inicial_utilizacao = Carbon::parse($data_inicial_utilizacao);
            $data_final_utilizacao = Carbon::parse($data_final_utilizacao);
        }

        $sem_data_venda = $request->get('sem_data_venda', false);

        if($sem_data_venda != 'true') {
            // Caso não seja informada nenhuma data, assume o mes atual por vendas
            if((!$data_inicial_venda && !$data_final_venda && !$data_inicial_utilizacao && !$data_final_utilizacao)) {
                $data_inicial_venda = Carbon::now()->startOfMonth();
                $data_final_venda = Carbon::now()->endOfMonth();
            }
        }

        // Busca as variaveis de servico, fornecedor e status
        $fornecedor_id = $request->get('fornecedor_id');
        $servico_id = $request->get('servico_id');
        $status = $request->get('status');

        // Inicia a query para buscar as reservas, já com serviço e agenda carregada
        $reservas = ReservaPedido::with(['servico', 'agendaDataServico']);

        // Caso tenha data de venda, filtra
        if($data_inicial_venda && $data_final_venda) {
            $data_final_venda_temp = new Carbon($data_final_venda);
            $reservas->where('created_at', '>=', $data_inicial_venda);
            $reservas->where('created_at', '<=', $data_final_venda_temp->addDays(1));
        }

        // Caso tenha data de utilização, filtra
        if($data_inicial_utilizacao && $data_final_utilizacao) {
            $reservas->whereHas('agendaDataServico', function ($agenda) use ($data_inicial_utilizacao, $data_final_utilizacao) {
                $agenda->where('data', '>=', $data_inicial_utilizacao);
                $agenda->where('data', '<=', $data_final_utilizacao);
            });
        }

        // Caso tenha filtro de fornecedor, filtra
        if($fornecedor_id) {
            $reservas->whereHas('servico.fornecedor', function ($fornecedor) use ($fornecedor_id) {
                $fornecedor->where('id', $fornecedor_id);
            });
        }

        // Caso tenha filtro de serviço, filtra
        if($servico_id) {
            $reservas->whereHas('servico', function ($servico) use ($servico_id) {
                $servico->where('id', $servico_id);
            });
        }

        // Caso tenha status, filtra
        if($status) {
            $reservas->whereIn('status', explode(',', $status));
        } else {
            $status = "";
        }
        
        // Busca todos os fornecedores para colocar no selec do filtro
        $fornecedores = Fornecedor::all();
        $servicos = collect([]);

        // Caso o fornecedor esteja selecionado no filtro, busca seus serviços
        if($fornecedor_id) {
            $servicos = Servico::where('fornecedor_id', $fornecedor_id)
                ->where('status', 'ATIVO')
                ->get();
        }

        $status_reservas = StatusReservaEnum::STATUS;
        
        // Cria um array com os dados que serão enviados para a view, XLSX ou PDF
        $dados = [
            'reservas' => $reservas->get(),
            'valor_total_venda' => $reservas->whereIn('status', ['ATIVA', 'UTILIZADO'])->sum('valor_total'),
            'valor_total_net' => $reservas->whereIn('status', ['ATIVA', 'UTILIZADO'])->sum('valor_net'),
            'quantidade_total' => $reservas->whereIn('status', ['ATIVA', 'UTILIZADO'])->sum('quantidade'),
            'quantidade_reservas' => $reservas->where('status', ['ATIVA', 'UTILIZADO'])->count(),
            'data_inicial_venda' => $data_inicial_venda,
            'data_final_venda' => $data_final_venda,
            'data_inicial_utilizacao' => $data_inicial_utilizacao,
            'data_final_utilizacao' => $data_final_utilizacao,
            'fornecedores' => $fornecedores,
            'servicos' => $servicos,
            'fornecedor_id' => $fornecedor_id,
            'servico_id' => $servico_id,
            'status' => $status,
            'status_reservas' => $status_reservas,
            'sem_data_venda' => $sem_data_venda
        ];

        $tipo_relatorio = $request->get('tipo_relatorio');

        if($tipo_relatorio == 'PDF') {

            $view_name = "paginas.relatorios.download.vendidos2-pdf";
            $dados['inline_pdf'] = true;
            $dados['logo_path'] = LogoCanalVendaEnum::LOGOS[1];

            return PDF::loadView($view_name, $dados)->setOptions(['orientation' =>'landscape'])->inline("Vendidos.pdf");
        } 

        if($tipo_relatorio == 'XLS') {

            $view_name = "paginas.relatorios.download.vendidos-xls";
             
            return (new RelatorioVendasTerminaisExport($view_name, $dados))->download("Vendidos.xlsx"); 
        }

        return view('paginas.relatorios.vendidos2', $dados);
    }

    /**
     * Soma os valores somente das reservas validas
     *
     * @param $reservas
     * @return array
     */
    public function somarValoresVendidos($reservas) {

        // Array com as variaveis do resultado
        $return = ['vendido' => 0, 'net' => 0, 'quantidade' => 0];

        // Soma os valores somente das reservas validas
        foreach ($reservas as $reserva) {
            if(in_array($reserva->status, StatusReservaEnum::RESERVAS_VALIDAS)) {
                $return['vendido'] += $reserva->valor_total;
                $return['net'] += $reserva->valor_net;
                $return['quantidade'] += $reserva->quantidade;
            }
        }

        return $return;
    }
}
