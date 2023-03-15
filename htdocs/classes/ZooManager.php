<?php


class ZooManager {
    private $db; 

    public function __construct(PDO $db){
        $this->setDb($db);
    }

    public function setZooInDB(string $name, string $userId, array $employeesId){

        $query = $this->db->prepare('   INSERT INTO zoos (name,user_id, employees_id)
                                        VALUES (:name,:user_id, :employeesId)');
        $query->execute([   'name' => $name,
                            'user_id' => $userId,
                            'employeesId' => serialize($employeesId)
                        ]);
       
        
    }

    public function findZoo(int $id){
        $query = $this->db->prepare('SELECT * FROM zoos
                                    WHERE id = :id');
        $query->execute([   'id' => $id,]);

        $zooDatas = $query->fetch(PDO::FETCH_ASSOC);

        $zooDatas['employees_id'] = unserialize($zooDatas['employees_id']);
        $zooDatas['enclosures_array'] = unserialize($zooDatas['enclosures_array']);

        return new Zoo ($zooDatas);

        
    }

    public function findAllZoosOfUser(int $user_id){
        $query = $this->db->prepare('SELECT * FROM zoos
                                    WHERE user_id = :user_id');
        $query->execute([   'user_id' => $user_id,]);

        $allZooDatas = $query->fetchAll(PDO::FETCH_ASSOC);

        $allZoosAsObjects = [];
                
        foreach ($allZooDatas as $zooDatas) {
            $zooDatas['employees_id'] = unserialize($zooDatas['employees_id']);
            $zooDatas['enclosures_array'] = unserialize($zooDatas['enclosures_array']);
        
            $zoo = new Zoo($zooDatas);
                
            array_push($allZoosAsObjects, $zoo);  
        }

        return $allZoosAsObjects;
    }

    // GETTERS & SETTERS
    public function setDb($db)    {
        $this->db = $db;

        return $this;
    }
}


?>