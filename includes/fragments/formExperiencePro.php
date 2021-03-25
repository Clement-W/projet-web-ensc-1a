<!-- Utilisé pour afficher expérience déjà définie OU pour en ajouter une nouvelle-->

<div class="d-flex">
    <div class="custom-control custom-checkbox mt-2 col-sm-4">
        <!-- On ajoute l'id de l'experience pro pour rendre unique ce champ s'il est affiché sur un profil avec plusieurs experiences pro -->
        <input type="checkbox" class="custom-control-input" id="intituleVisibilite<?= (isset($idExpPro)) ? $idExpPro :""; ?>" <?= (isset($visibilite) && ($visibilite[$idExpPro] == true)) ? "checked" : ""; ?>> <!-- On coche si la visibiliite de l'experience pro déjà existante a été cochée  -->
        <label class="custom-control-label font-weight-bold" for="intituleVisibilite<?= (isset($idExpPro)) ? $idExpPro :""; ?>">Intitulé de l'expérience</label>
    </div>
    <div class="form-group col-sm-8">
    <!-- Si on ajoute une nouvelle expérience, la variable est vide donc on remplit les champs avec ""-->
        <input type="text" value="<?= (isset($intituleExp)) ? $intituleExp :""; ?>" class="form-control" id="intitule<?= $idExpPro ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Type d'expérience *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" value="<?= (isset($typeExp)) ? $typeExp :""; ?>" class="form-control" id="typeExp" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Date de début *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="date" value="<?= (isset($dateDebut)) ? $dateDebut :""; ?>" class="form-control" id="dateDebut<?= $dateDebut ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Date de fin</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="date" value="<?= (isset($dateFin)) ? $dateFin :""; ?>" class="form-control" id="dateFin<?= $dateFin ?>">
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Type d'organisation *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" value="<?= (isset($typeOrganisation)) ? $typeOrganisation :""; ?>" class="form-control" id="typeOrganisation<?= $typeOrganisation ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Libellé de l'organisation *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" value="<?= (isset($libelleOrganisation)) ? $libelleOrganisation :""; ?>" class="form-control" id="libelleOrganisation<?= $libelleOrganisation ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Type de poste *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" value="<?= (isset($typePoste)) ? $typePoste :""; ?>" class="form-control" id="typePoste<?= $typePoste ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Région *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" value="<?= (isset($region)) ? $region :""; ?>" class="form-control" id="region<?= $region ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Ville *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" value="<?= (isset($villeExperience)) ? $villeExperience :""; ?>" class="form-control" id="ville<?= $villeExperience ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Secteur(s) d'activité *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" value="<?= (isset($secteursActivites)) ? $secteursActivites :""; ?>" class="form-control" id="secteursActivites<?= $secteursActivites ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Domaine(s) de compétence *</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" value="<?= (isset($domainesCompetences)) ? $domainesCompetences :""; ?>" class="form-control" id="domainesCompetences<?= $domainesCompetences ?>" required />
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Description </p>
    </div>
    <div class="form-group col-sm-8">
        <input type="text" value="<?= (isset($description)) ? $description :""; ?>" class="form-control" id="description<?= $description ?>"/>
    </div>
</div>
<div class="d-flex">
    <div class="mt-2 col-sm-4">
        <p class="ml-5">Salaire</p>
    </div>
    <div class="form-group col-sm-8">
        <input type="number" value="<?= (isset($salaire)) ? $salaire :""; ?>" class="form-control" id="salaire<?= $salaire ?>"/>
    </div>
</div>

<hr class="ml-5 mr-5" style="border: 0.5px solid grey;" />