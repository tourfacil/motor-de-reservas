<?php

namespace App\Http\Requests\Fornecedor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsuarioRequest extends FormRequest
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
        $usuario_id = request()->get('usuario_id');

        return [
            'usuario_id' => 'required|exists:usuario_fornecedores,id',
            'nome' => 'required',
            'email' => "required|email|unique:usuario_fornecedores,email,{$usuario_id},id",
            'level' => 'required',
        ];
    }
}
