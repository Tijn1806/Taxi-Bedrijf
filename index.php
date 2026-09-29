<?php
// index.php - Startpagina van de applicatie
require_once 'header.php';
?>

<div class="formulier" style="text-align: center;">
    <h1>Welkom bij Veel Auto</h1>
    <p style="margin: 1rem 0;">Klantonderhoudssysteem voor taxibedrijf Veel Auto</p>
    
    <?php if (isset($_SESSION['gebruiker_id'])): ?>
        <p>Je bent ingelogd als <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong></p>
        <p style="margin-top: 1rem;">
            <a href="klanten.php" class="knop">Naar klantoverzicht</a>
        </p>
    <?php else: ?>
        <p style="margin-top: 1rem;">
            <a href="login.php" class="knop">Inloggen</a>
            <a href="register.php" class="knop">Registreren</a>
        </p>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
