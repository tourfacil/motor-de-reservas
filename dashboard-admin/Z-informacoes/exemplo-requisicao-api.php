 <?php
 
 $data = [

        'metodo_pagamento' => 'CREDITO',
        'pagamento' => [
            'cartao' => [
                'numero' => '4000000000000010',
                'nome' => 'Desenvolvimento Tour Fa',
                'cvv' => '123',
                'validade_ano' => '28',
                'validade_mes' => '12',
                'bandeira' => 'VISA',
            ],
            'parcelamento' => 1,
        ],
        'cliente' => [
            'nome' => '',
            'email' => '',
            'cpf' => '',
            'nascimento' => '',
            'telefone' => '',
            'rua' => '',
            'numero' => '',
            'bairro' => '',
            'cidade' => '',
            'estado' => '',
            'cep' => ''
        ],  
        'servicos' => [
            [
                'uuid' => '51b81af4-d8d6-11ea-804b-0242ac120006',
                'codigo_data' => 6185,
                'clientes' => [
                    [
                        'codigo_categoria' => 86,
                        'nome' => 'Desenvolvimento Tour Fá',
                        'documento' => '746239',
                        'nascimento' => '01/01/2000',
                    ],
                    [
                        'codigo_categoria' => 86,
                        'nome' => 'Tour Fácil DEV',
                        'documento' => '746239',
                        'nascimento' => '01/01/2000',
                    ],
                    [
                        'codigo_categoria' => 87,
                        'nome' => 'Tourfa',
                        'documento' => '746239',
                        'nascimento' => '01/01/2000',
                    ],

                ],
                'campos_adicionais' => [
                    [
                        'codigo_campo_adicional' => 23,
                        'campo' => 'Hotel',
                    ],
                    [
                        'codigo_campo_adicional' => 23,
                        'campo' => 'Hotel',
                    ],
                ],
            ],
            [
                'uuid' => '51b81af4-d8d6-11ea-804b-0242ac120006',
                'codigo_data' => 6185,
                'clientes' => [
                    [
                        'codigo_categoria' => 86,
                        'nome' => 'Desenvolvimento Tour Fá',
                        'documento' => '746239',
                        'nascimento' => '01/01/2000',
                    ],
                    [
                        'codigo_categoria' => 88,
                        'nome' => 'Tour Fácil DEV',
                        'documento' => '746239',
                        'nascimento' => '01/01/2000',
                    ],

                ],
                'campos_adicionais' => [
                    [
                        'codigo_campo_adicional' => 23,
                        'campo' => 'Hotel',
                    ],
                    [
                        'codigo_campo_adicional' => 23,
                        'campo' => 'Hotel',
                    ],
                ],
            ],
        ],
    ];