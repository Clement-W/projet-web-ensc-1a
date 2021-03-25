<!-- Utilisé pour afficher expérience déjà définie OU pour en ajouter une nouvelle-->

<?php $idChamp = (isset($idExpPro)) ? $idExpPro : ""; //on utilise l'id de l'experience pro pour rendre unique le champ pour le cas où il ya  plusieurs experiences pro sur un profil. Si idExpPro n'est pas défini on est pas dans la page de modification de profil mais dans la modal d'ajout d'experience pro ?>


<div class="d-flex">
    <div class="custom-control custom-checkbox mt-2 col-sm-4">
        <!-- On ajoute l'id de l'experience pro pour rendre unique ce champ s'il est affiché sur un profil avec plusieurs experiences pro -->
        <input type="checkbox" name="visibiliteExpPro<?= $idChamp ?>" class="custom-control-input" id="intituleVisibilite<?= $idChamp ?>" <?= (isset($visibilite) && isset($idExpPro) && ($visibilite[$idExpPro] == true)) ? "checked" : ""; ?>> <!-- On coche si la visibiliite de l'experience pro déjà existante a été cochée  -->
        <label class="custom-control-label font-weight-bold" for="intituleVisibilite<?= $idChamp ?>">Intitulé de l'expérience</label>
    </div>
    <div class="form-group col-sm-8">
    <!-- Si on ajoute une nouvelle expérience, la variable est vide donc on remplit les champs avec ""-->
        <input type="text" name="intituleExperiencePro<?=$idChamp?>" value="<?= (isset($intituleExp)) ? $intituleExp :""; ?>" class="form-control" id="intitule<?=$idChamp?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Type d'expérience *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" name="typeExperiencePro<?=$idChamp?>" value="<?= (isset($typeExp)) ? $typeExp :""; ?>" class="form-control" id="typeExp<?=$idChamp?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Date de début *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="date" name="dateDebut<?=$idChamp?>" value="<?= (isset($dateDebut)) ? $dateDebut :""; ?>" class="form-control" id="dateDebut<?=$idChamp?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Date de fin</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="date" name="dateFin<?=$idChamp?>" value="<?= (isset($dateFin)) ? $dateFin :""; ?>" class="form-control" id="dateFin<?=$idChamp?>">
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Type d'organisation *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" name="typeOrganisation<?=$idChamp?>" value="<?= (isset($typeOrganisation)) ? $typeOrganisation :""; ?>" class="form-control" id="typeOrganisation<?=$idChamp?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Libellé de l'organisation *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" name="libelleOrganisation<?=$idChamp?>" value="<?= (isset($libelleOrganisation)) ? $libelleOrganisation :""; ?>" class="form-control" id="libelleOrganisation<?=$idChamp?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Type de poste *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" name="typePoste<?=$idChamp?>" value="<?= (isset($typePoste)) ? $typePoste :""; ?>" class="form-control" id="typePoste<?=$idChamp?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Région *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" name="region<?=$idChamp?>" value="<?= (isset($region)) ? $region :""; ?>" class="form-control" id="region<?=$idChamp?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Ville *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" name="ville<?=$idChamp?>" value="<?= (isset($villeExperience)) ? $villeExperience :""; ?>" class="form-control" id="ville<?=$idChamp?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Secteur(s) d'activité *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" name="secteursActivites<?=$idChamp?>" value="<?= (isset($secteursActivites)) ? $secteursActivites :""; ?>" class="form-control" id="secteursActivites<?=$idChamp?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Domaine(s) de compétence *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" name="domainesCompetences<?=$idChamp?>" value="<?= (isset($domainesCompetences)) ? $domainesCompetences :""; ?>" class="form-control" id="domainesCompetences<?=$idChamp?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Description </p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" name="description<?=$idChamp?>" value="<?= (isset($description)) ? $description :""; ?>" class="form-control" id="description<?=$idChamp?>"/>
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Salaire</p>
    </div> 
    <div class="form-group col-sm-8">
        <input type="number" name="salaire<?=$idChamp?>" value="<?= (isset($salaire)) ? $salaire :""; ?>" class="form-control" id="salaire<?=$idChamp?>"/>
    </div>
</div>

<hr class="ml-5 mr-5" style="border: 0.5px solid grey;" />