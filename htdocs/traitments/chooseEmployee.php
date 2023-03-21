<?php 
session_start();

$_SESSION['employee_id'] = $_GET['employee_id'];

header('Location:../zooPage.php');


?>