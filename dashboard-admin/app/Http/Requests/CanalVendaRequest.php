<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CanalVendaRequest extends FormRequest
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
            'site' => 'required',
            'maximo_parcelas' => 'required|integer',
            'parcelas_sem_juros' => 'required|integer',
            'juros_parcela' => 'required',
        ];
    }
}
