<?php
// config.php - Databaseverbinding instellingen
// Dit bestand wordt in alle andere PHP-bestanden geïncludeerd

// Database instellingen
$host = 'localhost';
$dbnaam = 'veelauto';
$gebruiker = 'root';
$wachtwoord = '';

// DSN (Data Source Name) voor PDO
$dsn = "mysql:host=$host;dbname=$dbnaam;charset=utf8mb4";

// PDO opties voor foutafhandeling en standaard fetch modus
$opties = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Gooi exceptions bij fouten
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Haal data als associatieve array
    PDO::ATTR_EMULATE_PREPARES => false, // Gebruik echte prepared statements
];

try {
    // Maak PDO verbinding met database
    $pdo = new PDO($dsn, $gebruiker, $wachtwoord, $opties);
} catch (PDOException $e) {
    // Foutmelding bij verbindingsprobleem
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
