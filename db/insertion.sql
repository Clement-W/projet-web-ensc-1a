/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Permet d'insérer un premier gestionnaire en base pour pouvoir gérer des comptes
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

INSERT INTO Compte(IdCompte,NomUtilisateur, MotDePasse, AdresseMail) VALUES (1,"gestionnaire",SHA2("pouet",512),"gestion@ensc.fr");
/* SHA2 fonctionne uniquement pour les versions supérieures ou égales à 5.5 de MySQL */
INSERT INTO Gestionnaire(IdCompte) VALUES (1)