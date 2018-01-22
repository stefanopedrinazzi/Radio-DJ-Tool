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

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	$data=$_POST['data'];

	$cat= $_POST['categoria'];

	$hour=2;

	$explode = explode('~', $cat);

//	print_r($explode);

	$categoria=$explode[1];

	$id_subcat=$explode[0];

	//echo $data."<br>";

	$now=Convert_date($data);

	//echo $now."<br>";
	//$now=different_convert_date($now);

	if($now==""){

	$actual_day = date ('N', time())-1;

	}else{
	
	//echo $now."<br>";

	$mytime= different_convert_date($now).", 00:00:00";

	//echo $mytime."<br>";

	$mytime=strtotime($mytime);

	//echo $mytime."<br>";

	$actual_day = date('N', $mytime)-1;

	}

	//echo $actual_day ."<br>";

	/*$ID_song_subcat="SELECT songs.ID FROM songs id_subcat='$id_subcat'";

		if($song_subcat=$connectionrd->query($ID_song_subcat)){

		while($song=$song_subcat->fetch_assoc()){

			$IDsong=$song['ID'];*/
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

		$grid="SELECT songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID='$ID_exc'";

		$Grid=$connectionap->query($grid);

		$result=$Grid->fetch_assoc();
	

				if($hour==0){

					$y=24*$actual_day;
				
				}else{

					$y=24*$actual_day+$hour;

				}

				$array_day[$x][]=$ID_song;

				$array_day[$x][]=$result["grid"][$y];

	}


	
	print_r($array_day);

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	

	$query="SELECT ID FROM songs WHERE id_subcat='$id_subcat'";

	if($song_subcat=$connectionrd->query($query)){

		while($song=$song_subcat->fetch_assoc()){

			$category[]=$song['ID'];

		}
	}

	//echo "<br><br><br>";
	//print_r($category);


	for($x=0;$x<sizeof($category);$x++){

		for($y=0;$y<sizeof($array_day);$y++){

			if($category[$x]==$array_day[$y][0]){

				$res[]=$array_day[$y][1];
			}

		}
	}
	//echo "<br><br><br>";
	print_r($res);

	$disattive=0;

	for($x=0;$x<sizeof($res);$x++){

		if($res[$x]==1){

			$disattive+=1;

		}
	}
	echo "<br><br><br>";
	
	echo $disattive;

	$connectionrd->close();
	$connectionap->close();


	
	?>