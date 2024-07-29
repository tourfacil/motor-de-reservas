<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDadosUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = auth()->user()->id;

        return [
            'nome' => 'required',
            'email' => "required|email|unique:usuario_fornecedores,email,{$user},id",
        ];
    }
}
