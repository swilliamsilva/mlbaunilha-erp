# instale
composer require bovigo/vfsStream

# mlbaunilha-erp
MERCADINHO LIBRE DA BAUNILHA

### Executar teste

docker build -t mlbaunilha-erp . 
docker run -d -p 8080:80 --name mlbaunilha-erp mlbaunilha-erp
docker logs mlbaunilha-erp
docker ps                          Para ver a porta 
docker exec -it mlbaunilha-erp bash

# Executar no docker 

# Executar todos os testes com cobertura
phpunit --coverage-html build/coverage

# Executar apenas testes do CodeIgniter Core
phpunit --testsuite "CodeIgniter Core"

# Gerar relatório para SonarQube
phpunit --coverage-clover build/logs/clover.xml
# Criação de tabelas e banco de dados
Na raiz do projeto você encontra instruções em 
/system/ setup.php 
e apartir deste você pode criar suas tabelas manualmente se preferir
em Cloud usando railway ou outra plataforma você pode digitar o seguinte endereço dentro para ele gerar automaticamente.

https://mlbaunilha-erp-production.up.railway.app/setup.php


# Para popular dados 
Voce pode usar o codigo da pasta /system/seed.php
pode copiar e usar no modelo em que estiver trabalhando
se for utilizando o cloud do railway pode gerar para testar
usando o endereço:

https://mlbaunilha-erp-production.up.railway.app/seed.php

# Para executar no terminal
 php -S localhost:8000

# Test utilizando webhooks
Na pasta test você encontra duas pastas 
Uma phpadmin para serem importados e executados dentro do phpadmin.
Na pasta powershell você tem testes para executar por este aplicativo
e neste caso basta digitar 
./webhook_test_completo.ps1 ele precisa ficar junto com o arquivo
webhook_test.sql que um teste para os arquivos do sql. Este pode ser importado
no phpadmin.


