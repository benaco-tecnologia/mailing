<?php
	session_start();
	$u = 'root';
	$p = 'r1r7C1h6ZG4ZVWF';
	$d = 'defensa';
	$link = mysqli_connect("sscl-db-instance.chapmhehekr9.us-east-1.rds.amazonaws.com", $u, $p);
	mysqli_select_db($link, $d);
	// Registro del envio
	$fecha = date("Y-m-d H:i:s");
	$asunto = utf8_decode($_SESSION["form-asunto"]);
	$remitente_id = $_SESSION["form-remitentes"];
	$lista_id = $_SESSION["form-listado"];
	$altBody = utf8_decode($_SESSION["form-alternativo"]);
	$archivos = implode(";",$_SESSION["archivos"]);
	$contenido = utf8_decode($_SESSION["html-mailing"]);
	$fechaProgramacion = strtotime($_SESSION["form-fecha"].' '.$_SESSION["form-hour"].':'.$_SESSION["form-min"].':00 ' . $_SESSION["form-ampm"]);
	$fechaProgramacion = date("Y-m-d H:i:s",$fechaProgramacion);
	$descripcion = utf8_decode($_SESSION["form-descripcion"]);
	mysqli_query($link, "INSERT INTO ml_envio (fecha,asunto,remitente_id,lista_id,archivos,contenido,altBody,descripcion,fechaProgramacion) value ('$fecha','$asunto','$remitente_id','$lista_id','$archivos','$contenido','$altBody','$descripcion','$fechaProgramacion');");
	$envio_id = mysqli_insert_id($link);	
	// Registro del detalle del envio
	$query = "SELECT ml_destinatarios.id as id FROM ml_destinatarios, ml_listas_detalle where ml_listas_detalle.lista_id = $lista_id AND ml_listas_detalle.destinatario_id=ml_destinatarios.id;";
	$resultado = mysqli_query($link, $query);
	$total_correos = 0;
	if (mysqli_num_rows($resultado) >= 1) {
		while ($row = mysqli_fetch_array($resultado)) {
			$fecha = date("Y-m-d H:i:s");
			mysqli_query($link, "INSERT INTO ml_detalle_envio (envio_id,destinatario_id,queueTimestamp) value ('$envio_id','{$row["id"]}','$fecha');");
			$total_correos++;
		}
	}
	session_destroy();
	mysqli_close($link) or die(mysqli_error());	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Queue / Mailist MDN</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
	<!-- include summernote css/js-->
	<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.css" rel="stylesheet">
	


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Mailist Defensa</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="./index.php">Inicio</a></li>
            <li><a href="./contactos.php">Contactos</a></li>
			<li><a href="./listas.php">Listas</a></li>
			<li><a href="./write.php">Escribir</a></li>
          </ul> 
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
	<p>&nbsp;</p>			
			<div class="panel panel-primary">
			<div class="panel-heading">Queue</div>
			<div class="panel-body"> 
				Se encolaron <?php echo $total_correos;?> correos, tomará alrededor de <?php
					$v = ($total_correos / 40 + 1)*3;
					echo ceil($v);
				?> minutos completar el envío.
			</div>
		</form>
	</div>

    </div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script>
		$(document).ready(function() {
			
		});
	</script>
  </body>
</html>