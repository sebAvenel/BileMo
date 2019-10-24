# BileMo
Projet numéro 7 de ma formation PHP/Symfony chez Openclassrooms qui consiste à créer une API pour BileMo afin de développer 
leur vitrine de téléphones mobiles.

## Description du projet

Voici les principales fonctionnalités disponibles demandées par le client:

  * consulter la liste des produits BileMo ;
  * consulter les détails d’un produit BileMo ;
  * consulter la liste des utilisateurs inscrits liés à un client sur le site web ;
  * consulter le détail d’un utilisateur inscrit lié à un client ;
  * ajouter un nouvel utilisateur lié à un client ;
  * supprimer un utilisateur ajouté par un client.
  
## Contraintes

Les clients de l’API doivent être authentifiés via Oauth ou JWT.

## Contrôle du code

La qualité du code a été validé par [Code climate](https://codeclimate.com/). Vous pouvez accéder au rapport de contrôle en cliquant sur le badge ci-dessous.

[![Maintainability](https://api.codeclimate.com/v1/badges/f67b31627779d2a8877b/maintainability)](https://codeclimate.com/github/sebAvenel/BileMo/maintainability)

## Prérequis

Php ainsi que Composer doivent être installés sur votre ordinateur afin de pouvoir correctement lancé l'API.

## Installation

  * Téléchargez et dézipper l'archive. Installer le contenu dans le répertoire de votre serveur:
      * Wamp : Répertoire 'www'.
      * Mamp : Répertoire 'htdocs'.
      
  * Renommer le fichier '.env-dist' se trouvant à la racine du projet en '.env' puis y configurer les lignes DATABASE_URL.
  
  * Ensuite placez-vous dans votre répertoire par le biais de votre console de commande (ou terminal) et renseignez la commande suivante:
      * ```bash
        'composer install' pour windows.
        ```
      * ```bash
        'php composer.phar install' pour Mac OS.
        ```
    
* Création de la base de données:

    ```bash
    php bin/console doctrine:database:create
    ```

    ```bash
    php bin/console make:migration
    ```

    ```bash
    php bin/console doctrine:migrations:migrate
    ```
    
* Création de données fictives pour tester le site:

    ```bash
    php bin/console doctrine:fixtures:load
    ```
    
* Démarrage du serveur de symfony:
  
    ```bash
    php bin/console server:run
    ```

## Liste des commandes CURL à exécuter dans le terminal ([jq](https://stedolan.github.io/jq/download/) doit être installé):

* Authentification:

    ```bash
    TOKEN=$(curl -s -X POST -H 'Accept: application/json' -H 'Content-Type: application/json' --data '{"username":"Orange" ,"password" : "passwordOrange"}' http://localhost:8000/api/login_check | jq -r '.token')
    ```
    * Il est aussi possible de s'authentifier avec différents identifiants:
      * "username":"Bouygues" ,"password" : "passwordBouygues"
      * "username":"SFR" ,"password" : "passwordSFR"
      
* Réupérer la liste des téléphones:

    ```bash
    curl -X GET -H 'Accept: application/json' -H "Authorization: Bearer $TOKEN" http://localhost:8000/api/products
    ```
* Réupérer la liste des téléphones par page:

    ```bash
    curl -X GET -H 'Accept: application/json' -H "Authorization: Bearer $TOKEN" http://localhost:8000/api/products?page=2
    ```
* Réupérer un téléphone:

    ```bash
    curl -X GET -H 'Accept: application/json' -H "Authorization: Bearer $TOKEN" http://localhost:8000/api/product/1
    ```
* Réupérer la liste des utilisateurs:

    ```bash
    curl -X GET -H 'Accept: application/json' -H "Authorization: Bearer $TOKEN" http://localhost:8000/api/users
    ```
* Réupérer la liste des utilisateurss par page:

    ```bash
    curl -X GET -H 'Accept: application/json' -H "Authorization: Bearer $TOKEN" http://localhost:8000/api/users?page=2
    ```
* Réupérer un utilisateur:

    ```bash
    curl -X GET -H 'Accept: application/json' -H "Authorization: Bearer $TOKEN" http://localhost:8000/api/user/1
    ```
    
* Ajouter un utilisateur:

    ```bash
    curl -X POST -H "Content-Type: application/json" -d '{"firstName":"John","lastName":"Doe","email":"john.doe@outlook.fr","phone":"0123456789","address":"78 rue magenta 14000 CAEN"}' -H "Authorization: Bearer $TOKEN" http://localhost:8000/api/user/add
    ```
    
* Supprimer un utilisateur:

    ```bash
    curl -X DELETE -H "Authorization: Bearer $TOKEN" http://localhost:8000/api/user/delete/102
    ```
      
      
  
