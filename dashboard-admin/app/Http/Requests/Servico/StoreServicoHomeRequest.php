<?php namespace App\Http\Requests\Servico;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicoHomeRequest extends FormRequest
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
            'destino_id' => 'required|integer|exists:destinos,id',
            'servicos' => 'required|array',
            'titulo' => 'required|string|max:40',
            'descricao' => 'max:70',
            'ordem' => 'required|numeric',
            'tipo' => 'required|string',
        ];
    }
}
