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

	//acquisizione lingua da utilizzare
	$riga=check_config();

	$language=$riga[7];

	$order= array("\r\n", "\n", "\r");
	$replace = '';

	$language=str_replace($order, $replace,$language);

	include("languages/".$language);

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	$stamp_category = "";

	//creazione select box con il nome e ID delle subcategory
	$category_query="SELECT category.name, category.ID FROM category ORDER BY category.name";

	if($category=$connectionrd->query($category_query)){

		while($cat =$category->fetch_assoc()){
    
    		$stamp_category .= "<option value=\"".$cat['ID']."~".$cat['name']."\">" . $cat['name'] ."</option>" ;
		}
	}

	//creazione select box con il nome e ID dei generi
	$genre_query="SELECT genre.name, genre.ID FROM genre";

	$stamp_genre = "";

	if($genre=$connectionrd->query($genre_query)){

		while($gen=$genre->fetch_assoc()){
    
    		$stamp_genre .= "<option value=\"".$gen['ID']."\">" . $gen['name'] ."</option>" ;

		}

	}

	$connectionrd->close();

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

		            	song.Info='<button class="mini ui icon labeled button" name="report" value='+song.Info+' formaction="report_song.php"><i class="line chart icon"></i>Info</button>';

		            	if(song.Abilitata==1){
						
							song.Abilitata='<a class="ui tiny green empty circular label" style="padding:.3em!important; "></a>';
						
						}else{

							song.Abilitata='<a class="ui tiny red empty circular label" style="padding:.3em!important;"></a>';
						}

		            	if(song.Eccezioni!==0){
						
							song.Azione='<button class="mini ui icon labeled blue button" name="get_song" value='+song.Azione+'><i class="setting icon"></i><?php echo $translation['label_edit']?></button>';
						}else{

							song.Azione='<button class="mini ui icon labeled green button" name="get_song" value='+song.Azione+'><i class="icon plus"></i><?php echo $translation['label_add']?></button>';
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
	  					{ data: "Titolo" },
	  					{ data: "Artista" },
	  					{ data: "Abilitata" },
	  					{ data: "Eccezioni" },
	  					{ data: "Info" },
	  					{ data: "Azione" }
	  				],
	  				columnDefs: [
    					{ orderable: false, targets: 3 },
    					{ orderable: false, targets: 4 },
  					],
	  				searching:false,
	  				language: {
	            		"lengthMenu": "<p style=\"margin-left:10px\"> <?php echo $translation['label_element_page']?></p>",
	            		"zeroRecords": "<?php echo $translation['label_no_records']?>",
	            		"info": "<p style=\"margin-left:10px\"> <?php echo $translation['info_element_page']?></p>",
	            		"oPaginate": {
	        				"sNext":       "<?php echo $translation['label_next_page']?>",
	        				"sPrevious":   "<?php echo $translation['label_previous_page']?>"
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

			if(ID_cat=="0"){
				$("#cat.folder").removeClass("open");
			}else{
				$("#cat.folder").addClass("open");
			}
				

			$.ajax({
		        type: 'POST',
		        url: 'Operation.php',
		        dataType: "HTML",
		        data: { ID_cat: ID_cat},
		        success: function(stamp_subcategory) {
	        	
	            	$('#subcategory').html(stamp_subcategory);
	            	$('#subcategory').val(subcat);
	            	$('#genre').val(genr);

	            	if($('#subcategory').val()=="0"){
						$("#sub.folder").removeClass("open");
					}else{
						$("#sub.folder").addClass("open");
					}
					
					if($('#genre').val(genr)=="0"){
						$("#gen.folder").removeClass("open");
					}else{
						$("#gen.folder").addClass("open");
					}		
	            	
	            	refresh();
	        		}
	    	});
			
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
  	
  		//pulsante per il ritorno al main menu
  		$('#annulla').on('click',function(){
	
		window.location.href = ('index.php');

		});		
  	

  	});	


  	</script>


	<head>
		<meta charset="UTF-8">

		<title>Tracks Manager</title>
	
	</head>
	
	<body>
	
	<form name="tabella" method="post" action="main_eccezioni.php">
	

	<table id="selecttable" class="ui blue large table">


		<tr class="center aligned" style="height:70px">
			<td>
				<i id="cat" class="large folder outline icon"></i><?php echo $translation['label_category'].":"?>

				<select class="ui selection dropdown" id="category" name="categoria"   style="width:200px">
					<option value ="0" selected="selected">All</option>
					<?php echo $stamp_category; ?>
			
			
				</select>
			</div>
			</td>
			<td>
				<i id="sub" class="large folder outline icon"></i><?php echo $translation['label_subcategory'].":"?>

				<select class="ui selection dropdown" id="subcategory" name="sottocategoria"  style="width:200px">
					<option value="0" selected="selected">All</option>
				</select>

			</td>
			<td>
				<i id="gen" class="large folder outline icon"></i><?php echo $translation['label_genre'].":"?>


				<select class="ui selection dropdown" id="genre" name="sottocategoria"  style="width:200px">
					<option value="0" selected="selected">All</option>
					<?php echo $stamp_genre; ?>
				</select>

			</td>
		</tr>
		<tr class="center aligned">

			<td><i class="large search icon"></i><?php echo $translation['label_search'].":"?></td>
			<td colspan="2">
				<div class="ui input focus">
  				<input id="search" style="width:600px"; type="text">
				</div>
			</td>

		</tr>
	
	</table>

  	
  	<div id="caricamento" class="ui inverted dimmer">
    		<div class="ui massive text loader"><?php echo $translation['label_loading']?></div>
  	</div>
	<table id="tablesong"  class="ui striped blue table" style="width:100%">
			
		<thead>
	        
			<tr>
				<th style="width:25%">
					<i class="music icon"></i><?php echo $translation['label_title']?>	
				</th>
				<th style="width:25%">
					<i class="user icon"></i><?php echo $translation['label_artist']?>
				</th>
				<th style="width:15%">
					<?php echo $translation['label_enabled']?>
				</th>
				<th style="width:12%">
					<i class="hashtag icon"></i><?php echo $translation['label_exception']?>
				</th>
				<th style="width:12%">
					<i class="info icon"></i>Info
				</th>
				<th>
					<i class="setting icon"></i><?php echo $translation['label_action']?>
				</th>

			</tr>

		</thead>

		<tbody>

		</tbody>	

	</table>
	
	</form>

		<button id="annulla" class=" big right floated ui icon labeled button" style="margin-top:10px; margin-right:30px">
  		<i class="reply icon"></i><label><?php echo $translation['label_close']?></label>
		</button>

	
	
	</body>
</html>