<?php
	error_reporting(E_STRICT | E_ALL);
	session_start();
	if(isset($_SESSION["last-reply"])){
		unset($_SESSION["last-reply"]);
	}
	require 'PHPMailer-master/PHPMailerAutoload.php';

	$mail = new PHPMailer(true);
	$mail->IsSMTP(); // telling the class to use SMTP
	
	
	try {
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
		$mail->Host       = "email-smtp.us-east-1.amazonaws.com";      // sets GMAIL as the SMTP server
		$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mail->Username   = "AKIAJ7CLGQPNJFM3K6FQ";  // GMAIL username
		$mail->Password   = "Au21bctoJdENYNcNCYGrxQBgH8AhHyDmGeUscl1jIc01";            // GMAIL password
		$mail->AddReplyTo('comunicaciones@defensa.cl', 'Defensa');
		$mail->SetFrom('comunicaciones@defensa.cl', 'Defensa');
	  
	 //$mail->AddReplyTo('primarias@helia.cl', 'Defensa');
	  //$mail->SetFrom('primarias@helia.cl', 'Defensa');
	  
		//$mail->AddAddress('complaint@simulator.amazonses.com', 'Daniel');
		$mail->AddAddress('daniel.fuentes.b@gmail.com', 'Daniel');
		$mail->AddAddress('daniel@salmonsoftware.cl', 'Daniel');
		$mail->AddAddress('dfuentes@defensa.cl', 'Daniel');
		//$mail->AddAddress('lcampillay@defensa.cl', 'Campi');
	  
	 //$mail->AddAddress('asdasdasdsada@salmonsoftware.cl', 'Amazon Success');
	 //$mail->AddAddress('d24314324@salmonsoftware.cl', 'Amazon Success');
	  //$mail->AddEmbeddedImage("DAD-Chillan_1.jpg", "1","","base64","image/jpeg");
	//  $mail->AddEmbeddedImage("DAD-Chillan_1.jpg", "2","","base64","image/jpeg");
	 // $mail->MessageID = time()."-".md5("success@simulator.amazonses.com")."@defensa.cl";
	  $mail->Subject = 'PHPMailer Test Subject via mail(), advanced';
	  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
	  //$mail->Body = "<img src=\"cid:1\" width=\"500px\" height=\"650px\" /><br /><img src=\"cid:2\" width=\"500px\" height=\"650px\" /><br />";
	  $mail->Body = "To view the message, please use an HTML compatible email viewer!";
	  $mail->IsHTML(true);
	  $mail->Send();
	  echo $_SESSION["last-reply"];
	  //print_r($mail->smtp());
	  //echo "Message Sent OK<p></p>\n";
	} catch (phpmailerException $e) {
	  //echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  //echo $e->getMessage(); //Boring error messages from anything else!
	}
?>