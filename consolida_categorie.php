<?php

	include("FunctionNew.php");

	//connessione al database di RadioDJ
	$connectionrd=DBrd_connection();

	mysqli_select_db($connectionrd,$db_namerd);

	//acquisizione del nome e ID delle sottocategorie
	$query="SELECT subcategory.name, subcategory.ID FROM category JOIN subcategory ON subcategory.parentid=category.ID WHERE parentid=1";

	$stamp_category = "";

	//creazione della select per la sottocategoria
	if($category = mysqli_query($connectionrd,$query)){
	
		while($riga = mysqli_fetch_assoc($category)){

    		$stamp_category .= "<option value=\"".$riga['ID']."~".$riga['name']."\">" . $riga['name'] ."</option>" ;

		}  
	}	


?>

<!DOCTYPE html>
<html>
	
	<head>

		
		<title>Consolida Categorie</title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			
  		$(document).ready(function(){

	  		$('#cat').on('change',function() {
	 				
 				var category=$('#cat').val();

  			if(category=="0"){
	  			$("#consolida").prop("disabled",true);
				$("#category.folder").removeClass("open");
			}else{
				$("#consolida").prop("disabled",false);
				$("#category.folder").addClass("open");	  			
		  	}
	  		});

	  		$("#annulla").on('click',function(){
		
			window.location.href = ('main_menu.php');

			});	

		});	

  		</script>
		<meta charset="UTF-8">
	
	</head>
	
	<body>
	
	<form name="tabella" action="Acquisizione_directory.php" method="post">

	<h3 class="ui header" style="margin-top:10px; margin-left:10px">
 		 <i class="folder outline icon"></i>
  			<div class="content">
    			Consolida le categorie
  			</div>
	</h3>

	<table class="ui blue table">

		
		<tr class="center aligned">

			<td>
				<h4>seleziona la categoria da consolidare:</h4>
			</td>	
			<td>
				<i id="category" class="large folder outline icon"></i>
				<select id="cat" class="big ui selection dropdown" name="categoria">
					<option value="0" selected="selected">Nessuna</option>
					<?php echo $stamp_category; ?>
				</select>
			</td>
		</tr>

	</table>

		<button id="annulla" class=" big red right floated ui icon labeled button" type="reset" style="margin-top:10px; margin-right:30px">
  			<i class="window close icon"></i><label>Chiudi</label>
		</button>
		<button id="consolida" class="big right floated ui icon labeled primary button" type="submit" disabled="true" style="margin-top:10px;">
  			<i class="checkmark icon"></i><label>Consolida</label>
		</button>

		</form>	

	</body>
</html>