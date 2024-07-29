<?php namespace App\Http\Controllers;

use App\Services\RedirectService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use TourFacil\Core\Services\Cache\CanalVendaCacheService;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Chave na sessao para salvar o destino atual
    const KEY_DESTINO = "destino_atual";

    /**
     * Padrão de resposta para formulário AJAX
     *
     * @param boolean $result
     * @param string $title
     * @param string $message
     * @param string|null $url
     * @param null $payload
     * @return array
     */
    public function autoResponseJson(bool $result, string $title, string $message, string $url = null, $payload = null)
    {
        $data = [
            'action' => [
                'result' => $result,
                'title' => $title,
                'message' => $message
            ]
        ];

        // Caso possua uma URL de redirecionamento
        if(! is_null($url)) $data['action']['url'] = $url;

        // Caso possua um payload de retorno
        if(is_null($payload) == false) $data['action']['payload'] = $payload;

        return $data;
    }

    /**
     * Redirecionamentos
     *
     * @param null $route
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect($route = null)
    {
        // Redirecionamentos
        $redirect = RedirectService::getRedirecionamentos(request());

        // Caso a URL tenha redirecionamento
        if($redirect['redirect'])
            return redirect()->to($redirect['path'], $redirect['code']);

        // Caso nao tenha url previa
        if(is_null($route))
            return redirect()->route('ecommerce.index');

        return redirect()->to($route);
    }

    /**
     * Salva a URL do destino atual
     *
     * @param $destino
     */
    public function setDestinoSession($destino)
    {
        // Destino atual na sessao
        $atual = session()->get(self::KEY_DESTINO);

        // Salva na sessao caso seja outro destino
        if($atual['destino_slug'] !== $destino->slug) {
            session()->put(self::KEY_DESTINO, [
                'destino' => $destino,
                'destino_slug' => $destino->slug,
                'url_destino' => route('ecommerce.destino.index', $destino->slug)
            ]);
        }
    }

    /**
     * Retorna o destino da sessao
     *
     * @return mixed
     */
    public function getDestinoSession()
    {
        return session()->get(self::KEY_DESTINO);
    }

    /**
     * Retorna o canal de venda atual
     *
     * @return mixed
     * @throws \Exception
     */
    public function getCanalVenda()
    {
        return CanalVendaCacheService::canalvendaSite();
    }
}
