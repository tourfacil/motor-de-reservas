<?php

return [
    'numero_whatsapp_formatado' => '(54) 9319-3000',
    'numero_whatsapp' => '555493193000',

    'numeros_vendedoras' => [
        '5554997051306',
        '5554999247223'
    ],

    'numero_vendedora_atual' => function () {
        $randomIndex = array_rand(config('site.numeros_vendedoras'));
        return config('site.numeros_vendedoras')[$randomIndex];
    },

    'numero_suporte' => '5554999261807',
    'numero_outros' => '5554999261807',

    'whatsapp_text_message_parameter' => 'text=Olá!%20Vim%20pelo%20site.%20Preciso%20dicas%20e%20informações%20para%20a%20minha%20viagem.%20Pode%20me%20ajudar%3F',

    'whatsapp_text_message_comercial' => 'Olá!%20Vim%20pelo%20site.%20Preciso%20de%20suporte%20comercial%20para%20a%20minha%20viagem.%20Pode%20me%20ajudar%3F',
    'whatsapp_text_message_suporte' => 'Olá!%20Vim%20pelo%20site.%20Preciso%20de%20suporte%20para%20a%20minha%20viagem.%20Pode%20me%20ajudar%3F',
    'whatsapp_text_message_outros' => 'Olá!%20Vim%20pelo%20site.%20Preciso%20de%20uma%20ajuda%20não%20listada.%20Pode%20me%20ajudar%3F',

    'instagram' => 'https://www.instagram.com/tourfaciloficial/',
    'twitter' => 'https://twitter.com/tourfacilbr',
    'facebook' => 'https://facebook.com/tourfaciloficial',
    'youtube' => '#',
    'parcelamento' => 12,


    'admin_ecommerce_api' => [
        'key_code' => 'Yuyo!Ye%pe7MZrE95RfMu^T7',
    ],


    // Esta configuração esta sendo usada em NovaVendaJob
    'notificacao_email' => [
        'path_log' => '/notificacao-email/log.txt'
    ],



/**********************************************************
 *                        ATENÇÃO                         *
 *             ARRAY MAIS IMPORTANTE DE TODOS             *
 *  PASSA PARA O SITE O VALOR PERCENTUAL DO PARCELAMENTO  *
 * MUITO CUIDADO AO FAZER ALTERAÇÕES AQUI QUE PODE CAUSAR *
 *           BUGS NA TELA DE PAGAMENTO E OUTROS           *
 *                        CUIDADO                         *
 **********************************************************/
    'juros_parcelas' => [
        '1' => 0,
        '2' => 4.84,
        '3' => 5.75,
        '4' => 6.68,
        '5' => 7.62,
        '6' => 8.58,
        '7' => 9.55,
        '8' => 10.55,
        '9' => 11.56,
        '10' => 12.59,
        '11' => 13.64,
        '12' => 14.71
    ],
];
