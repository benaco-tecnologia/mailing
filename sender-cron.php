<?php
	error_reporting(E_STRICT | E_ALL);
	require 'PHPMailer-master/PHPMailerAutoload.php';
	$u = 'root';
	$p = 'r1r7C1h6ZG4ZVWF';
	$d = 'defensa';
	$link = mysqli_connect("sscl-db-instance.chapmhehekr9.us-east-1.rds.amazonaws.com", $u, $p) or die(mysqli_error());
	mysqli_select_db($link, $d) or die(mysqli_error());
	$mail = new PHPMailer;
	//$mail->IsSendmail();
	$mail->IsSMTP();
	$mail->SMTPDebug  = 2;   
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	$mail->Host       = "smtp.cuentapublicadefensa.cl";      // sets GMAIL as the SMTP server
	$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
	$mail->Username   = "mailist@cuentapublicadefensa.cl";  // GMAIL username
	$mail->Password   = "Pri9174NNs";            // GMAIL password
	
	//$mail->IsSMTP();
	//$mail->SMTPDebug = 1;  
	//$mail->SMTPAuth = false;
	//$mail->Host = "localhost";
	//$mail->Port = 25;      

	/*$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
	$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
	$mail->Username   = "daniel@salmonsoftware.cl";  // GMAIL username
	$mail->Password   = "fuentesbusco.it";      
*/
	$q = "SELECT ml_detalle_envio.id, ml_destinatarios.nombres, ml_destinatarios.email, ml_envio.asunto, ml_envio.archivos, ml_detalle_envio.contenido, ml_remitentes.nombre, ml_remitentes.correo
		FROM defensa.ml_detalle_envio, ml_destinatarios, ml_envio, ml_remitentes
		where ml_detalle_envio.fecha_envio is null and estado is null 
		and ml_envio.remitente_id=ml_remitentes.id
		and ml_detalle_envio.envio_id=ml_envio.id
		and ml_detalle_envio.destinatario_id=ml_destinatarios.id
		order by ml_detalle_envio.id desc limit 2;";
	$resultado = mysqli_query($link, $q) or die(mysqli_error());
	if (mysqli_num_rows($resultado) >= 1) {
		while ($row = mysqli_fetch_array($resultado)) {
			$file = 1;
			$img = array();
			if($row["archivos"]!=null&&$row["archivos"]!=""){
				$archivos = explode(";",$row["archivos"]);
				foreach ($archivos as $archivo) {
					$mail->AddEmbeddedImage('adjuntos/'.$archivo, $file,"","base64","image/jpeg");
					$img[] = "<img src=\"cid:$file\" width=\"500px\" height=\"650px\" /><br />";
					$file++;
				}
			}
			//print_r($row);
			$mail->AltBody  = $row["asunto"];
			$mail->Subject = $row["asunto"];
			$mail->setFrom($row["correo"],$row["nombre"]);
			$mail->addReplyTo($row["correo"],$row["nombre"]);
			/*if($row["nombres"]==null||$row["nombres"]==''){
				$mail->AddAddress($row["email"],$row["email"]);
			}else{
				$mail->AddAddress($row["email"],$row["nombres"]);
			}*/
			$mail->AddAddress("dfuentes@defensa.cl","DanielDefensa");
			//$mail->AddAddress("daniel.fuentes.b@gmail.com","Daniel");
			//$mail->AddAddress("daniel@salmonsoftware.cl","DanielSalmon");
			
			
			//AddEmbeddedImage($path, $cid, $name = "", $encoding = "base64", $type = "application/octet-stream")
	
			//$mail->AddEmbeddedImage('DAD-Chillan_1.jpg', "1","","base64","image/jpeg");
			//$mail->AddEmbeddedImage('DAD-Chillan_1.jpg', "2","","base64","image/jpeg");
			
			$body = $row["contenido"];
			$body = str_replace("[ARCHIVO]", implode("",$img), $body);
			//$body = "<img src=\"cid:1\" width=\"500px\" height=\"650px\" /><br />";
			//$body .= "<img src=\"cid:2\" width=\"500px\" height=\"650px\" /><br />";
			//echo $body;
			$mail->Body = utf8_decode($body);
			$mail->IsHTML(true);
			$fecha = date("Y-m-d H:i:s");
			/*if (!$mail->send()) {
				//echo $mail->ErrorInfo;
				//echo "{$row["id"]} NOK<br />"."\n";
				mysqli_query($link, "UPDATE ml_detalle_envio SET estado='{$mail->ErrorInfo}' where id={$row["id"]};") or die(mysqli_error());
			} else {
				//echo "{$row["id"]} OK<br />"."\n";
				//mysqli_query($link, "UPDATE ml_detalle_envio SET fecha_envio='$fecha' where id={$row["id"]};") or die(mysqli_error());
			}*/
			$mail->clearAddresses();
			//$mail->clearAttachments();
			sleep(3);
		}
	}
	mysqli_close($link) or die(mysqli_error());
?>