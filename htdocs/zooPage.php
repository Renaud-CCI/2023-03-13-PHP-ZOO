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

// modifs sur $_SESSION
if (isset($_GET['zoo_id'])){
  $_SESSION['zoo_id'] = $_GET['zoo_id'];
}

unset($_SESSION['enclosure_id']);

//managers
$zoo = $zooManager->findZoo($_SESSION['zoo_id']);

// variables utiles
$isEmployee = "⚠️ Choisis un employé pour gérer tes enclos ! ⚠️";
$isEmployeeColor = "text-orange-700";

if (isset($_SESSION['employee_id'])){
  $choosenEmployee = $employeeManager->findZooEmployee($_SESSION['employee_id']);

  if ($choosenEmployee->getActions() == 0) {
    $isEmployee = "⚠️" . $choosenEmployee->getName() . " ne peut plus travailler aujourd'hui ! ⚠️";
  } else {
  $isEmployee = $choosenEmployee->getName() . " va bosser pour toi";
  $isEmployeeColor = "text-emerald-900";
  }
}

$allEnclosures = $enclosureManager->findAllEnclosuresOfZoo($_SESSION['zoo_id']);


// updates
if (isset($_POST['zooName'])){
  $zooManager->updateZooName($_SESSION['zoo_id'], $_POST['zooName']);
  $zoo = $zooManager->findZoo($_SESSION['zoo_id']);
}

?>

<nav class="flex items-center justify-between flex-wrap bg-emerald-900 px-6 py-3 w-auto">
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
  <div id="menu" class="w-full lg:w-auto lg:flex-grow lg:flex lg:items-center lg:justify-end lg:bg-emerald-900 lg:p-2 lg:rounded  lg:block hidden">
    <div class="lg:flex lg:items-center">
      <a href="./traitments/cookieSuppr.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end">
        Accueil
      </a>
      <a href="./traitments/sessionDestroy.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end">
        Se déconnecter
      </a>
    </div>
  </div>
</nav>

