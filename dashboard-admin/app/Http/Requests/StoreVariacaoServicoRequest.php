<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVariacaoServicoRequest extends FormRequest
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
            'servico_id' => 'required|integer|exists:servicos,id',
            'nome' => 'required',
            'net_servico' => 'required',
            'net_variacao' => 'required',
            'venda_variacao' => 'required',
            'consome_bloqueio' => 'required',
            'descricao' => 'required',
            'min_pax' => 'required|integer|min:0',
        ];
    }
}
