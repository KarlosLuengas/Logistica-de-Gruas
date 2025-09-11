<?php
$mensaje = array('texto' => '', 'clase' => ''); // Definir $mensaje con valores por defecto

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos (ajusta estos valores según tu configuración)
    $host = "localhost";
        $usu = "root";
        $contrasena = "";
        $base_de_datos = "gruask";
    $conexion = new mysqli($host, $usu, $contrasena, $base_de_datos);

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Recuperar datos del formulario
    $usuario = $_POST['usuario'];
    $email = $_POST['correo'];
    $password = $_POST['password'];
    $tipo= $_POST['tipo'];
    $confirmar_password = $_POST['confirmar-password'];

    // Verificar si las contraseñas coinciden
    if ($password === $confirmar_password) {
        $sql = "INSERT INTO usuarios (usuario, email, passwor,tipo) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssss", $usuario, $email, $password,$tipo);

        if ($stmt->execute()) {
            // Mostrar un mensaje de éxito utilizando una alerta Bootstrap
            $mensaje = array('texto' => 'Usuario registrado con éxito.', 'clase' => 'alert-success');
        } else {
            // Mostrar un mensaje de error utilizando una alerta Bootstrap
            $mensaje = array('texto' => 'Error al registrar el usuario.', 'clase' => 'alert-danger');
        }

        $stmt->close();
    } else {
        // Mostrar un mensaje de error utilizando una alerta Bootstrap
        $mensaje = array('texto' => 'Las contraseñas no coinciden.', 'clase' => 'alert-danger');
    }
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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
              
                    
                    <h3 class="text-white">Registro</h3>
                    <form class="text-white" method="post" action="">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de usuario:</label>
                            <select class="form-control" id="tipo" name="tipo" required>
                                    <option value="cliente">Cliente</option>
                                </select>

                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmar-password" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="confirmar-password"
                                name="confirmar-password" required>
                        </div>
                        <button type="submit" class="btn btn-vino btn-lg text-white">Registrarse</button>
                        <a href="login.php" class="btn btn-link">Inicio de sesion</a>
                    </form>
                    <?php
                    // Verificar si $mensaje está definida y no está vacía antes de mostrarla
                    if (!empty($mensaje)) {
                        echo '<div class="alert ' . $mensaje['clase'] . '" role="alert">' . $mensaje['texto'] . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


</body>

</html>