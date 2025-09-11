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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar los valores del formulario
        $lugarRecoleccion = $_POST['lugarRecoleccion'];
        $localidad = $_POST['localidad'];
        $municipio = $_POST['municipio'];
        $folio = $_POST['folio'];
        $codigoPostal = $_POST['codigoPostal'];
        $region = $_POST['region'];
        $almacen = $_POST['almacen'];
        $contactos = $_POST['contactos'];
        $telefono = $_POST['telefono'];
        $ubicacion = $_POST['ubicacion'];
        $email_cliente = $_POST['email_cliente'];
        $Precio_de_grua = $_POST['Precio_de_grua'];
       
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
        // Consulta SQL para obtener el IDUsuario si el campo email es igual a $email_cliente
        $sql1 = "SELECT IDUsuario FROM usuarios WHERE email =  '$email_cliente'";
        $resultado = $conexion->query($sql1);
        if ($resultado->num_rows > 0) {
            // Encontró un usuario con el correo electrónico proporcionado
            $fila = $resultado->fetch_assoc();
            $IDUsuario = $fila["IDUsuario"];
        } else {
            // No se encontró ningún usuario con el correo electrónico proporcionado
            $mensaje = array('texto' => 'Email no registrado.', 'clase' => 'alert-danger');
        }

        // Consulta SQL para insertar los datos en la tabla
        $sql = "INSERT INTO recoleccion (Lugar_de_Recoleccion, Colonia, Municipio, Folio, Codigo_Postal, Region, Corralon, Contactos, Telefono, Ubicacion, IDUsuario_fk,Precio_de_grua)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssssssss", $lugarRecoleccion, $localidad, $municipio, $folio, $codigoPostal, $region, $almacen, $contactos, $telefono, $ubicacion, $IDUsuario,$Precio_de_grua);

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
<?php

// Conexión a la base de datos (reemplaza con tus propios valores)
      $host = "localhost";
        $usu = "root";
        $contrasena = "";
        $base_de_datos = "gruask";

$conexion = new mysqli($host, $usu, $contrasena, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener el número actual de incidentes
$query = "SELECT COUNT(*) AS idRecoleccion FROM recoleccion";
$resultado = $conexion->query($query);
if ($resultado) {
  $fila = $resultado->fetch_assoc();
  $numero_incidentes = $fila['idRecoleccion'];

  // Incrementar el número de incidentes para obtener el nuevo folio
  $nuevo_numero_incidentes = $numero_incidentes + 1;

  // Obtener el año actual
  $anio = date("y");

  // Formatear el número de incidentes con 4 dígitos
  $numero_incidentes_formateado = str_pad($nuevo_numero_incidentes, 4, '0', STR_PAD_LEFT);

  // Generar el folio
  $folio = $anio . "-" . $numero_incidentes_formateado . "-01";

  // Cerrar la conexión
  $conexion->close();

  // Enviar el folio al formulario usando JavaScript
  echo "<script>
            document.getElementById('folio').value = '$folio';
          </script>";
} else {
  die("Error en la consulta: " . $conexion->error);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Formulario </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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
            top: 100px;
            left: 5px;
            height: 200px;
            width: 200px;
        }
        #map {
      height: 400px;
      width: 100%;
    }
    #distanceLabel {
      background-color: white;
      padding: 10px;
      position: absolute;
      top: 10px;
      left: 10px;
      border: 1px solid #ccc;
      z-index: 1;
    }
    @media (max-width: 768px) {
      /* Estilos para pantallas pequeñas */
    }
    @media (min-width: 769px) and (max-width: 1024px) {
      /* Estilos para pantallas medianas */
    }
    @media (min-width: 1025px) {
      /* Estilos para pantallas grandes */
    }

    </style>
</head>
<script>
  // Enviar el folio al formulario usando JavaScript
  document.addEventListener("DOMContentLoaded", function() {
    var folioInput = document.getElementById('folio');
    if (folioInput) {
      folioInput.value = '<?php echo $folio; ?>';
    }
  });
</script>
<body>

  <img id="logo" src="img/logo2.png" alt="Logo Gobierno de Puebla">
<nav class="navbar navbar-dark bg-danger">
  <div class="container">
      <a class="navbar-brand" >Servicios de Grua</a>
      <ul class="navbar-nav">
        <a href="form_call.php"  class="nav-link">Formulario Grua</a>
        <a href="list_c.php"  class="nav-link">Lista de corralones</a>
        <a href="registro_corr.php"  class="nav-link">Registro de corralones</a>
      </ul>
  </div>
