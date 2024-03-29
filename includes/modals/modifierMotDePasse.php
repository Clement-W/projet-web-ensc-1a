<?php

/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Modal contenant un formulaire permettant de modifier son mot de passe. (Utilisé par le gestionnaire et par l'élève)
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

require_once("../includes/fonctionsGenerales.php");
require_once("../includes/fonctionsEleve.php");

if (estConnecte()) { ?>
    <div class="modal fade" id="modifierMotDePasseModal">
        <div class="modal-dialog">
            <div class="modal-content">


                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Modifier le mot de passe</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <?php if (estGestionnaire()) { ?>
                    <form method="POST" action="accueil.php" class="register-form ml-5 mr-5" id="register-form">

                    <?php } else { ?>

                        <form method="POST" action="modifierProfil.php?idEleve=<?= getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]); ?>" class="register-form ml-5 mr-5" id="register-form">

                        <?php } ?>

                        <div class="form-group">
                            <label for="ancienMotDePasse"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" maxlength="50" name="ancienMotDePasse" id="ancienMotDePasse" placeholder="Ancien mot de passe" required />
                        </div>
                        <div class="form-group">
                            <label for="nouveauMotDePasse"><i class="zmdi zmdi-account material-icons-name "></i></label>
                            <input type="password" maxlength="50" name="nouveauMotDePasse" id="nouveauMotDePasse" placeholder="Nouveau mot de passe" required />
                        </div>
                        <div class="form-group">
                            <label for="confirmeNouveauMotDePasse"><i class="zmdi zmdi-account material-icons-name "></i></label>
                            <input type="password" maxlength="50" name="confirmeNouveauMotDePasse" id="confirmeNouveauMotDePasse" placeholder="Confirmer le nouveau mot de passe" required />
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <div class="form-group form-button d-flex ">
                                <input type="submit" class="btn btn-outline-danger" name="modifierMotDePasse" id="modifierMotDePasse" value="Modifier" />
                            </div>
                        </div>
                        </form>
            </div>
        </div>
    </div>


<?php } ?>