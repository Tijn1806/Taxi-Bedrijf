<?php
// register.php - Registratiepagina voor nieuwe gebruikers
require_once 'header.php';

$foutmeldingen = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formulierdata ophalen en XSS-veilig maken
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    $wachtwoord_herhaal = $_POST['wachtwoord_herhaal'] ?? '';

    // Validatie: verplichte velden
    if (empty($email)) {
        $foutmeldingen[] = 'E-mailadres is verplicht.';
    }

    // Validatie: geldig e-mailadres
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $foutmeldingen[] = 'Ongeldig e-mailadres.';
    }

    // Validatie: wachtwoord minimaal 8 tekens
    if (strlen($wachtwoord) < 8) {
        $foutmeldingen[] = 'Wachtwoord moet minimaal 8 tekens zijn.';
    }

    // Validatie: wachtwoorden komen overeen
    if ($wachtwoord !== $wachtwoord_herhaal) {
        $foutmeldingen[] = 'Wachtwoorden komen niet overeen.';
    }

    // Geen foutmeldingen? Probeer gebruiker toe te voegen
    if (empty($foutmeldingen)) {
        try {
            // Controleren of e-mail al bestaat
            $stmt = $pdo->prepare("SELECT id FROM gebruikers WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $foutmeldingen[] = 'Dit e-mailadres is al geregistreerd.';
            } else {
                // Wachtwoord hashen met password_hash
                $wachtwoord_hash = password_hash($wachtwoord, PASSWORD_DEFAULT);

                // Gebruiker invoegen in database
                $stmt = $pdo->prepare("INSERT INTO gebruikers (email, wachtwoord) VALUES (?, ?)");
                $stmt->execute([$email, $wachtwoord_hash]);

                // Succesmelding en doorsturen naar login
                echo '<div class="succesmelding">Registratie succesvol! Je kunt nu inloggen.</div>';
                echo '<p><a href="login.php" class="knop">Naar inloggen</a></p>';
                require_once 'footer.php';
                exit;
            }
        } catch (PDOException $e) {
            $foutmeldingen[] = 'Databasefout: ' . $e->getMessage();
        }
    }
}
?>

<div class="formulier">
    <h1>Registreren</h1>
    
    <?php if (!empty($foutmeldingen)): ?>
        <div class="foutmelding">
            <?php foreach ($foutmeldingen as $fout): ?>
                <p><?php echo $fout; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="email">E-mailadres:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>

        <label for="wachtwoord">Wachtwoord (minimaal 8 tekens):</label>
        <input type="password" id="wachtwoord" name="wachtwoord" required>

        <label for="wachtwoord_herhaal">Wachtwoord herhalen:</label>
        <input type="password" id="wachtwoord_herhaal" name="wachtwoord_herhaal" required>

        <button type="submit">Registreren</button>
    </form>

    <p style="margin-top: 1rem;">Al een account? <a href="login.php">Inloggen</a></p>
</div>

<?php require_once 'footer.php'; ?>
