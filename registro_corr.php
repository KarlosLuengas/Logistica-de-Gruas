<?php
session_start();

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    // Si el usuario no ha iniciado sesión, redirige de vuelta al login
    header('Location: login.php');
    exit();
} else {
    $usuario_id = $_SESSION['usuario_id'];

    // Inicializa un mensaje vacío
    $mensaje = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar los valores del formulario
        $Nombre = $_POST['Nombre'];
        $Latitud = $_POST['Latitud'];
        $Longitud = $_POST['Longitud'];
        $Ubicacion = $_POST['Ubicacion'];
        $Diasdisponibles = $_POST['Diasdisponibles'];
        $region = $_POST['Region'];
         

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
        // Consulta SQL para insertar los datos en la tabla
        $sql = "INSERT INTO corralones ( nombre, lat, longi, direccion,diaslaboral, region)
            VALUES (?,?,?,?,?,?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssss", $Nombre, $Latitud, $Longitud, $Ubicacion, $Diasdisponibles, $region);

        if ($stmt->execute()) {

            $mensaje = array('texto' => 'Datos insertados con exito.', 'clase' => 'alert-success');
        } else {
            $mensaje = array('texto' => 'Error al insertar los datos.', 'clase' => 'alert-danger');
        }

        $stmt->close();
        $conexion->close();
    }
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
              <a href="form_call.php"  class="nav-link">Formulario Grua</a>
                <a href="list_c.php"  class="nav-link">Lista de corralones</a>
                <a href="registro_corr.php"  class="nav-link">Registro de corralones</a>
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
                            <label for="Nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="Nombre" name="Nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="Latitud" class="form-label">Latitud</label>
                            <input type="text" class="form-control" id="Latitud" name="Latitud" required>
                        </div>
                        <div class="mb-3">
                            <label for="Longitud" class="form-label">Longitud</label>
                            <input type="text" class="form-control" id="Longitud" name="Longitud" required>
                            

                        </div>
                        <div class="mb-3">
                            <label for="Ubicacion" class="form-label">Ubicacion</label>
                            <input type="Ubicacion" class="form-control" id="Ubicacion" name="Ubicacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="Dias disponibles" class="form-label">Dias disponibles</label>
                            <input type="text" class="form-control" id="Diasdisponibles" name="Diasdisponibles" required>
                        </div>
                        <div class="mb-3">
                            <label for="Region" class="form-label">Region</label>
                             <input type="text" class="form-control" id="Region" name="Region" required min="1" max="5">
                        </div>
                        <button type="submit" class="btn btn-vino btn-lg text-white">Registrar corralon</button>
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