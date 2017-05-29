<?php
	//session_destroy();
	session_start();
	include_once 'error.php';

	$error="";
	if(isset($_POST['enviar'])){
		include_once 'connexio.php';	
		
		$u=$_POST['usu'];	//Guardamos el usuario introducido en el formulario en la variable $u

		$query = "SELECT * FROM logarse WHERE User='".$u."' AND Password='".$_POST['pwd']."' ";		//Creamos la consulta
		$consulta = mysqli_query($connexio, $query);	//Pasamos la connexión y la consulta
		$num_filas = mysqli_num_rows($consulta);		//Ejecutamos la consulta y guarda el numero de filas del resultado, si hay alguno dara 1 sino 0
		
		if($num_filas>0){	//Si hay algun resultado (1)
			$_SESSION['usuari_valid']=$u;	//Crearemos la variable de session usuari_valid	
			$query2 = "UPDATE `domino`.`logarse` SET `online` = 1 WHERE `logarse`.`User` = '".$u."'";

			$consulta2 =mysqli_query($connexio, $query2)	//Pasamos el valor 1 para indicar que esta online
				or die ('error a la 2 consulta');
		}else{
			$error="El usuario y password introducidos no coinciden con nuestra base de datos, vuelve a probar";	//Sino que mande un mensaje de error
			echo $error;
		}
	} 
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset=UTF-8 />
<!--Autor: Santos Sánchez Sola-->
<title> Login </title>
<link rel="stylesheet" href="css/estilo.css" type="text/css" />
</head>
<body>
	<h1> Login - Sesiones </h1>
	<?php
		if(isset($_SESSION['usuari_valid'])){
			header("location:registrat.php");
		}else{	//Sino accede al formulario para introducir los datos
		?>
			<form id="form1" method="post" action="login.php"> 
				<table>
					<tr>
						<td> Login </td>
						<td> <input name="usu" type="text" id="usu" placeholder="nombre"/> </td>
					</tr>
					<tr>
						<td> Password </td>
						<td> <input name="pwd" type="password" id="pwd" placeholder="password"/>	</td>
					</tr>
					<tr>
						<td colspan="2"> <input type='submit' name='enviar' value='Enviar' id="button"/> </td>
					</tr>
				</table>
			</form>
		
		<?php	
			echo $error;
		}
			echo "<a href='registre.php'> Registre </a>";
		?>
</body>
</html>