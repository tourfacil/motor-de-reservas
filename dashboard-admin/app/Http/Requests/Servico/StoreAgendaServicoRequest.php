<?php namespace App\Http\Requests\Servico;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgendaServicoRequest extends FormRequest
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
        $agenda_id = request()->get('agenda_id');

        // Validacao do servico
        $validate = ['servico_id' => 'required|integer|exists:servicos,id'];

        // Caso seja para criar uma nova agenda ou ligar a uma existente
        if(is_null($agenda_id)) {
            $validate['disponibilidade_minima'] = 'required|integer';
            $validate['compartilhada'] = 'required';
            $validate['dias_semana'] = 'required|array';
        } else {
            $validate['agenda_id'] = 'required|integer|exists:agenda_servicos,id';
        }

        return $validate;
    }
}