<?php
require_once("./config/autoload.php");
$db = require_once("./config/db.php");

function prettyDump($data) {
  highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");
}

// modifications de $_SESSION

if (isset($_GET['enclosure_id'])){
  $_SESSION['enclosure_id'] = intval($_GET['enclosure_id']);
}



// Instanciations d'objets
$zooManager = new ZooManager($db);
$enclosureManager = new EnclosureManager($db);
$animalManager = new AnimalManager($db);
$employeeManager = new EmployeeManager($db);


$zoo = $zooManager->findZoo($_SESSION['zoo_id']);
$enclosure = $enclosureManager->findEnclosure(intval($_SESSION['enclosure_id']));
$allAnimalsAsObject = $animalManager->findAllAnimalsOfEnclosure(intval($_SESSION['enclosure_id']));
$employee = $employeeManager->findZooEmployee($_SESSION['employee_id']);


// variables
$nutritionCost = 0 ;
$healthCost = 0;
$cleanCost = (10 - $enclosure->getCleanliness()) * ($enclosure->getAnimalPrice()/20);
foreach ($allAnimalsAsObject as $animal){
  $animalRate= ($animal->getPrice()/80);
  $nutritionCost += (10 - $animal->getIsHungry())*$animalRate;
  $healthCost += (10 - $animal->getIsSick())*$animalRate;
}
$nutritionCost = round($nutritionCost);
$healthCost = round($healthCost);
$cleanCost = round ($cleanCost);


// updates
if (isset($_POST['enclosureName'])){
  $enclosureManager->updateEnclosureName($_SESSION['enclosure_id'], $_POST['enclosureName']);
  $enclosure = $enclosureManager->findEnclosure(intval($_SESSION['enclosure_id']));
}

if (isset ($_POST['action'])){ 
  switch ($_POST['action']){

    case 'nutrition':
      $employeeManager->updateActions($employee->getId(), $employee->getActions()-1);
      $zooManager->updateBudget($_SESSION['zoo_id'], $zoo->getBudget() - $nutritionCost);
      foreach ($allAnimalsAsObject as $animal){
        $animalManager->updateIsHungry($animal->getId(), 10);
      }
      break;

    case 'healing' :
      $employeeManager->updateActions($employee->getId(), $employee->getActions()-1);
      $zooManager->updateBudget($_SESSION['zoo_id'], $zoo->getBudget() - $healthCost);
      foreach ($allAnimalsAsObject as $animal){
        $animalManager->updateIsSick($animal->getId(), 10);
      }
      break;

    case 'cleaning' :
      $employeeManager->updateActions($employee->getId(), $employee->getActions()-1);
      $zooManager->updateBudget($_SESSION['zoo_id'], $zoo->getBudget() - $cleanCost);
      $enclosureManager->updateCleanliness($enclosure->getId(), 10);
      break;

  }

  header('Location:./enclosurePage.php');
}



require_once("./config/header.php");
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
  <div id="menu" class="w-full lg:w-auto lg:flex-grow lg:flex lg:items-center lg:justify-end lg:bg-emerald-900 lg:p-2 lg:rounded lg:block hidden">
    <div class="lg:flex lg:items-center">
      <a href="./traitments/cookieSuppr.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end" style="display:<?= $createZooDivDisplay ?>">
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


