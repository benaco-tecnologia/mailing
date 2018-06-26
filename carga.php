<?php
$u = 'root';
$p = 'r1r7C1h6ZG4ZVWF';
$d = 'defensa';
header("Content-Type:text/html; charset=utf-8"); 
$link = mysqli_connect("sscl-db-instance.chapmhehekr9.us-east-1.rds.amazonaws.com", $u, $p) or die(mysqli_error());
mysqli_select_db($link, $d) or die(mysqli_error());
$fila = 1;
//ini_set('auto_detect_line_endings',TRUE);
if (($gestor = fopen("carga_anepe_cnad.csv", "r")) !== FALSE) {
	while (($line = fgets($gestor)) !== false) {
		$data = explode(";",$line);
		$numero = count($datos);
        echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
        $fila++;
		foreach($data as $key => $value){
			$data[$key] = trim($value);
			$data[$key] = utf8_decode($value);
		}
		//$datos=implode(";",$datos);
		//$datos=utf8_encode($datos);
		//$datos=str_replace("'","",$datos);
		//$data = explode(";",$datos);
		$data[6]=trim(strtolower($data[6]));
		print_r($data);
		//$data[0]=utf8_encode($data[0]);
		$q = "SELECT email, id FROM ml_destinatarios where LOWER(TRIM(email))='{$data[6]}' LIMIT 1;";
		echo $q;
		$resultado = mysqli_query($link, $q) or die(mysqli_error());
		if (mysqli_num_rows($resultado) >= 1) {
			if($row = mysqli_fetch_array($resultado)) {
				$update = "UPDATE ml_destinatarios SET nombres = '{$data[1]}', apellidos = '{$data[2]}', cargo='{$data[3]}', departamento='{$data[4]}', empresa='{$data[5]}', email=TRIM('{$data[6]}') WHERE LOWER(TRIM(email))='{$data[6]}';";
				echo $update . "<br />";
				//mysqli_query($link, $update) or print(mysqli_error());	
			}
			/*while ($row = mysqli_fetch_array($resultado)) {
				$insert = "INSERT INTO ml_listas_detalle (lista_id, destinatario_id) VALUES (16,'{$row["id"]}');";
				echo $insert . "<br />";
				mysqli_query($link, $insert) or print(mysqli_error());	
				$update = "UPDATE ml_destinatarios SET nombres = '{$data[0]}' WHERE LOWER(email)='{$data[1]}';";
				echo $update . "<br />";
				mysqli_query($link, $update) or print(mysqli_error());	
			}*/
		}else{
			$insert = "INSERT INTO ml_destinatarios (grado,nombres,apellidos,cargo,departamento,empresa,email) VALUES ('{$data[0]}','{$data[1]}','{$data[2]}','{$data[3]}','{$data[4]}','{$data[5]}',TRIM('{$data[6]}'));";
			echo $insert . "<br />";
			mysqli_query($link, $insert) or print(mysqli_error());
			//$dest_id = 1;
			//$dest_id = mysqli_insert_id($link);
			//$insert = "INSERT INTO ml_listas_detalle (lista_id, destinatario_id) VALUES (16,'$dest_id');";
			//echo $insert . "<br />";	
			//mysqli_query($link, $insert) or print(mysqli_error());
		}
		
		
		
    }
    fclose($gestor);
}
mysqli_close($link) or die(mysqli_error());
?>