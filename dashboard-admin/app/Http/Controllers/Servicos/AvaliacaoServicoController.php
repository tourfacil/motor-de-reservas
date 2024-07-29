<?php

namespace App\Http\Controllers\Servicos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\StatusAvaliacaoServicoEnum;
use TourFacil\Core\Models\AvaliacaoServico;
use TourFacil\Core\Models\Servico;

class AvaliacaoServicoController extends Controller
{
    public function index(Request $request) {

        $dados = [
            'avaliacoes' => AvaliacaoServico::all()->sortByDesc('status'),
        ];

        return view('paginas.servicos.avaliacoes.avaliacoes', $dados);
    }

    public function create(Request $request) {

        $dados = [
            'servicos' => Servico::where('status', 'ATIVO')->get(),
            'status_avaliacao' => StatusAvaliacaoServicoEnum::STATUS,
        ];

        return view('paginas.servicos.avaliacoes.nova-avaliacao', $dados);
    }

    public function store(Request $request) {
        $avaliacao = AvaliacaoServico::create($request->all());

        return redirect()->route('app.servicos.avaliacoes.index');
    }

    public function edit(Request $request, $avaliacao_id) {

        $avaliacao = AvaliacaoServico::find($avaliacao_id);

        if($avaliacao == null) {
            return redirect()->route('app.servicos.avaliacoes.index');
        }

        $dados = [
            'avaliacao' => $avaliacao,
            'servicos' => Servico::where('status', 'ATIVO')->get(),
            'status_avaliacao' => StatusAvaliacaoServicoEnum::STATUS,
        ];

        return view('paginas.servicos.avaliacoes.editar-avaliacao', $dados);
    }

    public function update(Request $request, $avaliacao_id) {

        $avaliacao = AvaliacaoServico::find($avaliacao_id);

        if($avaliacao == null) {
            return redirect()->route('app.servicos.avaliacoes.index');
        }

        $avaliacao->update($request->all());

        return redirect()->route('app.servicos.avaliacoes.edit', $avaliacao->id);
    }
}
