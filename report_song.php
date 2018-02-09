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

	$song_ID= $_POST['report'];

	$use="[";

	for($x=1;$x<=7;$x++){

		for($y=1;$y<=24;$y++){


			switch ($x) {
				case '1':
					$name=$translation['label_mon'];
					break;
				case '2':
					$name=$translation['label_tue'];
					break;
				case '3':
					$name=$translation['label_wed'];
					break;
				case '4':
					$name=$translation['label_thu'];
					break;
				case '5':
					$name=$translation['label_fri'];
					break;
				case '6':
					$name=$translation['label_sat'];
					break;
				case '7':
					$name=$translation['label_sun'];
					break;
			}

			$app=$y-1;

			if($app<10){
				
				$name.="0".$app;
									
			}else{

				$name.=$app;
									
			}

			$day=$x;

			$hour=$y-1;

	$use.="\"".exception_value_day_hour_ID($day,$hour,$song_ID)."\",";

		}
	}

	$use=substr($use, 0 , strlen($use)-1);

	$use.="]";

?>


<!DOCTYPE html>
	<html>
	<head>
		<title><?php echo $translation['title_song_report']?></title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			$(document).ready(function(){

  				$('.ui .circular').popup();

  				var array=<?php print_r($use); ?>;

  				var i=0;

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
							if(array[i]=="0"){

								$('#'+ID).removeClass("green");
								$('#'+ID).addClass("grey");

							}else{

								$('#'+ID).removeClass("grey");
								$('#'+ID).addClass("green");

							
							}
							i++;
						}

						
					}

					$('#caricamento').removeClass("active");


				$('#annulla').on('click',function(){
	
					window.location.href = ('index.php');

				});

  			});

  		</script>
	</head>
	<body>

		<h3 class="ui header" style="margin-top:10px; margin-left:10px">
 		 <i class="calendar icon"></i>
  			<div class="content">
    			<?php echo $translation['title_song_report']?>
  			</div>
		</h3>

		<table class="ui blue table">
		<div style="line-height:0;width:70%;margin:0 auto;">
		
		<tr style="height:80px">
			<td class="center aligned two wide">
				<i class="user large icon"></i>
			</td>	
			<td class="five wide">
				<div class="ui input focus large" style="width:400px">
		  			<input type="text" value="<?php echo Get_AT($song_ID)[0];?>" readonly/>
				</div>
			</td>
			<td class="center aligned two wide">
				<i class="music large icon"></i>
			</td>	
			<td class="five wide">
				<div class="ui input focus large" style="width:400px">
		  			<input type="text" value="<?php echo Get_AT($song_ID)[1]; ?>" readonly/>
				</div>
			</td>
		</tr>
		
		</table>
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
					<a class="ui grey circular label" id="<?php echo $translation['mon_00'] ?>" data-content="00:00 - 00:59"></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_00'] ?>" data-content="00:00 - 00:59"></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_00'] ?>" data-content="00:00 - 00:59"></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_00'] ?>" data-content="00:00 - 00:59"></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_00'] ?>" data-content="00:00 - 00:59"></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_00'] ?>" data-content="00:00 - 00:59"></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_00'] ?>" data-content="00:00 - 00:59"></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>01:00 - 01:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_01']?>" data-content="01:00 - 01:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_01']?>" data-content="01:00 - 01:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_01']?>" data-content="01:00 - 01:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_01']?>" data-content="01:00 - 01:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_01']?>" data-content="01:00 - 01:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_01']?>" data-content="01:00 - 01:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_01']?>" data-content="01:00 - 01:59" ></a>
				</td>
			</tr>	
			<tr>
				<td style="padding:2px">
					<strong>02:00 - 02:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_02']?>" data-content="02:00 - 02:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_02']?>" data-content="02:00 - 02:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_02']?>" data-content="02:00 - 02:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_02']?>" data-content="02:00 - 02:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_02']?>" data-content="02:00 - 02:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_02']?>" data-content="02:00 - 02:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_02']?>" data-content="02:00 - 02:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>03:00 - 03:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_03']?>" data-content="03:00 - 03:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_03']?>" data-content="03:00 - 03:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_03']?>" data-content="03:00 - 03:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_03']?>" data-content="03:00 - 03:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_03']?>" data-content="03:00 - 03:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_03']?>" data-content="03:00 - 03:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_03']?>" data-content="03:00 - 03:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>04:00 - 04:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_04']?>" data-content="04:00 - 04:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_04']?>" data-content="04:00 - 04:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_04']?>" data-content="04:00 - 04:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_04']?>" data-content="04:00 - 04:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_04']?>" data-content="04:00 - 04:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_04']?>" data-content="04:00 - 04:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_04']?>" data-content="04:00 - 04:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>05:00 - 05:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_05']?>" data-content="05:00 - 05:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_05']?>" data-content="05:00 - 05:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_05']?>" data-content="05:00 - 05:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_05']?>" data-content="05:00 - 05:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_05']?>" data-content="05:00 - 05:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_05']?>" data-content="05:00 - 05:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_05']?>" data-content="05:00 - 05:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>06:00 - 06:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_06']?>" data-content="06:00 - 06:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_06']?>" data-content="06:00 - 06:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_06']?>" data-content="06:00 - 06:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_06']?>" data-content="06:00 - 06:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_06']?>" data-content="06:00 - 06:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_06']?>" data-content="06:00 - 06:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_06']?>" data-content="06:00 - 06:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>07:00 - 07:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_07']?>" data-content="07:00 - 07:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_07']?>" data-content="07:00 - 07:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_07']?>" data-content="07:00 - 07:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_07']?>" data-content="07:00 - 07:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_07']?>" data-content="07:00 - 07:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_07']?>" data-content="07:00 - 07:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_07']?>" data-content="07:00 - 07:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>08:00 - 08:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_08']?>" data-content="08:00 - 08:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_08']?>" data-content="08:00 - 08:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_08']?>" data-content="08:00 - 08:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_08']?>" data-content="08:00 - 08:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_08']?>" data-content="08:00 - 08:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_08']?>" data-content="08:00 - 08:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_08']?>" data-content="08:00 - 08:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>09:00 - 09:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_09']?>" data-content="09:00 - 09:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_09']?>" data-content="09:00 - 09:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_09']?>" data-content="09:00 - 09:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_09']?>" data-content="09:00 - 09:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_09']?>" data-content="09:00 - 09:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_09']?>" data-content="09:00 - 09:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_09']?>" data-content="09:00 - 09:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>10:00 - 10:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_10']?>" data-content="10:00 - 10:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_10']?>" data-content="10:00 - 10:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_10']?>" data-content="10:00 - 10:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_10']?>" data-content="10:00 - 10:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_10']?>" data-content="10:00 - 10:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_10']?>" data-content="10:00 - 10:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_10']?>" data-content="10:00 - 10:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>11:00 - 11:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_11']?>" data-content="11:00 - 11:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_11']?>" data-content="11:00 - 11:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_11']?>" data-content="11:00 - 11:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_11']?>" data-content="11:00 - 11:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_11']?>" data-content="11:00 - 11:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_11']?>" data-content="11:00 - 11:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_11']?>" data-content="11:00 - 11:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>12:00 - 12:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_12']?>" data-content="12:00 - 12:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_12']?>" data-content="12:00 - 12:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_12']?>" data-content="12:00 - 12:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_12']?>" data-content="12:00 - 12:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_12']?>" data-content="12:00 - 12:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_12']?>" data-content="12:00 - 12:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_12']?>" data-content="12:00 - 12:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>13:00 - 13:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_13']?>" data-content="13:00 - 13:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_13']?>" data-content="13:00 - 13:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_13']?>" data-content="13:00 - 13:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_13']?>" data-content="13:00 - 13:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_13']?>" data-content="13:00 - 13:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_13']?>" data-content="13:00 - 13:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_13']?>" data-content="13:00 - 13:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>14:00 - 14:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_14']?>" data-content="14:00 - 14:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_14']?>" data-content="14:00 - 14:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_14']?>" data-content="14:00 - 14:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_14']?>" data-content="14:00 - 14:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_14']?>" data-content="14:00 - 14:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_14']?>" data-content="14:00 - 14:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_14']?>" data-content="14:00 - 14:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>15:00 - 15:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_15']?>" data-content="15:00 - 15:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_15']?>" data-content="15:00 - 15:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_15']?>" data-content="15:00 - 15:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_15']?>" data-content="15:00 - 15:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_15']?>" data-content="15:00 - 15:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_15']?>" data-content="15:00 - 15:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_15']?>" data-content="15:00 - 15:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>16:00 - 16:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_16']?>" data-content="16:00 - 16:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_16']?>" data-content="16:00 - 16:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_16']?>" data-content="16:00 - 16:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_16']?>" data-content="16:00 - 16:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_16']?>" data-content="16:00 - 16:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_16']?>" data-content="16:00 - 16:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_16']?>" data-content="16:00 - 16:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>17:00 - 17:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_17']?>" data-content="17:00 - 17:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_17']?>" data-content="17:00 - 17:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_17']?>" data-content="17:00 - 17:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_17']?>" data-content="17:00 - 17:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_17']?>" data-content="17:00 - 17:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_17']?>" data-content="17:00 - 17:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_17']?>" data-content="17:00 - 17:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>18:00 - 18:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_18']?>" data-content="18:00 - 18:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_18']?>" data-content="18:00 - 18:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_18']?>" data-content="18:00 - 18:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_18']?>" data-content="18:00 - 18:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_18']?>" data-content="18:00 - 18:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_18']?>" data-content="18:00 - 18:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_18']?>" data-content="18:00 - 18:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>19:00 - 19:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_19']?>" data-content="19:00 - 19:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_19']?>" data-content="19:00 - 19:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_19']?>" data-content="19:00 - 19:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_19']?>" data-content="19:00 - 19:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_19']?>" data-content="19:00 - 19:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_19']?>" data-content="19:00 - 19:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_19']?>" data-content="19:00 - 19:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>20:00 - 20:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_20']?>" data-content="20:00 - 20:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_20']?>" data-content="20:00 - 20:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_20']?>" data-content="20:00 - 20:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_20']?>" data-content="20:00 - 20:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_20']?>" data-content="20:00 - 20:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_20']?>" data-content="20:00 - 20:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_20']?>" data-content="20:00 - 20:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>21:00 - 21:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_21']?>" data-content="21:00 - 21:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_21']?>" data-content="21:00 - 21:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_21']?>" data-content="21:00 - 21:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_21']?>" data-content="21:00 - 21:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_21']?>" data-content="21:00 - 21:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_21']?>" data-content="21:00 - 21:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_21']?>" data-content="21:00 - 21:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>22:00 - 22:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_22']?>" data-content="22:00 - 22:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_22']?>" data-content="22:00 - 22:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_22']?>" data-content="22:00 - 22:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_22']?>" data-content="22:00 - 22:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_22']?>" data-content="22:00 - 22:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_22']?>" data-content="22:00 - 22:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_22']?>" data-content="22:00 - 22:59" ></a>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>23:00 - 00:59</strong>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['mon_23']?>" data-content="23:00 - 00:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['tue_23']?>" data-content="23:00 - 00:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['wed_23']?>" data-content="23:00 - 00:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['thu_23']?>" data-content="23:00 - 00:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['fri_23']?>" data-content="23:00 - 00:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sat_23']?>" data-content="23:00 - 00:59" ></a>
				</td>
				<td class="ui center aligned" style="padding:2px">
					<a class="ui grey circular label" id="<?php echo $translation['sun_23']?>" data-content="23:00 - 00:59" ></a>
				</td>
			</tr>
		</table>
		<div>
		<button id="annulla" class=" big right floated ui icon labeled button" style="margin-right:30px;margin-top:10px">
	  		<i class="reply icon"></i><label><?php echo $translation['label_close'] ?></label>
		</button>
		</div>
	</body>
</html>