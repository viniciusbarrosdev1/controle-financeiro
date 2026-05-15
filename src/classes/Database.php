<?php

class Database {
    private $host = 'mysql';
    private $db = 'bolsa_de_valores';
    private $user = 'dalmolino';
    private $pass = 'chico123';
    private $pdo;

    public function connect() {
        if (!$this->pdo) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db}";
                $this->pdo = new PDO($dsn, $this->user, $this->pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }
}
?>
