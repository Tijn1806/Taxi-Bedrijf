<?php
// login.php - Inlogpagina voor gebruikers
require_once 'header.php';

$foutmeldingen = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    // Validatie: verplichte velden
    if (empty($email)) {
        $foutmeldingen[] = 'E-mailadres is verplicht.';
    }

    if (empty($wachtwoord)) {
        $foutmeldingen[] = 'Wachtwoord is verplicht.';
    }

    // Geen foutmeldingen? Probeer in te loggen
    if (empty($foutmeldingen)) {
        try {
            // Gebruiker ophalen op basis van e-mail
            $stmt = $pdo->prepare("SELECT id, email, wachtwoord FROM gebruikers WHERE email = ?");
            $stmt->execute([$email]);
            $gebruiker = $stmt->fetch();

            // Controleren of gebruiker bestaat en wachtwoord klopt
            if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
                // Sessie starten en gebruiker ID opslaan
                $_SESSION['gebruiker_id'] = $gebruiker['id'];
                $_SESSION['email'] = $gebruiker['email'];

                // Sessie ID regenereren voor beveiliging
                session_regenerate_id(true);

                // Doorsturen naar klantoverzicht
                header('Location: klanten.php');
                exit;
            } else {
                $foutmeldingen[] = 'Ongeldige e-mail of wachtwoord.';
            }
        } catch (PDOException $e) {
            $foutmeldingen[] = 'Databasefout: ' . $e->getMessage();
        }
    }
}
?>

<div class="formulier">
    <h1>Inloggen</h1>
    
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

        <label for="wachtwoord">Wachtwoord:</label>
        <input type="password" id="wachtwoord" name="wachtwoord" required>

        <button type="submit">Inloggen</button>
    </form>

    <p style="margin-top: 1rem;">Nog geen account? <a href="register.php">Registreren</a></p>
</div>

<?php require_once 'footer.php'; ?>
