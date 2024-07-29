<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoriaServicoRequest extends FormRequest
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
        $servico_id = request()->get('servico_id');

        return [
            'categoria_id' => [
                'required',
                'exists:categorias,id',
                Rule::unique('categoria_servico')->where('servico_id', $servico_id)
            ],
            'servico_id' => 'required|exists:servicos,id',
            'secoes' => 'required|array',
            'padrao' => 'required'
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
            'categoria_id.unique' => 'Essa categoria já está ligada a este serviço!',
        ];
    }
}
