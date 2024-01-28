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
    <title>Detalles de la Sección</title>
    <style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #ffffff; /* Fondo blanco */
}

h1 {
    text-align: center;
    color: #4caf50; /* Verde oscuro para el título */
    margin-bottom: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 15px;
    text-align: left;
}

th {
    background-color: #4caf50; /* Verde oscuro para el encabezado de la tabla */
    color: #fff; /* Texto blanco */
}

.student-info {
    display: flex;
    justify-content: space-between;
    background-color: #ffffff; /* Fondo blanco para la información del estudiante */
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.acciones form {
    display: inline-block;
    margin-right: 5px;
}

.acciones form button {
    background-color: #4caf50; /* Verde oscuro para el botón de acciones */
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
}

.acciones form button:hover {
    background-color: #45a049; /* Verde más oscuro al pasar el ratón */
}

.mensaje {
    position: fixed;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    background-color: #4caf50; /* Verde oscuro para el mensaje */
    color: #fff;
    padding: 15px;
    border-radius: 5px;
    display: none;
    z-index: 1;
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
    background-color: #ffffff; /* Verde oscuro para el fondo del menú */
    z-index: 1000; /* Puedes ajustar el valor de z-index según sea necesario */
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

form {
    text-align: center;
    margin-top: 20px;
}

input[type="hidden"] {
    display: none; /* Oculta los campos ocultos */
}

button[type="submit"] {
    padding: 10px 20px;
    background-color: #4caf50; /* Verde */
    color: #ffffff; /* Texto blanco */
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

button[type="submit"]:hover {
    background-color: #388; /* Verde más oscuro al pasar el ratón */
}


</style>
</head>
<body>
  <div class="top-menu">
       <a href="busqueda.php">
  <button class="button">Atras</button>
</a>


    </div>
<br>
<br>
<br>
<br>
<br>

<?php
// Configuración de la conexión a la base de datos
include 'conexion.php';

// Función para borrar un estudiante por nombre
function borrarEstudiante($nombre) {
    global $conn;
    $borrarQuery = "DELETE FROM estudiantes WHERE nombre = '$nombre'";
    return $conn->query($borrarQuery);
}

// Verificar si se proporcionó un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $projectId = $_GET['id'];

    // Consulta para obtener la información detallada de la sección
    $sql = "SELECT * FROM estudiantes WHERE id = $projectId";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            // Mostrar la información detallada de la sección
            $row = $result->fetch_assoc();
            $projectPeriod = isset($row['periodo']) ? $row['periodo'] : 'Sin periodo';
            $projectSection = isset($row['seccion']) ? $row['seccion'] : 'Sin sección';
            $projectPath = isset($row['trayecto']) ? $row['trayecto'] : 'Sin trayecto';

            echo '<h1>Detalles de la Sección</h1>';
            echo '<table>';
            echo '<tr>';
            echo '<th>Sección, Trayecto y Año</th>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>';
            echo '<p><strong>Sección:</strong> ' . $projectSection . '</p>';
            echo '<p><strong>Trayecto:</strong> ' . $projectPath . '</p>';
            echo '<p><strong>Año:</strong> ' . $projectPeriod . '</p>';
            echo '</td>';

            echo '<form method="post" action="grupos.php">';
            echo '<input type="hidden" name="seccion" value="' . $projectSection . '">';
            echo '<input type="hidden" name="trayecto" value="' . $projectPath . '">';
            echo '<input type="hidden" name="periodo" value="' . $projectPeriod . '">';
            echo '<button type="submit">Ir a la página de grupos</button>';
            echo '</form>';

           // ...

// Agregar el formulario para ir a la página de grupos
echo '<form method="get" action="leer_grupos.php">';
echo '<input type="hidden" name="seccion" value="' . $projectSection . '">';
echo '<input type="hidden" name="trayecto" value="' . $projectPath . '">';
echo '<input type="hidden" name="periodo" value="' . $projectPeriod . '">';
echo '<button type="submit">Ver Grupos</button>';
echo '</form>';

// ...

            
            // Consulta para obtener la lista de estudiantes en la sección
            $studentsQuery = "SELECT id, nombre, cedula, telefono, correo FROM estudiantes WHERE periodo = '$projectPeriod' AND seccion = '$projectSection' AND trayecto = '$projectPath'";
            $studentsResult = $conn->query($studentsQuery);

            if ($studentsResult) {
                if ($studentsResult->num_rows > 0) {
                    // Mostrar la lista de estudiantes
                    echo '<tr>';
                    echo '<th>Lista de Estudiantes</th>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td>';
                    echo '<table>';
                    echo '<tr>';
                    echo '<th>Nombre</th>';
                    echo '<th>cedula</th>'; 
                    echo '<th>Teléfono</th>';
                    echo '<th>Correo</th>';
                    echo '</tr>';

                    while ($student = $studentsResult->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $student['nombre'] . '</td>';
                        echo '<td>' . $student['cedula'] . '</td>';  
                        echo '<td>' . $student['telefono'] . '</td>';
                        echo '<td>' . $student['correo'] . '</td>';
                        echo '<td class="acciones">';
                        // Botón de actualizar
                        echo '<form method="POST" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                        echo '<input type="hidden" name="actualizar_id" value="' . $student['id'] . '">';
                        echo '<button type="submit" name="accion" value="actualizar">Actualizar</button>';
                        echo '</form>';
                        // Botón de borrar
                        echo '<form method="POST" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                        echo '<input type="hidden" name="borrar_cedula" value="' . $student['cedula'] . '">';
                        echo '<button type="submit" name="accion" value="borrar">Borrar</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                    echo '</td>';
                    echo '</tr>';
                } else {
                    echo '<td>No hay estudiantes registrados para esta sección.</td>';
                }
            } else {
                echo '<td>Error en la consulta de estudiantes: ' . $conn->error . '</td>';
            }

            echo '</tr>';
            echo '</table>';
        } else {
            echo '<p>No se encontró información para el ID proporcionado.</p>';
        }
    } else {
        echo '<p>Error en la consulta de detalles de la sección: ' . $conn->error . '</p>';
    }
} else {
    echo '<p>Parámetro de ID no válido.</p>';
}

// Manejar las solicitudes de borrar y actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'borrar' && isset($_POST['borrar_cedula'])) {
            $nombre_a_borrar = $_POST['borrar_cedula'];
            if (borrarEstudiante($nombre_a_borrar)) {
                echo '<p>Estudiante borrado exitosamente.</p>';
            } else {
                echo '<p>Error al borrar al estudiante: ' . $conn->error . '</p>';
            }
        } elseif ($_POST['accion'] === 'actualizar' && isset($_POST['actualizar_id'])) {
            // Aquí puedes agregar el código para redirigir a la página de actualización
            // utilizando el ID del estudiante ($_POST['actualizar_id'])
            header("Location: actualizar_estudiante.php?id=" . $_POST['actualizar_id']);
            exit();
        }
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
</body>
</html>
