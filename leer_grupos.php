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
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
        }

        .grupo-container {
            background-color: #fff;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            width: 300px; /* Ancho del cuadro del grupo */
            float: left; /* Hace que los cuadros de grupo se alineen en línea */
            margin-right: 20px; /* Espacio entre los cuadros de grupo */
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
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
                    echo '<h2>Grupo ' . $grupo['numero'] . '</h2>';
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
                            echo '<table>';
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

                    echo '</div>'; // Cierre del contenedor del grupo
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
