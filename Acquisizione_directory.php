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
	
	//recupero category
	$var = $_POST['categoria'];

	//root path della cartella per staticizzare la musica
	$root_path = $_SESSION['path'];


	if(substr($root_path, -1)!="\\"){
	
		$root_path.="\\";
	
	}

	error_reporting(E_ERROR);
	
	//controllo dell'esistenza della cartella root o creazione
	if (is_dir($root_path)) {
  		
	} else {
		
		mkdir($root_path);
  		
	}

	//calcolo dell'indirizzo di root aggiungendo il nome della categoria
	$explode = explode('~', $var);

	$category=$explode[1];

	$category_ID=$explode[0];

	$extendedpath=$root_path.$category;

	//se non esiste l'extended path crealo
	if (is_dir($extendedpath)) {

	} else {
		mkdir($extendedpath);

	}

	//compilazione del database con il nuovo path ed il vecchio
	$response=Modifica_tabella_appoggio($root_path,$category,$category_ID);

	//lettura del database e spostamento file
	$response.=Sposta_file();

	error_reporting(E_ALL);	

	

?>

<!DOCTYPE html>
	<!DOCTYPE html>
	<html>
	<head>
		<title><?php echo $translation['title_validation']?></title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			$(document).ready(function(){
  				$("#annulla").on('click',function(){
	
					window.location.href = ("consolida_categorie.php");
  				});
  			});

  		</script>
	</head>
	<body>
		<table class="ui blue table">
			<tr>
				<td>
					<h3><?php echo $translation['label_validation_ok'];?></h3>
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
    									<?php echo $translation['label_operation']?>
  									</div>
							</h4>
    							<textarea rows="40"><?php echo $response ?></textarea>
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