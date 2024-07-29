<?php

namespace App\Services;

use Carbon\Carbon;
use TourFacil\Core\Enum\StatusReservaEnum;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Models\ReservaPedido;
use Illuminate\Support\Facades\Cache;

abstract class DashboardService
{
    public static function getDadosDashBoard() {

        $dados = [];

        $dados = collect($dados);

        $inicio = Carbon::now()->startOfMonth();
        $final = Carbon::now()->endOfMonth();

        $reservas = ReservaPedido::whereBetween('created_at', [$inicio, $final])
        ->whereIn('status', ['ATIVA', 'UTILIZADO'])
        ->with([
            'servico',
            'pedido',
            'afiliado',
            'vendedor'
        ])->get();

        $pedidos = Pedido::whereBetween('created_at', [$inicio, $final])
        ->whereIn('status', ['PAGO', 'UTILIZADO'])
        ->get();

        $dados['valor_total'] = $pedidos->sum('valor_total') + $pedidos->sum('juros');

        $dados['valor_total_mes_passado'] = self::getValorMesPassadoCache();

        $dados['vendido_hoje'] = self::getValorVendidoHoje($pedidos);

        $dados['meta'] = env('META_MENSAL');

        $dados['meta_diaria'] = self::getValorMetaDiaria($dados['valor_total']);

        $dados = $dados->merge(self::rankingServicos($reservas));

        $dados = DashboardService::setPercentualRankings($dados);

        return $dados;

    }

    private static function getValorMesPassadoCache() {
        $valor_mes_passado = Cache::get('dashboard.valor_mes_passado');

        if($valor_mes_passado) return $valor_mes_passado;

        $primeiro_dia = Carbon::now()->startOfMonth()->subDays(1)->startOfMonth();
        $ultimo_dia = Carbon::now()->startOfMonth()->subDays(1)->endOfMonth();

        $pedidos = Pedido::whereBetween('created_at', [$primeiro_dia, $ultimo_dia])
        ->whereIn('status', ['PAGO', 'UTILIZADO'])
        ->get();

        $valor_total = $pedidos->sum('valor_total') + $pedidos->sum('juros');

        Cache::put('dashboard.valor_mes_passado', $valor_total);

        return $valor_total;
    }

    private static function rankingServicos($reservas) {

        $dados = [];
        $dados['servicos'] = [];
        $dados['afiliados'] = [];
        $dados['vendedores'] = [];
        $dados['quantidade'] = 0;
        $dados['qtd_servicos_vendidos'] = 0;
        $dados['qtd_vendas_afiliados'] = 0;
        $dados['qtd_vendas_vendedores'] = 0;
        $dados['total_vendedoras'] = 0;
        $dados['total_afiliados'] = 0;

        foreach($reservas as $reserva) {

            // Guarda os valores de venda de cada serviço
            if(array_key_exists($reserva->servico->slug, $dados['servicos'])) {
                $dados['servicos'][$reserva->servico->slug]['valor'] += $reserva->valor_total;
            } else {
                $dados['qtd_servicos_vendidos']++;
                $dados['servicos'][$reserva->servico->slug] = [
                    'servico' => $reserva->servico->nome,
                    'valor' => $reserva->valor_total,
                ];
            }

            // Recupera os valores dos afiliados
            if($reserva->afiliado != null) {

                if(array_key_exists($reserva->afiliado->nome_fantasia, $dados['afiliados'])) {
                    $dados['afiliados'][$reserva->afiliado->nome_fantasia]['valor'] += $reserva->valor_total;
                    $dados['total_afiliados'] += $reserva->valor_total;
                } else {
                    $dados['afiliados'][$reserva->afiliado->nome_fantasia] = [
                        'afiliado' => $reserva->afiliado->nome_fantasia,
                        'valor' => $reserva->valor_total
                    ];
                    $dados['qtd_vendas_afiliados']++;
                    $dados['total_afiliados'] += $reserva->valor_total;
                }
            }

            if($reserva->vendedor != null) {

                if(array_key_exists($reserva->vendedor->nome_fantasia, $dados['vendedores'])) {
                    $dados['vendedores'][$reserva->vendedor->nome_fantasia]['valor'] += $reserva->valor_total;
                } else {
                    $dados['vendedores'][$reserva->vendedor->nome_fantasia] = [
                        'vendedor' => $reserva->vendedor->nome_fantasia,
                        'valor' => $reserva->valor_total
                    ];
                    $dados['qtd_vendas_vendedores']++;
                }

                $dados['total_vendedoras'] += $reserva->valor_total;
            }

            // Guarda a quantidade
            $dados['quantidade'] += $reserva->quantidade;
        }

        $dados['servicos'] = collect($dados['servicos']);
        $dados['servicos'] = $dados['servicos']->sortByDesc('valor');

        $dados['afiliados'] = collect($dados['afiliados']);
        $dados['afiliados'] = $dados['afiliados']->sortByDesc('valor');

        return $dados;
    }

