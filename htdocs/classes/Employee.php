<?php

class Employee {

    private int $id;
    private string $name;
    private float $age;
    private string $sex;
    private string $genderSymbol;
    private int $default_actions;
    private int $salary = 100;
    private int $actions;

    public function __construct(array $data){
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

        if (isset($data["birthdate"])){
            // Calcule l'âge à partir d'une date de naissance jj/mm/aaaa
            //On déclare les dates à comparer
            $dateNais = new DateTime($data["birthdate"]);
            $dateJour = new DateTime();

            //On calcule la différence
            $difference = $dateNais->diff($dateJour);

            //On retourne la différence en années
            $this->setAge($difference->format('%Y')); 
            
           
        }

        if (isset($data["sex"])){
            switch ($data["sex"]){
                case "Homme":
                    $this->setGenderSymbol('https://img.icons8.com/office/16/null/male.png');
                    break;
                case 'Femme':
                    $this->setGenderSymbol('https://img.icons8.com/office/16/null/female.png');
                    break;
                case 'Autre':
                    $this->setGenderSymbol('https://img.icons8.com/external-flaticons-lineal-color-flat-icons/64/null/external-gender-lgbt-flaticons-lineal-color-flat-icons-2.png');
                    break;
                default:
                    $this->setGenderSymbol('');
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

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;

        return $this;
    }

    public function getAge(){
        return $this->age;
    }
 
    public function setAge($age){
        $this->age = $age;

        return $this;
    }

    public function getSex(){
        return $this->sex;
    }

    public function setSex($sex){
        $this->sex = $sex;

        return $this;
    }

    public function getGenderSymbol(){
        return $this->genderSymbol;
    }

    public function setGenderSymbol($genderSymbol){
        $this->genderSymbol = $genderSymbol;

        return $this;
    }

    public function getActions(){
        return $this->actions;
    }

    public function setActions($actions){
        $this->actions = $actions;

        return $this;
    }

    public function getSalary(){
        return $this->salary;
    }

    public function setSalary($salary){
        $this->salary = $salary;

        return $this;
    }

    public function getDefault_actions(){
        return $this->default_actions;
    }

    public function setDefault_actions($default_actions){
        $this->default_actions = $default_actions;

        return $this;
    }
}

?>