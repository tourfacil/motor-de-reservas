<?php namespace App\Services;

use TourFacil\Core\Enum\CategoriasEnum;
use TourFacil\Core\Models\Categoria;
use TourFacil\Core\Models\Servico;

/**
 * Class RedirectService
 * @package App\Services
 */
class RedirectService
{
    /**
     * Redirecionamentos dos servicos antigos
     *
     * @param $request
     * @return array
     */
    public static function getRedirecionamentos($request)
    {
        // Lista com os redirects
        $redirects = [ ];

        // Nova url
        $new_url = $redirects[$request->path()] ?? null;
        $return = ['redirect' => false, 'code' => 302, 'path' => ''];

        if(is_array($new_url)) {

            // Redirecionamento por path
            if($new_url['type'] == "redirect") {
                $return['redirect'] = true;
                $return['path'] = $new_url['path'];
                return $return;
            }

            // Redirecionamento por route
            if($new_url['type'] == "route") {
                $return['redirect'] = true;
                $return['path'] = route($new_url['name']);
                return $return;
            }

            // Redirecionamento por route
            if($new_url['type'] == "category") {
                // Recupera os dados da categoria
                $categoria = Categoria::with(['destino' => function($q) {
                    return $q->select(['id', 'slug']);
                }])->select(['id', 'destino_id', 'slug'])->find($new_url['id']);
                $return['redirect'] = true;
                $return['path'] = route('ecommerce.categoria.index', [
                    $categoria->destino->slug, $categoria->slug
                ]);
                return $return;
            }

            // Redirecionamento por route
            if($new_url['type'] == "servico") {
                $return['redirect'] = true;
                // Caso nao possua o cadastro do novo servico redireciona para index
                if($new_url['id'] == 0) {
                    $return['code'] = 301;
                    $return['path'] = route('ecommerce.index');
                } else {

                    // Recupera os dados do servico
                    $servico = Servico::with([
                        'destino' => function($q) { return $q->select(['id', 'slug']);},
                        'categoria' => function($q) {
                            return $q->select(['id', 'slug'])
                                ->whereNotIn('id', CategoriasEnum::CATEGORIAS_OFERTA)->limit(1);
                        }
                    ])->select(['id', 'slug', 'destino_id'])->find($new_url['id']);

                    // URL do servico
                    $return['path'] = route('ecommerce.servico.view', [
                        $servico->destino->slug, $servico->categoria->slug, $servico->slug
                    ]);
                }

                return $return;
            }
        }

        return $return;
    }
}
