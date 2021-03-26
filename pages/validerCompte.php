<?php
require_once "../includes/functions.php";
session_start();
if (!empty($_POST["inscription"])) {
    inscription();
}
?>

<!doctype html>
<html lang="fr">

<?php
$titrePage = "Accueil";
require_once "../includes/fragments/head.php";
?>

<body class="background">
    <?php require_once "../includes/fragments/header.php"; ?>



    <div class="container">

        <?php require_once('../includes/fragments/alert.php');
        if (isset($_SESSION["alert"])) {
            unset($_SESSION["alert"]);
        }


        
        $comptesNonValides = getCompteNonValide();
        foreach ($comptesNonValides as $compte) {
        ?>
            <div class="whitecontainer mt-3 mb-3">
                <div class="ml-4 row text-secondary">
                    <div class="col-md-6 h3">
                        <div class="col-md-12">
                            <div class="affichageProfil"><?= $compte["Prenom"] ?> <?= $compte["Nom"] ?> - Promotion <?= $compte["Promotion"] ?></div>
                            
                            <button class="btn btn-outline-success mr-5" onclick='validerCompteEleve("<?= $compte["Promotion"] ?>")'>Valider</button>
                            <a href="" class="btn btn-outline-danger mr-5" type="button" onclick="invaliderCompteEleve($compte['IdEleve'])">Refuser</a>
                            <a href="profil.php?idEleve=<?= $compte["IdEleve"] ?>" class="btn btn-outline-dark mr-5" type="button">Voir le profil</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>



    </div>
</body>

</html>