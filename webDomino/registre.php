<?php
session_start ();

if($_POST){
	include_once 'connexio.php';
	

	$d=$_POST['dni'];	
	$c=$_POST['cog'];
	$u=$_POST['usu'];
	$p=$_POST['pwd'];
	$f=$_FILES['imagen']['name'];
	
	$valido=false;
	
	$query="INSERT INTO logarse(idJugador, DNI, Nom, Cognom, User, Password, Foto, online) VALUES ('', '".$d."', '".$_POST['nom']."', '".$c."', '".$u."', '".$p."', '".$f."',0)";  //Creamos la consulta
		
	if((strlen($_POST['nom'])==0) || (strlen($_POST['usu'])==0) || (strlen($_POST['pwd'])==0)){ //comprovamos los campos vacios
		$aviso="Todos los campos tienen que estar rellenados"."<br>";
		$aviso2="Usuario NO registrado, vuelve a intentarlo"."<br>";
	}else{
		if(($_FILES['imagen']['type']=="image/jpg")||($_FILES['imagen']['type']=="image/jpeg")||($_FILES['imagen']['type']=="image/gif")||($_FILES['imagen']['type']=="image/png")){ //comprovamos la extension del fichero

			if (is_uploaded_file ($_FILES['imagen']['tmp_name'])){//subimos el fichero
		   		$nombreDirectorio = "imgUsus/";
		   		$nombreFichero = $_FILES['imagen']['name'];
		   		move_uploaded_file ($_FILES['imagen']['tmp_name'],$nombreDirectorio . $nombreFichero);

		   		$consulta=mysqli_query($connexio, $query) //Pasamos la connexión y la consulta
					or die ("Error al registre, usuari ja registrat, prova amb un altre");
				

				echo "<center><font color=\"green\">"." Usuario registrado correctamente!! "."</font></center><br>";
				echo "<a href='login.php'> Ves al login </a>";
				$valido=true;
		   }
	    }else{ 
	   	   	echo "<br>"."No se ha podido subir el fichero\n"."<br>";
			echo "<font color=\"red\">"."no es una imagen"."<br></font><br>";
	    } 		
	}
}
		
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset=UTF-8 />
<link rel="stylesheet" href="css/estilo.css" type="text/css" />
<title> Registre </title>
</head>

<body>

<h2> Registre </h2>
<form action="registre.php" method="post" enctype="multipart/form-data" name="form1">
	<table>
		<tr>
			<th> DNI </th>
			<td> <input type="text" name="dni" id="dni" /> </td>
		</tr>
		<tr>
			<th> Nombre </th>
			<td> <input type="text" name="nom" id="nom" />  </td>
		</tr>
		<tr>
			<th> Cognom </th>
			<td> <input type="text" name="cog" id="cog" /> </td>
		</tr>
		<tr>
			<th> User </th>
			<td> <input type="text" name="usu" id="usu" /> </td>
		</tr>
		<tr>
			<th> Password </th>
			<td> <input type="text" name="pwd" id="pwd" /> </td>
		</tr>
		<tr>
			<th> Foto </th>
			<td> <input type="file" name="imagen" id="imagen" /> </td>
		</tr>
		<tr>
			<td colspan="2"> <input type="submit" name="registrate" id="button" value="Enviar registre" /> </td>
		</tr>
	</table>
</form>

<?php
if ($_POST && !$valido){
	echo "<br>".$aviso."<br>";
	echo $aviso2."<br>";
	}
	
	echo "<a href='login.php'> Login </a>";
?>


</body>
</html>
