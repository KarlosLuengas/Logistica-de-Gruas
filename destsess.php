<?php
session_start();

if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
    // Destruir la sesión
    session_destroy();
}

// Redirigir a la página de inicio de sesión
header('Location: login.php');
exit();
?>