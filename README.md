# EcoRide 

Plateforme web de covoiturage écologique développée dans le cadre du projet Studi Graduate Développeur Angular 2023-2029
Promo SEPTEMBRE-OCTOBRE 2026

## Lien du site
http://ecoride-app.infinityfree.me

## Description
EcoRide est une plateforme permettant de mettre en relation des chauffeurs et des passagers pour des trajets en covoiturage, en privilégiant les véhicules électriques via un label "Voyage Éco".

## Rôles utilisateurs
- **Visiteurs** — Recherche et consultation des trajets
- **Passagers** — Réservation de trajets via un système de crédits
- **Chauffeurs** — Proposition de trajets avec infos véhicule, démarrage et clôture du trajet
- **Employés** — Modération des avis
- **Administrateur** — Gestion des comptes et statistiques

## Identifiants de test
- Rôle Utilisateur
- Mail test@test.fr
- Password Test1234

## Stack technique
- **Frontend** : HTML5, Bootstrap 5, JavaScript
- **Backend** : PHP
- **Base de données** : MySQL
- **Déploiement** : InfinityFree

## 📁 Structure du projet

index.php -> Page d'accueil 

connexion.php -> Connexion utilisateur 

inscription.php -> Création de compte 

covoiturage.php -> Recherche de trajets 

detail.php -> Détail d'un trajet 

reservation.php -> Réservation + crédits 

historique.php -> Historique des réservations

proposer_trajet.php -> Proposer un trajet (chauffeur)

employe.php -> Espace employé 

admin.php -> Espace administrateur 

contact.php -> Page contact 

mentions_legales.php -> Mentions légales 

includes/db.php -> Connexion base de données 

includes/header.php -> Header commun 

includes/footer.php -> Footer commun 

ecoride.sql -> Structure de la base de données 

## Base de données
Importer le fichier "ecoride.sql" dans phpMyAdmin.

## Installation en local
1. Cloner le repo dans "C:/xampp/htdocs/"
2. Importer "ecoride.sql" dans phpMyAdmin
3. Modifier "includes/db.php" avec vos identifiants MySQL
4. Lancer Apache et MySQL dans XAMPP
5. Accéder à "http://localhost/ecoride/"

## Sécurité
- Mots de passe hashés avec "password_hash()"
- Validation du mot de passe : minimum 8 caractères, une majuscule et un chiffre obligatoires
- Requêtes préparées PDO (protection injections SQL)
- Vérification des sessions sur toutes les pages protégées
- Protection XSS via "echo" sécurisé

## Fonctionnalités partiellement implémentées
- **Envoi de mails** : Le code est en place (mail() PHP) mais nécessite une configuration SMTP en production
- **Filtres covoiturage** : Filtres par prix, durée et note prévus mais non implémentés
- **NoSQL MongoDB** : Prévu pour les avis mais non implémenté faute de temps
- **Graphiques admin** : Les données sont affichées mais la visualisation en graphiques est prévue et non implémentée
- **Boutons Démarrer/Arriver** : Implémentés dans l'historique, les mails aux participants post-trajet sont prévus mais non implémentés

## ℹInformations diverses
- Démarrage du projet : 6 mai 2026
- Fin du projet : 21 mai 2026
- Durée du projet : 81 heures
- Source palette couleurs : https://www.shutterstock.com/fr/blog/design-durable-palettes-couleurs-pour-arreter-greenwashing-de-marque

## Auteur
Alves David — Projet Studi 2026