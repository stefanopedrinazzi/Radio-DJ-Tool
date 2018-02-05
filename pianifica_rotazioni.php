<?php

	include("FunctionNew.php");

	//acquisizione dei dati per la connessione ai database
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
	
	//richiamo funzioni per testare le connessioni dei due database
	if(!test_db_connection($nomedbrd,$hostname,$usr,$pwd)){

		$control=0;

	}else{

		if(!test_db_connection($nomedbap,$hostname,$toolusr,$toolpwd)){

			$control=0;
	
		}else{

			$control=1;
		}

	}

	//set delle variabili di sessione
	if($control==1){
		$_SESSION['db_namerd']=$nomedbrd;
		$_SESSION['hostnamerd']=$hostname;
		$_SESSION['usernamerd']=$usr;
		$_SESSION['passwordrd']=$pwd;
		$_SESSION['usernameap']=$toolusr;
		$_SESSION['passwordap']=$toolpwd;
		$_SESSION['path']=$path;
		$_SESSION['language']=$language;
	
	}

	//connesione al database di RadioDJ
	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	//controllo dell'esistenza della categoria di eventi "RDJLA-events"
	if($control=$connectionrd->query("SELECT ID FROM events_categories WHERE name='RDJLA-events'")){

		//se non esiste la categoria viene creata
		if($control->num_rows == 0){

			$insert="INSERT INTO events_categories (name) values ('RDJLA-events')";

			$inser=$connectionrd->query($insert);
		
		}
	}

	//acquisizione ID dell'evento RDJA-events
	if($cat=$connectionrd->query("SELECT ID FROM events_categories WHERE name='RDJLA-events'")){

		while($riga = mysqli_fetch_assoc($cat)){

			$catID=$riga['ID'];

		}
	}
	
	//creazione degli eventi (24/7) da utilizzare per la pianificazione 					
	for($x=1;$x<=7;$x++){

		for($y=0;$y<=23;$y++){

			if($y-1==-1){

				$hours="&23";
			
				if($x-1==0){

					$day="&7";

				}else{

					$d=$x-1;

					$day="&".$d;

				}
			
			}else{

				$h=$y-1;

				$hours="&".$h;

				$day="&".$x;
			}

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

			if($y<10){
								
				$name.="0".$y;
								
			}else{

				$name.=$y;
								
			}

			$type=2;

			$time="00:59:00";


			//scrittura degli eventi nel database se non esistenti utilizzando giorno e ora ricavati
			if($exist=$connectionrd->query("SELECT ID FROM events WHERE day='$day' AND hours='$hours'")){

				if($exist->num_rows == 0){

				$events="INSERT INTO events (type,time,name,day,hours,catID,smart,data) VALUES ('$type','$time','$name','$day','$hours','$catID','0','Clear Playlist!')"; 

				$event=$connectionrd->query($events);

				}else{

				$update="UPDATE events SET name='$name' WHERE day='$day' AND hours='$hours'";
					
				$updat=$connectionrd->query($update);
				}
			}

		}
	}

 	//creazione delle select box
	$stamp_name="";

	$rotation="SELECT name FROM rotations";

	if($rotation_name = mysqli_query($connectionrd,$rotation)){
	
		while($name = mysqli_fetch_assoc($rotation_name)){


    		$stamp_name.= "<option value=\"".$name['name']."\">" . $name['name'] ."</option>" ;

		}  
	}

	//compilazione delle select box
	for($x=1;$x<=7;$x++){

		for($y=0;$y<=23;$y++){

			$day="&".$x;

			$hours="&".$y;

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

			if($y<10){
									
				$name.="0".$y;
									
			}else{

				$name.=$y;
									
			}

			//acquisizione delle rotazioni presenti nel database
			$data="SELECT data FROM events WHERE name='$name'";

			if($data_events = mysqli_query($connectionrd,$data)){
		
				while($events = mysqli_fetch_assoc($data_events)){

					$array=explode("|", $events['data']);

						if($events['data']=="Clear Playlist!" || $events['data']=="INIT"){

						$rotation_array[]="0";

						}else{
							if($array[0]=="Clear Playlist!
Load Rotation" && isset($array[3])==false){
								
								$rotation_array[]=$array[2];
							}else{

								$rotation_array[]="1";

							}
						}
				}
			}

		}
	}

	$connectionrd->close();

	?>

