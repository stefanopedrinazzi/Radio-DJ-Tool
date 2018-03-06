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

	//acquisizione dei dati per la connessione ai database
	$riga=check_config();

	$nomedbrd=$riga[0];

	$hostname=$riga[1];

	$usr=$riga[2];

	$pwd=$riga[3];

	$toolusr=$riga[4];

	$toolpwd=$riga[5];

	$path=$riga[6];

	$language=$riga[7];

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
	$path=str_replace($order, $replace,$path);
	$language=str_replace($order, $replace,$language);

	include("languages/".$language);
	
	//test di connessione ai due database
	if(!test_db_connection($nomedbrd,$hostname,$usr,$pwd)){

		$control=0;

	}else{

		if(!test_db_connection($nomedbap,$hostname,$toolusr,$toolpwd)){

			$control=0;
		}else{

			$control=1;
		}
	}

	//assegnazione delle variabili di sessione per la connessione ai database
	if($control==1){
		$_SESSION['db_namerd']=$nomedbrd;
		$_SESSION['hostnamerd']=$hostname;
		$_SESSION['usernamerd']=$usr;
		$_SESSION['passwordrd']=$pwd;
		$_SESSION['usernameap']=$toolusr;
		$_SESSION['passwordap']=$toolpwd;
		$_SESSION['path']=$path;
		$_SESSION['language']=$language;
	}

	//connessione al database di appoggio
	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);

	//assegnazione variabili ricavate tramite POST
	$data=$_POST['data'];

	$var= $_POST['categoria'];

	$explode = explode('~', $var);

	$categoria=$explode[1];

	$id_subcat=$explode[0];

	//conversione della data corrente mediante funzione
	$now=Convert_date($data);

	$date_for_query=$now;

	if($now==""){

	$actual_day = date ('N', time())-1;

	$now = date ('md',time());

   		if(substr($now,-4,1)==0){
    
   			$date_for_query=substr($now,-3, 3);
  		}

	}else{

	$mytime=different_convert_date($now).", 00:00:00";

	$mytime=strtotime($mytime);

	$actual_day = date('N', $mytime)-1;

	}
	
	//Array ID e ID_song di tutte le eccezioni con range di data che comprende la data e ora attuale
	$exception="SELECT songs_exceptions.ID,songs_exceptions.ID_song FROM songs_exceptions WHERE ('$date_for_query' BETWEEN songs_exceptions.data_in AND songs_exceptions.data_out) AND data_in!='0'";

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

	//creazione della matrice contenente ID delle canzoni e le informazioni delle eccezioni attive per la data selezionata
	for($x=0;$x<sizeof($c);$x++){

		$ID_exc=$c[$x][0];

		$ID_song=$c[$x][1];

		$grid="SELECT songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID='$ID_exc'";

		$Grid=$connectionap->query($grid);

		$result=$Grid->fetch_assoc();
	
		//creazione array contenente i valori delle eccezioni per ogni traccia
		for($y=(24*$actual_day);$y<((24*$actual_day)+24);$y++){

				$array_day[]=$result["grid"][$y];
		
		}

		$matrix[$x][]=$ID_song;

		for($z=$x*24;$z<($x*24)+24;$z++){
		
		$matrix[$x][]=$array_day[$z];

		}
	}
	
	//connessione al database di RadioDJ
	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	$i=0;

	//creazione array contenente l'ID delle canzoni presente nella categoria selezionata 
	$query="SELECT * FROM songs WHERE id_subcat='$id_subcat'";

	if($song_subcat=$connectionrd->query($query)){

		while($song=$song_subcat->fetch_assoc()){

			$category[]=$song['ID'];

			$i++;
		}
	}
	
	//creazione dell'array confrontanto gli ID della cateogria e gli ID delle eccezioni per la data selezionata
	for($x=0;$x<sizeof($category);$x++){

		for($y=0;$y<sizeof($matrix);$y++){

			if($category[$x]==$matrix[$y][0]){

				$res[]=$matrix[$y];
			}
		}
	}
	
	//creazione array contenente le tracce attive e disattive per ogni ora e giorno delle tracce della categoria
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
	
	$active="[";
	$notactive="[";

	//creazione variabili da utilizzare nel grafico
	for($x=0;$x<24;$x++){

		if($x==23){
		$active.="".$arrayhour[$x][1]." ";	
		}else{
		$active.="".$arrayhour[$x][1].", ";
		}
	}


	for($x=0;$x<24;$x++){

		if($x==23){
		$notactive.="".$arrayhour[$x][0]." ";	
		}else{
		$notactive.="".$arrayhour[$x][0].", ";
		}
	}
	
	$active.="],";
	$notactive.="],";

	$connectionrd->close();
	$connectionap->close();

?>


<!DOCTYPE html>
	<html>
	<head>
		<title><?php echo $translation['label_category_information']?></title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>
  		<script src="js/Chart.js"></script>

  		<script type="text/javascript">
  			$(document).ready(function(){

  				$("#annulla").on('click',function(){
	
					window.location.href = ("report_data.php");
  				});
  			});

  		</script>
	</head>
	<body>
		
		<table class="ui blue table">
			<tr>
				<td>
					<h3><i class="bar chart icon"></i><?php if($data==""){
						echo $translation['info_result_category']." ".$categoria." , ".$translation['info_result_today']; 
					}else{
						echo $translation['info_result_category']." ".$categoria." , ".$translation['info_result_day']." ".$data;
					}
					?></h3>
				</td>
			</tr>
			<tr>
			</tr>
			<tr>
				<td>
					<div class="ui form">
  						<div class="field">
    						<h4 class="ui header" style="margin-top:10px">
 		 						<i class="info icon"></i>
  									<div class="content">
    									<?php echo $translation['label_hour_exception']?>
  									</div>
							</h4>
    							<div id="container" style="width: 75%;margin:0 auto;">
        							<canvas id="canvas"></canvas>
    							</div>
   						
					    <script>

						    var densityCanvas = document.getElementById("canvas");

						    Chart.defaults.global.defaultFontFamily = "Lato";
						    Chart.defaults.global.defaultFontSize = 15;

						    var activeData = {
							  	label: <?php echo "\"".$translation['label_active']."\"";?>,
								data:<?php echo $active; ?>
								backgroundColor: '#21ba45',
								borderWidth: 0
							};

							var notactiveData = {
								label: <?php echo "\"".$translation['label_inactive']."\"";?>,
								data: <?php echo $notactive; ?>
								backgroundColor: '#db2828',
								borderWidth: 0
								
							};

							var songData = {
							  labels: ["00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23" ],
							  datasets: [activeData, notactiveData]
							};

							var chartOptions = {
							  scales: {
							    xAxes: [{
							      barPercentage: 1,
							      categoryPercentage: 0.6
							    }],
							     yAxes: [{
		           						display: true,
		            						ticks: {
							                suggestedMin: 0,   
								                callback: function(value, index, values) {
								        			if (Math.floor(value) === value) {
								            			return value;
								        			}
							    			}
						            	}
	        					}]
							  }
							};

							var barChart = new Chart(densityCanvas, {
							  type: 'bar',
							  data: songData,
							  options: chartOptions
							});

					    </script>
    					</div>
  					</div>
				</td>	
			</tr>
		</table>
		<div>
		<button id="annulla" class=" big right floated ui icon labeled button" style="margin-right:30px">
	  		<i class="reply icon"></i><label><?php echo $translation['label_close']?></label>
		</button>
	</div>
	</body>
</html>