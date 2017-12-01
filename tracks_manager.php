<?php


	include("FunctionNew.php");

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	$stamp_category = "";

	$category_query="SELECT category.name, category.ID FROM category";

	if($category=$connectionrd->query($category_query)){

		while($cat =$category->fetch_assoc()){
    
    		$stamp_category .= "<option value=\"".$cat['ID']."~".$cat['name']."\">" . $cat['name'] ."</option>" ;
		}
	}


	$genre_query="SELECT genre.name, genre.ID FROM genre";

	$stamp_genre = "";

	if($genre=$connectionrd->query($genre_query)){

		while($gen=$genre->fetch_assoc()){
    
    		$stamp_genre .= "<option value=\"".$gen['ID']."\">" . $gen['name'] ."</option>" ;

		}

	}

prova prova prova

?>
<!DOCTYPE html>
<html>

	<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
  	<script type="text/javascript"  charset="utf8" src="js/datatables.min.js"></script>
  	<script type="text/javascript" type="text/css" href="js/buttons.semanticui.min.css"></script>
  	<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  	<script src="js/semantic.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="js/dataTables.semanticui.min.css">

	
  
  	<script type="text/javascript">
  	
  	$(document).ready(function(){


  		function refresh(){

  			
  			$('#caricamento').addClass("active");
  			

  			var response="";
			
			var ID_cat=$('#category').val();
			var ID_subcat=$('#subcategory').val();
			var ID_genre=$('#genre').val();
			var search=$('#search').val();

			if(ID_cat=="0"){
				$("#cat.folder").removeClass("open");
			}
			if(ID_subcat=="0"){
				$("#sub.folder").removeClass("open");
			}
			if(ID_genre=="0"){
				$("#gen.folder").removeClass("open");
			}

  			$.ajax({
	        type: 'POST',
	        url: 'PrintSong.php',
	        data: { ID_cat: ID_cat, ID_subcat: ID_subcat, ID_genre: ID_genre, Search: search},
	        	success: function(stamp_song) {
	           	$('#caricamento').removeClass("active");
	           	
	            $('#tablesong').html(stamp_song);
	            	
	    			table = $('#tablesong').DataTable( {
   			 			paging: false
					} );
 
					table.destroy();

					$.fn.dataTable.ext.errMode = 'none';

					table = $('#tablesong').DataTable( {
						
						language: {
	            			"lengthMenu": "<p style=\"margin-left:10px\"> Elementi per pagina: _MENU_</p>",
	            			"zeroRecords": "Nessuna traccia",
	            			"info": "<p style=\"margin-left:10px\"> Pagina _PAGE_ di _PAGES_</p>",
	            			"oPaginate": {
	      						"sFirst":      "Prima",
	        					"sLast":       "Ultima",
	        					"sNext":       "Prossima",
	        					"sPrevious":   "Precedente"
	    					},
   			 			},
    					searching: false
					} );
	    		}
	    	});	
		
		}

		$('#category').on('change',function() {

			$("#cat.folder").addClass("open");

			refresh();

  			var cat='<?php echo $stamp_category; ?>';

  			var ID_cat=$('#category').val();

  			$.ajax({
		        type: 'POST',
		        url: 'Operation.php',
		        dataType: "HTML",
		        data: { ID_cat: ID_cat},
		        success: function(stamp_subcategory) {
	        	
	            	$('#subcategory').html(stamp_subcategory);
	        		}
	    	});
  			
  		});

  		
		$('#subcategory').on('change',function() {	
			$("#sub.folder").addClass("open");	  			
			refresh();
  		});
  		

		$('#genre').on('change',function() {
			$("#gen.folder").addClass("open");	  			
			refresh();
  		});

  		$('#search').on('keyup',function() {
  					  			
			refresh();
  		});
  		
  	});	

  	</script>


	<head>
		<meta charset="UTF-8">

		<title>Radio DJ</title>
	
	</head>
	
	<body>
	
	<form name="tabella" method="post" action="main_eccezioni.php">
	

	<table id="selecttable" class="ui blue large table">


		<tr class="center aligned" style="height:70px">
			<td>
				<i id="cat" class="large folder outline icon"></i>Categoria:

				<select class="ui selection dropdown" id="category" name="categoria"   style="width:200px">
					<option value ="0" selected="selected">All</option>
					<?php echo $stamp_category; ?>
			
			
				</select>
			</div>
			</td>
			<td>
				<i id="sub" class="large folder outline icon"></i>Sottocategoria:

				<select class="ui selection dropdown" id="subcategory" name="sottocategoria"  style="width:200px">
					<option value="0" selected="selected">All</option>
				</select>

			</td>
			<td>
				<i id="gen" class="large folder outline icon"></i>Genere:


				<select class="ui selection dropdown" id="genre" name="sottocategoria"  style="width:200px">
					<option value="0" selected="selected">All</option>
					<?php echo $stamp_genre; ?>
				</select>

			</td>
		</tr>
		<tr class="center aligned">

			<td><i class="large search icon"></i>Ricerca:</td>
			<td colspan="2">
				<div class="ui input focus">
  				<input id="search" style="width:600px"; type="text">
				</div>
			</td>

		</tr>
	
	</table>

  	
  	<div id="caricamento" class="ui inverted dimmer">
    		<div class="ui massive text loader">Caricamento...</div>
  		</div>
	<table id="tablesong"  class="ui striped blue large table">
		
		<thead>
        
			<tr>
			<th>
				<i class="music icon"></i>Titolo	
			</th>
			<th>
				<i class="user icon"></i>Artista
			</th>
			<th>
				<i class="hashtag icon"></i>Eccezioni
			</th>
			<th>
				<i class="setting icon"></i>Azione
			</th>

		</tr>
		</thead>
		<tbody>
		</tbody>	
	</table>

	
		
	
	</body>
</html>