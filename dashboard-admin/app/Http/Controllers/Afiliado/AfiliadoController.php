<?php

namespace App\Http\Controllers\Afiliado;

use App\Enum\LogoCanalVendaEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\EstadosEnum;
use TourFacil\Core\Enum\StatusReservaEnum;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Models\Afiliado;
use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use TourFacil\Core\Services\AfiliadoService;

/**
 *
 */
class AfiliadoController extends Controller
{
    /**
     * Retorna a lista de todos os afiliados do site
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request) {

        $dados = [
            'afiliados' => Afiliado::all(),
        ];

        return view('paginas.afiliados.afiliados', $dados);
    }

    /**
     * Retorna a página para criar um novo afiliado
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create(Request $request) {

        $dados = [
            'estados' => EstadosEnum::ESTADOS,
        ];

        return view('paginas.afiliados.novo-afiliado', $dados);
    }

    /**
     * Rota para fazer a criação do novo afilado apos inserir os dados na view
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $dados = $request->all();
        $dados['comissao'] = 5;

        $afiliado = Afiliado::create($dados);

        return redirect()->route('app.afiliados.index');
    }

    /**
     * Página para editar os afiliados do site
     * @param Request $request
     * @param $afiliado_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Request $request, $afiliado_id) {

        $afiliado = Afiliado::find($afiliado_id);

        if($afiliado == null) {
            return redirect()->route('app.afiliados.index');
        }

        $dados = [
            'afiliado' => $afiliado,
            'estados'  => EstadosEnum::ESTADOS,
        ];

        return view('paginas.afiliados.editar-afiliado', $dados);
    }

    /**
     * Rota para salvar o afiliado que foi editado
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {

        $afiliado = Afiliado::find($request->get('afiliado_id'));

        $afiliado->update($request->all());

        return redirect()->route('app.afiliados.edit', $afiliado->id);
    }

    /**
     * Relatório de de venda de um afiliado especifico
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function relatorioAfiliado(Request $request) {

        // Recupera todos os afiliados
        $afiliados_lista = Afiliado::all();

        // Recupera informações da URL
        $afiliado_id = $request->get('afiliado_id');
        $data_inicio = $request->get('inicio');
        $data_final = $request->get('final');

        // Caso não sejam informados parametros, será enviada uma página vazia
        if($afiliado_id == null || $data_inicio == null || $data_final == null) {

            $dados = [
                'afiliado' => null,
                'data_inicio' => null,
                'data_final' => null,
                'reservas' => [],
                'afiliados_lista' => $afiliados_lista,
                'tipo_operacao' => null,
                'total_comissionado' => 0,
                'total_vendido' => 0,
                'quantidade_reservas' => 0,
            ];

            return view('paginas.afiliados.relatorio', $dados);
        }

        // Caso o usuário for afiliado ou vendedor e tentar ver o relatório de outra pessoa. Ele será redirecionado
        if(userIsVendedor() || userIsAfiliado()) {

            if(auth()->user()->afiliado_id != $request->get('afiliado_id')) {
                return redirect()->route('app.relatorios.afiliados.index');
            }
        }

        // Service faz os calculos do relatório
        $dados = AfiliadoService::relatorioAfiliado($request);
        $dados['afiliados_lista'] = $afiliados_lista;

        return view('paginas.afiliados.relatorio', $dados);
    }

    /**
     * Relatório básico de todos os afiliados
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function relatorioAfiliados(Request $request) {

        // Service faz todos os calculos necessários
        $dados = AfiliadoService::relatorioAfiliados($request);

        return view('paginas.afiliados.relatorio-afiliados', $dados);
    }

    /**
     * Download do XLS de relatório básico de todos os afiliados
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function relatorioAfiliadosDownloadXLS(Request $request) {

        // Service faz todos os calculos necessários
        $dados = AfiliadoService::relatorioAfiliados($request);

        // Retorna o download do XLS
        return (new RelatorioVendasTerminaisExport("paginas/afiliados/download/relatorio-afiliados-xls", $dados))
            ->download('Relatório Afiliados.xlsx');

    }

    /**
     * Download do PDF de relatório básico de todos os afiliados
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function relatorioAfiliadosDownloadPDF(Request $request) {
        // Service faz todos os calculos necessários
        $dados = AfiliadoService::relatorioAfiliados($request);

        // Variaveis necessarias no gerador de PDF
        $dados['inline_pdf'] = true;
        $canal_venda = canalSession()->getCanal();
        $dados['logo_path'] = LogoCanalVendaEnum::LOGOS[$canal_venda->id];

        // PDF com as vendas
        return \PDF::loadView('paginas.afiliados.download.relatorio-afiliados-pdf', $dados)
            ->inline('Relatório Afiliados.pdf');
    }

    /**
     * Retorna o Download do arquivo XLS do relatório
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function relatorioAfiliadoDownloadXLS(Request $request) {

        // Service faz os cálculos necessários
        $dados = AfiliadoService::relatorioAfiliado($request);

        // XLS com as vendas
        return (new RelatorioVendasTerminaisExport("paginas/afiliados/download/relatorio-afiliado-xls", $dados))
            ->download('Relatório de afiliado.xls');
    }

    /**
     * Retorna o download do PDF do relatório
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function relatorioAfiliadoDownloadPDF(Request $request) {

        // Service cuida dos cálculos
       $dados = AfiliadoService::relatorioAfiliado($request);

        // Informações para o Arquivo XLS
        $canal_venda = canalSession()->getCanal();
        $title = "Relatório de afiliados";
        $logo_path = LogoCanalVendaEnum::LOGOS[$canal_venda->id];
        $dados['logo_path'] = $logo_path;
        $dados['inline_pdf'] = true;

        // PDF com as vendas
        return \PDF::loadView('paginas.afiliados.download.relatorio-afiliado-pdf', $dados)
            ->inline("{$title} - {$canal_venda->nome}.pdf");
    }

    /**
     * Método responsavel por atribuir um afiliado a uma reserva de forma manual
     * Não possui muitas validações pois é um método simples e que só é usado no ADMIN pelo operacional
     * @param  mixed $request
     * @return void
     */
    public function atribuirAfiliadoReserva(Request $request) {

        if(userIsAdmin() == false) {
            return response(['status' => false, 'motivo' => 'Permissão insuficiente'], 200);
        }

        // Busca a reserva no banco de dados
        $reserva = ReservaPedido::find($request->get('reserva_id'));

        // Pega o afiliado ID da req
        $afiliado_id = $request->get('afiliado_id');

        if($afiliado_id != "0") {
            // Caso o afiliado_id seja válido diferente de zero. Ele seta o devido afiliado a reserva
            $reserva->update(['afiliado_id' => $afiliado_id]);

        } else {
             // Caso o afiliado_id seja 0. Ele seta o valor como NULLO para deixar a reserva sem afiliado
            $reserva->update(['afiliado_id' => null]);

        }

        // Retorna um STATUS DE OK
        return response(['status' => true], 200);
    }
}
