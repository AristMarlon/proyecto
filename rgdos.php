<?php
// Iniciar sesión
session_start();

// Verificar si el usuario no está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>
<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario con comprobación de existencia
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $cedula = isset($_POST['cedula']) ? $_POST['cedula'] : '';
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
    $correo = isset($_POST['correo']) ? $_POST['correo'] : '';

    // Validación de campos (puedes agregar más validaciones según tus necesidades)

    // Verificar si todos los campos están llenos
    if (empty($nombre) || empty($cedula) || empty($telefono) || empty($correo)) {
        echo '<p>Por favor, completa todos los campos.</p>';
    } else {
        // Verificar si el docente ya existe
        $verificacionQuery = "SELECT * FROM docentes WHERE cedula = '$cedula' OR telefono = '$telefono' OR correo = '$correo'";
        $verificacionResult = $conn->query($verificacionQuery);

        if ($verificacionResult) {
            if ($verificacionResult->num_rows > 0) {
                echo '<p>El docente ya está registrado con la misma cédula, teléfono o correo.</p>';
            } else {
                // Docente no existe, proceder con la inserción en la base de datos
                $insertQuery = "INSERT INTO docentes (nombre, cedula, telefono, correo) VALUES ('$nombre', '$cedula', '$telefono', '$correo')";

                if ($conn->query($insertQuery) === TRUE) {
                    echo '<p>Docente registrado exitosamente.</p>';
                } else {
                    echo '<p>Error en el registro del docente: ' . $conn->error . '</p>';
                }
            }
        } else {
            echo '<p>Error en la consulta de verificación: ' . $conn->error . '</p>';
        }
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro y Lista de Docentes</title>
    <style>
       body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 20px;
  background-color: #f2f2f2;
}

h1 {
  text-align: center;
  color: #4caf50;
  margin-bottom: 30px;
}

.container {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

#formulario,
#lista-docentes {
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

#formulario h2,
#lista-docentes h2 {
  text-align: center;
  color: #4caf50;
  margin-bottom: 20px;
}

form {
  display: grid;
  gap: 15px;
}

label {
  font-weight: bold;
  color: #333;
}

input[type="text"],
input[type="email"] {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

button {
  width: 100%;
  background-color: #4caf50;
  color: #fff;
  border: none;
  padding: 10px;
  border-radius: 3px;
  cursor: pointer;
}

button:hover {
  background-color: #0052a3;
}

#lista-docentes table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

#lista-docentes th,
#lista-docentes td {
  border: 1px solid #ddd;
  padding: 12px;
  text-align: left;
}

#lista-docentes th {
  background-color: #f2f2f2;
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
    background-color: #ffffff;
    z-index: 1000; 
}

/* Estilos para los botones en el menú superior */
.top-menu .button {
    background-color: #4caf50; 
    color: #ffffff; 
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
  


    <h1>Registro y Lista de Docentes</h1>

    <div class="container">
        <div id="formulario">
            <h2>Registro de Docentes</h2>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="cedula">Cédula de Identidad:</label>
                <input type="text" id="cedula" name="cedula" required>

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required>

                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>

                <button type="submit">Registrar Docente</button>
            </form>
        </div>

        <div id="lista-docentes">
            <h2>Lista de Docentes</h2>

            <?php
            include 'conexion.php';

            // Consulta para obtener la lista de docentes
            $docentesQuery = "SELECT nombre, cedula, telefono, correo FROM docentes";
            $docentesResult = $conn->query($docentesQuery);

            if ($docentesResult) {
                if ($docentesResult->num_rows > 0) {
                    // Mostrar la lista de docentes en forma de tabla
                    echo '<table>';
                    echo '<tr>';
                    echo '<th>Nombre</th>';
                    echo '<th>Cédula</th>';
                    echo '<th>Teléfono</th>';
                    echo '<th>Correo</th>';
                    echo '</tr>';

                    while ($docente = $docentesResult->fetch_assoc()) {
                      echo '<tr>';
                      echo '<td>' . $docente['nombre'] . '</td>';
                      echo '<td>' . $docente['cedula'] . '</td>';
                      echo '<td>' . $docente['telefono'] . '</td>';
                      echo '<td>' . $docente['correo'] . '</td>';
                      // Agregar el botón de borrado
                      echo '<td><form method="POST" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"><input type="hidden" name="borrar_cedula" value="' . $docente['cedula'] . '"><button type="submit">Borrar</button></form></td>';
                      echo '</tr>';
                  }
                  

                    echo '</table>';
                } else {
                    echo '<p>No hay docentes registrados.</p>';
                }
            } else {
                echo '<p>Error en la consulta de docentes: ' . $conn->error . '</p>';
            }
            ?>

        </div>
    </div>

</body>
</html>
