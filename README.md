# 🎉 MiniEvent - Application Web de Gestion de Réservations d'Événements

## 📋 Description
Application web complète permettant aux utilisateurs de consulter des événements et de réserver en ligne, et à un administrateur de gérer les événements et les réservations via une interface sécurisée.

## 🛠️ Technologies Utilisées
- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 8.2 (Architecture MVC)
- **Base de données**: MySQL 8.0
- **Conteneurisation**: Docker & Docker Compose
- **Versioning**: Git & GitHub

## 👥 Équipe de Développement
- [Fourat Jebali] - [fouratcs@gmail.com/fouratjebali]
- [Mohamed Amin Neji] - [mohamedneji2025@gmail.com/AminNeji]
- [Salim Halila] - [salimhalila@gmail.com/salimhalila] 

## 🚀 Installation et Configuration

### Prérequis
- Docker Desktop installé
- Git installé

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone [URL_DU_REPO]
cd MiniProjet2A-EventReservation-NomEquipe
```

2. **Lancer Docker**
```bash
docker-compose up -d
```

3. **Accéder à l'application**
- Application principale: http://localhost:8080
- phpMyAdmin: http://localhost:8081
  - Serveur: mysql
  - Utilisateur: root
  - Mot de passe: rootpassword

### Identifiants Admin par défaut
- **Username**: admin
- **Password**: admin123

## 📁 Structure du Projet
```
MiniEvent/
├── app/
│   ├── models/          # Classes métier (Event, Reservation, Admin)
│   ├── controllers/     # Logique applicative
│   └── views/           # Fichiers de vues HTML/PHP
├── public/
│   ├── css/            # Feuilles de style
│   ├── js/             # Scripts JavaScript
│   ├── uploads/        # Images téléchargées
│   └── index.php       # Point d'entrée (routeur)
├── config/
│   ├── database.php    # Connexion PDO à MySQL
│   └── routes.php      # Gestion des routes
├── Dockerfile
├── docker-compose.yml
└── init.sql            # Script d'initialisation de la BD
```

## ✨ Fonctionnalités

### Côté Utilisateur
- ✅ Affichage de la liste des événements
- ✅ Consultation des détails d'un événement
- ✅ Formulaire de réservation
- ✅ Confirmation de réservation

### Côté Administrateur
- ✅ Authentification sécurisée
- ✅ Tableau de bord
- ✅ CRUD complet sur les événements
- ✅ Consultation des réservations
- ✅ Déconnexion sécurisée

## 🔧 Commandes Utiles

### Docker
```bash
# Démarrer les conteneurs
docker-compose up -d

# Arrêter les conteneurs
docker-compose down

# Voir les logs
docker-compose logs -f

# Reconstruire les images
docker-compose up -d --build

# Accéder au conteneur web
docker exec -it minievent_web bash

# Accéder au conteneur MySQL
docker exec -it minievent_mysql mysql -u root -p
```

### Base de données
```bash
# Réinitialiser la base de données
docker-compose down -v
docker-compose up -d
```

## 📊 Milestones GitHub
- ✅ Milestone 1: Structure MVC + Page d'accueil
- ✅ Milestone 2: Base de données + Affichage dynamique
- ✅ Milestone 3: Module de réservation
- ✅ Milestone 4: Espace administrateur + CRUD
- ✅ Milestone 5: Finitions et documentation

## 📝 Liens Utiles
- [Documentation PHP](https://www.php.net/docs.php)
- [Documentation MySQL](https://dev.mysql.com/doc/)
- [Documentation Docker](https://docs.docker.com/)
- [Guide GitHub](https://docs.github.com/)

## 📄 Licence
Projet académique - ISSATSO 2025
