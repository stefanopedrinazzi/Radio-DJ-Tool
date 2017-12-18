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
	  								}else{
	  									echo 0;	
	  								}
	  						}
  						?>;

  			if(control===1){
  				$('#eccezioni').prop("disabled",false);
  				$('#validazione').prop("disabled",false);
  				$('#informazioni').prop("disabled",false);
  			}			

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

			$('#chiudi').on('click',function(){

				$.ajax({
					url: 'clear_all.php',
					success: function(result) {
						
					}
				});

			});


	  	});

	  	</script>

	</head>

	<body>
		<h1 class="ui blue center aligned header" style="margin-top:40px">Radio DJ Library Assistant</h1>

		<table id="menutable" class="ui blue large table" style="margin-top:50px">
			<tr>
				<td>
					<h3>Tool per consolidare le categorie</h3>
					permette di aggiornare il path salvato nel database e spostare le tracce audio nella cartella indicata dal nuovo path. 
				</td>
				<td class="center aligned" style="width:40%">
					<button id="validazione" class="fluid big ui blue button" disabled="true">
  					<i class="share icon"></i><label>Consolida Categorie</label>
					</button>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Tool per la creazione e la modifica delle eccezioni</h3>
					permette di definire e modificare delle eccezioni orarie,settimanali e mensili per ogni traccia audio.
				</td>
				<td class="center aligned">
					<button id="eccezioni" class="fluid big ui blue button" disabled="true">
  					<i class="share icon"></i><label>Eccezioni Traccia</label>
					</button>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Tool per ottenere informazioni sulle eccezioni per categoria</h3>
					permette di ottenere il numero di eccezioni per categoria e giorno.
				</td>
				<td class="center aligned">
					<button id="informazioni" class="fluid big ui blue button" disabled="true">
  					<i class="share icon"></i><label>Informazioni per Categoria</label>
					</button>
				</td>
			</tr>
			<tr>
				<td>
					<h3>Impostazioni</h3>
					Inserisci le impostazioni di base per la configurazione del database e del path per le directory.
				</td>
				<td class="center aligned">
					<button id="impostazioni" class="fluid big ui button">
  					<i class="setting icon"></i><label>Impostazioni</label>
					</button>
				</td>	
			</tr>
		</table>

		<button id="chiudi" class=" big red right floated ui icon labeled button" style="margin-right:30px">
  		<i class="window close icon"></i><label>Esci</label>
		</button>
	</body>
</html>