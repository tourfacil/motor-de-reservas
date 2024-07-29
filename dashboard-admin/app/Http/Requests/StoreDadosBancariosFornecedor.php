<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDadosBancariosFornecedor extends FormRequest
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
            'banco' => 'required',
            'agencia' => 'required',
            'conta' => 'required',
            'tipo_conta' => 'required',
            'fornecedor_id' => 'required|integer|exists:fornecedores,id',
        ];
    }
}
