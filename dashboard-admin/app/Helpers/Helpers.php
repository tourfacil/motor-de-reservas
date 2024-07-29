<?php

use Illuminate\Support\Facades\Mail;
use TourFacil\Core\Enum\IntegracaoEnum;
use TourFacil\Core\Services\ParcelamentoService;

if (! function_exists('formatarNome')) {

    /**
     * Formata o nome de uma pessoa para ficar mais legivel
     *
     * Ex: fabyo guimarães de oliveira -> Fabyo Guimarães de Oliveira
     *
     * @param $string
     * @return string
     */
    function formatarNome($string) {
        $words = explode(' ', mb_strtolower(trim(preg_replace("/\s+/", ' ', $string))));
        $return[] = ucfirst($words[0]);

        unset($words[0]);

        foreach ($words as $word) {
            if (!preg_match("/^([dn]?[aeiou][s]?|em)$/i", $word)) {
                $word = ucfirst($word);
            }
            $return[] = $word;
        }
        return implode(' ', $return);
    }
}

if(! function_exists('formataValor')) {

    /**
     * Retorna o valor formatado em Reais BRL
     *
     * @param $valor
     * @return string
     */
    function formataValor($valor) {
        return number_format($valor, 2, ",", ".");
    }
}

if(! function_exists('canalSession')) {

    /**
     * Função para o canal de venda na sessao
     *
     * @return \App\Helpers\CanalVendaSession
     */
    function canalSession() {
        return new \App\Helpers\CanalVendaSession();
    }
}

if(! function_exists('consultaCNPJ')) {

    /**
     * Consulta dados do CNPJ
     *
     * @param $cnpj
     * @return mixed
     */
    function consultaCNPJ($cnpj) {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_URL => "http://receitaws.com.br/v1/cnpj/{$cnpj}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($curl);

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $error_curl = curl_error($curl);

        curl_close($curl);

        return json_decode($response, true);
    }
}

if(! function_exists("dataExtenso")) {

    /**
     * Retorna o nome do mes em PT
     *
     * @param $mes
     * @param bool $limit
     * @return string
     */
    function mesPT($mes, $limit = false) {
        switch($mes) {
            case 1: $mes = "Janeiro"; break;
            case 2: $mes = "Fevereiro"; break;
            case 3: $mes = "Março"; break;
            case 4: $mes = "Abril"; break;
            case 5: $mes = "Maio"; break;
            case 6: $mes = "Junho"; break;
            case 7: $mes = "Julho"; break;
            case 8: $mes = "Agosto"; break;
            case 9: $mes = "Setembro"; break;
            case 10: $mes = "Outubro"; break;
            case 11: $mes = "Novembro"; break;
            case 12: $mes = "Dezembro"; break;
        }
        if($limit) return \Illuminate\Support\Str::limit($mes, 3, "");
        return $mes;
    }

    /**
     * Retorna o dia da semana em PT
     *
     * @param $semana
     * @param bool $limit
     * @return string
     */
    function semanaPT($semana, $limit = false) {
        switch($semana) {
            case 0: $semana = "Domingo"; break;
            case 1: $semana = "Segunda Feira"; break;
            case 2: $semana = "Terça Feira"; break;
            case 3: $semana = "Quarta Feira"; break;
            case 4: $semana = "Quinta Feira"; break;
            case 5: $semana = "Sexta Feira"; break;
            case 6: $semana = "Sábado"; break;
            case 7: $semana = "Domingo"; break;
        }
        if($limit) return \Illuminate\Support\Str::limit($semana, 3, "");
        return $semana;
    }

    /**
     * Recebe a data em Y-m-d e retorna por extenso em portugues
     * @param $data
     * @param bool $mes_curto
     * @return string
     */
    function dataExtenso($data, $mes_curto = false) {

        $data = \Carbon\Carbon::parse($data);
        $semana = semanaPT($data->dayOfWeek);
        $mes = mesPT($data->month);

        if($mes_curto) {
            $mes = substr($mes, 0, 3);
            $semana = ($data->dayOfWeek == \Carbon\Carbon::MONDAY) ? "Segunda" : $semana;
        }

        return formatarNome($semana . ", " . $data->day . " " . $mes . " " . $data->year);
    }
}

