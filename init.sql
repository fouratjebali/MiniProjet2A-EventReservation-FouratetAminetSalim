-- Création de la base de données
CREATE DATABASE IF NOT EXISTS minievent_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE minievent_db;

-- Table events
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    seats INT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table reservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion d'un admin par défaut (username: admin, password: admin123)
INSERT INTO admin (username, password_hash) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insertion d'événements de démonstration
INSERT INTO events (title, description, date, location, seats, image) VALUES
('Concert de Jazz', 'Soirée jazz avec les meilleurs musiciens locaux', '2025-02-15', 'Théâtre Municipal de Sousse', 150, 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?w=500'),
('Conférence Tech 2025', 'Les dernières innovations en intelligence artificielle', '2025-03-10', 'Centre de Conférences ISSATSO', 200, 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=500'),
('Festival Culturel', 'Célébration de la culture tunisienne', '2025-04-20', 'Médina de Sousse', 500, 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=500'),
('Atelier Entrepreneuriat', 'Comment lancer votre startup', '2025-02-25', 'ISSATSO - Salle A12', 50, 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=500');

-- Quelques réservations de test
INSERT INTO reservations (event_id, name, email, phone) VALUES
(1, 'Ahmed Ben Salem', 'ahmed@example.com', '+216 20 123 456'),
(1, 'Fatma Khelifi', 'fatma@example.com', '+216 22 987 654'),
(2, 'Mohamed Trabelsi', 'mohamed@example.com', '+216 24 555 777');

