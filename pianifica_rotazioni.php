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

	if($control=$connectionrd->query("SELECT ID FROM events_categories WHERE name='RDJLA-events'")){

		if($control->num_rows == 0){

			$insert="INSERT INTO events_categories (name) values ('RDJLA-events')";

			$inser=$connectionrd->query($insert);
		
		}
	}
						
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
					$name="Lun";
					break;
				case '2':
					$name="Mar";
					break;
				case '3':
					$name="Mer";
					break;
				case '4':
					$name="Gio";
					break;
				case '5':
					$name="Ven";
					break;
				case '6':
					$name="Sab";
					break;
				case '7':
					$name="Dom";
					break;
			}

			if($y<10){
								
				$name.="0".$y;
								
			}else{

				$name.=$y;
								
			}

				$type=2;

				$time="00:59:00";

				if($cat=$connectionrd->query("SELECT ID FROM events_categories WHERE name='RDJLA-events'")){

					while($riga = mysqli_fetch_assoc($cat)){

						$catID=$riga['ID'];

					}
				}

				if($exist=$connectionrd->query("SELECT ID FROM events WHERE name='$name'")){

					if($exist->num_rows == 0){

				$events="INSERT INTO events (type,time,name,day,hours,catID,smart,data) VALUES ('$type','$time','$name','$day','$hours','$catID','0','AutoDJ Enable!')"; 

					$event=$connectionrd->query($events);

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
					$name="Lun";
					break;
				case '2':
					$name="Mar";
					break;
				case '3':
					$name="Mer";
					break;
				case '4':
					$name="Gio";
					break;
				case '5':
					$name="Ven";
					break;
				case '6':
					$name="Sab";
					break;
				case '7':
					$name="Dom";
					break;
			}

			if($y<10){
									
				$name.="0".$y;
									
			}else{

				$name.=$y;
									
			}

			$data="SELECT data FROM events WHERE name='$name'";

			if($data_events = mysqli_query($connectionrd,$data)){
		
				while($events = mysqli_fetch_assoc($data_events)){

					$array=explode("|", $events['data']);

					if($array[2]==""){

						$rotation_array[]="0";
					}else{

						$rotation_array[]=$array[2];
					}

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
		<title>Programmazione Settimanale</title>

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
							
							var print=$('#'+ID).val(rot_array[i]);

							i++;
						}
					}

  				var print="";

  				var obj ="";

  				$("#annulla").on('click',function(){
	
					window.location.href = ("main_menu.php");
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
							
							var print=$('#'+ID).val();

							//console.log(print);
							if(y==7 && x==23){

								obj += '"'+ID+'":"'+print+'"}';

							}else{
							
								obj += '"'+ID+'":"'+print+'",';
							
							}
							
							//console.log(myJSON);
						}	
					}
				});

  				$('#salva').on('click',function(){
	
					$('#salva').prop("disabled",true);

					$.ajax({	
						type: "POST",
						url: "scrittura_rotazione.php",
						data: { obj: obj},
						success: function(){

							alert("Pianificazione salvata.")

							location.reload(true);

						}
					});	

				});


  			});

  		</script>
	</head>
	<body>
	
		<h2 class="ui blue center aligned header" style="margin-top:20px">Programmazione Settimanale</h2>
		<table class="ui blue sm- Unscheduled - striped table" style="line-height:0">
			<thead>
				<tr>
					<th>
					</th>
					<th>
						<h3>Lunedì</h3>
					</th>
					<th>
						<h3>Martedì</h3>
					</th>
					<th>
						<h3>Mercoledì</h3>
					</th>
					<th>
						<h3>Giovedì</h3>
					</th>
					<th>
						<h3>Venerdì</h3>
					</th>
					<th>
						<h3>Sabato</h3>
					</th>
					<th>
						<h3>Domenica</h3>
					</th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<td style="padding:2px">
					<strong>00:00 - 00:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun00" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar00" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer00" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio00" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven00" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab00" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom00" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>01:00 - 01:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun01" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar01" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer01" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio01" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven01" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab01" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom01" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>	
			<tr>
				<td style="padding:2px">
					<strong>02:00 - 02:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun02" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar02" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer02" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio02" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven02" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab02" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom02" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>03:00 - 03:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun03" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar03" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer03" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio03" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven03" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab03" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom03" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>04:00 - 04:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun04" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar04" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer04" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio04" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven04" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab04" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom04" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>05:00 - 05:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun05" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar05" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer05" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio05" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven05" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab05" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom05" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>06:00 - 06:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun06" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar06" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer06" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio06" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven06" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab06" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom06" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>07:00 - 07:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun07" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar07" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer07" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio07" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven07" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab07" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom07" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>08:00 - 08:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun08" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar08" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer08" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio08" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven08" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab08" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom08" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>09:00 - 09:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun09" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar09" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer09" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio09" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven09" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab09" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom09" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>10:00 - 10:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun10" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar10" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer10" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio10" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven10" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab10" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom10" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>11:00 - 11:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun11" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar11" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer11" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio11" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven11" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab11" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom11" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>12:00 - 12:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun12" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar12" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer12" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio12" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven12" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab12" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom12" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>13:00 - 13:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun13" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar13" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer13" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio13" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven13" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab13" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom13" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>14:00 - 14:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun14" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar14" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer14" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio14" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven14" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab14" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom14" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>15:00 - 15:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun15" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar15" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer15" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio15" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven15" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab15" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom15" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>16:00 - 16:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun16" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar16" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer16" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio16" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven16" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab16" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom16" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>17:00 - 17:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun17" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar17" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer17" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio17" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven17" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab17" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom17" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>18:00 - 18:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun18" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar18" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer18" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio18" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven18" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab18" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom18" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>19:00 - 19:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun19" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar19" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer19" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio19" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven19" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab19" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom19" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>20:00 - 20:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun20" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar20" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer20" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio20" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven20" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab20" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom20" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>21:00 - 21:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun21" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar21" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer21" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio21" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven21" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab21" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom21" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>22:00 - 22:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun22" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar22" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer22" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio22" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven22" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab22" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom22" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>23:00 - 00:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Lun23" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mar23" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Mer23" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Gio23" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Ven23" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Sab23" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui dropdown" id="Dom23" name="giorno_ora" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			</tbody>
		</table>
		<div>
		<button id="annulla" class=" big right floated ui icon labeled negative button" style="margin-right:30px">
	  		<i class="window close icon"></i><label>Chiudi</label>
		</button>
		<button id="salva" class="big right floated ui icon labeled primary button">
  			<i class="checkmark icon"></i><label>Salva</label>
		</button>
		</div>
	</body>
</html>