<section id="allEnclosures">

  <div id="zooPresentation" class="flex flex-col items-center justify-center mt-6 mb-5 text-emerald-900 text-phosph">
    <?php if (isset ($_POST['modify']) && $_POST['modify']=='zooName') : ?>
      <form action="./zooPage.php" method="post">
        <input class="zooNameInput bg-transparent shadow appearance-none rounded w-48 mt-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="zooName" name="zooName" type="text" placeholder="nom du zoo" required>
        <button class="bg-emerald-900 text-white-1 font-bold py-2 px-4 rounded w-20">
          Valider
        </button>
      </form>
    <?php else : ?>
      <div class="flex flex-row">
        <h1 class="text-5xl lg:text-6xl font-bold text-center mb-4 overflow-hidden max-w-xs lg:max-w-xl"><?= $zoo->getName() ?></h1>
        <form action="./zooPage.php" method="post" class="ml-2 mt-8 h-4 justify-center">
          <input type="hidden" name="modify" value="zooName">
            <button type="submit" class="text-xs px-0.5 m-0 border border_green-1 rounded" title="modifier">
              <img class="w-3" src="./assets/images/icones/icons8-modifier-24.png" alt="modifier">
            </button>
        </form>
      </div>
    <?php endif ?>
    
    <p class="text-2xl lg:text-3xl font-bold text-center mb-2"> Jour 
      <?= $zoo->getDay() ?>
    </p>
  </div>

  <div id="subtitle" class="row flex">

    <div id="employeeChoice" class="w-1/2">
      <div id="employeesDiv" class="">

        <h2 class="text-xl text-emerald-900 font-bold text-center mb-4">Choisis l'employé.e actif.ve</h2>

        <form action="" method="get">
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <?php foreach ($zooManager->findZooEmployees($zoo->getId()) as $employee) : ?>
              <div class="inline-block w-20 mx-auto <?= isset($_SESSION['employee_id'])? ($_SESSION['employee_id'] == $employee->getId() ? ($employee->getActions()>0 ? 'border border-2 border_green-1 rounded-full' : 'border border-2 border-orange-700 rounded-full') : '' ): '' ?>">
                <input class="employee-input hidden" id="<?=$employee->getId()?>" type="radio" name="employeeId" value="<?=$employee->getId()?>" required>
                <label class="flex flex-col p-2 cursor-pointer bg-white shadow-lg rounded-full text-center text-lan" for="<?=$employee->getId()?>" onclick="window.location.href = './traitments/chooseEmployee.php?employee_id=<?=$employee->getId()?>'">
                  <img src="https://api.dicebear.com/5.x/personas/svg?seed=<?= $employee->getName() ?>" class="mx-auto w-16">
                  <?=$employee->getName()?>
                  <p class="text-xs"><?=$employee->getActions()?> 🛠️</p>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </form>

      </div>
    </div>

    <div id="zooInfos" class="w-1/2 text-center">

      <div>
        <ul class="text-base mb-2 items-center">
          <li class="text-xl text-lan text-emerald-900 text-center font-semibold">
          💰 : <?= $zoo->getBudget() ?>
          </li>
          <li class="text-lan text-emerald-900 text-center font-semibold">
            <?= $zooManager->findCountEnclosures($zoo->getId()) ?> enclos
          </li>
          <li class="text-lan text-emerald-900 text-center font-semibold">
            <?= $zooManager->findCountAnimals($zoo->getId()) ?>
            <?= $zooManager->findCountAnimals($zoo->getId())>1? ' animaux' : ' animal' ?> 
          </li>
          <li class="text-lan text-emerald-900 text-center font-semibold">
            <?= $zooManager->findCountHungryAnimals($zoo->getId()) ?>
            <?= $zooManager->findCountHungryAnimals($zoo->getId())>1? ' animaux affamés' : ' animal affamé' ?> 
          </li>
          <li class="text-lan text-emerald-900 text-center font-semibold">
            <?= $zooManager->findCountSickAnimals($zoo->getId()) ?>
            <?= $zooManager->findCountSickAnimals($zoo->getId())>1? ' animaux malades' : ' animal malade' ?>
          </li>
        </ul>
        
      </div>

      <div class="flex flex-col lg:flex-row justify-center items-center">
        <button class="text-xs bg-emerald-800 bg-emerald-900 text-white-1 font-bold py-1 px-2 rounded w-40 h-9 mx-2 mb-2" onclick="window.location.href = './addEmployee.php?zoo_id=<?=$zoo->getId()?>';">
          Ajouter un.e employé.e
        </button>

        <button class="text-xs bg-emerald-800 bg-emerald-900 text-white-1 font-bold py-1 px-2 rounded w-40 h-9 mx-2 mb-2" onclick="window.location.href = './addEnclosure.php?zoo_id=<?=$zoo->getId()?>';">
          Ajouter un enclos
        </button>
      </div>

    </div>

  </div>

  <p class="text-3xl font-bold text-center mt-10 mb-2 text-emerald-900 text-phosph">Gères tes enclos !</p>
  <p class="text-xl font-bold text-center mb-4 <?= $isEmployeeColor ?> text-phosph"><?= $isEmployee ?></p>

  <div id="enclosuresDiv" class="w-full mx-auto max-w-screen-xl">
    <div class="grid grid-cols-3 lg:grid-cols-6 gap-1 text-center justify-center">
      <?php foreach ($allEnclosures as $enclosure) : ?>
        
        <a class="inline-block" href="<?= (isset($_SESSION['employee_id']) ? ($choosenEmployee->getActions()>0 ? './enclosurePage.php?enclosure_id='.$enclosure->getId().'&employee_id='.$_SESSION['employee_id'] : '') : '')?>">

          <div class="mx-2 my-2 p-1 cursor-pointer bg-white rounded-lg shadow-lg">        
            <span class="textlg lg:text-xl overflow-hidden text-center font-semibold uppercase text-phosph text-emerald-900"><?=$enclosure->getName()?></span>
            <img src="<?= $enclosure->getAvatar() ?>" class="mx-auto w-20">
            <ul class="text-xs lg:text-sm mt-2 items-center">
              <li class="text-lan text-emerald-900 text-center font-semibold">
                <div class="text-lan text-emerald-900 font-semibold flex flex-row items-center justify-center">
                  <span class="mr-2">Propreté :</span>
                  <div class="w-8 mt-0 bg-stone-300 rounded-full h-1.5" title="<?= $enclosure->getCleanliness() ?>">
                    <div class="<?= $enclosure->getCleanliness()>6.8? 'bg-emerald-900' : ($enclosure->getCleanliness()>4.5? 'bg-amber-500': 'bg-orange-700')?> h-1.5 rounded-full" style="width: <?= $enclosure->getCleanliness() * 10 ?>%">
                    </div>
                  </div>
                </div>
              </li>
              <li class="text-lan text-emerald-900 text-center font-semibold">
                <?= $enclosureManager->findCountAnimals($enclosure->getId()) ?>/<?= $enclosure->getAccount() ?>
                <?= $enclosureManager->findCountAnimals($enclosure->getId())>1? ' animaux' : ' animal' ?>
              </li>
              <li class="text-lan text-emerald-900 text-center font-semibold">
                <?= $enclosureManager->findCountHungryAnimals($enclosure->getId()) ?>
                <?= $enclosureManager->findCountHungryAnimals($enclosure->getId())>1? ' affamés' : ' affamé' ?>
              </li>
              <li class="text-lan text-emerald-900 text-center font-semibold">
                <?= $enclosureManager->findCountSickAnimals($enclosure->getId()) ?>
                <?= $enclosureManager->findCountSickAnimals($enclosure->getId())>1? ' malades' : ' malade' ?>
              </li>
              <li class="text-lan text-emerald-900 text-center font-semibold">
                <?= $enclosureManager->findCountSleppyAnimals($enclosure->getId()) ?>
                <?= $enclosureManager->findCountSleppyAnimals($enclosure->getId())>1? ' endormis' : ' endormi' ?>
              </li>
            </ul>
          </div>
        </a>    
      <?php endforeach; ?>
    </div>
  </div>


  <div class="flex flex-col lg:flex-row justify-center items-center m-10">
    <button class="text-xl bg-orange-700 text-white-1 font-bold py-1 px-2 rounded w-80 h-9 mx-2 mb-2" onclick="window.location.href = './zooDailyPage.php';">
      Passer au jour suivant
    </button>
  </div>


</section>


<?php
require_once("./config/footer.php");
?>
