<?php 
require_once("./config/autoload.php");
$db = require_once("./config/db.php");

function prettyDump($data) {
  highlight_string("<?php\n\$data =\n" . var_export($data, true) . ";\n?>");
}

$userManager = new UserManager($db);
$zooManager = new ZooManager($db);
$employeeManager = new EmployeeManager($db);

unset($_SESSION['employee_id']);


$zoosDivDisplay = 'none';
$landingSectionDisplay = 'none';

if (!isset ($_SESSION['user_id'])){
    $connexionFormText = '';
    $inscriptionFormText = '';
    
    if (isset ($_POST['connexion_user_name'])){
        if ($userManager->findUserByName($_POST['connexion_user_name'])){
            $user = $userManager->findUserByName($_POST['connexion_user_name']);
            $_SESSION['user_id'] = $user->getId();
            header('Location: ./index.php');
            exit();
        } else{
            $connexionFormText = 'Utilisateur inconnu';
        }
    }

    if (isset ($_POST['inscription_user_name'])){
        if ($userManager->findUserByName($_POST['inscription_user_name'])){
            $inscriptionFormText = 'Utilisateur déjà connu<br>Connectez-vous';
        } else{
            $userManager->setUserInDB($_POST['inscription_user_name']);
            $user = $userManager->findUserByName($_POST['inscription_user_name']);
            $_SESSION['user_id'] = $user->getId();
        }
    }

    if (!isset ($_GET['inscription'])){
        $connexionSectionDisplay = 'block'; 
        $inscriptionSectionDisplay = 'none';
    } else {
        $connexionSectionDisplay = 'none'; 
        $inscriptionSectionDisplay = 'block';
    }


} else if($_SESSION['user_id']!=''){
    //variables d'affichage
    $connexionSectionDisplay = 'none'; 
    $inscriptionSectionDisplay = 'none';
    $landingSectionDisplay = 'block';
    $zoosDivDisplay = isset($_GET['create_zoo']) ? 'none' : 'block';
    $createZooDivDisplay= isset($_GET['create_zoo']) ? 'block' : 'none';
    $subtitleText = $zooManager->findAllZoosOfUser($_SESSION['user_id'])? "Choisis un zoo ci dessous ou" : "";

    //variables utiles
    $user = $userManager->findUserById($_SESSION['user_id']);
    $allEmployees = $employeeManager->findAllEmployees();
    $allZoos = $zooManager->findAllZoosOfUser($_SESSION['user_id']);
    // prettyDump($_SESSION['user_id']);
    // die;
}


?>

<?php require_once("./config/header.php"); ?>

<section id="connexionSection" class="container mx-auto flex justify-center items-center h-screen" style="display:<?=$connexionSectionDisplay?>">
  <div class="flex flex-col items-center">
    <img class="max-h-full sm:max-w-xs w-full" src="./assets/images/logos/Zoo-logo.png" alt="logo du zoo">
    <div class="my-form p-8 rounded-lg shadow-lg">
      <h2 class="text-green-1 text-2xl font-bold mb-8">Connexion</h2>
      <form action="./index.php" method="post">
        <div class="mb-4">
          <label class="text-green-1 block text-700 font-bold mb-2" for="connexion_user_name">Nom d'utilisateur</label>
          <input class="bg-white-1 border-white-1 border border-400 p-2 w-full" type="text" name="connexion_user_name" id="connexion_user_name" required>
        </div>
        <div class="formText text-orange-700 text-center mb-4">
            <?=$connexionFormText?>
        </div>
        <div class="mb-4">
          <button class="bg-emerald-800 bg-green-1 text-amber-200 font-bold py-2 px-4 rounded w-full" type="submit">Connexion</button>
        </div>
      </form>
      <div class="text-right mt-8">
        <a href="./index.php?inscription=true" class=" font-medium text-xl text-green-1 dark:text-blue-500 hover:underline">S'inscrire</a>
      </div>
    </div>
  </div>
</section>

