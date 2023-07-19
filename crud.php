<?php
    require_once 'connection.php';

    class Crud {
        private $conn;

        public function __construct() {
            $this->conn = (new Connection())->getConnection();
        }

        private function validate($data) {
            $invalidFields = [];
            $regex = "/^[a-zA-ZÀ-ÿ\s'-]+$/";
    
            if (empty($data['nome']) || empty($data['curso']) || empty($data['cidade']) || empty($data['idade'])) {
                $invalidFields = ['nome', 'curso', 'cidade', 'idade'];
            } else {
                if (!preg_match($regex, $data['nome'])) {
                    $invalidFields[] = 'nome';
                }
                if (!preg_match($regex, $data['curso'])) {
                    $invalidFields[] = 'curso';
                }
                if (!preg_match($regex, $data['cidade'])) {
                    $invalidFields[] = 'cidade';
                }
    
                if (!is_numeric($data['idade']) || $data['idade'] < 10 || $data['idade'] > 100) {
                    $invalidFields[] = 'idade';
                }
            }
    
            if (empty($invalidFields)) {
                return true;
            } else {
                return [false, $invalidFields];
            }
        }
    
        private function insertData($data) {
            $query = "INSERT INTO cadastro_aluno (nome, curso, cidade, idade) 
                        VALUES (:nome, :curso, :cidade, :idade)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':curso', $data['curso']);
            $stmt->bindParam(':cidade', $data['cidade']);
            $stmt->bindParam(':idade', $data['idade']);
    
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return false;
            }
        }
    
        private function updateData($id, $data) {
            $query = "UPDATE cadastro_aluno SET nome = :nome, curso = :curso, 
                      cidade = :cidade, idade = :idade WHERE matricula = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':curso', $data['curso']);
            $stmt->bindParam(':cidade', $data['cidade']);
            $stmt->bindParam(':idade', $data['idade']);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        private function getSingleData($id) {
            $query = "SELECT * FROM cadastro_aluno WHERE matricula = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result;
            } else {
                return false;
            }
        }
    
        private function getAllData() {
            $query = "SELECT * FROM cadastro_aluno";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result;
            } else {
                return false;
            }
        }

        private function removeData($id) {
            $query = "DELETE FROM cadastro_aluno WHERE matricula = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
    
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    
        private function getTotalCount() {
            $query = "SELECT COUNT(*) AS total FROM cadastro_aluno";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result['total'];
            } else {
                return 0;
            }
        }

        public function create($data) {
            $validationResult = $this->validate($data);
            return $validationResult !== true ? $validationResult : $this->insertData($data);
        }

        public function read($id) {
            return $this->getSingleData($id);
        }
    
        public function readAll() {
            return $this->getAllData();
        }
    
        public function update($id, $data) {
            $validationResult = $this->validate($data);
            return $validationResult !== true ? $validationResult : $this->updateData($id, $data);
        }

        public function delete($id) {
            return $this->removeData($id);
        }
    
        public function count() {
            return $this->getTotalCount();
        }
    }

?>