<?php
	
	include("languages/eng.php");

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

	$count=0;

	$query="SELECT subcategory.name, subcategory.ID FROM category JOIN subcategory ON subcategory.parentid=category.ID WHERE parentid=1 ORDER BY subcategory.name";

	if($category = mysqli_query($connectionrd,$query)){

	
		while($riga = mysqli_fetch_assoc($category)){

			$stamp_category =$riga['name'];

			$ID_sub=$riga['ID'];

			$total[$count][]=$stamp_category;


			$num="SELECT songs.ID,count(*) AS NUM FROM songs WHERE songs.id_subcat='$ID_sub'";

			if($number = mysqli_query($connectionrd,$num)){
	
				while($number_song = mysqli_fetch_assoc($number)){

					$num_song=$number_song['NUM'];

					//echo $num_song ."\n";				

					$total[$count][]=$num_song;

				}

			}

				if($cat=$connectionrd->query("SELECT ID FROM events_categories WHERE name='RDJLA-events'")){

					while($riga =$cat->fetch_assoc()){

						$cat_events_ID=$riga['ID'];

					}
				}


				if($name_events=$connectionrd->query("SELECT name,ID FROM rotations")){

					while($rotations=$name_events->fetch_assoc()){


						$rotation_name=$rotations['name'];

						$rotation_ID=$rotations['ID'];

						//$rotation_array[]=$rotation_ID;

						$data="SELECT data FROM events WHERE catID='$cat_events_ID'";

						if($data_events=$connectionrd->query($data)){

							$i=0;
					
							while($events=$data_events->fetch_assoc()){

								$array=explode("|", $events['data']);


								if($array[1]!="" && $rotation_name==$array[2]){

									$i++;
								}

								


							}
						}

						$total[$count][]=$i;
			

			$number_rotation="SELECT count(*) as TOTAL FROM rotations_list WHERE subID='$ID_sub' AND pID='$rotation_ID'";

				if($num_rotation=$connectionrd->query($number_rotation)){

					while($num_rot=$num_rotation->fetch_assoc()){

						$var=$num_rot['TOTAL'];

							//echo $var;

						$total[$count][]=$var;
					}

									
				}

			
					}  
					
				
			}

		$call_total="SELECT count(*) as TOTAL FROM rotations_list WHERE subID='$ID_sub'";

				if($call_tot=$connectionrd->query($call_total)){

					while($call=$call_tot->fetch_assoc()){

						$var=$call['TOTAL'];

							//echo $var;

						$total[$count][]=$var;
					}

									
				}

							
		$count++;					

		} 

		 
	}
	

	$stamp_table="<tbody><tr>";

	for($x=0;$x<sizeof($total);$x++){

		$hhpsett=0;

		$chiamate_tot=0;

		$chiamate_cat=0;

			$stamp_table.="<td>".$total[$x][0]."</td>";

			$stamp_table.="<td>".$total[$x][1]."</td>";
	

		for($y=2;$y<sizeof($total[$x]);$y++){

			//echo $y."\n";

			$chiamate_cat+=$total[$x][$y];

			$app=$total[$x][$y]*$total[$x][$y+1];

			if($total[$x][$y+1]!=0){
			
			$hhpsett+=$total[$x][$y];
			
			}
			$chiamate_tot+=$app;

			$y++;
			

		}
			
			//echo $chiamate_cat."*\n";

			//echo $chiamate_tot."\n ";

			if($chiamate_tot==0){
				
				$pxss=0;
			}else{

			$pxss=($chiamate_tot)/$total[$x][1];

			}

			$stamp_table.="<td>".$chiamate_tot."</td>";

			$stamp_table.="<td>".round($pxss,1)."</td>";

			$pxgg=$pxss/7;

			$stamp_table.="<td>".round($pxgg,1)."</td>";

			$stamp_table.="<td>".$hhpsett."</td>";

			$hhpg=$hhpsett/7;

			$stamp_table.="<td>".round($hhpg,1)."</td>";

			$separazione=$hhpg/$pxgg;

			$stamp_table.="<td>".round($separazione,1)."</td>";

		$stamp_table.="</tr>";

	}

	$stamp_table.="</tbody>";

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Informazioni eccezioni per categoria</title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			$(document).ready(function(){

  				$('#annulla').on('click',function(){
	
					window.location.href = ('main_menu.php');

				});		

  			});
  		</script>

  	</head>
	<body>
		<table class="ui blue center aligned striped table" style="line-height:0">
			<thead>
				<tr>
					<th>
						<h3>Categorie</h3>
					</th>
					<th>
						<h3># Canzoni</h3>
					</th>
					<th>
						<h3># Chiamate</h3>
					</th>
					<th>
						<h4>Riproduzioni<br>settimana</h4>
					</th>
					<th>
						<h4>Riproduzioni<br>giorno</h4>
					</th>
					<th>
						<h4>Ore programmate<br>(settimana)</h4>
					</th>
					<th>
						<h4>Ore programmate<br>(media giorno)</h4>
					</th>
					<th>
						<h4>Separazione<br>(media ore)</h4>
					</th>
					
				</tr>
			</thead>
			<?php echo $stamp_table ?>
		</table>
		<div>
		<button id="annulla" class=" big right floated ui icon labeled button" style="margin-right:30px">
	  		<i class="reply icon"></i><label>Chiudi</label>
		</button>
		</div>
	</body>
</html>