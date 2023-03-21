<?php
require_once("./config/autoload.php");
$db = require_once("./config/db.php");

require_once("./config/header.php");

function prettyDump($data) {
    highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");
}

// connections à la db
$zooManager = new ZooManager($db);
$employeeManager = new EmployeeManager($db);
$enclosureManager = new EnclosureManager($db);

$isEmployee = "⚠️ Choisis un employé pour gérer tes enclos ! ⚠️";
$isEmployeeColor = "text-red-500";

if (isset($_SESSION['employee_id'])){
  $choosenEmployee = $employeeManager->findEmployee(intval($_SESSION['employee_id']));

  $isEmployee = $choosenEmployee->getName() . " va bosser pour toi";
  $isEmployeeColor = "text-green-1";
}

if (isset($_GET['zoo_id'])){
  $_SESSION['zoo_id'] = $_GET['zoo_id'];
}


$zoo = $zooManager->findZoo($_SESSION['zoo_id']);

$allEnclosures = $enclosureManager->findAllEnclosuresOfZoo($_SESSION['zoo_id']);



?>

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
  <div id="menu" class="w-full lg:w-auto lg:flex-grow lg:flex lg:items-center lg:justify-end lg:bg-green-1 lg:p-2 lg:rounded  lg:block hidden">
    <div class="lg:flex lg:items-center">
      <a href="./index.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end">
        Accueil
      </a>
      <a href="./traitments/sessionDestroy.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end">
        Se déconnecter
      </a>
    </div>
  </div>
</nav>

<section id="allEnclosures">

    <div id="zooPresentation" class="flex flex-col items-center justify-center mt-4 mb-5 text-green-1 text-phosph">
      <h1 class="text-5xl font-bold text-center mb-4"><?= $zoo->getName() ?></h1>
      <p class="text-2xl font-bold text-center mb-2"> Jour 
        <?= $zoo->getDay() ?>
      </p>
    </div>

    <div class="row flex">
      <div class="w-1/3">
        <div id="employeesDiv" class="">
          <h2 class="text-xl text-green-1 font-bold text-center mb-4">Choisis l'employé actif</h2>
          <form action="" method="get">
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <?php foreach ($zoo->getEmployees_id() as $employeeId) : ?>
            <?php $employee = $employeeManager->findEmployee(intval($employeeId)); ?>
            <div class="inline-block w-20 mx-auto">
              
              <input class="employee-input hidden" id="<?=$employee->getId()?>" type="radio" name="employeeId" value="<?=$employee->getId()?>" required>
              <label class="flex flex-col p-2 cursor-pointer bg-white shadow-lg rounded-full text-center text-lan" for="<?=$employee->getId()?>" onclick="window.location.href = './traitments/chooseEmployee.php?employee_id=<?=$employee->getId()?>'">
                <img src="https://api.dicebear.com/5.x/personas/svg?seed=<?= $employee->getName() ?>" class="mx-auto w-10">
                <?=$employee->getName()?>
              </label>
             
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </form>
      </div>

      <div id="zooUpdate" class="w-2/3 text-center">
        <button class="bg-emerald-800 bg-green-1 text-white-1 font-bold py-2 px-4 rounded w-50 mb-2" onclick="window.location.href = './addEmployee.php?zoo_id=<?=$zoo->getId()?>';">Ajouter un employé</button>
        
        <button class="bg-emerald-800 bg-green-1 text-white-1 font-bold py-2 px-4 rounded w-50 mb-2" onclick="window.location.href = './addEnclosure.php?zoo_id=<?=$zoo->getId()?>';">Ajouter un enclos</button>

        <div>Aliquam quibusdam, eius illum non, cum architecto iure ullam natus dolorum, rerum laborum cupiditate culpa rem distinctio vero assumenda nihil quas veniam? Rem, natus vero illo repellat, quisquam ut harum nemo quae minima incidunt mollitia quia aperiam aut ratione. Laudantium, maxime expedita.</div>
      </div>
    </div>

    <p class="text-3xl font-bold text-center mt-10 mb-2 text-green-1 text-phosph">Gères tes enclos !</p>
    <p class="text-xl font-bold text-center mb-4 <?= $isEmployeeColor ?> text-phosph"><?= $isEmployee ?></p>
      <div class="w-full mx-auto max-w-screen-xl">
        <div class="grid grid-cols-3 lg:grid-cols-5 gap-4 text-center justify-center">
          <?php foreach ($allEnclosures as $enclosure) : ?>
            <a class="inline-block" href="<?= isset($_SESSION['employee_id'])? './enclosurePage.php?enclosure_id='.$enclosure->getId() : ''?>">
              <div class="mx-2 p-2 cursor-pointer bg-white rounded-lg shadow-lg">
            
                <span class="text-xl text-center font-semibold uppercase text-phosph text-green-1"><?=$enclosure->getName()?></span>
                <img src="<?= $enclosure->getAvatar() ?>" class="mx-auto w-20">
                <ul class="text-sm mt-2 items-center">
                  <li class="text-lan text-green-1 text-center font-semibold">Lorem, ipsum.</li>
                  <li class="flex justify-center text-lan text-green-1 text-center font-semibold">Sexe : </li>
                </ul>
              
              </div>
            </a>    
          <?php endforeach; ?>
        </div>
      </div>



</section>

  <!-- <?= prettyDump($zoo);?>
  <?=prettyDump($_SESSION);?> -->



<?php
require_once("./config/footer.php");
?>
