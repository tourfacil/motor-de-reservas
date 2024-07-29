<?php namespace App\Http\Controllers\Servicos;

use App\Http\Requests\StoreFotoServicoRequest;
use App\Http\Requests\UpdateFotoServicoRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\FotoServicoEnum;
use TourFacil\Core\Models\FotoServico;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Services\UploadPhotoService;

/**
 * Class FotoServicoController
 * @package App\Http\Controllers\Servicos
 */
class FotoServicoController extends Controller
{
    /**
     * Detalhes da foto
     *
     * @param $foto_id
     * @return mixed
     */
    public function view($foto_id)
    {
        return FotoServico::find($foto_id);
    }

    /**
     * Atualiza os dados da foto ou exclui ela
     *
     * @param UpdateFotoServicoRequest $request
     * @return array
     */
    public function update(UpdateFotoServicoRequest $request)
    {
        // Se é para deletar a foto
        $delete = $request->get('delete_foto');

        // Foto do servico
        $foto = FotoServico::find($request->get('foto_id'));

        // Tipo da foto
        $principal = $request->get('tipo', $foto->tipo);

        // Caso seja para deletar
        if($delete == "on") {

            // Deleta a foto
            $foto->delete();

            return $this->autoResponseJson(true, 'Foto deletada', 'A foto foi excluída com sucesso!');
        }

        // Caso seja para colocar a foto como principal
        if($principal == FotoServicoEnum::PRINCIPAL) {
            // Retira todas as fotos como principal
            FotoServico::where('servico_id', $foto->servico_id)
                ->where('id', '<>', $foto->id)->update(['tipo' => FotoServicoEnum::NORMAL]);
        }

        // Atualiza os dados da foto
        $foto->update(['legenda' => $request->get('legenda'), 'tipo' => $principal]);

        return $this->autoResponseJson(true, 'Foto atualizada', 'As informações sobre a foto foram salvas com sucesso!');
    }

    /**
     * Post para cadastro de fotos para o serviço
     *
     * @param StoreFotoServicoRequest $request
     * @return array
     */
    public function store(StoreFotoServicoRequest $request)
    {
        // Recupera o servico com o canal de venda
        $servico = Servico::with('canalVenda')->find($request->get('servico_id'));

        // Faz o upload das fotos
        $fotos_upload = UploadPhotoService::uploadFotosServico($request->file('fotos'), $servico, $servico->canalVenda);

        // verifica o retorno
        if($fotos_upload['upload']) {

            // Cadastra as fotos no banco de dados
            foreach ($fotos_upload['fotos'] as $foto_upload) {
                FotoServico::create([
                    'servico_id' => $servico->id,
                    'foto' => $foto_upload,
                    'legenda' => $servico->nome,
                ]);
            }

            return $this->autoResponseJson(true, 'Fotos cadastradas', 'As fotos do serviço foram cadastradas com sucesso!');
        }

        return $this->autoResponseJson(false, 'Fotos não cadastradas', 'Não foi possível realizar o upload das fotos, tente novamente!');
    }
}
