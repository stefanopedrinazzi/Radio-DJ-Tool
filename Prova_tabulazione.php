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


	$stamp_name="";

	$rotation="SELECT name FROM rotations";

	if($rotation_name = mysqli_query($connectionrd,$rotation)){
	
		while($name = mysqli_fetch_assoc($rotation_name)){

    		$stamp_name.= "<option value=\"".$name['name']."\">" . $name['name'] ."</option>" ;

		}  
	}	

	$connectionrd->close();

	?>

<!DOCTYPE html>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Informazioni eccezioni per categoria</title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			$(document).ready(function(){

  				$("#annulla").on('click',function(){
	
					window.location.href = ("report_data.php");
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
			<tr>
				<td style="padding:2px">
					<strong>00:00 - 00:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>01:00 - 01:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>	
			<tr>
				<td style="padding:2px">
					<strong>02:00 - 02:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>03:00 - 03:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>04:00 - 04:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>05:00 - 05:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>06:00 - 06:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>07:00 - 07:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>08:00 - 08:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>09:00 - 09:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>10:00 - 10:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>11:00 - 11:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>12:00 - 12:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>13:00 - 13:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>14:00 - 14:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>15:00 - 15:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>16:00 - 16:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>17:00 - 17:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>18:00 - 18:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>19:00 - 19:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>20:00 - 20:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>21:00 - 21:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>22:00 - 22:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
			<tr>
				<td style="padding:2px">
					<strong>23:00 - 00:59</strong>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
				<td style="padding:2px">
					<select class="ui  dropdown" id="genre" name="sottocategoria" style="padding:0; height:22px; width:120px">
						<option value="0" selected="selected">- Unscheduled -</option> <?php echo $stamp_name; ?> </select>
				</td>
			</tr>
		</table>
		<div>
		<button id="annulla" class=" big right floated ui icon labeled button" style="margin-right:30px">
	  		<i class="reply icon"></i><label>Chiudi</label>
		</button>
		</div>
	</body>
</html>