<section id="enclosureDetail">

  <div id="enclosureHeader" class="flex flex-col items-center justify-center mt-4 mb-3 text-emerald-900 text-phosph">

    <div class="flex flex-row items-center justify-center mb-1 w-full">
      <p class="text-xl text-lan text-emerald-900 font-semibold flex flex-row items-center justify-center w-1/4">💰 : <?= $zoo->getBudget() ?></p>
      <h1 class="text-6xl font-bold text-center w-1/2"><a href="./zooPage.php"><?= $zoo->getName() ?></a></h1>

      <div class="text-lg text-lan text-emerald-900 font-semibold items-center justify-center w-1/4 m-0">
        <div class="flex flex-row items-center justify-center">
          <img src="https://api.dicebear.com/5.x/personas/svg?seed=<?= $employee->getName() ?>" class="w-8">
          <span class="m-1 mt-3 text-2xl"><?=$employee->getName()?></span>
        </div>
        <p class="text-base items-center justify-center flex flex-row <?= $employee->getActions()>0? '' : 'text-orange-700'?>"><?=$employee->getActions()?> 🛠️</p>
      </div>

    </div>

    <?php if (isset ($_POST['modify']) && $_POST['modify']=='enclosureName') : ?>
      <form action="./enclosurePage.php" method="post">
        <input class="zooNameInput bg-transparent shadow appearance-none rounded w-48 m-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="enclosureName" name="enclosureName" type="text" placeholder="nom de l'enclos" required>
        <button class="bg-emerald-900 text-white-1 font-bold py-2 px-4 rounded w-20">
          Valider
        </button>
      </form>
    <?php else : ?>
      <div class="flex flex-row">
        <h2 class="text-5xl mb-2">
          <?=$enclosure->getName()?>
        </h2>
        <form action="./enclosurePage.php" method="post" class="ml-2 mt-4 h-4 justify-center">
          <input type="hidden" name="modify" value="enclosureName">
            <button type="submit" class="text-xs px-0.5 mt-3 border border_green-1 rounded" title="modifier">
              <img class="w-3" src="./assets/images/icones/icons8-modifier-24.png" alt="modifier">
            </button>
        </form>
      </div>
    <?php endif ?>

    <img class="w-32 mb-2" src="<?=$enclosure->getAvatar()?>" alt="">
  </div>

  
  
  <div class="flex flex-col grid <?= method_exists($enclosure,'getSalinity')? 'grid-cols-2 md:grid-cols-4 lg:w-2/3' : 'grid-cols-3 lg:w-1/2' ?> gap-2 mx-auto mb-2 text-center justify-center">

    <div class="text-lg text-lan text-emerald-900 font-semibold flex flex-row items-center justify-center">
      <span class="mr-2">Propreté :</span>
      <div class="w-10 mt-1 bg-stone-300 rounded-full h-2.5" title="<?= $enclosure->getCleanliness() ?>">
        <div class="<?= $enclosure->getCleanliness()>6.8? 'bg-emerald-900' : ($enclosure->getCleanliness()>4.5? 'bg-amber-500': 'bg-orange-700')?> h-2.5 rounded-full" style="width: <?= $enclosure->getCleanliness() * 10 ?>%">
        </div>
      </div>
    </div>
 
    <div class="text-lg text-lan text-emerald-900 font-semibold flex flex-row items-center justify-center" style="display:<?= method_exists($enclosure,'getSalinity')? 'flex' : 'none' ?>">
      <span class="mr-2">Salinité :</span>
      <div class="w-10 mt-1 bg-stone-300 rounded-full h-2.5" title="<?php echo method_exists($enclosure,'getSalinity') ? $enclosure->getSalinity() : '' ?>">
        <div class="<?= method_exists($enclosure,'getSalinity')? ($enclosure->getSalinity()>6.8? 'bg-emerald-900' : ($enclosure->getSalinity()>4.5? 'bg-amber-500': 'bg-orange-700')) : ''?> h-2.5 rounded-full" style="width: <?= method_exists($enclosure,'getSalinity')? ($enclosure->getSalinity() * 10) : ''?>%">
        </div>
      </div>
    </div>
   
    <div class="text-lg text-lan text-emerald-900 font-semibold flex flex-row items-center justify-center">
      <span class="">
        <?= $enclosureManager->findCountHungryAnimals($enclosure->getId()) ?>
        <?= $enclosureManager->findCountHungryAnimals($enclosure->getId())>1? ' animaux affamés' : ' animal affamé' ?>
      </span>        
    </div>
    
    <div class="text-lg text-lan text-emerald-900 font-semibold flex flex-row items-center justify-center">
      <span class="">
        <?= $enclosureManager->findCountSickAnimals($enclosure->getId()) ?>
        <?= $enclosureManager->findCountSickAnimals($enclosure->getId())>1? ' animaux malades' : ' animal malade' ?>
      </span>        
    </div>

  </div>

  

  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mx-auto mt-6 justify-items-center">
    <?php if ($enclosureManager->findCountAnimals($enclosure->getId())<6) : ?>
      <button class="bg-emerald-900 text-white-1 font-bold py-2 px-4 rounded w-48 mb-2" onclick="window.location.href = './addAnimal.php?enclosure_id=<?=$_SESSION['enclosure_id']?>'">
        Ajouter un animal
      </button>
    <?php else : ?>
      <button class="bg-orange-700 text-white-1 font-bold py-2 px-4 rounded w-48 mb-2">
      ⚠️ Nombre max d'animaux atteint ⚠️
      </button>
    <?php endif ?>

    <form action="./enclosurePage.php" method="<?= $employee->getActions()>0? 'post' : ''?>">
      <input type="hidden" name="action" value="nutrition">
      <?php if ($employee->getActions()>0) : ?>
        <button type="submit"class="bg-emerald-900 text-white-1 font-bold py-2 px-4 rounded w-48 mb-2">
          Nourrir l'enclos<br>
          <span class="mx-1 text-sm">-<?= $nutritionCost ?> 💰</span>
          <span class="mx-1 text-sm">-1 🛠️</span>
        </button>
      <?php else : ?>
        <button type="submit"class="bg-orange-700 text-white-1 font-bold py-2 px-4 rounded w-48 mb-2">
        Nourrir l'enclos<br>
          <span class="mx-1 text-sm">⚠️ <?= $employee->getName() ?> a 0 action ⚠️</span>
        </button>
      <?php endif; ?>
    </form>

    <form action="./enclosurePage.php" method="<?= $employee->getActions()>0? 'post' : ''?>">
      <input type="hidden" name="action" value="healing">
      <?php if ($employee->getActions()>0) : ?>
        <button type="submit"class="bg-emerald-900 text-white-1 font-bold py-2 px-4 rounded w-48 mb-2">
          Soigner l'enclos<br>
          <span class="mx-1 text-sm">-<?= $healthCost ?> 💰</span>
          <span class="mx-1 text-sm">-1 🛠️</span>
        </button>
      <?php else : ?>
        <button type="submit"class="bg-orange-700 text-white-1 font-bold py-2 px-4 rounded w-48 mb-2">
        Nourrir l'enclos<br>
          <span class="mx-1 text-sm">⚠️ <?= $employee->getName() ?> a 0 action ⚠️</span>
        </button>
      <?php endif; ?>
    </form>

    <form action="./enclosurePage.php" method="<?= $employee->getActions()>0? 'post' : ''?>">
      <input type="hidden" name="action" value="cleaning">
      <?php if ($employee->getActions()>0) : ?>
        <button type="submit" class="bg-emerald-900 text-white-1 font-bold py-2 px-4 rounded w-48 mb-2">
          Nettoyer l'enclos<br>
          <span class="mx-1 text-sm">-<?= $cleanCost ?> 💰</span>
          <span class="mx-1 text-sm">-1 🛠️</span>
        </button>
      <?php else : ?>
        <button type="submit"class="bg-orange-700 text-white-1 font-bold py-2 px-4 rounded w-48 mb-2">
          Nourrir l'enclos<br>
          <span class="mx-1 text-sm">⚠️ <?= $employee->getName() ?> a 0 action ⚠️</span>
        </button>
      <?php endif; ?> 
    </form>
  </div>



