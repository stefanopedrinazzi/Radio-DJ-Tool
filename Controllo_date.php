<?php

	include("FunctionNew.php");

	//connesione al database del tool
	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);

	$song_ID = $_POST['ID_song'];

	$date_start = $_POST['date_start'];

	$date_end = $_POST['date_end'];

	$modify=$_POST['modify'];

	$ExceptionID=$_POST['ExceptionID'];

	//richiamo della funzione per convertire le date di inizio e di fine
	$data_start=Convert_date($date_start);

	$data_end=Convert_date($date_end);

	//richiamo della funzione di controllo per le date
	$control=Control_date($song_ID,$data_start,$ExceptionID);

	echo $control;

	if ($control==0) {
		$control=Control_date($song_ID,$data_end,$ExceptionID);		
		
		echo $control;
	} else {
		$control=1;
		echo $control;
	}
?>