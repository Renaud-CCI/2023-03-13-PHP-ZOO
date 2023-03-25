<?php 

setcookie("employee_id", intval($_GET['employee_id']), 0, '/');

header('Location:../zooPage.php');
exit;


?>