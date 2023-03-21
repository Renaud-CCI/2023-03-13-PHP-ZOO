<?php
require_once("./config/autoload.php");
$db = require_once("./config/db.php");

require_once("./config/header.php");

function prettyDump($data) {
  highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");
}
?>

<?php

$zooManager = new ZooManager($db);
$enclosureManager = new EnclosureManager($db);
$animalManager = new AnimalManager($db);

$zoo = $zooManager->findZoo($_SESSION['zoo_id']);
$enclosure = $enclosureManager->findEnclosure($_GET['enclosure_id']);

$allAnimalsAsObject = $animalManager->findAllAnimalsOfEnclosure($_GET['enclosure_id']);

// prettyDump($enclosure);




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


<section id="enclosureDetail">
  <div class="flex flex-col items-center justify-center mt-4 mb-5 text-green-1 text-phosph">
    <h1 class="text-5xl font-bold text-center mb-4"><?= $zoo->getName() ?></h1>
    <h2 class="text-4xl mb-2"><?=$enclosure->getName()?></h2>
    <img class="w-28 mb-2" src="<?=$enclosure->getAvatar()?>" alt="">
  </div>

  
  
  <div class="grid grid-cols-3 lg:grid-cols-5 gap-4 mx-auto mb-4 text-center">
    <p>Propreté : <?= $enclosure->getCleanliness()?></p>
    <p style="display:<?= method_exists($enclosure,'getSalinity')? 'block' : 'none' ?>">Salinité : <?= method_exists($enclosure,'getSalinity')? $enclosure->getSalinity() : ''?></p>
    <p style="display:<?=  method_exists($enclosure,'getHeight')? 'block' : 'none' ?>">Hauteur : <?= method_exists($enclosure,'getHeight')? $enclosure->getHeight() : '' ?></p>
  </div>

  <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mx-auto justify-items-center">
  <button class="bg-emerald-800 bg-green-1 text-white-1 font-bold py-2 px-4 rounded w-50 mb-2" onclick="window.location.href = './addAnimal.php?enclosure_id=<?=$_GET['enclosure_id']?>';">Ajouter un animal</button>
  <button class="bg-emerald-800 bg-green-1 text-white-1 font-bold py-2 px-4 rounded w-48 mb-2">Bouton pour faire autre chose</button> 
  </div>

</section>

<section id="enclosureAnimals">
  <p class="text-3xl font-bold text-center mt-10 m-2 text-green-1 text-phosph">Animaux dans l'enclos</p>
    <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
      <?php foreach ($allAnimalsAsObject as $animal) : ?>

      <div class="">
        <input class="employee-input hidden" id="<?=$animal->getId()?>" type="radio" name="animalId" value="<?=$animal->getId()?>" required>
        <label class="flex flex-col p-2 cursor-pointer bg-white rounded-lg shadow-lg" for="<?=$animal->getId()?>">
          <span class="text-xl text-center font-semibold uppercase text-phosph text-green-1"><?=$animal->getName()?></span>
          <img src="./assets/images/logos/<?=$animal->getSpecies() ?>.png" alt="avatar" class="mx-auto w-20">
          <ul class="text-sm mt-2 items-center">
            <li class="text-lan text-green-1 text-center font-semibold"><?=$animal->getAge()?> ans</li>
            <li class="flex justify-center text-lan text-green-1 text-center font-semibold">Sexe : <img src="<?=$animal->getGenderSymbol()?>" alt="<?=$animal->getSex()?>" class="w-4 h-4 inline-block ml-1"></li>

          </ul>
        </label>
      </div>
 
  
        <?php endforeach; ?>
      </div>

</section>


<?php
require_once("./config/footer.php");
?>
