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
?>

<!DOCTYPE html>
<html>
	
	<head>
	<title>Toggle Song</title>
	<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  	<script src="js/semantic.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
   			if (window.location.href.indexOf('reload')==-1) {
         		window.location.replace(window.location.href+'?reload');
   			}
		});

		setTimeout (window.close, 10000);
		
	</script>
	</head>
	<body>
	
		<div id="caricamento" class="ui active inverted dimmer">
    		<div class="ui massive text loader">Loading...</div>
  		</div>
	
	</body>
</html>
<?php

	include("FunctionNew.php");

	$riga=check_config();

	$nomedbrd=$riga[0];

	$hostname=$riga[1];

	$usr=$riga[2];

	$pwd=$riga[3];

	$toolusr=$riga[4];

	$toolpwd=$riga[5];

	$language=$riga[7];

	$nomedbap='rdj_library_assistant';
	
	$control=0;
	
	$logger = fopen("log_active_track.txt", "a") or die("Unable to open file!");
	
	$order= array("\r\n", "\n", "\r");
	$replace = '';
	
	$nomedbrd=str_replace($order, $replace,$nomedbrd);
	$hostname=str_replace($order, $replace,$hostname);
	$usr=str_replace($order, $replace,$usr);
	$pwd=str_replace($order, $replace,$pwd);
	$toolusr=str_replace($order, $replace,$toolusr);
	$toolpwd=str_replace($order, $replace,$toolpwd);
	$language=str_replace($order, $replace,$language);

	include("languages/".$language);

	
	if(!test_db_connection($nomedbrd,$hostname,$usr,$pwd)){

		$control=0;

	}else{

		if(!test_db_connection($nomedbap,$hostname,$toolusr,$toolpwd)){

			$control=0;
		}else{

			$control=1;
		}

	}

	if($control==1){
		$_SESSION['db_namerd']=$nomedbrd;
		$_SESSION['hostnamerd']=$hostname;
		$_SESSION['usernamerd']=$usr;
		$_SESSION['passwordrd']=$pwd;
		$_SESSION['usernameap']=$toolusr;
		$_SESSION['passwordap']=$toolpwd;
	
	}


	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);

	$now = date ('md', time());

	if(substr($now,-4,1)==0){

			$now=substr($now,-3, 3);
		}

	fwrite($logger," ".PHP_EOL);

	$actual_hour = date ('H', time());

	fwrite($logger, gmdate("Y-m-d ".$actual_hour.":i:s",time()).PHP_EOL);

	$hour = date ('H', time())+1;
		if($hour>23){

			$hour=0;

		}

	$stamp=$translation['info_toggle'].": ".$hour;

	fwrite($logger, $stamp.PHP_EOL);


	//Array ID e ID_song di tutte le eccezioni con range di data che comprende la data e ora attuale

	$exception="SELECT songs_exceptions.ID,songs_exceptions.ID_song FROM songs_exceptions WHERE ('$now' BETWEEN songs_exceptions.data_in AND songs_exceptions.data_out) AND data_in!='0'";

	$i=0;
	$x=0;
	if($exc_date=$connectionap->query($exception)){

		while($exc=$exc_date->fetch_assoc()){

			$a[$i][$x]=$exc['ID'];
			$x=1;
			$a[$i][$x]=$exc['ID_song'];
			$i++;
			$x=0;
		}
		
	}

	
	//Array ID e ID_song di tutte le eccezioni di default

	$default="SELECT songs_exceptions.ID,songs_exceptions.ID_song FROM songs_exceptions WHERE data_in='0'";

	$i=0;
	$x=0;
	if($def_date=$connectionap->query($default)){

		while($def=$def_date->fetch_assoc()){

			$b[$i][$x]=$def['ID'];
			$x=1;
			$b[$i][$x]=$def['ID_song'];
			$i++;
			$x=0;
		}
		
	}

	//Creazione array di $c=$b-$a (default-eccezioni attive) 
	
	for($x=0;$x<sizeof($b);$x++){

			$flag=0;
		if(sizeof($a)==0){
			$c[]=$b[$x];
		}else{
			for($y=0;$y<sizeof($a);$y++){


				if($b[$x][1]!=$a[$y][1]){
					
					$flag=1;	
				
				}else{

					$flag=0;
					
					break;
				}

			}
			if($flag==1){

				$c[]=$b[$x];

			}
		}
		
	
	}

	//Compilazione array $c completo di tutte eccezioni di default e le eccezioni attive ora
	for($x=0;$x<sizeof($a);$x++){

				
			$c[]=$a[$x];

	}		


	//query per ricavare la grid settimanale di ogni traccia
	for($x=0;$x<sizeof($c);$x++){

		$ID_exc=$c[$x][0];

		$ID_song=$c[$x][1];

		$grid="SELECT songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID='$ID_exc' ";

		$Grid=$connectionap->query($grid);

		$result=$Grid->fetch_assoc();

		
		fwrite($logger, "ID exception ".$ID_exc." ");		

		$actual_day = date ('N', time())-1;


		//creo l'array per il giorno corrente
		for($y=(24*$actual_day);$y<((24*$actual_day)+24);$y++){

			$array_day[]=$result["grid"][$y];

		}

	
		$status=$array_day[$hour];


		fwrite($logger,toggle_song($ID_song,$status).PHP_EOL);

		$array_day= array();
	}


?>

