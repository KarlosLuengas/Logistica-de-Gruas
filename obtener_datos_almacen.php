<?php
// Reemplaza con tus credenciales de conexión a la base de datos
$host = "localhost";
$usu = "root";
$contrasena = "";
$base_de_datos = "gruask";

// Obtener parámetros de la solicitud GET
$lat = $_GET['lat'];
$lng = $_GET['longi'];

$conexion = new mysqli($host, $usu, $contrasena, $base_de_datos);

if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Realizar la consulta a la base de datos para obtener datos del almacén
$sql = "SELECT lat,longi FROM corralones ";
$result = $conexion->query($sql);

if ($resultado->num_rows > 0) {
  $data = $resultado->fetch_assoc();
  echo json_encode($data);
} else {
  echo json_encode(null); // No se encontraron datos del almacén
}

$conexion->close();
?>
