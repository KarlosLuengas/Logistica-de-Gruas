<?php
    // Inicializa un mensaje vacío
    $mensaje = array('texto' => '', 'clase' => '');
    
    // Verifica si el formulario ha sido enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtén los valores del formulario
        $correo = $_POST["correo"];
        $usuario = $_POST["usuario"];
        $contrasenaform = $_POST["contrasena"];
        $nueva_contrasena = $_POST["nueva_contrasena"]; 
        // Conexión a la base de datos (ajusta estos valores según tu configuración)
        $host = "localhost";
        $usu = "root";
        $contrasena = "";
        $base_de_datos = "gruask";

        // Crear una conexión a la base de datos
        $conexion = new mysqli($host, $usu, $contrasena, $base_de_datos);

        // Verifica si la conexión fue exitosa
        if ($conexion->connect_error) {
            $mensaje = array('texto' => 'Error del sistema.', 'clase' => 'alert-danger');

        }

        // Escapa los valores para evitar inyección de SQL
        $usuario = $conexion->real_escape_string($usuario);
        $correo = $conexion->real_escape_string($correo);
        $contrasenaform = $conexion->real_escape_string($contrasenaform);
        $nueva_contrasena = $conexion->real_escape_string($nueva_contrasena);

        // Consulta para verificar la existencia del usuario y la contraseña actual
        $consulta = "SELECT * FROM usuarios WHERE email = '$correo' AND usuario = '$usuario'";
        $resultado = $conexion->query($consulta);

        // Verifica si se encontró un usuario con el correo y la contraseña proporcionados
        if ($resultado->num_rows > 0) {
            if($contrasenaform == $nueva_contrasena){
                // Actualiza la contraseña del usuario
                $consulta_actualizar = "UPDATE usuarios SET passwor = '$nueva_contrasena' WHERE email = '$correo'";
                $resultado_actualizar = $conexion->query($consulta_actualizar);

                // Verifica si la actualización fue exitosa
                if ($resultado_actualizar) {
                $mensaje = array('texto' => 'Contraseña actualizada exitosamente.', 'clase' => 'alert-success');

                }
                else {
                    $mensaje = array('texto' => 'Error al cambiar la contraseña.', 'clase' => 'alert-danger');

                }
            }
            else{
            $mensaje = array('texto' => 'Las contraseñas no coinciden.', 'clase' => 'alert-danger');

            }
        } else {
        $mensaje = array('texto' => 'Correo electrónico y/o usuario incorrectos.', 'clase' => 'alert-danger');

        }
       
    // Cierra la conexión a la base de datos
        $conexion->close();

    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cambio de contraseña</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
                
                    <h3 class="text-white">Cambio de contraseña</h3>
                    <form class="text-white" method="post" action="">

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                        </div>
                        <div class="mb-3">
                            <label for="nueva-contrasena" class="form-label">nueva Contraseña</label>
                            <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
                        </div>
                        <button  type="submit" class="btn btn-vino btn-lg">Cambiar</button>
                    </form>
                    <?php
                    if ($mensaje) {
                        echo '<div class="alert ' . $mensaje['clase'] . '" role="alert">' . $mensaje['texto'] . '</div>';
                    }
                    ?>
                    <a href="login.php" class="btn btn-link">Inicio sesion</a>
                </div>
            </div>
        </div>
    </div>
    
    

    </body>
    </html>