### Integrantes

-   Eduardo Soares e Araújo
-   Maria Eduarda Fagundes Pires

### Instalação ambiente Windows + XAMPP

1. Certifique-se que tenha instalado as seguintes ferramentas
    - XAMPP
    - PHP >=8.0
    - Composer
    - MySQL
    - NodeJS + NPM
2. Copie o arquivo .env.example para .env
    - `copy .env.example .env`
3. Altere os valores começando com `DB_` para serem iguais aos dados de acesso do Banco de Dados MySQL
4. Execute os seguintes comandos
    - `composer install`
    - `npm install`
5. Ative o servidor XAMPP
6. Opcionalmente, execute o comando para asset live refresh
    - `npm run dev`

### Instalação ambiente Linux + Docker

1. Certifique-se que tem Docker e Docker Compose instalado
2. Copie o arquivo .env.example para .env
    - `cp .env.example .env`
3. Altere os valores de .env
    - **Importante**: defina `VITE_HOST='0.0.0.0'`
    - Opcionalmente, utilize dados de banco de dados desejados
4. Ative os containers utilizando `docker compose up -d`
5. Acesse o servidor em http://localhost
6. Utilize essa referência para comandos
    - `docker compose exec app sh` - Abre o terminal no ambiente PHP. Utilizado para comandos `composer`
    - `docker compose exec vite sh` - Abre o terminal no ambiente Node. Utilizado para comandos `npm`
    - `docker cmopose exec db sh` - Abre o terminal no ambiente MySQL. Utilizado para comandos `mysql`

### Referências

-   [Docker para NGINX + PHP + MYSQL](https://www.youtube.com/watch?v=S6j4VGMD3Y8&list=PLQH1-k79HB396mS8xRQ5gih5iqkQw-4aV)
-   [PHP Framework Pattern - The Front Controller](https://www.youtube.com/watch?v=akPcD5e9N4M&list=PLQH1-k79HB3-0SKspp8814ZI1GIqRYLAu)

### TODO

-   Implement the object relationship
