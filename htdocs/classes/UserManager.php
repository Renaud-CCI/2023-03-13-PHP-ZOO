<?php


class UserManager {
    private $db; 

    public function __construct(PDO $db){
        $this->setDb($db);
    }

    public function findUserByName(string $name){
        $query = $this->db->prepare('SELECT * FROM users WHERE LOWER(name) = :name ');
        $query->execute(['name' => strtolower($name)]);
        
        $userData = $query->fetch(); 

        if ($userData){
            $user = new User($userData);
            return $user;
        }
        
    }
    
    public function findUserByid(int $id){
        $query = $this->db->prepare('SELECT * FROM users WHERE id = :id ');
        $query->execute(['id' => $id]);
        
        $userData = $query->fetch(); 

        if ($userData){
            $user = new User($userData);
            return $user;
        }
        
    }

    public function setUserInDB(string $name){
        $query = $this->db->prepare('   INSERT INTO users (name)
                                        VALUES (:name)');
        $query->execute(['name' => $name]);
       
        
    }
        
    // GETTERS & SETTERS
    public function setDb($db){
        $this->db = $db;

        return $this;
    }
}


?>