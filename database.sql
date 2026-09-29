-- Database: veelauto
-- Auteur: [Jouw naam]
-- Beschrijving: Database voor klantonderhoudssysteem taxibedrijf Veel Auto

CREATE DATABASE IF NOT EXISTS veelauto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE veelauto;

CREATE TABLE IF NOT EXISTS gebruikers (
id INT AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(255) NOT NULL UNIQUE,
wachtwoord VARCHAR(255) NOT NULL,
aangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS klanten (
id INT AUTO_INCREMENT PRIMARY KEY,
gebruiker_id INT NULL,
naam VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
telefoon VARCHAR(20) NOT NULL,
adres VARCHAR(255) NOT NULL,
woonplaats VARCHAR(255) NOT NULL,
aangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (gebruiker_id) REFERENCES gebruikers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
