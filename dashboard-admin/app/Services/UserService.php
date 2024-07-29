<?php namespace App\Services;

use TourFacil\Core\Services\CanalVendaService;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    /** Configura o canal default do usuario */
    public static function configureCanalUserDefault()
    {
        // Dados do usuario logado
        $user = auth()->user();

        // Recupera os canais de vendas ativos
        $canais_venda = CanalVendaService::getCanaisDeVendaAtivos();

        // Procura o canal padrão do usuario
        $user_default = $canais_venda->first(function ($canal) use ($user) {
            return ($canal->id == $user->canal_venda_id);
        });

        // Caso seja desativado o canal utiliza o primeiro que encontrar
        if(is_null($user_default)) {
            $user_default = $canais_venda->first();
        }

        // Guarda na sessao o canal do usuario
        canalSession()->setCanal($user_default);
    }
}
