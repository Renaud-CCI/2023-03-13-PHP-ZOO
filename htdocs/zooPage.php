<?php
require_once("./config/autoload.php");
$db = require_once("./config/db.php");

require_once("./config/header.php");

function prettyDump($data) {
    highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");
}


$zooManager = new ZooManager($db);

$zoo = $zooManager->findZoo($_SESSION['zooId']);



?>

<nav class="flex items-center justify-between flex-wrap bg-green-1 p-6">
  <div class="flex items-center flex-shrink-0 text-white-1 text-phosph">
    <img class="h-8 w-8 mr-2 rounded" src="./assets/images/logos/Zoo-logo.png" alt="Logo">
    <span class="font-semibold text-xl tracking-tight">PHP ZOO</span>
  </div>

  <div class="block lg:hidden">
    <button id="menu-toggle" class="flex items-center px-3 py-2 border rounded text-white-1 border-white-1 hover:text-white hover:border-white">
    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M3 6h14v2H3V6zm0 5h14v2H3v-2zm0 5h14v2H3v-2z" clip-rule="evenodd" />
    </svg>
    </button>
  </div>
  <div id="menu" class="w-full lg:w-auto lg:flex-grow lg:flex lg:items-center lg:justify-end lg:bg-blue-500 lg:p-2 lg:rounded lg:shadow-lg lg:block hidden">
    <div class="lg:flex lg:items-center">
      <a href="./index.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4">
        Accueil
      </a>
      <a href="./traitments/sessionDestroy.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4">
        Se déconnecter
      </a>
    </div>
  </div>
</nav>

  HELLO WORLD!

  <?= prettyDump($zoo);?>



<?php
require_once("./config/footer.php");
?>
