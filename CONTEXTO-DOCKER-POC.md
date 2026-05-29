# POC 12 — Containerização do Ambiente de Desenvolvimento (Docker)

**Disciplina:** Desenvolvimento Web Avançado (PHP e OOP)
**Professor:** Alex Morgado Pereira
**Curso:** Tecnologia em Sistemas para Internet
**Atividade:** 2º Bimestre
**Integrantes:** Vinícius Barros de Oliveira (RA 250259) — Carlos Eduardo Tolentino Faustino dos Santos (RA 250260)
**Data de entrega:** 06/06/2026

---

## 1. Qual problema a gente quis resolver?

Esse é um problema que a gente sente todo semestre: **um colega baixa o XAMPP 8.1, o outro baixa o 8.2, e eu fico no 7.4.** Aí na hora de rodar o projeto do grupo um vê tela branca, outro vê erro de extensão, e a aula vira "tenta no meu PC". Sem falar do MySQL, que cada um instala de um jeito.

A ideia da POC foi parar de depender da máquina de cada um. Em vez de instalar PHP + MySQL + Apache na mão, a gente descreve **tudo isso num arquivo** (`docker-compose.yml`) e qualquer pessoa sobe o ambiente com **um comando só**. Não precisa nem ter PHP instalado — só Docker.

## 2. O que foi usado

- **Docker** — quem cria e roda os contêineres.
- **Docker Compose** — quem orquestra os vários serviços juntos.
- **PHP 8.2 FPM** — o interpretador do PHP, rodando como serviço FastCGI.
- **Nginx (alpine)** — servidor web, repassa os `.php` para o PHP.
- **MySQL 8.0** — banco de dados.
- **phpMyAdmin** — interface web pra olhar/editar o banco direto pelo navegador.

## 3. Como o ambiente está organizado

```
┌───────────────────────────────────────────────────────────────┐
│                    docker-compose.yml                         │
│                                                               │
│  ┌─────────┐   ┌─────────┐   ┌─────────┐   ┌──────────────┐   │
│  │  nginx  │──▶│   php   │──▶│  mysql  │◀──│  phpmyadmin  │   │
│  │  :8080  │   │  :9000  │   │  :3306  │   │    :8081     │   │
│  └─────────┘   └─────────┘   └─────────┘   └──────────────┘   │
│       │             │             │                           │
│   ./src         ./src       mysql_data                        │
└───────────────────────────────────────────────────────────────┘
```

- O **Nginx** atende na porta `8080` do PC e manda os `.php` pro serviço `php`.
- O **PHP-FPM** roda o código que está em `./src` (montado como volume).
- O **MySQL** guarda os dados num volume nomeado (`mysql_data`), então **mesmo desligando os contêineres, os dados ficam salvos**.
- O **phpMyAdmin** sobe na porta `8081` pra gente espiar o banco sem precisar de cliente SQL instalado.

A classe `src/classes/Database.php` conecta usando `$host = 'mysql'` — esse nome é o do serviço no compose. O Docker resolve esse nome automaticamente pela rede interna.

## 4. Como rodar

**Pré-requisitos:** Docker Desktop instalado e a porta `8080` livre.

```bash
# entrar no projeto
cd controle-finan

# subir tudo
docker-compose up -d --build
```

Pronto. Em alguns segundos os 4 contêineres estão de pé.


| Onde acessar | URL                                                                |
| ------------ | ------------------------------------------------------------------ |
| Aplicação    | [http://localhost:8080/login.php](http://localhost:8080/login.php) |
| phpMyAdmin   | [http://localhost:8081](http://localhost:8081)                     |


Para desligar:

```bash
docker-compose down          # mantém os dados do banco
docker-compose down -v       # apaga tudo, inclusive o banco
```

## 5. Como testar


| #   | Teste                                         | O que tem que acontecer                                 |
| --- | --------------------------------------------- | ------------------------------------------------------- |
| 1   | `docker-compose ps`                           | 4 serviços com status `Up`                              |
| 2   | Abrir `localhost:8080/registro.php`           | Tela de cadastro carrega normal                         |
| 3   | Criar uma conta e fazer login                 | Cai no dashboard (`index.php`)                          |
| 4   | Cadastrar uma compra                          | Aparece em `ativos.php` e no gráfico do `relatorio.php` |
| 5   | Abrir `localhost:8081` (phpMyAdmin)           | Login automático, vê o banco `bolsa_de_valores`         |
| 6   | `docker-compose down && docker-compose up -d` | Os usuários e compras continuam no banco                |


E a prova mais importante: dá pra clonar esse repositório **numa máquina sem PHP, sem MySQL e sem XAMPP**, rodar `docker-compose up -d --build` e o sistema fica de pé. Sem essa de "ah, instala o XAMPP versão X".

## 6. O que a gente aprendeu fazendo

- O hostname do banco no PHP **não** é `localhost` — é o nome do serviço no compose (`mysql`). Isso porque cada contêiner enxerga só o próprio `localhost`.
- Usar volume nomeado para o MySQL (`mysql_data`) garante que parar o ambiente não significa perder os dados.
- Montar `./src` como volume no PHP permite editar o código no editor e a alteração aparecer no navegador na hora, sem precisar dar `build` de novo.

## 7. Resumo Acadêmico

**[Contexto/Objetivo]** Equipes de desenvolvimento frequentemente enfrentam problemas causados por versões diferentes de PHP, MySQL e XAMPP instaladas nas máquinas de cada integrante, o que dificulta a execução do mesmo projeto em diferentes ambientes. O objetivo desta POC foi padronizar o ambiente do sistema de Controle Financeiro (PHP/OOP) utilizando Docker. **[Metodologia]** Foi escrito um Dockerfile baseado na imagem `php:8.2-fpm` com as extensões `pdo` e `pdo_mysql`, e um `docker-compose.yml` que organiza quatro serviços em contêineres separados: Nginx, PHP-FPM, MySQL 8.0 e phpMyAdmin. Esses serviços se comunicam entre si por uma rede interna criada pelo próprio Compose, e os dados do banco ficam guardados em um espaço reservado pelo Docker, de modo que não se perdem ao desligar os contêineres. **[Resultados]** Com o comando `docker-compose up -d --build`, o ambiente completo foi iniciado. Alterações no código apareceram em tempo real no navegador, e os dados cadastrados permaneceram salvos mesmo após reiniciar os contêineres. **[Conclusão]** A solução foi eficaz para padronizar o ambiente entre os integrantes do grupo, reduzir o tempo de configuração inicial e aproximar a forma como o projeto é executado em desenvolvimento da forma como seria executado em produção.
