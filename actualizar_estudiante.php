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
    <title>Actualizar Estudiante</title>
    <style>
body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            width: 100%;
            background-color: #0066cc;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0052a3;
        }

        .mensaje {
            text-align: center;
            margin-top: 10px;
            padding: 10px;
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            border-radius: 3px;
            display: <?php echo isset($mensaje) ? 'block' : 'none'; ?>;
        }    </style>
</head>
<body>

<?php
// Configuración de la conexión a la base de datos
include 'conexion.php';

// Función para obtener los detalles de un estudiante por ID
function obtenerDetallesEstudiante($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $detallesQuery = "SELECT * FROM estudiantes WHERE id = $id";
    $result = $conn->query($detallesQuery);
    return $result->fetch_assoc();
}

// Función para actualizar un estudiante
function actualizarEstudiante($id, $nombre, $cedula, $telefono, $correo) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $nombre = mysqli_real_escape_string($conn, $nombre);
    $cedula = mysqli_real_escape_string($conn, $cedula);
    $telefono = mysqli_real_escape_string($conn, $telefono);
    $correo = mysqli_real_escape_string($conn, $correo);

    $updateQuery = "UPDATE estudiantes SET nombre='$nombre', cedula='$cedula', telefono='$telefono', correo='$correo' WHERE id='$id'";
    
    return $conn->query($updateQuery);
}

// Verificar si se proporcionó un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $studentId = $_GET['id'];

    // Consulta para obtener los detalles del estudiante
    $details = obtenerDetallesEstudiante($studentId);

    if ($details) {
        // Mostrar el formulario de actualización
?>
        <h1>Actualizar Estudiante</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="id_actualizar" value="<?php echo $studentId; ?>">
            
            <label for="nombre_actualizar">Nombre:</label>
            <input type="text" id="nombre_actualizar" name="nombre_actualizar" value="<?php echo $details['nombre']; ?>" required>

            <label for="cedula_actualizar">Cédula:</label>
            <input type="text" id="cedula_actualizar" name="cedula_actualizar" value="<?php echo $details['cedula']; ?>" required>

            <label for="telefono_actualizar">Teléfono:</label>
            <input type="text" id="telefono_actualizar" name="telefono_actualizar" value="<?php echo $details['telefono']; ?>" required>

            <label for="correo_actualizar">Correo:</label>
            <input type="email" id="correo_actualizar" name="correo_actualizar" value="<?php echo $details['correo']; ?>" required>

            <button type="submit" name="accion" value="guardar_actualizacion">Guardar Actualización</button>
        </form>
<?php
    } else {
        echo '<p>No se encontró información para el ID proporcionado.</p>';
    }

}

// Manejar la solicitud de guardar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] === 'guardar_actualizacion') {
        // Procesar el formulario de actualización y guardar los cambios
        $id_actualizar = $_POST['id_actualizar'];
        $nombre_actualizar = $_POST['nombre_actualizar'];
        $cedula_actualizar = $_POST['cedula_actualizar'];
        $telefono_actualizar = $_POST['telefono_actualizar'];
        $correo_actualizar = $_POST['correo_actualizar'];

        // Actualizar estudiante
        if (actualizarEstudiante($id_actualizar, $nombre_actualizar, $cedula_actualizar, $telefono_actualizar, $correo_actualizar)) {
            $mensaje = "Estudiante actualizado correctamente";

            // Redirigir a la página de detalles
            header("Location: detalles.php?id=" . $id_actualizar);
            exit();
        } else {
            $mensaje = "Error al actualizar al estudiante: " . $conn->error;
        }
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<div class="mensaje"><?php echo isset($mensaje) ? $mensaje : ''; ?></div>

</body>
</html>
