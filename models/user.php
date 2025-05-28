<?php
namespace App\models;

use PDO;
use App\core\Database;

class UserModel {
    private $db;
    private $id;
    private $name;
    private $password;
    private $email;

    private $tipo;
    private $idPerson;

    private $CreationAcount;
    public function __construct() {
       $this->db = new Database()->connect(); // Obtiene la instancia Singleton de PDO
    }

    public function createUser($data) {
        $stmt = $this->db->prepare(
            query: "INSERT INTO users (CA, name, email, password, id_person) 
             VALUES (:CA,:name, :email, :password, :id_person)"
        );
        $stmt->execute(params: [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash(password: $data['password'], algo: PASSWORD_BCRYPT),
            'id_person' => $data['id_person']
        ]);
        return $this->db->lastInsertId();
    }


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

    //Metodos get----------------------------------------------------------------------
    public function getId(): mixed  {
        return $this->id;
    }

    public function getName(): mixed  {
        return $this->name;
    }

    public function getPassword(): mixed  {
        return $this->password;
    }

    public function getEmail(): mixed  {
        return $this->email;
    }

    public function getUserById($id): mixed {
        $stmt = $this->db->prepare(query: "SELECT * FROM users WHERE id = :id");
        $stmt->execute(params: ['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //Metodos set----------------------------------------------------------------------
    public function setId(): mixed  {
        return $this->id;
    }

    public function setName($name): void  {
        $this->name = $name;
    }

    public function setPassword($password): void  {
        $this->password = $password;
    }

    public function setEmail($email): void  {
        $this->email = $email;
    }

    public function setCreationAcount($fecha){
        $this->CreationAcount = $fecha;
    }

    public function setIdPerson($Id_person){
        $this->idPerson = $Id_person;
    }

}