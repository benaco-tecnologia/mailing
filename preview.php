<?php
	session_start();
	// Codificar pagina en ANSI
	// Codificar contenidos en UTF8 con utf8_encode
	// En aplicación usa como UTF8 con guard let
	
	$u = 'root';
	$p = 'r1r7C1h6ZG4ZVWF';
	$d = 'defensa';
	$link = mysqli_connect("sscl-db-instance.chapmhehekr9.us-east-1.rds.amazonaws.com", $u, $p) or die(mysqli_error());
	mysqli_select_db($link, $d) or die(mysqli_error());
	
	/*
	Array ( [form-remitentes] => 1 [form-asunto] => Prueba nuevo formato [form-listado] => 3 [html-mailing] =>
	asdasdasdasdas

	[IMAGENES]

	[btn-aceptar] => [archivos] => Array ( [0] => 14651829011.jpg [1] => 14651829012.jpg ) )
	
	*/
	//print_r($_SESSION["archivos"]);
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

    <title>Preview / Mailist MDN</title>

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
			<div class="panel-heading">Escribir</div>
			<div class="panel-body"> 
				<form method="post" enctype="multipart/form-data">
					<fieldset>
						<div class="form-group">
							<label for="form-remitentes">Remitente</label>
							<select id="form-remitentes" name="form-remitentes" class="form-control">
								<?php 
									$q = "SELECT id, nombre, correo FROM ml_remitentes where id={$_SESSION["form-remitentes"]};";
									$resultado = mysqli_query($link, $q) or die(mysqli_error());
									if (mysqli_num_rows($resultado) >= 1) {
										while ($row = mysqli_fetch_array($resultado)) {
								?>
								<option value="<?php echo $row["id"];?>" selected="selected"><?php echo utf8_encode($row["nombre"]);?> 	&lt;<?php echo utf8_encode($row["correo"]);?>&gt;</option>
								<?php 
										}
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="form-asunto">Asunto</label>
							<input type="text" class="form-control" name="form-asunto" id="form-asunto" placeholder="Ingrese un asunto" value="<?php echo $_SESSION["form-asunto"]; ?>" />
						</div>
						<div class="form-group">
							<label for="form-asunto">Texto Alternativo</label>
							<input type="text" class="form-control" name="form-alternativo" id="form-alternativo" placeholder="Ingrese un texto alternativo" value="<?php echo $_SESSION["form-alternativo"]; ?>" />
						</div>
						<div class="form-group">
							<label for="form-listado">Listas</label>
							<select id="form-listado" name="form-listado" class="form-control">
								<?php 
									$q = "SELECT id, nombre FROM ml_listas where id={$_SESSION["form-listado"]};";
									$resultado = mysqli_query($link, $q) or die(mysqli_error());
									if (mysqli_num_rows($resultado) >= 1) {
										while ($row = mysqli_fetch_array($resultado)) {
											$q = "SELECT count(*) as total FROM ml_listas_detalle where ml_listas_detalle.lista_id = {$row["id"]};";
											$resultado2 = mysqli_query($link, $q) or die(mysqli_error());
											$contactos = 0;
											if (mysqli_num_rows($resultado2) >= 1) {
												$row2 = mysqli_fetch_array($resultado2);
												$contactos = $row2["total"];
											}
								?>
								<option value="<?php echo $row["id"];?>" selected="selected"><?php echo utf8_encode($row["nombre"]);?> (<?php echo $contactos;?> destinatarios)</option>
								<?php 
										}
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="form-fecha">Fecha de Envío</label>
							<div class="row">
							  <div class="col-md-4">
								<input type="date" class="form-control" name="form-fecha" id="form-fecha" value="<?php echo $_SESSION["form-fecha"]; ?>" placeholder="" />
							  </div>
							  <div class="col-md-2">
								<input type="text" class="form-control" name="form-hour" id="form-hour" value="<?php echo $_SESSION["form-hour"]; ?>" placeholder="" />
								</div>
								<div class="col-md-2">
									<input type="text" class="form-control" name="form-min" id="form-min" value="<?php echo $_SESSION["form-min"]; ?>" placeholder="" />
							  </div>
							  <div class="col-md-4">
								<input type="text" class="form-control" name="form-ampm" id="form-ampm" value="<?php echo $_SESSION["form-ampm"]; ?>" placeholder="" />
							  </div>
							</div>
						</div>
						<div class="form-group">
							<label for="form-listado">Correo</label>
							<?php
								$html = $_SESSION["html-mailing"];
								$images = array();
								foreach ($_SESSION["archivos"] as $archivo) {
									$images[] = "<img src=\"adjuntos/$archivo\" width=\"800px\" height=\"798px\" /><br />";
								}
								$html = str_replace("[IMAGENES]", implode("",$images), $html);
								echo $html;
							?>
						</div>
						<div class="form-group">
							<label for="form-asunto">Descripción Interna del Envío</label>
							<input type="text" class="form-control" name="form-descripcion" id="form-descripcion" placeholder="Ingrese una description interna del envío" value="<?php echo $_SESSION["form-descripcion"]; ?>" />
						</div>
						<div class="btn-group btn-group-justified" role="group">
							<div class="btn-group" role="group">
								<button class="btn btn-lg btn-danger btn-block" type="button" id="btn-volver" name="btn-modificar">Modificar</button>
							</div>
							<div class="btn-group" role="group">
								<button class="btn btn-lg btn-success btn-block" type="submit" id="btn-aceptar" name="btn-aceptar">Encolar</button>
							</div>
						</div>
					</fieldset>
				</form>
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
			$("#btn-volver").click(function(){
				history.back();
				return true;
			});
			
			$("#btn-aceptar").click(function(){
				if(confirm("Al encolar el correo será enviado a todos los destinatarios. ¿Está seguro que desea hacerlo?")){
					window.location='./queue.php';
					return false;
				}
				return false;
			});
		});
	</script>
  </body>
</html>
<?php
	mysqli_close($link) or die(mysqli_error());	
?>