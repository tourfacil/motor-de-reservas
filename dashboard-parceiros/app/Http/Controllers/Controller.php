<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
    public function autoResponseJson(bool $result, string $title, string $message, string $url = "", $payload = null)
    {
        $data = [
            'action' => [
                'result' => $result,
                'title' => $title,
                'message' => $message
            ]
        ];

        // Caso possua uma URL de redirecionamento
        if($url != "") $data['action']['url'] = $url;

        // Caso possua um payload de retorno
        if(is_null($payload) == false) $data['action']['payload'] = $payload;

        return $data;
    }
}
