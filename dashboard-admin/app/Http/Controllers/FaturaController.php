<?php

namespace App\Http\Controllers;

use App\Enum\LogoCanalVendaEnum;
use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use Illuminate\Http\Request;
use PDF;
use TourFacil\Core\Enum\Faturas\StatusFaturaEnum;
use TourFacil\Core\Models\Fatura;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Services\FaturaService;
use Carbon\Carbon;

class FaturaController extends Controller
{
    /**
     * Middleware que joga todos os usuários que nã forem admin para fora do menu faturas
     * Summary of __construct
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (!userIsAdmin()) {
                return redirect()->route('app.dashboard');
            }

            return $next($request);
        });
    }

    /**
     * Summary of index
     * Tela que lista todas as faturas
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $faturas = Fatura::all();

        $dados = [
            'faturas' => $faturas,
        ];

        return view('paginas.faturas.faturas', $dados);
    }

    /**
     * Summary of show
     * Tela que mostra uma fatura
     *
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request)
    {
        $fatura_id = $request->get('fatura_id');

        $fatura = Fatura::find($fatura_id);

        if (!$fatura) {
            return redirect()->route('app.faturas.index');
        }

        $dados = [
            'fatura' => $fatura
        ];

        $tipo_relatorio = $request->get('tipo_relatorio');

        if ($tipo_relatorio == 'PDF') {


            $view_name = "paginas.faturas.download.fatura-pdf";
            $dados['inline_pdf'] = true;
            $dados['logo_path'] = LogoCanalVendaEnum::LOGOS[1];

            if ($dados['inline_pdf']) {
                return PDF::loadView($view_name, $dados)
                    ->setOptions([
                        'orientation' => 'landscape',
                        'margin-bottom' => 0,
                        'margin-left' => 0,
                        'margin-right' => 0,
                        'margin-top' => 0,
                    ])
                    ->inline("Fatura-{$fatura->id}.pdf");
            } else {
                return view($view_name, $dados);
            }
        }

        if ($tipo_relatorio == 'XLSX') {

            $view_name = "paginas.faturas.download.fatura-xlsx";

            return (new RelatorioVendasTerminaisExport($view_name, $dados))
                ->download("Fatura-{$fatura->id}.xlsx");
        }

        return view('paginas.faturas.fatura', $dados);
    }

    /**
     * Summary of pendenteAprovacao
     * Tela que mostra as faturas que dependem de aprovação
     * @param Request $request
     * @return mixed
     */
    public function pendenteAprovacao(Request $request)
    {
        $faturas = Fatura::where('status', StatusFaturaEnum::PENDENTE_APROVACAO)
            ->with(['reservas'])
            ->get();

        $dados = [
            'faturas' => $faturas,
        ];

        return view('paginas.faturas.faturas-pendente-aprovacao', $dados);
    }

    /**
     *  Tela mostra as faturas que estão com pagamento pendente
     * @param Request $request
     * @return mixed
     */
    public function pendentePagamento(Request $request)
    {
        $query = Fatura::where('status', StatusFaturaEnum::PENDENTE_PAGAMENTO)
            ->with(['reservas']);

        if ($request->filled('fornecedor_id')) {
            $query->whereHas('fornecedor', function ($query) use ($request) {
                $query->where('id', $request->fornecedor_id);
            });
        }

        $faturas = $query->get();

        $fornecedores = Fornecedor::orderBy('nome_fantasia', 'asc')->get();

        $dados = [
            'faturas' => $faturas,
            'fornecedores' => $fornecedores,
        ];

        $tipo_relatorio = $request->get('tipo_relatorio');

        if ($tipo_relatorio == 'PDF') {

            $view_name = "paginas.faturas.download.faturas-pendente-pagamento-pdf";
            $dados['inline_pdf'] = false;
            $dados['logo_path'] = LogoCanalVendaEnum::LOGOS[1];

            if ($dados['inline_pdf']) {
                return PDF::loadView($view_name, $dados)
                    ->setOptions([
                        'orientation' => 'landscape',
                    ])
                    ->inline("Fatura-previsao.pdf");
            } else {
                return view($view_name, $dados);
            }
        }

        if ($tipo_relatorio == 'XLSX') {

            $view_name = "paginas.faturas.download.faturas-pendente-pagamento-xlsx";

            return (new RelatorioVendasTerminaisExport($view_name, $dados))
                ->download("Fatura-previsao.xlsx");
        }

        return view('paginas.faturas.faturas-pendente-pagamento', $dados);
    }

    /**
     * Summary of setFaturaPaga
     * Método usado quando apertamos o botão de pagar a fatura
     * @param Request $request
     * @return mixed
     */
    public function setFaturaPaga(Request $request)
    {
        $fatura_id = $request->get('fatura_id');

        $fatura = Fatura::find($fatura_id);

        if ($fatura != null) {

            $fatura->update(['status' => StatusFaturaEnum::PAGA]);
        }

        return response(['status' => true], 200);
    }

    /**
     * Summary of faturasPrevistas
     * Método que mostra as faturas previstas
     * @param Request $request
     * @return mixed
     */
    public function faturasPrevistas(Request $request)
    {

        $inicio = $request->get('inicio', Carbon::today()->addMonths(1)->startOfMonth());
        $final = $request->get('final', Carbon::today()->addMonths(1)->endOfMonth());

        $inicio = Carbon::parse($inicio);
        $final = Carbon::parse($final);

        $fatura_service = new FaturaService();

        $faturas = collect($fatura_service->previsaoDeFaturaFornecedores($inicio, $final));

        $dados = [
            'faturas' => $faturas,
            'inicio' => $inicio,
            'final' => $final
        ];

        return view('paginas.faturas.faturas-previsao', $dados);
    }

    /**
     * Summary of faturaPrevista
     * $método que mostra uma fatura prevista
     * @param Request $request
     * @return mixed
     */
    public function faturaPrevista(Request $request)
    {

        $fornecedor = Fornecedor::find($request->get('fornecedor'));
        $inicio = $request->get('inicio');
        $final = $request->get('final');

        if ($inicio && $final && $fornecedor) {

            $inicio = Carbon::parse($inicio);
            $final = Carbon::parse($final);
        } else {
            return redirect()->route('app.faturas.index');
        }

        $fatura_service = new FaturaService();
        $reservas = $fatura_service->getReservasFornecedorPorPeriodo($fornecedor, $inicio, $final);

        $dados = [
            'inicio' => $inicio,
            'final' => $final,
            'fornecedor' => $fornecedor,
            'reservas' => $reservas
        ];

        $tipo_relatorio = $request->get('tipo');


        if ($tipo_relatorio == 'PDF') {

            $view_name = "paginas.faturas.download.fatura-previsao-pdf";
            $dados['inline_pdf'] = true;
            $dados['logo_path'] = LogoCanalVendaEnum::LOGOS[1];

            if ($dados['inline_pdf']) {
                return PDF::loadView($view_name, $dados)
                    ->setOptions([
                        'orientation' => 'landscape',
                    ])
                    ->inline("Fatura-previsao.pdf");
            } else {
                return view($view_name, $dados);
            }
        }

        if ($tipo_relatorio == 'XLSX') {

            $view_name = "paginas.faturas.download.fatura-previsao-xlsx";

            return (new RelatorioVendasTerminaisExport($view_name, $dados))
                ->download("Fatura-previsao.xlsx");
        }

        return view('paginas.faturas.fatura-previsao', $dados);
    }
}
