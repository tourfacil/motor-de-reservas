<?php

namespace App\Http\Controllers\Servicos;

use TourFacil\Core\Models\FaqServico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class FaqServicoController extends Controller
{

    public function store(Request $request)
    {
        // dd($request->all());

        $dados = $request->all();
        $dados['titulo'] = $request->get('nova_pergunta_pergunta');
        $dados['texto'] = $request->get('nova_pergunta_resposta');

        FaqServico::create($dados);

        return $this->autoResponseJson(true, 'Pergunta criada', 'A pergunta foi salva e ja está visivel no serviço');
    }

    public function update(Request $request)
    {
        
        $faq_servico_id = $request->get('faq_servico_id');
        
        $dados = [
            'titulo' => $request->get('edit_pergunta_pergunta'),
            'texto' => $request->get('edit_pergunta_resposta'),
        ];

        FaqServico::find($faq_servico_id)->update($dados);

        return $this->autoResponseJson(true, 'Pergunta editada', 'A pergunta foi salva e ja está visivel no serviço');
    }

    public function delete(Request $request)
    {
        FaqServico::find($request->get('faq_servico_id'))->delete();

        return $this->autoResponseJson(true, 'Pergunta excluída', 'A pergunta foi excluída e não está mais visivel no serviço');
    }
}
