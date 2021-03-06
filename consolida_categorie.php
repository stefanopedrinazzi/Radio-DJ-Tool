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

	//connessione al database di RadioDJ
	$connectionrd=DBrd_connection();

	mysqli_select_db($connectionrd,$db_namerd);

	//acquisizione del nome e ID delle sottocategorie
	$query="SELECT subcategory.name, subcategory.ID FROM category JOIN subcategory ON subcategory.parentid=category.ID WHERE parentid=1 ORDER BY subcategory.name";

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

		
		<title><?php echo $translation['title_consolidate_category']?></title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			
  		$(document).ready(function(){

  			//azioni legate al cambiamento della select box della categoria
	  		$('#cat').on('change',function() {
	 				
 				var category=$('#cat').val();

 				//attivazione dinamica del tasto consolida
  				if(category=="0"){
	  				$("#consolida").prop("disabled",true);
					$("#category.folder").removeClass("open");
				}else{
					$("#consolida").prop("disabled",false);
					$("#category.folder").addClass("open");	  			
		  		}
	  			});

	  			//azione legata all'evento onclick del tasto annulla
	  			$("#annulla").on('click',function(){
			
				window.location.href = ('index.php');

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
    			<?php echo $translation['title_consolidate_category']?>
  			</div>
	</h3>

	<table class="ui blue table">

		
		<tr class="center aligned">

			<td>
				<h4><?php echo $translation['text_select_category'] ." to consolidate:"?></h4>
			</td>	
			<td>
				<i id="category" class="large folder outline icon"></i>
				<select id="cat" class="big ui selection dropdown" name="categoria">
					<option value="0" selected="selected"><?php echo $translation['label_none']?></option>
					<?php echo $stamp_category; ?>
				</select>
			</td>
		</tr>

	</table>

		<button id="annulla" class=" big right floated ui icon labeled button" type="reset" style="margin-top:10px; margin-right:30px">
  			<i class="reply icon"></i><label><?php echo $translation['label_close']?></label>
		</button>
		<button id="consolida" class="big right floated ui icon labeled primary button" type="submit" disabled="true" style="margin-top:10px;">
  			<i class="checkmark icon"></i><label><?php echo $translation['label_consolidate_button']?></label>
		</button>

		</form>	

	</body>
</html>