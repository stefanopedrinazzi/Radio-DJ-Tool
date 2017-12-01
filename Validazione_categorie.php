<?php

	include("FunctionNew.php");

	$connectionrd=DBrd_connection();

	mysqli_select_db($connectionrd,$db_namerd);

	$query="SELECT subcategory.name, subcategory.ID FROM category JOIN subcategory ON subcategory.parentid=category.ID WHERE parentid=1";

	$stamp_category = "";

	if($category = mysqli_query($connectionrd,$query)){
	
		while($riga = mysqli_fetch_assoc($category)){

    		$stamp_category .= "<option value=\"".$riga['ID']."~".$riga['name']."\">" . $riga['name'] ."</option>" ;

		}  
	}	


?>

<!DOCTYPE html>
<html>
	
	<head>

		
		<title>Validazione Categorie</title>

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
				<h4>seleziona la categoria da validare:</h4>
			</td>	
			<td>
				<i id="category" class="large folder outline icon"></i>
				<select id="cat" class="big ui selection dropdown" name="categoria">
					<option value="0" selected="selected">Nessuna</option>
					<?php echo $stamp_category; ?>
				</select>
			</td>
		</tr>

		<tr class="center aligned">
			<td colspan="2">
				<button id="consolida" class="medium ui icon labeled primary button" disabled="true">
  				<i class="checkmark icon"></i><label>Consolida</label>
				</button>
			</td>
		</tr>
	
	</table>

	</body>
</html>