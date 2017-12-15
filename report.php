<?php

	include("FunctionNew.php");

	$riga=check_config();

	$nomedbrd=$riga[0];

	$hostname=$riga[1];

	$usr=$riga[2];

	$pwd=$riga[3];

	$toolusr=$riga[4];

	$toolpwd=$riga[5];

	$nomedbap='rdj_library_assistant';
	
	$control=0;
	

	
	$order= array("\r\n", "\n", "\r");
	$replace = '';
	
	$nomedbrd=str_replace($order, $replace,$nomedbrd);
	$hostname=str_replace($order, $replace,$hostname);
	$usr=str_replace($order, $replace,$usr);
	$pwd=str_replace($order, $replace,$pwd);
	$toolusr=str_replace($order, $replace,$toolusr);
	$toolpwd=str_replace($order, $replace,$toolpwd);

	
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


	$now="1217";
	$hour="12";
	$id_subcat=1;

	$mytime= different_convert_date($now)." ".$hour.":00:00";

	echo $mytime."<br>";

	$actual_day = date ('N', $mytime);

	echo $actual_day."<br>";

	//echo $now."<br>";

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
	echo "a:";
	print_r($a);
	echo "<br><br><br>";
	
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
	echo "b:";
	print_r($b);
	echo "<br><br><br><br>";

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

	echo "c:";	
	print_r($c);
	echo"<br><br><br>";


	for($x=0;$x<sizeof($c);$x++){

		$ID_exc=$c[$x][0];

		$ID_song=$c[$x][1];

		$grid="SELECT songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID='$ID_exc' ";

		$Grid=$connectionap->query($grid);

		$result=$Grid->fetch_assoc();
	

		for($y=(24*$actual_day);$y<((24*$actual_day)+24);$y++){

				$array_day[]=$result["grid"][$y];

		}

		/*echo "<br>";
		print_r($array_day);
		echo "<br>";*/
		$matrix[$x][]=$ID_song;
		$matrix[$x][]=$array_day[$hour];

		echo $ID_song.": ";
		print_r($array_day[$hour]);

		echo "<br>";

		$array_day= array();

	}
	echo "***************************<br>";
	print_r($matrix);
	echo "***************************<br>";

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	$i=0;

	$query="SELECT * FROM songs WHERE id_subcat='$id_subcat'";

	if($song_subcat=$connectionrd->query($query)){

		while($song=$song_subcat->fetch_assoc()){

			$category[$i][]=$song['ID'];
			$category[$i][]=$song['enabled'];

			echo $song['ID'].": ".$song['enabled'];

			echo "<br>";

			$i++;

		}
	}
	print_r($category);

	for($x=0;$x<sizeof($category);$x++){

		
		for($y=0;$y<sizeof($matrix);$y++){

			if($category[$x][0]==$matrix[$y][0]){

				$category[$x][1]=$matrix[$y][1];
				break;
			}else{
				$category[$x][1]=1;
			}
		}
	}

	echo "<br>.................<br><br><br><br>";
	print_r($category);
	echo "<br>.................<br><br><br><br>";

	$disabled=0;
	$all=sizeof($category);

	for($x=0;$x<sizeof($category);$x++){

		if ($category[$x][1]==0){
			$disabled=$disabled+1;
		}
	}
	$enabled = $all - $disabled;
	echo "<br>disabilitati: ".$disabled;
	echo "<br>abilitati: ".$enabled;
?>