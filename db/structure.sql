drop table if exists ExperiencePro;
drop table if exists InfosPerso;
drop table if exists Parametres;
drop table if exists Gestionnaire;
drop table if exists Eleve;
drop table if exists Compte;

CREATE TABLE Compte(
   IdCompte INT AUTO_INCREMENT,
   NomUtilisateur VARCHAR(50),
   MotDePasse VARCHAR(50),
   AdresseMail VARCHAR(50),
   PRIMARY KEY(IdCompte)
);

CREATE TABLE Gestionnaire(
   IdGestionnaire INT AUTO_INCREMENT,
   IdCompte INT NOT NULL,
   PRIMARY KEY(IdGestionnaire),
   UNIQUE(IdCompte),
   FOREIGN KEY(IdCompte) REFERENCES Compte(IdCompte)
);

CREATE TABLE Eleve(
   IdEleve INT AUTO_INCREMENT,
   CompteValide BOOLEAN,
   IdCompte INT NOT NULL,
   PRIMARY KEY(IdEleve),
   UNIQUE(IdCompte),
   FOREIGN KEY(IdCompte) REFERENCES Compte(IdCompte)
);

CREATE TABLE Parametres(
   IdParametres INT AUTO_INCREMENT,
   LibelleInformation VARCHAR(30),
   Visibilite BOOLEAN,
   IdEleve INT,
   PRIMARY KEY(IdParametres),
   FOREIGN KEY(IdEleve) REFERENCES Eleve(IdEleve)
);

CREATE TABLE InfosPerso(
   IdInfosPerso INT AUTO_INCREMENT,
   Nom VARCHAR(50),
   Prenom VARCHAR(50),
   Genre VARCHAR(10),
   Promotion INT,
   Adresse VARCHAR(50),
   Ville VARCHAR(50),
   CodePostal INT,
   NumTelephone INT,
   IdEleve INT NOT NULL,
   PRIMARY KEY(IdInfosPerso),
   UNIQUE(IdEleve),
   FOREIGN KEY(IdEleve) REFERENCES Eleve(IdEleve)
);

CREATE TABLE ExperiencePro(
   IdExperiencePro INT AUTO_INCREMENT,
   IntituleExperiencePro VARCHAR(50),
   TypeExperiencePro VARCHAR(50),
   DateDebut DATE,
   DateFin DATE,
   TypeOrganisation VARCHAR(50),
   LibelleOrganisation VARCHAR(50),
   TypePoste VARCHAR(50),
   Region VARCHAR(50),
   Ville VARCHAR(50),
   SecteursActivites VARCHAR(50),
   DomainesCompetences VARCHAR(50),
   Description VARCHAR(400),
   Salaire DOUBLE,
   IdEleve INT,
   PRIMARY KEY(IdExperiencePro),
   FOREIGN KEY(IdEleve) REFERENCES Eleve(IdEleve)
);
