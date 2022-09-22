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


