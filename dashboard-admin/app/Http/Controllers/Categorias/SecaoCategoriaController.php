<?php namespace App\Http\Controllers\Categorias;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSecaoCategoriaRequest;
use App\Http\Requests\UpdateSecaoCategoriaRequest;
use Illuminate\Http\Request;
use TourFacil\Core\Models\Categoria;
use TourFacil\Core\Models\SecaoCategoria;

/**
 * Class SecaoCategoriaController
 * @package App\Http\Controllers\Categorias
 */
class SecaoCategoriaController extends Controller
{
    /**
     * Detalhes da secao para a modal
     *
     * @param $secao_id
     * @return mixed
     */
    public function view($secao_id)
    {
        return SecaoCategoria::find($secao_id);
    }

    /**
     * Atualiza os dados da secao
     *
     * @param UpdateSecaoCategoriaRequest $request
     * @return array
     */
    public function update(UpdateSecaoCategoriaRequest $request)
    {
        // Recupera os dados da secao
        $secao_categoria = SecaoCategoria::find($request->get('secao_id'));

        // Caso encontre
        if(is_object($secao_categoria)) {

            // Atualiza os dados
            $secao_categoria->update($request->only(SecaoCategoria::ARRAY_UPDATE));

            // Caso seja para excluir a secao
            if($request->get('delete_secao') == "on") {
                // Deleta a secao
                $secao_categoria->delete();

                return $this->autoResponseJson(true, "Seção excluida", "A seção foi excluida com sucesso!");
            }

            return $this->autoResponseJson(true, "Seção atualizada", "As informações da seção foram salvas sucesso!");
        }

        return $this->autoResponseJson(false, "Seção não atualizada", "Não foi possível atualizar os dados da seção, tente novamente!");
    }

    /**
     * Post de cadastro de uma nova seção categoria
     *
     * @param StoreSecaoCategoriaRequest $request
     * @return array
     */
    public function store(StoreSecaoCategoriaRequest $request)
    {
        // Verifica se não existe uma seção com o mesmo nome para a categoria
        $exists_secao = SecaoCategoria::where('nome', 'LIKE', formatarNome($request->get('nome')))
            ->where('categoria_id', $request->get('categoria_id'))->first();

        // Caso nao exista nenhuma secao com o nome
        if(is_null($exists_secao)) {

            // Cadastra a secao
            $secao_categoria = SecaoCategoria::create($request->only(SecaoCategoria::ARRAY_STORE));

            // O cadastro ocorreu normalmente
            if(is_object($secao_categoria)) {
                return $this->autoResponseJson(true, "Seção cadastrada", "A seção {$secao_categoria->nome} foi cadastrada com sucesso!");
            }

            return $this->autoResponseJson(false, "Seção não cadastrada", "Não foi possível cadastrar a seção, tente novamente!");
        }

        return $this->autoResponseJson(false, "Seção não cadastrada", "Já existe uma seção com este nome para essa categoria!");
    }
}
