<?php
session_start();

// Verificar la sesión del usuario
if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    // Si el usuario no ha iniciado sesión, redirige de vuelta al login
    header('Location: login.php');
    exit();
}

// Verificar si se ha proporcionado un ID válido en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de corralón no proporcionado.";
    exit();
}

// Conexión a la base de datos (ajusta estos valores según tu configuración)
$host = "localhost";
        $usu = "root";
        $contrasena = "";
        $base_de_datos = "gruask";

// Crear una conexión a la base de datos
$conexion = new mysqli($host, $usu, $contrasena, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener el ID del corralón desde la URL
$corralon_id = $_GET['id'];

// Realizar una consulta para obtener la información del corralón a actualizar
$sql = "SELECT * FROM corralones WHERE IdCorralon = $corralon_id";
$result = $conexion->query($sql);

// Verificar si se encontró el corralón
if ($result->num_rows > 0) {
    $corralon = $result->fetch_assoc();
} else {
    echo "Corralón no encontrado.";
    exit();
}

// Verificar si se ha enviado el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nuevoNombre = $_POST["nuevoNombre"];
    $nuevaLatitud = $_POST["nuevaLatitud"];
    $nuevaLongitud = $_POST["nuevaLongitud"];
    $nuevaDireccion = $_POST["nuevaDireccion"];
    $nuevosDiasLaborales = $_POST["nuevosDiasLaborales"];
    $nuevaRegion = $_POST["nuevaRegion"];

    // Realizar la actualización en la base de datos
    $sqlUpdate = "UPDATE corralones SET
                    nombre = '$nuevoNombre',
                    lat = $nuevaLatitud,
                    longi = $nuevaLongitud,
                    direccion = '$nuevaDireccion',
                    diaslaboral = '$nuevosDiasLaborales',
                    region = $nuevaRegion
                  WHERE IdCorralon= $corralon_id";

    if ($conexion->query($sqlUpdate) === TRUE) {
        echo "Corralón actualizado correctamente.";
        // Puedes redirigir a la página de lista de corralones u otra página después de la actualización.
        header("Location: list_c.php");
    } else {
        echo "Error al actualizar el corralón: " . $conexion->error;
    }
}

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Corralón</title>
    <!-- Agrega el enlace al archivo de estilos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
        body {
            background: url('img/GBP.jpeg') no-repeat center center fixed;
            background-size: cover;
            background-repeat: no-repeat;
        }

        form {
            background-color: rgba(128, 0, 0, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin-top: 50px;
        }

        button {
            width: 100%;
            height: 5%;
        }

        #logo {
            position: absolute;
            top: 5px;
            left: 5px;
            height: 200px;
            width: 200px;
        }
        #distanceLabel {
              background-color: transparent; 
              padding: 10px;
              position: absolute;
              top: 50px; 
              left: 10px;
              border: 1px solid #ccc;
              z-index: 1;
            }

        #map {
          width: 100%;
          height: 400px;
          margin-top: 50px;
        }
        h1 {
          text-align: center; 
          color: #fff;
          padding: 10px;
          margin: 0;
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
                
          </li>
          <li class="nav-item">
              
                <a href="list_c.php"  class="nav-link">Lista de corralones</a>
                
          </li>
          <li class="nav-item">
              
                <a href="registro_corr.php"  class="nav-link">Registro de corralones</a>
          </li>
            </ul>
        </div>
      </nav>
    <div class="container mt-4 text-white">
        <h1>Actualizar Corralón</h1>
        <form method="post" action="update_corralon.php?id=<?php echo $corralon_id; ?>">
            <div class="mb-3">
                <label for="nuevoNombre" class="form-label">Nuevo Nombre</label>
                <input type="text" class="form-control" id="nuevoNombre" name="nuevoNombre"
                    value="<?php echo $corralon['nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nuevaLatitud" class="form-label">Nueva Latitud</label>
                <input type="number" step="any" class="form-control" id="nuevaLatitud" name="nuevaLatitud"
                    value="<?php echo $corralon['lat']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nuevaLongitud" class="form-label">Nueva Longitud</label>
                <input type="number" step="any" class="form-control" id="nuevaLongitud" name="nuevaLongitud"
                    value="<?php echo $corralon['longi']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nuevaDireccion" class="form-label">Nueva Dirección</label>
                <input type="text" class="form-control" id="nuevaDireccion" name="nuevaDireccion"
                    value="<?php echo $corralon['direccion']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nuevosDiasLaborales" class="form-label">Nuevos Días Laborales</label>
                <input type="text" class="form-control" id="nuevosDiasLaborales" name="nuevosDiasLaborales"
                    value="<?php echo $corralon['diaslaboral']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nuevaRegion" class="form-label">Nueva Región</label>
                <input type="number" class="form-control" id="nuevaRegion" name="nuevaRegion"
                    value="<?php echo $corralon['region']; ?>" required min="1" max="5">
            </div>
            <button type="submit" class="btn btn-vino btn-lg text-white">Actualizar Corralón</button>
        </form>
    </div>

    <!-- Agrega los enlaces a los archivos de scripts de Bootstrap al final del cuerpo del documento -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-hYWB+C3I/9cy65sEGQ3aB6AuoN+IaXsbmGR3vqRc9Kt7abF2txPnRBAhTzPFDU71"
        crossorigin="anonymous"></script>
</body>

</html>