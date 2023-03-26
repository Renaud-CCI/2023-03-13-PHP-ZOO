<?php 
require_once("./config/autoload.php");

$db = require_once("./config/db.php");

function prettyDump($data) {
  highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");
}

$enclosureManager = new EnclosureManager($db);
$animalManager = new AnimalManager($db);
$zooManager = new ZooManager($db);

$enclosure = $enclosureManager->findEnclosure($_GET['enclosure_id']);
$zoo = $zooManager->findZoo($_SESSION['zoo_id']);


if (isset($_GET['animalName'])){
    $animalManager->setAnimalInDb($_GET);
    $zooManager->updateBudget($_SESSION['zoo_id'], $zoo->getBudget() - $_GET['animal_price']);

    header('Location:./enclosurePage.php?enclosure_id='.$_GET['enclosure_id']);
}

$animalTypes = [];

if ($enclosure->getAnimals_type() == 'default'){
    foreach ($enclosure->getAcceptedAnimals() as $animalType){
        array_push($animalTypes, $animalType);
    }
} else {
    array_push($animalTypes, $enclosure->getAnimals_type());
}



?>
<?php require_once("./config/header.php"); ?>


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
    <a href="./enclosurePage.php?enclosure_id=<?=$_GET['enclosure_id']?>" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end">
        Retour Enclos
    </a>
    <a href="./traitments/sessionDestroy.php" class="block mt-4 lg:inline-block lg:mt-0 text-white-1  hover:text-white mr-4 text-end">
        Se déconnecter
    </a>
    </div>
</div>
</nav>


  

<div id="constructAnimal" class="grid">

    <form action="./addAnimal.php" method="get" class="mx-auto w-full max-w-screen-sm">
      
        
        
        
        <div class="mt-8 m-4 flex flex-col justify-center items-center">
            <label class="block text-4xl font-bold text-center m-2 text-emerald-900 text-phosph" for="animalName">
                Nom de l'animal
            </label>
            <input class="zooNameInput bg-transparent shadow appearance-none rounded w-96 m-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="animalName" name="animalName" type="text" placeholder="nom" required>
        </div>
        
        <div class="m-4 flex justify-center items-center flex-row text-end">
            <div class="m-4 flex justify-center items-center flex-row ">
                <label for="animalSelect" class="text-3xl font-bold text-emerald-900 text-phosph w-1/2">Type</label>
                <select class="zooNameInput bg-transparent shadow appearance-none rounded w-32 m-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-1/2" name="animal_type" id="animalSelect" class="text-center" required>
                    <?php foreach ($animalTypes as $animalType) : ?>
                        <option value="<?=$animalType?>"><?=$animalType?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="m-4 flex justify-center items-center flex-row ">
                <label for="sexSelect" class="text-3xl font-bold text-emerald-900 text-phosph w-1/2">Sexe</label>
                <select class="zooNameInput bg-transparent shadow appearance-none rounded w-32 m-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-1/2" name="sex" id="sexSelect" class="text-center" required>
                    <option value="Male">Male</option>
                    <option value="Femelle">Femelle</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
        </div>




        <div class="m-4 flex justify-center items-center flex-row text-end">
            <label class="text-3xl font-bold text-emerald-900 text-phosph w-1/2" for="animalWeight">Poids</label>
            <input class="zooNameInput bg-transparent shadow appearance-none rounded w-32 m-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-1/2" id="animalWeight" name="animalWeight" type="number" min="0" placeholder="poids (kg)" step="0.5" required>
            <label class="text-3xl font-bold text-emerald-900 text-phosph w-1/2" for="animalHeight">Taille</label>
            <input class="zooNameInput bg-transparent shadow appearance-none rounded w-32 m-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-1/2" id="animalHeight" name="animalHeight" type="number" min="0" placeholder="taille (m)" step="0.1" required>
        </div>


    <input type="hidden" name="enclosure_id" value="<?=$_GET['enclosure_id']?>">
    <input type="hidden" name="animal_price" value="<?= $enclosure->getAnimalPrice() ?>">
    <input type="hidden" name="birthday" value="<?= $zoo->getDay() ?>">
    
    <div class="flex flex-col items-center mt-6">
        <button class="bg-emerald-900 text-white-1 font-bold py-2 px-4 rounded w-64">
            Valider <span class="text-base ml-3">- <?= $enclosure->getAnimalPrice() ?> 💰</span>
        </button>
    </div>
</form>

    <div class="flex flex-col items-center mt-6">
        <button class="bg-red-700 text-white-1 font-bold py-2 px-4 rounded w-64" onclick="window.location.href = './enclosurePage.php?enclosure_id=<?=$_GET['enclosure_id']?>';">
          Annuler
        </button>
      </div>




  </div>



<?php
require_once('./config/footer.php');
?>