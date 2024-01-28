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
<!-- leer_grupos.php -->
<html lang="es">

<head>
    <title>Lista de Grupos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f2f2f2;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .grupo-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            width: 300px;
        }

        .grupo-container h2, h3 {
            color: #4caf50;
        }

        .grupo-container p {
            margin: 10px 0;
        }

        .estudiantes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .estudiantes-table th, .estudiantes-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .proyecto-btn {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        <a href="proest.php">
            <button class="button">Atras</button>
        </a>
    </div>

    <br>
    <br>
    <br>

    <?php
    // Configuración de la conexión a la base de datos
    include 'conexion.php';

    // Verificar si se proporcionaron sección, trayecto y periodo válidos
    if (isset($_GET['seccion'], $_GET['trayecto'], $_GET['periodo'])) {
        $seccion = $_GET['seccion'];
        $trayecto = $_GET['trayecto'];
        $periodo = $_GET['periodo'];

        // Consulta para obtener la información de los grupos en la sección, trayecto y periodo
        $gruposQuery = "SELECT * FROM grupos WHERE seccion = '$seccion' AND trayecto = '$trayecto' AND periodo = '$periodo'";
        $gruposResult = $conn->query($gruposQuery);

        if ($gruposResult) {
            if ($gruposResult->num_rows > 0) {
                // Mostrar la información de cada grupo
                while ($grupo = $gruposResult->fetch_assoc()) {
                    echo '<div class="grupo-container">';
                    echo '<h2>Información del Grupo</h2>';
                    echo '<p><strong>Grupo:</strong> ' . $grupo['numero'] . '</p>';
                    echo '<p><strong>Sección:</strong> ' . $grupo['seccion'] . '</p>';
                    echo '<p><strong>Trayecto:</strong> ' . $grupo['trayecto'] . '</p>';
                    echo '<p><strong>Periodo:</strong> ' . $grupo['periodo'] . '</p>';

                    // Consulta para obtener la lista de estudiantes en el grupo
                    $grupoId = $grupo['id'];
                    $estudiantesQuery = "SELECT estudiantes.nombre, estudiantes.cedula FROM estudiantes
                                         JOIN estudiantes_grupos ON estudiantes.id = estudiantes_grupos.estudiante_id
                                         WHERE estudiantes_grupos.grupo_id = $grupoId";
                    $estudiantesResult = $conn->query($estudiantesQuery);

                    if ($estudiantesResult) {
                        if ($estudiantesResult->num_rows > 0) {
                            // Mostrar la lista de estudiantes
                            echo '<h3>Estudiantes del Grupo</h3>';
                            echo '<table class="estudiantes-table">';
                            echo '<tr>';
                            echo '<th>Nombre</th>';
                            echo '<th>Cédula</th>';
                            echo '</tr>';

                            while ($estudiante = $estudiantesResult->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $estudiante['nombre'] . '</td>';
                                echo '<td>' . $estudiante['cedula'] . '</td>';
                                echo '</tr>';
                            }

                            echo '</table>';
                        } else {
                            echo '<p>No hay estudiantes registrados para este grupo.</p>';
                        }
                    } else {
                        echo '<p>Error en la consulta de estudiantes: ' . $conn->error . '</p>';
                    }

                    // Botón para vincular al formulario de registro
                    echo '<form action="rgpro.php" method="get">';
                    echo '<input type="hidden" name="grupo_id" value="' . $grupoId . '">';
                    echo '<button type="submit" class="proyecto-btn">Registrar Proyecto</button>';
                    echo '</form>';

                    echo '</div>';
                }
            } else {
                echo '<p>No hay grupos registrados para esta sección, trayecto y periodo.</p>';
            }
        } else {
            echo '<p>Error en la consulta de grupos: ' . $conn->error . '</p>';
        }
    } else {
        echo '<p>Parámetros de sección, trayecto y periodo no válidos.</p>';
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
    ?>
</body>

</html>
