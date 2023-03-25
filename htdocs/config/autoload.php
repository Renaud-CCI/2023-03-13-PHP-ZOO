<?php
// Strict
declare(strict_types=1);
// Enable errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set("html_errors", "1"); 
ini_set("error_prepend_string", "<pre style='color: #333; font-face:monospace; white-space: pre-wrap;font-size: 17px;color:#880808'>"); 
ini_set("error_append_string ", "</pre>"); 
error_reporting(E_ALL);

function chargerClasse($classname)
{
    require './classes/' . $classname . '.php';
}

spl_autoload_register('chargerClasse');

session_start();

?>