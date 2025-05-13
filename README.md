# EcoRide

**EcoRide** est une application Symfony de covoiturage écoresponsable, développée dans le cadre du TP DWWM.

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
tcd ecoridesymfony
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

> Ce projet a été réalisé par [@KlausAbut](https://github.com/KlausAbut).
