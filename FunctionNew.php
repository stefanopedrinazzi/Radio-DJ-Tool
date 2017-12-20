<?php
	 
	session_start();

	error_reporting(E_ERROR);
	
	$hostnamerd=$_SESSION['hostnamerd'];
	$usernamerd=$_SESSION['usernamerd'];
	$passwordrd=$_SESSION['passwordrd'];
	$db_namerd=$_SESSION['db_namerd'];
	$hostnameap=$_SESSION['hostnamerd'];
	$usernameap=$_SESSION['usernameap'];
	$passwordap=$_SESSION['passwordap'];
	$db_nameap="rdj_library_assistant";
	
	error_reporting(E_ALL);
/*	
	
	$hostnamerd="";
	$usernamerd="";
	$passwordrd="";
	$db_namerd="";
	$hostnameap="";
	$usernameap="";
	$passwordap="";
	$db_nameap="";
	
*/		
	function check_config(){

		$path = 'config.txt';
		if (file_exists($path)) {

			$config = fopen("config.txt", "r") or die("Unable to open file!");

				while(!feof($config)) {
		  			$riga[]=fgets($config);
				}
			
			fclose($config);

			if($riga[0]==""){

				return 1;
			
			}else{

				return $riga;

			}

			

		
		}else{

			$config = fopen("config.txt", "w") or die("Unable to open file!");
		
			fclose($config);

			return 1;
		}

	}

	
	function DBap_connection(){

		//$URL="http://localhost/phpmyadmin/rdj_library_assistant/";

		global $hostnameap,$usernameap,$passwordap,$db_nameap;

		$connectionap = new mysqli($hostnameap,$usernameap,$passwordap,$db_nameap);

		if ($connectionap->connect_error) {
 		   die('Failed to connect to MySQL:  (' . $connectionap->connect_errno . ')'
        . $connectionap->connect_error);
		}

		return $connectionap;
	}


	function DBrd_connection(){

		global $hostnamerd,$usernamerd,$passwordrd,$db_namerd;

		//$URL="http://localhost/phpmyadmin/radiodjnew/";

		$connectionrd = new mysqli($hostnamerd,$usernamerd,$passwordrd,$db_namerd);

		if ($connectionrd->connect_error) {
 		   die('Failed to connect to MySQL: ('. $connectionrd->connect_errno.')'
 		. $connectionrd->connect_error);
		}

		return $connectionrd;

	}

	function test_db_connection(&$db_name,&$hostname,&$username,&$password){

			/*$connection =new mysqli($hostname,$username,$password,$db_name);

			if (!$connection) {
				mysqli_close ($connection);
	 			return 0;
			}else{
				mysqli_close ($connection);
				return 1;
			}


*/
			error_reporting(E_ERROR);
			$link = mysqli_connect($hostname,$username,$password,$db_name);

			/* check connection */
			if (mysqli_connect_errno()) {
				return false;
			}

			/* check if server is alive */
			if (mysqli_ping($link)) {
			    mysqli_close($link);
			    return true;
			} else {
				mysqli_close($link);
				return false;
			}
			error_reporting(E_ALL);
	}


	function Modifica_tabella_appoggio(&$destination_path,&$directory,&$categoryID){

		$connectionrd=DBrd_connection();
		$connectionap=DBap_connection();

		global $db_namerd;
		global $db_nameap;

		mysqli_select_db($connectionrd,$db_namerd);
		mysqli_select_db($connectionap,$db_nameap);

		$flag="";
		$new="";

		$query="SELECT songs.path,songs.ID FROM songs JOIN subcategory ON subcategory.ID=songs.id_subcat WHERE subcategory.ID='$categoryID'";

		//echo $query;
		//echo "<br>	";

		//acquisizione path dalla tabella song radio dj
		if($oldpath= $connectionrd->query($query)){

			//print_r ($oldpath);
			
			while ($view=$oldpath->fetch_array()) {

				//print_r($view);

				//variabile contenete ID songs
				$songID=$view['ID'];
				//variabile contenente vecchio path
				$old=$view['path'];

				$explode = explode('\\', $old);
    		
    			//rimozione del nome del file dal path
				$file=array_pop($explode);

				//assegnazione nuovo path			
				$newpath=$destination_path.$directory."\\".$file;

				//acquisizione ID song tabella di appoggio


				if($control=$connectionap->query("SELECT moving_list.ID FROM moving_list")){

					//echo $songID."<br>";

					//echo $control->num_rows ."<br>";

					if($control->num_rows == 0){
						
						//echo $control->num_rows ."<br>";

						$write="INSERT INTO moving_list (ID,old_path,new_path) VALUES ('$songID','$old','$newpath')";
			
							$new=addcslashes($write, '\\');
							
						$connectionap->query($new);

			    			
					
							//print_r($new);

					}

					while($controlID=$control->fetch_array()){					

					//	if($controlID['ID']==$songID){

					//		echo("update <br> ");
							
							//$update="UPDATE tabella_appoggio SET  new_path='$newpath'";

							//$new1=addcslashes($update, '\\');

					//		$flag=1;

							//($connectionap->query($new1));

					//		break;							
					//	}
					//}
					//	if($flag!=1){

							$write="INSERT INTO moving_list (ID,old_path,new_path) VALUES ('$songID','$old','$newpath') ON DUPLICATE KEY  UPDATE new_path='$newpath'";
			
							$new=addcslashes($write, '\\');
							
						if($connectionap->query($new)){
			    				//echo "Aggiornamento Database avvenuto con successo. <br>";
							}
						}		
					
				}
			}
		}	

		$connectionrd->close();
		$connectionap->close();
			
		return "tabella di appoggio aggiornata con successo. \n\n";

	}

	

	function Sposta_file(){

		$info="";

		$connectionrd=DBrd_connection();
		$connectionap=DBap_connection();

		global $db_namerd;
		global $db_nameap;

		mysqli_select_db($connectionrd,$db_namerd);
		mysqli_select_db($connectionap,$db_nameap);

		$queryoldpath="SELECT moving_list.ID,moving_list.old_path FROM moving_list";

		//ricavo ID e old path dalla tabella di appoggio per le canzoni da spostare
		if($oldpath=$connectionap->query($queryoldpath)){


		while ($view=$oldpath->fetch_array()){

			$flag=0;
			//ID canzone da spostare
			$songID=$view['ID'];
			$info.="ID song ".$songID ."\nOld path: ".$view['old_path'] . "\n";

			$queryqueuelist="SELECT queuelist.songID FROM queuelist JOIN songs ON songs.ID=queuelist.songID";

			if($getqueuelistID=$connectionrd->query($queryqueuelist)){

				while ($queuelistID=$getqueuelistID->fetch_array()){


					//ID della traccia nella queuelist	
					$nextID=$queuelistID['songID'];
					
					//se l'ID della canzone da spostare è presente nella queuelist passa al prossimo
					if($songID==$nextID){
					
						//imposto il flag 
						$flag=1;

						$info.= "è presente nella quelist. \n";

						break;
			
					}

				}

			}

			//se il flag non è attivo 
			if($flag==0){
				
				$old_path=$view['old_path'];

				$querynewpath="SELECT moving_list.new_path FROM moving_list WHERE moving_list.ID='$songID'";

				$newpath=$connectionap->query($querynewpath);

			
				$sql_new_path=$newpath->fetch_array();
			
				$new_path=$sql_new_path['new_path'];

				$info.= "New path: " .$new_path . "\n";

				if(rename($old_path, $new_path)===TRUE){
					
					$update ="UPDATE songs SET songs.path='$new_path' WHERE songs.ID='$songID'";

					$new=addcslashes($update, '\\');

					if($connectionrd->query($new)){
	    				$info.= "Copia file avvenuta con successo. \n";
					} else {
						$info.= "ERROR: Could not able to execute $new. " . mysqli_error($connectionrd) ."<br>";
					}

					$delete ="DELETE FROM moving_list WHERE moving_list.ID='$songID'";

					if($connectionap->query($delete)){
	    				$info.= "Eliminazione file avvenuta con successo. \n\n";
					} else {
						$info.= "ERROR: Could not able to execute $delete. " . mysqli_error($connectionap) ."<br>";
					}
					
				}
				else{
					continue;
				}	
					
			}else{

				$flag=0;
			
		
			}
		}

		}

		$connectionrd->close();
		$connectionap->close();

		return $info;
	}


	function Stamp_directory(){

		$connectionrd=DBrd_connection();

		mysqli_select_db($connectionrd,$db_namerd);


		$stamp_category = "";

		$category=query("SELECT subcategory.name, subcategory.ID FROM category JOIN subcategory ON subcategory.parentid=category.ID WHERE parentid=1");

		if($cat=$connectionrd->query($category)){


			while($riga =$category->fetch_assoc()){
		    
		    	$stamp_category .= "<option value=\"".$riga['ID']."~".$riga['name']."\">" . $riga['name'] ."</option>" ;

			} 
		} 

		$connectionrd->close();

	}


	function Get_exception(&$songID){

		$connectionap=DBap_connection();

		global $db_nameap;

		mysqli_select_db($connectionap,$db_nameap);


		$ID_exc_query="SELECT songs_exceptions.ID FROM songs_exceptions WHERE songs_exceptions.ID_song='$songID' ORDER BY songs_exceptions.data_in ASC";

		
		$exc="[";


		if($ID=$connectionap->query($ID_exc_query)){

			while($riga =$ID->fetch_assoc()){

				
				$exc.=$riga['ID'].",";
				
			}
		}
		if(strlen($exc)>1){
		$exc=substr($exc, 0 , strlen($exc)-1);
		}
		$exc.="]";


		$connectionap->close();

		return($exc);
	
	}

	//funzione per contare il numero di eccezioni presenti per ogni traccia
	function Number_exception(){

		$connectionap=DBap_connection();

		$array=array();

		global $db_nameap;

		mysqli_select_db($connectionap,$db_nameap);

		$exception="SELECT ID_song,count(*) AS total FROM songs_exceptions GROUP BY ID_song";

		if($exc=$connectionap->query($exception)){

			while($riga =$exc->fetch_assoc()){

			$array[$riga['ID_song']]=$riga['total'];

			}
		}

		$connectionap->close();
		return($array);

	}

	//funzione per acquisire titolo e artista della canzone per stampare nel form di eccezioni
	function Get_AT(&$songID){

		$connectionrd=DBrd_connection();

		global $db_namerd;

		mysqli_select_db($connectionrd,$db_namerd);


		$AT="SELECT songs.artist,songs.title FROM songs WHERE songs.ID='$songID'";


		$at=$connectionrd->query($AT);

		$riga =$at->fetch_assoc();

		
		$artist_title = array(
		$riga['artist'], $riga['title']
		);

		$connectionrd->close();
		

		return($artist_title);


	}

	function Convert_exception(&$grid){

	$array_exc="";

	for($x=0;$x<7;$x++){
		for($y=0;$y<24;$y++){
			$array_exc.= "".$grid[$x][$y]."";
		}
	}

	 return $array_exc;

	}

	function Convert_date(&$data){

		$date="";
		
		$app= "";

		$explode=explode(' , ', $data);

		$date_day=$explode[0];
		
		if($date_day!=0){
			$app=$explode[1];
			
			switch ($app) {
	    		case "Gennaio":
	        		$app="01";
	        		break;
	    		case "Febbraio":
	        		$app="02";
	        		break;
	        	case "Marzo":
	        		$app="03";
	        		break;
	        	case "Aprile":
	        		$app="04";
	        		break;
	        	case "Maggio":
	        		$app="05";
	        		break;
	        	case "Giugno":
	        		$app="06";
	        		break;
	        	case "Luglio":
	        		$app="07";
	        		break;
	        	case "Agosto":
	        		$app="08";
	        		break;
	        	case "Settembre":
	        		$app="09";
	        		break;
	        	case "Ottobre":
	        		$app="10";
	        		break;
	        	case "Novembre":
	        		$app="11";
	        		break;
	        	case "Dicembre":
	        		$app="12";
	        		break;		
			}
		

		$date=$app.$date_day;
		}
		return ($date);

	}


	function Set_exceptions(&$songID,&$data_start,&$data_end,&$grid,&$modify,&$ExceptionID){

		$connectionap=DBap_connection();

		global $db_nameap;

		mysqli_select_db($connectionap,$db_nameap);


		if($modify==0){
			$insert="INSERT INTO songs_exceptions (ID_song,data_in,data_out,grid) values ('$songID','$data_start','$data_end','$grid')";
		
		}else{

			$insert="UPDATE songs_exceptions SET data_in='$data_start', data_out='$data_end', grid='$grid' WHERE songs_exceptions.ID_song='$songID' AND  songs_exceptions.ID='$ExceptionID'";
		}
		
		$inser=$connectionap->query($insert);
		
		$connectionap->close();

		echo ($inser);
	}


	function Delete_exceptions(&$songID,&$ExceptionID){

		$connectionap=DBap_connection();

		global $db_nameap;

		$ID_default="";

		$active_delete=0;

		mysqli_select_db($connectionap,$db_nameap);

		$query="SELECT songs_exceptions.ID FROM songs_exceptions WHERE songs_exceptions.data_in='0' AND songs_exceptions.ID_song='$songID'";

		if($control=$connectionap->query($query)){

			while($default = $control->fetch_assoc()){

				$ID_default=$default['ID'];
			}
		}

		$query1="SELECT count(*) AS count FROM songs_exceptions WHERE songs_exceptions.data_in!='0' AND songs_exceptions.ID_song='$songID'";

		if($num_exc=$connectionap->query($query1)){

			while($num = $num_exc->fetch_assoc()){

				if($num['count']!=0){
					$number_exception=$num['count'];
				}else{
					$number_exception=0;
				}
			}
		}
	
		if($number_exception>0 && $ID_default==$ExceptionID){

			$connectionap->close();

			echo 0;

		}else{
		
			$delete="DELETE FROM songs_exceptions WHERE songs_exceptions.ID_song='$songID' AND songs_exceptions.ID='$ExceptionID'";

			$delet=$connectionap->query($delete);

			$connectionap->close();

			echo 1;
		}
	
	}

	function Get_date(&$songID){

		$connectionap=DBap_connection();

		global $db_nameap;

		mysqli_select_db($connectionap,$db_nameap);

		$date_string="[";

		$date="SELECT songs_exceptions.data_in, songs_exceptions.data_out FROM songs_exceptions WHERE songs_exceptions.ID_song='$songID' ORDER BY songs_exceptions.data_in ASC";

		if($date_array=$connectionap->query($date)){

		

			while($riga = $date_array->fetch_assoc()){

				//$data_start=substr($riga['data_in'], 5, 6);
				
				//$data_end=substr($riga['data_out'], 5 , 6);

				//$start=Convert_date_name($data_start);
				
				//$end=Convert_date_name($data_end);


				$start=Convert_date_name($riga['data_in']);
				
				$end=Convert_date_name($riga['data_out']);


				if($start=="Default"){
					$end="";

				$end=substr($end, 0 , strlen($end));

				$date_string .= "\"".$start."\",";
				}else{

				$end=substr($end, 0 , strlen($end));

				$date_string .= "\"dal ".$start." al ".$end."\",";
				}

			}
		}
		
		if(strlen($date_string)>1){
		
			$date_string=substr($date_string, 0 , strlen($date_string)-1);
		
		}
		
		$date_string.="];";
		
		$connectionap->close();

		return ($date_string);

	}

	function Convert_date_name(&$data){

		$day=substr($data,-2);

		$mese=explode($day,$data);

		//$explode=explode('-', $data);

		//$mese=$explode[0];
		$mese=$mese[0];

		switch ($mese) {
	    		case "1":
	        		$mese="Gennaio";
	        		break;
	    		case "2":
	        		$mese="Febbraio";
	        		break;
	        	case "3":
	        		$mese="Marzo";
	        		break;
	        	case "4":
	        		$mese="Aprile";
	        		break;
	        	case "5":
	        		$mese="Maggio";
	        		break;
	        	case "6":
	        		$mese="Giugno";
	        		break;
	        	case "7":
	        		$mese="Luglio";
	        		break;
	        	case "8":
	        		$mese="Agosto";
	        		break;
	        	case "9":
	        		$mese="Settembre";
	        		break;
	        	case "10":
	        		$mese="Ottobre";
	        		break;
	        	case "11":
	        		$mese="Novembre";
	        		break;
	        	case "12":
	        		$mese="Dicembre";
	        		break;
	        	default:
	        		$mese="Default";			
			}
		if($mese=="Default"){
			$return="".$mese."";
		}else{
			//$explode[1]=substr($explode[1], 0 , strlen($explode[1])-1);
			//$return="".$explode[1]." ".$mese."/";
			$return=$day." , ".$mese;
			
		}

		return ($return);

	}

	function Control_date(&$songID,&$data_check,&$ExceptionID){

		$control=0;
		
		$connectionap=DBap_connection();

		global $db_nameap;

		mysqli_select_db($connectionap,$db_nameap);


		$data="SELECT count(*) AS count FROM songs_exceptions WHERE '$data_check' BETWEEN songs_exceptions.data_in AND songs_exceptions.data_out AND songs_exceptions.ID_song='$songID' AND songs_exceptions.ID!='$ExceptionID'";
	
		$date=$connectionap->query($data);

		$result=$date->fetch_assoc();

		if($result['count']!=0){
			$control=1;
		}

		$connectionap->close();
		
		return $control;

	}

	function toggle_song(&$ID_song,&$status){

		$is_active="";

		if($status==0){
			$status=1;
			$is_active.= "Traccia attiva ";
		}else{
			$status=0;
			$is_active.= "Traccia disattiva ";
		}

		$connectionrd=DBrd_connection();

		global $db_namerd;

		mysqli_select_db($connectionrd,$db_namerd);

		$id_subcat=get_subcat_from_ID_song($ID_song);

		$minmax=get_min_max_playout($id_subcat);

		$daterand=randomize($minmax);


		$update="UPDATE songs SET enabled='$status', date_played='$daterand' WHERE songs.ID='$ID_song'";

		$is_active.=$update;
		return $is_active;


		$stat=$connectionrd->query($update);

		$connectionrd->close();
	}

	function different_convert_date(&$data){

		$year=date("Y");

		if(strlen($data)==3){
			$data.="0".$data;
		}
		
		if($data!=0){
			$day=substr($data,-2);
			$mese=substr($data, -4,-2);

				$data_convert=$year."-".$mese."-".$day;
				

				}else{
			
					$data_convert="0000-00-00";
				}

		return $data_convert;
	}

	function get_subcat_from_ID_song(&$ID_song){

		$connectionrd=DBrd_connection();

		global $db_namerd;

		mysqli_select_db($connectionrd,$db_namerd);

		$query="SELECT songs.id_subcat FROM songs WHERE ID='$ID_song'";

		if($subc=$connectionrd->query($query)){

			while($riga = $subc->fetch_assoc()){

				$result=$riga['id_subcat'];	
			}
		}

		return $result;
		$connectionrd->close();
	}

	function get_min_max_playout(&$id_subcat){

		$connectionrd=DBrd_connection();

		global $db_namerd;

		mysqli_select_db($connectionrd,$db_namerd);

		$query="SELECT songs.ID, MIN(songs.date_played) AS minimo, MAX(songs.date_played) AS massimo FROM songs WHERE id_subcat='$id_subcat' AND enabled=1";

		if($subc=$connectionrd->query($query)){

			while($riga = $subc->fetch_assoc()){

				$result[]=strtotime($riga['minimo']);
				$result[]=strtotime($riga['massimo']);	
			}
		}			
	
		return $result;
		$connectionrd->close();

	} 

	function randomize(&$val){

		$rand = rand($val[0],$val[1]);

		$rand = strftime("%Y-%m-%d %H:%M:%S", $rand);

		return $rand;
	}
?>	