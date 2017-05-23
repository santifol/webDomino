<?php 
	session_start();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset=UTF-8 />
<title> Pagina registrada </title>
<link rel="stylesheet" href="css/estilo.css" type="text/css" />
</head>

<body>
<?php
	if(isset($_SESSION['usuari_valid'])){
		include_once 'connexio.php';
			
		$query="SELECT * FROM logarse WHERE User='".$_SESSION['usuari_valid']."' ";
		
		echo "<p> Benvingut ".$_SESSION['usuari_valid']."!!</p><br>";
		$consulta = mysqli_query($connexio, $query)
			or die('error en la consulta');
		$num_filas = mysqli_num_rows($consulta);
					
		echo "<table class='a'>";
		echo "<tr><th class='a'> DNI </th><th class='a'> Nom </th><th class='a'> Cognom </th><th class='a'> User </th><th class='a'> Password </th><th class='a'> Foto </th></tr>";
		while ($fila = mysqli_fetch_array($consulta)) {
			echo "<tr><td class='a'>";
			echo $fila[1];
			echo "</td><td class='a'>";
			echo $fila[2];
			echo "</td><td class='a'>";
			echo $fila[3];
			echo "</td><td class='a'>";
			echo $fila[4];
			echo "</td><td class='a'>";
			echo $fila[5];
			echo "</td><td class='a'>";
			echo "<img src='imgUsus/".$fila[6]."' alt='imagen' />";
			echo "</td></tr>";
		}
		echo "</table>";
		
		echo "<br /><a href='logout.php'> Desconectarse </a><br />";
		}else{
			echo $error="Usuari no valid"."<br />";
			echo "<br><a href='login.php'> Login </a>";
			}		

?>

</body>
</html>

