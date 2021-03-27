# projet-web1A

Pour l'instant j'ai : 
* Créé une base de donnée brouillon pour tester
* On rentre les paramètres avec un nom, pas de lien direct avec les tables experience pro et infos persos

Le mieux ça reste de faire une architecture qui ne bouge pas, quand on veut ajouter un paramètre, il ne faut pas qu'il y ait besion de modifier l'architecture de la base de données, il faut juste qu'on ait besoin d'insérer quelque chose. Donc c'est une mauvaise idée de faire un champ par attribut (nom, prenom,..) genre VisibiliteNom, VisibilitePrenom, parce que si on veut ajouter un paramètre concernant la confidentialité par exemple, ça demanderait de modifier la base de données et d'ajouter ConfidentialitéNom, ConfidentialitéPrenom,...

Donc l'idée d'avoir une table settings avec un attribut key qui prend comme valeur le nom de l'attribut, permet d'ajouter autant de paramètre qu'on veut en isnérant juste des données dans la base.


Probleme : Pour la table experiences pro, je sais pas comment on peut tout cacher, soit on ajoute un attribut visibilite dedans qui et true ou false (faisable ici vu qu'on veut masquer tout ou rien, pas possible dans infos persos vu que c'est autant de visibilité que d'attributs) Mais si on fait ça, ça casse ce qu'on fait avec settings parce que ça demanderait d'ajouter un nouvel attribut si on veut ajouter un nouveau paramètre comme la confidentialité.



A la création d'un compte il faudra inserer autant de settings que de paramètre à visibilité modifiable (on met tout en visible par défaut puis après l'utilisateur peut modifier à sa guise)


Je sais toujours pas comment faire pour gérer le cas de la table experiences pro...



On a trouvé comment gérer le problème de l'experience pro, on va utiliser IdeXPERIENCEpRO pour identifier les experiences pro et gerer leur visibilité. On a dissocié la table eleve et gestionnaire du compte pour pouvoir faire en sorte qu'un éleve a des experiences pro et infos persos et pas un gestionnaire.


Informations à se souvenir : 
QUand quelqu'un recherche des profils, il faut vérifier que ceux-ci sont bien validés par un gestionnaire car même ceux qui sont pas validés auront un profil. Ce qu'on peut faire pour eviter que quelqu'un accède à un profil depuis l'url c'est verifier dans la variable session que le compte connecté est un gestionnaire 

Pour savoir si un compte connecté est un gestionnaire ou non, il faudra faire une mtéhode qui fait un select de l'id compte avec l'username, puis ensuite on peut select dans eleve ou dans gestionnaire idCompte pour voir dans quelle table est cet id et donc savoir si c'est un élève ou un gestionnaire


QUAND un gestionnaire refuse l'inscription d'un éleve en appuyant sur la croix, ça envoie un mail qui lui dit qu'il a été refusé puis ça le supprime de la bdd



À mettre dans TOUS les fichiers : 

/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier :
 * Permet de créer la database ainsi que d'allouer tous les privilèges dessus pour un utilisateur donné
 *
 * À ajouter en premier pour la création de la DB
 *
 * Copyright 2020, BINET Coline et PERRIER Alban
 * https://ensc.bordeaux-inp.fr/fr
 *
 */ 



TODO : 
* bouton navbar couleur (????min)

* probleme du search filter (envie de canner) (???min)

* probleme de la page modifier profil : il y a un espace blanc en dessous du bouton enregistrer quand on a des experiences pro(???min)

* verifier la portabilité de tout


* faire en sorte que le gestionnaire puisse modifier son profil

---

2h :
* commenter
* relire le code
* ajouter les entetes de chaque fichier avec une explication du fichier



rapport :
dire que la visibilté des infos dans profil n'est pas grisée si c'est invisible mais on y a pensé 