if(! function_exists("valorVariacao")) {
    function valorVariacao($valor_venda, $porcentagem) {
        $valor_venda_opcao = ($valor_venda / 100 * $porcentagem);
        return (float) number_format($valor_venda_opcao, 2, ".", "");
    }
}

if(! function_exists("periodoPesquisa")) {

    /**
     * Datas para pesquisa
     *
     * @return array
     */
    function periodoPesquisa() {
        return [
            'hoje' => [
                'nome' => 'Hoje',
                'start' => \Carbon\Carbon::today()->startOfDay(),
                'end' => \Carbon\Carbon::today()->endOfDay()
            ],
            'ultimos_7' => [
                'nome' => 'Últimos 7 dias',
                'start' => \Carbon\Carbon::today()->subDays(7)->startOfDay(),
                'end' => \Carbon\Carbon::today()->endOfDay()
            ],
            'ultimos_15' => [
                'nome' => 'Últimos 15 dias',
                'start' => \Carbon\Carbon::today()->subDays(15)->startOfDay(),
                'end' => \Carbon\Carbon::today()->endOfDay()
            ],
            'ultimos_30' => [
                'nome' => 'Últimos 30 dias',
                'start' => \Carbon\Carbon::today()->subDays(30)->startOfDay(),
                'end' => \Carbon\Carbon::today()->endOfDay()
            ],
            'este_mes' => [
                'nome' => "Este mês - " . mesPT(date('m')),
                'start' => \Carbon\Carbon::today()->firstOfMonth()->startOfDay(),
                'end' => \Carbon\Carbon::today()->endOfMonth()->endOfDay()
            ]
        ];
    }
}

if(! function_exists('iconeTopServico')) {

    /**
     * Icone para os servicos mais vendidos
     *
     * @param $position
     * @return string
     */
    function iconeTopServico($position) {

        if($position == 1) {
            return "<img src='" . asset("images/icons/medal-1.svg") ."' alt='Primeiro lugar' />";
        } elseif($position == 2) {
            return "<img src='" . asset("images/icons/medal-2.svg") ."' alt='Segundo lugar' />";
        } elseif ($position == 3) {
            return "<img src='" . asset("images/icons/medal-3.svg") ."' alt='Terceiro lugar' />";
        } else {
            return "{$position}º";
        }
    }
}

if(! function_exists("userIsAdmin")) {

    /**
     * @return bool
     */
    function userIsAdmin() {
        return (auth()->user()->level === \App\Enum\UserLevelEnum::ADMIN);
    }
}

if(! function_exists("userIsVendedor")) {

    /**
     * @return bool
     */
    function userIsVendedor() {
        return (auth()->user()->level === \App\Enum\UserLevelEnum::VENDEDOR);
    }
}

if(! function_exists("userIsAfiliado")) {

    /**
     * @return bool
     */
    function userIsAfiliado() {
        return (auth()->user()->level === \App\Enum\UserLevelEnum::AFILIADO);
    }
}

if(! function_exists("calculaMarkupVariacao")) {

    /**
     * @param $net_base
     * @param $net_variacao
     * @param $venda_variacao
     * @return array
     */
    function calculaMarkupVariacao($net_base, $net_variacao, $venda_variacao) {

        // Quando for gratuito
        if($net_base == 0 && $net_variacao == 0 && $venda_variacao == 0) {
            return ['percentual' => 0, 'comissao' => 0, 'markup' => 0];
        }

        // Quando a venda por de R$ 1,00
        if($net_base == 0 && $net_variacao == 0 && $venda_variacao >= 1) {
            return ['percentual' => 0, 'comissao' => 0, 'markup' => \TourFacil\Core\Enum\AgendaEnum::MARKUP_UM_REAL];
        }

        $porcent_net = (float) number_format((($net_variacao / $net_base) * 100), 5,".", "");

        $comissao_variacao = (float) number_format((100 - (($net_variacao / $venda_variacao) * 100)), 2, ".", "");

        $markup = (float) number_format((100 / (100 - $comissao_variacao)), 5,".", "");

        return [
            'percentual' => $porcent_net,
            'comissao' => $comissao_variacao,
            'markup' => $markup
        ];
    }
}

