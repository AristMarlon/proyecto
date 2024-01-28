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
<html>
<head>
  <title>Usuario</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <script src="script.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .user-container {
      width: 300px;
      margin: 10px;
      padding: 15px;
      border: 2px solid #21333a;
      background-color: #f8f8f8;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .user-container form {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .user-container label {
      font-weight: bold;
    }

    .user-container input {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .user-container .update-btn, .user-container .delete-btn {
      background-color: #4caf50;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
    }

    .user-container .update-btn:hover, .user-container .delete-btn:hover {
      background-color: #45a049;
    }

    .new-user-container {
      width: 300px;
      margin-top: 20px;
      padding: 15px;
      border: 2px solid #21333a;
      background-color: #f8f8f8;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .new-user-container form {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .new-user-container label {
      font-weight: bold;
    }

    .new-user-container input {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .new-user-container .update-btn {
      background-color: #4caf50;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
    }

    .new-user-container .update-btn:hover {
      background-color: #45a049;
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
  background-color: #f2f2f2;
  z-index: 1000; /* Puedes ajustar el valor de z-index según sea necesario */
}


/* Estilos para los botones en el menú superior */
.top-menu .button {
  background-color: #05f535;
  color: #fff;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
  font-size: 16px;
  border-radius: 5px;
}
</style>
</head>
<body>
  <div class="top-menu">
       <a href="menuu.php">
  <button class="button">Atras</button>
</a>


    </div>
<br>
<br>
<br>
<br>
<br>

<?php
$host = "localhost";
$usuario = "root";
$contraseña = "";
$db = "sistema";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db", $usuario, $contraseña);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Actualizar registro
    if (isset($_POST['actualizar'])) {
        $nombre_usuario = $_POST['nombre_usuario'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        $sentenciaSQL = $conexion->prepare("UPDATE usuarios SET nombre_usuario = :nombre_usuario, contrasena = :contrasena WHERE id = :id");
        $sentenciaSQL->bindParam(':nombre_usuario', $nombre_usuario);
        $sentenciaSQL->bindParam(':contrasena', $contrasena);
        $sentenciaSQL->bindParam(':id', $_POST['id']);
        $sentenciaSQL->execute();

        echo "<p>Registro actualizado correctamente.</p>";
    }

    // Eliminar registro
    if (isset($_POST['eliminar'])) {
        $sentenciaSQL = $conexion->prepare("DELETE FROM usuarios WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $_POST['id']);
        $sentenciaSQL->execute();

        echo "<p>Registro eliminado correctamente.</p>";
    }

    // Insertar nuevo registro
    if (isset($_POST['nuevo'])) {
        $nombre_usuario_nuevo = $_POST['nombre_usuario_nuevo'] ?? '';
        $contrasena_nueva = $_POST['contrasena_nueva'] ?? '';

        $sentenciaSQL = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, contrasena) VALUES (:nombre_usuario, :contrasena)");
        $sentenciaSQL->bindParam(':nombre_usuario', $nombre_usuario_nuevo);
        $sentenciaSQL->bindParam(':contrasena', $contrasena_nueva);
        $sentenciaSQL->execute();

        echo "<p>Nuevo registro agregado correctamente.</p>";
    }

    // Mostrar registros
    $sentenciaSQL = $conexion->prepare("SELECT * FROM `usuarios`");
    $sentenciaSQL->execute();
    $resultados = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultados as $fila) {
        echo "<div class='user-container'>";
        echo "<form method='POST'>";
        echo "<label>Usuario:</label><input type='text' name='nombre_usuario' value='{$fila['nombre_usuario']}'><br>";
        echo "<label>Contraseña:</label><input type='text' name='contrasena' value='{$fila['contrasena']}'><br>";
        echo "<input type='hidden' name='id' value='{$fila['id']}'>";
        echo "<input type='submit' name='actualizar' class='update-btn' value='Actualizar'>";
        echo "<input type='submit' name='eliminar' class='delete-btn' value='Eliminar'>";
        echo "</form>";
        echo "</div>";
    }

    // Formulario para ingresar nuevo registro
    echo "<div class='new-user-container'>";
    echo "<form method='POST'>";
    echo "<label>Nuevo Usuario:</label><input type='text' name='nombre_usuario_nuevo'><br>";
    echo "<label>Nueva Contraseña:</label><input type='text' name='contrasena_nueva'><br>";
    echo "<input type='submit' name='nuevo' class='update-btn' value='Agregar Nuevo'>";
    echo "</form>";
    echo "</div>";

    $conexion = null;

} catch (PDOException $ex) {
    echo "<p style='color:red'>Error al conectar a la base de datos: " . $ex->getMessage() . "</p>";
}
?>
</body>
</html>
