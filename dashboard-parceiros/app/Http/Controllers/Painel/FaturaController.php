<?php

namespace App\Http\Controllers\Painel;

use App\Enum\LogoCanalVendaEnum;
use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use TourFacil\Core\Models\Fatura;
use TourFacil\Core\Services\FaturaService;

class FaturaController extends Controller
{
    public function index(Request $request)
    {
        $faturas = Fatura::where('fornecedor_id', fornecedor()->id)->get();

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

        if(!$fatura) {
            return redirect()->route('app.faturas.index');
        }

        if($fatura->fornecedor_id != fornecedor()->id) {
            return redirect()->route('app.faturas.index');
        }

        $dados = [
            'fatura' => $fatura
        ];

        $tipo_relatorio = $request->get('tipo_relatorio');

        if($tipo_relatorio == 'PDF') {


            $view_name = "paginas.faturas.download.fatura-pdf";
            $dados['inline_pdf'] = true;
            $dados['logo_path'] = LogoCanalVendaEnum::LOGOS[1];

            if($dados['inline_pdf']) {
                return \PDF::loadView($view_name, $dados)
                    ->setOptions([
                        'orientation' =>'landscape',
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

        if($tipo_relatorio == 'XLSX') {

            $view_name = "paginas.faturas.download.fatura-xlsx";

            return (new RelatorioVendasTerminaisExport($view_name, $dados))
                ->download("Fatura-{$fatura->id}.xlsx");

        }

        return view('paginas.faturas.fatura', $dados);
    }

    public function faturasPrevistas(Request $request)
    {
        $inicio = $request->get('inicio', Carbon::today()->addMonths(1)->startOfMonth());
        $final = $request->get('final', Carbon::today()->addMonths(1)->endOfMonth());

        $inicio = Carbon::parse($inicio);
        $final = Carbon::parse($final);

        $fatura_service = new FaturaService();

        $faturas = collect($fatura_service->previsaoDeFaturaFornecedor(fornecedor(), $inicio, $final));

        $dados = [
            'faturas' => $faturas,
            'inicio' => $inicio,
            'final' => $final
        ];

        return view('paginas.faturas.faturas-previsao', $dados);
    }

    public function faturaPrevista(Request $request)
    {

        $fornecedor = fornecedor();
        $inicio = $request->get('inicio');
        $final = $request->get('final');

        if($inicio && $final && $fornecedor) {

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


        if($tipo_relatorio == 'PDF') {

            $view_name = "paginas.faturas.download.fatura-previsao-pdf";
            $dados['inline_pdf'] = true;
            $dados['logo_path'] = LogoCanalVendaEnum::LOGOS[1];

            if($dados['inline_pdf']) {
                return \PDF::loadView($view_name, $dados)
                    ->setOptions([
                        'orientation' =>'landscape',
                    ])
                    ->inline("Fatura-previsao.pdf");
            } else {
                return view($view_name, $dados);
            }
        }

        if($tipo_relatorio == 'XLSX') {

            $view_name = "paginas.faturas.download.fatura-previsao-xlsx";

            return (new RelatorioVendasTerminaisExport($view_name, $dados))
                ->download("Fatura-previsao.xlsx");

        }

        return view('paginas.faturas.fatura-previsao', $dados);
    }
}
