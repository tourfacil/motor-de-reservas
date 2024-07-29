<?php

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

if(! function_exists('saudacao')) {

    /**
     * Saudacao bom dia, boa tarde ou boa noite
     *
     * @return string
     */
    function saudacao(){
        $hour = date('H');
        if($hour >= 00 && $hour <= 12) {
            return "Bom dia";
        } elseif($hour > 12 && $hour <= 18) {
            return "Boa tarde";
        } else {
            return "Boa noite";
        }
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

if(! function_exists('getUserIP')) {

    /**
     * Retorna o IP do cliente
     *
     * @return mixed
     */
    function getUserIP(){
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
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

if(! function_exists('fornecedor')) {

    function fornecedor(){
        return session()->get('ses_fornecedor');
    }
}


