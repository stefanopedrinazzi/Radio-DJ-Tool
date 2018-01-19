<?php

	include("FunctionNew.php");

	$riga=check_config();

	$nomedbrd=$riga[0];

	$hostname=$riga[1];

	$usr=$riga[2];

	$pwd=$riga[3];

	$toolusr=$riga[4];

	$toolpwd=$riga[5];

	$nomedbap='rdj_library_assistant';
	
	$control=0;
	

	
	$order= array("\r\n", "\n", "\r");
	$replace = '';
	
	$nomedbrd=str_replace($order, $replace,$nomedbrd);
	$hostname=str_replace($order, $replace,$hostname);
	$usr=str_replace($order, $replace,$usr);
	$pwd=str_replace($order, $replace,$pwd);
	$toolusr=str_replace($order, $replace,$toolusr);
	$toolpwd=str_replace($order, $replace,$toolpwd);

	
	if(!test_db_connection($nomedbrd,$hostname,$usr,$pwd)){

		$control=0;

	}else{

		if(!test_db_connection($nomedbap,$hostname,$toolusr,$toolpwd)){

			$control=0;
		}else{

			$control=1;
		}

	}

	if($control==1){
		$_SESSION['db_namerd']=$nomedbrd;
		$_SESSION['hostnamerd']=$hostname;
		$_SESSION['usernamerd']=$usr;
		$_SESSION['passwordrd']=$pwd;
		$_SESSION['usernameap']=$toolusr;
		$_SESSION['passwordap']=$toolpwd;
	
	}

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);


	//acquisizione del nome e ID delle sottocategorie
	$query="SELECT subcategory.name, subcategory.ID FROM category JOIN subcategory ON subcategory.parentid=category.ID WHERE parentid=1";

	$stamp_category = "";

	//creazione della select per la sottocategoria
	if($category = mysqli_query($connectionrd,$query)){
	
		while($riga = mysqli_fetch_assoc($category)){

    		$stamp_category .= "<option value=\"".$riga['ID']."~".$riga['name']."\">" . $riga['name'] ."</option>" ;



    		//Numero di canzoni per sottocategoria
    		$num="SELECT songs.ID,count(*) AS NUM FROM songs WHERE songs.id_subcat='$ID_sub'";

			if($number = mysqli_query($connectionrd,$num)){
	
				while($number_song = mysqli_fetch_assoc($number)){

					$num_song=$number_song['NUM'];

					//echo $num_song ."\n";				

					$total[$count][]=$num_song;

				}

			}

		}  
	}	

	$connectionrd->close();
	

	?>

