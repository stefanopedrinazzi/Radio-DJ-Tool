<?php

	include("FunctionNew.php");
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

  								echo 1;
  								$riga=check_config();
  								$_SESSION['db_namerd']=$riga[0];
  								$_SESSION['hostnamerd']=$riga[1];
  								$_SESSION['usernamerd']=$riga[2];
  								$_SESSION['passwordrd']=$riga[3];
  								$_SESSION['usernameap']=$riga[4];
  								$_SESSION['passwordap']=$riga[5];
  								
  								

  							}
  						
  						?>;

  			if(control==1){

  				$('#eccezioni').prop("disabled",false);
  				$('#validazione').prop("disabled",false);
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