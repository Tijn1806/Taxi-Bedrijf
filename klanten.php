<?php
// klanten.php - Overzicht van alle klanten
require_once 'header.php';

// Controleren of gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: login.php');
    exit;
}

try {
    // Alle klanten ophalen van ingelogde gebruiker
    $stmt = $pdo->prepare("SELECT * FROM klanten WHERE gebruiker_id = ? ORDER BY aangemaakt DESC");
    $stmt->execute([$_SESSION['gebruiker_id']]);
    $klanten = $stmt->fetchAll();
} catch (PDOException $e) {
    $foutmelding = 'Databasefout: ' . $e->getMessage();
}
?>

<div class="tabel">
    <h1>Klantoverzicht</h1>
    
    <p style="margin-bottom: 1rem;">
        <a href="klant_toevoegen.php" class="knop">Nieuwe klant toevoegen</a>
    </p>

    <?php if (isset($foutmelding)): ?>
        <div class="foutmelding">
            <p><?php echo $foutmelding; ?></p>
        </div>
    <?php elseif (empty($klanten)): ?>
        <p>Je hebt nog geen klanten toegevoegd.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>E-mail</th>
                    <th>Telefoon</th>
                    <th>Adres</th>
                    <th>Woonplaats</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($klanten as $klant): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($klant['naam']); ?></td>
                        <td><?php echo htmlspecialchars($klant['email']); ?></td>
                        <td><?php echo htmlspecialchars($klant['telefoon']); ?></td>
                        <td><?php echo htmlspecialchars($klant['adres']); ?></td>
                        <td><?php echo htmlspecialchars($klant['woonplaats']); ?></td>
                        <td>
                            <a href="klant_bewerken.php?id=<?php echo $klant['id']; ?>" class="knop">Bewerken</a>
                            <form method="POST" action="klant_verwijderen.php" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $klant['id']; ?>">
                                <button type="submit" class="knop knop-rood" onclick="return confirm('Weet je zeker dat je deze klant wilt verwijderen?');">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
