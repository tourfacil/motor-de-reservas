<?php namespace App\Http\Requests\Banners;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerDestinoRequest extends FormRequest
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
            'banner_id' => 'required|integer|exists:banner_destinos,id',
            'titulo' => 'required|string|max:25',
            'descricao' => 'required|string|max:25',
            'ordem' => 'required',
            'banner' => 'image',
        ];
    }
}