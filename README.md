
# API de Gestion de Profils
## Description

Cette API permet de gérer des profils en offrant un CRUD complet pour l'entité profil. L'accès à ces fonctionnalités est restreint à un administrateur authentifié, sauf pour une route publique qui liste les profils actifs (status = 1).

## Fonctionnalités

- CRUD complet pour sur l'entité profils : Créer, lire, mettre à jour et supprimer des profils, par un administrateur authentifié.

- Route publique : Liste tous les profils actifs (status = 1).

- Restriction d'accès : Les profils inactifs (status ≠ 1) sont uniquement accessibles par un administrateur authentifié.
  
- Accès à la documentation complète de l'API pour voir et tester les différents endpoints directement depuis le navigateur via Swagger.  
  
> **Note importante** : Pour le Update et la Creation de Profil, vous ne pourrez pas ajouter l'image depuis swagger, pour tester correctement ces deux routes en insérerant les images, utilisez un outil comme **postman** ou autre qui permet d'envoyer les données sous forme de paire clé-valeur,mais les routes restent fonctionnelles car l'image est optionnelle lors de la creation ou le update.

  
## Technologies utilisées

-  **Framework** : Laravel 11
-  **Base de données** : MySQL
-  **Versionning** : Git et GitHub
-  **Environnement local** : Laragon

## Prérequis

- PHP >= 8.3

- Composer

- MySQL

- Git

- Laravel installé localement

## Installation

### 1 - Cloner le projet
git clone <URL_DU_REPO>

### 2 -  Entrer dans le répertoire cloné si necessaire
cd Repertoire

### 3 - Installer les dépendances
composer install

### 4 - Configurer l'environnement de développement et de testing
cp .env.example .env  
cp .env.example .env.testing

DB_CONNECTION=mysql   
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=nom_de_votre_base  
DB_USERNAME=nom_utilisateur  
DB_PASSWORD=mot_de_passe  

APP_ENV=testing  
DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=nom_de_votre_base  
DB_USERNAME=nom_utilisateur  
DB_PASSWORD=mot_de_passe  

### 5 -  Générer la clé d'application
php artisan key:generate


### 6 - Génerer la clé secrète JWT
php artisan jwt:secret

> **Note importante** :la clé sera ajoutée dans .env, copier cette clé et ajouter la dans le .env.testing également.

### 7 - Exécuter les migrations

php artisan migrate  
php artisan migrate --env=testing , pour l'environnement de test

### 8 - Démarrer le serveur
php artisan serve















## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

