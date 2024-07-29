<?php namespace App\Http\Controllers\Destinos;

use App\Http\Requests\Servico\StoreServicoHomeRequest;
use App\Http\Requests\Servico\UpdateServicoHomeRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\HomeDestino;

/**
 * Class ServicosHomeController
 * @package App\Http\Controllers\Destinos
 */
class ServicosHomeController extends Controller
{
    /**
     * Detalhes da home destino selecionada
     *
     * @param $home_destino_id
     * @return mixed
     */
    public function view($home_destino_id)
    {
        return HomeDestino::with([
            'servicos' => function($q) {
                return $q->select(['servicos.id', 'fornecedor_id', 'nome'])->with([
                    'fornecedor' => function($f) {
                        return $f->select(['fornecedores.id', 'nome_fantasia']);
                    }
                ]);
            }
        ])->find($home_destino_id);
    }

    /**
     * POST de cadastro de uma secao na home destino
     *
     * @param StoreServicoHomeRequest $request
     * @return array
     */
    public function store(StoreServicoHomeRequest $request)
    {
        // Lista dos servicos para a secao
        $servicos = [];

        // Ajusta a ordenacao dos servicos
        foreach ($request->get('servicos') as $servico) {
            $servicos[$servico['id']] = ['ordem' => $servico['ordem']];
        }

        // Cria a nova secao no destino
        $home_destino = HomeDestino::create($request->except('servicos'));

        // Verifica se encontrou o destino
        if(is_object($home_destino)) {

            // Salva os servicos da secao
            $home_destino->servicos()->sync($servicos);

            return $this->autoResponseJson(true, "Seção cadastrada", "A seção foi cadastrada com sucesso!");
        }

        return $this->autoResponseJson(false, "Seção não cadastrada", "Não foi possível cadastrar a seção, tente novamente!");
    }

    /**
     * POST para atualizar a secao e servicos na home destino
     *
     * @param UpdateServicoHomeRequest $request
     * @return array
     */
    public function update(UpdateServicoHomeRequest $request)
    {
        // Lista dos servicos para a secao
        $servicos = [];

        // Ajusta a ordenacao dos servicos
        foreach ($request->get('servicos') as $servico) {
            $servicos[$servico['id']] = ['ordem' => $servico['ordem']];
        }

        // Recupera a home destino pelo id
        $home_destino = HomeDestino::find($request->get('home_destino_id'));

        // Verifica se encontrou o destino
        if(is_object($home_destino)) {

            // Atualiza os dados da secao
            $update = $home_destino->update($request->except(['servicos', '_method', 'home_destino_id']));

            if($update) {

                // Atualiza os serviços
                $home_destino->servicos()->sync($servicos);

                return $this->autoResponseJson(true, "Seção atualizada", "A seção foi atualizada com sucesso!");
            }
        }

        return $this->autoResponseJson(false, "Seção não atualizada", "Não foi possível atualizar a seção, tente novamente!");
    }

    /**
     * DELETE para destivar a seção
     *
     * @param Request $request
     * @return array
     */
    public function remove(Request $request)
    {
        // Recupera a home destino pelo id
        $home_destino = HomeDestino::find($request->get('home_destino_id'));

        // Desativa a secao
        $delete = $home_destino->delete();

        if($delete) {
            return $this->autoResponseJson(true, "Seção desativada", "A seção foi desativada com sucesso!");
        }

        return $this->autoResponseJson(false, "Falha ao desativar", "Não foi possível desativar a seção, tente novamente!");
    }

    /**
     * Reativa a seção com os serviço na home
     *
     * @param Request $request
     * @return array
     */
    public function restore(Request $request)
    {
        // Recupera a home destino pelo id
        $home_destino = HomeDestino::withTrashed()->find($request->get('home_destino_id'));

        // Reativa a secao
        $restore = $home_destino->restore();

        if($restore) {
            return $this->autoResponseJson(true, "Seção reativada", "A seção foi reativada com sucesso!");
        }

        return $this->autoResponseJson(false, "Falha ao reativar a seção", "Não foi possível reativar a seção, tente novamente!");
    }
}
