E-Contact
=========

> Une petite application de carnet d'adresses

Instalation
-----------

Pour installer E-Contact sur votre serveur commencer par **créer un fichier .env** à la racine du projet.

```
# Exemple de fichier .env
CONTEXT='DEV'
BDD_NAME=e_contact
BDD_HOST=localhost
BDD_PORT=8889
BDD_USER=contact
BDD_PASS=contactPass
```

Ensuite **installer les dependances avec composer**

```bash
composer install
```

Puis effectuer la migration de la base de données

```bash
php bin/cli.php migrations lancer
```

Et voila c'est déjà fini

> vous pouvez profiter de votre carnet d'adresse sur votre serveur, si c'est pas beau :cry:

Amélioration à venir
--------------------

- [ ] Ajouter une historisation des migrations
- [ ] Ajouter la generation des commandes revert pour les migrations
- [ ] Ajouter la commande migrations revert à l'utilitaire de migration cli 
- [ ] Passer le generateur de php et de mysql en template moustache
- [ ] Ajouter un générateur de test unitaire phpunit
- [ ] Passer à php 7.2
- [ ] Ajouter un générateur de module
- [ ] Ajouter un utilitaire cli pour gérer les modules
- [ ] Ajouter generateur de views pour un backoffice CRUD automatique
- [ ] Ajouter generateur de modules user spécifique
- [ ] Ajouter generateur de routes pour le CRUD des modules
- [ ] Ajouter generateur de routes pour les pages du backoffice CRUD aut omatique
- [ ] Ajouter un générateur de documentation interactives pour les modules