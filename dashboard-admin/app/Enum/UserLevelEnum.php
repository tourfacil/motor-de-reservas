<?php namespace App\Enum;

/**
 * Class UserLevelEnum
 * @package App\Enum
 */
abstract class UserLevelEnum
{
    const ADMIN = "ADMIN";

    const BASICO = "BASICO";

    const MEDIO = "MEDIO";

    const VENDEDOR = "VENDEDOR";

    const AFILIADO = "AFILIADO";

    const INTEGRADOR = "INTEGRADOR";

    const LEVELS = [
        self::BASICO => "Básico",
        self::MEDIO => 'Médio',
        self::ADMIN => "Administrador",
        self::VENDEDOR => "Vendedor",
        self::AFILIADO => "Afiliado",
        self::INTEGRADOR => "Integrador",
    ];
}