    public static function getValorVendidoHoje($pedidos) {

        $valor = 0;

        foreach($pedidos as $pedido) {

            if(Carbon::parse($pedido->created_at)->day == Carbon::now()->day) {
                $valor += $pedido->valor_total + $pedido->juros;
            }
        }

        return $valor;
    }

    /**
     * Calcula o valor NET vendido em cada destino
     * Guarda o valor em CACHE
     *
     * @return array|mixed
     */
    public static function GetValorPorDestinoMes() {

        // Index do CACHE e tempo de expiração
        $index_cache = 'dashboard.valor_por_destino_mes';
        $time_out_cache = 60;

        // Busca destinos no Cache
        $destinos = Cache::get($index_cache);

        // Caso existir retorna, se não inicia os calculos
        if($destinos) {
            return $destinos;
        }

        // Pega o primeiro e o ultimo dia do mes
        $primeiro_dia = Carbon::now()->startOfMonth();
        $ultimo_dia = Carbon::now()->endOfMonth()->addDays(1);

        // Pega todas as reservas do mes
        $reservas = ReservaPedido::whereBetween('created_at', [$primeiro_dia, $ultimo_dia])
                                 ->whereIn('status', StatusReservaEnum::RESERVAS_VALIDAS)
                                 ->with([
                                     'servico',
                                     'servico.destino'
                                 ])->get();

        // Array para guardar os destinos
        $destinos = [];

        // Passa em todas as reservas e vai somando os netss dentro de cada destino
        foreach($reservas as $reserva) {

            // Guarda o indice do destino
            $destino_index = $reserva->servico->destino->nome;

            // Caso a variavel do destino ainda não exista no array de destinos ele cria
            if(array_key_exists($destino_index, $destinos) == false) {
                $destinos[$destino_index] = 0;
            }

            // Soma o valor do NET ao destino dentro do array
            $destinos[$destino_index] += $reserva->valor_net;
        }

        // Salva no Cache
        Cache::put($index_cache, $destinos, $time_out_cache);

        // Retorna
        return $destinos;
    }

    private static function getValorMetaDiaria($valor_total) {
        $meta = env('META_MENSAL');

        $hoje = Carbon::now();
        $ultimo_dia = Carbon::now()->endOfMonth();

        $diferenca = $ultimo_dia->day - $hoje->day;

        if($diferenca > 0) {
            return ($meta - $valor_total) / $diferenca;
        } else {
            return ($meta - $valor_total);
        }
    }

    private static function setPercentualRankings($dados) {

        $servicos = $dados['servicos']->toArray();
        $afiliados = $dados['afiliados']->toArray();
        $vendedores = $dados['vendedores'];
        $dados = $dados;

        $total = $dados['valor_total'];
        $total_afiliados = $dados['total_afiliados'];
        $total_vendedoras = $dados['total_vendedoras'];

        foreach($servicos as $key => $servico) {

            $servicos[$key]['percentual'] = self::regra3Percentual($total, $servico['valor']);

        }

        $dados['servicos'] = collect($servicos);

        foreach($afiliados as $key => $afiliado) {

            $afiliados[$key]['percentual'] = self::regra3Percentual($total_afiliados, $afiliado['valor']);

        }

        $dados['afiliados'] = collect($afiliados);

        foreach($vendedores as $key => $vendedor) {

            $vendedores[$key]['percentual'] = self::regra3Percentual($total_vendedoras, $vendedor['valor']);

        }

        $dados['vendedores'] = collect($vendedores);

        return $dados;

    }

    private static function regra3Percentual($total, $parcial)
    {
        return number_format(($parcial * 100) / $total, 2, ',', '.') . '%';
    }
}
