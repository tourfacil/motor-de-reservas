<?php namespace App\Http\Requests\Fornecedor;

use Illuminate\Foundation\Http\FormRequest;

class StoreSplitRequest extends FormRequest
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
            'fornecedor_id' => 'required|integer|exists:fornecedores,id',
            'canal_venda_id' => 'required|integer|exists:canal_vendas,id',
            'token' => 'required|min:4'
        ];
    }
}
