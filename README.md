# Take'A'Vet (SAE 3.01)

![Banner](Banner.png)

Application de gestion d'emploi du temps, de rendez-vous, d'animaux pour vétérinaire et clients en Symfony.

## Table des matières

<!-- TOC -->
* [Take'A'Vet (SAE 3.01)](#takea--vet--sae-301-)
  * [Table des matières](#table-des-matires)
  * [Les auteurs du projet](#les-auteurs-du-projet)
  * [Les outils](#les-outils)
  * [Pour commencer](#pour-commencer)
  * [Autres Commandes](#autres-commandes)
  * [Données](#donnes)
    * [Identifiants](#identifiants)
  * [Fonctionnalités](#fonctionnalits)
    * [Vétérinaire](#vtrinaire)
    * [Client](#client)
    * [Admin](#admin)
  * [Aperçu](#aperu)
    * [Accueil](#accueil)
    * [Planning](#planning)
    * [Page de contact](#page-de-contact)
  * [Production](#production)
    * [Base de données](#base-de-donnes)
<!-- TOC -->

## Les auteurs du projet

- Alexis Udycz
- Vincent Guillemot
- Clément Perrot
- Romain Leroy
- Benoit Soulière

## Les outils

- Symfony
- PhpCsFixer
- Codeception
- Zenstruck/foundry
- EasyAdmin
- Orm-fixtures
- EasyAdmin2
- Imagine
- FontAwesome

## Pour commencer

- Installation du projet
```shell
git clone https://iut-info.univ-reims.fr/gitlab/udyc0001/sae3-01.git ./takeavet
cd ./takeavet/
# Pour installer toutes les dépendances du projets
composer install 
```

- Configuration du projet.

Dans le fichier `.env.local`, indiquez votre URL de base de données.

```ini
DATABASE_URL="mysql://root:admin@127.0.0.1:3306/TAKEAVET_DEV?serverVersion=8&charset=utf8mb4"
```

Avec cette suite de commandes.

```shell
cp .env .env.local

# Modifier le fichier .env.local avec votre DATABASE_URL

composer migrate
composer db
```

- Lancer le projet dans l'environnement de développement..

| Avec Composer    | Avec Symfony Console |
|------------------|----------------------|
| `composer start` | `symfony serve`      |
## Autres Commandes

- Vérifier le code avec l'outil Cs Fixer :
```shell
composer test:cs
```

- Corriger le code avec l'outil PHP-CS-Fixer.
```shell
composer fix:cs
```

- Créer une nouvelle migration pour la base de données.
```shell
composer migrate
```

- Générer des données factices.
```shell
composer db
```

- Générer tous les tests.
```shell
composer test
```
## Données

### Identifiants

Voici la liste des identifiants qui sont générés par les fixtures dans le projet.

| Email               | Mot de passe | Type de compte      |
|---------------------|--------------|---------------------|
| admin@take.vet      | admin        | Client (ROLE_ADMIN) |
| client-XXX@take.vet | test         | Client              |
| veto-XXX@take.vet   | test         | Vétérinaire         |

Fichiers de données

| Fichier                                            | Description                                                                                           |
|----------------------------------------------------|-------------------------------------------------------------------------------------------------------|
| [animals.json](src/DataFixtures/data/animals.json) | Liste de nom d'animaux pour la factory [CategoryFixtures.php](src/DataFixtures/CategoryFixtures.php). |
## Fonctionnalités

- [X] Gestion automatisée de rendez-vous.
- [X] Gestion et affichage des plannings.
- [X] Foire aux questions pour utilisateur et vétérinaire
- [X] Gestion de vos animaux et de leurs vaccins.
- [ ] ...

### Vétérinaire
Si vous êtes connecté en tant que Vétérinaire vous pourrez créer des récapitulatifs des animaux, modifier votre planning etc...
### Client
En tant que client vous pouvez enregistrer vos animaux, voir leurs possible récapitulatifs, voir le planning des vétérinaires et prendre rendez vous.
### Admin
En tant qu'administrateur, vous avez accès au dashboard easyadmin, ce qui vous permet d'ajouter facilement des utilisateurs, des animaux et de supprimer des messages dans la foire aux questions, ainsi que de nombreuses autres possibilités.

## Aperçu
### Accueil
![l'Accueil du site](/Accueil.PNG)
### Planning
![Planning](/planning.png)
### Page de contact
![Contact](/contact.png)

## Production

Le serveur est accessible à l'adresse [http://10.31.11.105/](http://10.31.11.105/) et est configuré pour utiliser PHP 8.1 et Apache 2.

| Username | Password |
|----------|----------|
| sae      | iutinfo  |

```shell
ssh sae@10.31.11.105
```

Répertoire important pour la configuration du serveur :
- `/var/www/takeavet.fr/` Répertoire du projet symfony en production.
- `/var/www/takeavet.fr/.env.local.php` Configuration en production, créer par le `composer dump-env prod`
- `/etc/apache2/sites-available/takeavet.fr.conf` Configuration du serveur Apache2 pour le site.

### Base de données

| Email                        | Password   | Type             |
|------------------------------|------------|------------------|
| admin@take.vet               | *          | :\Lk13s`/Q>4unp  | Administrateur |
| marguerite.hamel@exemple.com | testtest   | Vétérinaire      |
| emile.marleau@exemple.com    | {<E5tG{r>5 | Client           |

* : R9ei|:\Lk13s`/Q>4unp 

