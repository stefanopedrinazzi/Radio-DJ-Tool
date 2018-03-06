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

	include("FunctionNew.php");

	//connesione al database di RDJLA
	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);

	//assegnazione variabili tramite $_POST di main_eccezioni.php
	$song_ID = $_POST['ID_song'];

	$exception_ID = $_POST['ExceptionID'];
	
	//acquisizone dei valori dal DB
	$get="SELECT songs_exceptions.ID_song, songs_exceptions.data_in, songs_exceptions.data_out, songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID_song='$song_ID' AND songs_exceptions.ID='$exception_ID'";

	$take=$connectionap->query($get);

	$riga = $take->fetch_assoc();
	
	$data_start=different_convert_date($riga['data_in']);

	$data_end=different_convert_date($riga['data_out']);

	//restituzione di un oggetto contenente i valori restituiti dalla query
	echo json_encode(array('ID_song' => $riga['ID_song'] , 'data_start' => $data_start , 'data_end' => $data_end , 'eccezione'=>$riga['grid']));

?>