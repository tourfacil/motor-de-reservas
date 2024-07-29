<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\Descontos\TipoDescontoValor;
use TourFacil\Core\Models\Desconto;
use TourFacil\Core\Models\Servico;

class DescontoController extends Controller
{
    public function index(Request $request)
    {
        $dados = [
            'descontos' => Desconto::all(),
        ];

        return view('paginas.descontos.descontos.descontos', $dados);
    }

    public function create(Request $request)
    {
        $dados = [
            'servicos' => Servico::where('status', 'ATIVO')->get(),
        ];

        return view('paginas.descontos.descontos.novo-desconto', $dados);
    }

    public function store(Request $request)
    {
        $dados = $request->all();

        $dados['valor_de'] = $this->stringParaDouble($dados['valor_de']);
        $dados['valor_por'] = $this->stringParaDouble($dados['valor_por']);

        $dados['inicio'] = Carbon::parse($dados['inicio']);
        $dados['final'] = Carbon::parse($dados['final']);
        $dados['inicio_utilizacao'] = Carbon::parse($dados['inicio_utilizacao']);
        $dados['final_utilizacao'] = Carbon::parse($dados['final_utilizacao']);

        if($dados['tipo_desconto_valor'] == TipoDescontoValor::PERCENTUAL) {

            $dados['desconto'] = $dados['valor_percentual_venda'];
            $dados['desconto_net'] = $dados['valor_percentual_net'];

        } else {

            $dados['desconto'] = $this->stringParaDouble($dados['valor_fixo_venda']);
            $dados['desconto_net'] = $this->stringParaDouble($dados['valor_fixo_net']);

        }

        $desconto = Desconto::create($dados);

        return redirect()->route('app.descontos.desconto.index');

    }

    public function edit(Request $request)
    {
        $desconto = Desconto::find($request->get('desconto_id'));

        if($desconto == null) {
            return redirect()->route('app.descontos.desconto.index');
        }

        $dados = [
            'desconto' => $desconto,
            'servicos' => Servico::where('status', 'ATIVO')->get(),
        ];

        return view('paginas.descontos.descontos.editar-desconto', $dados);
    }

    public function update(Request $request)
    {
        $dados = $request->all();

        $desconto = Desconto::find($dados['desconto_id']);

        $dados['valor_de'] = $this->stringParaDouble($dados['valor_de']);
        $dados['valor_por'] = $this->stringParaDouble($dados['valor_por']);

        $dados['inicio'] = Carbon::parse($dados['inicio']);
        $dados['final'] = Carbon::parse($dados['final']);
        $dados['inicio_utilizacao'] = Carbon::parse($dados['inicio_utilizacao']);
        $dados['final_utilizacao'] = Carbon::parse($dados['final_utilizacao']);

        if($dados['tipo_desconto_valor'] == TipoDescontoValor::PERCENTUAL) {

            $dados['desconto'] = $dados['valor_percentual_venda'];
            $dados['desconto_net'] = $dados['valor_percentual_net'];

        } else {

            $dados['desconto'] = $this->stringParaDouble($dados['valor_fixo_venda']);
            $dados['desconto_net'] = $this->stringParaDouble($dados['valor_fixo_net']);

        }

        $desconto->update($dados);

        return redirect()->route('app.descontos.desconto.edit', ['desconto_id' => $desconto->id]);
    }

    private function stringParaDouble(String $valor)
    {
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);

        return doubleval($valor);
    }
}
