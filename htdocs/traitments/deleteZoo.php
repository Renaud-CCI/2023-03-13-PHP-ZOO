<?php 
session_start();
require_once("../classes/ZooManager.php");
require_once("../classes/Zoo.php");
$db = require_once("../config/db.php");
$zooManager = new ZooManager($db);

$zooManager->deleteZooInDB($_GET['zoo_id']);

header('Location: ../index.php');

?>