<?php
// Iniciar sesión
session_start();

// Verificar si el usuario no está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Menú de Usuario</title>
    <style>
       body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 90vh;
  background: #ffffff;
  flex-direction: column;
}

/* Estilos para la imagen de cabecera */
.header-image {
  width: 100%;
  max-width: 60px;
  margin-top: 2px;
}

/* Estilos para el menú superior */
.top-menu {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: space-between;
  width: 100%;
  padding: 20px;
  box-sizing: border-box;
  background-color: ##ffffff;
  z-index: 1000; /* Puedes ajustar el valor de z-index según sea necesario */
}


/* Estilos para los botones en el menú superior */
.top-menu .button {
  background-color: #ff0d00;
  color: #fff;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
  font-size: 16px;
  border-radius: 5px;
}

/* Estilos para el espacio entre menú superior e imagen */
.user-menu-space {
  margin-top: 1px;
}

/* Estilos para el menú de usuario */
.user-menu {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
  margin-top: 20px;
}

/* Estilos para cada columna en el menú de usuario */
.column {
  flex: 1;
  text-align: center;
  padding: 20px;
  border-right: 1px solid #ff0d00;
}

/* Elimina el borde derecho en la última columna */
.column:last-child {
  border-right: none;
}

/* Estilos para los elementos desplegables */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Estilos para el botón principal del desplegable */
.dropbtn {
  padding: 10px 20px;
  border: none;
  cursor: pointer;
  font-size: 16px;
  border-radius: 5px;
  color: #ffffff;
}

/* Estilos específicos para cada desplegable */
.dropdown:nth-child(1) .dropbtn {
  background-color: #4caf50;
}

.dropdown:nth-child(2) .dropbtn {
  background-color: #6bbf69;
}

.dropdown:nth-child(3) .dropbtn {
  background-color: #ff0000;
}

/* Estilos para el contenido del desplegable */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #ffffff;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 1;
  border-radius: 5px;
}

/* Estilos para los enlaces dentro del desplegable */
.dropdown-content a {
  color: #004cff;
  padding: 10px;
  display: block;
  text-decoration: none;
}

/* Mostrar el contenido del desplegable al pasar el ratón sobre él */
.dropdown:hover .dropdown-content {
  display: block;
} 
    </style>
    <script>
// JavaScript en el encabezado de tu HTML
history.replaceState(null, null, window.location.href);

window.addEventListener('popstate', function () {
    history.replaceState(null, null, window.location.href);
    window.location.replace('menuu.php');
});

function cerrarSesion() {
    history.replaceState(null, null, 'cerrar_sesion.php');
    window.location.replace('cerrar_sesion.php');
}


</script>


</head>

<body>

    <div class="top-menu">
        <!-- Enlace para cerrar sesión con el evento onclick -->
        <a href="javascript:void(0);" onclick="cerrarSesion()" class="button">Cerrar Sesión</a>
         <a href="acc.php">
            <button class="button">Usuarios</button>
        </a>
    </div>
    <div class="user-menu-space"></div>
    <img src="img/7.jpg" alt="Logo">

    <div class="user-menu">
        <div class="column">
            <div class="dropdown">
                <button class="dropbtn">Estudiantes:</button>
                <div class="dropdown-content">
                    <a href="rgest.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/rgest.php') ? 'active' : ''; ?>">Registro</a>
                    <a href="busqueda.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/busqueda.php') ? 'active' : ''; ?>">Secciones</a>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="dropdown">
                <button class="dropbtn">Docentes:</button>
                <div class="dropdown-content">
                    <a href="rgdos.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/rgdos.php') ? 'active' : ''; ?>">Registro</a>
                    <a href="lisdos.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/lisdos.php') ? 'active' : ''; ?>">Lista</a>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="dropdown">
                <button class="dropbtn">Proyecto:</button>
                <div class="dropdown-content">
                    <a href="proest.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/proest.php') ? 'active' : ''; ?>">Registro</a>
                    <a href="buspro.php" class="<?php echo ($_SERVER['PHP_SELF'] == '/buspro.php') ? 'active' : ''; ?>">Busqueda</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
