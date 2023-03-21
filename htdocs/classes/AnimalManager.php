<?php


class AnimalManager {
    private $db; 

    public function __construct(PDO $db){
        $this->setDb($db);
    }

    public function findAnimal(int $id){
        $query = $this->db->prepare('SELECT * FROM animal
                                    WHERE id = :id');
        $query->execute(['id' => $id,]);

        $animalData = $query->fetch(PDO::FETCH_ASSOC);

        // var_dump($employeeData);
        // die;
          
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

    public function setAnimalInDb(array $data){
        $query = $this->db->prepare('   INSERT INTO animals
                                        (enclosure_id, name, species, sex, weight, height)
                                        VALUES (:enclosure_id, :name, :species, :sex, :weight, :height)');
        $query->execute([   'enclosure_id' => $data['enclosure_id'],
                            'name' => $data['animalName'],
                            'species' => $data['animal_type'],
                            'sex' => $data['sex'],
                            'weight' => $data['animalWeight'],
                            'height' => $data['animalHeight'],]);


        $query = $this->db->prepare('   UPDATE enclosures
                                        SET animals_type = :animals_type
                                        WHERE id = :id ');
        $query->execute([   'animals_type' => $data['animal_type'],
                            'id' => $data['enclosure_id'] ]);

        
    }

        
    // GETTERS & SETTERS
    public function setDb($db){
        $this->db = $db;

        return $this;
    }
}


?>