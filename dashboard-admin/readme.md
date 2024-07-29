# Administrativo - TourFácil

Aplicação que gerencia os canais de vendas utilizados pela TourFácil - [adm.tourfacil.com.br](https://adm.tourfacil.com.br)

## Requisitos
- PHP 7.2
- Laravel 5.8.
- MariaDB 10.1.35
- Composer
- Npm e Yarn

## Utilizado o Docker (*desenvolvimento*)
- Configure o `.env` conforme o `.env.example`
- Crie um pasta utilizando o comando `mkdir core_tourfacil`
- Entre na pasta e clone o projeto `git clone git@github.com:tourfacil/core.git`
- Volte para a pasta raiz do projeto `cd ..`
- Rode o docker-compose `docker-compose -f docker-compose-dev.yml up -d`
- Entre no container `docker exec -it admin-app bash`
- Instale os pacotes do composer `composer install --ignore-platform-reqs`
- Adicione o Dump a pasta mysql do projeto
- Copie o Dump para o container `docker cp <path do arquivo> tourfacil_mysql:/`
- Entre no container do mysql `docker exec -it tourfacil_mysql bash`
- Importe o dump para o banco `mysql -u tourfacil -p tourfacil < tourfacil.sql`
  
## Utilizado o Docker (*produção*)
- Siga os passos do arquvio Comandos úteis na sessão Configurando o servidor

## Utilizado o Xampp (*desenvolvimento*)
- Configure o `.env` conforme o `.env.example`
- Configure um Virtual Host para a aplicação `tourfacil-admin.test`
- Crie um pasta utilizando o comando `mkdir core_tourfacil`
- Entre na pasta e clone o projeto `git clone https://github.com/tourfacil/core.git`
- Instale os pacotes do composer `composer install --ignore-platform-reqs`
- Instale as dependencias do npm utilizado o yarn `yarn install`
- Instale e configure o wkhtmltopdf [download aqui](https://wkhtmltopdf.org/downloads.html)

## Laravel Horizon Windows
- Use o comando `composer require laravel/horizon --ignore-platform-reqs`
- Quando for a primeira vez que estiver instalando a vendor use `composer install --ignore-platform-reqs`

## Changelog

Lista de mudanças, melhorias e correções de bugs.

### *Snapshot (27 de Março de 2024)*
- Corrigindo criação de acesso para afiliados
- Editando logica de atualização de colaborador

### *Snapshot (20 de Março de 2024)*
- Criei migrate para adicionar coluna de min_pax na tabela Variacao_servicos
- Adicionando logica de min_pax na criação da variação e atualização de variação
- Retorno quantidade min de pax na dashboard de variações

### *Snapshot (12 de Março de 2024)*
- Adicionando pasta de configuração do container Mysql
- Alterando .env.example para novas configurações dos containers
- Adicionando filtro no relatório de disponibilidade(Correção de Time-Exception)
- Criando paínel para o relatório de disponibilidade
- Inclusão de bloqueio no relatório com detalhes
- Corrigido erro no upload de fotos

### *v1.7.10 (08 de Novembro de 2022)*
- Adicionado integração com o mátria.
- Adicionado novo ranking de vendas por destino no dashboard.
- Adicionado integração com Parque Vila da Monica Gramado.
- Adicionado integração com PWI (Neste momento SKYGLASS).
- Adicionado Regra que permite aumentar valor para o mesmo dia.

### *v1.7.9 (11 de Outubro de 2022)*
- Adicionado sistema de cupons de desconto nas vendas internas

### *v1.7.8 (07 de Outubro de 2022)*
- E-mail da notificação de backup alterado para `dev@tourfacil.com.br`
- Corrigido BUG que fazia valor net aparecer zerado no relatório de vendidos em PDF.
- Adicionado possibilidade de vendedoras editarem produtos para abrir disponibilidade.

### *v1.7.7 (30 de Setembro de 2022)*
- Adicionado sistema de controle de entradas financeiro nas vendas internas.
- BUG: Corrigido campos de SEO que estavam obrigatórios na criação de novos serviços.

### *1.7.6 (15 de Setembro de 2022)*
- Adicionado possibilidade de alterar nome do serviço na página de informações do mesmo.
- Adicionado novo sistema de auto completar nas vendas internas.

### *v1.7.5 (31 de Agosto de 2022)*
- Adicionado novo sistema de avaliações dos serviços.

### *v1.7.4 (25 de Agosto de 2022)*
- Adicionado recurso de título de página em destinos.

### *v1.7.3 (24 de Agosto de 2022)*
- Adicionado recurso de título de página em serviços.
- Adicionado recurso de título de página em categorias.

### *v1.7.2 (23 de Agosto de 2022)*
- Resolvido BUG de data inválida no venda interna. Reportado por Matheus.
- Novo sistema de descontos por PIX.
- Adicionado campo de quanto foi descontado por PIX do pedido no admin.

### *v1.7.1 (19 de Agosto de 2022)*
- Resolvido BUG que não permitia colar a data de nascimento do titular nas vendas internas.

### *v1.7.0 (16 de Agosto de 2022)*
- Sistema de afiliados com comissão fixa salva no banco de dados.
- Relatório de afiliado adaptado ao novo sistema.
- Novo relatórios de afiliados.

### *v1.6.15 (13 de Agosto de 2022)*
- Adicionado integração com o Snowland.

### *v1.6.14 (12 de Agosto de 2022)*
- Adicionado listagem de afiliados.
- Adicionado criação de afiliados.
- Adicionado edição de afiliados.
- Novo menu de afiliados na sidebar.
- Relatório de afiliados movido para novo menu de afiliados.

### *v1.6.13 (11 de Agosto de 2022)*
- Adicionado integração com Fanstatic House.

### *v1.6.12 (10 de Agosto de 2022)*
- Adicionado botão para forçar integração pendente.

### *v1.6.11 (08 de Agosto de 2022)*
- Adicionado integração com o Alpen Park.

### *v1.6.10 (08 de Agosto de 2022)*
- Adicionado integração com o Exceed.

### *v1.6.9 (05 de Agosto de 2022)*
- Agora as vendedoras podem ver a listagem de clientes do site.

### *v1.6.8 (05 de Agosto de 2022)*
- Adicionado novo Dashboard em tempo real.

### *v1.6.7 (04 de Agosto de 2022)*
- Adicionado botão para resetar a senha dos clientes.

### *v1.6.6 (03 de Agosto de 2022)*
- Adicionado integração com o Dreams.

### *v1.6.5 (30 de Julho de 2022)*
- Vendas Internas Pago: Corrigido BUG que permitia inserir data inválida no cliente.
- Finalização de Reservas: Corrigido BUG que permitia inserir data inválida no cliente.
- Finalização de Reservas: Corrigido BUG que causava problemas caso a reserva fosse finalizada e depois disso o serviço 
fosse alterado.
- Pedidos do Site: Corrigido BUG que fazia que Admins vinculados a um afiliado não pudessem ver todos os pedidos.

### *v1.6.4 (29 de Julho de 2022)*
- Adicionado sistema de finalização de reservas.

### *v1.6.3 (27 de Julho de 2022)*
- Adicionado novo pacote de icones do font awesome.
- Novo sistema de tags de serviço com descrição e icone.

### *v1.6.2 (21 de Julho de 2022)*
- Adicionado novo dashboard para as vendedoras.

### *v1.6.1 (16 de Julho de 2022)*
- Homelist agora não mostra mais reservas negadas, pendentes e expiradas.

### *v1.6.0 (15 de Julho de 2022)*
- Adicionado novo sistema de PIX pela Pagarme.
- Agora todas as reservas são registradas no sistema. Até as que falham.

### *v1.5.6 (25 de Junho de 2022)*
- Gateway de pagamento alterado para Pagarme.

### *v1.5.5 (22 de Junho de 2022)*
- Afiliados: Corrigido BUG no relatório. Acontecia devido a não estar preparado para imprimir informações da categoria Natal Luz.

### *v1.5.4 (18 de Junho de 2022)*
- Adicionado integração com MiniMundo.

### *v1.5.3 (10 de Junho de 2022)*
- Agora é possivel usar virgulas no relatório de afiliados.

### *v1.5.2 (10 de Junho de 2022)*
- Adicionado possibilidade de atribuir afiliados as reservas pelo portal do Admin.

### *v1.5.1 (31 de Maio de 2022)*
- Adicionado serviço invisivel. Fica disponivel para venda por link, mas não aparece nas listagens e na pesquisa do site.

### *v1.5.0 (31 de Maio de 2022)*
- Adicionado integração com o Olivas.

### *v1.4.13 (26 de Maio de 2022)*
- Vendas Internas: Adicionado sistema de logs e de envio de e-mails de alerta caso a integração da venda interna falhe.

### *v1.4.12 (25 de Maio de 2022)*
- Vendas Internas: Corrigido BUG no copia e cola na máscara do telefone. 

### *v1.4.11 (09 de Maio de 2022)*
- Adicionado API que permite que o Admin envie e-mails pelo Ecommerce.

### *v1.4.10 (04 de Maio de 2022)*
- Vendas Internas: Terminadas algumas documentações do sistema.
- Vendas Internas: Cadastro de clientes só será solicitado caso esteja habilitado no serviço.
- Vendas Internas: Validação de dados do titular.
- Vendas Internas: Validação de dados de todos os clientes.
- Vendas Internas: Validação de todos os campos adicionais.
- Vendas Internas: Agora sistema puxa os dados de titulares ja cadastrados no sistema automaticamente. 
- Vendas Internas: Removido auto-complete do navegador dos campos de clientes e titular.
- Vendas Internas: Campo de endereço do titular deixa de ser obrigatório.
- Vendas Internas: Agora campos do titular vem desativados até que se preencha o e-mail. Objetivo: Garantir que não se preencha dados de clientes ja cadastrados.


### *v1.4.9 (27 de Abril de 2022)*
- Adicionado forma de monitorar as reservas que estão pendentes para finalização.

### *v1.4.8 (25 de Abril de 2022)*
- Adicionado informação de qual afiliado esta vinculado aquela reserva na tela de reserva. 

### *v1.4.7 (25 de Abril de 2022)*
- Adicionado Helper que retorna os números de celular somente com números
- Redirect para whatsapp no Homelist agora usando o Helper acima mencionado
- Adicionado link de redirect para Whatsapp na tela do pedido

### *v1.4.6 (25 de Abril de 2022)*
- Adicionado link para whatsapp de cliente no homelist
- Adicionado link para reserva no homelist

### *v1.4.5 (16 de Abril de 2022)*
- Adicionado PIX nos detalhes de pedido

### *v1.4.4 (12 de Abril de 2022)*
- Adicionado pequeno sistema de conferencia de reservas

### *v1.4.3 (12 de Abril de 2022)*
- favicon e logos do admin estavam como os antigos. Estavam trocados direto em produção. Feito versionamento e lançada versão 1.4.3 para integra-los de forma correta.

### *v1.4.2 (11 de Abril de 2022)*
- Ativado sistema de vendas internas para pessoal do operacional: Sistema estava desativado desde sua implementação na versão 1.3.0 devido a pequenos bugs que não tinham prioridade de serem resolvidos naquele momento

### *v1.4.1 (07 de Abril de 2022)*
- Adicionado relatório de Home List temporário

### *v1.4.0 (06 de Abril de 2022)*
- Adicionado novo sistema de cupons de desconto
- Adicionado painel de cadastro de cupons
- Adicionado painel de edição de cupons

### *v1.3.5 (29 de Março de 2022)*
- Corrigido BUG que fazia a página do serviço não abrir quando não tinha nenhuma categoria cadastrada no produto

### *v1.3.4 (25 de Março de 2022)*
- Botões das vendas internas com novas cores e alinhados
- Coluna de vendas internas corrigida para responsividade
- Botão de edição de de itens no carrinho de vendas internas desativado
- Corrigido botão de comprar que ficava caindo para nova linha
- Removido temporariamente a opção de copiar link automatica
- Campo de link destacado

### *v1.3.3 (23 de Março de 2022)*
- Relatório de afiliados PDF: Texto de data alterado

### *v1.3.2 (23 de Março de 2022)*
- Relatório de afiliados: Adicionado possibilidade de gerar XLS e PDF

### *v1.3.1 (23 de Março de 2022)*
- Relatórios de fornecedores e ingressos vendidos: Período de vendas agora mostra se o relatório é de venda ou utilização.
- Desativado relatório de ingressos autenticados.
- Corrigido BUG que fazia relatório de fornecedor buscar informação por utilização ao invés de venda.

### *v1.3.0 (21 de Março de 2022)*
- Adicionado sistema de geração de links para as vendedoras montarem o carrinho dos clientes
- Adicionado sistema de vendas internos, para que o operacional possa adicionar reservas

### *v1.2.0 (08 de Março de 2022)*
- Adicionado nova ferramenta a geração de relatórios: Agora é possivel tirar relatórios por data de venda e de utilização.

### *v1.1.1 (25 de Novembro 2021)*
- Ajuste nos templates referentes ao relatório autenticados. 

### *v1.1.0 (25 de Novembro 2021)*
- Ajuste nas request onde verifica o certificado ssl para por hora não verificar 

### *v1.0.9 (24 de Novembro 2020)*
- Ajuste no editor markdown

### *v1.0.7 e v1.0.8 (03 de Novembro 2020)*
- v1.0.8 Fix domain nginx, init-letsencrypt.sh config
- Fix options snappy pdf
- Alterado logos para TourFácil (antes estava Brocker)

### *v1.0.6 (14 de Setembro 2020)*
- Administração de tags serviço

### *v1.0.5 (10 de Setembro 2020)*
- Ajustes no serviços da home (método sync)
- Habilitado a tarefa de limpar o cache do servidor 

### *v1.0.4 (21 de Agosto 2020)*
- Ajustes no serviços da home

### *v1.0.3 (30 de Julho 2020)*
- Adicionado novos botões no editor markdown (limitador de texto e tag h4)

### *v1.0.2 (24 de Julho 2020)*
- Fix migration (padrao categoria)
- Texto alterado (tarifa net de venda para tarifa net de custo na agenda)
- Novo dump default do banco de dados (tourfacil.sql)

### *v1.0.1 (01 de Julho 2020)*
- Ajustes no Docker (arquvios do nginx)
- Nova versão do certbot
- Ajustes no install.sh e no init-letsencrypt.sh
- Adicionado o swap-memory.sh para swap de memoria no servidor

### *v1.0.0 (29 de Junho 2020)*
- Adicionado tipo Destaques nos serviços da home destino
- Adicionado Newsletter (Migration, listagem e download)
- Criação e configuração do projeto
