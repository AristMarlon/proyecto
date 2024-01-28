<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ya está autenticado
if (isset($_SESSION['usuario'])) {
    // Redirigir al usuario a la página principal u otra página después del inicio de sesión
    header('Location: menu.html');
    exit();
}

// Verificar si se han enviado los datos del formulario
if (isset($_POST['usuario']) && isset($_POST['contraseña'])) {
    // Conectar a la base de datos
    $conexion = mysqli_connect('localhost', 'root', '', 'sistema');
    
    if (!$conexion) {
        die("Error al conectar a la base de datos: " . mysqli_connect_error());
    }

    // Escapar los datos para evitar inyección SQL
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $contraseña = mysqli_real_escape_string($conexion, $_POST['contraseña']);

    // Consultar la base de datos
    $consulta = "SELECT * FROM usuarios WHERE nombre_usuario='$usuario' AND contrasena='$contraseña'";
    $resultado = mysqli_query($conexion, $consulta);

    // Verificar si la consulta SQL se ejecutó correctamente
    if ($resultado) {
        // Verificar si se encontraron resultados
        if (mysqli_num_rows($resultado) == 1) {
            // Iniciar sesión y redirigir al usuario a la página principal
            $_SESSION['usuario'] = $usuario;
            header('Location: menuu.php');
            exit();
        } else {
            // Mostrar mensaje de error
            $mensaje_error = 'Usuario o contraseña incorrecto';
        }
    } else {
        // Mostrar mensaje de error
        $mensaje_error = 'Error al ejecutar la consulta SQL: ' . mysqli_error($conexion);
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sistema de login</title>
    <style>
       body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            position: relative;
        }

        h1 img {
            display: block;
            margin: 10 auto; /* Centra horizontalmente la imagen */
            max-width: 100px; /* Ajusta el ancho de la imagen según sea necesario */
            margin-left: 20px; /* Mueve la imagen 20px a la derecha */

        }

        form {
            max-width: 300px;
            margin: 50px auto; /* Ajusta la distancia superior según sea necesario */
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .show-password {
            position: relative;
        }

        #show-password-checkbox {
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php
    // Mostrar mensaje de error si existe
    if (isset($mensaje_error)) {
        echo '<div class="error">' . $mensaje_error . '</div>';
    }
    ?>

    <!-- Mostrar formulario de inicio de sesión -->
    <form method="post">
        <img src="img/ima.png" alt="Logo">
        <h1>Sistema de Usuarios</h1>
        <p>Usuario<input type="text" placeholder="Ingrese su nombre" name="usuario"></p>
        <p class="show-password">Contraseña<input type="password" placeholder="Ingrese su contraseña" name="contraseña" id="password-input"><input type="checkbox" id="show-password-checkbox" onclick="togglePasswordVisibility()"></p>
        <input type="submit" value="Ingresar">
    </form>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password-input");
            var checkbox = document.getElementById("show-password-checkbox");

            if (checkbox.checked) {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>

</html>
