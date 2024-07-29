<?php namespace App\Http\Requests\Terminais;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioTerminaisRequest extends FormRequest
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
        $user_id = request()->get('usuario_id');

        return [
            'nome' => 'required',
            'email' => "required|email|unique:usuario_terminais,email,{$user_id},id",
            'usuario_id' => 'required|integer|exists:usuario_terminais,id',
        ];
    }
}
