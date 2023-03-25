<?php
require_once("./config/autoload.php");

$db = require_once("./config/db.php");


function prettyDump($data) {
    highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");
}

// Instanciations d'objets
$zooManager = new ZooManager($db);
$employeeManager = new EmployeeManager($db);
$enclosureManager = new EnclosureManager($db);
$animalManager = new AnimalManager($db);


$zoo = $zooManager->findZoo($_SESSION['zoo_id']);
$allAnimalsOfZooAsObject = $zooManager->findAllAnimalsOfZoo($zoo->getId());
$allEnclosures = $enclosureManager->findAllEnclosuresOfZoo($zoo->getId());


// calcul des entrées
$adultsEntrancePrice = 10;
$childrenEntrancePrice = 6 ;
$entrances = 0;

foreach ($allAnimalsOfZooAsObject as $animal){
    $animalEnclosureCleanliness = ($animalManager->findEnclosureCleanlinessOfAnAnimal($animal->getId()))/10;
    $entrances += rand(0,$animal->getPrice()/50) * $animalEnclosureCleanliness;
}

$entrances=ceil($entrances/2)*2;

$adultsEntrances = round(rand($entrances/2, $entrances),0, PHP_ROUND_HALF_EVEN);
$childrenEntrances = $entrances - $adultsEntrances;

$adultsEntrancesGain = $adultsEntrances * $adultsEntrancePrice ;
$childrenEntrancesGain = $childrenEntrances * $childrenEntrancePrice ;


// calculs des coûts
$employeesCost = count($zoo->getEmployees_id()) * 100;

// Gains totaux
$dailyGain = $adultsEntrancesGain + $childrenEntrancesGain - $employeesCost;

//--Insertions en DB et retour vers le zoo--
if (isset($_GET['dailyGain'])){
    // updates zoo
    $zooManager->updateBudget($zoo->getId(), $zoo->getBudget() + $_GET['dailyGain']);
    $zooManager->updateDay($zoo->getId(), $zoo->getDay() + 1);

    // updates employee actions
    foreach ($zoo->getEmployees_id() as $employeeId){
        $employeeManager->updateActions($employeeId, 10);
        }

    // update de la propreté des enclos
    foreach ($allEnclosures as $enclosure){
        $enclosureCleanliness = $enclosure->getCleanliness() - rand(0,3);
        $enclosureCleanliness < 0? $enclosureCleanliness = 0: $enclosureCleanliness=$enclosureCleanliness;
        $enclosureManager->updateCleanliness($enclosure->getId(), $enclosureCleanliness);
    }

    // updates animals properties  
    $deadAnimals = 0;  
    foreach ($allAnimalsOfZooAsObject as $animal){
        $animalSatiation = $animal->getIsHungry() - (rand(0,30)/10);
        $animalSatiation < 0? $animalSatiation = 0: $animalSatiation=$animalSatiation;

        $animalHealth = $animal->getIsSick() - (rand(0,(10-$animalSatiation)*10)/10);
        if ($animalHealth < 0){
            $animalHealth = 0;
            $animalManager->updateDeadAnimal($animal->getId());
            $deadAnimals ++;
        }

        prettyDump($animalSatiation);        
        prettyDump($animalHealth);        
        var_dump($animalManager->updateIsHungry($animal->getId(), $animalSatiation));
        var_dump($animalManager->updateIsSick($animal->getId(), $animalHealth));
        die;
    }

    //renvoi vers DailyPage si animal mort, sinon retour Zoo au jour suivant
    $deadAnimals!=0 ? header('Location: ./zooDailyPage.php?dead') : header('Location: ./zooPage.php');
    

}



require_once("./config/header.php");
?>

