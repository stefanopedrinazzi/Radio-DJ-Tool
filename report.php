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

	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);

	$data=$_POST['data'];

	$var= $_POST['categoria'];

	$explode = explode('~', $var);

//	print_r($explode);

	$categoria=$explode[1];

	$id_subcat=$explode[0];

	//echo $data."<br>";

	$now=Convert_date($data);

	//echo $now."<br>";
	//$now=different_convert_date($now);

	if($now==""){

	$actual_day = date ('N', time())-1;

	}else{
	
	//echo $now."<br>";

	$mytime= different_convert_date($now).", 00:00:00";

	//echo $mytime."<br>";

	$mytime=strtotime($mytime);

	//echo $mytime."<br>";

	$actual_day = date('N', $mytime)-1;

	}

	//echo $actual_day ."<br>";

	//Array ID e ID_song di tutte le eccezioni con range di data che comprende la data e ora attuale

	$exception="SELECT songs_exceptions.ID,songs_exceptions.ID_song FROM songs_exceptions WHERE ('$now' BETWEEN songs_exceptions.data_in AND songs_exceptions.data_out) AND data_in!='0'";

	$i=0;
	$x=0;
	if($exc_date=$connectionap->query($exception)){

		while($exc=$exc_date->fetch_assoc()){

			$a[$i][$x]=$exc['ID'];
			$x=1;
			$a[$i][$x]=$exc['ID_song'];
			$i++;
			$x=0;
		}
		
	}

	//echo "a:";
	//print_r($a);
	//echo "<br><br><br>";
	
	//Array ID e ID_song di tutte le eccezioni di default

	$default="SELECT songs_exceptions.ID,songs_exceptions.ID_song FROM songs_exceptions WHERE data_in='0'";

	$i=0;
	$x=0;
	if($def_date=$connectionap->query($default)){

		while($def=$def_date->fetch_assoc()){

			$b[$i][$x]=$def['ID'];
			$x=1;
			$b[$i][$x]=$def['ID_song'];
			$i++;
			$x=0;
		}
		
	}
	//echo "b:";
	//print_r($b);
	//echo "<br><br><br><br>";

	//Creazione array di $c=$b-$a (default-eccezioni attive) 
	
	for($x=0;$x<sizeof($b);$x++){

			$flag=0;
		if(sizeof($a)==0){
			$c[]=$b[$x];
		}else{
			for($y=0;$y<sizeof($a);$y++){


				if($b[$x][1]!=$a[$y][1]){
					
					$flag=1;	
				
				}else{

					$flag=0;
					
					break;
				}

			}
			if($flag==1){

				$c[]=$b[$x];

			}
		}
		
	
	}

	//Compilazione array $c completo di tutte eccezioni di default e le eccezioni attive ora
	for($x=0;$x<sizeof($a);$x++){

				
			$c[]=$a[$x];

	}		

	//echo "c:";	
	//print_r($c);
	//echo"<br><br><br>";


	for($x=0;$x<sizeof($c);$x++){

		$ID_exc=$c[$x][0];

		$ID_song=$c[$x][1];

		$grid="SELECT songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID='$ID_exc'";

		$Grid=$connectionap->query($grid);

		$result=$Grid->fetch_assoc();
	

		for($y=(24*$actual_day);$y<((24*$actual_day)+24);$y++){

				$array_day[]=$result["grid"][$y];
		
		}
		
		
			$matrix[$x][]=$ID_song;

		for($z=$x*24;$z<($x*24)+24;$z++){

		
		$matrix[$x][]=$array_day[$z];

		

		}
	}
	
	//print_r($matrix);

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	$i=0;

	$query="SELECT * FROM songs WHERE id_subcat='$id_subcat'";

	if($song_subcat=$connectionrd->query($query)){

		while($song=$song_subcat->fetch_assoc()){

			$category[]=$song['ID'];
			//$category[$i][]=$song['enabled'];

			//echo $song['ID'].": ".$song['enabled'];

			//echo "<br>";

			$i++;

		}
	}
	//echo "<br><br><br>";
	//print_r($category);

	

	for($x=0;$x<sizeof($category);$x++){

		for($y=0;$y<sizeof($matrix);$y++){

			if($category[$x]==$matrix[$y][0]){

				$res[$x]=$matrix[$y];
			}

		}
	}
	//echo "<br><br><br>";
	//print_r($res);

	$disabled=0;

	for($y=1;$y<=24;$y++){

		for($x=0;$x<sizeof($matrix);$x++){


			if ($res[$x][$y]==1){

				$disabled+=1;

			}
		}
		$arrayhour[$y-1][0]=$disabled;
		$arrayhour[$y-1][1]=sizeof($category)-$disabled;
		$disabled=0;
	}
	//echo "<br><br><br><br>";
	//print_r($arrayhour);

	$stamp="<table><tr>
				<td></td>
				<td>00</td>
				<td>01</td>
				<td>02</td>
				<td>03</td>
				<td>04</td>
				<td>05</td>
				<td>06</td>
				<td>07</td>
				<td>08</td>
				<td>09</td>
				<td>10</td>
				<td>11</td>
				<td>12</td>
				<td>13</td>
				<td>14</td>
				<td>15</td>
				<td>16</td>
				<td>17</td>
				<td>18</td>
				<td>19</td>
				<td>20</td>
				<td>21</td>
				<td>22</td>
				<td>23</td>
				</tr>
				<tr>
				<td>tracce attive</td>
				";
	for($x=0;$x<24;$x++){

		$stamp.="<td>".$arrayhour[$x][1]."</td>";

	}

	$stamp.="</tr><tr><td>tracce disattive</td>";

	for($x=0;$x<24;$x++){

		$stamp.="<td>".$arrayhour[$x][0]."</td>";

	}
	$stamp.="</tr></table>";
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
		<table class="ui blue table">
			<tr>
				<td>
					<h3><?php if($now==""){
						echo "Risultati per oggi "; 
					}else{
						echo "Risultati per il giorno ".$data;
					}
					?></h3>
				</td>
			</tr>
			<tr>
			</tr>
			<tr>
				<td>
					<div class="ui form">
  						<div class="field">
    						<h4 class="ui header" style="margin-top:10px">
 		 						<i class="info icon"></i>
  									<div class="content">
    									Eccezioni orarie
  									</div>
							</h4>
    							<?php echo $stamp ?>
    					</div>
  					</div>
					
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