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
    <title>Seleccione una sección para el registro</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h1, h2 {
    text-align: center;
    color: #333;
}

form {
    text-align: center;
    margin-bottom: 20px;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

input[type="text"] {
    padding: 10px;
    width: 300px;
    border: 1px solid #ccc;
}

button {
    padding: 10px 20px;
    background-color: #4caf50;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0052a3;
}

.no-results {
    text-align: center;
    color: #333;
    margin-top: 20px;
}

.project-list {
    list-style-type: none;
    padding: 0;
    margin-top: 30px;
}

.project-list-item {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    width: 400px;
    text-align: center;
}

.project-list-item:hover {
    background-color: #f2f2f2;
}

.project-details {
    font-size: 16px;
    color: #333;
}

.view-groups-button {
    display: block;
    text-align: center;
    margin-top: 10px;
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

    <h1>Seleccione una sección para el registro</h1>

    <form method="GET" action="">
        <label for="search">Buscar sección:</label>
        <input type="text" id="search" name="search" placeholder="Ingrese sección">
        <button type="submit">Buscar</button>
    </form>

    <?php
    include 'conexion.php';

    // Verificar si se realizó una búsqueda
    if (isset($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $sql = "SELECT MAX(id) as id, periodo, trayecto, seccion FROM estudiantes WHERE periodo LIKE '%$searchTerm%' GROUP BY periodo, trayecto, seccion";
    } else {
        $sql = "SELECT MAX(id) as id, periodo, trayecto, seccion FROM estudiantes GROUP BY periodo, trayecto, seccion";
    }

    // Realizar la consulta
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<h2>Seleccione una sección para el registro:</h2>';
        echo '<ul class="project-list">';

        while ($row = $result->fetch_assoc()) {
            displayProjectInfo($row);
        }

        echo '</ul>';
    } else {
        echo '<p class="no-results">No se encontraron secciones';
        if (isset($searchTerm)) {
            echo ' que coincidan con la búsqueda';
        }
        echo '.</p>';
    }

    $conn->close();

    // Función para mostrar la información del proyecto
    function displayProjectInfo($row) {
        $projectPeriod = isset($row['periodo']) ? $row['periodo'] : 'Sin periodo';
        $projectSection = isset($row['seccion']) ? $row['seccion'] : 'Sin sección';
        $projectPath = isset($row['trayecto']) ? $row['trayecto'] : 'Sin trayecto';

        $projectId = $row['id'];
        echo '<li class="project-list-item" onclick="showProjectInfo(' . $projectId . ')">';
        echo '<p class="project-details">Año: ' . $projectPeriod . ', Sección: ' . $projectSection . ', Trayecto: ' . $projectPath . '</p>';
    
        // Agrega el formulario para ver grupos
        echo '<form class="view-groups-button" method="get" action="selegru.php">';
        echo '<input type="hidden" name="seccion" value="' . $projectSection . '">';
        echo '<input type="hidden" name="trayecto" value="' . $projectPath . '">';
        echo '<input type="hidden" name="periodo" value="' . $projectPeriod . '">';
        echo '<button type="submit">Ver Grupos</button>';
        echo '</form>';
        
        echo '</li>';
    }
    ?>

</body>

</html>
