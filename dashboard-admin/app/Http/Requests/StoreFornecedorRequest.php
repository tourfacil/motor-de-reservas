<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFornecedorRequest extends FormRequest
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
            'cnpj' => 'required|unique:fornecedores',
            'razao_social' => 'required',
            'nome_fantasia' => 'required',
            'responsavel' => 'required',
            'email_responsavel' => 'required',
            'telefone_responsavel' => 'required',
            'cep' => 'required',
            'endereco' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'email' => 'required',
            'estado' => 'required',
            'telefone' => 'required',
        ];
    }
}
