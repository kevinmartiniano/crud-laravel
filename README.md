## Sobre o projeto

O projeto é um CRUD simples feito em Laravel 11 com o intuíto de demonstrar de forma simples práticas de programação que adoto hoje em dia!

## Documentação

- Temos uma tabela de contatos que foi inspirada em uma agenda de contatos telefônicos de um telefone celular.
  - id (Identificação do registro)
  - first_name (Primeiro nome)
  - last_name (Segundo nome ou sobrenome)
  - company (Nome da empresa)
  - phone_number (Número de telefone)
  - mobile_number (Número do celular)
  - email (Endereço de email)
  - birth_date (Data de aniversário)
  - created_at (Data de criação do registro)
  - updated_at (Data de atualização do registro)
  - deleted_at (Data de deleçao do registro) OBS: Funcionalidade de delete ainda não implementada.
- Swagger OBS: Ainda não implementado
- Rotas
  - Criação de contatos (POST http://localhost/contacts body: {"first_name": "nome", "mobile_number": "(12) 1234-1234"}) OBS: Os campos first_name e last_name são obrigatórios.
  - Edição de contatos (PUT http://localhost/contacts/<id> {"phone_number": "(12) 1234-1234"})
  - Detalhes do contato (GET http://localhost/contacts/<id>)
  - Listagem de todos os contatos (GET http://localhost/contacts)
  - Listagem de contatos por filtro (GET http://localhost/contacts?first_name="nome") OBS: Podemos filtrar por qualquer um dos campos da tabela de contatos e por mais de um campo de uma vez em forma de AND na consulta.
- Makefile foram criados comandos com o objetivo de facilitar a execução de alguns comandos.
  - ```make up``` (Comando para "subir" a aplicação)
  - ```make down``` (Comando para parar a aplicação)
  - ```make restart``` (Comando para reiniciar a aplicação)
  - ```make ps``` (Comando para verificar os containers que estão up)
  - ```make composer-install``` (Comando para executar o composer install dentro do container da aplicação)
  - ```make test``` (Comando para executar os testes)
  - ```make test-filter filter="NomeDoTeste"``` (Comando para executar teste baseado no filtro)
  - ```make bash``` (Comando para acessar o terminal do docker que contém o código da aplicação)
  - ```make run exec="comando para executar dentro do container"``` (Comando para executar outros comandos dentro do container da aplicação)
  - ```make cs-fixer directory="app"``` (Comando para corrigir os code smells)

## Créditos

Documentação do framework utilizado (Laravel 11) [documentation](https://laravel.com/docs)

Parceiros da Laravel [Laravel Partners program](https://partners.laravel.com).
