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

    public function findCountEnclosures(int $zoo_id){
        $query = $this->db->prepare('   SELECT COUNT(id)
                                        FROM enclosures
                                        WHERE zoo_id = :zoo_id');
        $query->execute(['zoo_id' => $zoo_id,]);
        $count = $query->fetchColumn();
        return $count;             
    }

    public function findAllAnimalsOfZoo(int $zoo_id){
        $query = $this->db->prepare('   SELECT animals.id, animals.enclosure_id, animals.name, animals.species, animals.sex, animals.weight, animals.height, animals.birthday, animals.isHungry, animals.isSleppy, animals.isSick, animals.dead
                                        FROM animals
                                        INNER JOIN enclosures
                                        ON animals.enclosure_id = enclosures.id
                                        WHERE enclosures.zoo_id = :zoo_id');
        $query->execute([':zoo_id' => $zoo_id]);

        $allAnimalsData = $query->fetchAll(PDO::FETCH_ASSOC); 
        
        $allAnimalsAsObjects = [];  
        
        foreach ($allAnimalsData as $animalData) {
            $animalAsObject = new $animalData['species']($animalData);
            array_push($allAnimalsAsObjects, $animalAsObject);
        }
        
        return $allAnimalsAsObjects;       
    }

    public function findAllDeadAnimals(int $zoo_id){
        $query = $this->db->query('   SELECT *
                                        FROM animals
                                        WHERE dead = 1');
        
        $allDeadAnimalsData = $query->fetchAll(PDO::FETCH_ASSOC); 

        $allDeadAnimalsAsObjects = [];        
        
        foreach ($allDeadAnimalsData as $animalData) {
            $animalAsObject = new $animalData['species']($animalData);
            array_push($allDeadAnimalsAsObjects, $animalAsObject);
        }
        
        return $allDeadAnimalsAsObjects;       
    }

    public function findCountAnimals(int $zoo_id){
        $query = $this->db->prepare('   SELECT COUNT(animals.id)
                                        FROM animals
                                        INNER JOIN enclosures
                                        ON animals.enclosure_id = enclosures.id
                                        WHERE enclosures.zoo_id = :zoo_id');
        $query->execute(['zoo_id' => $zoo_id,]);
        $count = $query->fetchColumn();
        return $count;             
    }

    public function findCountHungryAnimals(int $zoo_id){
        $query = $this->db->prepare('   SELECT COUNT(animals.id)
                                        FROM animals
                                        INNER JOIN enclosures
                                        ON animals.enclosure_id = enclosures.id
                                        WHERE enclosures.zoo_id = :zoo_id AND isHungry<5');
        $query->execute(['zoo_id' => $zoo_id,]);
        $count = $query->fetchColumn();
        return $count;             
    }

    public function findCountSickAnimals(int $zoo_id){
        $query = $this->db->prepare('   SELECT COUNT(animals.id)
                                        FROM animals
                                        INNER JOIN enclosures
                                        ON animals.enclosure_id = enclosures.id
                                        WHERE enclosures.zoo_id = :zoo_id AND isSick<5');
        $query->execute(['zoo_id' => $zoo_id,]);
        $count = $query->fetchColumn();
        return $count;             
    }

    public function updateZooEmployeesInDB(int $id, array $employeesId){

        $query = $this->db->prepare('   UPDATE zoos
                                        SET employees_id = :employeesId
                                        WHERE id = :id');
        $query->execute([   'employeesId' => serialize($employeesId),
                            'id' => $id]);
               
    }

    public function updateZooName(int $id, string $name){
        $query = $this->db->prepare('   UPDATE zoos 
                                        SET name = :name 
                                        WHERE id = :id');
        $query->execute([   'id' => $id,
                            'name' => $name]);
    }

    public function updateBudget(int $id, int $budget){
        $query = $this->db->prepare('   UPDATE zoos 
                                        SET budget = :budget 
                                        WHERE id = :id');
        $query->execute([   'id' => $id,
                            'budget' => $budget]);
    }

    public function updateDay(int $id, int $day){
        $query = $this->db->prepare('   UPDATE zoos 
                                        SET day = :day 
                                        WHERE id = :id');
        $query->execute([   'id' => $id,
                            'day' => $day]);
    }

    public function deleteZooInDB($zoo_id){
        $query = $this->db->prepare('   DELETE FROM zoos 
                                        WHERE id = :id');
        $query->execute([   'id' => $zoo_id]);       
    }

    // Fonctions sans rapport avec la db
    public function echoDeathSentence(){
        $array = [
            "Une petite fille a pleuré devant ce spectacle",
            "Bonne nouvelle : les frais d'équarrissage ne sont pas intégrés au jeu !",
            "Cela veut dire qu'iel ne vit plus",
            "Merci d'observer une minute de silence",
            "Un monument sera érigé en son honneur",
            "Nous offrons une glace au petit garçon témoin de son dernier souffle",
            "Rappelons-nous à quel point la vie est précieuse",
            "De toute façon personne ne l'aimait",
            "Une intoxication alimentaire n'est pas à écarter",
            "Pas de bol !",
            "Probablement par manque de soins"
        ];

        return $array[array_rand($array)];

    }

    // GETTERS & SETTERS
    public function setDb($db)    {
        $this->db = $db;

        return $this;
    }
}


?>