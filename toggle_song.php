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


	$now = date ('md', time());

	echo $now."<br>";

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


	//query per ricavare la grid settimanale di ogni traccia
	for($x=0;$x<sizeof($c);$x++){

		$ID_exc=$c[$x][0];

		$ID_song=$c[$x][1];

		$grid="SELECT songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID='$ID_exc' ";

		$Grid=$connectionap->query($grid);

		$result=$Grid->fetch_assoc();

		//print_r($result);
		
		echo "ID exception ".$ID_exc."<br>";		

		$actual_day = date ('N', time())-1;

		echo "Giorno della settimana ".$actual_day."<br>";
	
		for($y=(24*$actual_day);$y<((24*$actual_day)+24);$y++){

			$array_day[]=$result["grid"][$y];

		}

		print_r($array_day);
		
		$actual_hour = date ('H', time())+1;

		if($actual_hour>23){

			$actual_hour=0;

			$actual_day=$actual_day+1;
		}

		echo "<br>Ora per attivazione:".$actual_hour ."<br>";

		$status=$array_day[$actual_hour];

		echo $status ."<br><br>";

		toggle_song($ID_song,$status);

		$array_day= array();
	}


?>

