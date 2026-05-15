<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'corsettistore360_corsetti_cms');
define('DB_USER', 'corsettistore360_corsetti_admin');
define('DB_PASS', 'lT9VUtxA5kHHYZ0s');
define('UPLOAD_PATH', '/home/corsettistore360/public_html/corsetti/admin/uploads/');
define('UPLOAD_URL', '/corsetti/admin/uploads/');

// Connessione al database
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die('Errore connessione database: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

// Avvia la sessione se non è già attiva
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Credenziali admin
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', password_hash('marco', PASSWORD_DEFAULT));
?>
