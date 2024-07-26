Sobre o projeto

Este é um projeto de servidor que tem o objetivo de rodar até 3 apps em um único servidor HTTP.
Podem funcionar aqui o Ecommerce, o Admin e o App de Parceiros.

Guia de instalação.

- Clone o repositório principal `git clone git@github.com:tourfacil/luz-camera-gramado.git`
- Entre em repositório/ecommerce `cd luz-camera-gramado/ecommerce `
- Crie uma pasta chamada core_tourfacil `mkdir core_tourfacil`
- Entre na pasta core_tourfacil `cd core_tourfacil`
- Clone o core `git clone git@github.com:tourfacil/core.git`
- Volte ao /ecommerce/ `cd ../`
- Instale as dependencias PHP `composer install --ignore-platform-reqs`
- Instale as depdencias JS `yarn install`
- Repita estes passos para o projeto do admin
- Configure os dominíos em `./init-letsencrypt.sh` e em `./docker/nginx.conf`

## anotacoes bruno 

- Técnicamente pelo o que eu entendi o vini esta projetando um servidor principal rodando php, nginx e certbot(para SSL) e containers adicionais se necessário como:
    
    Mysql, redis entre outros...

- dentro deste projeto principal temos os outros internos que rodam em cima dele, como ecommerce, admin e parceiros.

- copiei o projeto principal, ele veio com o ecommerce dentro e no .git.ignore tá excluindo parceiros e admin.

- vou seguir o passo a passo...
- Vou remover do composer.json o developers cielo pois não estar mais em uso e indisponivel para download, dei uma olhada nas configs do projeto acho que esse gateway está como principal.

- vou excluir o composer.lock para não dar erro nos próximos comandos.
- tive que alterar o Helpers.php para [] pois estava {} e no php atual dá problema.
- rodando composer novamente.
- deu outro erro vou rodar um composer update.
- acho que preciso subir os containers primeiro, entrar e rodar o composer i.
- vou tentar subir esse container ecommerce.
- copiar .env.example para .env
- rodar o docker-compose `docker-compose -f docker-compose-dev.yml up -d`
- entrar no container `docker exec -it app_ecommerce bash`
- agora sim rodar composer 
- instalou!
- yarn install
- compilou yarn mas nada acontece...
- olhei logs tem muitos erros o primeiro foi sem key o app rodei `php artisan key:generate`
- alterei as configs do .env e no .env.example
- adicionei o redis aos containers...
- irei rebuildar os containers `docker-compose -f docker-compose-dev.yml up -d --build`
- e entrar no container e buildar o composer sair e buildar yarn
- agora estou o erro é do canal de venda no .env porem estou com ele preenchido.
- continuamos com os mesmo problema do .env não ser encontrado teste com tinker e realmente não tá lendo.
- criei um rota para verificar mais facilmente o .env url-teste mas está retornando as infos como null
