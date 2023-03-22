<?php 
require_once("./config/autoload.php");
$db = require_once("./config/db.php");

function prettyDump($data) {
  highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");
}

$zooManager = new ZooManager($db);
$employeeManager = new EmployeeManager($db);

$zoo = $zooManager->findZoo($_SESSION['zoo_id']);


//Gestion de l'ajout d'un employé
if (isset($_GET['employeeId'])){
    $zooEmployees = $zoo->getEmployees_id();
    $employeeIdToAdd = $_GET['employeeId'];
    array_push($zooEmployees, $employeeIdToAdd);
    $zooManager->updateZooEmployeesInDB($_SESSION['zoo_id'], $zooEmployees);
    header('Location: ./zooPage.php');
}



//Création de la liste d'employés disponibles
$allEmployeesInDb = $employeeManager->findAllEmployees();
$allFreeEmployeesId = [];
$allFreeEmployeesAsObject = [];

foreach ($allEmployeesInDb as $employee){
  if (in_array($employee->getId(), $zoo->getEmployees_id())){
    continue;
  }
  array_push($allFreeEmployeesId, $employee->getId());
}

foreach (array_unique($allFreeEmployeesId) as $employeeId){
    array_push($allFreeEmployeesAsObject, $employeeManager->findEmployee($employeeId));
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
        <a href="./traitments/sessionDestroy.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end">
          Se déconnecter
        </a>
      </div>
    </div>
  </nav>


  

  <div id="allFreeEmployees">

    <form action="./addEmployee.php" method="get" class="mx-auto w-full max-w-screen-sm">
      

      <p class="text-3xl font-bold text-center m-2 text-green-1 text-phosph">Choisis un employé</p>
      <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
        <?php foreach ($allFreeEmployeesAsObject as $employee) : ?>
                

        <div class="">
          <input class="employee-input hidden" id="<?=$employee->getId()?>" type="radio" name="employeeId" value="<?=$employee->getId()?>" required>
          <label class="flex flex-col p-2 cursor-pointer bg-white rounded-lg shadow-lg" for="<?=$employee->getId()?>">
            <span class="text-xl text-center font-semibold uppercase text-phosph text-green-1"><?=$employee->getName()?></span>
            <img src="https://api.dicebear.com/5.x/personas/svg?seed=<?= $employee->getName() ?>" class="mx-auto w-20">
            <ul class="text-sm mt-2 items-center">
              <li class="text-lan text-green-1 text-center font-semibold"><?=$employee->getAge()?> ans</li>
              <li class="flex justify-center text-lan text-green-1 text-center font-semibold">Sexe : <img src="<?=$employee->getGenderSymbol()?>" alt="<?=$employee->getSex()?>" class="w-4 h-4 inline-block ml-1"></li>

            </ul>
          </label>
        </div>
 
  
        <?php endforeach; ?>
      </div>
      
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