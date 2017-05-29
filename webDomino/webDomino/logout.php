<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset=UTF-8 />
<link rel="stylesheet" href="css/estilo.css" type="text/css" />
<title> Logout </title>
</head>

<body>
	<?php
		if(isset($_SESSION['usuari_valid'])){
			include_once 'connexio.php';
			$query2 = "UPDATE `domino`.`logarse` SET `online` = '0' WHERE `logarse`.`User` = '".$_SESSION['usuari_valid']."'";
			mysqli_query($connexio, $query2);	//Pasamos la connexión y la consulta
			session_destroy();
			$error="";
			
			echo "<a href='login.php'> Login </a>";
			}else{
				echo $error="No existeix connexió";
				echo "<a href='login.php'>";
			}
	?>
	
</body>
</html>