</section>

<section id="enclosureAnimals">
  <p class="text-3xl font-bold text-center mt-10 m-2 text-emerald-900 text-phosph">Animaux dans l'enclos</p>
    <div class="grid grid-cols-3 lg:grid-cols-6 gap-3 px-2">
      <?php foreach ($allAnimalsAsObject as $animal) : ?>

      <div class="my-4">
        <input class="employee-input hidden" id="<?=$animal->getId()?>" type="radio" name="animalId" value="<?=$animal->getId()?>" required>
        <label class="flex flex-col py-2 cursor-pointer bg-white rounded-lg shadow-lg" for="<?=$animal->getId()?>">
          <span class="text-xl text-center font-semibold uppercase text-phosph text-emerald-900 overflow-hidden"><?=$animal->getName()?></span>
          <img src="./assets/images/logos/<?=$animal->getSpecies() ?>.png" alt="avatar" class="mx-auto w-20">
          <ul class="text-sm mt-2 items-center">
            <li class="text-lan text-emerald-900 text-center font-semibold">Age : <?=$zoo->getDay() - $animal->getBirthday() + 1?></li>
            <li class="flex justify-center text-lan text-emerald-900 text-center font-semibold">
              Sexe : 
              <img src="<?=$animal->getGenderSymbol()?>" alt="<?=$animal->getSex()?>" title="<?=$animal->getSex()?>" class="w-4 h-4 inline-block ml-1">
            </li>
            <li class="flex justify-center text-lan text-emerald-900 text-center font-semibold">
              <p class="flex items-center">
                <span class="mr-2">Satiété :</span>
                <div class="w-10 mt-1 bg-stone-300 rounded-full h-2.5" title="<?= $animal->getIsHungry() ?>">
                  <div class="<?= $animal->getIsHungry()>6.8? 'bg-emerald-900' : ($animal->getIsHungry()>4.5? 'bg-amber-500': 'bg-orange-700')?> h-2.5 rounded-full" style="width: <?= $animal->getIsHungry() * 10 ?>%">
                  </div>
                </div>
              </p>
            </li>
            <li class="flex justify-center text-lan text-emerald-900 text-center font-semibold">
              <p class="flex items-center">
                <span class="mr-2">Santé :</span>
                <div class="w-10 mt-1 bg-stone-300 rounded-full h-2.5" title="<?= $animal->getIsSick() ?>">
                  <div class="<?= $animal->getIsSick()>6.8? 'bg-emerald-900' : ($animal->getIsSick()>4.5? 'bg-amber-500': 'bg-orange-700')?> h-2.5 rounded-full" style="width: <?= $animal->getIsSick() * 10 ?>%">
                  </div>
                </div>
              </p>
            </li>

          </ul>
        </label>
      </div>
 
  
        <?php endforeach; ?>
      </div>

</section>

<div class="flex flex-col lg:flex-row justify-center items-center m-10">
    <button class="text-xl bg-orange-700 text-white-1 font-bold py-1 px-2 rounded w-80 h-9 mx-2 mb-2" onclick="window.location.href = './zooPage.php';">
      Retour Zoo
    </button>
  </div>

<?php
require_once("./config/footer.php");
?>
