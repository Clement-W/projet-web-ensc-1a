<?php 
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Ce fichier inclus les champs de formExperiencePro.php pour créer un formulaire permettant
 * d'ajouter une nouvelle expérience professionnelle.
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

 ?>

<div class="modal fade" id="ajouterExperience">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Ajouter une nouvelle expérience</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->

            <div class="modal-body">
                <form method="POST" action="modifierProfil.php?idEleve=<?= getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]); ?>">
                    </br>
                    <p class="h5 text-secondary"><i class="fa fa-exclamation-triangle fa-sm" style="color:black" aria-hidden="true"></i> Les champs avec des astérisques (*) sont obligatoires. Vous pouvez cocher la case pour rendre visible cette expérience professionelle auprès des autres utilisateurs. </p>
                    </br>
                    <?php require_once("../includes/fragments/formExperiencePro.php"); ?>
                    <div class="d-flex justify-content-end ">
                        <input type="submit" class="btn btn-outline-success" name="ajouterExperiencePro" id="ajouterExperiencePro" value="Ajouter" />
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>