<?php namespace App\Http\Requests\Reserva;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateAcompanhanteRequest
 * @package App\Http\Requests\Reserva
 */
class UpdateAcompanhanteRequest extends FormRequest
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
            'acompanhante_id' => 'required|integer|exists:dado_cliente_reserva_pedidos,id',
            'nome' => 'required',
            'documento' => 'required',
            'nascimento' => 'required',
        ];
    }
}
