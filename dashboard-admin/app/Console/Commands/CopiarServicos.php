<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use TourFacil\Core\Enum\CanaisVendaEnum;
use TourFacil\Core\Enum\FotoServicoEnum;
use TourFacil\Core\Models\AgendaServico;
use TourFacil\Core\Models\CampoAdicionalServico;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Models\Categoria;
use TourFacil\Core\Models\Destino;
use TourFacil\Core\Models\FotoServico;
use TourFacil\Core\Models\SecaoCategoria;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Models\VariacaoServico;
use Storage;

class CopiarServicos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicos:copiar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copia os servicos de um canal de venda para outro';

    /**
     * Canal de onde vem os dados
     *
     * @var int
     */
    protected $canal_dados = CanaisVendaEnum::TERMINAIS_CDI;

    /**
     * Canal para onde vai os dados
     *
     * @var int
     */
    protected $canal_novo = CanaisVendaEnum::TOURFACIL;

    /**
     * Dados do novo canal de venda
     *
     * @var CanalVenda
     */
    private $canal_venda;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Novo canal de venda
        $this->canal_venda = CanalVenda::find($this->canal_novo);

        // Copia os destinos
        $this->copiarBase();
    }

    /**
     * Copia os destinos do canal
     */
    private function copiarBase()
    {
        $destinos = Destino::with([
            'categorias.secoesCategoria'
        ])->where('canal_venda_id', $this->canal_dados)->oldest()->get();

        $enum_large = FotoServicoEnum::LARGE;

        $enum_medium = FotoServicoEnum::MEDIUM;

        $enum_small = FotoServicoEnum::SMALL;

        $novos = ['destinos' => [], 'categorias' => [], 'secoes' => []];

        foreach ($destinos as $destino) {

            // Random name image
            $name = $destino->slug . "-" . uniqid() . "-" . str_slug($enum_large) . ".jpg";

            // Path das fotos slug do canal de venda
            $path = str_slug($this->canal_venda->nome) . "/destinos/" . $name;

            // Copia a foto do destino
            $copy = Storage::cloud()->copy($destino->foto[$enum_large], $path);
            Storage::cloud()->setVisibility($path, "public");

            if($copy) {

                // Copia o destino para o canal de venda
                $novo_destino = Destino::create([
                    'nome' => $destino->nome,
                    'canal_venda_id' => $this->canal_novo,
                    'descricao_curta' => $destino->descricao_curta,
                    'descricao_completa' => $destino->descricao_completa,
                    'foto' => [$enum_large => $path]
                ]);

                // Salva o ID do novo destino
                $novos['destinos'][$destino->id] = $novo_destino->id;

                $this->info("Região: {$destino->nome} copiada com sucesso!");

                foreach ($destino->categorias as $categoria) {

                    // Path das fotos slug do canal de venda
                    $path = str_slug($this->canal_venda->nome) . "/categorias/";

                    // banner da categoria
                    $banner = $path . $categoria->slug . "-" . $novo_destino->slug . "-" . uniqid() . "-" . str_slug($enum_large) . ".jpg";

                    // Foto da categoria
                    $foto = $path . $categoria->slug . "-" . $novo_destino->slug . "-" . uniqid() . "-" . str_slug($enum_medium) . ".jpg";

                    // Copia o banner
                    $copy = Storage::cloud()->copy($categoria->foto[$enum_large], $banner);
                    Storage::cloud()->setVisibility($banner, "public");

                    // Copia a foto da categoria
                    $copy = Storage::cloud()->copy($categoria->foto[$enum_medium], $foto);
                    Storage::cloud()->setVisibility($foto, "public");

                    if($copy) {

                        // Copia a categoria para o novo destino
                        $nova_categoria = Categoria::create([
                            'destino_id' => $novo_destino->id,
                            'nome' => $categoria->nome,
                            'descricao' => $categoria->descricao,
                            'posicao_menu' => $categoria->posicao_menu,
                            'foto' => [
                                $enum_large => $banner,
                                $enum_medium => $foto
                            ]
                        ]);

                        // Salva o ID da nova categoria
                        $novos['categorias'][$categoria->id] = $nova_categoria->id;

                        $this->info("Categoria: {$categoria->nome} copiada com sucesso!");

                        // Copia as secoes da categoria
                        foreach ($categoria->secoesCategoria as $secao_categoria) {
                            // Copia a secao categoria
                            $nova_secao = SecaoCategoria::create([
                                'nome' => $secao_categoria->nome,
                                'categoria_id' => $nova_categoria->id,
                            ]);

                            // Salva o ID da nova secao categoria
                            $novos['secoes'][$secao_categoria->id] = $nova_secao->id;
                        }

                    } else {
                        $this->error("Copia de {$categoria->nome} falhou!");
                    }
                }
            } else {
                $this->error("Copia de {$destino->nome} falhou!");
            }
        }

        $this->copiarServicos($novos);
    }

    /**
     * Copia os servicos
     *
     * @param array $novos
     */
    private function copiarServicos(array $novos)
    {
        $servicos = Servico::with([
            'agendaServico',
            'categorias',
            'secoesCategoria',
            'camposAdicionais',
            'fotos',
            'variacaoServico',
        ])->where('canal_venda_id', $this->canal_dados)
            ->oldest()->get();

        $enum_large = FotoServicoEnum::LARGE;

        $enum_medium = FotoServicoEnum::MEDIUM;

        $enum_small = FotoServicoEnum::SMALL;

        $novos_destinos = $novos['destinos'];

        $novas_categorias = $novos['categorias'];

        $novas_secoes = $novos['secoes'];

        $bar = $this->output->createProgressBar($servicos->count());

        foreach ($servicos as $servico) {

            $novas_categorias_servico = [];
            $novas_secao_categoria_servico = [];

            $novo_servico = Servico::create([
                'destino_id' => $novos_destinos[$servico->destino_id],
                'fornecedor_id' => $servico->fornecedor_id,
                'canal_venda_id' => $this->canal_novo,
                'nome' => $servico->nome,
                'valor_venda' => $servico->valor_venda,
                'comissao_afiliado' => $servico->comissao_afiliado,
                'antecedencia_venda' => $servico->antecedencia_venda,
                'tipo_corretagem' => $servico->tipo_corretagem,
                'corretagem' => $servico->corretagem,
                'horario' => $servico->horario,
                'descricao_curta' => $servico->descricao_curta,
                'descricao_completa' => $servico->descricao_completa,
                'regras' => $servico->regras,
                'observacao_voucher' => $servico->observacao_voucher,
                'palavras_chaves' => $servico->palavras_chaves,
                'info_clientes' => $servico->info_clientes,
                'integracao' => $servico->integracao,
                'localizacao' => $servico->localizacao,
                'cidade' => $servico->cidade,
                'status' => $servico->status,
            ]);

            // Coloca o servico na agenda compartilhada
            $agenda = AgendaServico::find($servico->agenda->id);
            $agenda->servicos()->attach($novo_servico->id);

            // Recupera as categorias
            foreach ($servico->categorias as $categoria_servico) {
                $novas_categorias_servico[] = $novas_categorias[$categoria_servico->id];
            }

            // Salva a nova categoria com seviço
            $novo_servico->categorias()->attach($novas_categorias_servico);

            // Recupera as secoes categoria
            foreach ($servico->secoesCategoria as $secao_categoria) {
                if(isset($novas_secoes[$secao_categoria->id])) {
                    $novas_secao_categoria_servico[] = $novas_secoes[$secao_categoria->id];
                }
            }

            // Salva as secoes com o servico
            $novo_servico->secoesCategoria()->attach($novas_secao_categoria_servico);

            // Campos adicionais do servico
            foreach ($servico->camposAdicionais as $campo_adicional) {
                CampoAdicionalServico::create([
                    'servico_id' => $novo_servico->id,
                    'campo' => $campo_adicional->campo,
                    'placeholder' => $campo_adicional->placeholder,
                    'obrigatorio' => $campo_adicional->obrigatorio,
                ]);
            }

            // Novas variacoes do servico
            foreach ($servico->variacaoServico as $variacao_servico) {
                VariacaoServico::create([
                    'servico_id' => $novo_servico->id,
                    'nome' => $variacao_servico->nome,
                    'descricao' => $variacao_servico->descricao,
                    'percentual' => $variacao_servico->percentual,
                    'markup' => $variacao_servico->markup,
                    'consome_bloqueio' => $variacao_servico->consome_bloqueio,
                ]);
            }

            // Copia as fotos do servico
            foreach ($servico->fotos as $foto_servico) {

                $fotos = $foto_servico->foto;

                // path das fotos
                $path = str_slug($this->canal_venda->nome) . "/servicos/";

                // Random name image
                $new_small = $path . $servico->slug . "-" . uniqid() . "-" . str_slug($enum_small) . ".jpg";
                $new_medium = $path . $servico->slug . "-" . uniqid() . "-" . str_slug($enum_medium) . ".jpg";
                $new_large = $path . $servico->slug . "-" . uniqid() . "-" . str_slug($enum_large) . ".jpg";

                // Copia a foto do servico SMALL
                Storage::cloud()->copy($fotos[$enum_small], $new_small);
                Storage::cloud()->setVisibility($new_small, "public");

                // Copia a foto do servico MEDIUM
                Storage::cloud()->copy($fotos[$enum_medium], $new_medium);
                Storage::cloud()->setVisibility($new_medium, "public");

                // Copia a foto do servico LARGE
                Storage::cloud()->copy($fotos[$enum_large], $new_large);
                Storage::cloud()->setVisibility($new_large, "public");

                FotoServico::create([
                    'servico_id' => $novo_servico->id,
                    'legenda' => $foto_servico->legenda,
                    'tipo' => $foto_servico->tipo,
                    'foto' => [
                        $enum_small => $new_small,
                        $enum_medium => $new_medium,
                        $enum_large => $new_large,
                    ],
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
    }
}
