<?php

    class Connection {
        private $host = 'localhost';
        private $username = 'root';
        private $password = '';
        private $database = 'escola';
        private $conn;

        public function __construct() {
            // data source name
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8";

            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            );

            try {
                $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $e) {
                // equivale ao exit() porém permite que seja exibida uma mensagem adicional
                die('Ocorreu um erro na conexão');
            }
        }

        public function getConnection() {
            return $this->conn;
        }
    }
?>