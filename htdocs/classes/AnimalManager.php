<?php


class AnimalManager {
    private $db; 

    public function __construct(PDO $db){
        $this->setDb($db);
    }
    
    public function setAnimalInDb(array $data){
        $query = $this->db->prepare('   INSERT INTO animals
                                        (enclosure_id, name, species, sex, weight, height, birthday)
                                        VALUES (:enclosure_id, :name, :species, :sex, :weight, :height, :birthday)');
        $query->execute([   'enclosure_id' => $data['enclosure_id'],
                            'name' => $data['animalName'],
                            'species' => $data['animal_type'],
                            'sex' => $data['sex'],
                            'weight' => $data['animalWeight'],
                            'height' => $data['animalHeight'],
                            'birthday' => $data['birthday'],]);


        $query = $this->db->prepare('   UPDATE enclosures
                                        SET animals_type = :animals_type
                                        WHERE id = :id ');
        $query->execute([   'animals_type' => $data['animal_type'],
                            'id' => $data['enclosure_id'] ]);

        
    }

    public function findAnimal(int $id){
        $query = $this->db->prepare('SELECT * FROM animals
                                    WHERE id = :id');
        $query->execute(['id' => $id,]);

        $animalData = $query->fetch(PDO::FETCH_ASSOC);
          
        return new $animalData['species']($animalData);
                   
    }

    public function findAllAnimalsOfEnclosure(int $enclosure_id){
        $query = $this->db->prepare(' SELECT * FROM animals
                                    WHERE enclosure_id = :enclosure_id
                                    ORDER BY name');
        $query->execute(['enclosure_id' => $enclosure_id,]);
        
        $allAnimalsData = $query->fetchAll(PDO::FETCH_ASSOC); 

        $allAnimalsAsObjects = [];        
        
        foreach ($allAnimalsData as $animalData) {
            $animalAsObject = new $animalData['species']($animalData);
            array_push($allAnimalsAsObjects, $animalAsObject);
        }
        


        return $allAnimalsAsObjects;       
    }

    public function findEnclosureCleanlinessOfAnAnimal(int $animalId){
        $query = $this->db->prepare('   SELECT enclosures.cleanliness
                                        FROM enclosures
                                        INNER JOIN animals
                                        ON animals.enclosure_id = enclosures.id
                                        WHERE animals.id = :animalId');
        $query->execute(['animalId' => $animalId,]);
        
        $animalEnclosureCleanliness = $query->fetch(PDO::FETCH_ASSOC); 

        return $animalEnclosureCleanliness['cleanliness'];       
    }

    public function updateIsHungry(int $animalId, float $isHungry){
        $query = $this->db->prepare('   UPDATE animals 
                                        SET isHungry = :isHungry 
                                        WHERE id = :id');
        $query->execute([   'id' => $animalId,
                            'isHungry' => $isHungry]);

    }

    public function updateIsSick(int $animalId, float $isSick){
        $query = $this->db->prepare('   UPDATE animals 
                                        SET isSick = :isSick 
                                        WHERE id = :id');
        $query->execute([   'id' => $animalId,
                            'isSick' => $isSick]);
    }

    public function updateDeadAnimal(int $animalId){
        $query = $this->db->prepare('   UPDATE animals 
                                        SET dead = 1 
                                        WHERE id = :id');
        $query->execute([   'id' => $animalId]);
    }

    public function deleteDeadAnimal(int $animalId){
        $query = $this->db->prepare('   DELETE FROM animals 
                                        WHERE id = :id');
        $query->execute([   'id' => $animalId]);
    }


        
    // GETTERS & SETTERS
    public function setDb($db){
        $this->db = $db;

        return $this;
    }
}


?>