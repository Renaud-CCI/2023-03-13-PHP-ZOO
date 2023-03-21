<?php


class EnclosureManager {
    private $db; 

    public function __construct(PDO $db){
        $this->setDb($db);
    }

    public function findEnclosure(int $id){
        $query = $this->db->prepare('SELECT * FROM enclosures
                                    WHERE id = :id');
        $query->execute(['id' => $id,]);
        $enclosureData = $query->fetch(PDO::FETCH_ASSOC);

        return new $enclosureData['enclosure_type']($enclosureData);
                   
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

    public function setEnclosureInDb(Enclosure $enclosure){
        $query = $this->db->prepare('   INSERT INTO enclosures (zoo_id, enclosure_type, name)
                                        VALUES (:zoo_id, :enclosure_type, :name)');
        $query->execute([   
                            'zoo_id' => $enclosure->getZoo_id(),
                            'enclosure_type' => $enclosure->getEnclosure_type(),
                            'name' => $enclosure->getName()]);
    }

    public function setZooEnclosureInDb(){

    }

        
    // GETTERS & SETTERS
    public function setDb($db){
        $this->db = $db;

        return $this;
    }
}


?>