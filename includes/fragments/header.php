<?php //Si on appuie sur le bouton submit de la modal Modifier le mot de passe
require_once('../includes/modals/modifierMotDePasse.php');
if (!empty($_POST["modifierMotDePasse"])) {
    mettreAJourMotDePasse();
}
?>
<nav class="navbar sticky-top navbar-expand-lg navbar-dark transparent" id="nav">
    <div class="container-fluid">
        <div class="navbar-header">

            <a class="btn btn-default" href="accueil.php">
                <i class="fa fa-address-book-o fa-2x" style="color:white" aria-hidden="true"></i>
            </a>
            <a href="accueil.php" class="navbar-brand text-light" href="#">Annuaire</a>

        </div>

        <!--Met les éléments de la navbar à l'opposé l'un de l'autre-->

        <?php if (estConnecte() && !estGestionnaire()) { ?>

            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle text-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bonjour <?= $_SESSION["nomUtilisateur"]; ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">


                    <a class="dropdown-item" href="profil.php?idEleve=<?= getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]); ?>">Profil</a> <!-- On récupère notre propre idEleve pour accéder à notre profil-->
                    <a class="dropdown-item" href="deconnexion.php">Se déconnecter</a>
                </div>
            </div>
        <?php } else if (estConnecte() && estGestionnaire()) { ?>
            <!-- navbar du gestionnaire -->
            

            <a href="creerComptes.php" class="navbar-brand text-light" href="#">Créer un compte</a>

            <a href="validationCompte.php" class="navbar-brand text-light" href="#">Valider un compte</a>

            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle text-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bonjour <?= $_SESSION["nomUtilisateur"]; ?>
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                    <!-- Modification de mot de passe -->
                    <button class="dropdown-item" type="button" id="modifierMotDePasseGestionnaire">Mot de passe</button>

                    <script type="text/javascript"> //pour ouvrir la modal
                        $('#modifierMotDePasseGestionnaire').on('click', function() {
                            $('#modifierMotDePasse').modal('show');
                        });
                    </script>
                    
                    <!-- Déconnexion -->
                    <a class="dropdown-item" href="deconnexion.php">Se déconnecter</a>
                </div>
            </div>


        <?php } else { ?>
            <a href="connexion.php" class="btn btn-outline-light my-2 my-sm-0 text-light" type="button"> J'ai déjà un compte</a>
        <?php } ?>

    </div>
</nav>