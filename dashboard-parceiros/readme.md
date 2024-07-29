# Fornecedores TourFácil

Aplicação do painel para fornecedores do TourFácil - 

## Requisitos
- PHP ^7.2
- Laravel 5.8.^
- MariaDB 10.1.35
- Composer
- Npm e Yarn

## Utilizado o Xampp (*desenvolvimento*)
- Crie um pasta utilizando o comando `mkdir core_tourfacil`
- Entre na pasta e clone o projeto `git clone git@github.com:tourfacil/core.git`
- Instale os pacotes do composer `composer install --ignore-platform-reqs`
- Instale as dependencias do npm utilizado o yarn `yarn install`
- Configure um Virtual Host para a aplicação `fornecedor.test`
- Instale e configure o wkhtmltopdf [download aqui](https://wkhtmltopdf.org/downloads.html) 

## Utilizado o Docker (*produção*)
- Configure as senhas no `docker-compose.yml`
- Crie um pasta utilizando o comando `mkdir core_tourfacil`
- Entre na pasta e clone o projeto `git clone git@github.com:tourfacil/core.git`
- Rode o arquivo `bash install.sh`
- De permissão as pastas `chmod -R 777 bootstrap storage`
- Configure o `.env`

## Configurando o SSL
- Configure o endereço do site nos arquivos `/docker/web/nginx.conf` e no `init-letsencrypt.sh`
- De permissão ao arquivo `chmod +x init-letsencrypt.sh`
- Gere o SSL automaticamente `sudo ./init-letsencrypt.sh`

## Laravel Horizon Windows
- Use o comando `composer require laravel/horizon --ignore-platform-reqs`
- Quando for a primeira vez que estiver instalando a vendor use `composer install --ignore-platform-reqs`

## Changelog

Lista de mudanças, melhorias e correções de bugs.

### SNAPSHOT - (13 de Setembro de 2022)
- Corrigido texto de "Esqueceu sua senha" na tela de login.

### *v1.1.4 - (01 de Agosto de 2022)*
- Resolvido BUG do dashboard.

### *v1.1.3 - (16 de Julho de 2022)*
- Homelist: Corrigido BUG que permitia ver reservas de outros parceiros
- Homelist: Removido reservas canceladas

### *v1.1.2 - (16 de Julho de 2022)*
- Homelist não mostra mais reservas negadas, pendentes ou expiradas

### *v1.1.1 - (30 de Junho de 2022)*
- Relatório de ingressos agora puxa por data de utilização
- Botões de gerar PDF e XLS removidos dos relatórios até que o container de PDF seja corrigido
- Removido botão de agenda da página de agenda

### *v1.1.0 - (30 Junho 2022)*
- Nomes alterados para Tourfacil
- Filtros de relatórios alterados para data de utilização
- Valor total do resumo dos relatórios alterado para somente reservas ativas
- Adicionado homelist

### *v1.0.4 e v1.0.5 - (08 Dezembro 2019)*
- Adicionado logo do ingressos.com.br
- Colocado link para o antigo ingressos.com.br
- Update para php 7.3.11 e nginx 1.17.6
- Removido o Sentry e adicionado o Bugsnag
- Timezone para America/Bahia

### *v1.0.3 - (20 Junho 2019)*
- Ajustado informações do readme
- Ajustado informações do banco de dados

### *v1.0.2 - (17 Junho 2019)*
- Arrumado dominio nas configurações do Nginx

### *v1.0.0 e v1.0.1 - (14 Junho 2019)*
- v1.0.1 (Fixes biblioteca hashid)
- Inicialização do projeto
- Painel temporário para autenticação dos vouchers 
