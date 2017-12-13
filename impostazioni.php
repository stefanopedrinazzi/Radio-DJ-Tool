<?php
	
	include("FunctionNew.php");

	//print_r(check_config());
	if(check_config()==1){

		$riga[0]="";
		$riga[1]="";
		$riga[2]="";
		$riga[3]="";
		$riga[4]="";
		$riga[5]="";
	}else{

		$riga=check_config();
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

			var nomedb=$('#nomedb').val(<?php echo json_encode($riga[0]) ?>);
			var nomehost=$('#nomehost').val(<?php echo json_encode($riga[1]) ?>);
			var usr=$('#usr').val(<?php echo json_encode($riga[2]) ?>);
			var pwd=$('#pwd').val(<?php echo json_encode($riga[3]) ?>);
			var toolusr=$('#toolusr').val(<?php echo json_encode($riga[4]) ?>);
			var toolpwd=$('#toolpwd').val(<?php echo json_encode($riga[5]) ?>);

	/*		var nomedb=$('#nomedb').val();
			var nomehost=$('#nomehost').val();
			var usr=$('#usr').val();
			var pwd=$('#pwd').val();*/


			$("#conferma").on('click',function(){

			nomedb=$('#nomedb').val();
			nomehost=$('#nomehost').val();
			usr=$('#usr').val();
			pwd=$('#pwd').val();
			toolusr=$('#toolusr').val();
			toolpwd=$('#toolpwd').val();

			if(nomedb=="" || nomehost=="" || toolusr=="" || usr==""){

				alert("Devi inserire nome del database, host e Username.");
			}else{

				$.ajax({
				        type: 'POST',
				        url: 'test_connection.php',
				        dataType: "HTML",
				        data: { nomedb: nomedb, nomehost: nomehost, usr: usr, pwd: pwd, toolusr: toolusr,toolpwd: toolpwd},
				        success: function(result) {

						//alert(result);								
						var res = parseInt(result, 10)

						if(res===0){
							alert("I parametri inseriti non sono corretti");
				
						}else{

							$.ajax({
						        type: 'POST',
						        url: 'Config.php',
						        dataType: "HTML",
						        data: { nomedb: nomedb, nomehost: nomehost,usr: usr,pwd: pwd,toolusr: toolusr,toolpwd: toolpwd},
						        success: function(result) {
					        	
					            	alert("Operazione andata a buon fine.");
					            	window.location.href = ('main_menu.php');
				        		}
				    		});
						}
					}
				});
			}

			});

			$('#annulla').on('click',function(){
	
			window.location.href = ('main_menu.php');

			});		
	
		});

	</script>

</head>
<body>

	<h3 class="ui center aligned header" style="margin-top:40px">Impostazioni</h3>


	<table id="menutable" class="ui blue large table" style="margin-top:40px">
			<tr  class="center aligned">
				<td>
					<h4>Nome del Database di RadioDJ:</h4>
				</td>
				<td>
					<div class="ui input focus large" style="width:400px">
						<input id="nomedb" type="text" name="nomedb">
					</div>
				</td>
			</tr>
			<tr  class="center aligned">
				<td>
					<h4>Nome Host RadioDJ (e Tool):</h4>
				</td>
				<td>
					<div class="ui input focus large" style="width:400px">
					 	<input id="nomehost" type="text" name="nomehost">
					</div>	
				</td>
			</tr>
			<tr  class="center aligned">
				<td>
					<h4>Username RadioDJ:</h4>
				</td>
				<td>
					<div class="ui input focus large" style="width:400px">
						<input id="usr" type="text" name="usr">
					</div>
				</td>
			</tr>
			<tr  class="center aligned">
				<td>
					<h4>Password RadioDJ:</h4>
				</td>
				<td>
					<div class="ui input focus large" style="width:400px">
						<input id="pwd" type="text" name="pwd">
					</div>
				</td>
			</tr>
			<tr  class="center aligned">
				<td>
					<h4>Username Tool:</h4>
				</td>
				<td>
					<div class="ui input focus large" style="width:400px">
						<input id="toolusr" type="text" name="toolusr">
					</div>
				</td>
			</tr>
			<tr  class="center aligned">
				<td>
					<h4>Password Tool:</h4>
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
					(per consolidare le categorie)
				</td>
				<td>
					
						<input id="root" type="file" name="rootdirectory"  directory mozdirectory webkitdirectory/>
					
				</td>
			</tr>
		
		</table>

		<button id="annulla" class=" big red right floated ui icon labeled button" style="margin-right:30px">
  		<i class="window close icon"></i><label>Chiudi</label>
		</button>
		<button id="conferma" class=" big right floated ui icon labeled primary button">
  		<i class="checkmark icon"></i><label>Aggiungi</label>
		</button>


</body>
</html>