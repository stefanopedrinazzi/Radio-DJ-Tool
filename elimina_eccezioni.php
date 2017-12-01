<?php


	include("FunctionNew.php");

	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);

	$song_ID= $_POST['ID_song'];

	$ExceptionID= $_POST['ExceptionID'];

	$delete=Delete_exceptions($song_ID,$ExceptionID);

	return ($delete);
?>