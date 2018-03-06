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

	//richiamo della funzione per fare il check del file config.txt per la connessione
	$riga=check_config();
	

	$language=$riga[7];

	$order= array("\r\n", "\n", "\r");
	$replace = '';

	$language=str_replace($order, $replace,$language);

	//impostazione della lingua inglese se il file config.txt non è ancora configurato
	if($language==""){

		include("languages/eng.php");

	}else{

		include("languages/".$language);

	}

	//creazione select box con le lingue presenti nella cartella languages
	$stamp_languages="";

	$dir="languages/";

	$files = scandir($dir);

	for($i=0;$i<sizeof($files);$i++){

		if($files[$i]=="." || $files[$i]==".."){

			continue;

		}else{

			$text=substr($files[$i], 0 , -4);

			$stamp_languages .= "<option value=\"".$files[$i]."\">".$text."</option>" ;
		}
	}

	//controllo della presenza degli eventi creati dal tool RDJLA
	$status_toggle=0;

	$status_move=0;

	$status_events=0;

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	//evento Toggle Song
	if($exist=$connectionrd->query("SELECT ID FROM events WHERE name='Toggle Song'")){

		if($exist->num_rows == 1){

			$status_toggle=1;
		
		}else{

			$status_toggle=0;
		}
	}

	//evento Move Tracks
	if($exist=$connectionrd->query("SELECT ID FROM events WHERE name='Move Tracks'")){

		if($exist->num_rows == 1){

			$status_move=1;
		
		}else{

			$status_move=0;
		}
	}

	//ciclo per ricavare il nome degli eventi settimanali
	for($x=1;$x<=7;$x++){

		for($y=0;$y<=23;$y++){

			if($y-1==-1){

				$hours="&23";
			
				if($x-1==0){

					$day="&0";

				}else{

					$d=$x-1;

					$day="&".$d;

				}
			
			}else{

				$h=$y-1;

				$hours="&".$h;

				if ($x==7){
					$day="&0";
				}else{
					$day="&".$x;

				}
			}

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

			
			//controllo degli eventi settimanali per la rotazione
			if($exist=$connectionrd->query("SELECT ID FROM events WHERE day='$day' AND hours='$hours'")){

				if($exist->num_rows == 1){

					$status_events+=1;

				}
			}
		}
	}
	
?>

<!DOCTYPE html>
<html>

<head>

	<title>Impostazioni</title>

	<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
	<script type="text/javascript" type="text/css" href="js/buttons.semanticui.min.css"></script>
  	<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
	<script src="js/semantic.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){

			//valorizzazione delle variabili dal file config.txt
			var nomedb=$('#nomedb').val(<?php echo json_encode($riga[0]) ?>);
			var nomehost=$('#nomehost').val(<?php echo json_encode($riga[1]) ?>);
			var usr=$('#usr').val(<?php echo json_encode($riga[2]) ?>);
			var pwd=$('#pwd').val(<?php echo json_encode($riga[3]) ?>);
			var toolusr=$('#toolusr').val(<?php echo json_encode($riga[4]) ?>);
			var toolpwd=$('#toolpwd').val(<?php echo json_encode($riga[5]) ?>);
			var path=$('#root').val(<?php echo json_encode($riga[6]) ?>);
			var language=<?php echo "\"".$language."\""; ?>;

			$('#files option[value="'+language+'"]').prop("selected", true);

			//controllo esistenza degli eventi sopra elencati 
			var status_move=<?php echo $status_move;?>;

			var status_toggle=<?php echo $status_toggle;?>;

			var status_events=<?php echo $status_events;?>;

			//attivazione report di stato
			if(status_move===1){

				$("#move").removeClass("red");
				$("#move").addClass("green");
			}

			if(status_toggle===1){

				$("#toggle").removeClass("red");
				$("#toggle").addClass("green");
			}

			if(status_events===168){

				$("#events").removeClass("red");
				$("#events").addClass("green");
			}

			//evento on click di conferma
			$("#conferma").on('click',function(){

			nomedb=$('#nomedb').val();
			nomehost=$('#nomehost').val();
			usr=$('#usr').val();
			pwd=$('#pwd').val();
			toolusr=$('#toolusr').val();
			toolpwd=$('#toolpwd').val();
			path=$('#root').val();
			language=$('#files').val();

			//controllo dei valori necessari
			if(nomedb=="" || nomehost=="" || toolusr=="" || usr=="" || path==""){

				alert("<?php echo $translation['alert_login']?>");
			}else{

				$('#caricamento').addClass("active");
			//richiamo del file per testare la connessione con i valori inseriti  
				$.ajax({
				        type: 'POST',
				        url: 'test_connection.php',
				        dataType: "HTML",
				        data: { nomedb: nomedb, nomehost: nomehost, usr: usr, pwd: pwd, toolusr: toolusr,toolpwd: toolpwd},
				        success: function(result) {
							
						$('#caricamento').removeClass("active");

						var res = parseInt(result, 10)

						//check dei risultati restituiti dalla funzione per il test della connessione
						if(res===0){
							alert("<?php echo $translation['alert_login_not_correct']?>");
				
						}else{

							$.ajax({
						        type: 'POST',
						        url: 'Config.php',
						        dataType: "HTML",
						        data: { nomedb: nomedb, nomehost: nomehost,usr: usr,pwd: pwd,toolusr: toolusr,toolpwd: toolpwd, path: path, language:language},
						        success: function(result) {
					        	
					            	alert("<?php echo $translation['alert_login_correct']?>");
					            	window.location.href = ('index.php');
				        		}
				    		});
						}
					}
				});
			}

			});

			//azione per tornare al menù principale
			$('#annulla').on('click',function(){
	
			window.location.href = ('index.php');

			});		
	
		});

	</script>


