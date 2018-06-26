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
	
	if(isset($_POST["btn-aceptar"])){
		$asunto = utf8_decode($_POST["form-asunto"]);
		$remitente_id = $_POST["form-remitentes"];
		$lista_id = $_POST["form-listado"];
		$fecha = date("Y-m-d H:i:s");
		$archivo = time();
		$archivos=array();
		$_SESSION=$_POST;
		$_SESSION["archivos"]=array();
		$i=1;
		foreach ($_FILES["form-file"]["tmp_name"] as $file) {
			if(@$file==""){
				break;
			}
			move_uploaded_file($file,"adjuntos/".$archivo.$i.".jpg");
			$archivos[]=$archivo.$i.".jpg";
			$_SESSION["archivos"][]=$archivo.$i.".jpg";
			$i++;
		}
		$archivos = implode(";",$archivos);
		header("Location: preview.php");
		exit;
	}
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

    <title>Write / Mailist MDN</title>

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
								<option selected="selected" disabled>Seleccione un remitente</option>
								<?php 
									$q = "SELECT id, nombre, correo FROM ml_remitentes;";
									$resultado = mysqli_query($link, $q) or die(mysqli_error());
									if (mysqli_num_rows($resultado) >= 1) {
										while ($row = mysqli_fetch_array($resultado)) {
								?>
								<option value="<?php echo $row["id"];?>"><?php echo utf8_encode($row["nombre"]);?> 	&lt;<?php echo utf8_encode($row["correo"]);?>&gt;</option>
								<?php 
										}
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="form-asunto">Asunto</label>
							<input type="text" class="form-control" name="form-asunto" id="form-asunto" placeholder="Ingrese un asunto" />
						</div>
						<div class="form-group">
							<label for="form-alternativo">Texto Alternativo</label>
							<input type="text" class="form-control" name="form-alternativo" id="form-alternativo" placeholder="Ingrese un texto alternativo" />
						</div>
						<div class="form-group">
							<label for="form-fecha">Fecha de Envío</label>
							<div class="row">
							  <div class="col-md-4">
								<input type="date" class="form-control" name="form-fecha" id="form-fecha" placeholder="" />
							  </div>
							  <div class="col-md-2">
								<select id="form-hour" name="form-hour" class="form-control">
									<option>01</option>
									<option>02</option>
									<option>03</option>
									<option>04</option>
									<option>05</option>
									<option>06</option>
									<option>07</option>
									<option>08</option>
									<option>09</option>
									<option>10</option>
									<option>11</option>
									<option>12</option>
								</select>
								</div>
								<div class="col-md-2">
								<select id="form-min" name="form-min" class="form-control">
									<?php
										for ($i=0;$i<60;$i++){
											echo "<option>$i</option>\n";
										}
									?>
								</select>
							  </div>
							  <div class="col-md-4">
								<select id="form-ampm" name="form-ampm" class="form-control">
									<option>AM</option>
									<option>PM</option>
								</select>
							  </div>
							</div>
						</div>
						<div class="form-group">
							<label for="form-listado">Listas</label>
							<select id="form-listado" name="form-listado" class="form-control">
								<option selected="selected" disabled>Seleccione una lista de destinatarios</option>
								<option>Ninguna</option>
								<?php 
									$q = "SELECT id, nombre FROM ml_listas;";
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
								<option value="<?php echo $row["id"];?>"><?php echo utf8_encode($row["nombre"]);?> (<?php echo $contactos;?> destinatarios)</option>
								<?php 
										}
									}
								?>
							</select>
						</div>
						<div id="summernote"><?php
							if(isset($_SESSION["html-mailing"])&&@$_SESSION["html-mailing"]!=""){
								echo @$_SESSION["html-mailing"]; 
							}else {
							?>
								<p align="center">Boletin Informativo del Ministerio de Defensa Nacional - Gobierno de Chile</p>
								<p align="center">[IMAGENES]</p>
								<p align="center">Boletin Informativo del Ministerio de Defensa Nacional - Gobierno de Chile<br />Zenteno 45 Piso 4 - Telefono: +56 2 2222 1202 - Comunicaciones y Prensa +56 2 2937 9990</p><p  align="center">Este correo fue enviado a [CORREO], a nombre de la Unidad de Comunicaciones del Ministerio de Defensa Nacional<br />Para no recibir este mensaje favor comunicarse con comunicacionesmdn@defensa.cl o a traves de este www.defensa.cl</p>
								<p align="center">Algunos tildes y acentos han sido omitidos intencionalmente a objeto de asegurar la lectura de este email.</p>
							<?php
							}
							?></div>
						<input type="hidden" name="html-mailing" id="html-mailing" />
						<div class="form-group">
							<label for="form-file">Archivos (Ingrese en el correo el texto [IMAGENES] para desplegar)</label>
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
							<input type="file" name="form-file[]" id="form-file[]" class="form-control" />
						</div>
						<div class="form-group">
							<label for="form-asunto">Descripción Interna del Envío</label>
							<input type="text" class="form-control" name="form-descripcion" id="form-descripcion" placeholder="Ingrese una description interna del envío" />
						</div>
						<button class="btn btn-lg btn-success btn-block" type="submit" id="btn-aceptar" name="btn-aceptar">Vista Previa</button>
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
	<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.js"></script>
	<script>
		$(document).ready(function() {
			$('#summernote').summernote({
				 height: 400,
				 lang: 'es-ES'
			});
			
			$("#btn-aceptar").click(function(){
				var markupStr = $('#summernote').summernote('code');
				$("#html-mailing").val(markupStr);
				return true;
			});
		});
	</script>
  </body>
</html>
<?php
	mysqli_close($link) or die(mysqli_error());	
?>