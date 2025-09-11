<php>
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
            top: 100px;
            left: 5px;
            height: 200px;
            width: 200px;
        }
        .btn-vino {
            

            width: 30%;

            height: 50%;

        }
        img{

            height: 800px;
            width: 1200px;
        }

    </style>
</head>
<body>

<!-- Logo del Gobierno de Puebla en la esquina superior izquierda -->
<img id="logo" src="img/logo2.png" alt="Logo Gobierno de Puebla">

<nav class="navbar navbar-dark bg-danger">
  <div class="container">
      <a class="navbar-brand" >Servicios de Grua</a>
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" href="login.php">Inicio de sesion</a>
          </li>
         
      </ul>
  </div>
</nav>

<div class="container mt-5">
  <div class="row">
      <div class="col-12 text-center text-white">
          <h1>Bienvenido a Nuestros Servicios de Grua</h1>
          <p>Estamos aquí para ayudarte en situaciones de emergencia en la carretera.</p>
      </div>
      <div class="col-12 text-center text-white">
        <h1>Numero de contacto: xxx-xxx-xxxx</h1>
      </div>

      <div class="col-12 text-center text-white">
        <h1>Biografia del sitio web</h1>
        <p class="p-3 bg-danger bg-opacity-10 border border-danger border-start-0 rounded-end" >Esta informacion mostrara el funcionamiento del sitio web.
            
          
        </p>
        
      </div>
      <video width="740" height="460" controls>
        <source src="img/video.mp4" type="video/mp4">
        Tu navegador no soporta el tag de video.
    </video>
      
  </div>
</div>


</body>
</html>

</php>