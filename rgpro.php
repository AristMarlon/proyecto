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
    <title>Formulario de Proyecto</title>
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

.container {
    display: flex;
}

#formulario-container,
#info-container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    text-align: left;
    margin-right: 20px;
}

#formulario-container {
    width: 600px;
}

#info-container {
    width: 400px;
}

h1 {
    color: #333333;
    margin-bottom: 20px;
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

table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
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
    <div class="top-menu">
        <a href="proest.php">
            <button class="button">Atras</button>
        </a>
    </div>

    <br>
    <br>
    <br>
   
    <script>
        function habilitarDocentes() {
            // Obtener los elementos de ambas listas desplegables
            var tutorAcademicoSelect = document.getElementById('tutor_academico');
            var juradoSelect = document.getElementById('jurado');

            // Obtener el valor seleccionado en la lista de Tutor Académico
            var tutorAcademicoValue = tutorAcademicoSelect.value;

            // Habilitar todos los elementos en ambas listas desplegables
            for (var i = 0; i < tutorAcademicoSelect.options.length; i++) {
                tutorAcademicoSelect.options[i].disabled = false;
                juradoSelect.options[i].disabled = false;
            }

            // Deshabilitar el docente seleccionado en la lista de Jurado
            for (var i = 0; i < juradoSelect.options.length; i++) {
                if (juradoSelect.options[i].value == tutorAcademicoValue) {
                    juradoSelect.options[i].disabled = true;
                    break;
                }
            }
        }
    </script>
</head>

<body>

    <?php
    // Configuración de la conexión a la base de datos
    include 'conexion.php';

    // Verificar si se proporcionó un grupo_id válido
    if (isset($_GET['grupo_id'])) {
        $grupoId = $_GET['grupo_id'];

        // Obtener información del grupo, trayecto, sección y estudiantes
        $infoQuery = "SELECT g.numero AS grupo_numero, g.trayecto, g.seccion, e.nombre AS estudiante_nombre, e.cedula
                      FROM grupos g
                      JOIN estudiantes_grupos eg ON g.id = eg.grupo_id
                      JOIN estudiantes e ON eg.estudiante_id = e.id
                      WHERE g.id = $grupoId";

        $infoResult = $conn->query($infoQuery);

        // Obtener lista de docentes para las listas desplegables
        $docentesQuery = "SELECT id, nombre FROM docentes";
        $docentesResult = $conn->query($docentesQuery);

        if ($infoResult && $docentesResult) {
            $grupo = $infoResult->fetch_assoc();
                
              

            echo '<div id="formulario-container">';
            echo '<h1>Formulario de Proyecto</h1>';
            echo '<form action="procesar_registro.php" method="post" enctype="multipart/form-data">';
            echo '<label for="titulo">Título:</label>';
            echo '<input type="text" name="titulo" required><br>';

            // Lista desplegable para Tutor Académico
            echo '<label for="tutor_academico">Tutor Académico:</label>';
            echo '<select name="tutor_academico" id="tutor_academico" onchange="habilitarDocentes()" required>';
            while ($docente = $docentesResult->fetch_assoc()) {
                echo '<option value="' . $docente['id'] . '">' . $docente['nombre'] . '</option>';
            }
            echo '</select><br>';

            // Lista desplegable para Jurado
            echo '<label for="jurado">Jurado:</label>';
            echo '<select name="jurado" id="jurado" required>';
            $docentesResult->data_seek(0); // Reiniciar el puntero
            while ($docente = $docentesResult->fetch_assoc()) {
                echo '<option value="' . $docente['id'] . '">' . $docente['nombre'] . '</option>';
            }
            echo '</select><br>';

            echo '<label for="tutor_empresarial">Tutor Empresarial:</label>';
            echo '<input type="text" name="tutor_empresarial" required><br>';
            echo '<label for="fecha">Fecha:</label>';
            echo '<input type="date" name="fecha" required><br>';
            echo '<label for="pdf">Archivo PDF:</label>';
            echo '<input type="file" name="pdf" accept=".pdf" required><br>';
            echo '<input type="hidden" name="grupo_id" value="' . $grupoId . '">';
            echo '<button type="submit">Registrar Proyecto</button>';
            echo '</form>';
            echo '</div>';

            echo '<div id="info-container">';
            echo '<h1>Información del Grupo</h1>';
            echo '<p><strong>Grupo:</strong> ' . $grupo['grupo_numero'] . '</p>';
            echo '<p><strong>Trayecto:</strong> ' . $grupo['trayecto'] . '</p>';
            echo '<p><strong>Sección:</strong> ' . $grupo['seccion'] . '</p>';

            echo '<h2>Estudiantes del Grupo</h2>';
            echo '<table>';
            echo '<tr>';
            echo '<th>Nombre</th>';
            echo '<th>Cédula</th>';
            echo '</tr>';

            // Mostrar la lista de estudiantes
            do {
                echo '<tr>';
                echo '<td>' . $grupo['estudiante_nombre'] . '</td>';
                echo '<td>' . $grupo['cedula'] . '</td>';
                echo '</tr>';
            } while ($grupo = $infoResult->fetch_assoc());

            echo '</table>';
            echo '</div>';
        } else {
            echo '<p>Error al obtener información del grupo o docentes.</p>';
        }
    } else {
        echo '<p>Parámetro de grupo_id no válido.</p>';
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
    ?>

</body>

</html>
