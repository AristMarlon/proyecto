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
    <title>Resultado de Borrar Proyecto</title>
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

        #mensaje-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }

        h1 {
            color: #333333;
            margin-bottom: 20px;
        }

        p {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
        }

        .exito {
            background-color: #f7f5f5;
            color: green;
        }

        .error {
            background-color: #f44336;
            color: white;
        }

        button {
            background-color: #4caf50;
            color: #ffffff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            margin: 20px auto; /* Centra el botón */
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div id="mensaje-container">
        <?php
        // Configuración de la conexión a la base de datos
        include 'conexion.php';

        echo '<h1>Resultado de Borrar Proyecto</h1>';

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            // Obtener el ID del proyecto a borrar
            $proyectoId = $_GET['id'];

            // Consulta para borrar el proyecto
            $borrarProyectoQuery = "DELETE FROM proyectos WHERE id = $proyectoId";

            if ($conn->query($borrarProyectoQuery)) {
                echo '<p class="exito">Proyecto borrado correctamente.</p>';
            } else {
                echo '<p class="error">Error al borrar el proyecto: ' . $conn->error . '</p>';
            }
        } else {
            echo '<p class="error">ID del proyecto no válido.</p>';
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
        ?>

        <!-- Botón para volver atrás -->
        <button onclick="window.location.href='buspro.php'">Volver Atrás</button>
    </div>
</body>

</html>
