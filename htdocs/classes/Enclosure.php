<?php
class Enclosure {
    private int $id;
    private int $zoo_id;
    private string $enclosure_type;
    private int $account;
    private string $name;
    private float $cleanliness;
    private string $animals_type;

    
    public function __construct(array $data){
        $this->hydrate($data);
    }

    protected function hydrate(array $data){
        // On fait une boucle avec le tableau de données
        foreach ($data as $key => $value) {
            // On récupère le nom des setters correspondants
            // si la clef est id le setter est setId
            // il suffit de mettre la 1ere lettre de key en Maj et de le préfixer par set
            $method = 'set'.ucfirst($key);

            // On vérifie que le setter correspondant existe
            if (method_exists($this, $method)) {
                // S'il existe, on l'appelle
                $this->$method($value);
            }
        }
    }
    
    // GETTERS & SETTERS
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;

        return $this;
    }

    public function getZoo_id(){
        return $this->zoo_id;
    }

    public function setZoo_id($zoo_id){
        $this->zoo_id = $zoo_id;

        return $this;
    }

    public function getEnclosure_type(){
        return $this->enclosure_type;
    }

    public function setEnclosure_type($enclosure_type){
        $this->enclosure_type = $enclosure_type;

        return $this;
    }

    public function getAccount(){
        return $this->account;
    }

    public function setAccount($account){
        $this->account = $account;

        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;

        return $this;
    }

    public function getCleanliness(){
        return $this->cleanliness;
    }

    public function setCleanliness($cleanliness){
        $this->cleanliness = $cleanliness;

        return $this;
    }

    public function getAnimals_type(){
        return $this->animals_type;
    }

    public function setAnimals_type($animals_type){
        $this->animals_type = $animals_type;

        return $this;
    }
}

?>