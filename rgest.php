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
    <title>Registro de Estudiantes</title>
    <style>
   body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

#formulario {
    background-color: #fff;
    padding: 10px; /* Reducido el padding */
    max-width: 400px;
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 20px;
}

#formulario h2 {
    margin-top: 0;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 10px;
}

input[type="text"],
input[type="number"],
input[type="tel"],
input[type="email"],
select {
    width: 100%;
    padding: 8px; /* Reducido el padding */
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-bottom: 10px;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 8px 16px; /* Reducido el padding */
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

#lista-estudiantes {
    background-color: #fff;
    padding: 20px;
}

#lista-estudiantes h2 {
    margin-top: 0;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}

form button {
    background-color: #f44336;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

form button:hover {
    background-color: #d32f2f;
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
       <a href="menuu.php">
  <button class="button">Atras</button>
</a>


    </div>
<br>
<br>
<br>
<br>
<br>

<div class="container">
    <div id="formulario">
        <h2>Registro de Estudiantes</h2>

        <!-- Formulario de registro -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" name="nombre" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚüÜ\s']+" required>

            <label for="cedula">Cedula de identidad:</label>
            <input type="number" name="cedula" pattern="[0-9]+" maxlength="8" required>

            <label for="telefono">Telefono:</label>
            <input type="number" name="telefono" pattern="[0-9]+" maxlength="11" required>

            <label for="correo">Correo electronico:</label>
            <input type="email" name="correo">

            <label for="seccion">Sección:</label>
            <select name="seccion" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
            
            <label for="trayecto">Trayecto:</label>
            <select name="trayecto" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>

            <label for="periodo">Periodo:</label>
           <select name="periodo" required>
              <?php
            $inicio = 2018;
               $fin = 2029;

       for ($i = $inicio; $i <= $fin; $i++) {
         $nextYear = $i + 1;
         echo "<option value='$i-$nextYear'>$i-$nextYear</option>";
    }
       ?>
</select>

            <input type="submit" value="Registrar">
        </form>

        <?php
            include 'conexion.php';

            // Asegúrate de que se hayan enviado datos antes de procesarlos
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
                $cedula = isset($_POST['cedula']) ? $_POST['cedula'] : '';
                $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
                $correo = isset($_POST['correo']) ? $_POST['correo'] : '';
                $seccion = isset($_POST['seccion']) ? $_POST['seccion'] : '';
                $periodo = isset($_POST['periodo']) ? $_POST['periodo'] : '';
                $trayecto = isset($_POST['trayecto']) ? $_POST['trayecto'] : '';

                // Validar la longitud de la cédula
                if (strlen($cedula) !== 8) {
                    echo "Error: La cédula debe tener 8 números.";
                    exit;
                }

                // Validar la longitud del teléfono
                if (strlen($telefono) !== 11) {
                    echo "Error: El teléfono debe tener 11 números.";
                    exit;
                }

                // Verificar si ya existe un estudiante con el mismo nombre, cédula, trayecto o periodo
                $verificacion_query = "SELECT * FROM estudiantes 
                                       WHERE nombre = '$nombre' 
                                       AND cedula = '$cedula' 
                                       AND trayecto >= '$trayecto' 
                                       AND periodo >= '$periodo'";
                $verificacion_resultado = $conn->query($verificacion_query);

                if ($verificacion_resultado->num_rows > 0) {
                    echo "Error: Ya existe un estudiante con el mismo nombre, cédula, trayecto o periodo.";
                } else {
                    // Todos los datos son válidos, proceder con la inserción en la base de datos
                    $sql = "INSERT INTO estudiantes (nombre, cedula, telefono, correo, seccion, periodo, trayecto) 
                            VALUES ('$nombre', '$cedula', '$telefono', '$correo' , '$seccion', '$periodo', '$trayecto')";

                    if ($conn->query($sql) === TRUE) {
                        echo "Estudiante registrado correctamente";
                    } else {
                        if ($conn->errno == 1062) {
                            echo "Error: Ya existe un estudiante con la misma cédula, sección y periodo.";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    }
                }
            } else {
                echo "No se han recibido datos del formulario.";
            }

            $conn->close();
        ?>
    </div>
</div>


     

<div id="lista-estudiantes">
    <h2>Lista de Estudiantes</h2>

    <?php
    include 'conexion.php';

    // Verificar si se ha enviado una solicitud de borrado
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrar_cedula'])) {
        $borrar_cedula = $_POST['borrar_cedula'];

        // Verificar si la cédula existe antes de intentar borrar
        $verificacion_query = "SELECT * FROM estudiantes WHERE cedula = '$borrar_cedula'";
        $verificacion_resultado = $conn->query($verificacion_query);

        if ($verificacion_resultado->num_rows > 0) {
            // Realizar la eliminación del estudiante con la cédula proporcionada
            $borrar_query = "DELETE FROM estudiantes WHERE cedula = '$borrar_cedula'";
            if ($conn->query($borrar_query) === TRUE) {
                echo "Estudiante eliminado correctamente";
            } else {
                echo "Error al eliminar el estudiante: " . $conn->error;
            }
        } else {
            echo "Error: No existe un estudiante con la cédula proporcionada.";
        }
    }

    // Consulta para obtener la lista de estudiantes
    $estudiantesQuery = "SELECT nombre, cedula, telefono, correo, seccion, periodo, trayecto FROM estudiantes";
    $estudiantesResult = $conn->query($estudiantesQuery);

    if ($estudiantesResult) {
        if ($estudiantesResult->num_rows > 0) {
            // Mostrar la lista de estudiantes en forma de tabla
            echo '<table>';
            echo '<tr>';
            echo '<th>Nombre</th>';
            echo '<th>Cedula</th>';
            echo '<th>Teléfono</th>';
            echo '<th>Correo</th>';
            echo '<th>Sección</th>';
            echo '<th>Trayecto</th>';
            echo '<th>Periodo</th>';
            echo '<th>Acciones</th>';
            echo '</tr>';

            while ($estudiante = $estudiantesResult->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $estudiante['nombre'] . '</td>';
                echo '<td>' . $estudiante['cedula'] . '</td>';
                echo '<td>' . $estudiante['telefono'] . '</td>';
                echo '<td>' . $estudiante['correo'] . '</td>';
                echo '<td>' . $estudiante['seccion'] . '</td>';
                echo '<td>' . $estudiante['trayecto'] . '</td>';
                echo '<td>' . $estudiante['periodo'] . '</td>';
                echo '<td><form method="POST" onsubmit="return confirm(\'¿Estás seguro de que deseas borrar a este estudiante?\')" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                echo '<input type="hidden" name="borrar_cedula" value="' . $estudiante['cedula'] . '">';
                echo '<button type="submit">Borrar</button>';
                echo '</form></td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<p>No hay estudiantes registrados.</p>';
        }
    } else {
        echo '<p>Error en la consulta de estudiantes: ' . $conn->error . '</p>';
    }

    $conn->close();
    ?>
</div>



</body>
</html>
