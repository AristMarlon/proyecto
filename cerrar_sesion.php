<?php
// Iniciar sesión si aún no se ha hecho
session_start();

// Destruir la sesión
session_destroy();

// Redirigir al usuario al formulario de inicio de sesión
header('Location: login.php');
exit();
?>
