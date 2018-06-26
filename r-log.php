<?php
	// Codificar pagina en ANSI
	// Codificar contenidos en UTF8 con utf8_encode
	// En aplicación usa como UTF8 con guard let
	if(isset($_GET["di"])){
		$di=$_GET["di"];
		$u = 'root';
		$p = 'r1r7C1h6ZG4ZVWF';
		$d = 'defensa';
		$link = mysqli_connect("sscl-db-instance.chapmhehekr9.us-east-1.rds.amazonaws.com", $u, $p) or die(mysqli_error());
		mysqli_select_db($link, $d) or die(mysqli_error());
		$fecha = date("Y-m-d H:i:s");
		mysqli_query($link, "UPDATE ml_detalle_envio SET readTimestamp='$fecha' where ml_detalle_envio.id=$di;") or die(mysqli_error());
		mysqli_close($link) or die(mysqli_error());
	}	
?>