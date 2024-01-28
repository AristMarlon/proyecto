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

// Función para borrar un docente por cédula
function borrarDocente($cedula) {
    global $conn;
    $borrarQuery = "DELETE FROM docentes WHERE cedula = '$cedula'";
    return $conn->query($borrarQuery);
}

// Función para obtener los detalles de un docente por cédula
function obtenerDetallesDocente($cedula) {
    global $conn;
    $detallesQuery = "SELECT * FROM docentes WHERE cedula = '$cedula'";
    $result = $conn->query($detallesQuery);
    return $result->fetch_assoc();
}

// Función para actualizar un docente
function actualizarDocente($cedula, $nombre, $telefono, $correo) {
    global $conn;
    $updateQuery = "UPDATE docentes SET nombre = '$nombre', telefono = '$telefono', correo = '$correo' WHERE cedula = '$cedula'";
    return $conn->query($updateQuery);
}

// Consulta para obtener la lista detallada de docentes con campos no nulos y no vacíos
$docentesQuery = "SELECT * FROM docentes 
                  WHERE nombre IS NOT NULL AND LENGTH(nombre) > 0
                  AND cedula IS NOT NULL AND LENGTH(cedula) > 0
                  AND telefono IS NOT NULL AND LENGTH(telefono) > 0
                  AND correo IS NOT NULL AND LENGTH(correo) > 0";

$docentesResult = $conn->query($docentesQuery);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista Detallada de Docentes</title>
    <style>
  
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #ffffff;
}

h1 {
    text-align: center;
    color: #333;
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
    color: #333;
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
    width: 100%;
}

button {
    width: 100%;
    background-color: #4caf50;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s;
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

.acciones form {
    display: inline-block;
    margin-right: 5px;
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

    <h1>Lista Detallada de Docentes</h1>

    <div id="lista-docentes">
        <h2>Detalles de los Docentes</h2>

        <?php
        if ($docentesResult) {
            if ($docentesResult->num_rows > 0) {
                // Mostrar la lista detallada de docentes en forma de tabla
                echo '<table>';
                echo '<tr>';
                echo '<th>Nombre</th>';
                echo '<th>Cédula</th>';
                echo '<th>Teléfono</th>';
                echo '<th>Correo</th>';
                echo '<th>Acciones</th>';
                echo '</tr>';

                while ($docente = $docentesResult->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $docente['nombre'] . '</td>';
                    echo '<td>' . $docente['cedula'] . '</td>';
                    echo '<td>' . $docente['telefono'] . '</td>';
                    echo '<td>' . $docente['correo'] . '</td>';
                    echo '<td class="acciones">';
                    // Botón de actualizar
                    echo '<form method="POST" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                    echo '<input type="hidden" name="actualizar_cedula" value="' . $docente['cedula'] . '">';
                    echo '<button type="submit" name="accion" value="actualizar">Actualizar</button>';
                    echo '</form>';
                    // Botón de borrar
                    echo '<form method="POST" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                    echo '<input type="hidden" name="borrar_cedula" value="' . $docente['cedula'] . '">';
                    echo '<button type="submit" name="accion" value="borrar">Borrar</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<p>No hay docentes registrados con información completa.</p>';
            }
        } else {
            echo '<p>Error en la consulta de docentes: ' . $conn->error . '</p>';
        }

        // Manejar las solicitudes de borrar y actualizar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar' && isset($_POST['actualizar_cedula'])) {
                $cedula_a_actualizar = $_POST['actualizar_cedula'];
                $detallesDocente = obtenerDetallesDocente($cedula_a_actualizar);
                ?>
                <!-- Mostrar el formulario de actualización -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="cedula_actualizar" value="<?php echo $cedula_a_actualizar; ?>">
                    <label for="nombre_actualizar">Nombre:</label>
                    <input type="text" id="nombre_actualizar" name="nombre_actualizar" value="<?php echo $detallesDocente['nombre']; ?>" required>
        
                    <label for="telefono_actualizar">Teléfono:</label>
                    <input type="text" id="telefono_actualizar" name="telefono_actualizar" value="<?php echo $detallesDocente['telefono']; ?>" required>
        
                    <label for="correo_actualizar">Correo:</label>
                    <input type="email" id="correo_actualizar" name="correo_actualizar" value="<?php echo $detallesDocente['correo']; ?>" required>
        
                    <button type="submit" name="accion" value="guardar_actualizacion">Guardar Actualización</button>
                </form>
                <?php
            } elseif (isset($_POST['accion']) && $_POST['accion'] === 'guardar_actualizacion') {
                // Procesar el formulario de actualización y guardar los cambios
                $cedula_actualizar = $_POST['cedula_actualizar'];
                $nombre_actualizar = $_POST['nombre_actualizar'];
                $telefono_actualizar = $_POST['telefono_actualizar'];
                $correo_actualizar = $_POST['correo_actualizar'];
        
                if (actualizarDocente($cedula_actualizar, $nombre_actualizar, $telefono_actualizar, $correo_actualizar)) {
                    echo '<p>Docente actualizado exitosamente.</p>';
                } else {
                    echo '<p>Error al actualizar el docente: ' . $conn->error . '</p>';
                }
            } elseif (isset($_POST['accion']) && $_POST['accion'] === 'borrar' && isset($_POST['borrar_cedula'])) {
                $cedula_a_borrar = $_POST['borrar_cedula'];
                if (borrarDocente($cedula_a_borrar)) {
                    echo '<p>Docente borrado exitosamente.</p>';
                } else {
                    echo '<p>Error al borrar el docente: ' . $conn->error . '</p>';
                }
            }
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
        ?>

    </div>

</body>
</html>
