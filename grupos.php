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
<html>
<head>
	<title>grupos</title>

	
</head>
    <style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f2f2f2;
}

h2 {
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
    color: #333;
}

form {
    margin-top: 15px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

label {
    display: block;
    margin-bottom: 8px;
}

input[type="text"],
input[type="submit"],
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #333;
    color: #fff;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #555;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
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
// grupos.php

include 'conexion.php';

// Verificar si se proporcionaron los parámetros desde detalles.php
if (isset($_POST['seccion'], $_POST['trayecto'], $_POST['periodo'])) {
    $seccion = $_POST['seccion'];
    $trayecto = $_POST['trayecto'];
    $periodo = $_POST['periodo'];

    // Puedes utilizar estos valores para realizar consultas y operaciones específicas en grupos.php
} else {
    echo '<p>Parámetros no válidos.</p>';
    exit; // Salir del script si no hay parámetros
}

// Procesar la creación de grupos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_grupo'])) {
    $numero_grupo = isset($_POST['numero_grupo']) ? $_POST['numero_grupo'] : null;
    $estudiantes_seleccionados = isset($_POST['estudiantes_seleccionados']) ? $_POST['estudiantes_seleccionados'] : [];

    // Validar que el número de grupo sea un valor numérico y no esté duplicado
    if (!ctype_digit($numero_grupo) || $numero_grupo <= 0) {
        echo '<p>El número de grupo debe ser un valor numérico y mayor que 0.</p>';
    } elseif (count($estudiantes_seleccionados) > 3) {
        echo '<p>Solo puedes seleccionar hasta 3 estudiantes por grupo.</p>';
    } else {
        // Verificar duplicidad del número de grupo
        $consulta_duplicidad_grupo = "SELECT id FROM grupos WHERE numero = $numero_grupo AND seccion = '$seccion' AND trayecto = '$trayecto' AND periodo = '$periodo'";
        $resultado_duplicidad_grupo = $conn->query($consulta_duplicidad_grupo);

        if ($resultado_duplicidad_grupo && $resultado_duplicidad_grupo->num_rows > 0) {
            echo '<p>El número de grupo ya está en uso para esta sección, trayecto y periodo.</p>';
        } else {
            // Iniciar transacción para garantizar operaciones atómicas
            $conn->begin_transaction();

            try {
                // Ejemplo de consulta para insertar el nuevo grupo
                $insertar_grupo_query = "INSERT INTO grupos (numero, seccion, trayecto, periodo) 
                                        VALUES ($numero_grupo, '$seccion', '$trayecto', '$periodo')";
                $resultado_insercion_grupo = $conn->query($insertar_grupo_query);

                if (!$resultado_insercion_grupo) {
                    throw new Exception('Error al crear el grupo: ' . $conn->error);
                }

                // Obtener el ID del grupo recién insertado
                $grupo_id = $conn->insert_id;

                // Insertar registros en la tabla de relación estudiantes_grupos
                foreach ($estudiantes_seleccionados as $estudiante_id) {
                    // Verificar si el estudiante ya está registrado en algún grupo
                    $consulta_estudiante_existente = "SELECT id FROM estudiantes_grupos WHERE estudiante_id = $estudiante_id";
                    $resultado_estudiante_existente = $conn->query($consulta_estudiante_existente);

                    if ($resultado_estudiante_existente && $resultado_estudiante_existente->num_rows === 0) {
                        $insertar_estudiante_grupo_query = "INSERT INTO estudiantes_grupos (estudiante_id, grupo_id) 
                                                            VALUES ($estudiante_id, $grupo_id)";
                        $resultado_insercion_estudiante_grupo = $conn->query($insertar_estudiante_grupo_query);

                        if (!$resultado_insercion_estudiante_grupo) {
                            throw new Exception('Error al asociar estudiantes con el grupo: ' . $conn->error);
                        }
                    }
                }

                // Confirmar la transacción si todas las operaciones se realizaron con éxito
                $conn->commit();

                echo '<p>Grupo creado exitosamente.</p>';
            } catch (Exception $e) {
                // Revertir la transacción en caso de cualquier error
                $conn->rollback();
                echo '<p>Error: ' . $e->getMessage() . '</p>';
            }
        }
    }
}

// Consultar grupos existentes para la sección, trayecto y periodo
$consulta_grupos = "SELECT * FROM grupos WHERE seccion = '$seccion' AND trayecto = '$trayecto' AND periodo = '$periodo'";
$resultado_grupos = $conn->query($consulta_grupos);

if ($resultado_grupos && $resultado_grupos->num_rows > 0) {
    // Mostrar la lista de grupos
    echo '<h2>Grupos existentes</h2>';
    while ($grupo = $resultado_grupos->fetch_assoc()) {
        echo '<p>Grupo: ' . $grupo['numero'] . '</p>';
        // Puedes mostrar más información sobre el grupo si es necesario
    }
} else {
    echo '<p>No hay grupos registrados para esta sección, trayecto y periodo.</p>';
}

// Consultar estudiantes para la sección, trayecto y periodo
$consulta_estudiantes = "SELECT id, nombre, cedula FROM estudiantes WHERE seccion = '$seccion' AND trayecto = '$trayecto' AND periodo = '$periodo'";
$resultado_estudiantes = $conn->query($consulta_estudiantes);

if ($resultado_estudiantes && $resultado_estudiantes->num_rows > 0) {
    // Mostrar la lista de estudiantes para seleccionar
    echo '<h2>Lista de Estudiantes</h2>';
    echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
    echo '<input type="hidden" name="seccion" value="' . $seccion . '">';
    echo '<input type="hidden" name="trayecto" value="' . $trayecto . '">';
    echo '<input type="hidden" name="periodo" value="' . $periodo . '">';
    echo '<label for="numero_grupo">Número del Grupo:</label>';
    echo '<input type="text" name="numero_grupo" required>';
    echo '<table>';
    echo '<tr>';
    echo '<th>Nombre del Estudiante</th>';
    echo '<th>Cédula</th>';
    echo '<th>Seleccionar</th>';
    echo '</tr>';

    while ($estudiante = $resultado_estudiantes->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $estudiante['nombre'] . '</td>';
        echo '<td>' . $estudiante['cedula'] . '</td>';
        
        // Verificar si el estudiante ya está registrado en algún grupo
        $consulta_estudiante_existente = "SELECT id FROM estudiantes_grupos WHERE estudiante_id = " . $estudiante['id'];
        $resultado_estudiante_existente = $conn->query($consulta_estudiante_existente);

        if ($resultado_estudiante_existente && $resultado_estudiante_existente->num_rows === 0) {
            echo '<td><input type="checkbox" name="estudiantes_seleccionados[]" value="' . $estudiante['id'] . '"></td>';
        } else {
            echo '<td>Ya seleccionado</td>';
        }
        
        echo '</tr>';
    }

    echo '</table>';
    echo '<input type="submit" name="crear_grupo" value="Crear Grupo">';
    echo '</form>';
} else {
    echo '<p>No hay estudiantes registrados para esta sección, trayecto y periodo.</p>';
}


// Cerrar la conexión a la base de datos
$conn->close();
?>

</body>
</html>
