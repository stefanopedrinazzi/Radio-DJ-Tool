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

	$nomedbrd=$riga[0];

	$hostname=$riga[1];

	$usr=$riga[2];

	$pwd=$riga[3];

	$toolusr=$riga[4];

	$toolpwd=$riga[5];

	$path=$riga[6];

	$language=$riga[7];

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
	$path=str_replace($order, $replace,$path);
	$language=str_replace($order, $replace,$language);

	include("languages/".$language);
	
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
		$_SESSION['passwordrd']=$pwdl;
		$_SESSION['usernameap']=$toolusr;
		$_SESSION['passwordap']=$toolpwd;
		$_SESSION['path']=$path;
		$_SESSION['language']=$language;
	
	}

	write_events();

	$connectionrd=DBrd_connection();

	global $db_namerd;

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

	$connectionrd->close();
	

	?>


<!DOCTYPE html>
	<html>
	<head>
		<title><?php echo $translation['title_weekly_statement']?></title>

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
									name='<?php echo $translation['label_mon']?>';
									break;
								case 2:
									name='<?php echo $translation['label_tue']?>';
									break;
								case 3:
									name='<?php echo $translation['label_wed']?>';
									break;
								case 4:
									name='<?php echo $translation['label_thu']?>';
									break;
								case 5:
									name='<?php echo $translation['label_fri']?>';
									break;
								case 6:
									name='<?php echo $translation['label_sat']?>';
									break;
								case 7:
									name='<?php echo $translation['label_sun']?>';
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
			  			$("#consolida").prop("disabled",true);
						$("#category.folder").removeClass("open");
					}else{
						$("#consolida").prop("disabledl",false);
						$("#category.folder").addClass("open");	  			
				  	}

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
							
							var y=1;

							var count=0;

							var number_tracks=obj[0];

							number_tracks=parseInt(number_tracks);

							$('#num_song').text(number_tracks);

							var previous=0;

							var diff;

							obj.forEach(function(x){

								var control=obj[y+1];

							control=parseInt(control);

								var exc=obj[y+2];

								exc=parseInt(exc);

								if(control!=0){

									count+=control;

									//number_tracks-=control;

									if(previous!=exc){

										diff=previous-exc;

										number_tracks=number_tracks+diff;
										}


									
									if(count<number_tracks){
										$('#'+obj[y]+'l').text(obj[y+1]+"/"+obj[y+2]);
										$('#'+obj[y]).removeClass("grey");
										$('#'+obj[y]).addClass("green");
									}else{
										$('#'+obj[y]+'l').text(obj[y+1]+"/"+obj[y+2]);
										$('#'+obj[y]).removeClass("grey");
										$('#'+obj[y]).addClass("red");
										count=count-number_tracks;
										
									}
								
								}
								
								previous=exc;

								y+=3;


							});

							$('#caricamento').removeClass("active");
						}

					});	

					

				});

				$('#annulla').on('click',function(){
	
					window.location.href = ('index.php');

				});	

				$('#pianifica_rotazione').on('click',function(){
	
					window.open('pianifica_rotazioni.php');

				});

				$('#statistica').on('click',function(){
	
					window.open('statistica.php');

				});

  			});

  		</script>
	</head>
	<body>

		<h3 class="ui header" style="margin-top:10px; margin-left:10px">
 		 <i class="calendar icon"></i>
  			<div class="content">
    			<?php echo $translation['title_weekly_statement']?>
  			</div>
		</h3>

		<table class="ui blue table"></table>
		<div style="line-height:0;width:70%;margin:0 auto;">
			
			<strong style="margin-left:30px"><?php echo $translation['text_select_category'].":"?> </strong>
			<i style="margin-left:30px" id="category" class="large folder outline icon"></i>
			<select class="uli focus dropdown" id="sottocategoria" name="sottocategoria">
				<option value="0" selected="selected"><?php echo $translation['label_none'];  echo $stamp_category; ?></option>
			</select>
			<strong style="margin-left:30px"><?php echo $translation['label_num_cat']?> </strong>
			<strong id="num_song" style="margin-left:30px"></strong>
			
		</div>
		
		<div style="line-height:0;width:70%;margin:0 auto;">
			
			<strong style="margin-left:30px"><?php echo $translation['label_info_green']?></strong>
				<a style="margin-left:5px" class="ui green circular label"><label></label>1/1</a>
			<strong style="margin-left:60px"><?php echo $translation['label_info_red']?></strong>
				<a style="margin-left:5px" class="ui red circular label"><label></label>1/1</a>
				<br>

		</div> 
		<div id="caricamento" class="ui inverted dimmer">
    		<div class="ui massive text loader"><?php echo $translation['label_loading']?></div>
  		</div>
		<table class="ui table" style="line-height:0;width:70%;margin:0 auto;">
			<thead>
				<tr>
					<th style="width:10%">
					</th>
					<th class="ui center aligned" style="width:12.5%">
						<h3><?php echo $translation['label_monday']?></h3>
					</th>
					<th class="ui center aligned" style="width:12.5%">
						<h3><?php echo $translation['label_tuesday']?></h3>
					</th>
					<th class="ui center aligned" style="width:12.5%">
						<h3><?php echo $translation['label_wednesday']?></h3>
					</th>
					<th class="ui center aligned" style="width:12.5%">
						<h3><?php echo $translation['label_thursday']?></h3>
					</thl>
					<th class="ui center aligned" style="width:12.5%">
						<h3><?php echo $translation['label_friday']?></h3>
					</th>
					<th class="ui center aligned" style="width:12.5%">
						<h3><?php echo $translation['label_saturday']?></h3>
					</th>
					<th class="ui center aligned">
						<h3><?php echo $translation['label_sunday']?></h3>
					</th>
				</tr>
			</thead>
			<tr>
				<td style="padding:2px">
					<strong>00:00 - 00:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_00']?>" ><label id="<?php echo $translation['mon_00l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_00']?>" ><label id="<?php echo $translation['tue_00l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_00']?>" ><label id="<?php echo $translation['wed_00l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_00']?>" ><label id="<?php echo $translation['thu_00l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_00']?>" ><label id="<?php echo $translation['fri_00l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_00']?>" ><label id="<?php echo $translation['sat_00l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_00']?>" ><label id="<?php echo $translation['sun_00l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>01:00 - 01:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_01']?>"><label id="<?php echo $translation['mon_01l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_01']?>"><label id="<?php echo $translation['tue_01l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_01']?>"><label id="<?php echo $translation['wed_01l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_01']?>"><label id="<?php echo $translation['thu_01l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_01']?>"><label id="<?php echo $translation['fri_01l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_01']?>"><label id="<?php echo $translation['sat_01l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_01']?>"><label id="<?php echo $translation['sun_01l']?>"></label></a>
				</td>
			</tr>	
			<tr>
				<td style="padding:2px">
					<strong>02:00 - 02:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_02']?>"><label id="<?php echo $translation['mon_02l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_02']?>"><label id="<?php echo $translation['tue_02l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_02']?>"><label id="<?php echo $translation['wed_02l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_02']?>"><label id="<?php echo $translation['thu_02l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_02']?>"><label id="<?php echo $translation['fri_02l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_02']?>"><label id="<?php echo $translation['sat_02l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_02']?>"><label id="<?php echo $translation['sun_02l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>03:00 - 03:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_03']?>"><label id="<?php echo $translation['mon_03l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_03']?>"><label id="<?php echo $translation['tue_03l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_03']?>"><label id="<?php echo $translation['wed_03l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_03']?>"><label id="<?php echo $translation['thu_03l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_03']?>"><label id="<?php echo $translation['fri_03l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_03']?>"><label id="<?php echo $translation['sat_03l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_03']?>"><label id="<?php echo $translation['sun_03l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>04:00 - 04:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_04']?>"><label id="<?php echo $translation['mon_04l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_04']?>"><label id="<?php echo $translation['tue_04l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_04']?>"><label id="<?php echo $translation['wed_04l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_04']?>"><label id="<?php echo $translation['thu_04l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_04']?>"><label id="<?php echo $translation['fri_04l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_04']?>"><label id="<?php echo $translation['sat_04l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_04']?>"><label id="<?php echo $translation['sun_04l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>05:00 - 05:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_05']?>"><label id="<?php echo $translation['mon_05l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_05']?>"><label id="<?php echo $translation['tue_05l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_05']?>"><label id="<?php echo $translation['wed_05l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_05']?>"><label id="<?php echo $translation['thu_05l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_05']?>"><label id="<?php echo $translation['fri_05l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_05']?>"><label id="<?php echo $translation['sat_05l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_05']?>"><label id="<?php echo $translation['sun_05l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>06:00 - 06:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_06']?>"><label id="<?php echo $translation['mon_06l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_06']?>"><label id="<?php echo $translation['tue_06l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_06']?>"><label id="<?php echo $translation['wed_06l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_06']?>"><label id="<?php echo $translation['thu_06l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_06']?>"><label id="<?php echo $translation['fri_06l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_06']?>"><label id="<?php echo $translation['sat_06l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_06']?>"><label id="<?php echo $translation['sun_06l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>07:00 - 07:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_07']?>"><label id="<?php echo $translation['mon_07l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_07']?>"><label id="<?php echo $translation['tue_07l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_07']?>"><label id="<?php echo $translation['wed_07l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_07']?>"><label id="<?php echo $translation['thu_07l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_07']?>"><label id="<?php echo $translation['fri_07l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_07']?>"><label id="<?php echo $translation['sat_07l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_07']?>"><label id="<?php echo $translation['sun_07l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>08:00 - 08:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_08']?>"><label id="<?php echo $translation['mon_08l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_08']?>"><label id="<?php echo $translation['tue_08l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_08']?>"><label id="<?php echo $translation['wed_08l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_08']?>"><label id="<?php echo $translation['thu_08l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_08']?>"><label id="<?php echo $translation['fri_08l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_08']?>"><label id="<?php echo $translation['sat_08l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_08']?>"><label id="<?php echo $translation['sun_08l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>09:00 - 09:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_09']?>"><label id="<?php echo $translation['mon_09l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_09']?>"><label id="<?php echo $translation['tue_09l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_09']?>"><label id="<?php echo $translation['wed_09l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_09']?>"><label id="<?php echo $translation['thu_09l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_09']?>"><label id="<?php echo $translation['fri_09l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_09']?>"><label id="<?php echo $translation['sat_09l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_09']?>"><label id="<?php echo $translation['sun_09l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>10:00 - 10:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_10']?>"><label id="<?php echo $translation['mon_10l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_10']?>"><label id="<?php echo $translation['tue_10l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_10']?>"><label id="<?php echo $translation['wed_10l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_10']?>"><label id="<?php echo $translation['thu_10l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_10']?>"><label id="<?php echo $translation['fri_10l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_10']?>"><label id="<?php echo $translation['sat_10l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_10']?>"><label id="<?php echo $translation['sun_10l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>11:00 - 11:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_11']?>"><label id="<?php echo $translation['mon_11l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_11']?>"><label id="<?php echo $translation['tue_11l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_11']?>"><label id="<?php echo $translation['wed_11l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_11']?>"><label id="<?php echo $translation['thu_11l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_11']?>"><label id="<?php echo $translation['fri_11l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_11']?>"><label id="<?php echo $translation['sat_11l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_11']?>"><label id="<?php echo $translation['sun_11l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>12:00 - 12:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_12']?>"><label id="<?php echo $translation['mon_12l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_12']?>"><label id="<?php echo $translation['tue_12l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_12']?>"><label id="<?php echo $translation['wed_12l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_12']?>"><label id="<?php echo $translation['thu_12l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_12']?>"><label id="<?php echo $translation['fri_12l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_12']?>"><label id="<?php echo $translation['sat_12l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_12']?>"><label id="<?php echo $translation['sun_12l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>13:00 - 13:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_13']?>"><label id="<?php echo $translation['mon_13l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_13']?>"><label id="<?php echo $translation['tue_13l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_13']?>"><label id="<?php echo $translation['wed_13l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_13']?>"><label id="<?php echo $translation['thu_13l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_13']?>"><label id="<?php echo $translation['fri_13l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_13']?>"><label id="<?php echo $translation['sat_13l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_13']?>"><label id="<?php echo $translation['sun_13l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>14:00 - 14:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_14']?>"><label id="<?php echo $translation['mon_14l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_14']?>"><label id="<?php echo $translation['tue_14l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_14']?>"><label id="<?php echo $translation['wed_14l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_14']?>"><label id="<?php echo $translation['thu_14l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_14']?>"><label id="<?php echo $translation['fri_14l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_14']?>"><label id="<?php echo $translation['sat_14l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_14']?>"><label id="<?php echo $translation['sun_14l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>15:00 - 15:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_15']?>"><label id="<?php echo $translation['mon_15l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_15']?>"><label id="<?php echo $translation['tue_15l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_15']?>"><label id="<?php echo $translation['wed_15l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_15']?>"><label id="<?php echo $translation['thu_15l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_15']?>"><label id="<?php echo $translation['fri_15l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_15']?>"><label id="<?php echo $translation['sat_15l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_15']?>"><label id="<?php echo $translation['sun_15l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>16:00 - 16:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_16']?>"><label id="<?php echo $translation['mon_16l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_16']?>"><label id="<?php echo $translation['tue_16l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_16']?>"><label id="<?php echo $translation['wed_16l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_16']?>"><label id="<?php echo $translation['thu_16l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_16']?>"><label id="<?php echo $translation['fri_16l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_16']?>"><label id="<?php echo $translation['sat_16l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_16']?>"><label id="<?php echo $translation['sun_16l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>17:00 - 17:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_17']?>"><label id="<?php echo $translation['mon_17l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_17']?>"><label id="<?php echo $translation['tue_17l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_17']?>"><label id="<?php echo $translation['wed_17l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_17']?>"><label id="<?php echo $translation['thu_17l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_17']?>"><label id="<?php echo $translation['fri_17l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_17']?>"><label id="<?php echo $translation['sat_17l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_17']?>"><label id="<?php echo $translation['sun_17l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>18:00 - 18:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_18']?>"><label id="<?php echo $translation['mon_18l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_18']?>"><label id="<?php echo $translation['tue_18l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_18']?>"><label id="<?php echo $translation['wed_18l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_18']?>"><label id="<?php echo $translation['thu_18l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_18']?>"><label id="<?php echo $translation['fri_18l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_18']?>"><label id="<?php echo $translation['sat_18l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_18']?>"><label id="<?php echo $translation['sun_18l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>19:00 - 19:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_19']?>"><label id="<?php echo $translation['mon_19l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_19']?>"><label id="<?php echo $translation['tue_19l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_19']?>"><label id="<?php echo $translation['wed_19l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_19']?>"><label id="<?php echo $translation['thu_19l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_19']?>"><label id="<?php echo $translation['fri_19l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_19']?>"><label id="<?php echo $translation['sat_19l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_19']?>"><label id="<?php echo $translation['sun_19l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>20:00 - 20:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_20']?>"><label id="<?php echo $translation['mon_20l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_20']?>"><label id="<?php echo $translation['tue_20l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_20']?>"><label id="<?php echo $translation['wed_20l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_20']?>"><label id="<?php echo $translation['thu_20l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_20']?>"><label id="<?php echo $translation['fri_20l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_20']?>"><label id="<?php echo $translation['sat_20l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_20']?>"><label id="<?php echo $translation['sun_20l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>21:00 - 21:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_21']?>"><label id="<?php echo $translation['mon_21l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_21']?>"><label id="<?php echo $translation['tue_21l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_21']?>"><label id="<?php echo $translation['wed_21l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_21']?>"><label id="<?php echo $translation['thu_21l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_21']?>"><label id="<?php echo $translation['fri_21l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_21']?>"><label id="<?php echo $translation['sat_21l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_21']?>"><label id="<?php echo $translation['sun_21l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>22:00 - 22:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_22']?>"><label id="<?php echo $translation['mon_22l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_22']?>"><label id="<?php echo $translation['tue_22l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_22']?>"><label id="<?php echo $translation['wed_22l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_22']?>"><label id="<?php echo $translation['thu_22l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_22']?>"><label id="<?php echo $translation['fri_22l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_22']?>"><label id="<?php echo $translation['sat_22l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_22']?>"><label id="<?php echo $translation['sun_22l']?>"></label></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>23:00 - 00:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_23']?>"><label id="<?php echo $translation['mon_23l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_23']?>"><label id="<?php echo $translation['tue_23l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_23']?>"><label id="<?php echo $translation['wed_23l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_23']?>"><label id="<?php echo $translation['thu_23l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_23']?>"><label id="<?php echo $translation['fri_23l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_23']?>"><label id="<?php echo $translation['sat_23l']?>"></label></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_23']?>"><label id="<?php echo $translation['sun_23l']?>"></label></a>
				</td>
			</tr>
		</table>
		
		<div>
			<button id="annulla" class=" big right floated ui icon labeled button" style="margin-right:30px;margin-top:10px">
		  		<i class="reply icon"></i><label><?php echo $translation['label_close'] ?></label>
			</button>
			<button id="pianifica_rotazione" class="big ui icon labeled button" style="margin-left:20px;;margin-top:10px">
	  			<i class="plus square outline icon icon"></i><label><?php echo $translation['label_plan_rotation']?></label>
			</button>
			<button id="statistica" class="big ui icon labeled button">
	  			<i class="plus square outline icon icon"></i><label><?php echo $translation['label_statistics']?></label>
			</button>
		</div>
	</body>
</html>