<!DOCTYPE html>
	<html>
	<head>
		<title><?php echo $translation['label_plan_rotation'];?></title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			$(document).ready(function(){

  				$('#salva').prop("disabled",true);

  				var rot_array=<?php echo json_encode($rotation_array); ?>

  					var i=0;

	  				for(var y=1;y<=7;y++){
						
						for(var x=0;x<=23;x++){	

						var name="";

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
							
							var print=$('#'+ID).val(rot_array[i]);

							i++;
						}
					}

  				print="";

  				var obj ="";

  				$("#annulla").on('click',function(){
	
					window.location.href = ("index.php");
  				});

  				$("#report_settimanale").on('click',function(){
	
					window.open("cruscotto_settimanale.php");
  				});

  				$("#statistica").on('click',function(){
	
					window.open("statistica.php");
  				});

  				$('.ui .dropdown').on('change',function(){

  					$('#salva').prop("disabled",false);

  					obj ="{";

	  				for(var y=1;y<=7;y++){
						
						for(var x=0;x<=23;x++){	

						var name="";

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
							
							print=$('#'+ID).val();

							//console.log(print);
							if(y==7 && x==23){

								obj += '"'+ID+'":"'+print+'"}';

							}else{
							
								obj += '"'+ID+'":"'+print+'",';
							
							}
							
							
						}	
					}
				});

  				$('#salva').on('click',function(){
	
					$('#salva').prop("disabled",true);
					$('#annulla').prop("disabled",true);

					$.ajax({	
						type: "POST",
						url: "scrittura_rotazione.php",
						data: { obj: obj},
						success: function(){

							alert("<?php echo $translation['alert_plan_saved']?>");
							$('#annulla').prop("disabled",false);

							location.reload(true);

						}
					});	

				});


  			});

  		</script>
	</head>
	<body>

		<h3 class="ui header" style="margin-top:10px; margin-left:10px">
 		 <i class="calendar icon"></i>
  			<div class="content">
    			<?php echo $translation['label_plan_rotation']?>
  			</div>
		</h3>
	
		<table class="ui blue striped table" style="line-height:0">
			<thead>
				<tr>
					<th>
					</th>
					<th>
						<h3><?php echo $translation['label_monday']?></h3>
					</th>
					<th>
						<h3><?php echo $translation['label_tuesday']?></h3>
					</th>
					<th>
						<h3><?php echo $translation['label_wednesday']?></h3>
					</th>
					<th>
						<h3><?php echo $translation['label_thursday']?></h3>
					</th>
					<th>
						<h3><?php echo $translation['label_friday']?></h3>
					</th>
					<th>
						<h3><?php echo $translation['label_saturday']?></h3>
					</th>
					<th>
						<h3><?php echo $translation['label_sunday']?></h3>
					</th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<td style="padding:2px">
					<strong>00:00 - 00:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_00']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden hidden>- User option -</option>
						<?php echo $stamp_name; ?>
					</select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_00']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_00']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_00']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_00']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_00']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_00']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>01:00 - 01:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_01']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_01']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_01']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_01']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_01']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_01']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_01']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>	
			<tr>
				<td style="padding:2px">
					<strong>02:00 - 02:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_02']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_02']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_02']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_02']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_02']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_02']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_02']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>03:00 - 03:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_03']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_03']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_03']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_03']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_03']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_03']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_03']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>04:00 - 04:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_04']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_04']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_04']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_04']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_04']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_04']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_04']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>05:00 - 05:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_05']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_05']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_05']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_05']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_05']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_05']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_05']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>06:00 - 06:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_06']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_06']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_06']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_06']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_06']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_06']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_06']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>07:00 - 07:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_07']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_07']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_07']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_07']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_07']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_07']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_07']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>08:00 - 08:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_08']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_08']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_08']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_08']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_08']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_08']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_08']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>09:00 - 09:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_09']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_09']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_09']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_09']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_09']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_09']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_09']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>10:00 - 10:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_10']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_10']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_10']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_10']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_10']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_10']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_10']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>11:00 - 11:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_11']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_11']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_11']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_11']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_11']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_11']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_11']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>12:00 - 12:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_12']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_12']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_12']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_12']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_12']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_12']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_12']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>13:00 - 13:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_13']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_13']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_13']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_13']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_13']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_13']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_13']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>14:00 - 14:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_14']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_14']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_14']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_14']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_14']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_14']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_14']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>15:00 - 15:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_15']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_15']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_15']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_15']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_15']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_15']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_15']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>16:00 - 16:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_16']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_16']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_16']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_16']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_16']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_16']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_16']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>17:00 - 17:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_17']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_17']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_17']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_17']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_17']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_17']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_17']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>18:00 - 18:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_18']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_18']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_18']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_18']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_18']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_18']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_18']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>19:00 - 19:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_19']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_19']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_19']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_19']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_19']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_19']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_19']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>20:00 - 20:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_20']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_20']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_20']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_20']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_20']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_20']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_20']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>21:00 - 21:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_21']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_21']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_21']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_21']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_21']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_21']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_21']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>22:00 - 22:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_22']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_22']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_22']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_22']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_22']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_22']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_22']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>23:00 - 00:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['mon_23']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['tue_23']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['wed_23']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['thu_23']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['fri_23']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sat_23']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="<?php echo $translation['sun_23']?>" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option>
						<option value="1" hidden>- User option -</option>
						<?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			</tbody>
		</table>
		<div>
		<button id="annulla" class=" big right floated ui icon labeled negative button" style="margin-right:30px">
	  		<i class="window close icon"></i><label><?php echo $translation['label_close']?></label>
		</button>
		<button id="salva" class="big right floated ui icon labeled primary button">
  			<i class="checkmark icon"></i><label><?php echo $translation['label_save']?></label>
		</button>
		<button id="report_settimanale" class="big ui icon labeled button" style="margin-left:10px">
  			<i class="plus square outline icon icon"></i><label><?php echo $translation['label_weekly_report']?></label>
		</button>
		<button id="statistica" class="big ui icon labeled button" style="margin-left:10px;;margin-top:10px">
  			<i class="plus square outline icon icon"></i><label><?php echo $translation['label_statistics']?></label>
		</button>
		</div>
	</body>
</html>