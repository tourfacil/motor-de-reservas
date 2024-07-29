<?php

namespace App\Services;

use Carbon\Carbon;
use TourFacil\Core\Enum\AgendaEnum;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Enum\VariacaoServicoEnum;
use TourFacil\Core\Models\AgendaDataServico;
use TourFacil\Core\Models\VariacaoServico;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Services\AgendaService;
use TourFacil\Core\Services\RegraServico\ValorExcecaoDiaService;


abstract class ConferenciaReserva
{
    // Método resposanvel por garantir que o produto do carrinho esteja com o valor atualizado e
    // que ainda tenham vagas disponiveis
    /**
     * @return array
     */
    public static function confereValorEVagasDaSessao() {

        $carrinho = carrinho()->all();

        $resposta = [];

        foreach($carrinho as $servico_sessao) {

            // Recupera o serviço e sua agenda direto do banco de dados para garantir que as informações estejam atualizadas

            $servico_db = Servico::find($servico_sessao['gtin']);
            $agenda_servico_db = AgendaDataServico::find($servico_sessao['agenda_selecionada']['data_servico_id']);

            // Guarda algumas informações que podem ser uteis para quem recer a resposta

            $resposta[$servico_db->id]['info']['servico_db'] = $servico_db;
            $resposta[$servico_db->id]['info']['agenda_servido_db'] = $agenda_servico_db;


            // Verifica se o produto ainda tem disponibilidade

            $quantidade_disponivel_db = $agenda_servico_db->disponivel - $agenda_servico_db->consumido;

            // Roda todas os clientes para ver quantos clientes que consomem bloqueio estão no passeio

            $quantidade_clientes_sessao = 0;

            foreach($servico_sessao['acompanhantes'] as $pessoa_sessao) {
                $variacao_servico = VariacaoServico::find($pessoa_sessao['variacao_servico_id']);

                if($variacao_servico->consome_bloqueio == "SIM") {
                    $quantidade_clientes_sessao++;
                }
            }

            if(($quantidade_disponivel_db - $quantidade_clientes_sessao) < 0) {
                $resposta[$servico_db->id]['falta_vagas'] = true;
            } else {
                $resposta[$servico_db->id]['falta_vagas'] = false;
            }

            // Verifica se o produto esta com o valor atualizado

            $valor_venda_sessao = $servico_sessao['agenda_selecionada']['valor_venda'];
            $valor_venda_db = $agenda_servico_db->valor_venda;

            if($valor_venda_sessao != $valor_venda_db) {
                $resposta[$servico_db->id]['valor_diferente'] = true;
            } else {
                $resposta[$servico_db->id]['valor_diferente'] = false;
            }
        }

        return $resposta;
    }

    /**
     * Método que válida o carrinho
     * Faz as seguintes verificações: serviço ativo, agenda ativa, dentro da antecedencia, mesmo valor, disponibilidade
     * @return array
     */
    public static function validarCarrinho() {

        $carrinho = carrinho()->all();

        $servicos_indisponiveis = [];
        $servicos_indisponiveis['problemas'] = 0;

        foreach ($carrinho as $servico_carrinho) {

            // Busca o serviço no banco de dados
            $servico_db = Servico::find($servico_carrinho['gtin']);

            // Busca a agenda no banco de dados
            $agenda_db = AgendaDataServico::find($servico_carrinho['agenda_selecionada']['data_servico_id']);

            // Variavel que guarda quantos problemas este produto irá apresentar
            $problemas_servico = 0;

            // Cria uma estrutura de array para depois usar na resposta e guardar os problemas
            // Ele cria para todos os produtos, mas caso o mesmo não apresente problema, será removido mais a frente.
            $servicos_indisponiveis['servicos'][$servico_db->slug] = [
                'motivo' => [],
                'servico' => $servico_db,
            ];

            // Verifica se o serviço esta com status ativo ou invisivel
            if(!self::isServicoAtivo($servico_db)) {
                $servicos_indisponiveis['servicos'][$servico_db->slug]['motivo'][] = "O serviço não esta mais disponível.";
                $servicos_indisponiveis['problemas']++;
                $problemas_servico++;
            }

            // Verifica se a agenda selecionada esta com status ativo
            if(!self::isAgendaAtiva($agenda_db)) {
                $servicos_indisponiveis['servicos'][$servico_db->slug]['motivo'][] = "A data selecionada não esta mais disponível.";
                $servicos_indisponiveis['problemas']++;
                $problemas_servico++;
            }

            if(!self::isAgendaComVagas($agenda_db, $servico_carrinho['agenda_selecionada']['variacoes'])) {
                $servicos_indisponiveis['servicos'][$servico_db->slug]['motivo'][] = "A data selecionada não tem mais vagas.";
                $servicos_indisponiveis['problemas']++;
                $problemas_servico++;
            }

            if(!self::isDentroAntecedencia($agenda_db, $servico_db)) {
                $servicos_indisponiveis['servicos'][$servico_db->slug]['motivo'][] = "Período de antecedencia do serviço vencido.";
                $servicos_indisponiveis['problemas']++;
                $problemas_servico++;
            }

            if(!self::isServicoMesmoValor($agenda_db, $servico_db, $servico_carrinho['agenda_selecionada']['variacoes'])) {
                $servicos_indisponiveis['servicos'][$servico_db->slug]['motivo'][] = "O valor do serviço foi alterado.";
                $servicos_indisponiveis['problemas']++;
                $problemas_servico++;
            }

            // Caso este serviço não apresente problemas, será removido seu indice da lista de serviços indisponiveis
            if($problemas_servico == 0) {
                unset($servicos_indisponiveis['servicos'][$servico_db->slug]);
            }
        }

        self::removerServicosIndisponiveis($servicos_indisponiveis);

        return $servicos_indisponiveis;
    }

