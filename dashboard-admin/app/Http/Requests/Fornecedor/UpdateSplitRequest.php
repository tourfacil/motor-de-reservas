<?php namespace App\Http\Requests\Fornecedor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSplitRequest extends FormRequest
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
            'split_pagamento_id' => 'required|integer|exists:split_fornecedores,id',
            'token' => 'required|min:4'
        ];
    }
}