<!DOCTYPE html>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Cruscotto Settimanale</title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			$(document).ready(function(){

  				$('#sottocategoria').on('change',function(){

  					$('#caricamento').addClass("active");

  					for(var y=1;y<=7;y++){
						
						for(var x=0;x<=23;x++){	

						var ID="";	
							
							switch(y){
								case 1:
									name="Lun";
									break;
								case 2:
									name="Mar";
									break;
								case 3:
									name="Mer";
									break;
								case 4:
									name="Gio";
									break;
								case 5:
									name="Ven";
									break;
								case 6:
									name="Sab";
									break;
								case 7:
									name="Dom";
									break;
							}
							
							if(x<10){
									
								ID=name+"0"+x;
									
							}else{

								ID=name+x;
									
							}
							$('#'+ID+'l').text("");
							$('#'+ID).removeClass("green");
							$('#'+ID).removeClass("red");
							$('#'+ID).addClass("grey");

						}
					}
  				
  				var categoria=$('#sottocategoria').val();

  					if(categoria==0){
  						$('#caricamento').removeClass("active");
  						location.reload(true);
  					}
					
					$.ajax({	
						type: "POST",
						url: "calendario_categoria.php",
						data: { categoria: categoria},
						success: function(result){
							var obj=JSON.parse(result);
							console.log(obj);

							var y=1;

							var number_tracks=obj[0];

							var max=number_tracks;

							var previous=0;

							var diff;

							obj.forEach(function(x){

								var control=obj[y+1];

								var exc=obj[y+2];

								if(control!=0){

									number_tracks-=control;

									//if(previous!=exc){

										diff=previous-exc;

										number_tracks=number_tracks+diff;
									//	}


									
									if(number_tracks>0){
										$('#'+obj[y]+'l').text(obj[y+1]+"/"+obj[y+2]);
										$('#'+obj[y]).removeClass("grey");
										$('#'+obj[y]).addClass("green");
									}else{
										$('#'+obj[y]+'l').text(obj[y+1]+"/"+obj[y+2]);
										$('#'+obj[y]).removeClass("grey");
										$('#'+obj[y]).addClass("red");
										number_tracks=max-exc;
									}
								}
								
								previous=exc;

								y+=3;


							});

							$('#caricamento').removeClass("active");
						}

					});	

					

				});


  			});

  		</script>
	</head>
	<body>
		<h2 class="ui blue center aligned header" style="margin-top:20px">Cruscotto settimanale</h2>
		<table class="ui blue table"></table>
		<div style="line-height:0;width:70%;margin:0 auto;">
		
		<strong style="margin-left:30px">Seleziona la sottocategoria:</strong>
		
		<select class="ui focus dropdown" id="sottocategoria" name="sottocategoria">
			<option value="0" selected="selected">Nessuna <?php echo $stamp_category; ?></option>
		</select>
		
		</div>
		
		<div style="line-height:0;width:70%;margin:0 auto;">
		
		<strong style="margin-left:30px">N° Chiamate categoria per ora/ N° eccezioni </strong>
			<a class="ui green circular label"><label></label>1/1</a>
		<strong style="margin-left:30px">Esaurite tracce per categoria</strong>
			<a class="ui red circular label"><label></label>1/1</a>
			<br>
		</div> 
		<div id="caricamento" class="ui inverted dimmer">
    		<div class="ui massive text loader">Caricamento...</div>
  		</div>
		<table class="ui table" style="line-height:0;width:70%;margin:0 auto;">
			<thead>
				<tr>
					<th>
					</th>
					<th class="ui center aligned">
						<h3>Lunedì</h3>
					</th>
					<th class="ui center aligned">
						<h3>Martedì</h3>
					</th>
					<th class="ui center aligned">
						<h3>Mercoledì</h3>
					</th>
					<th class="ui center aligned">
						<h3>Giovedì</h3>
					</th>
					<th class="ui center aligned">
						<h3>Venerdì</h3>
					</th>
					<th class="ui center aligned">
						<h3>Sabato</h3>
					</th>
					<th class="ui center aligned">
						<h3>Domenica</h3>
					</th>
				</tr>
			</thead>
			<tr>
				<td style="padding:2px">
					<strong>00:00 - 00:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun00" ><label id="Lun00l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar00" ><label id="Mar00l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer00" ><label id="Mer00l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio00" ><label id="Gio00l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven00" ><label id="Ven00l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab00" ><label id="Sab00l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom00" ><label id="Dom00l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>01:00 - 01:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun01" ><label id="Lun01l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar01" ><label id="Mar01l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer01" ><label id="Mer01l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio01" ><label id="Gio01l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven01" ><label id="Ven01l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab01" ><label id="Sab01l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom01" ><label id="Dom01l"></label></a>
				</td>
			</tr>	
			<tr>
				<td style="padding:2px">
					<strong>02:00 - 02:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun02" ><label id="Lun02l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar02" ><label id="Mar02l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer02" ><label id="Mer02l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio02" ><label id="Gio02l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven02" ><label id="Ven02l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab02" ><label id="Sab02l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom02" ><label id="Dom02l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>03:00 - 03:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun03" ><label id="Lun03l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar03" ><label id="Mar03l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer03" ><label id="Mer03l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio03" ><label id="Gio03l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven03" ><label id="Ven03l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab03" ><label id="Sab03l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom03" ><label id="Dom03l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>04:00 - 04:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun04" ><label id="Lun04l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar04" ><label id="Mar04l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer04" ><label id="Mer04l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio04" ><label id="Gio04l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven04" ><label id="Ven04l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab04" ><label id="Sab04l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom04" ><label id="Dom04l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>05:00 - 05:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun05" ><label id="Lun05l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar05" ><label id="Mar05l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer05" ><label id="Mer05l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio05" ><label id="Gio05l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven05" ><label id="Ven05l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab05" ><label id="Sab05l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom05" ><label id="Dom05l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>06:00 - 06:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun06" ><label id="Lun06l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar06" ><label id="Mar06l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer06" ><label id="Mer06l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio06" ><label id="Gio06l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven06" ><label id="Ven06l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab06" ><label id="Sab06l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom06" ><label id="Dom06l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>07:00 - 07:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun07" ><label id="Lun07l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar07" ><label id="Mar07l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer07" ><label id="Mer07l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio07" ><label id="Gio07l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven07" ><label id="Ven07l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab07" ><label id="Sab07l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom07" ><label id="Dom07l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>08:00 - 08:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun08" ><label id="Lun08l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar08" ><label id="Mar08l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer08" ><label id="Mer08l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio08" ><label id="Gio08l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven08" ><label id="Ven08l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab08" ><label id="Sab08l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom08" ><label id="Dom08l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>09:00 - 09:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun09" ><label id="Lun09l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar09" ><label id="Mar09l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer09" ><label id="Mer09l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio09" ><label id="Gio09l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven09" ><label id="Ven09l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab09" ><label id="Sab09l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom09" ><label id="Dom09l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>10:00 - 10:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun10" ><label id="Lun10l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar10" ><label id="Mar10l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer10" ><label id="Mer10l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio10" ><label id="Gio10l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven10" ><label id="Ven10l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab10" ><label id="Sab10l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom10" ><label id="Dom10l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>11:00 - 11:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun11" ><label id="Lun11l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar11" ><label id="Mar11l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer11" ><label id="Mer11l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio11" ><label id="Gio11l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven11" ><label id="Ven11l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab11" ><label id="Sab11l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom11" ><label id="Dom11l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>12:00 - 12:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun12" ><label id="Lun12l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar12" ><label id="Mar12l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer12" ><label id="Mer12l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio12" ><label id="Gio12l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven12" ><label id="Ven12l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab12" ><label id="Sab12l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom12" ><label id="Dom12l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>13:00 - 13:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun13" ><label id="Lun13l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar13" ><label id="Mar13l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer13" ><label id="Mer13l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio13" ><label id="Gio13l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven13" ><label id="Ven13l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab13" ><label id="Sab13l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom13" ><label id="Dom13l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>14:00 - 14:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun14" ><label id="Lun14l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar14" ><label id="Mar14l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer14" ><label id="Mer14l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio14" ><label id="Gio14l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven14" ><label id="Ven14l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab14" ><label id="Sab14l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom14" ><label id="Dom14l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>15:00 - 15:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun15" ><label id="Lun15l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar15" ><label id="Mar15l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer15" ><label id="Mer15l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio15" ><label id="Gio15l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven15" ><label id="Ven15l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab15" ><label id="Sab15l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom15" ><label id="Dom15l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>16:00 - 16:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun16" ><label id="Lun16l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar16" ><label id="Mar16l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer16" ><label id="Mer16l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio16" ><label id="Gio16l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven16" ><label id="Ven16l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab16" ><label id="Sab16l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom16" ><label id="Dom16l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>17:00 - 17:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun17" ><label id="Lun17l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar17" ><label id="Mar17l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer17" ><label id="Mer17l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio17" ><label id="Gio17l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven17" ><label id="Ven17l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab17" ><label id="Sab17l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom17" ><label id="Dom17l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>18:00 - 18:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun18" ><label id="Lun18l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar18" ><label id="Mar18l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer18" ><label id="Mer18l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio18" ><label id="Gio18l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven18" ><label id="Ven18l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab18" ><label id="Sab18l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom18" ><label id="Dom18l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>19:00 - 19:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun19" ><label id="Lun19l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar19" ><label id="Mar19l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer19" ><label id="Mer19l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio19" ><label id="Gio19l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven19" ><label id="Ven19l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab19" ><label id="Sab19l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom19" ><label id="Dom19l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>20:00 - 20:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun20" ><label id="Lun20l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar20" ><label id="Mar20l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer20" ><label id="Mer20l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio20" ><label id="Gio20l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven20" ><label id="Ven20l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab20" ><label id="Sab20l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom20" ><label id="Dom20l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>21:00 - 21:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun21" ><label id="Lun21l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar21" ><label id="Mar21l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer21" ><label id="Mer21l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio21" ><label id="Gio21l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven21" ><label id="Ven21l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab21" ><label id="Sab21l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom21" ><label id="Dom21l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>22:00 - 22:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun22" ><label id="Lun22l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar22" ><label id="Mar22l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer22" ><label id="Mer22l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio22" ><label id="Gio22l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven22" ><label id="Ven22l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab22" ><label id="Sab22l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom22" ><label id="Dom22l"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>23:00 - 00:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Lun23" ><label id="Lun23l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mar23" ><label id="Mar23l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Mer23" ><label id="Mer23l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Gio23" ><label id="Gio23l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Ven23" ><label id="Ven23l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Sab23" ><label id="Sab23l"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="Dom23" ><label id="Dom23l"></label></a>
				</td>
			</tr>
		</table>
		<div>
		<button id="annulla" class=" big right floated ui icon labeled button" style="margin-right:30px;margin-top:10px">
	  		<i class="reply icon"></i><label>Chiudi</label>
		</button>
		</div>
	</body>
</html>