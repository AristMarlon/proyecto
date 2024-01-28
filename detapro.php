
<?php
// Iniciar sesión
session_start();

// Verificar si el usuario no está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?><!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles del Proyecto</title>
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

        #detalles-container {
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

        ul {
            list-style-type: none;
            padding: 0;
            margin-top: 20px;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            color: #050505;
        }

        a:hover {
            text-decoration: underline;
        }

        #acciones-proyecto {
            margin-top: 20px;
        }

        button {
            background-color: #4caf50;
            color: #050505;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 10px;
        }

        button:hover {
            background-color: #48d4f7;
        }

    </style>
</head>

<body>
    <?php
// Configuración de la conexión a la base de datos
include 'conexion.php';

// Verificar si se recibió el ID del proyecto
if (isset($_GET['id'])) {
    $proyectoId = $_GET['id'];

    // Consulta para obtener la información completa del proyecto
    $detalleProyectoQuery = "SELECT p.titulo, p.tutor_academico, p.tutor_empresarial, p.jurado, p.fecha, g.seccion, g.trayecto, p.pdf, e.nombre as estudiante_nombre, e.cedula
                            FROM proyectos p
                            JOIN grupos g ON p.grupo_id = g.id
                            JOIN estudiantes_grupos eg ON g.id = eg.grupo_id
                            JOIN estudiantes e ON eg.estudiante_id = e.id
                            WHERE p.id = $proyectoId";

    $detalleProyectoResult = $conn->query($detalleProyectoQuery);

    if ($detalleProyectoResult) {
        if ($detalleProyectoResult->num_rows > 0) {
            $proyecto = $detalleProyectoResult->fetch_assoc();

            // Consultas para obtener los nombres de los docentes
            $tutorAcademicoQuery = "SELECT nombre FROM docentes WHERE id = " . $proyecto['tutor_academico'];
            $juradoQuery = "SELECT nombre FROM docentes WHERE id = " . $proyecto['jurado'];

            $tutorAcademicoResult = $conn->query($tutorAcademicoQuery);
            $juradoResult = $conn->query($juradoQuery);

            $tutorAcademicoNombre = ($tutorAcademicoResult && $tutorAcademicoResult->num_rows > 0) ? $tutorAcademicoResult->fetch_assoc()['nombre'] : 'No disponible';
            $juradoNombre = ($juradoResult && $juradoResult->num_rows > 0) ? $juradoResult->fetch_assoc()['nombre'] : 'No disponible';

            echo '<div id="detalles-container">';
            echo '<h1>Detalles del Proyecto</h1>';
            echo '<ul>';
            echo '<li><strong>Título:</strong> ' . $proyecto['titulo'] . '</li>';
            echo '<li><strong>Tutor Académico:</strong> ' . $tutorAcademicoNombre . '</li>';
            echo '<li><strong>Tutor Empresarial:</strong> ' . $proyecto['tutor_empresarial'] . '</li>';
            echo '<li><strong>Jurado:</strong> ' . $juradoNombre . '</li>';
            echo '<li><strong>Fecha:</strong> ' . $proyecto['fecha'] . '</li>';
            echo '<li><strong>Sección:</strong> ' . $proyecto['seccion'] . '</li>';
            echo '<li><strong>Trayecto:</strong> ' . $proyecto['trayecto'] . '</li>';
            echo '<li><strong>PDF:</strong> <button><a href="pdf/' . $proyecto['pdf'] . '" download>Descargar </a></button></li>';
            echo '</ul>';

            // Mostrar información de todos los estudiantes asociados al proyecto
            echo '<h2>Estudiantes Asociados</h2>';
            echo '<ul>';

            // Reiniciar el puntero del resultado
            $detalleProyectoResult->data_seek(0);

            while ($estudiante = $detalleProyectoResult->fetch_assoc()) {
                echo '<li>' . $estudiante['estudiante_nombre'] . ' - ' . $estudiante['cedula'] . '</li>';
            }

            echo '</ul>';

            // Botones de Acciones
            echo '<div id="acciones-proyecto">';
            echo '<button onclick="location.href=\'editarpro.php?id=' . $proyectoId . '\'">Editar</button>';
            echo '<button onclick="if(confirm(\'¿Estás seguro de que quieres borrar este proyecto?\')){ location.href=\'borrarpro.php?id=' . $proyectoId . '\'; }">Borrar</button>';
            echo '<button onclick="location.href=\'buspro.php\'">Volver Atrás</button>';
            echo '</div>';
        } else {
            echo '<p>No hay proyectos registrados con el ID proporcionado.</p>';
        }
    } else {
        echo '<p>Error en la consulta de proyectos: ' . $conn->error . '</p>';
    }
} else {
    echo '<p>ID del proyecto no válido.</p>';
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

</body>

</html>
