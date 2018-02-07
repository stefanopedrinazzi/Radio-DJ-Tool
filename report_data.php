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

	$connectionrd=DBrd_connection();

	mysqli_select_db($connectionrd,$db_namerd);

	$query="SELECT subcategory.name, subcategory.ID FROM category JOIN subcategory ON subcategory.parentid=category.ID WHERE parentid=1 ORDER BY subcategory.name";

	$stamp_category = "";

	if($category = mysqli_query($connectionrd,$query)){
	
		while($riga = mysqli_fetch_assoc($category)){

    		$stamp_category .= "<option value=\"".$riga['ID']."~".$riga['name']."\">" . $riga['name'] ."</option>" ;

		}  
	}	

	$connectionrd->close();

?>

<!DOCTYPE html>
<html>
	
	<head>

		
		<title><?php echo $translation['label_category_information']?></title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/calendar.min.js"></script>
		<link rel="stylesheet" href="js/calendar.min.css" />
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			
  		$(document).ready(function(){

  			var oggi=new Date();

  			console.log(oggi);

			$("#data_select").val(oggi.toLocaleString());

  			$('#dateinput').calendar({

  		
  			monthFirst: false,

  			text: {
     				days: [ '<?php echo $translation['label_sun']?>',
     						'<?php echo $translation['label_mon']?>',
							'<?php echo $translation['label_tue']?>',
							'<?php echo $translation['label_wed']?>',
							'<?php echo $translation['label_thu']?>',
							'<?php echo $translation['label_fri']?>',
							'<?php echo $translation['label_sat']?>'
						],

     			 	months: ['<?php echo $translation['label_january']?>',
							'<?php echo $translation['label_february']?>',
							'<?php echo $translation['label_march']?>',
							'<?php echo $translation['label_april']?>',
							'<?php echo $translation['label_may']?>',
							'<?php echo $translation['label_june']?>',
							'<?php echo $translation['label_july']?>',
							'<?php echo $translation['label_august']?>',
							'<?php echo $translation['label_september']?>',
							'<?php echo $translation['label_october']?>',
							'<?php echo $translation['label_november']?>',
							'<?php echo $translation['label_december']?>'
      					]
      
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
        					month = '<?php echo $translation['label_january']?>';
        					break;
					    case 1:
					        month = '<?php echo $translation['label_february']?>';
					        break;
					    case 2:
					        month = '<?php echo $translation['label_march']?>';
					        break;
            			case 3:
        					month = '<?php echo $translation['label_april']?>';
        					break;
					    case 4:
					        month = '<?php echo $translation['label_may']?>';
					        break;
					    case 5:
					        month = '<?php echo $translation['label_june']?>';
					        break;
					   	case 6:
        					month = '<?php echo $translation['label_july']?>';
        					break;
					    case 7:
					        month = '<?php echo $translation['label_august']?>';
					        break;
					    case 8:
					        month = '<?php echo $translation['label_september']?>';
					        break;
					    case 9:
        					month = '<?php echo $translation['label_october']?>';
        					break;
					    case 10:
					        month = '<?php echo $translation['label_november']?>';
					        break;
					    case 11:
					        month = '<?php echo $translation['label_december']?>';
					        break;
					    
					}
            		return day + ' , ' + month;
        		}
    		},
  			
		});


	  	$('#cat').on('change',function() {
	 				
 			var category=$("#cat").val();

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
	
	<form name="tabella" action="report.php" method="post">

	<h3 class="ui header" style="margin-top:10px; margin-left:10px">
 		 <i class="folder outline icon"></i>
  			<div class="content">
    			<?php echo $translation['label_category_information']?>
  			</div>
	</h3>

	<table class="ui blue table">

		<tr class="center aligned">
			<td>
				<h4><?php echo $translation['label_select_day']?></h4>
			</td>
			<td>
				<div class="ui calendar" id="dateinput">
   					<div class="ui input left icon">
      					<i class="calendar icon"></i>
      						<input id="data_select" type="text" placeholder="Date" name="data">
    				</div>
  				</div>
			</td>

		</tr>
		<tr class="center aligned">

			<td>
				<h4><?php echo $translation['text_select_category'].":"?></h4>
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

		<button id="annulla" class=" big right floated ui icon labeled button" type="reset" onclick="window.location.href='index.php'" style="margin-top:10px;margin-right: 30px">
  			<i class="reply icon"></i><label><?php echo $translation['label_close']?></label>
		</button>
		<button id="consolida" class="big right floated ui icon labeled primary button" type="submit" disabled="true" style="margin-top:10px">
  			<i class="checkmark icon"></i><label><?php echo $translation['label_continue']?></label>
		</button>

	</form>

		

	</body>
</html>