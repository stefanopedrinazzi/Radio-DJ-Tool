<!DOCTYPE html>
<html>
	
	<head>
	<title>Move Tracks</title>
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
    		<div class="ui massive text loader">Copia dei file in corso...</div>
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

	
	error_reporting(E_ERROR);

	$logger = fopen("log_move_track.txt", "a") or die("Unable to open file!");

	$actual_hour = date ('H', time());

	fwrite($logger, gmdate("Y-m-d ".$actual_hour.":i:s",time()).PHP_EOL);

	$info=Sposta_file();

	fwrite($logger,$info.PHP_EOL);

	error_reporting(E_ALL);

?>