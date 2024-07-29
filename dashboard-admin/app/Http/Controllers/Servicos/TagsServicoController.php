<?php namespace App\Http\Controllers\Servicos;

use App\Http\Requests\Servico\StoreTagServicoRequest;
use App\Http\Requests\Servico\UpdateTagServicoRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\TagServico;

/**
 * Class TagsServicoController
 * @package App\Http\Controllers\Servicos
 */
class TagsServicoController extends Controller
{
    /**
     * Lista os icones disponiveis para tags
     *
     * @return array|false|string
     */
    public function index()
    {
        // icones tags
        $icones_jam = file_get_contents(resource_path("assets/fonts/jam-icons.json"));
        $icones_jam = ($icones_jam) ? json_decode($icones_jam, true)["icons"] : [];

        return $icones_jam;
    }

    /**
     * Retorna os detalhes da tag
     *
     * @param $tag_id
     * @return mixed
     */
    public function view($tag_id)
    {
        return TagServico::findOrFail($tag_id);
    }

    /**
     * POST para cadastro da tag no servico
     *
     * @param StoreTagServicoRequest $request
     * @return array
     */
    public function store(StoreTagServicoRequest $request)
    {
        // Cadastra a tag no servico
        $tag_servico = TagServico::create($request->all());

        // Verifica se cadastrou
        if(is_object($tag_servico)) {
            return $this->autoResponseJson(true, "Tag serviço cadastrada", "A tag foi cadastrada com sucesso!");
        }

        return $this->autoResponseJson(false, 'Tag não cadastrado', 'Não foi possível cadastrar a tag, tente novamente!');
    }

    /**
     * Atualiza o exclui a tag servico
     *
     * @param UpdateTagServicoRequest $request
     * @return array
     */
    public function update(UpdateTagServicoRequest $request)
    {
        // Recupera a tag servico
        $tag_servico = TagServico::find($request->get('tag_id'));

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
