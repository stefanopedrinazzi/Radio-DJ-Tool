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

	$data=$_POST['data'];

	$var= $_POST['categoria'];

	$explode = explode('~', $var);

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

	//echo "a:";
	//print_r($a);
	//echo "<br><br><br>";
	
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
	//echo "b:";
	//print_r($b);
	//echo "<br><br><br><br>";

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

	//echo "c:";	
	//print_r($c);
	//echo"<br><br><br>";


	for($x=0;$x<sizeof($c);$x++){

		$ID_exc=$c[$x][0];

		$ID_song=$c[$x][1];

		$grid="SELECT songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID='$ID_exc'";

		$Grid=$connectionap->query($grid);

		$result=$Grid->fetch_assoc();
	

		for($y=(24*$actual_day);$y<((24*$actual_day)+24);$y++){

				$array_day[]=$result["grid"][$y];
		
		}
		
		
			$matrix[$x][]=$ID_song;

		for($z=$x*24;$z<($x*24)+24;$z++){

		
		$matrix[$x][]=$array_day[$z];

		

		}
	}
	
	//print_r($matrix);

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	$i=0;

	$query="SELECT * FROM songs WHERE id_subcat='$id_subcat'";

	if($song_subcat=$connectionrd->query($query)){

		while($song=$song_subcat->fetch_assoc()){

			$category[]=$song['ID'];
			//$category[$i][]=$song['enabled'];

			//echo $song['ID'].": ".$song['enabled'];

			//echo "<br>";

			$i++;

		}
	}
	//echo "<br><br><br>";
	//print_r($category);

	

	for($x=0;$x<sizeof($category);$x++){

		for($y=0;$y<sizeof($matrix);$y++){

			if($category[$x]==$matrix[$y][0]){

				$res[]=$matrix[$y];
			}

		}
	}
	//echo "<br><br><br>";
	//print_r($res);

	$disabled=0;

	for($y=1;$y<=24;$y++){

		for($x=0;$x<sizeof($matrix);$x++){


			if ($res[$x][$y]==1){

				$disabled+=1;

			}
		}
		$arrayhour[$y-1][0]=$disabled;
		$arrayhour[$y-1][1]=sizeof($category)-$disabled;
		$disabled=0;
	}
	//echo "<br><br><br><br>";
	//print_r($arrayhour);


	?>

<!DOCTYPE html>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Informazioni eccezioni per categoria</title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			$(document).ready(function(){

  				$("#annulla").on('click',function(){
	
					window.location.href = ("report_data.php");
  				});

  			});

  		</script>
	</head>
	<body>
		<h2 class="ui blue center aligned header" style="margin-top:20px">Programmazione Settimanale</h2>
		<table class="ui blue small striped table" style="line-height:0">
			<thead>
				<tr>
					<th>
					</th>
					<th>
						<h3>Lunedì</h3>
					</th>
					<th>
						<h3>Martedì</h3>
					</th>
					<th>
						<h3>Mercoledì</h3>
					</th>
					<th>
						<h3>Giovedì</h3>
					</th>
					<th>
						<h3>Venerdì</h3>
					</th>
					<th>
						<h3>Sabato</h3>
					</th>
					<th>
						<h3>Domenica</h3>
					</th>
				</tr>
			</thead>
			<tr>
				<td style="padding:1px">
					<strong>00:00 - 00:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>01:00 - 01:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>	
			<tr>
				<td style="padding:1px">
					<strong>02:00 - 02:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>03:00 - 03:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>04:00 - 04:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>05:00 - 05:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>06:00 - 06:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>07:00 - 07:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>08:00 - 08:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>09:00 - 09:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>10:00 - 10:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>11:00 - 11:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>12:00 - 12:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>13:00 - 13:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>14:00 - 14:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>15:00 - 15:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>16:00 - 16:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>17:00 - 17:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>18:00 - 18:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>19:00 - 19:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>20:00 - 20:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>21:00 - 21:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>22:00 - 22:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding:1px">
					<strong>23:00 - 00:59</strong>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
				<td style="padding:1px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">All</option>
					</select>
				</td>
			</tr>
		</table>
		<div>
		<button id="annulla" class=" big right floated ui icon labeled button" style="margin-right:30px">
	  		<i class="reply icon"></i><label>Chiudi</label>
		</button>
		</div>
	</body>
</html>