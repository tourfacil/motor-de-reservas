<?php namespace App\Http\Controllers\Categorias;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategoriaRequest;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\CategoriasEnum;
use TourFacil\Core\Enum\FotoServicoEnum;
use TourFacil\Core\Enum\StatusCategoriaEnum;
use TourFacil\Core\Models\Categoria;
use TourFacil\Core\Services\CategoriaService;
use TourFacil\Core\Services\DestinoService;
use TourFacil\Core\Services\UploadPhotoService;

/**
 * Class CategoriasController
 * @package App\Http\Controllers\Categorias
 */
class CategoriasController extends Controller
{
    /**
     * Listagem das categorias por canal de venda
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Recupera os destinos com as categorias
        $destino_categorias = CategoriaService::categoriasCanalVenda($canal_venda, true);

        return view('paginas.categorias.categorias', compact(
            'destino_categorias',
            'canal_venda'
        ));
    }

    /**
     * View para cadastro de uma nova categoria
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Recupera os destinos de acordo com o canal de venda
        $destinos = DestinoService::destinoCanalVenda($canal_venda);

        // Preset do banner
        $preset_banner = Categoria::$PHOTO_PRESET[FotoServicoEnum::LARGE];

        // Preset da foto da categoria
        $preset_foto = Categoria::$PHOTO_PRESET[FotoServicoEnum::MEDIUM];

        // Tipo de categoria
        $tipos_categoria = CategoriasEnum::TIPOS_CATEGORIA;

        $tipo_status = StatusCategoriaEnum::STATUS;

        return view('paginas.categorias.nova-categoria', compact(
            'canal_venda',
            'destinos',
            'preset_banner',
            'preset_foto',
            'tipos_categoria',
            'tipo_status'
        ));
    }

    /**
     * View para detalhes da categoria
     *
     * @param $categoria_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($categoria_id = null)
    {
        // Recupera os dados da categoria
        $categoria = Categoria::with('destino.canalVenda', 'secoesCategoria')->withTrashed()->findOrFail($categoria_id);

        // Preset do banner
        $preset_banner = Categoria::$PHOTO_PRESET[FotoServicoEnum::LARGE];

        // Preset da foto da categoria
        $preset_foto = Categoria::$PHOTO_PRESET[FotoServicoEnum::MEDIUM];

        // Tipo de categoria
        $tipos_categoria = CategoriasEnum::TIPOS_CATEGORIA;

        $status_categoria = StatusCategoriaEnum::STATUS;

        return view('paginas.categorias.detalhes-categoria', compact(
            'categoria',
            'preset_foto',
            'preset_banner',
            'tipos_categoria',
            'status_categoria'
        ));
    }

    /**
     * Detalhes da categoria JSON
     *
     * @param null $categoria_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]
     */
    public function viewJson($categoria_id = null)
    {
        // Recupera os dados da categoria
        return Categoria::with('secoesCategoria')->findOrFail($categoria_id);
    }

    /**
     * Post de cadastro da nova categoria
     *
     * @param StoreCategoriaRequest $request
     * @return array
     */
    public function store(StoreCategoriaRequest $request)
    {
        // Cria uma nova categoria com os dados do request
        $categoria = Categoria::create($request->all());

        if(is_object($categoria)) {
            return [
                'action' => true,
                'categoria' => $categoria,
                'view' => route('app.categorias.view', $categoria->id),
            ];
        }

        return ['action' => false];
    }

    /**
     * Post para atualização da descrição da categoria
     *
     * @param UpdateCategoriaRequest $request
     * @return array
     */
    public function update(UpdateCategoriaRequest $request)
    {
        // Recupera os dados da categoria
        $categoria = Categoria::withTrashed()->find($request->get('categoria_id'));

        // Caso encontre a categoria
        if(is_object($categoria)) {

            // Status da categoria via input
            $status = $request->get('status');

            // Atualiza os dados
            $categoria->update($request->only(Categoria::ARRAY_UPDATE));

            // Caso seja para desativar a categoria
            if(is_null($status) && $categoria->status) {

                // Desativa a categoria
                $categoria->delete();

                return $this->autoResponseJson(true, "Categoria desativada", "A categoria foi desativada com sucesso!");
            }

            // Ativa a categoria
            if(($status == "on") && $categoria->status == false) $categoria->restore();

            return $this->autoResponseJson(true, "Categoria atualizada", "As informações da categoria foram salvas com sucesso!");
        }

        return $this->autoResponseJson(false, "Categoria não atualizada", "Não foi possível atualizar os dados da categoria, tente novamente!");
    }

