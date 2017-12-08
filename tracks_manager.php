<?php

	session_start();

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

  		var old=<?php  if(isset($_GET["global"])){
							echo "1";
						}else{
							echo "0";
						}
			?>

		var ID_cat;
		var ID_subcat;
		var ID_genre;
		var search;	

  		function refresh(){
  			
			ID_cat=$('#category').val();
			ID_subcat=$('#subcategory').val();
			ID_genre=$('#genre').val();
			search=$('#search').val();
			

			if(ID_cat=="0"){
				$("#cat.folder").removeClass("open");
			}
			if(ID_subcat=="0"){
				$("#sub.folder").removeClass("open");
			}
			if(ID_genre=="0"){
				$("#gen.folder").removeClass("open");
			}

			if ( $.fn.DataTable.isDataTable('#tablesong') ) {
  				$('#tablesong').DataTable().destroy();
				$('#tablesong').off('xhr.dt');
			}

			$('#tablesong')
		        .on('xhr.dt', function ( e, settings, json, xhr ) {
		        	
		            json.data=json.data.map(function(song){
		            	if(song.Eccezioni!==0){
						
							song.Azione='<button class="mini ui icon labeled primary button" name="get_song" value='+song.Azione+'><i class="setting icon"></i>Modifica</button>';
						}else{

							song.Azione='<button class="mini ui icon labeled green button" name="get_song" value='+song.Azione+'><i class="icon plus"></i>Aggiungi</button>';
						}
						return song;
		            })

		            $('#caricamento').removeClass("active");

		        })
		            
		        .dataTable( {
		            serverSide: true,
		            ajax: {
	    				url: 'PrintSong.php',
	    				type: 'POST',
	    				data: { ID_cat: ID_cat, ID_subcat: ID_subcat, ID_genre: ID_genre, Search: search,old: old},

	  				},columns:[
	  					{ data: "Artista" },
	  					{ data: "Titolo" },
	  					{ data: "Eccezioni" },
	  					{ data: "Azione" }
	  				],
	  				searching:false,
	  				language: {
	            		"lengthMenu": "<p style=\"margin-left:10px\"> Elementi per pagina: _MENU_</p>",
	            		"zeroRecords": "Nessuna traccia",
	            		"info": "<p style=\"margin-left:10px\"> elementi da _START_ a _END_ di _TOTAL_ elementi</p>",
	            		"oPaginate": {
	      					"sFirst":      "Prima",
	        				"sLast":       "Ultima",
	        				"sNext":       "Prossima",
	        				"sPrevious":   "Precedente"
	    				},
   			 		}	


				});

		}

		//controllo dell'esistenza di una sessione attiva
		if(old==1){
			var cat=<?php if(isset($_SESSION["ID_cat"])){
							echo json_encode($_SESSION["ID_cat"]);
							unset($_SESSION["ID_cat"]);
						}else{
							echo "0";
						}

				?>;
			var subcat=<?php if(isset($_SESSION["ID_subcat"])){
							echo $_SESSION["ID_subcat"];
							unset($_SESSION["ID_subcat"]);
						}else{
							echo "0";
						}

				?>;
			var genr=<?php if(isset($_SESSION["ID_genre"])){
							echo $_SESSION["ID_genre"];
							unset($_SESSION["ID_genre"]);
						}else{
							echo "0";
						}

				?>;	

			//assegnazione del valori alle select
			$('#category').val(cat);

			ID_cat=$('#category').val();			

			$.ajax({
		        type: 'POST',
		        url: 'Operation.php',
		        dataType: "HTML",
		        data: { ID_cat: ID_cat},
		        success: function(stamp_subcategory) {
	        	
	            	$('#subcategory').html(stamp_subcategory);
	            	$('#subcategory').val(subcat);
	        		}
	        		
	    	});
			
			$('#genre').val(genr);
			refresh();
			old=0;
		}





		//eventi legati agli onchange delle select

		//OnChange di category
		$('#category').on('change',function() {

			$("#cat.folder").addClass("open");

  			$('#caricamento').addClass("active");

			refresh();

  			ID_cat=$('#category').val();

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

  		//OnChange di subcategory
		$('#subcategory').on('change',function() {	
			$("#sub.folder").addClass("open");

  			$('#caricamento').addClass("active");
 
	  			
			refresh();
  		});
  		
		//OnChange di genere
		$('#genre').on('change',function() {
			$("#gen.folder").addClass("open");

  			$('#caricamento').addClass("active");  

			refresh();
  		});

		//OnChange di search
  		$('#search').on('keyup',function() {

  			$('#caricamento').addClass("active");
  			
		  			
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