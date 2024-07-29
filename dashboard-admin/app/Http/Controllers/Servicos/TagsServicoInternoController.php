<?php

namespace App\Http\Controllers\Servicos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servico\UpdateTagServicoRequest;
use App\Http\Requests\TagServicoInternoRequest;
use App\Services\IconService;
use TourFacil\Core\Models\TagServico;
use TourFacil\Core\Models\TagServicoInterno;

/**
 * TagsServicoInternoController
 */
class TagsServicoInternoController extends Controller
{
    /**
     * Retorna a listagem de Icones em JSON
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function index() {
        $icones = IconService::getIcons();
        return response($icones, 200);
    }

    /**
     * Salva uma Tag de serviço interno
     * @param TagServicoInternoRequest $request
     * @return array|array[]
     */
    public function store(TagServicoInternoRequest $request) {
        $tag = TagServicoInterno::create($request->all());

        return $this->autoResponseJson(true, 'Cadastrado', 'A tag foi cadastrada com sucesso.');
    }

    /**
     * Retorna os detalhes da tag
     *
     * @param $tag_id
     * @return mixed
     */
    public function view($tag_id)
    {
        return TagServicoInterno::findOrFail($tag_id);
    }

    /**
     * Atualiza o exclui a tag servico
     *
     * @param UpdateTagServicoRequest $request
     * @return array
     */
    public function update(TagServicoInternoRequest $request)
    {
        // Recupera a tag servico
        $tag_servico = TagServicoInterno::find($request->get('tag_id'));

        // Caso seja para deletar a tag
        $delete_tag = $request->get('delete_tag');

        // Verifica se é para atualizar a tag
        if($delete_tag == "off") {

            // Atualiza os dados
            $update = $tag_servico->update($request->all());

            if($update) {
                return $this->autoResponseJson(true, "Tag atualizada", "A tag foi atualizada com sucesso!");
            }

            return $this->autoResponseJson(false, "Não foi possível atualizar", "A tag serviço não foi atualizada, tente novamente!");

        } else {

            // Deleta a tag
            $tag_servico->delete();

            return $this->autoResponseJson(true, "Tag excluída", "O tag foi excluída com sucesso!");
        }
    }
}
