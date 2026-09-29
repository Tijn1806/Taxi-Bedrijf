<?php
// logout.php - Uitloggen en sessie vernietigen
session_start();

// Sessie variabelen vernietigen
$_SESSION = [];

// Sessie cookie verwijderen
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Sessie volledig vernietigen
session_destroy();

// Doorsturen naar homepagina
header('Location: index.php');
exit;
