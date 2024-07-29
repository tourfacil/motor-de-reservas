<?php namespace App\Helpers;

/**
 * Class CanalVendaSession
 * @package App\Helpers
 */
class CanalVendaSession
{
    /**
     * @var string
     */
    protected $key = "canal_session";

    /**
     * Retorna o canal default do usuario
     *
     * @return mixed
     */
    public function getCanal()
    {
        return session()->get($this->key, (object) ['id' => 1]);
    }

    /**
     * Configura o canal default do usuario
     *
     * @param $canal
     */
    public function setCanal($canal)
    {
        return session()->put($this->key, $canal);
    }

    /**
     * Retorna se o canal esta configurado
     *
     * @return bool
     */
    public function hasCanal()
    {
        return isset($this->getCanal()->nome);
    }
}