<?php namespace App\Helpers;

use TourFacil\Core\Services\CupomDescontoService;
use TourFacil\Core\Services\Pagamento\Gerencianet\Pix\PixService;

/**
 * Class Carrinho
 * @package App\Helpers
 */
class Carrinho
{
    /**
     * @var string
     */
    protected $session_key = "carrinho";

    /**
     * @var array|mixed
     */
    protected $carrinho = [];

    /**
     * No construtor guardamos nossos serviços no atributo carrinho
     *
     * SessaoCarrinho constructor.
     */
    public function __construct()
    {
        $this->carrinho = $this->verifySession();
    }

    /**
     * Cria ou retorna a casa do carrinho da sessão
     *
     * @return mixed
     */
    protected function verifySession()
    {
        if(! session()->has($this->session_key)) {
            session([$this->session_key => []]);
        }

        return array_values(session($this->session_key));
    }

    /**
     * Adiciona um serviço na sessão
     *
     * @param $servico
     * @return bool
     */
    public function add($servico)
    {
        if(! $this->has($servico['uuid'])) {
            array_unshift($this->carrinho, $servico);

            CupomDescontoService::removerCupomSessao();
            PixService::cancelarPixSessao();

            return true;
        }

        return $this->update($servico);
    }

    /**
     * Pega as informações do serviço guardado na sessão
     *
     * @param $servico_uuid
     * @return mixed
     */
    public function get($servico_uuid)
    {
        return $this->all()->first(function ($servico) use ($servico_uuid) {
            return ($servico['uuid'] == $servico_uuid);
        });
    }

    /**
     * Verifica se existe o serviço na sessão
     *
     * @param $servico_uuid
     * @return bool
     */
    public function has($servico_uuid)
    {
        return $this->all()->contains('uuid', $servico_uuid);
    }

    /**
     * Remove o serviço da sessão
     *
     * @param $servico_uuid
     * @return bool
     */
    public function remove($servico_uuid)
    {
        if($this->has($servico_uuid)) {

            // Forma um novo array retirando o serviço escolhido
            $filtered = $this->all()->filter(function ($servico) use ($servico_uuid) {
                return $servico['uuid'] <> $servico_uuid;
            });

            $this->carrinho = $filtered->toArray();

            CupomDescontoService::removerCupomSessao();
            PixService::cancelarPixSessao();

            return true;
        }

        return false;
    }

    /**
     * Atualiza um item na sessão
     *
     * @param $servico
     * @return bool
     */
    public function update($servico)
    {
        if($this->has($servico['uuid'])) {

            $key = array_search($servico['uuid'], array_column($this->carrinho, 'uuid'));

            $this->carrinho[$key] = $servico;

            CupomDescontoService::removerCupomSessao();
            PixService::cancelarPixSessao();

            return true;
        }

        return false;
    }

    /**
     * Retorna todos os itens do carrinho
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return collect($this->carrinho);
    }

    /**
     * Retorna a quantidade de serviços no carrinho
     *
     * @return int
     */
    public function count()
    {
        return count($this->carrinho);
    }

    /**
     * Retorna o valor total do carrinho de compras
     *
     * @return mixed
     */
    public function totalCarrinho()
    {
        return $this->all()->sum('valor_total');
    }

    /**
     * Verifica se existe algo no carrinho de compras
     *
     * @return bool
     */
    public function check()
    {
        return ($this->all()->count() > 0);
    }

    /** Apaga todos os serviços do carrinho */
    public function destroy()
    {
        $this->carrinho = [];
    }

    /**
     * Atualiza o carrinho de compra
     *
     * @param $carrinho
     */
    public function put($carrinho)
    {
        $this->carrinho = $carrinho;
    }

    /**
     * Sempre que a classe for destruida atualiza os itens na sessão
     * Com os itens do $this->carrinho
     */
    public function __destruct()
    {
        session()->put($this->session_key, $this->carrinho);
    }
}
