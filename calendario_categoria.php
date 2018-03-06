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

	$riga=check_config();

	$language=$riga[7];

	$order= array("\r\n", "\n", "\r");
	$replace = '';

	$language=str_replace($order, $replace,$language);

	include("languages/".$language);

	//separazione ID nome della categoria passata da $_POST
	$var = $_POST['categoria'];

	$explode = explode('~', $var);

	$category=$explode[1];

	$category_ID=$explode[0];

	$rotation_array=array();

	//connessione al database di radiodj
	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	error_reporting(E_ERROR);

	//conteggio canzoni abilitate per la categoria passata
	$num="SELECT songs.ID,count(*) AS NUM FROM songs WHERE songs.id_subcat='$category_ID' AND songs.enabled='1'";

		if($number = mysqli_query($connectionrd,$num)){
		
			while($number_song = mysqli_fetch_assoc($number)){

				$num_song=$number_song['NUM'];			

				$total[]=$num_song;
			}
		}

	//ID categoria eventi di RDJLA
	if($cat=$connectionrd->query("SELECT ID FROM events_categories WHERE name='RDJLA-events'")){

		while($riga =$cat->fetch_assoc()){

				$cat_events_ID=$riga['ID'];
		}
	}

	//nome e ID delle rotazioni 
	if($name_events=$connectionrd->query("SELECT name,ID FROM rotations")){

		while($rotations=$name_events->fetch_assoc()){

			$rotation_name=$rotations['name'];

			$rotation_ID=$rotations['ID'];

			//informazioni riguardante gli eventi di RDJLA
			$data="SELECT data FROM events WHERE catID='$cat_events_ID'";
	
			$total[]=$rotation_name;

			//numero di chiamate della categoria nella rotazione 
			$number_rotation="SELECT count(*) as TOTAL FROM rotations_list WHERE subID='$category_ID' AND pID='$rotation_ID'";

				if($num_rotation=$connectionrd->query($number_rotation)){

					while($num_rot=$num_rotation->fetch_assoc()){

						$var=$num_rot['TOTAL'];

						$total[]=$var;
					}					
				}
		}
	}

	//ciclo per trovare il nome di tutti gli eventi della settimana
	for($x=1;$x<=7;$x++){

		for($y=0;$y<=23;$y++){

			$day="&".$x;
			$hours="&".$y;

			switch ($x) {
				case '1':
					$name=$translation['label_mon'];
					break;
				case '2':
					$name=$translation['label_tue'];
					break;
				case '3':
					$name=$translation['label_wed'];
					break;
				case '4':
					$name=$translation['label_thu'];
					break;
				case '5':
					$name=$translation['label_fri'];
					break;
				case '6':
					$name=$translation['label_sat'];
					break;
				case '7':
					$name=$translation['label_sun'];
					break;
			}

			if($y<10) {
									
				$name.="0".$y;							
			
			} else {

				$name.=$y;						
			}
			//selezione delle informazioni per ogni evento settimanale
			$data="SELECT data FROM events WHERE name='$name'";

			//creazione array in base ai risultati della query
			if($data_events = mysqli_query($connectionrd,$data)){
		
				while($events = mysqli_fetch_assoc($data_events)){

					$array1=explode("|", $events['data']);

					if($events['data']=="Clear Playlist!"){

						$rotation_array[]=0;

					}else{
						
						if($array1[0]=="Clear Playlist!
Load Rotation" && isset($array1[3])==false){

							$rotation_array[]=$array1[2];

						}else{

							$rotation_array[]=0;
						}
					}
				}
			}
		}
	}

	$result=array();
	error_reporting(E_ALL);

	//confronto dell'array contenente informazioni sulla rotazione e le chiamate della categoria
	for($x=0;$x<sizeof($rotation_array);$x++){

		for($y=1;$y<sizeof($total);$y++){

			if($rotation_array[$x]==$total[$y]){

				$result[$x]=$total[$y+1];

				break;
		
			}else{	

			}

			$y++;
		}
	}

	if(sizeof($result)==0){

		$result[]=0;
	}

	$i=0;

	$ult_result[]=$total[0];

	//ciclo per trovare il nome di tutti gli eventi della settimana
	for($x=1;$x<=7;$x++){

		for($y=1;$y<=24;$y++){

			switch ($x) {
				case '1':
					$name=$translation['label_mon'];
					break;
				case '2':
					$name=$translation['label_tue'];
					break;
				case '3':
					$name=$translation['label_wed'];
					break;
				case '4':
					$name=$translation['label_thu'];
					break;
				case '5':
					$name=$translation['label_fri'];
					break;
				case '6':
					$name=$translation['label_sat'];
					break;
				case '7':
					$name=$translation['label_sun'];
					break;
			}

			$app=$y-1;

			if($app<10){
				
				$name.="0".$app;
									
			}else{

				$name.=$app;
									
			}

			//controllo dell'esistenza dell'indice nell'array
			if(array_key_exists( $i , $result)==1){
				
				$ult_result[]=$name;
				$ult_result[]=$result[$i];
			}

		$day=$x-1;
		
		$hour=$y-1;
		
		//calcolo del numero di eccezioni dato giorno,ora e sottocategoria
		$n_exception=exception_value_day_hour_subcat($day,$hour,$category_ID);
		
		$ult_result[]="".$n_exception."";
		
		$i++;

		}
	}
	
	$ult_result=json_encode($ult_result);
	print_r($ult_result);

	$connectionrd->close();
	
?>