</nav>
    <div class="container bg-danger bg-opacity-75 p-3">
        <h2 class="text-white">Formulario de Autollenado</h2>
        
        
       
        <form class="text-white" method="post" action="">
            <h1>Geolocalizacion</h1>
            <h1> </h1>
            <br>
            <br>
            <div id="map"></div>
            <div class="text-black" id="distanceLabel">Distancia: <span id="distanceValue">--</span></div>
            <br>
            <div class="row">
            <div>
              <embed src="img/Mapa Regiones.pdf" type="application/pdf" width="100%" height="600px" />
              </div>
                <div class="col-md-6">
                    <h3 class="text-white">Puntos de Recolección</h3>
                    <div class="mb-3">
                        <label for="lugarRecoleccion" class="form-label text-white">Lugar de Recolección</label>
                        <input type="text" class="form-control" id="lugarRecoleccion" name="lugarRecoleccion" required>
                    </div>
                    <div class="mb-3">
                        <label for="localidad1" class="form-label text-white">Localidad </label>
                        <input type="text" class="form-control" id="localidad1" name="localidad1" required>
                    </div>
                    <div class="mb-3">
                        <label for="municipio" class="form-label text-white">Municipio</label>
                        <input type="text" class="form-control" id="municipio" name="municipio" required>
                    </div>
                    <div class="mb-3">
                        <label for="colonia1" class="form-label text-white">Colonia</label>
                        <input type="text" class="form-control" id="colonia1" name="colonia1" required>
                    </div>
                    <div class="mb-3">
                      <label for="folio" class="form-label text-white">Folio</label>
                      <input type="text" class="form-control" id="folio" name="folio" readonly required>
                    </div>
        
                    <div class="mb-3">
                        <label for="codigoPostal1" class="form-label text-white">Código Postal</label>
                        <input type="text" class="form-control" id="codigoPostal1" name="codigoPostal1" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactos1" class="form-label text-white">Contactos</label>
                        <input type="text" class="form-control" id="contactos1" name="contactos1" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <h3 class="text-white">Asignación de Almacén</h3>
                    <div class="mb-3">
                        <label for="region" class="form-label text-white">Región</label>
                        <input type="text" class="form-control" id="region" name="region" required>
                    </div>
                    <div class="mb-3">
                        <label for="almacen" class="form-label text-white">Corralon</label>
                        <input type="text" class="form-control" id="almacen" name="almacen" required>
                    </div>
                    <div class="mb-3">
                        <label for="municipio" class="form-label text-white">Municipio</label>
                        <input type="text" class="form-control" id="municipio1" name="municipio" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label text-white">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label for="ubicacion" class="form-label text-white">Ubicación</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="localidad" class="form-label text-white">Localidad </label>
                        <input type="text" class="form-control" id="localidad" name="localidad" required>
                    </div>
                    <div class="mb-3">
                        <label for="colonia" class="form-label text-white">Colonia</label>
                        <input type="text" class="form-control" id="colonia" name="colonia" required>
                    </div>
                    <div class="mb-3">
                        <label for="codigoPostal" class="form-label text-white">Código Postal</label>
                        <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactos" class="form-label text-white">Contactos</label>
                        <input type="text" class="form-control" id="contactos" name="contactos" required>
                    </div>
                    <div>
                    <label for="gruaSelect">Selecciona una grúa:</label>
                    <select class="form-control" id="gruaSelect"  onchange="updateGruaPrice(this.value)">
                      <option>Escoje el tipo de grua </option>
                      <option  value="1000">Grúa Pesada - $1000</option>
                      <option  value="500">Grúa Regular - $500</option>
                      <option  value="100">Grúa Chica - $100</option>
                    </select>
                  </div>
                  <div class="mb-3">
                        <label for="Precio_de_grua" class="form-label text-white">Precio de grua</label>
                        <input type="text" class="form-control" id="Precio_de_grua" name="Precio_de_grua" required>
                    </div>

                  <div id="result">
                  <strong>Costo Total:</strong> $<span id="totalCost"  >--</span>
                  <input type="hidden"  id="totalCostInput" value="">
                    
                  </div>


                </div>
                
            </div>
            
          


            
            <div class="mb-3">
                <label for="email_cliente" class="form-label text-white">Correo electronico del cliente</label>
                <input type="email" class="form-control" id="email_cliente" name="email_cliente" required>
            </div>
           
            <button type="submit" class="btn btn-vino btn-lg text-white text-white">Enviar</button>
        </form>
        


        <?php
        // Conexión a la base de datos (ajusta estos valores según tu configuración)
        $host = "localhost";
        $usu = "root";
        $contrasena = "";
        $base_de_datos = "gruask";

        $conexion = new mysqli($host, $usu, $contrasena, $base_de_datos);
        // Verifica si la conexión fue exitosa
        if ($conexion->connect_error) {
          die("Error en la conexión: " . $conexion->connect_error);
        }

        // Consulta SQL para obtener la información de lugares
        $consulta = "SELECT nombre, lat, longi, direccion,diaslaboral,region FROM corralones";
        $resultado = $conexion->query($consulta);

        // Verifica si la consulta se ejecutó correctamente
        if ($resultado) {
          // Inicializa un array vacío para almacenar la información de lugares
          $locations = array();

          // Recorre los resultados de la consulta y agrega cada lugar al array
          while ($fila = $resultado->fetch_assoc()) {
            $locations[] = array(
              'nombre' => $fila['nombre'],
              'lat' => $fila['lat'],
              'longi' => $fila['longi'],
              'direccion' => $fila['direccion'],
              'region' => $fila['region']
            ); 
          }

          // Libera el conjunto de resultados
          $resultado->free();
        } else {
          echo "Error en la consulta: " . $conexion->error;
        }

        // Cierra la conexión a la base de datos
        $conexion->close();
        ?>
         

        
        <script>
    var map;
    var directionsService;
    var directionsDisplay;
    var geocoder;
    var distanceLabel = document.getElementById('distanceValue');
    var reculeccionInput = document.getElementById('lugarRecoleccion');
    var ubicacionInput = document.getElementById('ubicacion');
    // Función para inicializar el mapa
    function initMap() {
      // Coordenadas de Puebla
      var puebla = { lat: 19.0414, lng: -98.2063 };

      // Crear un mapa centrado en Puebla
      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: puebla
      });

      directionsService = new google.maps.DirectionsService();
      directionsDisplay = new google.maps.DirectionsRenderer();
      directionsDisplay.setMap(map);
      geocoder = new google.maps.Geocoder();

      // Array de ubicaciones con sus coordenadas
      // Ahora, puedes utilizar el array PHP generado para inicializar el array JavaScript
      var locations = <?php echo json_encode($locations); ?>;
      console.log(locations);
      // Agregar marcadores para cada ubicación
      var markers = locations.map(function(location) {
        var marker = new google.maps.Marker({
          
          position: { lat: parseFloat(location.lat), lng:parseFloat(location.longi) },
          map: map,
          title: location.nombre
        }); 
        return marker;
      });

      // Agregar un evento de clic al mapa
      google.maps.event.addListener(map, 'click', function(event) {
        var closestMarker = findClosestMarker(event.latLng, markers);
        if (closestMarker) {
          calculateAndDisplayRoute(event.latLng, closestMarker.getPosition());
          reverseGeocode(event.latLng);
        }
      });

      // Función para encontrar el marcador más cercano
      function findClosestMarker(clickedLatLng, markerArray) {
        var closestMarker = null;
    var closestDistance = Number.MAX_VALUE;

    markerArray.forEach(function (marker) {
        var markerLatLng = marker.getPosition();
        var distance = google.maps.geometry.spherical.computeDistanceBetween(clickedLatLng, markerLatLng);

        if (distance < closestDistance) {
            closestDistance = distance;
            closestMarker = marker;
          }
        });
        if (closestMarker) {
        // Obtener la información de la ubicación seleccionada
        var lugar = closestMarker.getTitle();
        var latitud = closestMarker.getPosition().lat();
        var longitud = closestMarker.getPosition().lng();
        var direccion = closestMarker.get('direccion');
        var region = closestMarker.get('region');
        var municipio = closestMarker.get('municipio');
        var localidad = closestMarker.get('localidad');
        var codigoPostal = closestMarker.get('codigoPostal');

        // Asignar valores a los campos en el formulario
       
        
        document.getElementById('almacen').value = lugar; // Puedes ajustar esto según tus necesidades
        
        reverseGeocode1(closestMarker.getPosition());
        // ... Otros campos que desees asignar ...
    }

    return closestMarker;
}

        return closestMarker;
      }