</head>
<body>

	<div id="caricamento" class="ui inverted dimmer">
    		<div class="ui massive text loader"><?php echo $translation['label_loading']?></div>
  	</div>

	<h3 class="ui header" style="margin-top:10px; margin-left:10px">
 		 <i class="setting icon"></i>
  			<div class="content">
    			<?php echo $translation['label_settings']?>
  			</div>
	</h3>

	<table id="menutable" class="ui blue large table" style="margin-top:10px">
		<tr  class="center aligned">
			<td>
				<h4><?php echo $translation['label_database_name_RDJ']?></h4>
			</td>
			<td>
				<div class="ui input focus large" style="width:400px">
					<input id="nomedb" type="text" name="nomedb">
				</div>
			</td>
		</tr>
		<tr  class="center aligned">
			<td>
				<h4><?php echo $translation['label_host_name']?></h4>
			</td>
			<td>
				<div class="ui input focus large" style="width:400px">
				 	<input id="nomehost" type="text" name="nomehost">
				</div>	
			</td>
		</tr>
		<tr  class="center aligned">
			<td>
				<h4><?php echo $translation['label_RDJ_user_name']?></h4>
			</td>
			<td>
				<div class="ui input focus large" style="width:400px">
					<input id="usr" type="text" name="usr">
				</div>
			</td>
		</tr>
		<tr  class="center aligned">
			<td>
				<h4><?php echo $translation['label_RDJ_password']?></h4>
			</td>
			<td>
				<div class="ui input focus large" style="width:400px">
					<input id="pwd" type="text" name="pwd">
				</div>
			</td>
		</tr>
		<tr  class="center aligned">
			<td>
				<h4>Tool DB user name:</h4>
			</td>
			<td>
				<div class="ui input focus large" style="width:400px">
					<input id="toolusr" type="text" name="toolusr">
				</div>
			</td>
		</tr>
		<tr  class="center aligned">
			<td>
				<h4>Tool DB  Password:</h4>
			</td>
			<td>
				<div class="ui input focus large" style="width:400px">
					<input id="toolpwd" type="text" name="toolpwd">
				</div>
				</td>
		</tr>
		<tr  class="center aligned">
			<td>
				<h4>Root Directory:</h4>
				
			</td>
			<td>
				<div class="ui input focus large" style="width:400px">
					<input id="root" type="text" name="rootdirectory"/>					
				</div>
			</td>
		</tr>
		<tr  class="center aligned">
			<td>
				<h4><?php echo $translation['label_language']?></h4>
			</td>
			<td>
				<div>
					<select id="files" class="big ui selection dropdown" name="files" style="width:400px;height:45px;">
						<?php echo $stamp_languages; ?>
					</select>
				</div>
			</td>
		</tr>
		
	</table>

	<div>
		<button id="annulla" class=" big red right floated ui icon labeled button" style="margin-right:30px">
  			<i class="window close icon"></i><label><?php echo $translation['label_close']?></label>
		</button>
		<button id="conferma" class=" big right floated ui icon labeled primary button">
  			<i class="checkmark icon"></i><label><?php echo $translation['label_save']?></label>
		</button>
	</div>

	<div style="margin-left:10px; font-size:13px">
		
		<?php echo $translation['label_settings_toggle'];?>
				
		<a class="ui red empty circular label" id="toggle" style="padding:.3em!important; vertical-align:0px"></a>
				
		<?php echo $translation['label_settings_move'];?>
				
		<a class="ui red empty circular label" id="move" style="padding:.3em!important; vertical-align:0px"></a>
				
		<?php echo $translation['label_settings_events'];?>
				
		<a class="ui red empty circular label" id="events" style="padding:.3em!important; vertical-align:0px"></a>
				
	</div>


</body>
</html>