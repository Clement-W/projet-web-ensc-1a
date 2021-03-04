CREATE TABLE InfosPerso(
   IdInfosPersos INT,
   nom VARCHAR(50),
   prenom VARCHAR(50),
   promo VARCHAR(50),
   PRIMARY KEY(IdInfosPersos)
);

CREATE TABLE Compte(
   IdCompte INT,
   username VARCHAR(50),
   password VARCHAR(50),
   PRIMARY KEY(IdCompte)
);

CREATE TABLE Gestionnaire(
   IdGestionnaire INT,
   IdCompte INT NOT NULL,
   PRIMARY KEY(IdGestionnaire),
   UNIQUE(IdCompte),
   FOREIGN KEY(IdCompte) REFERENCES Compte(IdCompte)
);

CREATE TABLE Eleve(
   IdEleve INT,
   IdCompte INT NOT NULL,
   IdInfosPersos INT NOT NULL,
   PRIMARY KEY(IdEleve),
   UNIQUE(IdCompte),
   UNIQUE(IdInfosPersos),
   FOREIGN KEY(IdCompte) REFERENCES Compte(IdCompte),
   FOREIGN KEY(IdInfosPersos) REFERENCES InfosPerso(IdInfosPersos)
);

CREATE TABLE Settings(
   IdSettings INT,
   nomParam VARCHAR(50),
   visibilite VARCHAR(50),
   IdEleve INT,
   PRIMARY KEY(IdSettings),
   FOREIGN KEY(IdEleve) REFERENCES Eleve(IdEleve)
);

CREATE TABLE ExperiencesPro(
   IdExperiencePro INT,
   oui VARCHAR(50),
   IdEleve INT,
   PRIMARY KEY(IdExperiencePro),
   FOREIGN KEY(IdEleve) REFERENCES Eleve(IdEleve)
);
