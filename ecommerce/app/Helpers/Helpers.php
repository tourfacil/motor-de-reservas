<?php
use TourFacil\Core\Services\ParcelamentoService;

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

if(! function_exists('iconeCategoria')) {

    /**
     * Retorna o icone conforme o slug da categoria
     *
     * @param $categoria_slug
     * @return mixed
     */
    function iconeCategoria($categoria_slug) {
        $icones = [
            'ingressos' => 'tourfacil:ingressos',
            'ingresso' => 'tourfacil:ingressos',
            'passeios' => 'tourfacil:passeios',
            'passeio' => 'tourfacil:passeios',
            'transfers' => 'tourfacil:transfers',
            'transfer' => 'tourfacil:transfers',
            'gastronomia' => 'tourfacil:gastronomia',
            'restaurantes' => 'tourfacil:gastronomia',
            'restaurante' => 'tourfacil:gastronomia',
            'pacotes' => 'tourfacil:pacotes',
            'pacote' => 'tourfacil:pacotes',
            'sales' => 'tourfacil:promocao',
            'sale' => 'tourfacil:promocao',
        ];

        return $icones[$categoria_slug] ?? $icones['sale'];
    }
}

if(! function_exists('is_mail')) {

    /**
     * Retorna se o e-mail é valido
     *
     * @param $email
     * @return false|int
     */
    function is_mail($email) {
        return (preg_match("/^([[:alnum:]_.-]){3,}@([[:lower:][:digit:]_.-]{3,})(.[[:lower:]]{2,3})(.[[:lower:]]{2})?$/", $email));
    }
}


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

if(! function_exists('somenteNome')) {
    /**
     * Retorna somente o nome da pessoa
     *
     * @param $nome
     * @return mixed
     */
    function somenteNome($nome) {
        return formatarNome(explode(' ', $nome)[0]);
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
        if($limit) return Str::limit($mes, 3, "");
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
        if($limit) return Str::limit($semana, 3, "");
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
            $semana = ($data->isMonday()) ? "Segunda" : $semana;
        }

        return formatarNome($semana . ", " . $data->day . " " . $mes . " " . $data->year);
    }
}

if(! function_exists('dateFormat')) {

    /**
     * Retorna a data formatada
     *
     * @param $date
     * @param string $format
     * @return string
     */
    function dateFormat($date, $format = 'd/m/Y') {
        $date = \Carbon\Carbon::parse($date);
        return $date->format($format);
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

        return $numeros[0] . $numeros[1][0] . $numeros[1][1] . "****" . $numeros[3];
    }
}

if(! function_exists('tirarAcentos')) {

    /**
     * Retirar os acentos das palavras
     *
     * @param $string
     * @return mixed
     */
    function tirarAcentos($string){
        $string = preg_replace('/[áàãâä]/ui', 'a', $string);
        $string = preg_replace('/[éèêë]/ui', 'e', $string);
        $string = preg_replace('/[íìîï]/ui', 'i', $string);
        $string = preg_replace('/[óòõôö]/ui', 'o', $string);
        $string = preg_replace('/[úùûü]/ui', 'u', $string);
        $string = preg_replace('/[ç]/ui', 'c', $string);
        return $string;
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

if(! function_exists('carrinho')) {

    /**
     * Função para o carrinho de compras
     *
     * @return \App\Helpers\Carrinho
     */
    function carrinho() {
        return new \App\Helpers\Carrinho();
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

if(! function_exists("somenteNumero")) {
    function somenteNumero($str) {
        return (int) preg_replace("/[^0-9]/", "", $str);
    }
}


if(! function_exists('tirarAcentos')) {

    /**
     * Retirar os acentos das palavras
     *
     * @param $string
     * @return mixed
     */
    function tirarAcentos($string){
        $string = mb_strtolower($string);
        $string = preg_replace('/[áàãâä]/ui', 'a', $string);
        $string = preg_replace('/[éèêë]/ui', 'e', $string);
        $string = preg_replace('/[íìîï]/ui', 'i', $string);
        $string = preg_replace('/[óòõôö]/ui', 'o', $string);
        $string = preg_replace('/[úùûü]/ui', 'u', $string);
        $string = preg_replace('/[ç]/ui', 'c', $string);
        return $string;
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
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}

if(! function_exists('lastDigits')) {

    /**
     * @param $number
     * @param int $length
     * @return bool|string
     */
    function lastDigits($number, $length = 4){
        return substr($number, -$length);
    }
}

if(! function_exists('toCents')) {

    /**
     * @param $price
     * @return int
     */
    function toCents($price) {
        return (int) number_format($price * 100, 0, "", "");
    }
}

if(! function_exists('dataParaLog')) {

    /**
     * @param $price
     * @return int
     */
    function dataParaLog() {
        return '[' . Carbon\Carbon::now()->format('d/m/Y - H:i:s') . ']';
    }
}

if(! function_exists('breakWord')) {

    /**
     * @param $word
     * @return mixed|string
     */
    function breakWord($word) {

        $parts = explode(" ", $word);
        $return = "";

        if(isset($parts[1])) {
            if(strlen($parts[1]) <= 2) {
                $return .= "<span>{$parts[0]} {$parts[1]}</span> ";
                unset($parts[0], $parts[1]);
                $return .= implode(" ", $parts);
            } else {
                $return .= "<span>{$parts[0]}</span> ";
                unset($parts[0]);
                $return .= implode(" ", $parts);
            }
        } else {
            $return = $parts[0];
        }

        return $return;
    }
}

if(! function_exists('isPixAtivo')) {

    /**
     * @param $word
     * @return mixed|string
     */
    function isPixAtivo() {
        return env('PIX_ENABLED') === true;
    }
}

if(! function_exists('isReservaFinalizada')) {

    /**
     * @param $word
     * @return mixed|string
     */
    function isReservaFinalizada($reserva) {
        return \TourFacil\Core\Services\FinalizacaoService::isReservaFinalizada($reserva);
    }
}

if(! function_exists('isTesteAB')) {

    /**
     * @param $word
     * @return mixed|string
     */
    function isTesteAB(String $key, String $value) {
        return app('request')->input($key) == $value;
    }
}

if(! function_exists('evitarValorMenorQueUm')) {

    /**
     * Função para garantir que o valor inserido não baixe de R$ 1,00
     *
     * @param $valor
     * @return int|mixed
     */
    function evitarValorMenorQueUm($valor) {

        if($valor >= 1) {
            return $valor;
        } else {
            return 1;
        }
    }
}

if(! function_exists('getTituloPaginaServico')) {

    /**
     * Caso o serviço tenha um título de pagina o retorna.
     * Caso não tenha, retorna o próprio nome.
     * @param \TourFacil\Core\Models\Servico $servico
     * @return mixed
     */
    function getTituloPaginaServico(\TourFacil\Core\Models\Servico $servico) {
        if($servico->titulo_pagina) {
            return $servico->titulo_pagina;
        }

        return $servico->nome . ' - Tour Fácil';
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




