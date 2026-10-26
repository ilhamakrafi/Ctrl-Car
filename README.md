# Ctrl+Car

Application web de covoiturage multipages permettant de publier, rechercher et réserver des trajets entre particuliers.

---

## Aperçu du projet

**Ctrl+Car** permet aux utilisateurs de :

- Proposer des trajets en covoiturage.
- Rechercher un trajet selon plusieurs critères.
- Réserver une place en ligne.
- Consulter leurs réservations depuis leur espace personnel.

---

## Fonctionnalités

- **Authentification sécurisée** : connexion avec vérification des identifiants en base de données et gestion de session PHP.
- **Liste des trajets** : affichage des trajets futurs disposant encore de places libres (`nbPlace > 0`), triés par ordre chronologique.
- **Publication de trajets** : formulaire de dépôt avec données pré-remplies et validation des informations saisies.
- **Moteur de recherche** : filtres par ville de départ, ville d’arrivée, date et prix maximum, avec recherche partielle via `LIKE`.
- **Réservation sécurisée** : décrémentation automatique du nombre de places, contrôle de disponibilité et blocage de l’auto-réservation.
- **Espace personnel** : consultation des trajets réservés via la page « Mes réservations » et des jointures SQL.

---

## Technologies utilisées

- PHP
- MySQL / MariaDB
- PDO avec requêtes préparées
- HTML / CSS
- Serveur PHP intégré

---

## Architecture et base de données

L’application repose sur une architecture PHP multipages pilotée par un routeur :

```text
configuration.php?action=...
```

La base de données est interrogée via **PDO** et des requêtes préparées afin de limiter les risques d’injection SQL.

### Structure de la base de données

Le fichier `covoit.sql` contient les tables suivantes :

| Table | Description |
|---|---|
| `Internautes` | Utilisateurs de l’application : email, mot de passe, nom, prénom et téléphone. |
| `Propositions` | Trajets proposés par les conducteurs : villes, date, nombre de places et prix. |
| `Transports` | Réservations effectuées par les passagers sur les propositions de trajet. |

### Contraintes principales

- `email` est la clé primaire de la table `Internautes`.
- `idProposition` est la clé primaire de la table `Propositions`.
- `idTransport` est la clé primaire de la table `Transports`.
- Les clés étrangères utilisent `ON DELETE CASCADE` afin de préserver la cohérence des données.
- Les dates sont stockées au format `AAMMJJ` pour faciliter les tris et comparaisons.

---

## Prérequis

Avant de lancer le projet, assurez-vous d’avoir installé :

- PHP avec l’extension `pdo_mysql`
- MySQL ou MariaDB
- Un navigateur web
- Un terminal

---

## Installation

1. Clonez le dépôt :

```bash
git clone [https://github.com/votre-compte/ctrl-car.git](https://github.com/votre-compte/ctrl-car.git)
cd ctrl-car
```

2. Créez la base de données :

```bash
mysql -u root -p
```

3. Exécutez les requêtes SQL suivantes :

```sql
CREATE DATABASE covoit CHARACTER SET utf8 COLLATE utf8_general_ci;
USE covoit;
SOURCE covoit.sql;
```

4. Configurez la connexion à la base de données dans le fichier `config.php` :

```php
$dsn = 'mysql:host=localhost;dbname=covoit;charset=utf8';
$username = 'root';
$password = 'votre_mot_de_passe';
```

---

## Lancement

Démarrez le serveur PHP local depuis la racine du projet :

```bash
php -S localhost:8888
```

Puis accédez à l’application via les URLs suivantes :

| Page | URL |
|---|---|
| Connexion | `http://localhost:8888/login.html` |
| Liste des trajets | `http://localhost:8888/configuration.php?action=liste` |
| Recherche | `http://localhost:8888/configuration.php?action=rechercher` |
| Mes réservations | `http://localhost:8888/configuration.php?action=mes_reservations` |

---

## Améliorations apportées

| Problème initial | Correction apportée |
|---|---|
| Trajets complets encore visibles | Ajout de la condition `nbPlace > 0` dans les requêtes et dans l’`UPDATE` de réservation. |
| Auto-réservation possible | Contrôle empêchant un conducteur de réserver son propre trajet. |
| Recherche trop stricte | Utilisation de `LIKE '%texte%'` pour autoriser une recherche partielle. |
| Trajets passés affichés | Filtre chronologique `date >= :today` appliqué aux requêtes. |

