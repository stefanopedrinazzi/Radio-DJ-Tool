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

	//connessione al database del tool
	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);

	//assegnazione variabili ottenute da $_POST del file main_eccezioni.php 
	$song_ID= $_POST['ID_song'];

	$ExceptionID= $_POST['ExceptionID'];

	//richiamo della funzione per eliminare le eccezioni
	$delete=Delete_exceptions($song_ID,$ExceptionID);

	return ($delete);
?>