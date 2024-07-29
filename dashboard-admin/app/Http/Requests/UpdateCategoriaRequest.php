<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaRequest extends FormRequest
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

        $categoria_id = request()->get('categoria_id', '');

        return [
            'destino_id' => [
                'required',
                'integer',
                'exists:destinos,id',
                Rule::unique('categorias')
                    ->where('slug', $name_slug)
                    ->whereNot('id', $categoria_id) // Não pode possuir slug duplicados para o mesmo destino
            ],
            'nome' => 'required',
            'descricao' => 'required',
            'posicao_menu' => 'required|integer',
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
            'destino_id.unique' => 'Já existe uma categoria para este destino com este nome!',
        ];
    }
}
