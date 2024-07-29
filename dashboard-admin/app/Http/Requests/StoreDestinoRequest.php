<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDestinoRequest extends FormRequest
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
        $name_slug = str_slug(request()->get('nome', ''));

        return [
            'canal_venda_id' => [
                'required',
                'integer',
                'exists:canal_vendas,id',
                Rule::unique('destinos')->where('slug', $name_slug) // Não pode possuir slug duplicados para o mesmo canal de venda
            ],
            'nome' => 'required',
            'descricao_curta' => 'required',
            'descricao_completa' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'canal_venda_id.unique' => 'Já existe um destino com este nome!',
        ];
    }
}
