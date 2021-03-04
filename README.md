# projet-web1A

Pour l'instant j'ai : 
* Créé une base de donnée brouillon pour tester
* On rentre les paramètres avec un nom, pas de lien direct avec les tables experience pro et infos persos

Le mieux ça reste de faire une architecture qui ne bouge pas, quand on veut ajouter un paramètre, il ne faut pas qu'il y ait besion de modifier l'architecture de la base de données, il faut juste qu'on ait besoin d'insérer quelque chose. Donc c'est une mauvaise idée de faire un champ par attribut (nom, prenom,..) genre VisibiliteNom, VisibilitePrenom, parce que si on veut ajouter un paramètre concernant la confidentialité par exemple, ça demanderait de modifier la base de données et d'ajouter ConfidentialitéNom, ConfidentialitéPrenom,...

Donc l'idée d'avoir une table settings avec un attribut key qui prend comme valeur le nom de l'attribut, permet d'ajouter autant de paramètre qu'on veut en isnérant juste des données dans la base.


Probleme : Pour la table experiences pro, je sais pas comment on peut tout cacher, soit on ajoute un attribut visibilite dedans qui et true ou false (faisable ici vu qu'on veut masquer tout ou rien, pas possible dans infos persos vu que c'est autant de visibilité que d'attributs) Mais si on fait ça, ça casse ce qu'on fait avec settings parce que ça demanderait d'ajouter un nouvel attribut si on veut ajouter un nouveau paramètre comme la confidentialité.



A la création d'un compte il faudra inserer autant de settings que de paramètre à visibilité modifiable (on met tout en visible par défaut puis après l'utilisateur peut modifier à sa guise)


Je sais toujours pas comment faire pour gérer le cas de la table experiences pro... 