// Función para realizar la geocodificación inversa
function reverseGeocode1(latLng) {
    geocoder.geocode({ 'location': latLng }, function (results, status) {
        if (status === 'OK') {
            if (results[0]) {
                // Obtener la dirección completa
                var address = results[0].formatted_address;
                var components = results[0].address_components;
                var localidad = getComponentValue(results[0], 'locality');
                // Buscar la información deseada en los componentes
                var localidad = "";
                var municipio = "";
                var codigoPostal = "";

                for (var i = 0; i < components.length; i++) {
                  var types = components[i].types;
                  if (types.includes('locality')) {
                    municipio = components[i].long_name;
                  } else if (types.includes('sublocality')) {
                    colonia = components[i].long_name;
                  } else if (types.includes('postal_code')) {
                    codigoPostal = components[i].long_name;
                  }
                }
                // Actualizar los campos del formulario
                document.getElementById('ubicacion').value = address;
                document.getElementById('municipio1').value = municipio;
                document.getElementById('colonia').value = colonia;
                document.getElementById('localidad').value = localidad;
                document.getElementById('codigoPostal').value = codigoPostal;
            } else {
                // No se encontró la dirección
                document.getElementById('localidad').value = 'No se encontró la ubicación';
            }
        } else {
            // Error en la geocodificación inversa
            document.getElementById('localidad').value = 'Error en la geocodificación inversa';
        }
    });
}
function getComponentValue(result, componentType) {
    for (var i = 0; i < result.address_components.length; i++) {
        var types = result.address_components[i].types;
        if (types.includes(componentType)) {
            return result.address_components[i].long_name;
        }
    }
    return '';
}
      // Función para calcular y mostrar la ruta
      function calculateAndDisplayRoute(origin, destination) {
        var request = {
          origin: origin,
          destination: destination,
          travelMode: 'DRIVING'
        };
        directionsService.route(request, function(response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
            var distance = response.routes[0].legs[0].distance.text;
            distanceLabel.textContent = distance;
        
          }
        });
      }
      var totalCostSpan = document.getElementById('totalCost');

  // Función para calcular el costo total
  function calculateCost(distance) {
    // Obtener el precio de la grúa seleccionada del elemento select
    var selectedGrua = document.getElementById('gruaSelect');
    var selectedGruaPrice = parseFloat(selectedGrua.value);

    // Calcular el costo total multiplicando la distancia por el precio de la grúa
    var totalCost = distance * selectedGruaPrice;

    // Mostrar el costo total en el span
    totalCostSpan.textContent = totalCost.toFixed(2);

    // Actualizar el valor del campo oculto Costo_Total
    document.getElementById('Precio_de_grua').value = totalCost.toFixed(2);
  }

  // Función para actualizar el precio de la grúa seleccionada
  function updateGruaPrice(price) {
    // Llamar a la función calculateCost con la distancia actual
    calculateCost(parseFloat(document.getElementById('distanceValue').textContent));
  }
      
   

      // Función para realizar la geocodificación inversa
      function reverseGeocode(latLng) {
  geocoder.geocode({ 'location': latLng }, function(results, status) {
    if (status === 'OK') {
      if (results[0]) {
        // Obtener la dirección completa
        var address = results[0].formatted_address;

        // Obtener componentes de la dirección
        var components = results[0].address_components;

        // Buscar la información deseada en los componentes
        var localidad = "";
        var municipio = "";
        var codigoPostal = "";

        for (var i = 0; i < components.length; i++) {
          var types = components[i].types;
          if (types.includes('locality')) {
            municipio = components[i].long_name;
          } else if (types.includes('sublocality')) {
            colonia = components[i].long_name;
          } else if (types.includes('postal_code')) {
            codigoPostal = components[i].long_name;
          }
        }
        

        // Actualizar los campos del formulario
        document.getElementById('municipio').value = municipio;
        document.getElementById('colonia1').value = colonia;
        document.getElementById('codigoPostal1').value = codigoPostal;
        document.getElementById('lugarRecoleccion').value = address;
        

      } else {
        // No se encontró la dirección
        reculeccionInput.value = 'No se encontró la ubicación';
      }
    } else {
      // Error en la geocodificación inversa
      reculeccionInput.value = 'Error en la geocodificación inversa';
    }
  });
  
}



      
    
  </script>
  <!-- Incluir la API de Google Maps con tu clave -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAn8CzW6tHD-lPlO8SjK0ks46r3udQwgQ&callback=initMap" async defer></script>
        <?php
        if ($mensaje) {
            echo '<div class="alert ' . $mensaje['clase'] . '" role="alert">' . $mensaje['texto'] . '</div>';
        }
        ?>
        <form method="post" action="destsess.php">
            <input type="submit" class="btn btn-danger  " name="cerrar_sesion" value="Cerrar Sesión">
        </form>
    </div>


</body>

</html>
