<?php
// klant_bewerken.php - Bestaande klant bewerken
require_once 'header.php';

// Controleren of gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: login.php');
    exit;
}

// Klant ID ophalen uit URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: klanten.php');
    exit;
}

$foutmeldingen = [];

try {
    // Klantgegevens ophalen
    $stmt = $pdo->prepare("SELECT * FROM klanten WHERE id = ? AND gebruiker_id = ?");
    $stmt->execute([$id, $_SESSION['gebruiker_id']]);
    $klant = $stmt->fetch();

    if (!$klant) {
        $foutmeldingen[] = 'Klant niet gevonden.';
    }
} catch (PDOException $e) {
    $foutmeldingen[] = 'Databasefout: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($foutmeldingen)) {
    // Formulierdata ophalen en XSS-veilig maken
    $naam = htmlspecialchars(trim($_POST['naam'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $telefoon = htmlspecialchars(trim($_POST['telefoon'] ?? ''));
    $adres = htmlspecialchars(trim($_POST['adres'] ?? ''));
    $woonplaats = htmlspecialchars(trim($_POST['woonplaats'] ?? ''));

    // Validatie: verplichte velden
    if (empty($naam)) {
        $foutmeldingen[] = 'Naam is verplicht.';
    }

    if (empty($email)) {
        $foutmeldingen[] = 'E-mailadres is verplicht.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $foutmeldingen[] = 'Ongeldig e-mailadres.';
    }

    if (empty($telefoon)) {
        $foutmeldingen[] = 'Telefoonnummer is verplicht.';
    }

    if (empty($adres)) {
        $foutmeldingen[] = 'Adres is verplicht.';
    }

    if (empty($woonplaats)) {
        $foutmeldingen[] = 'Woonplaats is verplicht.';
    }

    // Geen foutmeldingen? Probeer klant te bewerken
    if (empty($foutmeldingen)) {
        try {
            $stmt = $pdo->prepare("UPDATE klanten SET naam = ?, email = ?, telefoon = ?, adres = ?, woonplaats = ? WHERE id = ? AND gebruiker_id = ?");
            $stmt->execute([$naam, $email, $telefoon, $adres, $woonplaats, $id, $_SESSION['gebruiker_id']]);

            // Succesmelding en doorsturen naar klantoverzicht
            echo '<div class="succesmelding">Klant succesvol bijgewerkt!</div>';
            echo '<p><a href="klanten.php" class="knop">Terug naar klantoverzicht</a></p>';
            require_once 'footer.php';
            exit;
        } catch (PDOException $e) {
            $foutmeldingen[] = 'Databasefout: ' . $e->getMessage();
        }
    }
}
?>

<div class="formulier">
    <h1>Klant bewerken</h1>
    
    <?php if (!empty($foutmeldingen)): ?>
        <div class="foutmelding">
            <?php foreach ($foutmeldingen as $fout): ?>
                <p><?php echo $fout; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($klant) && $klant): ?>
        <form method="POST">
            <label for="naam">Naam:</label>
            <input type="text" id="naam" name="naam" value="<?php echo htmlspecialchars($klant['naam']); ?>" required>

            <label for="email">E-mailadres:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($klant['email']); ?>" required>

            <label for="telefoon">Telefoonnummer:</label>
            <input type="text" id="telefoon" name="telefoon" value="<?php echo htmlspecialchars($klant['telefoon']); ?>" required>

            <label for="adres">Adres:</label>
            <input type="text" id="adres" name="adres" value="<?php echo htmlspecialchars($klant['adres']); ?>" required>

            <label for="woonplaats">Woonplaats:</label>
            <input type="text" id="woonplaats" name="woonplaats" value="<?php echo htmlspecialchars($klant['woonplaats']); ?>" required>

            <button type="submit">Opslaan</button>
        </form>

        <p style="margin-top: 1rem;"><a href="klanten.php">Annuleren</a></p>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
