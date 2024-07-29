<?php namespace App\Http\Requests\Servico;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDataAgendaRequest extends FormRequest
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
            'date_end' => 'required',
            'date_start' => 'required',
            'dias_semana' => 'required|array',
            'agenda_id' => 'required|integer|exists:agenda_servicos,id',
        ];
    }
}