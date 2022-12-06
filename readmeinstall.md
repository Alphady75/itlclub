1) symfony check:requirements  ==> verifie que symfony est bien installé avec tous les packages necessaires. Si ce n'est pas le cas il liste ce qu'il faut installer
2) composer install  ==> installe toutes les dépendances de composer utile au projet symfony
3) npm install  ===> installe les dépendances npm utiles au projet 
4) créer la database mysql
5) modifier le fichier .env   ===> DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
                              ===> MAILER_DSN=smtp://user:pass@smtp.example.com:port
6) supprimer les fichiers "Version..." dans le dossier migrations puis taper bin/console make:migration
7) php bin/console doctrine:migrations:migrate  ===> créer les tables et les champs dans la base de donnée
8) si erreur de fichier manifest.json   ===> yarn encore dev --watch 
9) symfony server:start




Creer un user admin: 

1) créer une agence  ====>  insert into agency (name, created, updated) values ('Toulouse', STR_TO_DATE('19/07/2022 10:10:15','%d/%m/%Y %H:%i:%s'), STR_TO_DATE('15/01/2017 10:10:15','%d/%m/%Y %H:%i:%s'));

2) ajouter un comptoir lié à l'agence (verifier que 'agency_id' s'est bien enregistrer en id 1 sinon changer l'id dans la requete) ===> insert into agence_adress (agence_id, number, street, postal_code, city, created, updated) values (1, 0505050505, '102 rue du lac', '31000', 'labege', STR_TO_DATE('19/07/2022 10:10:15','%d/%m/%Y %H:%i:%s'),STR_TO_DATE('19/07/2022 10:10:15','%d/%m/%Y %H:%i:%s'));

3) créer un utilisateur en utilisant le formulaire d'adherent du site  ===> bouton s'inscrire de la barre de navigation ou '/integrer-le-reseau'

4) une fois l'utilisateur créer, il reste juste a modifier son role dans la bdd (verifier que l'id correspond bien a l'user) ===> update user set roles = '["ROLE_ADMIN"]' where id = 1;

4) acceder à la partie admin/dashboard ===> /admin/dashboard