<section id="inscriptionSection" class="container mx-auto flex justify-center items-center h-screen" style="display:<?=$inscriptionSectionDisplay?>">
  <div class="flex flex-col items-center">
    <img class="max-w-xl max-h-full sm:max-w-xs" src="./assets/images/logos/Zoo-logo.png" alt="Image description">
    <div class="my-form p-8 rounded-lg shadow-lg">
      <h2 class="text-green-1 text-2xl font-bold mb-8">Inscription</h2>

      <form action="./index.php?inscription=true" method="post">
        <div class="mb-4">
          <label class="text-green-1 block text-700 font-bold mb-2" for="inscription_user_name">Nom d'utilisateur</label>
          <input class="bg-white-1 border-white-1 border border-400 p-2 w-full" type="text" name="inscription_user_name" id="inscription_user_name" required>
        </div>
        <div class="formText text-orange-700 text-center mb-4">
            <?=$inscriptionFormText?>
        </div>
        <div class="mb-4">
          <button class="bg-emerald-800 bg-green-1 text-amber-200 font-bold py-2 px-4 rounded w-full" type="submit">Inscription</button>
        </div>
      </form>
      <div class="text-right mt-8">
        <a href="./index.php" class=" font-medium text-xl text-green-1 dark:text-blue-500 hover:underline">Se connecter</a>
      </div>
    </div>
  </div>
</section>

<section id="landingSection" class="container mx-auto flex justify-center items-center h-screen" style="display:<?=$landingSectionDisplay?>">

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


  <div id="zoosDiv" style="display:<?= $zoosDivDisplay ?>" >
    <div class="flex flex-col items-center justify-center mt-4 text-green-1 text-phosph">
      <h1 class="text-5xl font-bold text-center mb-4">Bienvenue <?= $user->getName() ?></h1>
    </div>

    <div class="flex flex-col items-center justify-center text-green-1 text-phosph">
      <p class="text-2xl font-bold text-center mb-2">
      <?= $subtitleText ?>
      </p>
      <p class="text-xl font-bold text-center mb-4">
      <button class="bg-transparent hover:bg-emerald-900 text-green-1 font-semibold hover:text-amber-50 py-1 px-3 border border-emerald-900 hover:border-transparent rounded" onclick="window.location.href = './index.php?create_zoo=true';">
        créé une nouvelle aventure !
      </button>
      
      </p>
    </div>


    <div class="container mx-auto" >
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

      <?php foreach ($allZoos as $zoo) : ?>
      <div class="bg-white rounded-lg shadow-lg m-2 ">
        <form action="./zooPage.php" method="get" class="zooSelectionCard rounded-lg">
          <div class="p-4">
          <p class="text-xl font-semibold uppercase text-phosph text-green-1 text-center mb-2"><?=$zoo->getName()?></p>
            <p class="text-lan text-green-1 text-center font-semibold">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          </div>
          <div class="flex justify-center">
            <input type="hidden" name="zoo_id" value="<?=$zoo->getId()?>">
            <button class="bg-emerald-800 bg-green-1 text-white font-bold py-2 px-4 m-2 rounded w-l" type="submit">Choisir</button>
          </div>
        </form>
      </div>

      <?php endforeach; ?>
       
      </div>
    </div>
  </div>

  <div id="createZooDiv" style="display:<?= $createZooDivDisplay ?>" >

    <form action="./traitments/createZoo.php" method="get" class="mx-auto w-full max-w-screen-sm">
      <div class="mb-4">
        <label class="block text-3xl font-bold text-center m-2 text-green-1 text-phosph" for="zooName">
          Nom de ton Zoo
        </label>
        <input class="zooNameInput bg-transparent shadow appearance-none rounded w-full m-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="zooName" name="zooName" type="text" placeholder="nom du zoo" required>
      </div>

      <p class="text-3xl font-bold text-center m-2 text-green-1 text-phosph">Choisis un employé</p>
      <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
        <?php foreach ($allEmployees as $employee) : ?>
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
    </form>


  </div>

</section>


<?php
require_once("./config/footer.php");
?>
