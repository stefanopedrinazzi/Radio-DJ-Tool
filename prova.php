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

	include("languages/".$_SESSION['language']);

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	$var = $_POST['categoria'];

	$ID_week= $_POST['ID_week'];

	$explode = explode('~', $var);

	$category=$explode[1];

	$category_ID=$explode[0];

	$hour=substr($ID_week, 3);

	$day=substr($ID_week, 0 , strlen($ID_week)-2);


	switch ($day) {
		case $translation['label_mon']:
	   		$day=1;
	        break;
	    case $translation['label_tue']:
	        $day=2;
	        break;
	    case $translation['label_wed']:
	        $day=3;
	     	break;
	    case $translation['label_thu']:
	    	$day=4;
	    	break;
	    case $translation['label_fri']:
	     	$day=5;
	        break;
	    case $translation['label_sat']:
	    	$day=6;
	     	break;
	    case $translation['label_sun']:
	    	$day=7;
	     	break;        		
	}

	$actual_month = date ('m', time());

	$actual_week = date ('W', time());

	$actual_year = date ('Y', time());

	$date1 = date_create();

	date_isodate_set($date1, $actual_year, $actual_week, $day);
	
	$between1=date_format($date1, 'Y-m-d');

	$between1.=" ".$hour.":00:00";


	$hour_plus=intval($hour);

	if($hour_plus+1==24){

		$hour_plus="00";

		$day+=1;
	}else{

		$hour_plus+=1;
		if($hour_plus<10){

			$hour_plus="0".$hour_plus;
		
		}else{

			$hour_plus=strval($hour_plus);
		}
	}

	$date2 = date_create();

	date_isodate_set($date2, $actual_year, $actual_week, $day);

	$between2=date_format($date2, 'Y-m-d');

	$between2.=" ".$hour_plus.":00:00";

	$song="";

	echo $between1."\n";

	echo $between2."\n";

//_____________________________________________________	


	$query="SELECT history.title, history.artist FROM history WHERE (history.date_played BETWEEN '$between1' AND '$between2') AND history.id_subcat='$category_ID' ORDER BY history.date_played";

	if($result=$connectionrd->query($query)){

		while($res=$result->fetch_assoc()){

			$song.=$res['title']." ".$res['artist']."\n";

		}
	}

	$connectionrd->close();

	echo $song;
?>