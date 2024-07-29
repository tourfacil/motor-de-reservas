<?php namespace App\Enum;

/**
 * Class ConfiguracoesEnum
 * @package App\Enum
 */
abstract class ConfiguracoesEnum
{
    const DASHBOARD = "DASHBOARD";
    const FORNECEDORES = "FORNECEDORES";
    const SERVICOS = "SERVICOS";
    const PEDIDOS = "PEDIDOS";
    const CLIENTES = "CLIENTES";

    /**
     * Páginas disponiveis para redirecionamento
     */
    const PAGES = [
        self::DASHBOARD => 'Dashboard',
        self::FORNECEDORES => 'Fornecedores',
        self::SERVICOS => 'Serviços',
        self::PEDIDOS => 'Pedidos do site',
        self::CLIENTES => 'Lista de clientes',
    ];

    /**
     * nome das rotas para cada página
     */
    const ROUTES_PAGE = [
        self::DASHBOARD => 'app.dashboard',
        self::FORNECEDORES => 'app.fornecedores.index',
        self::SERVICOS => 'app.servicos.index',
        self::PEDIDOS => 'app.pedidos.index',
        self::CLIENTES => 'app.clientes.index',
    ];

    /**
     * Retorna o path da rota de acordo com a configuração do usuario
     *
     * @param $user
     * @return string
     */
    public static function getPageDefault($user)
    {
        $route = self::ROUTES_PAGE[$user->pagina_padrao] ?? self::ROUTES_PAGE[self::DASHBOARD];
        return route($route);
    }
}
