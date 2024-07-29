<?php namespace App\Http\Requests\Clientes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDadosClienteRequest extends FormRequest
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
        return [
            'cliente_id' => 'required|integer|exists:clientes,id',
            'nome' => 'required',
            'email' => "required|email",
            'telefone' => 'required',
            'nascimento' => 'required',
            'cpf' => 'required',
        ];
    }
}
