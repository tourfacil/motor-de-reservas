<?php namespace App\Enum;

/**
 * Class DestinoBreveEnum
 * @package App\Enum
 */
abstract class DestinoBreveEnum
{
    /**
     * Destinos em breve
     *
     * @var array
     */
    const BREVE_DESTINOS = [
        [
            'nome' => 'Bento Gonçalves',
            'image' => '/images/breve-destinos/bento.jpg'
        ],
        [
            'nome' => 'Cambará do Sul',
            'image' => '/images/breve-destinos/cambara-do-sul.jpg'
        ],
        [
            'nome' => 'São Francisco de Paula',
            'image' => '/images/breve-destinos/sao-francisco.jpg'
        ],
    ];
}
