# EcoRide

**EcoRide** est une application Symfony de covoiturage écoresponsable.

---

## 🚗 Fonctionnalités principales

* Recherche de trajets en AJAX sans rechargement de page
* Création de covoiturages pour les conducteurs
* Participation à un trajet pour les passagers
* Système de connexion/inscription
* Espace personnel pour voir ses trajets / réservations
* Zone d'administration pour validation des trajets et des avis
* Laisser un avis sur un covoiturage terminé

---

## ⚙️ Installation locale

### 1. Cloner le projet

```bash
git clone https://github.com/KlausAbut/ecoridesymfony.git
cd ecoridesymfony
```

### 2. Lancer Docker

```bash
docker compose up -d
```

### 3. Installer les dépendances PHP

```bash
docker compose exec www composer install
```

### 4. Créer la base de données et charger les fixtures

```bash
docker compose exec www php bin/console doctrine:database:create

docker compose exec www php bin/console doctrine:migrations:migrate

docker compose exec www php bin/console doctrine:fixtures:load
```

### 5. Installer les dépendances front

```bash
npm install
npm run build
```

### 6. Accéder au site

[http://localhost:8080](http://localhost:8080)

---

## 📊 Stack technique

* Symfony 6.4
* PHP 8.2
* MySQL / Doctrine
* Webpack Encore
* Bootstrap 5 + Tailwind CSS mixé
* JavaScript vanilla + fetch/ajax

---

## 🤖 Accès de démo

* Admin : `admin@ecoride.com` / `admin`
* Utilisateur : `user@ecoride.com` / `user`

---

## 🔧 Fonctionnalités à venir

* Paiement en ligne
* Carte interactive des trajets (Leaflet ou Google Maps)
* Sélection des places disponibles
* Notification e-mail à la validation d’un trajet

---

## 🚧 Auteur

> Ce projet a été réalisé par [@KlausAbut](https://github.com/KlausAbut) et [@Saritahh](https://github.com/Saritahh).

---

## 📋 Check-list déploiement Heroku (manuel)

### ✅ Prérequis

* Créer un compte Heroku
* Installer la CLI Heroku
* Ajouter un fichier `Procfile`, `composer.json`, `package.json`, `postcss.config.js` bien configurés

### 🚀 Déploiement rapide

```bash
heroku create ecoride-demo --buildpack heroku/php
heroku addons:create heroku-postgresql:hobby-dev

git push heroku main
heroku run php bin/console doctrine:migrations:migrate
```

### ⚠️ Attention

* Pensez à configurer les variables d'environnement (`APP_ENV`, `APP_SECRET`, `DATABASE_URL`, `MAILER_DSN`, etc.)
* Les assets doivent être compilés avant déploiement (`npm run build` + `php bin/console asset-map:compile` si utile)

