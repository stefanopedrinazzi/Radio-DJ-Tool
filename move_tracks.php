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
	
	error_reporting(E_ERROR);

	$logger = fopen("log_move_track.txt", "a") or die("Unable to open file!");

	$actual_hour = date ('H', time());

	fwrite($logger, gmdate("Y-m-d ".$actual_hour.":i:s",time()).PHP_EOL);

	$info=Sposta_file();

	fwrite($logger,$info.PHP_EOL);

	error_reporting(E_ALL);

?>