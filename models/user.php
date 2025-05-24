<?php

require_once __DIR__ . '/../config/Database.php';
class UserModel {
    private $db;

    public function __construct() {
       $this->db = (new Database())->connect(); // Obtiene la instancia Singleton de PDO
    }

  
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password) 
             VALUES (:name, :email, :password)"
        );
        $stmt->execute([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
        return $this->db->lastInsertId();
    }

    // Ejemplo: Actualizar usuario
    public function updateUser($id, $data) {
        $stmt = $this->db->prepare(
            "UPDATE users SET name = :name, email = :email WHERE id = :id"
        );
        return $stmt->execute([
            'id'    => $id,
            'name'  => $data['name'],
            'email' => $data['email']
        ]);
    }
}