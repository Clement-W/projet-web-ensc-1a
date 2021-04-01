<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Contient la barre de navigation présente sur chacune des pages du site web
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */


require_once('../includes/modals/modifierMotDePasse.php'); // Pour le gestionnaire

// Si on appuie sur le bouton submit de la modal Modifier le mot de passe
if (!empty($_POST["modifierMotDePasse"])) { // pour que le gestionnaire puisse changer son mot de passe depuis la modal 
    mettreAJourMotDePasse();
}

?>
<nav class="navbar sticky-top navbar-expand-lg navbar-dark transparent" id="nav">
    <div class="container-fluid">
        <div class="navbar-header">

            <a class="btn btn-default" href="accueil.php">
                <i class="fa fa-address-book-o fa-2x" style="color:white" aria-hidden="true"></i>
            </a>
            <a href="accueil.php" class="navbar-brand text-light">Annuaire</a>

        </div>



        <?php if (estConnecte() && estGestionnaire()) { ?>
            <!-- Seul le gestionnaire a ces menus -->
            <a href="creerComptes.php" class="navbar-brand text-light">Créer un compte</a>

            <a href="validationCompte.php" class="navbar-brand text-light">Valider un compte</a>

        <?php } ?>



        <?php if (estConnecte()) { ?>
            <!-- Personnalisation du menu dropdown en fonction du type d'utilisateur connecté -->
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle text-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bonjour <?= $_SESSION["nomUtilisateur"]; ?>
                </button>
                <div class="dropdown-menu">

                    <?php if (!estGestionnaire()) { ?>

                        <a class="dropdown-item" href="profil.php?idEleve=<?= getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]); ?>">Profil</a> <!-- On récupère notre propre idEleve pour accéder à notre profil-->
                        <a class="dropdown-item" href="deconnexion.php">Se déconnecter</a>

                    <?php } else if (estGestionnaire()) {  ?>

                        <!-- Modification de mot de passe -->
                        <a href="#" class="dropdown-item" id="modifierMotDePasseGestionnaire">Mot de passe</a>

                        <script>
                            //pour ouvrir la modal de modification de mot de passe
                            $('#modifierMotDePasseGestionnaire').on('click', function() {
                                $('#modifierMotDePasseModal').modal('show');
                            });
                        </script>

                        <!-- Déconnexion -->
                        <a class="dropdown-item" href="deconnexion.php">Se déconnecter</a>

                        <?php } ?>
                </div>
            </div>

        <!-- bouton de connnexion lorsqu'il n'y a pas d'utilisateur connecté -->
    <?php } else { ?>
        <a href="connexion.php" class="btn btn-outline-light my-2 my-sm-0 text-light"> J'ai déjà un compte</a>
    <?php } ?>

    </div>
</nav>