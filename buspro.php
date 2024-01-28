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
    <meta charset="UTF-8">
    <title>Lista de Proyectos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        #proyectos-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            text-align: center;
            margin-top: 30px;
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

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .proyecto-item {
            cursor: pointer;
            color: #3498db;
            text-decoration: none;
            display: block;
            margin: 10px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .proyecto-item:hover {
            background-color: #ecf0f1;
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
    <div id="proyectos-container">
        <h1>Lista de Proyectos Registrados</h1>

        <label for="busqueda">Buscar por Título:</label>
        <input type="text" id="busqueda" oninput="filtrarProyectos()">

        <div id="proyectos-lista">
            <?php
            // Configuración de la conexión a la base de datos
            include 'conexion.php';

            // Consulta para obtener la información de todos los proyectos con detalles del grupo y estudiantes
            $proyectosQuery = "SELECT p.id, p.titulo, p.tutor_academico, p.tutor_empresarial, p.jurado, p.fecha, g.seccion, g.trayecto
                              FROM proyectos p
                              JOIN grupos g ON p.grupo_id = g.id";

            $proyectosResult = $conn->query($proyectosQuery);

            if ($proyectosResult) {
                if ($proyectosResult->num_rows > 0) {
                    while ($proyecto = $proyectosResult->fetch_assoc()) {
                        echo '<a href="detapro.php?id=' . $proyecto['id'] . '" class="proyecto-item">';
                        echo '<strong>' . $proyecto['titulo'] . '</strong><br>';
                        echo 'Sección: ' . $proyecto['seccion'] . ' - Trayecto: ' . $proyecto['trayecto'] . '<br>';
                        echo 'Fecha: ' . $proyecto['fecha'];
                        echo '</a>';
                    }
                } else {
                    echo '<p>No hay proyectos registrados.</p>';
                }
            } else {
                echo '<p>Error en la consulta de proyectos: ' . $conn->error . '</p>';
            }

            // Cerrar la conexión a la base de datos
            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function filtrarProyectos() {
            var input, filter, proyectos, proyectoItem, i, txtValue;
            input = document.getElementById("busqueda");
            filter = input.value.toUpperCase();
            proyectos = document.getElementById("proyectos-lista");
            proyectoItem = proyectos.getElementsByClassName("proyecto-item");

            for (i = 0; i < proyectoItem.length; i++) {
                txtValue = proyectoItem[i].textContent || proyectoItem[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    proyectoItem[i].style.display = "";
                } else {
                    proyectoItem[i].style.display = "none";
                }
            }
        }
    </script>

</body>

</html>