    /**
     * Faz o upload do banner e da foto da categoria
     *
     * @param Request $request
     * @return array
     */
    public function uploadFotosCategoria(Request $request)
    {
        // Verifica se existe a foto ou o banner
        if((! $request->hasFile('foto')) || (! $request->hasFile('banner'))) {
            return ['action' => false, 'message' => 'É necessário enviar uma foto e um banner da categoria!'];
        }

        // Procura a categoria
        $categoria = Categoria::with('destino.canalVenda')->find($request->get('categoria_id'));

        // Caso encontre envia as fotos
        if(is_object($categoria)) {

            // Foto
            $foto = UploadPhotoService::uploadFotoCategoria($request->file('foto'), $categoria, $categoria->destino, $categoria->destino->canalVenda);

            // Faz o upload do banner
            $banner = UploadPhotoService::uploadBannerCategoria($request->file('banner'), $categoria, $categoria->destino, $categoria->destino->canalVenda);

            // Caso alguma de falha
            if((!$foto['upload']) || (!$banner['upload'])) {

                // Deleta a imagem se subiu alguma
                UploadPhotoService::delete($foto['foto'], $banner['foto']);

                return ['action' => false, 'message' => 'Não foi possível fazer o upload das fotos, tente novamente!'];
            }

            // Atualiza as fotos da categoria
            $categoria->update(['foto' => [
                FotoServicoEnum::MEDIUM => $foto['foto'],
                FotoServicoEnum::LARGE => $banner['foto'],
            ]]);

            return ['action' => true];
        }

        return ['action' => false, 'message' => 'Não foi possível localizar a categoria, tente novamente!'];
    }

    /**
     * Atualiza o banner ou a foto da categoria
     *
     * @param Request $request
     * @return array
     */
    public function updateFotoCategoria(Request $request)
    {
        // Verifica se existe a foto ou o banner
        if((! $request->hasFile('foto')) && (! $request->hasFile('banner'))) {
            return ['action' => false, 'message' => 'É necessário enviar uma foto e um banner da categoria!'];
        }

        // Procura a categoria
        $categoria = Categoria::with('destino.canalVenda')->withTrashed()->find($request->get('categoria_id'));

        // Para verifcar se atualizou algo
        $update = false;

        // Caso encontre envia as fotos
        if(is_object($categoria)) {

            // Caso seja para atualizar a foto
            if($request->hasFile('foto')) {

                // Faz o upload
                $foto = UploadPhotoService::uploadFotoCategoria($request->file('foto'), $categoria, $categoria->destino, $categoria->destino->canalVenda);

                // Se fez o upload
                if($foto['upload']) {

                    // Atualiza as fotos da categoria
                    $categoria->update(['foto' => [
                        FotoServicoEnum::MEDIUM => $foto['foto'],
                        FotoServicoEnum::LARGE => $categoria->foto[FotoServicoEnum::LARGE],
                    ]]);

                    // Atualizou a foto
                    $update = true;

                } else {

                    // Deleta a imagem se subiu
                    UploadPhotoService::delete($foto['foto']);

                    return ['action' => false, 'message' => 'Não foi possível fazer o upload da foto, tente novamente!'];
                }
            }

            // Caso seja para atualizar o banner
            if($request->hasFile('banner')) {

                // Faz o upload do banner
                $banner = UploadPhotoService::uploadBannerCategoria($request->file('banner'), $categoria, $categoria->destino, $categoria->destino->canalVenda);

                // Se fez o upload
                if($banner['upload']) {

                    // Atualiza as fotos da categoria
                    $categoria->update(['foto' => [
                        FotoServicoEnum::MEDIUM => $categoria->foto[FotoServicoEnum::MEDIUM],
                        FotoServicoEnum::LARGE => $banner['foto'],
                    ]]);

                    // Atualizou o banner
                    $update = true;

                } else {

                    // Deleta a imagem se subiu
                    UploadPhotoService::delete($banner['foto']);

                    return ['action' => false, 'message' => 'Não foi possível fazer o upload do banner, tente novamente!'];
                }
            }

            // Caso a foto ou o banner foram atualizados
            if($update) return ['action' => true];

            return ['action' => false, 'message' => 'Nenhuma foto foi atualizada, tente novamente!'];
        }

        return ['action' => false, 'message' => 'Não foi possível localizar a categoria, tente novamente!'];
    }
}
