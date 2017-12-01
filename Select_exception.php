<?php


	include("FunctionNew.php");

	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);
	
	
	$song_ID = $_POST['ID_song'];

	$exception_ID = $_POST['ExceptionID'];
	
	$get="SELECT songs_exceptions.ID_song, songs_exceptions.data_in, songs_exceptions.data_out, songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID_song='$song_ID' AND songs_exceptions.ID='$exception_ID'";

	$take=$connectionap->query($get);

	$riga = $take->fetch_assoc();

	echo json_encode(array('ID_song' => $riga['ID_song'] , 'data_start' => $riga['data_in'] , 'data_end' => $riga['data_out'] , 'eccezione'=>$riga['grid']));

?>