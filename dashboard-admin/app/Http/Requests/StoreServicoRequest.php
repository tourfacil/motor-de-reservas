<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServicoRequest extends FormRequest
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
        $name_slug = str_slug(request()->get('nome', ''));

        return [
            'destino_id' => 'required|integer|exists:destinos,id',
            'fornecedor_id' => 'required|integer|exists:fornecedores,id',
            'canal_venda_id' => [
                'required',
                'integer',
                'exists:canal_vendas,id',
                Rule::unique('servicos')->where('slug', $name_slug) // Não pode possuir slug duplicados para o mesmo canal de venda
            ],
            'nome' => 'required|max:255',
            'valor_venda' => 'required',
            'info_clientes' => 'required',
            'comissao_afiliado' => 'required',
            'palavras_chaves' => 'required|max:255',
            'antecedencia_venda' => 'required|integer',
            'tipo_corretagem' => 'required',
            'cidade' => 'required',
            'horario' => 'required',
            'descricao_curta' => 'required',
            'descricao_completa' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'canal_venda_id.unique' => 'Já existe um serviço com este nome para este canal de venda!',
        ];
    }
}
