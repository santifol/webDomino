<?php
function mi($errno, $errstr, $errfile, $errline){
	switch ($errno) {
		case E_USER_NOTICE:
			echo "<table class='error'><tr><th>". $errno."</th><th>". $errstr."</th><th>". $errfile."</th><th>". $errline.'</th><tr><table>';
		break;
		case E_NOTICE:
			echo "<table class='error'><tr><th>". $errno."</th><th>". $errstr."</th><th>". $errfile."</th><th>". $errline.'</th><tr><table>';
		break;
		default:
			echo "<table class='error'><tr><th>". $errno."</th><th>". $errstr."</th><th>". $errfile."</th><th>". $errline.'</th><tr><table>';
		break;
	}
}
$g = set_error_handler("mi");

?>