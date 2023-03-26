<?php
session_start();

unset($_SESSION["employee_id"]);

header('Location:../index.php');


?>