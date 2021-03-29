/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Permet de créer la base de données et un utilisateur pouvant se connecter
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

create database if not exists annuaireEleves character set utf8 collate utf8_unicode_ci;
use annuaireEleves;

grant all privileges on annuaireEleves.* to 'annuaireUser'@'localhost' identified by 'explodingkittens';
