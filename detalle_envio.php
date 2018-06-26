<?php
	// Codificar pagina en ANSI
	// Codificar contenidos en UTF8 con utf8_encode
	// En aplicación usa como UTF8 con guard let
	
	$u = 'root';
	$p = 'r1r7C1h6ZG4ZVWF';
	$d = 'defensa';
	$link = mysqli_connect("sscl-db-instance.chapmhehekr9.us-east-1.rds.amazonaws.com", $u, $p) or die(mysqli_error());
	mysqli_select_db($link, $d) or die(mysqli_error());
	$envio_id = $_GET["id"];
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

    <title>Mailist Defensa</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">


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

    <div class="container" style="width:100% !important;">
		<p></p>
     <div class="panel panel-primary">
	  <div class="panel-heading">Detalle Envío</div>
		<!--
		<div class="panel-body"> 
		</div>
		-->
		<table class="table">
			<thead>   
				<tr> <th>#</th> <th>Nombre</th><th>Cargo</th> <th>Departamento</th> <th>Empresa</th> <th>Correo</th>
				<th>Enviado</th>
				<th>Entregado</th>
				<th>Error</th>
				</tr>
			</thead>
			<tbody> 
				<?php 
					$q="SELECT ml_destinatarios.id as id, nombres, cargo, departamento, empresa, email, ml_envio.id as envio_id
						FROM ml_destinatarios, ml_listas_detalle, ml_envio 
						where ml_envio.lista_id=ml_listas_detalle.lista_id and ml_envio.id = $envio_id AND ml_listas_detalle.destinatario_id=ml_destinatarios.id;";
					$resultado = mysqli_query($link, $q) or die(mysqli_error());
					if (mysqli_num_rows($resultado) >= 1) {
						while ($row = mysqli_fetch_array($resultado)) {
				?>
				<tr> 
					<th scope="row"><?php echo $row["id"];?></th>
					<td><?php echo utf8_encode($row["nombres"]);?></td>
					<td><?php echo utf8_encode($row["cargo"]);?></td> 
					<td><?php echo utf8_encode($row["departamento"]);?></td>
					<td><?php echo utf8_encode($row["empresa"]);?></td> 
					<td><?php echo $row["email"];?></td> 
					<td nowrap>
					<?php
						$query = "SELECT ml_sns_log.mailTimestamp 
						FROM ml_detalle_envio, ml_sns_log where envio_id=$envio_id and destinatario_id={$row["id"]} and ml_detalle_envio.mailMessageId=ml_sns_log.mailMessageId limit 1;";
						$resultado2 = mysqli_query($link, $query) or die(mysqli_error());
						if (mysqli_num_rows($resultado2) >= 1) {
							while ($row2 = mysqli_fetch_array($resultado2)) {
								echo $row2["mailTimestamp"];
							}
						}
					?>
					</td>
					<td nowrap>
					<?php
						$query = "SELECT ml_sns_log.notificationType, ml_sns_log.notificationTimestamp 
						FROM ml_detalle_envio, ml_sns_log where envio_id=$envio_id and destinatario_id={$row["id"]} and ml_detalle_envio.mailMessageId=ml_sns_log.mailMessageId and notificationType='Delivery' limit 1;";
						$resultado2 = mysqli_query($link, $query) or die(mysqli_error());
						if (mysqli_num_rows($resultado2) >= 1) {
							while ($row2 = mysqli_fetch_array($resultado2)) {
								echo $row2["notificationTimestamp"];
							}
						}
					?>
					</td>
					
						<?php
						$query = "SELECT ml_sns_log.notificationType, ml_sns_log.notificationTimestamp , ml_sns_log.message
						FROM ml_detalle_envio, ml_sns_log where envio_id=$envio_id and destinatario_id={$row["id"]} and ml_detalle_envio.mailMessageId=ml_sns_log.mailMessageId and notificationType like 'Bounce%' limit 1;";
						$resultado2 = mysqli_query($link, $query) or die(mysqli_error());
						if (mysqli_num_rows($resultado2) >= 1) {
							while ($row2 = mysqli_fetch_array($resultado2)) {
								echo '<td nowrap title="'.$row2["notificationType"].'">';
								echo $row2["notificationTimestamp"].' / ';
								//echo $row2["message"];
								echo '</td>';
							}
						}else{
							echo '<td nowrap>';
							echo '</td>';
						}
					?>
					
				</tr> 
				<?php 
						}
					}
				?>
			</tbody>
		</table>
	</div>


    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
<?php
	mysqli_close($link) or die(mysqli_error());	
?>