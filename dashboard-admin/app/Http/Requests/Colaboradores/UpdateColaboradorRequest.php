<?php namespace App\Http\Requests\Colaboradores;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColaboradorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return userIsAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $colaborador_id = request()->get('colaborador_id');

        return [
            'colaborador_id' => 'required|exists:users,id',
            'name' => 'required',
            'email' => "required|email|unique:users,email,{$colaborador_id},id",
            'level' => 'required'
        ];
    }
}
