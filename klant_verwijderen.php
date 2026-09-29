<?php
// klant_verwijderen.php - Klant verwijderen (alleen via POST)
require_once 'header.php';

// Controleren of gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: login.php');
    exit;
}

// Alleen POST requests toestaan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: klanten.php');
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id) {
    header('Location: klanten.php');
    exit;
}

try {
    // Klant verwijderen (alleen van ingelogde gebruiker)
    $stmt = $pdo->prepare("DELETE FROM klanten WHERE id = ? AND gebruiker_id = ?");
    $stmt->execute([$id, $_SESSION['gebruiker_id']]);

    if ($stmt->rowCount() > 0) {
        echo '<div class="succesmelding">Klant succesvol verwijderd!</div>';
    } else {
        echo '<div class="foutmelding">Klant niet gevonden of je hebt geen toegang.</div>';
    }
} catch (PDOException $e) {
    echo '<div class="foutmelding">Databasefout: ' . $e->getMessage() . '</div>';
}
?>

<p style="margin-top: 1rem;"><a href="klanten.php" class="knop">Terug naar klantoverzicht</a></p>

<?php require_once 'footer.php'; ?>