<?php if (isset($_GET['dead'])) : ?>

    <section id="deadAnimals">

        <?php foreach ($zooManager->findAllDeadAnimals($zoo->getId()) as $deadAnimal) : ?>
            <?php $animalManager->deleteDeadAnimal(intval($deadAnimal->getId())) ?>
            <div class="flex justify-center">
                <div class="text-center">
                    <h1 class="text-5xl font-bold mt-8 m-4 text-lan text-orange-700"><?=$deadAnimal->getName()?> est mort(e) !</h1>
                    <span class="text-lg text-phosp text-green-1"><?= $zooManager->echoDeathSentence() ?></span>
                </div>
            </div>
        <?php endforeach ?>

        <div id="followingDay" class="flex flex-col lg:flex-row justify-center items-center m-10">
            <button class="w-36 text-xl bg-green-1 text-white-1 font-bold py-1 px-2 rounded h-12 mx-2 mb-2" onclick="window.location.href = './zooPage.php';">
                Valider
            </button>
        </div>

<?php die ?>
<?php endif ?>
    </section>

<section id="pageBody" class="bg-white-1 flex flex-col justify-center items center mt-6">

    <h1 class="text-6xl text-phosph text-green-1 font-bold text-center mb-4"><?= $zoo->getName() ?></h1>
    <p class="text-3xl text-phosph text-green-1 font-bold text-center mb-2"> Jour 
      <?= $zoo->getDay() ?>
    </p>

    <div id="entrancesTable" class="w-11/12 sm:w-2/3 lg:w-1/2 rounded-lg overflow-hidden mx-auto text-2xl text-lan text-green-1 mt-8 mb-4">
        <table class="w-full text-center border border_green-1 rounded-lg">
            <thead class="bg-green-1 text-white-1">
            <tr>
                <th colspan="4">Visites de <?= $zoo->getName() ?> - Jour <?= $zoo->getDay() ?></th>
            </tr>
            </thead>
            <tbody>
            <tr class="font-semibold">
                <td>Public</td>
                <td>Entrées</td>
                <td>Prix/entrée</td>
                <td>Gain</td>
            </tr>
            <tr>
                <td>Adultes</td>
                <td><?= $adultsEntrances ?></td>
                <td><?= $adultsEntrancePrice ?> 💰</td>
                <td><?= $adultsEntrancesGain ?> 💰</td>
            </tr>
            <tr>
                <td>Enfants</td>
                <td><?= $childrenEntrances ?></td>
                <td><?= $childrenEntrancePrice ?> 💰</td>
                <td><?= $childrenEntrancesGain ?> 💰</td>
            </tr>
            <tr>
                <td>TOTAL</td>
                <td><?= $adultsEntrances + $childrenEntrances ?></td>
                <td>-</td>
                <td><?= $adultsEntrancesGain + $childrenEntrancesGain ?> 💰</td>
            </tr>
            </tbody>
        </table>
    </div>


    <div id="expensesTable" class="w-11/12 sm:w-2/3 lg:w-1/2 rounded-lg overflow-hidden mx-auto text-2xl text-lan text-green-1 my-4">
        <table class="w-full text-center border border_green-1">
            <thead class="bg-green-1 text-white-1">
                <tr>
                    <th colspan="3">Dépenses de fin de journée</th>
                </tr>
            </thead>
            <tbody>
                <tr class="font-semibold">
                    <td>Secteur</td>
                    <td>Nombre</td>
                    <td>Coût</td>
                </tr>
                <tr>
                    <td>Employés</td>
                    <td><?= count($zoo->getEmployees_id($zoo->getId())) ?></td>
                    <td><?= $employeesCost ?> 💰</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="dailyGainTable"  class="w-11/12 sm:w-2/3 lg:w-1/2 rounded-lg overflow-hidden mx-auto text-2xl text-lan text-green-1 my-4">
        <table class="w-full text-center border border_green-1">
            <thead class="bg-green-1 text-white-1">
                <tr>
                    <th colspan="1">Gains jour <?= $zoo->getDay() ?></th>
                </tr>
            </thead>
            <tbody>
                <tr class="font-semibold text-3xl">
                    <td><?= $dailyGain ?> 💰</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="followingDay" class="flex flex-col lg:flex-row justify-center items-center m-10">
            <button class="w-1/2 text-3xl bg-green-1 text-white-1 font-bold py-1 px-2 rounded w-80 h-16 mx-2 mb-2" onclick="window.location.href = './zooDailyPage.php?dailyGain=<?=$dailyGain?>';">
                Valider
            </button>
    </div>

   

    

</section>



<?php require_once('./config/footer.php'); ?>