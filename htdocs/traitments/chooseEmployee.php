<?php 
session_start();

$_SESSION['employee_id'] = intval($_GET['employee_id']);

header('Location:../zooPage.php');
exit;


?>