<?php 
require_once("./config/autoload.php");
$db = require_once("./config/db.php");

function prettyDump($data) {
  highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");
}

$enclosureManager = new EnclosureManager($db);

if (isset($_GET['enclosureType'])){
  $enclosureData = [
    'zoo_id' => $_GET['zoo_id'],
    'enclosure_type' => $_GET['enclosureType'],
    'name' => $_GET['enclosureName']
  ];
  
  $enclosure = new Enclosure($enclosureData);

  $enclosureManager->setEnclosureInDb($enclosure);

  header('Location: ./zooPage.php');
}









?>
<?php require_once("./config/header.php"); ?>


  <nav class="flex items-center justify-between flex-wrap bg-green-1 p-6">

    <div class="flex items-center flex-shrink-0 text-white-1 text-phosph">
      <img class="w-10 mr-2 rounded" src="./assets/images/logos/Zoo-logo.png" alt="Logo">
      <span class="font-semibold text-3xl tracking-tight">PHP ZOO</span>
    </div>

    <div class="block lg:hidden">
      <button id="menu-toggle" class="flex items-center px-3 py-2 border rounded text-white-1 border-white-1 hover:text-white hover:border-white">
      <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3 6h14v2H3V6zm0 5h14v2H3v-2zm0 5h14v2H3v-2z" clip-rule="evenodd" />
      </svg>
      </button>
    </div>
    <div id="menu" class="w-full lg:w-auto lg:flex-grow lg:flex lg:items-center lg:justify-end lg:bg-green-1 lg:p-2 lg:rounded lg:block hidden">
      <div class="lg:flex lg:items-center">
        <a href="./index.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end" style="display:<?= $createZooDivDisplay ?>">
          Accueil
        </a>
        <a href="./zooPage.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end">
          Retour Zoo
        </a>
        <a href="./traitments/sessionDestroy.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end">
          Se déconnecter
        </a>
      </div>
    </div>
  </nav>


  

  <div id="allFreeEmployees" class="grid">

    <form action="./addEnclosure.php" method="get" class="mx-auto w-full max-w-screen-sm">
      

      <p class="text-3xl font-bold text-center m-4 text-green-1 text-phosph">Choisis le type d'enclos</p>

      <div class="grid grid-cols-3 gap-4 m-2">
        <div class="">
          <input class="employee-input hidden" id="Park" type="radio" name="enclosureType" value="Park" required>
          <label class="flex flex-col p-2 cursor-pointer bg-white rounded-lg shadow-lg" for="Park">
            <span class="text-xl text-center font-semibold uppercase text-phosph text-green-1">Enclos</span>
            <img src="https://img.icons8.com/officel/80/null/defensive-wood-wall.png" class="mx-auto w-20">
            
          </label>
        </div>
 
        <div class="">
          <input class="employee-input hidden" id="Aviary" type="radio" name="enclosureType" value="Aviary" required>
          <label class="flex flex-col p-2 cursor-pointer bg-white rounded-lg shadow-lg" for="Aviary">
            <span class="text-xl text-center font-semibold uppercase text-phosph text-green-1">Volière</span>
            <img src="https://img.icons8.com/color-glass/48/000000/cage-of-a-bird.png" class="mx-auto w-20">
            
          </label>
        </div>

        <div class="">
          <input class="employee-input hidden" id="Aquarium" type="radio" name="enclosureType" value="Aquarium" required>
          <label class="flex flex-col p-2 cursor-pointer bg-white rounded-lg shadow-lg" for="Aquarium">
            <span class="text-xl text-center font-semibold uppercase text-phosph text-green-1">Aquarium</span>
            <img src="https://img.icons8.com/dusk/64/null/aquarium.png" class="mx-auto w-20">
            
          </label>
        </div>
      </div>

      <div class="m-6 flex flex-col justify-center items-center">
        <label class="block text-3xl font-bold text-center m-2 text-green-1 text-phosph" for="enclosureName">
          Nom de ton enclos
        </label>
        <input class="zooNameInput bg-transparent shadow appearance-none rounded w-96 m-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="enclosureName" name="enclosureName" type="text" placeholder="nom de l'enclos" required>
      </div>
      
      <input type="hidden" name="zoo_id" value="<?=$_GET['zoo_id']?>">

      <div class="flex flex-col items-center mt-6">
        <button class="bg-green-1 text-white-1 font-bold py-2 px-4 rounded w-64">
          Valider
        </button>
      </div>
    </form>

    <div class="flex flex-col items-center mt-6">
        <button class="bg-red-700 text-white-1 font-bold py-2 px-4 rounded w-64" onclick="window.location.href = './zooPage.php';">
          Annuler
        </button>
      </div>




  </div>



<?php
require_once('./config/footer.php');
?>