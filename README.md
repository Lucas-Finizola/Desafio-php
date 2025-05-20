
# 📦 Desafio Engeselt Softwares - Desenvolvedor Web PHP JR

=======
# Desafio_php
>>>>>>> a096248 (Update README.md)
Este projeto utiliza o framework Laravel. Abaixo estão os passos completos para rodar o ambiente local, indicamos o uso do laradock onde abaixo deixaremos um link de exemplificação de uso, porém pode ser feito uso do laravel sail, docker... O importante é que o projeto seja executado e consiga ser feito as devidas tratativas.
<<<<<<< HEAD

---
## Requisitos ✅✨

- PHP >= 8.3
- Composer 2.8.5
- PostgreSQL 17
- Node.JS 22.15.1 (LTS)
- Docker 28.1

## Executando o Projeto Localmente 🚀✨

### 1. Clone o repositório 😎

```
git clone https://git.engeselt.com.br/wesley.silva/desafio-php.git
```

### 2. Copie o arquivo .env 📂

```
cp .env.example .env
```

### 3. Configuração do Laradock e Execução do Projeto 🚀

> Recomendamos o uso do Laradock, mas sinta-se livre para executar o projeto da forma que for mais conveniente para você.

[Clique aqui para acessar um tutorial de configuração do Laradock](https://youtu.be/4oO_ZGX3Rbs?si=dFLaG_HvlAOU5uiv) 

Na raiz do projeto há um arquivo `.env.laradock.example`, nele já está definido todo o ambiente com as respectivas versões das tecnologias utilizadas.

1° - Na raiz do projeto, utilize o comando abaixo para copiar o arquivo `.env.laradock.example` para a raiz do seu Laradock:
```
cp .env.laradock.example laradock/.env
```
> Dependendo da sua estrutura de pastas, será necessário ajustar o path de destino.

2° - Cerifique-se de ter o Docker instalado, caso não tenha [clique aqui para seguir o passo a passo para instalação](https://docs.docker.com/get-started/get-docker/).

3° - Na raiz do seu Laradock execute o comando abaixo:
```
docker compose up -d postgres nginx
```
> Dependendo da sua conexão com internet, o processo pode ser um pouco demorado, pois ao executar pela primeira vez o Docker irá baixar as imagens dos respectivos containers.

4° - Crie a base de dados `chamados` que será utilizada pela aplicação:
* Conecte-se ao container do PostgreSQL:
    ```
    docker exec -it laradock-postgres-1 bash
    ```
* Conecte-se ao servidor do PostgreSQL:
    ```
    psql -h localhost -U default -w
    ```
* Crie o banco de dados:
    ```
    CREATE DATABASE chamados;
    ```
* Desconecte-se do servidor do PostgreSQL:
    ```
    \q
    ```
* Por fim, encerre a sessão no bash do container:
    ```
    exit
    ```

5° - Agora vamos: instalar as dependências do projeto, executar as migrations, executar as seeders, gerar a app key e instalar as dependências do front-end:
* Conecte-se ao Workspace:
    ```
    docker exec -it laradock-workspace-1 bash
    ```
* Instale as dependências do projeto:
    ```
    composer install
    ```
* Execute as migrations:
    ```
    php artisan migrate
    ```
* Execute as seeders:
    ```
    php artisan db:seed
    ```
    > Esse comando é responsável por popular seu banco de dados com alguns dados iniciais.
* Gere a APP Key do Laravel:
    ```
    php artisan key:generate
    ```
* Instale as dependências do front-end:
    ```
    npm install
    ```

6° - Ainda no Workspace, vamos executar o front-end da aplicação:
```
npm run dev
```

Pronto, agora será possível acessar a aplicação através do link: [http://localhost](http://localhost).

### 4. Usuários de Acesso 🧑‍💻

A aplicação conta com dois usuários para que você possa utilizar para testar a aplicação.

E-mail | Senha | Role
------ | ----- | ------
colaborador@mail.com | colaborador#engeselt | Colaborador
tecnico@mail.com | tecnico#engeselt | Técnico

* Role Colaborador: Pode criar novos chamados, acompanhar os chamados existentes e filtrar os chamados;
* Role Técnico: Pode visualizar todos os chamados, acompanhar os chamados existentes e filtrar os chamados;
=======
=======
# 📦 Laravel - Guia de Instalação Completo

Este projeto utiliza o framework Laravel. Abaixo estão os passos completos para rodar o ambiente local, indicamos o uso do laradock onde abaixo deixaremos um link de exemplificação de uso, porém pode ser feito uso do laravel sail, docker... O importante é que o projeto seja executado e consiga ser feito as devidas tratativas.

---

## ✅ Requisitos

- PHP >= 8.3;
- Composer;
- MySQL ou PostgreSQL;
- node.js e npm.
- docker
- docker-compose

## 🚀 Instalação - Ambiente Local

### 1. Clonar o repositório

<!-- ```bash
git clone https://github.com/seu-usuario/seu-projeto.git
cd seu-projeto -->

### 2. Copiar o arquivo .env (Configurações ambiente Laravel)

cp .env.example .env (Realize as configurações do .env de acordo com as especifições das tecnologias usadas e suas portas).

### 3. Laradock (caso faça uso)

https://youtu.be/4oO_ZGX3Rbs?si=dFLaG_HvlAOU5uiv (exemplificação de uso) - deixaremos um ".env.laradock.example" solto no projeto para substituir o .env padrão do laradock com as configurações do ambiente já definidas, caso necessário realize ajustes...

Cerifique-se de ter o docker/docker-compose instalado.

Após download do repositório e suas devidas configurações acesse o caminho do laradock pelo terminal, rode o comando "docker-compose up -d postgres nginx".

Rode o comando no terminal "docker-compose exec workspace bash" e siga com os demais comandos abaixo, sempre executando os comandos artisan e npm dentro do workspace.

### 4. Instalar as dependências PHP

composer install

### 4. Rodar as migrations

php artisan migrate

### 5. Rodar as seeders

php artisan db:seed

### 6. Gerar APP Key

php artisan key:generate

### 7. Instalar dependências front-end (executar o npm)

npm install
npm run dev
>>>>>>> ce5afc5 (Initial commit)


