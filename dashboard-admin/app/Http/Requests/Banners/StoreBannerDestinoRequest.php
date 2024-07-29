<?php namespace App\Http\Requests\Banners;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerDestinoRequest extends FormRequest
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
            'destino_id' => 'required|integer|exists:destinos,id',
            'servico_id' => 'required|integer|exists:servicos,id',
            'titulo' => 'required|string|max:25',
            'descricao' => 'required|string|max:25',
            'ordem' => 'required',
            'banner' => 'required|image',
        ];
    }
}
