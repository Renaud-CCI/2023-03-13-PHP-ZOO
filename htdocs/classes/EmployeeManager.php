<?php


class EmployeeManager {
    private $db; 

    public function __construct(PDO $db){
        $this->setDb($db);
    }

    public function findEmployee(int $id){
        $query = $this->db->prepare('SELECT *  FROM employees
                                    WHERE id = :id');
        $query->execute(['id' => $id,]);

        $employeeData = $query->fetch(PDO::FETCH_ASSOC);

        // var_dump($employeeData);
        // die;
          
        return new Employee($employeeData);
                   
    }

    public function findZooEmployee(int $zooEmployeeId){
        $query = $this->db->prepare('   SELECT ze.id, ze.actions, ze.default_actions, emp.name, emp.birthdate, emp.sex, emp.salary 
                                        FROM zoosEmployees AS ze
                                        JOIN employees AS emp
                                        ON ze.employee_id = emp.id
                                        WHERE ze.id = :zooEmployeeId');
        $query->execute(['zooEmployeeId' => $zooEmployeeId,]);

        $employeeData = $query->fetch(PDO::FETCH_ASSOC);

        // var_dump($zooEmployeeId);
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
        $query = $this->db->prepare('   UPDATE zoosEmployees 
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