if(! function_exists('mask')) {

    /**
     * mask($data,'##/##/####');
     * mask($data,'[##][##][####]');
     * mask($data,'(##)(##)(####)');
     *
     * @param $val
     * @param $mask
     * @return string
     */
    function mask($val, $mask){
        $maskared = ''; $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i]) && isset($val[$k])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}

if(! function_exists("somenteNumero")) {
    function somenteNumero($str) {
        return (int) preg_replace("/[^0-9]/", "", $str);
    }
}

if(! function_exists('porcentagemComissao')) {

    /**
     * @param $valor_venda
     * @param $comissao
     * @return float
     */
    function porcentagemComissao($valor_venda, $comissao) {
        if($valor_venda == 0) return 0;
        return (float) number_format((($comissao / $valor_venda) * 100), 0,".", "");
    }
}

if(! function_exists('makeNestedList')) {
    function makeNestedList($array){
        $array = (array) $array;
        $html = '<ul class="list-tid">';
        foreach($array as $key => $value){
            if($value) {
                $html .= "<li><strong>{$key}: </strong>";
                if(is_array($value) || is_object($value)){
                    $html .= makeNestedList($value);
                }else{
                    $html .= "<strong class='text-secondary'>{$value}</strong>";
                }
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }
}

if(! function_exists('telefoneSomenteNumero')) {
    function telefoneSomenteNumero($telefone){
        $telefone = str_replace("(", "", $telefone);
        $telefone = str_replace(")", "", $telefone);
        $telefone = str_replace("-", "", $telefone);
        $telefone = str_replace(" ", "", $telefone);
        return $telefone;
    }
}

if(! function_exists('getUrlWhatsapp')) {
    function getUrlWhatsapp($telefone){

        return config('site.whatsapp.url') . telefoneSomenteNumero($telefone);
    }
}

if(! function_exists('simpleMail')) {
    function simpleMail($titulo, $texto, $destino){

        if(is_string($destino)) {
            Mail::raw($texto, function($message) use ($titulo, $destino) {
                $message->to($destino)
                    ->subject($titulo);
            });
        }

        if(is_array($destino)) {
            foreach($destino as $destino_atual) {
                Mail::raw($texto, function($message) use ($titulo, $destino_atual) {
                    $message->to($destino_atual)
                        ->subject($titulo);
                });
                sleep(3);
            }
        }
    }
}

if(! function_exists('isReservaFinalizada')) {
    function isReservaFinalizada(\TourFacil\Core\Models\ReservaPedido $reserva){
        return \TourFacil\Core\Services\FinalizacaoService::isReservaFinalizada($reserva);
    }
}

if(! function_exists('isReservaIntegrada')) {
    function isReservaIntegrada(\TourFacil\Core\Models\ReservaPedido $reserva){


        if($reserva->servico->integracao == 'NAO') {
            return false;
        }

        $integracao = $reserva->servico->integracao;

        if($integracao == IntegracaoEnum::SNOWLAND) {
            return !is_null($reserva->snowlandVoucher);
        }

        // if($integracao == IntegracaoEnum::BETO_CARRERO) {
        //     return is_null($reserva->betoCarreroVoucher);
        // }

        if($integracao == IntegracaoEnum::EXCEED_PARK) {
            return !is_null($reserva->exceedVoucher);
        }

        if($integracao == IntegracaoEnum::OLIVAS) {
            return !is_null($reserva->olivasVoucher);
        }

        if($integracao == IntegracaoEnum::MINI_MUNDO) {
            return !is_null($reserva->miniMundoVoucher);
        }

        if($integracao == IntegracaoEnum::DREAMS) {
            return !is_null($reserva->dreamsVoucher);
        }

        if($integracao == IntegracaoEnum::ALPEN) {
            return !is_null($reserva->alpenVoucher);
        }

        if($integracao == IntegracaoEnum::FANTASTIC_HOUSE) {
            return !is_null($reserva->fantasticHouseVoucher);
        }

        if($integracao == IntegracaoEnum::VILA_DA_MONICA) {
            return !is_null($reserva->vilaDaMonicaVoucher);
        }

        if($integracao == IntegracaoEnum::SKYGLASS) {
            return !is_null($reserva->integracaoPWI);
        }

        if($integracao == IntegracaoEnum::MATRIA) {
            return !is_null($reserva->matriaVoucher);
        }

        if($integracao == IntegracaoEnum::ACQUA_MOTION) {
            return !is_null($reserva->acquaMotionVoucher);
        }

        if($integracao == IntegracaoEnum::NBA_PARK) {
            return !is_null($reserva->nbaParkVoucher);
        }

        return false;
    }
}

if(! function_exists('isReservaIntegrada')) {
    function isReservaIntegrada(\TourFacil\Core\Models\ReservaPedido $reserva){

        if($reserva->servico->integracao == 'NAO') {
            return false;
        }

        $integracao = $reserva->servico->integracao;

        if($integracao == IntegracaoEnum::SNOWLAND) {
            return !is_null($reserva->snowlandVoucher);
        }

        // if($integracao == IntegracaoEnum::BETO_CARRERO) {
        //     return is_null($reserva->betoCarreroVoucher);
        // }

        if($integracao == IntegracaoEnum::EXCEED_PARK) {
            return !is_null($reserva->exceedVoucher);
        }

        if($integracao == IntegracaoEnum::OLIVAS) {
            return !is_null($reserva->olivasVoucher);
        }

        if($integracao == IntegracaoEnum::MINI_MUNDO) {
            return !is_null($reserva->miniMundoVoucher);
        }

        if($integracao == IntegracaoEnum::DREAMS) {
            return !is_null($reserva->dreamsVoucher);
        }

        if($integracao == IntegracaoEnum::ALPEN) {
            return !is_null($reserva->alpenVoucher);
        }

        if($integracao == IntegracaoEnum::FANTASTIC_HOUSE) {
            return !is_null($reserva->fantasticHouseVoucher);
        }

        if($integracao == IntegracaoEnum::VILA_DA_MONICA) {
            return !is_null($reserva->vilaDaMonicaVoucher);
        }

        if($integracao == IntegracaoEnum::SKYGLASS) {
            return !is_null($reserva->integracaoPWI);
        }

        if($integracao == IntegracaoEnum::MATRIA) {
            return !is_null($reserva->matriaVoucher);
        }

        if($integracao == IntegracaoEnum::ACQUA_MOTION) {
            return !is_null($reserva->acquaMotionVoucher);
        }

        if($integracao == IntegracaoEnum::NBA_PARK) {
            return !is_null($reserva->nbaParkVoucher);
        }

        return false;
    }
}

if(! function_exists('dateStringBRParaDate')) {
    function dateStringBRParaDate(String $data){
        $data = explode('/', $data);
        $data = $data[2] . "-" . $data[1] . "-" . $data[0];
        return Carbon\Carbon::parse($data);
    }
}

if(! function_exists('calculaParcelas')) {

    /**
     * O calculo da porcentagem de juros e feita
     * 1 - Numero da parcela multiplica pelo juros percentual
     * 2 - O valor com o juros é (valor informado * percuntual da parcela / 100) + valor informado
     *
     * @param $valor
     * @param $juros_parcela
     * @param $qtd_parcelas_sem_juros
     * @param $max_parcelas
     * @param bool $strict
     * @return array
     */
    function calculaParcelas($valor, $juros_parcela, $qtd_parcelas_sem_juros, $max_parcelas, $strict = false){
        return ParcelamentoService::calculaParcelas($valor, $juros_parcela, $qtd_parcelas_sem_juros, $max_parcelas);
    }
}

if(! function_exists('maskNumberCard')) {

    /**
     * Retorna somente alguns numeros do cartao de credito
     *
     * @param $number_card
     * @return string
     */
    function maskNumberCard($number_card) {

        $numeros = explode(" ", $number_card);

        if(! isset($numeros[1]) || ! isset($numeros[3])) {
            return $number_card;
        }

        return $numeros[0] . $numeros[1]{0} . $numeros[1]{1} . "****" . $numeros[3];
    }
}
