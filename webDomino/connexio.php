<?php
	$connexio = mysqli_connect('localhost', 'root', 'rootDaw2', 'domino');	//Conectamos con  la base de datos
	if (!$connexio) {	//si la connexió no s'ha fet, salta el error
		die('Error de Conexió ('.mysqli_connect_errno().') '. mysqli_connect_error());
	}
?>