<?php


class EmployeeManager {
    private $db; 

    public function __construct(PDO $db){
        $this->setDb($db);
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

        
    // GETTERS & SETTERS
    public function setDb($db){
        $this->db = $db;

        return $this;
    }
}


?>