    /**
     * Retorna se o serviço ainda esta ativo
     * @param Servico $servico
     * @return bool
     */
    private static function isServicoAtivo(Servico $servico) {
        return $servico->status == ServicoEnum::ATIVO || $servico->status == ServicoEnum::INVISIVEL;
    }

    /**
     * Retorna se a agenda ainda esta ativa
     * @param AgendaDataServico $agenda
     * @return bool
     */
    private static function isAgendaAtiva(AgendaDataServico $agenda) {
        return $agenda->status == AgendaEnum::ATIVO;
    }

    /**
     * Retorna se a agenda ainda tem vagas suficientes para a configuração do carrinho
     * @param AgendaDataServico $agenda
     * @param array $variacoes
     * @return bool
     */
    private static function isAgendaComVagas(AgendaDataServico $agenda, Array $variacoes) {

        // Variavel para calcular a quantidade que esta no carrinho
        $quantidade_carrinho = 0;

        // Percorre todas as variacoes para contar as quantidades
        foreach($variacoes as $variacao) {

            // Verifica se a variavel quantidade esta setada.
            // Em casos onde a variacao não tenha pessoas, pode estar como null
            if(!array_key_exists("quantidade", $variacao)) {
                continue;
            }

            // Verifica se a quantidade consome bloqueio e então coloca na conta
            if($variacao['bloqueio'] == VariacaoServicoEnum::CONSOME_BLOQUEIO) {
                $quantidade_carrinho += $variacao['quantidade'];
            }
        }

        // Calcula a quantidade disponivel na agenda do banco
        $agenda_disponivel = $agenda->disponivel - $agenda->consumido;

        // Verifica se o número de pessoas do carrinho cabe na quantidade disponivel da agenda do serviço
        if($agenda_disponivel - $quantidade_carrinho >= 0) {
            return true;
        }

        return false;
    }

    /**
     * Retorna se o serviço esta dentro do periodo de antecedencia na data selecionada
     * @param AgendaDataServico $agenda
     * @param Servico $servico
     * @return bool
     */
    private static function isDentroAntecedencia(AgendaDataServico $agenda, Servico $servico) {

        $hoje = Carbon::today();
        $tempo_limite = $agenda->data->subDays($servico->antecedencia_venda);

        if($hoje->isAfter($tempo_limite)) {
            return false;
        }

        return true;
    }

    /**
     * Retorna se o serviço do carrinho não teve seu valor alterado
     * @param AgendaDataServico $agenda
     * @param Servico $servico
     * @param array $variacoes_carrinho
     * @return bool
     */
    private static function isServicoMesmoValor(AgendaDataServico $agenda, Servico $servico, Array $variacoes_carrinho) {

        // Busca o valor do serviço atual no DB e torna as variações uma coleção
        $valores_db = AgendaService::disponibilidadeDia($agenda, $servico)['disponibilidade'][0];

        $variacoes_db = Collect($valores_db['variacoes']);

        // Variavel que guarda se alguma das variações mudar de valor
        $valor_valido = true;

        // Roda todas as variacoes do serviço do carrinho
        foreach($variacoes_carrinho as $key => $variacao_carrinho) {

            if(array_key_exists("quantidade", $variacao_carrinho)) {

                $variacao_db = [];

                // Busca a variação do banco com base na do carrinho
                $variacao_db = $variacoes_db->where('variacao_id', $variacao_carrinho['variacao_id'])[$key];

                // Verifica se os valores estão iguais
                if($variacao_db['valor_venda'] * $variacao_carrinho['quantidade'] != $variacao_carrinho['quantidade'] * $variacao_carrinho['valor_venda']) {
                    $valor_valido = false;
                }
            }
        }

        return $valor_valido;
    }

    /**
     * Remove os serviços indisponiveis do carrinho
     * @param array $servicos_indisponiveis
     * @return void
     */
    private static function removerServicosIndisponiveis(Array $servicos_indisponiveis) {

        $carrinho = carrinho()->all();

        if($servicos_indisponiveis['problemas'] == 0) {
            return;
        }

        // Roda todos os servicos indisponiveis
        foreach($servicos_indisponiveis['servicos'] as $servico_indisponivel) {
            carrinho()->remove($servico_indisponivel['servico']->uuid);
        }
    }
}
