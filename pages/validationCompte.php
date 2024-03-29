<?php

require_once("../includes/fonctionsUtilitaires.php");
require_once("../includes/fonctionsGenerales.php");
require_once("../includes/fonctionsGestionnaire.php");
require_once("../includes/fonctionsEleve.php");

session_start();
if (!empty($_POST["validation"])) {
    validerCompteEleve(escape($_POST["IdEleve"]));
}

if (!empty($_POST["invalidation"])) {
    supprimerCompteEleve(escape($_POST["IdEleve"]));
}

if (estConnecte() && estGestionnaire()) {
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
            <script src="../includes/fonctions.js"></script>

            <?php
            $comptesNonValides = getComptesNonValide();
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

                                <div class="d-flex">
                                    <button class="btn btn-outline-success mr-5" onclick='validerCompte("<?= $compte["IdEleve"] ?>")'>Valider</button>
                                    <button class="btn btn-outline-danger mr-5" onclick='invaliderCompte("<?= $compte["IdEleve"] ?>")'>Refuser</button>
                                    <a href="profil.php?idEleve=<?= $compte["IdEleve"] ?>" class="btn btn-outline-dark mr-5">Voir le profil</a>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php }
            } ?>



        </div>
    </body>

    </html>
<?php } else {
    redirect("404.php");
} ?>