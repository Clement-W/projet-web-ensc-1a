<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Permet de créer une popup pour alerter l'utilisateur qu'il n'a pas d'expériences professionnelle.
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */
?>

<div class="modal fade" id="alertePasExperiencePro">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Attention !</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Vous n'avez pas encore ajouté d'expérience professionnelle à votre profil.
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <a href="modifierProfil.php?idEleve=<?= getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]); ?>" class="btn btn-success" type="button">Compléter le profil</a>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    // Pour afficher la popup
    $('#alertePasExperiencePro').modal('show');
</script>