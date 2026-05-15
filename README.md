# Controle Financeiro — Bolsa de Valores

Sistema de controle financeiro focado em rastreamento de investimentos e dividendos em ativos de renda variável. Projeto educacional desenvolvido em PHP com Orientação a Objetos.

## Tecnologias

- PHP 8.2 (FPM)
- MySQL 8.0
- Nginx (Alpine)
- Docker + Docker Compose
- PDO (acesso ao banco de dados)
- Chart.js (gráficos de relatório)

---

## Pré-requisitos

| Modo | Requisito |
|------|-----------|
| Docker (recomendado) | [Docker Desktop](https://www.docker.com/products/docker-desktop) instalado e rodando |
| XAMPP | XAMPP com Apache + MySQL iniciados |

---

## Rodando com Docker (recomendado)

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/controle-financeiro.git
cd controle-financeiro
```

### 2. Suba os containers

```bash
docker compose up -d
```

Isso cria três containers:
- `nginx` — servidor web na porta **8080**
- `php` — PHP 8.2-FPM
- `mysql` — MySQL 8.0 na porta **3306**

### 3. Injete as tabelas no banco de dados

```bash
docker compose exec mysql mysql -u dalmolino -pchico123 bolsa_de_valores < db.sql
```

> Caso o banco ainda não exista (primeiro run), execute:
> ```bash
> docker compose exec mysql mysql -u root -proot -e "CREATE DATABASE IF NOT EXISTS bolsa_de_valores;"
> docker compose exec mysql mysql -u root -proot bolsa_de_valores < db.sql
> ```

### 4. Acesse a aplicação

```
http://localhost:8080
```

### Parar os containers

```bash
docker compose down
```

Para remover também os volumes (apaga os dados do banco):

```bash
docker compose down -v
```

---

## Rodando com XAMPP

### 1. Clone o repositório dentro do htdocs

```bash
cd C:/xampp/htdocs
git clone https://github.com/viniciusbarrosdev1/controle-financeiro
```

### 2. Configure a conexão com o banco

Edite `src/classes/Database.php` e ajuste as credenciais:

```php
private $host = 'localhost';
private $db   = 'bolsa_de_valores';
private $user = 'root';     // usuário padrão do XAMPP
private $pass = '';         // senha padrão do XAMPP (vazia)
```

### 3. Injete as tabelas no banco de dados

**Opção A — via MySQL CLI:**

```bash
mysql -u root -p < db.sql
```

**Opção B — via phpMyAdmin:**

1. Acesse `http://localhost/phpmyadmin`
2. Crie o banco `bolsa_de_valores`
3. Selecione o banco criado
4. Vá em **Importar** → selecione o arquivo `db.sql` → clique em **Executar**

**Opção C — executando o SQL manualmente:**

Abra o terminal MySQL e cole:

```sql
CREATE DATABASE bolsa_de_valores;

USE bolsa_de_valores;

CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ativo VARCHAR(10) NOT NULL,
    quantidade INT NOT NULL,
    valor_unitario DECIMAL(10, 2) NOT NULL,
    data_compra DATE NOT NULL
);

CREATE TABLE dividendos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ativo VARCHAR(10) NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    data_recebimento DATE NOT NULL
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(250) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 4. Acesse a aplicação

```
http://localhost/controle-financeiro/src/login.php
```

---

## Credenciais do banco (Docker)

| Campo    | Valor              |
|----------|--------------------|
| Host     | `localhost:3306`   |
| Database | `bolsa_de_valores` |
| Usuário  | `dalmolino`        |
| Senha    | `chico123`         |
| Root     | `root`             |

---

## Estrutura do projeto

```
controle-financeiro/
├── docker-compose.yml
├── Dockerfile
├── db.sql                  # Schema do banco de dados
├── nginx/
│   └── default.conf        # Configuração do Nginx
└── src/
    ├── index.php            # Dashboard (página inicial)
    ├── login.php            # Autenticação
    ├── registro.php         # Cadastro de usuário
    ├── logout.php
    ├── compras.php          # Registrar compra de ativo
    ├── dividendos.php       # Registrar dividendo recebido
    ├── ativos.php           # Visão geral dos ativos
    ├── relatorio.php        # Gráfico investido vs dividendos
    ├── usuarios.php         # Gerenciamento de usuários
    ├── editar_usuario.php
    ├── functions.php
    ├── classes/
    │   ├── Database.php     # Conexão PDO
    │   ├── Usuario.php      # Autenticação e CRUD de usuários
    │   ├── Compra.php       # Operações de compra
    │   ├── Dividendo.php    # Operações de dividendos
    │   └── Ativo.php        # Cálculo de preço médio
    ├── css/
    │   └── style.css
    └── js/
        └── script.js
```

---

## Funcionalidades

- Cadastro e autenticação de usuários (senhas com bcrypt)
- Registro de compras de ativos (ticker, quantidade, valor unitário, data)
- Registro de dividendos recebidos por ativo
- Cálculo de preço médio de compra por ativo
- Relatório visual com Chart.js (total investido vs dividendos)
- Gerenciamento de usuários (CRUD)

---

## Depuração com Xdebug (VS Code)

O projeto já possui configuração em `.vscode/launch.json`. Basta:

1. Instalar a extensão **PHP Debug** no VS Code
2. Iniciar o debug com **F5** → selecionar `Listen for Xdebug`
3. Xdebug escuta na porta **9003**
