<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVariacaoServicoRequest extends FormRequest
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
            'variacao_id' => 'required|integer|exists:variacao_servicos,id',
            'nome' => 'required',
            'consome_bloqueio' => 'required',
            'descricao' => 'required',
            'min_pax' => 'required|integer|min:0',
        ];
    }
}
