<?php

namespace App\Http\Controllers\APIExpanda;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Services\AgendaService;

class ServicosControllerExpanda extends Controller
{
    /**
     * Summary of servicos
     * Retorna todos os servicos do site que estejam ativos
     * @param Request $request
     * @return mixed
     */
    public function servicos(Request $request)
    {
        // Busca os servicos
        $servicos = Servico::where('status', 'ATIVO')
        ->get();

        // Array para guardar os servicos
        $retorno = [];

        // Roda os servicos para montar um array somente com os dados necessarios
        foreach($servicos as $servico) {
            $retorno[] = [
                'uuid' => $servico->uuid,
                'nome' => $servico->nome,
                'slug' => $servico->slug,
                'cidade' => $servico->cidade,
                'foto' => env('CDN_URL') . $servico->fotoPrincipal->foto['MEDIUM'],
                'valor' => $servico->valor_venda
            ];
        }

        // Retorna a resposta
        return response($retorno, 200);
    }

    /**
     * Summary of servico
     * Retorna os dados de um servico
     * @param Request $request
     * @return mixed
     */
    public function servico(Request $request)
    {

        // Valid a arequisicao
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string|size:36',
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        // Busca o servico
        $servico = Servico::where('uuid', $request->get('uuid'))->get()->first();

        // Caso o servico não exista, retorna 404
        if(!$servico) {
            return response(['info' => 'Produto não encontrado'], 404);
        }

        // Monta o array de retorno somente com os dados mais importantes e uteis
        $retorno = [
            'uuid' => $servico->uuid,
            'nome' => $servico->nome,
            'slug' => $servico->slug,
            'cidade' => $servico->cidade,
            'valor' => $servico->valor_venda,
            'horario' => $servico->horario,
        ];
        
        // Coloca as fotos no array
        foreach($servico->fotos as $foto) {
            $retorno['fotos'][] = env('CDN_URL') . $foto->foto['MEDIUM'];
        }

        // Adiciona as informacoes maiores no array
        $retorno['descricao_curta'] = $servico->descricao_curta;
        $retorno['descricao_completa'] = $servico->descricao_completa;
        $retorno['regras'] = $servico->regras;
        $retorno['observacao_voucher'] = $servico->observacao_voucher;
        $retorno['localizacao'] = $servico->localizacao;

        // Retorna o array
        return response($retorno, 200);
    }

    /**
     * Summary of disponibilidade
     * Retorna as disponibilidades daquele servico
     * @param Request $request
     * @return mixed
     */
    public function disponibilidade(Request $request)
    {

        // Valida a requisicao
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string|size:36',
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        // Busca o servico
        $servico = Servico::where('uuid', $request->get('uuid'))->get()->first();

        // Caso o servico nao for encontrado, retorna 404
        if(!$servico) {
            return response(['info' => 'Produto não encontrado'], 404);
        }

        // Busca a dispobilidade do servico e cria um array para guardar o retorno
        $agenda = AgendaService::disponibilidadeSite($servico->uuid);
        $retorno = [];

        // Contador para o array
        $contador = 0;

        // Roda todos os dias do foreach
        foreach($agenda['disponibilidade'] as $dia)  {

            // Caso a disponibilidade seja menor ou igual a zero continua
            if($dia['disponibilidade'] <= 0) {
                continue;
            }

            // Coloca o código e a data no array
            $retorno[$contador]['codigo_data'] = $dia['data_servico_id'];
            $retorno[$contador]['data'] = $dia['data'];

            // Array interno para guardar as variacoes
            $variacoes_dia_retorno = [];
            
            // Roda as variacoes
            foreach($dia['variacoes'] as $variacao_dia) {

                // Coloca as variacoes dentro do retorno
                $variacoes_dia_retorno[] = [
                    'codigo_categoria' => $variacao_dia['variacao_id'],
                    'nome' => $variacao_dia['variacao'],
                    'info' => $variacao_dia['descricao'],
                    'valor_venda' => $variacao_dia['valor_venda'],
                ];
            }

            // Adiciona as variacoes na categoria
            $retorno[$contador]['categorias'] = $variacoes_dia_retorno;
            $contador++;
        }

        // Retorna
        return response($retorno, 200);
    }
}
