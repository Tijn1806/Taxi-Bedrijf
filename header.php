<?php
// header.php - Bovenste deel van elke pagina
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veel Auto - Klantonderhoud</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <span class="logo-veel">Veel</span><span class="logo-auto">Auto</span>
        </div>
        <nav>
            <?php if (isset($_SESSION['gebruiker_id'])): ?>
                <a href="klanten.php">Klanten</a>
                <a href="logout.php">Uitloggen</a>
            <?php else: ?>
                <a href="index.php">Home</a>
                <a href="login.php">Inloggen</a>
                <a href="register.php">Registreren</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
