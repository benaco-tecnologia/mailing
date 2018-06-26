<?php
	error_reporting(E_STRICT | E_ALL);
	session_start();
	require 'PHPMailer-master/PHPMailerAutoload.php';
	$u = 'root';
	$p = 'r1r7C1h6ZG4ZVWF';
	$d = 'defensa';
	$link = mysqli_connect("sscl-db-instance.chapmhehekr9.us-east-1.rds.amazonaws.com", $u, $p);
	mysqli_select_db($link, $d);
	$mail = new PHPMailer(true);
	$mail->IsSMTP();
	$email_enviados = 0;
	try {
		$mail->SMTPDebug  = 0;           
		$mail->SMTPAuth   = true;          
		$mail->SMTPSecure = "tls";       
		$mail->Host       = "email-smtp.us-east-1.amazonaws.com";  
		$mail->Port       = 587;            
		$mail->Username   = "AKIAJ7CLGQPNJFM3K6FQ";
		$mail->Password   = "Au21bctoJdENYNcNCYGrxQBgH8AhHyDmGeUscl1jIc01";
		$fechaProgramacion = date("Y-m-d H:i:s");
		$query = "SELECT ml_envio.id, ml_envio.asunto, ml_envio.archivos, ml_envio.contenido, ml_remitentes.nombre, ml_remitentes.correo, ml_envio.altBody ".
				 "FROM ml_envio, ml_remitentes ".
				 "where ml_envio.fechaProgramacion<='$fechaProgramacion' and ml_envio.id in (SELECT envio_id FROM defensa.ml_detalle_envio where mailMessageId is null) ".
				 "	and ml_remitentes.id = ml_envio.remitente_id;";
		$resultado = mysqli_query($link, $query);
		if (mysqli_num_rows($resultado) >= 1) {
			while ($row = mysqli_fetch_array($resultado)) {
				$file = 1;
				$img = array();
				if($row["archivos"]!=null&&$row["archivos"]!=""){
					$archivos = explode(";",$row["archivos"]);
					foreach ($archivos as $archivo) {
						$mail->AddEmbeddedImage('adjuntos/'.$archivo, $file,"","base64","image/jpeg");
						$img[] = "<img src=\"cid:$file\" width=\"800px\" height=\"798px\" /><br />";
						$file++;
					}
				}
				$mail->AddReplyTo("comunicaciones@defensa.cl",$row["nombre"]);
				$mail->SetFrom($row["correo"],$row["nombre"]);
				$mail->Subject = $row["asunto"];
				$mail->AltBody = $row["altBody"];
				$body = $row["contenido"];
				$body = str_replace("[IMAGENES]", implode("",$img), $body);
				{
					$query = "SELECT ml_detalle_envio.id, ml_destinatarios.nombres, ml_destinatarios.email ".
							 "FROM ml_detalle_envio, ml_destinatarios ".
							 "where ml_detalle_envio.mailMessageId is null and ml_destinatarios.email is not null and ml_destinatarios.email <> '' ".
								"and ml_detalle_envio.envio_id = {$row["id"]} ".
								"and ml_detalle_envio.destinatario_id = ml_destinatarios.id order by RAND() limit 50;";
					$resultado2 = mysqli_query($link, $query);
					if (mysqli_num_rows($resultado2) >= 1) {
						while ($row2 = mysqli_fetch_array($resultado2)) {
							if(isset($_SESSION["last-reply"])){
								unset($_SESSION["last-reply"]);
							}
							$destinatarios = explode(";",$row2["email"]);
							foreach($destinatarios as $value){
								$value = trim($value);
								if($row2["nombres"]==null||$row2["nombres"]==''){
									$mail->AddAddress($value,$value);
								}else{
									$mail->AddAddress($value,$row2["nombres"]);
								}		
							}
							$body2 = str_replace("[CORREO]", $row2["email"], $body);
							$body2 .= "<iframe src=\"http://ec2-54-84-194-2.compute-1.amazonaws.com/mailist/r-log.php?di={$row2["id"]}\" height=\"1\" width=\"1\" style=\"display: none;\"></iframe>";
							//$mail->Body = utf8_encode($body2);
							$mail->Body = $body2;
							$mail->IsHTML(true);
							$mail->send();
							$_SESSION["last-reply"]=trim(str_replace("\n","",$_SESSION["last-reply"]));
							$query = "UPDATE ml_detalle_envio SET mailMessageId = '{$_SESSION["last-reply"]}' WHERE id = {$row2["id"]};";
							mysqli_query($link, $query);
							$mail->clearAddresses();
							if($email_enviados++%10==0){
								sleep(1);
							}
						}
					}
				}
				$mail->clearAttachments();
				sleep(1);
			}
		}
	} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
	}
	session_destroy();
	mysqli_close($link);	
?>