INSERT INTO `Compte`(`NomUtilisateur`, `MotDePasse`) VALUES ("username1","password1");
INSERT INTO `Compte`(`NomUtilisateur`, `MotDePasse`) VALUES ("username2","password2");

INSERT INTO `Eleve`(`IdCompte`) VALUES (1);

INSERT INTO `Gestionnaire`(`IdCompte`) VALUES (2);

INSERT INTO `InfosPerso`(`Nom`, `Prenom`, `Genre`, `Promotion`, `Adresse`, `Ville`, `CodePostal`, `AdresseMail`, `NumTelephone`, `IdEleve`) VALUES ("Carena","Emma","F",2023,"2 rue de la poisse","Tonlence",30290,"emma.mcarena@coldmail.com",0622222222,1);

INSERT INTO `ExperiencePro`(`TypeExperiencePro`, `DateDebut`, `DateFin`, `TypeOrganisation`, `Lieu`, `SecteursActivites`, `DomainesCompetences`, `Description`, `Salaire`, `IdEleve`) VALUES ("Stage","2019-04-22","2019-06-22","Laboratoire","Tonlence","Santé","IA","Stage visant à étudier et expliciter les processus danalyse explcites",3942,1);

INSERT INTO `ExperiencePro`(`TypeExperiencePro`, `DateDebut`, `DateFin`, `TypeOrganisation`, `Lieu`, `SecteursActivites`, `DomainesCompetences`, `Description`, `Salaire`, `IdEleve`) VALUES ("Stage","2018-04-22","2019-06-22","Entreprise","Bordaxe","Commerce","IHM","Stage visant à commercer avec le commerce",3942,1);


-- Paramètres pour les infos personnelles
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("Nom",true,1);
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("Prenom",true,1);
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("Genre",false,1);
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("Promotion",true,1);
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("Adresse",false,1);
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("Ville",true,1);
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("CodePostal",true,1);
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("AdresseMail",false,1);
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("NumTelephone",false,1);

-- Paramètres pour les experiences pro
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("1",true,1) 
INSERT INTO `Parametres`(`LibelleInformation`, `Visibilite`, `IdEleve`) VALUES ("2",true,1) 
