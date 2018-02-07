<?php

/**
 * RadioDJ Library Assistant
 * @link https://github.com/stefanopedrinazzi/RadioDJ-Library-Assistant
 * Version: 1.0.0
 *
 * Copyright 2017-2018 Stefano Pedrinazzi & Paolo Camozzi
 * Released under the MIT license
 * @link https://github.com/stefanopedrinazzi/RadioDJ-Library-Assistant/blob/master/LICENSE.md
 */

	include("languages/eng.php");

	include("FunctionNew.php");

	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);


	$song_ID = $_POST['ID_song'];

	$exception_ID = $_POST['ExceptionID'];
	
	$get="SELECT songs_exceptions.ID_song, songs_exceptions.data_in, songs_exceptions.data_out, songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID_song='$song_ID' AND songs_exceptions.ID='$exception_ID'";

	$take=$connectionap->query($get);

	$riga = $take->fetch_assoc();
	
	$data_start=different_convert_date($riga['data_in']);

	$data_end=different_convert_date($riga['data_out']);

	echo json_encode(array('ID_song' => $riga['ID_song'] , 'data_start' => $data_start , 'data_end' => $data_end , 'eccezione'=>$riga['grid']));

?>