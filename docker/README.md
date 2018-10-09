# Docker

Este projeto é um bootstrap para outros projetos em wordpress usando Docker, PHP7 e Composer.

## Como instalar

Com docker-composer instalado rode a build para configurar os serviços que estão em docker-compose.yml:

    docker-composer build

Será montado um container com os seguintes serviços:

- db: serviço de banco de dados já configurado um banco para o wordpress
- wordpress: serviço que vai conter o servidor com php7 e wordpress instalado.

## Como rodar

Após fazer a build rode o seguinte comando:

    docker-composer up


## Como desenvolver

O projeto é desenvolvido através da tema padrão em:

    /wp-content/themes/

E através do Webpack é que os arquivos de Sass e Javascript são processados:

    // Developing mode
    npm start

    // Production mode
    npm run build

## Plugins do Wordpress

Durante a build do serviço de wordpress será instalado e rodado o composer do php para instalar os plugins do wordpress.

Arquivos envolvidos:

- composer.json
- app.docker