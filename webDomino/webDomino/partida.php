<?php
	session_start();

	if(isset($_SESSION['usuari_valid'])){
	include_once 'connexio.php';
		
		function comprobar2Jugadores($connexio){
		

			
			$query = "SELECT User, Foto, Online FROM logarse WHERE Online = 1";
						//Creamos la consulta

			$consulta = mysqli_query($connexio, $query)
				or die('error en la consulta');
			$num_filas = mysqli_num_rows($consulta);		//Ejecutamos la consulta y guarda el numero de filas del resultado, si hay alguno dara 1 sino 0

				
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
			<table class='a'>
			<tr><th class='a'> User </th><th class='a'> Foto </th><th class='a'> Online </th></tr>
			<?php
				echo "<h1> ".$query." </h1>";
				echo $num_filas;


			while ($fila = mysqli_fetch_array($consulta)) {
				echo "<tr><td class='a'>".$fila['User']."</td>";
				echo "<td class='a'><img src='imgUsus/".$fila['Foto']."' alt='imagen' /></td>";
				echo "<td class='a'>".$fila['Online']."</td></tr>";
			}

			?>
			</table>
</body>
</html>
<?php

			/*
			if($num_filas>0){	//Si hay algun resultado (1)
			
			if(){ //si hay 2 usuarios conectados, inicia juego
				
			}else{ //sino mostrara mensaje y tendra que esperar
				//mensaje esperando usuario

				//setInterval("comprobar2Jugadores()",3000);
			}
			*/

			
		}

		comprobar2Jugadores($connexio);

		function crearPartida(){
			echo "esta dentro de la funcion crear funcion";
		}

		function cambioTurno(){

		}

		function ganarPartida(){

		}
	}

?>