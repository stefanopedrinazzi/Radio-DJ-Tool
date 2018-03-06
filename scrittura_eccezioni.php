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

	include("languages/".$_SESSION['language']);

	include("FunctionNew.php");

	//connesione al database di RDJLA
	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);

	//variabili ottenute dal $_POST di main_eccezioni.php
	$song_ID= $_POST['ID_song'];

	$date_start=$_POST['date_start'];

	$date_end=$_POST['date_end'];

	$eccezione=$_POST['eccezione'];

	$modify=$_POST['modify'];

	$ExceptionID= $_POST['ExceptionID'];

	$array_exc=Convert_exception($eccezione);

	//conversione delle date inserite dall'utente
	$data_start=Convert_date($date_start);

	$data_end=Convert_date($date_end);

	//conversione delle date se a cavallo tra dicembre e gennaio
	if($data_start>$data_end){

		$data_start1=$data_start;

		$data_end1=1231;

		$data_start2=101;

		$data_end2=$data_end;

		$modify=0;

		//scritture delle eccezioni
		$exception=Set_exceptions($song_ID,$data_start1,$data_end1,$array_exc,$modify,$ExceptionID);
	
		$exception.=Set_exceptions($song_ID,$data_start2,$data_end2,$array_exc,$modify,$ExceptionID);
	
	}else{

		$exception=Set_exceptions($song_ID,$data_start,$data_end,$array_exc,$modify,$ExceptionID);
	}

	echo($exception);

?>