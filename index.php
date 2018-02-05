<?php

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

	if($language==""){

		include("languages/eng.php");

	}else{

		include("languages/".$language);

	}
	

	//richiamo funzioni per testare le connessioni dei due database 
	if(!test_db_connection($nomedbrd,$hostname,$usr,$pwd)){

		$control=0;

	}else{

		if(!test_db_connection($nomedbap,$hostname,$toolusr,$toolpwd)){

			$control=0;
		}else{

			$control=1;
		}

	}

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	if($control_ID=$connectionrd->query("SELECT ID FROM events_categories WHERE name='RDJLA-events'")){

		//se non esiste la categoria viene creata
		if($control_ID->num_rows == 0){

			$insert="INSERT INTO events_categories (name) values ('RDJLA-events')";

			$inser=$connectionrd->query($insert);
		
		}else{

			$riga = mysqli_fetch_assoc($cat);

					$catID=$riga['ID'];

				
		}
	}


	//scrittura degli eventi nel database se non esistenti per Toogle_Song e Move_Tracks
	if($exist=$connectionrd->query("SELECT ID FROM events WHERE name='Toggle Song'")){

		if($exist->num_rows == 0){

			$events="INSERT INTO events (type,time,name,day,hours,catID,smart,data) VALUES ('2','00:57:00','Toggle Song','&1&2&3&4&5&6&0','&1&2&3&4&5&6&7&8&9&10&11&12&13&14&15&16&17&18&19&20&21&22&23&0','$catID','0','Launch Internet Link|http://localhost/git/toggle_song.php')";

			$event=$connectionrd->query($events);
		}
	}

	if($exist=$connectionrd->query("SELECT ID FROM events WHERE name='Move Tracks'")){

		if($exist->num_rows == 0){

			$events="INSERT INTO events (type,time,name,day,hours,catID,smart,data) VALUES ('2','00:50:00','Move Tracks','&1&2&3&4&5&6&0','&1&2&3&4&5&6&7&8&9&10&11&12&13&14&15&16&17&18&19&20&21&22&23&0','$catID','0','Launch Internet Link|http://localhost/git/move_tracks.php')";

			$event=$connectionrd->query($events);
		}
	}

?>

<!DOCTYPE html>
<html>

	<head>

		<title>Radio DJ Library Assistant</title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
	  	<script type="text/javascript" type="text/css" href="js/buttons.semanticui.min.css"></script>
	  	<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
	  	<script src="js/semantic.min.js"></script>


	  	<script type="text/javascript">
	  	
	  	$(document).ready(function(){

	  		//controllo delle connessione e assegnazione delle variabili di sessione
	  		var control=<?php if (check_config()==1){
  							
  								echo 0;
  						
  							}else{
  									if($control==1){
	  									echo 1;
	  								$riga=check_config();
	  								$_SESSION['db_namerd']=$nomedbrd;
	  								$_SESSION['hostnamerd']=$hostname;
	  								$_SESSION['usernamerd']=$usr;
	  								$_SESSION['passwordrd']=$pwd;
	  								$_SESSION['usernameap']=$toolusr;
	  								$_SESSION['passwordap']=$toolpwd;
	  								$_SESSION['path']=$path;
	  								$_SESSION['language']=$language;

	  								}else{
	  									echo 0;	
	  								}
	  						}
  						?>;

  			//abilitazione dei pulsanti se le connessioni ai database sono valide
  			if(control===1){
  				$('#eccezioni').prop("disabled",false);
  				$('#validazione').prop("disabled",false);
  				$('#informazioni').prop("disabled",false);
  				$('#rotazione').prop("disabled",false);
  				$('#report').prop("disabled",false);
  			}			

  			//eventi legati al click dei pulsanti con reindirizzamento ai link per ogni funzionalità
	  		$('#validazione').on('click',function(){
	
				window.location.href = ("consolida_categorie.php");

			});	
	  		
	  		$('#eccezioni').on('click',function(){
	
				window.location.href = ("tracks_manager.php");

			});	

			$('#impostazioni').on('click',function(){
	
				window.location.href = ("impostazioni.php");

			});

			$('#informazioni').on('click',function(){
	
				window.location.href = ("report_data.php");

			});	

			$('#rotazione').on('click',function(){
	
				window.location.href = ("pianifica_rotazioni.php");

			});	

			$('#report').on('click',function(){
	
				window.location.href = ("cruscotto_settimanale.php");

			});	

	  	});

	  	</script>

	</head>

	<body>
		<h1 class="ui blue center aligned header" style="margin-top:40px">Radio DJ Library Assistant</h1>

		<table id="menutable" class="ui blue large table" style="margin-top:50px">
			<tr>
				<td>
					<h3><?php echo$translation['title_consolidate_categories']?></h3>
					<?php echo $translation['text_consolidate_categories']?>
				</td>
				<td class="center aligned" style="width:40%">
					<button id="validazione" class="fluid big ui blue button" disabled="true">
  					<i class="share icon"></i><label><?php echo $translation['label_consolidate_categories']?></label>
					</button>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo $translation['title_songs_exceptions']?></h3>
					<?php echo $translation['text_songs_exceptions']?>
				</td>
				<td class="center aligned">
					<button id="eccezioni" class="fluid big ui blue button" disabled="true">
  					<i class="share icon"></i><label><?php echo $translation['label_songs_exceptions']?></label>
					</button>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo $translation['title_category_information']?></h3>
					<?php echo $translation['text_category_information']?>
				</td>
				<td class="center aligned">
					<button id="informazioni" class="fluid big ui blue button" disabled="true">
  					<i class="share icon"></i><label><?php echo $translation['label_category_information']?></label>
					</button>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo $translation['title_plan_rotation']?></h3>
					<?php echo $translation['text_plan_rotation']?>
				</td>
				<td class="center aligned">
					<button id="rotazione" class="fluid big ui blue button" disabled="true">
  					<i class="share icon"></i><label><?php echo $translation['label_plan_rotation']?></label>
					</button>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo $translation['title_weekly_report']?></h3>
					<?php echo $translation['text_weekly_report'];?>
				</td>
				<td class="center aligned">
					<button id="report" class="fluid big ui blue button" disabled="true">
  					<i class="share icon"></i><label><?php echo $translation['label_weekly_report']?></label>
					</button>
				</td>
			</tr>
			<tr>
				<td>
					<h3><?php echo $translation['label_settings'];?></h3>
					<?php echo $translation['text_settings'];?>
				</td>
				<td class="center aligned">
					<button id="impostazioni" class="fluid big ui button">
  					<i class="setting icon"></i><label><?php echo $translation['label_settings'];?></label>
					</button>
				</td>	
			</tr>
		</table>

	</body>
</html>