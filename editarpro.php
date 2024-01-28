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
    // Configuración de la conexión a la base de datos
    include 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proyecto</title>
    <style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

#formulario-container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 400px;
    text-align: center;
}

h1 {
    color: #333333;
}

label {
    display: block;
    margin-bottom: 15px;
    color: #4caf50;
    font-weight: bold;
}

input {
    background-color: #e0e0e0;
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background-color: #4caf50;
    color: #ffffff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
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
    background-color: rgba(200, 200, 200, 0.0); /* Fondo blanco con 80% de opacidad */
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
        <a href="buspro.php">
            <button class="button">Atras</button>
        </a>
    </div>

    <br>
    <br>
    <br>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
        // Procesar la actualización del proyecto
        $proyectoId = $_POST['id'];
        $nuevoTitulo = $_POST['titulo'];
        $nuevoTutorEmpresarial = $_POST['tutor_empresarial'];
        $nuevaFecha = $_POST['fecha'];

        // Verificar si se proporcionó un nuevo archivo PDF
        if ($_FILES['nuevoPdf']['error'] === 0) {
            // Obtener información del archivo
            $nombreArchivo = $_FILES['nuevoPdf']['name'];
            $tipoArchivo = $_FILES['nuevoPdf']['type'];
            $tamanioArchivo = $_FILES['nuevoPdf']['size'];
            $rutaTemporal = $_FILES['nuevoPdf']['tmp_name'];

            // Mover el archivo a la carpeta deseada (en este caso, se asume que es "pdf/")
            $rutaDestino = 'pdf/' . $nombreArchivo;
            move_uploaded_file($rutaTemporal, $rutaDestino);

            // Actualizar la información del proyecto con el nuevo PDF
            $actualizarProyectoQuery = "UPDATE proyectos SET 
                                        titulo = '$nuevoTitulo',
                                        tutor_empresarial = '$nuevoTutorEmpresarial',
                                        fecha = '$nuevaFecha',
                                        pdf = '$nombreArchivo'
                                        WHERE id = $proyectoId";
        } else {
            // No se proporcionó un nuevo archivo PDF, actualizar sin cambiar el PDF
            $actualizarProyectoQuery = "UPDATE proyectos SET 
                                        titulo = '$nuevoTitulo',
                                        tutor_empresarial = '$nuevoTutorEmpresarial',
                                        fecha = '$nuevaFecha'
                                        WHERE id = $proyectoId";
        }

        if (!empty($actualizarProyectoQuery)) {
            if ($conn->query($actualizarProyectoQuery)) {
                echo '<div id="formulario-container">';
                echo '<p>Proyecto actualizado correctamente.</p>';
                echo '<button onclick="window.location.href=\'detapro.php?id=' . $proyectoId . '\'">Volver Atrás</button>';
                echo '</div>';
            } else {
                echo '<div id="formulario-container">';
                echo '<p>Error al actualizar el proyecto: ' . $conn->error . '</p>';
                echo '</div>';
            }
        }
    }

    if (isset($_GET['id'])) {
        $proyectoId = $_GET['id'];

        $consultaProyecto = "SELECT * FROM proyectos WHERE id = $proyectoId";
        $resultadoProyecto = $conn->query($consultaProyecto);

        if ($resultadoProyecto->num_rows > 0) {
            $proyecto = $resultadoProyecto->fetch_assoc();

            echo '<div id="formulario-container">';
            echo '<h1>Editar Proyecto</h1>';
            echo '<form method="post" action="editarpro.php" enctype="multipart/form-data">';
            echo '<input type="hidden" name="id" value="' . $proyectoId . '">';
            echo '<label for="titulo">Título:</label>';
            echo '<input type="text" name="titulo" value="' . $proyecto['titulo'] . '"><br>';
            echo '<label for="tutor_empresarial">Tutor Empresarial:</label>';
            echo '<input type="text" name="tutor_empresarial" value="' . $proyecto['tutor_empresarial'] . '"><br>';
            echo '<label for="fecha">Fecha:</label>';
            echo '<input type="date" name="fecha" value="' . $proyecto['fecha'] . '"><br>';
            echo '<label for="nuevoPdf">Nuevo PDF:</label>';
            echo '<input type="file" name="nuevoPdf"><br>';
            echo '<input type="submit" value="Actualizar">';
            echo '</form>';
            echo '</div>';
        } else {
            echo '<div id="formulario-container">';
            echo '<p>No se encontró el proyecto.</p>';
            echo '</div>';
        }
    } else {
        echo '';
        echo '';
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
?>

</body>
</html>
