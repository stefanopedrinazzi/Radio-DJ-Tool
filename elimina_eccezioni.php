<?php
	
	include("languages/eng.php");

	include("FunctionNew.php");

	//connessione al database del tool
	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);

	$song_ID= $_POST['ID_song'];

	$ExceptionID= $_POST['ExceptionID'];

	//richiamo della funzione per eliminare le eccezioni
	$delete=Delete_exceptions($song_ID,$ExceptionID);

	return ($delete);
?>