<?php


class EmployeeManager {
    private $db; 

    public function __construct(PDO $db){
        $this->setDb($db);
    }

    public function findEmployee(int $id){
        $query = $this->db->prepare('SELECT * FROM employees
                                    WHERE id = :id');
        $query->execute(['id' => $id,]);

        $employeeData = $query->fetch(PDO::FETCH_ASSOC);

        // var_dump($employeeData);
        // die;
          
        return new Employee($employeeData);
                   
    }

    public function findAllEmployees(){
        $query = $this->db->query('SELECT * FROM employees ORDER BY name');
        
        $allEmployeesData = $query->fetchAll(PDO::FETCH_ASSOC); 

        $allEmployeesAsObjects = [];        
        
        foreach ($allEmployeesData as $employeeData) {
            $employeeAsObject = new Employee($employeeData);
            array_push($allEmployeesAsObjects, $employeeAsObject);
        }
        
        return $allEmployeesAsObjects;       
    }

    public function updateActions(int $employeeId, int $employeeActions){
        $query = $this->db->prepare('   UPDATE employees 
                                        SET actions = :actions 
                                        WHERE id = :id');
        $query->execute([   'id' => $employeeId,
                            'actions' => $employeeActions]);
    }

        
    // GETTERS & SETTERS
    public function setDb($db){
        $this->db = $db;

        return $this;
    }
}


?>