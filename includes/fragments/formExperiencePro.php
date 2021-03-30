<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Ce fichier contient les champs d'un formulaire permettant d'afficher une expérience professionnelle déjà définie
 * ou d'en ajouter une nouvelle.
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */


$idChamp = (isset($idExpPro)) ? $idExpPro : ""; //on utilise l'id de l'experience pro pour rendre unique l'id du champ pour le cas où il ya  plusieurs experiences pro sur un profil. 
//Si idExpPro n'est pas défini, on est pas dans la page de modification de profil mais dans la modal d'ajout d'experience pro
?>

<div class="d-flex">
    <div class="custom-control custom-checkbox mt-2 col-sm-4">
        <!-- On ajoute l'id de l'experience pro pour rendre unique ce champ s'il est affiché sur un profil avec plusieurs experiences pro -->
        <input type="checkbox" name="visibiliteExpPro<?= $idChamp ?>" class="custom-control-input" id="intituleVisibilite<?= $idChamp ?>" <?= (isset($visibilite) && isset($idExpPro) && ($visibilite[$idExpPro] == true)) ? "checked" : ""; ?>> <!-- On coche si la visibiliite de l'experience pro déjà existante a été cochée  -->
        <label class="custom-control-label font-weight-bold" for="intituleVisibilite<?= $idChamp ?>">Visibilité</label>
    </div>

</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Intitulé de l'experience *</p>
    </div>
    <div class="form-group col-sm-8">
        <!-- Si on ajoute une nouvelle expérience, la variable est vide donc on remplit les champs avec ""-->
        <input type="text" maxlength="200" placeholder="Ex : Stage de fin d'études chez ..." name="IntituleExperiencePro<?= $idChamp ?>" value="<?= (isset($intituleExp)) ? $intituleExp : ""; ?>" class="form-control" id="intitule<?= $idChamp ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Type d'expérience *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" maxlength="50" placeholder="Ex : Stage" name="TypeExperiencePro<?= $idChamp ?>" value="<?= (isset($typeExp)) ? $typeExp : ""; ?>" class="form-control" id="typeExp<?= $idChamp ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Date de début *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="date" name="DateDebut<?= $idChamp ?>" value="<?= (isset($dateDebut)) ? $dateDebut : ""; ?>" class="form-control" id="dateDebut<?= $idChamp ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Date de fin</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="date" name="DateFin<?= $idChamp ?>" value="<?= (isset($dateFin)) ? $dateFin : ""; ?>" class="form-control" id="dateFin<?= $idChamp ?>">
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Type d'organisation *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" maxlength="50" placeholder="Ex : Entreprise, Laboratoire, ..." name="TypeOrganisation<?= $idChamp ?>" value="<?= (isset($typeOrganisation)) ? $typeOrganisation : ""; ?>" class="form-control" id="typeOrganisation<?= $idChamp ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Libellé de l'organisation *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" maxlength="50" placeholder="Ex : Thales, IMS, ..." name="LibelleOrganisation<?= $idChamp ?>" value="<?= (isset($libelleOrganisation)) ? $libelleOrganisation : ""; ?>" class="form-control" id="libelleOrganisation<?= $idChamp ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Type de poste *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" maxlength="50" placeholder="Ex : Stagiaire, Développeur, CTO, ..." name="TypePoste<?= $idChamp ?>" value="<?= (isset($typePoste)) ? $typePoste : ""; ?>" class="form-control" id="typePoste<?= $idChamp ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Région *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" maxlength="50" placeholder="Ex : Aquitaine" name="Region<?= $idChamp ?>" value="<?= (isset($region)) ? $region : ""; ?>" class="form-control" id="region<?= $idChamp ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Ville *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" maxlength="50" placeholder="Ex : Bordeaux" name="Ville<?= $idChamp ?>" value="<?= (isset($villeExperience)) ? $villeExperience : ""; ?>" class="form-control" id="ville<?= $idChamp ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Secteur(s) d'activité *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" maxlength="50" placeholder="Ex : Agroalimentaire, Banque, Aéronautique, ..." name="SecteursActivites<?= $idChamp ?>" value="<?= (isset($secteursActivites)) ? $secteursActivites : ""; ?>" class="form-control" id="secteursActivites<?= $idChamp ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Domaine(s) de compétence *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" maxlength="50" placeholder="Ex : IA, SHS, ..." name="DomainesCompetences<?= $idChamp ?>" value="<?= (isset($domainesCompetences)) ? $domainesCompetences : ""; ?>" class="form-control" id="domainesCompetences<?= $idChamp ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Description </p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" maxlength="1000" placeholder="Description de l'experience, objectifs, responsabilités, ..." name="Description<?= $idChamp ?>" value="<?= (isset($description)) ? $description : ""; ?>" class="form-control" id="description<?= $idChamp ?>" />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Salaire</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="number" placeholder="Salaire mensuel brut" name="Salaire<?= $idChamp ?>" value="<?= (isset($salaire)) ? $salaire : ""; ?>" class="form-control" id="salaire<?= $idChamp ?>" />
    </div>
</div>

<hr class="ml-5 mr-5" style="border: 0.5px solid grey;" />