# Tourfacil.com.br

Marketplace para venda de ingressos - [tourfacil.com.br](http://www.tourfacil.com.br)

## Requisitos
- PHP ^7.2
- Laravel 5.8.^
- MariaDB 10.1.35
- Composer
- Npm e Yarn

## Utilizado o Xampp (*desenvolvimento*)
- Configure o `.env` conforme o `.env.example`
- Configure um Virtual Host para a aplicação `tourfacil.test`
- Crie um pasta utilizando o comando `mkdir core_tourfacil`
- Entre na pasta e clone o projeto `git clone https://github.com/tourfacil/core.git`
- Instale os pacotes do composer `composer install --ignore-platform-reqs`
- Instale as dependencias do npm utilizado o yarn `yarn install`
- Instale e configure o wkhtmltopdf [download aqui](https://wkhtmltopdf.org/downloads.html)

## Utilizando o Docker (*desenvolvimento*)
- Configure o `.env` conforme o `.env.example`
- Crie um pasta utilizando o comando `mkdir core_tourfacil`
- Entre na pasta e clone o projeto `git clone git@github.com:tourfacil/core.git`
- docker-compose -f docker-compose-dev.yml up

## Utilizado o Docker (*produção*)
- Configure o `.env` conforme o `.env.example`
- Crie um pasta utilizando o comando `mkdir core_tourfacil`
- Entre na pasta e clone o projeto `git clone git@github.com:tourfacil/core.git`
- De permissão para o arquivo `chmod -R 777 entrypoint.sh`
- Rode o arquivo `bash install.sh`


## Laravel Horizon Windows
- Use o comando `composer require laravel/horizon --ignore-platform-reqs`
- Quando for a primeira vez que estiver instalando a vendor use `composer install --ignore-platform-reqs`

## Configurando o SSL
- Configure o endereço do site nos arquivos `/docker/web/nginx.conf` e no `init-letsencrypt.sh`
- De permissão ao arquivo `chmod +x init-letsencrypt.sh`
- Gere o SSL automaticamente `sudo ./init-letsencrypt.sh`

## Changelog

### *v1.5.5 (25 de Junho de 2022)*
- Gateway de pagamento agora é a Pagarme.

### *v1.5.4 (17 de Junho de 2022)*
- Clientes da integração do mini mundo recebem e-mail.

### *v1.5.3 (17 de Junho de 2022)*
- Adicionado integração com o Mini Mundo.

### *v1.5.2 (01 de Junho de 2022)*
- Adicionado possibilidade de slider de campanha.

### *v1.5.1 (31 de Maio de 2022)*
- Adicionado serviço invisivel. Fica disponivel para venda por link, mas não aparece nas listagens e na pesquisa do site.

### *v1.5.0 (31 de Maio de 2022)*
- Adicionado Integração com o Olivas.

### *v1.4.12 (25 de Maio de 2022)*
- Adicionado LOG no envio de e-mail para maior controle.

### *v1.4.11 (09 de Maio de 2022)*
- API Admin Ecommerce: Corrigido problema de e-mail para cliente em caso de venda interna.

### *v1.4.10 (09 de Maio de 2022)*
- Adicionado API para que o ADMIN consiga mandar e-mails pelo Ecommerce.

### *v1.4.9 (03 de Maio de 2022)*
- Agora serviços que não tem localização setada não exibirão o mapa.

### *v1.4.8 (27 de Abril de 2022)*
- Reserva recebe flag que diz se esta ou não finalizada. Serve para controlar isso no admin de forma mais eficiente.

### *v1.4.7 (23 de Abril de 2022)*
- Corrigido texto da modal do click copy do código PIX no pagamento.

### *v1.4.6 (23 de Abril de 2022)*
- Adicionado possibilidade de ativar e desativar sistema de PIX por configuração. Por enquanto somente por .ENV

### *v1.4.5 (22 de Abril de 2022)*
- Corrigido BUG que permitia submeter req para verificar PIX sem ter os dados do cliente preenchidos

### *v1.4.4 (21 de Abril de 2022)*
- Corrigido caminho de certificado de produção para API do PIX

### *v1.4.3 (19 de Abril de 2022)*
- Corrigido problema de click e copy do PIX

### *v1.4.2 (16 de Abril de 2022)*
- PIX reativado
- Corrigido BUG que permitia PIX aprovar pedido caso a req fosse alterada
- Código PIX registrado na sessão para cliente não perder PIX salvo
- Caso o carrinho ou cupom for alterado PIX é derrubado da sessão
- Adicionado Descontos e Cupons de desconto ao PIX
- Adicionado vendas de afiliados ao sistema de PIX

### *v1.4.1 (06 de Abril de 2022)*
- Links para redes sociais agora estão em arquivo de configuração e replicam para o site todo
- Links de redes sociais atualizados

### *v1.4.0 (06 de Abril de 2022)*
- Adicionado novo sistema de cupons de desconto

### *v1.3.7 (30 de Março de 2022)*
- Alterado e-mail da página de cancelamento para atendimento@expandaviagens.com.br.

### *v1.3.6 (30 de Março de 2022)*
- Corrigido BUG que fazia o analitycs não registrar a venda.

### *v1.3.5 (29 de Março de 2022)*
- Removido texto de "Mantenha a Flexibilidade" da index do site.

### *v1.3.4 (29 de Março de 2022)*
- Corrigido BUG que fazia o logo não aparecer no voucher.

### *v1.3.3 (25 de Março de 2022)*
- Tela de confirmação de pagamento agora avisa ao cliente que é necessário finalizar a reserva em casos de venda interna.

### *v1.3.2 (24 de Março de 2022)*
- Agora o e-mail é enviado aos fornecedores somente apos a finalização de todas as reservas do pedido.

### *v1.3.1 (23 de Março de 2022)*
- Sistema de PIX desativado temporariamente
- Criado docker-compose para desenvolvimento
- E-mails com nova arte do TourFacil

### *v1.3.0 (21 de Março de 2022)*
- Adicionado sistema de geração de links para as vendedoras montarem o carrinho dos clientes
- Adicionado sistema de vendas internos, para que o operacional possa adicionar reservas

### *v1.2.0 - (13 Janeiro 2022)*
- Adicionado forma de pagamento por pix

### *v1.1.5 - (13 Janeiro 2022)*
- Alteração de texto homepage.
- Ajuste de ação em modal de compra.
- Ajuste de eventos para exibição do botão de whatsapp.

### *v1.1.4 - (13 Janeiro 2022)*
- Edição de imagens, alteração de marca.

### *v1.1.3 - (13 Janeiro 2022)*
- Redirecionamento de URLs

### *v1.1.2 - (04 Dezembro 2021)*
- Remoção de botão de whatsapp quando estando em uma modal. 
- Ajustado footer cortando o layout

### *v1.1.1 - (04 Dezembro 2021)*
- Ajuste de sobreposição do botçao de whatsapp no botão de pagamento. 
- Colocado botão de whatsapp, removido de outras e retirado do template master 
- Alterar nome do botão na pagina de serviço de Ver datas disponiveis para COMPRAR AGORA
- Ajustar para aparecer o botão comprar agora abaixo do valor na pagina do serviço 

### *v1.1.0 - (04 Dezembro 2021)*
- Alterações de endereço
- Alterações de contato
- Alteração de nomenclatura Sacola para Carrinho
- Alteração de link seja um fornecedor
- Alteração de link seja um afiliado
- Alteração de informações de pagamento
- Comentado section dica de viajantes na home
- Adicionado botão de whatsapp flutuante 

### *v1.0.3 - (08 Novembro 2021)*
- Fix comando de sitemap, rotas de ajuda
- Alterado gateway de pagamento para Cielo 

### *v1.0.2 - (24 Novembro 2020)*
- Nova logo
- Ajustes nos textos
- Link para WhatsApp
- Link para formulário de Seja fornecedor e Afiliado
- Textos de cancelamento, privacidade e termos
- Textos dos FAQ (serviço e carrinho)

### *v1.0.1 - (13 Novembro 2020)*
- Página de meus dados cliente
- Tags serviço

### *v1.0.0 - (10 Setembro 2020)*
- Lançamento do site
- Criação e configuração do projeto
