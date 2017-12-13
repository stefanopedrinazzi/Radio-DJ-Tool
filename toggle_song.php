<?php

	include("FunctionNew.php");

	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);


	$now = date ('Y-m-d', time());

	$exception="SELECT songs_exceptions.ID,songs_exceptions.ID_song FROM songs_exceptions WHERE '$now' BETWEEN songs_exceptions.data_in AND songs_exceptions.data_out";

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

	print_r($a);
	echo "<br>";

	$default="SELECT songs_exceptions.ID,songs_exceptions.ID_song FROM songs_exceptions WHERE data_in='0000-00-00 00:00:00'";

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

	print_r($b);
	$z=0;
	for($x=0;$x<=sizeof($b);$x++){
		for($y=0;$y<=sizeof($a);$y++){

			if($b[$x]!=$a[$y]){
				
				$c[$z]=$a[$x];
				$z++;
				break;
			}
			else{
				$c[$z]=$b[$x];
				$z++;

			}
		}
	}
	print_r($c);
?>

