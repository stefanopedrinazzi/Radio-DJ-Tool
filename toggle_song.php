<?php

	include("FunctionNew.php");

	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);


	$now = date ('y-m-d', time());

	echo $now;

	//Array ID e ID_song di tutte le eccezioni con range di data che comprende la data e ora attuale

	$exception="SELECT songs_exceptions.ID,songs_exceptions.ID_song FROM songs_exceptions WHERE ('$now' BETWEEN songs_exceptions.data_in AND songs_exceptions.data_out) AND data_in!='0000-00-00 00:00:00'";

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
	echo "<br><br><br>";
	
	//Array ID e ID_song di tutte le eccezioni di default

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
	echo "<br><br><br><br>";

	//Creazione array di $c=$b-$a (default-eccezioni attive) 
	
	for($x=0;$x<sizeof($b);$x++){

			$flag=0;
		
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

	//Compilazione array $c completo di tutte eccezioni di default e le eccezioni attive ora
	for($x=0;$x<sizeof($a);$x++){

				
			$c[]=$a[$x];

	}		

	
	print_r($c);
	echo"<br>";


	//query per ricavare la grid settimanale di ogni traccia
	for($x=0;$x<sizeof($c);$x++){

		$ID_exc=$c[$x][0];


		$grid="SELECT songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID='$ID_exc' ";

		$Grid=$connectionap->query($grid);

		$result=$Grid->fetch_assoc();

		print_r($result);
		
		echo $ID_exc."<br>";		

		$actual_day = date ('N', time())-1;

		echo $actual_day."<br>";
	
		for($y=(24*$actual_day);$y<((24*$actual_day)+24);$y++){

			$array_day[]=$result["grid"][$y];

			

		}

		print_r($array_day);
		
		$actual_hour = date ('H', time());

		echo $actual_hour ."<br>";

		$ae_hour=$array_day[$actual_hour];

		echo $ae_hour ."<br><br>";
	}


?>

