<?php 

setcookie("employee_id", intval($_GET['employee_id']), time()+(3600*24), '/');


header('Location:../zooPage.php');


?>