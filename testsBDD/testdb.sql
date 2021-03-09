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
   PRIMARY KEY(IdEleve),
   UNIQUE(IdCompte),
   FOREIGN KEY(IdCompte) REFERENCES Compte(IdCompte)
);

CREATE TABLE Settings(
   IdSettings INT,
   nomParam VARCHAR(50),
   visibilite BOOLEAN,
   IdEleve INT,
   PRIMARY KEY(IdSettings),
   FOREIGN KEY(IdEleve) REFERENCES Eleve(IdEleve)
);

CREATE TABLE InfosPerso(
   IdInfosPersos INT,
   nom VARCHAR(50),
   prenom VARCHAR(50),
   promo INT,
   IdEleve INT NOT NULL,
   PRIMARY KEY(IdInfosPersos),
   UNIQUE(IdEleve),
   FOREIGN KEY(IdEleve) REFERENCES Eleve(IdEleve)
);

CREATE TABLE ExperiencesPro(
   IdExperiencePro INT,
   salaire DOUBLE,
   IdEleve INT,
   PRIMARY KEY(IdExperiencePro),
   FOREIGN KEY(IdEleve) REFERENCES Eleve(IdEleve)
);


grant all privileges on testWebUsers2.* to 'user'@'localhost' identified by 'pass';
