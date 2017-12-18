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

		
		<title>Informazioni eccezioni per categoria</title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/calendar.min.js"></script>
		<link rel="stylesheet" href="js/calendar.min.css" />
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			
  		$(document).ready(function(){

  			$('#dateinput').calendar({
  		
  			 monthFirst: false,

  			text: {
     				days: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
     			 	months: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
      
    		},
  			type: 'date',
  			
  			formatter: {
       			 date: function (date, settings) {
            		if (!date) return '';
            		var day = date.getDate() + '';
            		if (day.length < 2) {
                		day = '0' + day;
            		}
            		var month = date.getMonth();
            		
            		switch(month) {
    					case 0:
        					month = "Gennaio";
        					break;
					    case 1:
					        month = "Febbraio";
					        break;
					    case 2:
					        month = "Marzo";
					        break;
            			case 3:
        					month = "Aprile";
        					break;
					    case 4:
					        month = "Maggio";
					        break;
					    case 5:
					        month = "Giugno";
					        break;
					   	case 6:
        					month = "Luglio";
        					break;
					    case 7:
					        month = "Agosto";
					        break;
					    case 8:
					        month = "Settembre";
					        break;
					    case 9:
        					month = "Ottobre";
        					break;
					    case 10:
					        month = "Novembre";
					        break;
					    case 11:
					        month = "Dicembre";
					        break;
					    
					}
            		return day + ' , ' + month;
        		}
    		},
  			
  			endCalendar: $('#rangeend'),
		
			onChange: function (date, text) {
					$('.checkbox').find('.active_date').prop("checked",true);
					$('.checkbox').find('.active_date').attr("checked",true);
     			date_start = text;
    		},


		});

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
	
	<form name="tabella" action="report.php" method="post">

	<h3 class="ui header" style="margin-top:10px; margin-left:10px">
 		 <i class="folder outline icon"></i>
  			<div class="content">
    			Infromazioni eccezioni per categoria
  			</div>
	</h3>

	<table class="ui blue table">

		<tr class="center aligned">
			<td>
				<h4>Seleziona il giorno:</h4>
			</td>
			<td>
				<div class="ui calendar" id="dateinput">
   					<div class="ui input left icon">
      					<i class="calendar icon"></i>
      						<input type="text" placeholder="Date" name="data">
    				</div>
  				</div>
			</td>

		</tr>
		<tr class="center aligned">

			<td>
				<h4>Seleziona la categoria:</h4>
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

		<button id="annulla" class=" big red right floated ui icon labeled button" style="margin-top:10px; margin-right:30px">
  			<i class="window close icon"></i><label>Chiudi</label>
		</button>
		<button id="consolida" class="big right floated ui icon labeled primary button" disabled="true" style="margin-top:10px;">
  			<i class="checkmark icon"></i><label>Continua</label>
		</button>

	</form>	

	</body>
</html>