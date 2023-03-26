<?php


class EnclosureManager {
    private $db; 

    public function __construct(PDO $db){
        $this->setDb($db);
    }
    
    public function setEnclosureInDb(Enclosure $enclosure){
        $query = $this->db->prepare('   INSERT INTO enclosures (zoo_id, enclosure_type, name)
                                        VALUES (:zoo_id, :enclosure_type, :name)');
        $query->execute([   
                            'zoo_id' => $enclosure->getZoo_id(),
                            'enclosure_type' => $enclosure->getEnclosure_type(),
                            'name' => $enclosure->getName()]);
    }

    public function findEnclosure(int $enclosure_id){
        $query = $this->db->prepare('SELECT * FROM enclosures
                                    WHERE id = :id');
        $query->execute(['id' => $enclosure_id,]);
        $enclosureData = $query->fetch(PDO::FETCH_ASSOC);

        return new $enclosureData['enclosure_type']($enclosureData);
                   
    }

    public function findCountAnimals(int $enclosure_id){
        $query = $this->db->prepare('   SELECT COUNT(id) FROM animals
                                        WHERE enclosure_id=:enclosure_id');
        $query->execute(['enclosure_id' => $enclosure_id,]);
        $count = $query->fetchColumn();
        return $count;             
    }

    public function findCountHungryAnimals(int $enclosure_id){
        $query = $this->db->prepare('   SELECT COUNT(id) FROM animals
                                        WHERE enclosure_id=:enclosure_id AND isHungry<4.5');
        $query->execute(['enclosure_id' => $enclosure_id,]);
        $count = $query->fetchColumn();
        return $count;             
    }

    public function findCountSickAnimals(int $enclosure_id){
        $query = $this->db->prepare('   SELECT COUNT(id) FROM animals
                                        WHERE enclosure_id=:enclosure_id AND isSick<=4.5');
        $query->execute(['enclosure_id' => $enclosure_id,]);
        $count = $query->fetchColumn();
        return $count;             
    }

    public function findCountSleppyAnimals(int $enclosure_id){
        $query = $this->db->prepare('   SELECT COUNT(id) FROM animals
                                        WHERE enclosure_id=:enclosure_id AND isSleppy=1');
        $query->execute(['enclosure_id' => $enclosure_id,]);
        $count = $query->fetchColumn();
        return $count;             
    }

    public function findAllEnclosuresOfZoo(int $zoo_id){
        $query = $this->db->prepare(' SELECT * FROM enclosures
                                    WHERE zoo_id=:zoo_id
                                    ORDER BY id');
        $query->execute(['zoo_id' => $zoo_id,]);
        $allEnclosuresData = $query->fetchAll(PDO::FETCH_ASSOC); 

        $allEnclosuresAsObjects = [];        
        
        foreach ($allEnclosuresData as $enclosureData) {
            $enclosureAsObject = new $enclosureData['enclosure_type']($enclosureData);
            array_push($allEnclosuresAsObjects, $enclosureAsObject);
        }
        
        return $allEnclosuresAsObjects;       
    }

    public function updateEnclosureName(int $id, string $name){
        $query = $this->db->prepare('   UPDATE enclosures 
                                        SET name = :name 
                                        WHERE id = :id');
        $query->execute([   'id' => $id,
                            'name' => $name]);
    }

    public function updateCleanliness(int $enclosureId, float $cleanliness){
        $query = $this->db->prepare('   UPDATE enclosures 
                                        SET cleanliness = :cleanliness 
                                        WHERE id = :id');
        $query->execute([   'id' => $enclosureId,
                            'cleanliness' => $cleanliness]);
    }

        
    // GETTERS & SETTERS
    public function setDb($db){
        $this->db = $db;

        return $this;
    }
}


?>