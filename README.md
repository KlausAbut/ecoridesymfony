# EcoRide

**EcoRide** est une application web de covoiturage écoresponsable développée avec Symfony 7. Elle permet aux utilisateurs de proposer et rejoindre des trajets partagés, de réduire leur empreinte carbone et de réaliser des économies.

---

## Aperçu

- Design moderne avec palette "Night Garden" (vert forêt / menthe / violet)
- Recherche de trajets en temps réel (AJAX) avec skeleton loader
- Système de crédits pour les réservations
- Chatbot assistant intégré (Claude AI)
- Interface responsive avec navigation mobile
- Mode sombre / clair

---

## Fonctionnalités

### Visiteurs
- Recherche de trajets par départ, arrivée, date, prix max, énergie, places disponibles
- Tri des résultats par date, prix ou note
- Consultation des avis membres
- Page de contact

### Utilisateurs connectés
- Inscription / connexion
- Profil avec statistiques (crédits, trajets, CO₂ économisé, note moyenne)
- Réservation de trajets (1 crédit par réservation)
- Laisser un avis après un trajet terminé
- Historique des réservations et trajets

### Conducteurs
- Enregistrer un véhicule
- Proposer, démarrer et terminer des trajets
- Gérer ses covoiturages

### Employés
- Modération des avis (valider / refuser)
- Validation des trajets

### Administrateurs
- Tableau de bord EasyAdmin
- Gestion complète des utilisateurs, trajets et avis
- Attribution des rôles

---

## Stack technique

| Composant | Technologie |
|---|---|
| Backend | Symfony 7.2 / PHP 8.2 |
| Base de données SQL | MySQL (Doctrine ORM) |
| Base de données NoSQL | MongoDB (crédits utilisateurs) |
| Frontend | Bootstrap 5.3 + Tailwind CSS |
| JavaScript | Alpine.js + Webpack Encore |
| Icônes | Lucide Icons |
| Conteneurisation | Docker |
| IA | Anthropic Claude (chatbot) |

---

## Installation

### Prérequis
- Docker & Docker Compose
- Node.js 18+

### Lancer le projet

```bash
# Cloner le dépôt
git clone <url-du-repo>
cd ecoridesymfony

# Copier et configurer l'environnement
cp .env .env.local
# Modifier .env.local avec vos valeurs (DB, MongoDB, ANTHROPIC_API_KEY)

# Démarrer les conteneurs
docker compose up -d

# Installer les dépendances PHP
docker compose exec www composer install

# Créer la base de données et appliquer les migrations
docker compose exec www php bin/console doctrine:database:create
docker compose exec www php bin/console doctrine:migrations:migrate

# Installer les dépendances JS et builder les assets
npm install
npm run build
```

L'application est accessible sur [http://localhost:8082](http://localhost:8082)
phpMyAdmin : [http://localhost:8780](http://localhost:8780)

---

## Variables d'environnement

```env
DATABASE_URL=mysql://root@mysql:3306/ecoride_symfony
MONGODB_URL=mongodb://localhost:27017
MONGODB_DB=ecoride
ANTHROPIC_API_KEY=        # Optionnel — active le chatbot IA
```

---

## Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| Admin | admin@ecoride.fr | admin |
| Employé | employe@ecoride.fr | employe |
| Conducteur | conducteur@ecoride.fr | password |
| Passager | user@ecoride.fr | password |

> Adaptez ces valeurs selon votre base de données locale.

---

## Structure du projet

```
src/
├── Controller/       # Contrôleurs (Default, Covoiturage, Avis, Chatbot…)
├── Entity/           # Entités Doctrine (User, Covoiturage, Avis, Voiture…)
├── Repository/       # Requêtes personnalisées
├── Enum/             # Statuts (CovoiturageStatut, AvisStatut…)
├── Document/         # Documents MongoDB (UserCredit)
└── Form/             # Formulaires Symfony

templates/
├── default/          # Accueil, carousel, avis
├── covoiturage/      # Liste, détail, création
├── user/             # Profil, trajets, réservations
├── partials/         # Navbar, footer
└── bundles/          # Pages d'erreur personnalisées (404…)

assets/
├── app.js            # JS principal (recherche AJAX, chatbot, toasts…)
└── styles/app.css    # CSS global + variables + composants
```

---

## Auteur

Développé par **Claudiu Abutnariti** — ESGI 2025
