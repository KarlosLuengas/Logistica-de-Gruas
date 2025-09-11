<?php
session_start();

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    // Si el usuario no ha iniciado sesión, redirige de vuelta al login
    header('Location: login.php');
    exit();
}
else {
    $usuario_id = $_SESSION['usuario_id'];

    // Inicializa un mensaje vacío
    $mensaje = '';
    if (isset($_POST['eliminar_corralon'])) {
        if (isset($_POST['IdCorralon']) && !empty($_POST['IdCorralon'])) {
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

            // Obtener el ID del corralón a eliminar desde el formulario
            $IdCorralon = $_POST['IdCorralon'];

            // Realizar la consulta SQL para eliminar el corralón
            $sql = "DELETE FROM corralones WHERE IdCorralon = $IdCorralon";

            if ($conexion->query($sql) === TRUE) {
                $mensaje = array('texto' => 'Registro eliminado correctamente.', 'clase' => 'alert-success');
            } else {
                $mensaje = array('texto' => 'Error al eliminar el registro.', 'clase' => 'alert-danger');
            }

            // Cerrar la conexión
            $conexion->close();
        } else {
            $mensaje = array('texto' => 'Registro de corralón no encontrado..', 'clase' => 'alert-danger');
        }
    }

    if (isset($_POST['actualizar_corralon'])) {
        if (isset($_POST['corralon_id']) && !empty($_POST['corralon_id'])) {
            // Obtener el ID del corralón a actualizar desde el formulario
            $corralon_id = $_POST['corralon_id']; 
            // Aquí, redirigimos a otra página llamada update_corralon.php y le pasamos el ID.
            header("Location: update_corralon.php?id=$corralon_id");
            exit();
        } else {
            echo "ID de corralón no proporcionado.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Gobierno de Puebla</title>
    
    <!-- Agregar los enlaces a los archivos CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!-- Estilos personalizados para los botones -->
    <style>
        body {
            background: url('img/GBP.jpeg') no-repeat center center fixed;
            background-size: cover;
        }
        #logo {
            height: 200px;
            width: 200px;
            position: absolute;
        }
        .btn-vino {  
            width: 30%; 
            height: 50%; 
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
    
    <?php
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

    // Realizar la consulta SQL
    $sql = "SELECT IdCorralon,nombre,lat,longi,direccion,diaslaboral,region FROM corralones ";
    $result = $conexion->query($sql);
    ?>
      <img id="logo" src="img/logo2.png" alt="Logo Gobierno de Puebla">
      
      
      <!-- Logo del Gobierno de Puebla en la esquina superior izquierda -->
      
      
      <div class="container mt-4 text-white">
      
              <h1 class="text-center">Historial de Servicio</h1>
              <table class="table table-danger" >
                  <thead>
                      <tr>
                          <th>Nombre</th>
                          <th>Latitud</th>
                          <th>Longitud</th>
                          <th>Ubicación</th>
                          <th>Dias disponible</th>
                          <th>Región</th>
                          <th>Acciones</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["nombre"] . "</td>";
                            echo "<td>" . $row["lat"] . "</td>";
                            echo "<td>" . $row["longi"] . "</td>";
                            echo "<td>" . $row["direccion"] . "</td>";
                            echo "<td>" . $row["diaslaboral"] . "</td>";
                            echo "<td>" . $row["region"] . "</td>";
                            echo "<td>
                            <form method='post' action='list_c.php'>
                                <input type='hidden' name='IdCorralon' value='" . $row["IdCorralon"] . "'>
                                <button type='submit' class='btn btn-sm btn-danger' name='eliminar_corralon'>Eliminar</button>
                            </form>
                            <form method='post' action='list_c.php'>
                                <input type='hidden' name='corralon_id' value='" . $row["IdCorralon"] . "'>
                                <button type='submit' class='btn btn-sm btn-primary' name='actualizar_corralon'>Actualizar</button>
                            </form>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "No se encontraron resultados.";
                    }
                    $conexion->close();
                    ?>
                     
                  </tbody>
              </table>
            <?php
            // Verificar si $mensaje está definida y no está vacía antes de mostrarla
            if (!empty($mensaje)) {
                echo '<div class="alert ' . $mensaje['clase'] . '" role="alert">' . $mensaje['texto'] . '</div>';
            }
            ?>
              <form method="post" action="destsess.php">
                  <input type="submit" class="btn btn-danger  " name="cerrar_sesion" value="Cerrar Sesión">
              </form>
          </div>
          </div>
</body>
</html>