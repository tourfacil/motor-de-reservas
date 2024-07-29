<?php namespace App\Http\Requests\Terminais;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTerminaisRequest extends FormRequest
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
            'nome' => 'required',
            'identificacao' => 'required',
            'fornecedor_id' => 'required|integer|exists:fornecedores,id',
            'destino_id' => 'required|integer|exists:destinos,id',
            'fabricante' => 'required',
            'nome_responsavel' => 'required',
            'email_responsavel' => 'required|email',
            'telefone_responsavel' => 'required',
            'endereco_mapa' => 'required',
            'nome_local' => 'required',
            'endereco' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'geolocation' => 'required',
        ];
    }
}
