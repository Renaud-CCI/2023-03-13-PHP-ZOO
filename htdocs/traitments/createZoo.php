<?php 
session_start();
require_once("../classes/ZooManager.php");
require_once("../classes/EmployeeManager.php");
require_once("../classes/Zoo.php");
require_once("../classes/Employee.php");
$db = require_once("../config/db.php");

$zooManager = new ZooManager($db);
$employeeManager = new EmployeeManager($db);

$zooManager->setZooInDB($_GET['zooName'],$_SESSION['user_id']);

$newZoo = $zooManager->findZoo($db->lastInsertId());
$newEmployee = $employeeManager->findEmployee($_GET['employeeId']);

$_SESSION['zoo_id'] = $newZoo->getId();
$zooManager->setZooEmployee($newZoo->getId(), $newEmployee);

header('Location: ../zooPage.php');

?>
