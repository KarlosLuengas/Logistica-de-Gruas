<?php
session_start();
 

// Inicializa un mensaje vacío
$mensaje = array('texto' => '', 'clase' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['usuario']) && isset($_POST['passwor'])) {
        $username = $_POST['usuario'];
        $passwor = $_POST['passwor'];

        // Conexión a la base de datos (ajusta estos valores según tu configuración)
        $host = "localhost";
        $usu = "root";
        $contrasena = "";
        $base_de_datos = "gruask";

        $conexion = new mysqli($host, $usu, $contrasena, $base_de_datos);

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Consulta SQL para verificar las credenciales y obtener el tipo de usuario
        $sql = "SELECT IDUsuario, usuario, passwor, tipo FROM usuarios WHERE usuario = ? AND passwor = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $username, $passwor);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc(); 
                    // Las credenciales son válidas, el usuario ha iniciado sesión con éxito

                    // Almacenar el tipo de usuario en la sesión
                
                    $_SESSION['usuario_id'] = $row['IDUsuario'];

                    // Redirigir a la página correspondiente según el tipo de usuario
                    switch ($row['tipo']) {
                        case "cliente":
                            header("Location: Historial.php"); // Redirige a la página de cliente
                            exit;
                        case "callcenter":
                            header("Location: form_call.php"); // Redirige a la página de call center
                            exit; 
                        default:
                            $mensaje = array('texto' => 'Tipo de usuario no válido. Por favor, inténtalo de nuevo.', 'clase' => 'alert-danger');
                    }
                 
            } else {
                $mensaje = array('texto' => 'Usuario no encontrado. Por favor, inténtalo de nuevo.', 'clase' => 'alert-danger');
            }
        } else {
            $mensaje = array('texto' => 'Error en la consulta. Por favor, inténtalo de nuevo.', 'clase' => 'alert-danger');
        }

        $stmt->close();
        $conexion->close();
    } else {
        $mensaje = array('texto' => 'Debes proporcionar nombre de usuario y contraseña.', 'clase' => 'alert-danger');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background: url('img/GBP.jpeg') no-repeat center center fixed;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .login-container {
            background-color: rgba(128, 0, 0, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin-top: 50px;
        }

        #logo {
            height: 200px;
            width: 200px;
            position: absolute;
        }

        .btn-vino {
            width: 100%;
            height: 20%;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-dark bg-danger">
  <div class="container">
      <a class="navbar-brand" >Servicios de Grua</a>
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" href="index.php">Inicio</a>
          </li>
         
    

      </ul>
  </div>
</nav>
<img id="logo" src="img/logo2.png" alt="Logo Gobierno de Puebla">
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="login-container">
                   
                    <h3 class="text-white">Iniciar Sesión</h3>
                    <form class="text-white" method="post" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label text-white">Nombre de usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario"
                                placeholder="Nombre de usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label text-white">Contraseña</label>
                            <input type="password" class="form-control" id="passwor" name="passwor"
                                placeholder="Contraseña" required>
                        </div>
                        <button type="submit" class="btn btn-vino btn-lg">Iniciar Sesión</button>
                        <a href="recuperar.php" class="btn btn-link">¿Olvidaste tu contraseña?</a>
                        <a href="registro_cli.php" class="btn btn-link">Registrarse</a>
                    </form>
                    <?php
                    if ($mensaje) {
                        echo '<div class="alert ' . $mensaje['clase'] . '" role="alert">' . $mensaje['texto'] . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>