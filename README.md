Este projeto foi feito utilizando docker-composer, Laravel, Mysql, algumas libs js, como jQuery, select2, DataTable, mesmo utilizando jQuery opto por colocar codigo javascript puro pois era uma das requisições do teste

Para instala-lo é necessario ao menos ter o docker desktop instalado no meu caso que utilizo windows, uso o WSL 2 para utiliza-lo

O passo a passo é simples após ter o docker funcionando corretamente basta

1 - abrir o terminal na pasta raiz do projeto
2 - utilizar o comando "composer install", para fazer o download das dependencias do projeto
3 - utilizar o comando "docker compose up -d" para subir o container
4 - utilizar o comando "docker-compose exec app bash" para acessar o terminal do container
5 - Terminal do container acessado dar o comando "php artisan migrate" para criar as tabelas do banco de dados
6 - Após a criação das tabelas utilizar o comando "php artisan db:seed --class=ProductTableSeeder" para popular as tabelas necessarias para utilização do projeto, lembrando que essas seeder são para facilitar o trabalho de popular o banco, por exemplo executar o comando 2 vezes, resultará em 20 produtos, entretanto as referencias vão continuar nos 10 produtos criados anteriormente pois ele pega um numero aleatorio de 1 a 10

O projeto pode ser acessado na rota http://localhost:8989/


Para carregar os produtos eu utilizo um ajax para uma rota, que acessa o banco de dados e traz os dados que eu preciso, normalmente eu não faço dessa forma, eu prefiro trazer os produtos direto para view na rota que a rota é acessada, o front-end é simples, você seleciona um produto que pode ser pesquisado por nome ou referencia utilizando numeros, o cep consulta a API passada no readme do teste, ele consulta na hora que é inserido o ultimo digito do CEP, ele faz uma validação no back-end utilizando a Classe validade do Laravel que ajuda bastante para validações, e mostra na tela os campos inválidos, venda cadastrada, irá fazer uma busca no banco trazendo assim o json de dados, e renderizando em um dataTable, uma biblioteca que gosto bastante de utilizar para trabalhar com tabelas e que já inclui um filtro geral
