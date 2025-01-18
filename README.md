
# API de Gestion de Profils
## Description

Cette API permet de gérer des profils en offrant un CRUD complet pour l'entité profil. L'accès à ces fonctionnalités est restreint à un administrateur authentifié, sauf pour une route publique qui liste les profils actifs (status = 1).

## Fonctionnalités

- CRUD complet pour sur l'entité profils : Créer, lire, mettre à jour et supprimer des profils, par un administrateur authentifié.

- Route publique : Liste tous les profils actifs (status = 1).

- Restriction d'accès : Les profils inactifs (status ≠ 1) sont uniquement accessibles par un administrateur authentifié.
  
- Accès à la documentation complète de l'API pour voir et tester les différents endpoints directement depuis le navigateur via Swagger.  
  
> **Note importante** : Pour le Update et la Creation de Profil, vous ne pourrez pas ajouter l'image depuis swagger, pour tester correctement ces deux routes en insérerant les images, utilisez un outil comme **postman** ou autre qui permet d'envoyer les données sous forme de paire clé-valeur, les routes restent fonctionnelles car l'image est optionnelle lors de la creation ou le update.

  
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

## Preview

## Installation

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

