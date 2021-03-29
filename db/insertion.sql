/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Permet d'insérer un premier gestionnaire en base pour pouvoir gérer des comptes
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

INSERT INTO Compte(IdCompte,NomUtilisateur, MotDePasse, AdresseMail) VALUES (1,"gestionnaire","pouet","gestion@ensc.fr");
INSERT INTO Gestionnaire(IdCompte) VALUES (1)