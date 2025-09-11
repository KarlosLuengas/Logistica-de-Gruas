<?php
session_start();

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    // Si el usuario no ha iniciado sesión, redirige de vuelta al login
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['usuario_id'];
?>
<!DOCTYPE html>
<html lang="es">
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
            position: absolute;
           
            height: 200px;
            width: 200px;
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
        
         
      </ul>
  </div>
</nav>
<img id="logo" src="img/logo2.png" alt="Logo Gobierno de Puebla">
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
$sql = "SELECT Municipio,Ubicacion,Corralon,Region,Folio,Codigo_Postal,Telefono, Precio_de_grua FROM recoleccion where IDUsuario_fk=$usuario_id";
$result = $conexion->query($sql);
?>


<div class="container mt-4 text-white">
        <h1 class="text-center">Historial de Servicio</h1>
        <table class="table table-danger" >
            <thead>
                <tr> 
                    <th>Municipio</th>
                  
                    <th>Ubicación de Corralon</th>
                    <th>nombre de Corralon</th>
                    <th>region de Corralon</th>
                    <th>folio</th>
                    <th>CP de Corralon</th>
                    <th>Numero Telefonico del Corralon</th>
                    <th>Precio de grua</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Municipio"] . "</td>";
                      
                        echo "<td>" . $row["Ubicacion"] . "</td>";
                        echo "<td>" . $row["Corralon"] . "</td>";
                        echo "<td>" . $row["Region"] . "</td>";
                        echo "<td>" . $row["Folio"] . "</td>";
                        echo "<td>" . $row["Codigo_Postal"] . "</td>";
                        echo "<td>" . $row["Telefono"] . "</td>";
                        echo "<td>" . $row["Precio_de_grua"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "No se encontraron resultados.";
                }
                $conexion->close();
                ?>
                
            </tbody>
        </table>
        <form method="post" action="destsess.php">
        <input type="submit" class="btn btn-danger  " name="cerrar_sesion" value="Cerrar Sesión">
        </form>
    </div>
</body>
</html>