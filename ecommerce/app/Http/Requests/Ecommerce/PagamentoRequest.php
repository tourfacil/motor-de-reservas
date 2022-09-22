<?php namespace App\Http\Requests\Ecommerce;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use TourFacil\Core\Enum\MetodoPagamentoEnum;
use TourFacil\Core\Services\Cache\DefaultCacheService;

class PagamentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $email_validation = "";
        // Enums para formas de pagamento
        $enum_cartao = MetodoPagamentoEnum::CARTAO_CREDITO;

        if(! auth('clientes')->check()) {
            $email_validation = [
                "required", "email", "min:3", "max:150",
                Rule::unique('clientes', 'email')->where(function ($query) {
                    return $query->where('canal_venda_id', DefaultCacheService::getCanalVenda());
                })
            ];
        }

        return [
            // Validacao do cliente
            'cliente' => 'required|array',
            'cliente.nome' => 'required|string|min:3|max:150',
            'cliente.cpf' => 'required|string|min:11|max:14',
            'cliente.nascimento' => 'required|string|min:8|max:10',
            'cliente.telefone' => 'required|string|min:11|max:16',
            'cliente.email' => $email_validation,

            'cliente.rua' => 'required|string|min:3|max:255',
            'cliente.numero' => 'required|string|min:2|max:10',
            'cliente.bairro' => 'required|string|min:3|max:255',
            'cliente.cidade' => 'required|string|min:3|max:255',
            'cliente.estado' => 'required|string|min:2|max:3',
            'cliente.cep'    => 'required|string|min:9|max:10',

            // Metodo de pagamento
            'metodo_pagamento' => "required",

            // Validacao do cartao de credito
            'credito.numero_cartao' => "required_if:metodo_pagamento,$enum_cartao",
            'credito.nome_cartao' => "required_if:metodo_pagamento,$enum_cartao",
            'credito.validade_mes_cartao' => "required_if:metodo_pagamento,$enum_cartao",
            'credito.validade_ano_cartao' => "required_if:metodo_pagamento,$enum_cartao",
            'credito.codigo_cartao' => "required_if:metodo_pagamento,$enum_cartao",
            'credito.parcelas' => "required_if:metodo_pagamento,$enum_cartao|int",
            'credito.bandeira_cartao' => "required_if:metodo_pagamento,$enum_cartao",
        ];
    }
}
