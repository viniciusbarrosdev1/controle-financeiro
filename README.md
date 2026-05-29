Controle Financeiro — Gestão de Ativos

Aplicação em PHP para acompanhar investimentos em ativos da bolsa: registro de
compras, dividendos recebidos, cálculo de preço médio e um relatório visual
comparando total investido x dividendos. O ambiente roda em Docker
(Nginx + PHP-FPM + MySQL + phpMyAdmin).

Funcionalidades





Cadastro e autenticação de usuários (senhas com bcrypt)



Registro de compras de ativos (ticker, quantidade, valor unitário, data)



Registro de dividendos recebidos por ativo



Cálculo de preço médio de compra por ativo



Relatório visual com Chart.js (total investido x dividendos)



Gerenciamento de usuários (CRUD)

Stack





PHP 8.2 (PHP-FPM)



MySQL 8.0



Nginx (Alpine)



phpMyAdmin



Docker / Docker Compose



Xdebug 3 (depuração)

Estrutura do projeto

.
├── docker-compose.yml          # Orquestra nginx, php, mysql e phpmyadmin
├── Dockerfile                  # Imagem PHP-FPM + pdo_mysql + Xdebug
├── db.sql                      # Schema do banco (tabelas)
├── docker/
│   └── xdebug.ini              # Configuração do Xdebug
├── nginx/
│   └── default.conf            # Virtual host do Nginx
└── src/                        # Raiz da aplicação (document root)
    ├── classes/                # Camada de domínio/acesso a dados (PDO)
    │   ├── Database.php
    │   ├── Usuario.php
    │   ├── Ativo.php
    │   ├── Compra.php
    │   └── Dividendo.php
    ├── layouts/                # Cabeçalho e rodapé reutilizáveis
    │   ├── header.php
    │   └── footer.php
    ├── css/
    │   └── style.css
    ├── functions.php           # Helpers (ex.: validação de sessão)
    ├── index.php               # Dashboard
    ├── login.php / registro.php / logout.php
    ├── compras.php / dividendos.php / ativos.php
    ├── relatorio.php
    ├── usuarios.php / editar_usuario.php
    └── infophp.php

Como executar

Pré-requisitos: Docker e Docker Compose instalados.

docker compose up -d --build

Serviços disponíveis:







Serviço



URL





Aplicação



http://localhost:8080





phpMyAdmin



http://localhost:8081

Banco de dados

O db.sql contém o schema das tabelas (usuarios, compras, dividendos).
O banco bolsa_de_valores é criado automaticamente pelo container MySQL, mas as
tabelas precisam ser importadas. Importe o db.sql via phpMyAdmin
(http://localhost:8081) ou pela linha de comando:

docker compose exec -T mysql mysql -uroot -proot bolsa_de_valores < db.sql



Para um novo cadastro/login funcionar, as tabelas precisam existir.

Credenciais padrão (apenas desenvolvimento)







Onde



Usuário



Senha





MySQL root



root



root





MySQL app



dalmolino



chico123

Depuração com Xdebug (VS Code)

O Xdebug 3 já vem instalado e habilitado na imagem PHP (porta 9003), com
acesso ao host configurado via host.docker.internal no docker-compose.yml.

Para depurar no VS Code:





Instale a extensão PHP Debug (xdebug.php-debug).



Crie o arquivo .vscode/launch.json com o conteúdo abaixo:

 {
     "version": "0.2.0",
     "configurations": [
         {
             "name": "Listen for Xdebug",
             "type": "php",
             "request": "launch",
             "port": 9003,
             "pathMappings": {
                 "/var/www/html": "${workspaceFolder}/src"
             }
         }
     ]
 }



Pressione F5 e selecione Listen for Xdebug.



Coloque um breakpoint e acesse a aplicação no navegador — a execução vai parar

no ponto marcado.

Log do Xdebug (dentro do container): /tmp/xdebug.log.

Comandos úteis

docker compose ps                 # Status dos containers
docker compose logs -f php        # Logs do PHP-FPM
docker compose restart nginx      # Reinicia o Nginx (útil após recriar o php)
docker compose down               # Para e remove os containers
docker compose down -v            # Idem, removendo também os dados do MySQL

