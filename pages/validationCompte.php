<?php
require_once "../includes/functions.php";
session_start();
if (!empty($_POST["validation"])) {
    validerCompteEleve(escape($_POST["IdEleve"]));
}

if (!empty($_POST["invalidation"])) {
    supprimerCompteEleve(escape($_POST["IdEleve"]));
}
?>

<!doctype html>
<html lang="fr">

<?php
$titrePage = "Validation de comptes";
require_once "../includes/fragments/head.php";
?>

<body class="background">
    <?php require_once "../includes/fragments/header.php"; ?>

    <div class="container">
        <script src="../js/function.js"></script>

        <?php
        $comptesNonValides = getCompteNonValide();
        if (count($comptesNonValides) == 0) { ?>

            <div class="text-center mt-5">
                <h2>Il n'y a pas de compte en attente de validation.</h2>
            </div>



            <?php

        } else {
            foreach ($comptesNonValides as $compte) {
            ?>



                <div class="whitecontainer mt-3 mb-3" id="compteNonValide<?= $compte["IdEleve"] ?>">
                    <div class="ml-4 row text-secondary">
                        <div class="h3">
                            <div class="affichageProfil"><?= $compte["Prenom"] ?> <?= $compte["Nom"] ?> - Promotion <?= $compte["Promotion"] ?></div>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-outline-success mr-5" onclick='validerCompte("<?= $compte["IdEleve"] ?>")'>Valider</button>
                                <button class="btn btn-outline-danger mr-5" onclick='invaliderCompte("<?= $compte["IdEleve"] ?>")'>Refuser</button>
                                <a href="profil.php?idEleve=<?= $compte["IdEleve"] ?>" class="btn btn-outline-dark mr-5" type="button">Voir le profil</a>
                            </div>
                        </div>
                    </div>
                </div>
        <?php }
        } ?>



    </div>
</body>

</html>