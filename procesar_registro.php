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

        #contenedor {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 600px;
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
            width: calc(100% - 22px);
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
            display: block;
            margin: 20px auto; /* Centra el botón */
        }

        button:hover {
            background-color: #45a049;
        }

        .mensaje {
            margin: 20px 0;
            padding: 10px;
            background-color: #f44336;
            color: white;
            border-radius: 5px;
        }

        .exito {
            margin: 20px 0;
            padding: 10px;
            background-color: #f7f5f5;
            color: green;
            border-radius: 5px;
        }    </style>
</head>

<body>

    <div id="contenedor">
        <h1>Formulario de Proyecto</h1>

 <?php
// Configuración de la conexión a la base de datos
include 'conexion.php';

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $titulo = $_POST['titulo'];
    $tutorAcademico = $_POST['tutor_academico'];
    $tutorEmpresarial = $_POST['tutor_empresarial'];
    $jurado = $_POST['jurado'];
    $fecha = $_POST['fecha'];
    $grupoId = $_POST['grupo_id'];

    // Validar que el Tutor Académico y el Jurado no sean la misma persona
    if ($tutorAcademico == $jurado) {
        echo '<div class="mensaje">El Tutor Académico y el Jurado no pueden ser la misma persona.</div>';
    } else {
        // Verificar si ya existe un proyecto con los mismos tutores académicos y jurados
        $verificarProyectoQuery = "SELECT id FROM proyectos 
                                    WHERE (tutor_academico = '$tutorAcademico' AND jurado = '$jurado')
                                    OR (tutor_academico = '$jurado' AND jurado = '$tutorAcademico')";
        $verificarResult = $conn->query($verificarProyectoQuery);

        if ($verificarResult->num_rows > 0) {
            echo '<div class="mensaje">Ya existe un proyecto con el mismo Tutor Académico y Jurado.</div>';
        } else {
            // Procesar y guardar los datos en la base de datos
            $insertProyectoQuery = "INSERT INTO proyectos (titulo, tutor_academico, tutor_empresarial, jurado, fecha, grupo_id)
                                    VALUES ('$titulo', '$tutorAcademico', '$tutorEmpresarial', '$jurado', '$fecha', $grupoId)";

            if ($conn->query($insertProyectoQuery) === TRUE) {
                // Obtener el ID del proyecto recién insertado
                $proyectoId = $conn->insert_id;

                // Procesar la carga del archivo PDF
                $pdfFileName = $_FILES['pdf']['name'];
                $pdfTempName = $_FILES['pdf']['tmp_name'];
                $pdfLocation = "C:/xampp/htdocs/sistema/pdf/" . $pdfFileName; // Ruta donde deseas almacenar los PDF

                if (move_uploaded_file($pdfTempName, $pdfLocation)) {
                    // Actualizar el registro del proyecto con el nombre del PDF
                    $updateProyectoQuery = "UPDATE proyectos SET pdf = '$pdfFileName' WHERE id = $proyectoId";

                    if ($conn->query($updateProyectoQuery) === TRUE) {
                        echo '<div class="exito">Proyecto registrado exitosamente.</div>';
                    } else {
                        echo '<div class="mensaje">Error al actualizar el nombre del PDF: ' . $conn->error . '</div>';
                    }
                } else {
                    echo '<div class="mensaje">Error al subir el archivo PDF.</div>';
                }
            } else {
                echo '<div class="mensaje">Error al registrar el proyecto: ' . $conn->error . '</div>';
            }
        }
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Si se intenta acceder al script directamente sin enviar datos del formulario
    echo '<div class="mensaje">Acceso no autorizado.</div>';
}
?>

<!-- Botón para redirigir a selegru.php -->
<button onclick="window.location.href='proest.php'">Seleccionar otro seccion</button>
</div>
</body>
</html>


</body>

</html>
