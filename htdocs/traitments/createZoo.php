<?php 
session_start();
require_once("../classes/ZooManager.php");
require_once("../classes/Zoo.php");
$db = require_once("../config/db.php");
$zooManager = new ZooManager($db);

$zooManager->setZooInDB($_GET['zooName'],$_SESSION['user_id'], [$_GET['employeeId']]);

$newZoo = $zooManager->findZoo($db->lastInsertId());

$_SESSION['zoo_id'] = $newZoo->getId();

header('Location: ../zooPage.php');

?>
