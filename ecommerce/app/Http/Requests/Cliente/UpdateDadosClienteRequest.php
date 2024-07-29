<?php namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use TourFacil\Core\Services\Cache\DefaultCacheService;

class UpdateDadosClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('clientes')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $cliente = auth('clientes')->user();

        return [
            'nome' => 'required|string|min:3|max:150|regex:/^[^<]*$/',
            'cpf' => 'required|string|min:11|max:14|regex:/^[^<]*$/',
            'nascimento' => 'required|string|min:8|max:10|regex:/^[^<]*$/',
            'telefone' => 'required|string|min:11|max:16|regex:/^[^<]*$/',
            'email' => [
                "required", "email", "min:3", "max:150",
                Rule::unique('clientes', 'email')->where(function ($query) use ($cliente) {
                    return $query->where('id', '<>', $cliente->id)
                        ->where('canal_venda_id', DefaultCacheService::getCanalVenda());
                }),
                'regex:/^[^<]*$/',
            ]
        ];
    }
}
