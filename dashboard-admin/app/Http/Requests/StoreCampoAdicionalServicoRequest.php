<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCampoAdicionalServicoRequest extends FormRequest
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
            'servico_id' => 'required|exists:servicos,id',
            'campo' => 'required|max:255',
            'obrigatorio' => 'required',
            'placeholder' => 'required|max:255',
        ];
    }
}