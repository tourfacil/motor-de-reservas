<?php


namespace App\Http\Controllers\Servicos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\RegraServicoEnum;
use TourFacil\Core\Enum\StatusEnum;
use TourFacil\Core\Models\RegraServico;

class RegraServicoController extends Controller
{
    public function storeOuUpdate(Request $request) {

        $regra_servico_id = $request->get('regra_ant_regra_id');

        $valor = $request->get('regra_ant_valor');
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);

        $dados_regra_servico = [
            'servico_id' => $request->get('regra_ant_servico_id'),
            'tipo_regra' => RegraServicoEnum::VALOR_EXCECAO_DIA,
            'status' => StatusEnum::ATIVA,
            'regras' => [
                'valor' => $valor,
                'antecedencia' => $request->get('regra_ant_antecedencia'),
                'tipo_valor_servico' => 'FIXO',
            ],
            'prioridade' => 1
        ];

        $regra_servico = RegraServico::find($regra_servico_id);

        if($regra_servico == null) {

            RegraServico::create($dados_regra_servico);

        } else {

            $regra_servico->update($dados_regra_servico);

        }

        return $this->autoResponseJson(true, "Serviço atualizado", "As informações do serviço foram salvas com sucesso!");
    }
}
