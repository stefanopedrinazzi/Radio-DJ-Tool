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

	//acquisizione valori per la connesione ai DB
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
	
	//test di connesione ai DB
	if(!test_db_connection($nomedbrd,$hostname,$usr,$pwd)){

		$control=0;

	}else{

		if(!test_db_connection($nomedbap,$hostname,$toolusr,$toolpwd)){

			$control=0;
		}else{

			$control=1;
		}

	}

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	$count=0;
	
	//acquisizione nome e ID di ogni subcategory di song
	$query="SELECT subcategory.name, subcategory.ID FROM category JOIN subcategory ON subcategory.parentid=category.ID WHERE parentid=1 ORDER BY subcategory.name";

	if($category=$connectionrd->query($query)){

		while($riga=$category->fetch_assoc()){

			$stamp_category =$riga['name'];

			$ID_sub=$riga['ID'];

			$total[$count][]=$stamp_category;

			//conteggio delle canzoni attive per ogni sottocategoria
			$num="SELECT count(*) AS NUM FROM songs WHERE songs.id_subcat='$ID_sub' AND enabled='1'";

			if($number=$connectionrd->query($num)){
	
				while($number_song=$number->fetch_assoc()){

					$num_song=$number_song['NUM'];				

					$total[$count][]=$num_song;
				}
			}
				//acquisizione ID della categoria eventi RDJLA
				if($cat=$connectionrd->query("SELECT ID FROM events_categories WHERE name='RDJLA-events'")){

					while($riga=$cat->fetch_assoc()){

						$cat_events_ID=$riga['ID'];

					}
				}

				//acquisizione nome e ID delle rotazioni
				if($name_events=$connectionrd->query("SELECT name,ID FROM rotations")){

					while($rotations=$name_events->fetch_assoc()){

						$rotation_name=$rotations['name'];

						$rotation_ID=$rotations['ID'];

						//acquisizione data dagli eventi di RDJLA
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
			
							//conteggio delle volte in cui la sottocategoria viene richiamata in una rotazione
							$number_rotation="SELECT count(*) as TOTAL FROM rotations_list WHERE subID='$ID_sub' AND pID='$rotation_ID'";

						if($num_rotation=$connectionrd->query($number_rotation)){

							while($num_rot=$num_rotation->fetch_assoc()){

								$var=$num_rot['TOTAL'];

								$total[$count][]=$var;
							}			
						}
					}  		
				}

				//conteggio delle volte in cui la sottocategoria viene chiamata
				$call_total="SELECT count(*) as TOTAL FROM rotations_list WHERE subID='$ID_sub'";

				if($call_tot=$connectionrd->query($call_total)){

					while($call=$call_tot->fetch_assoc()){

						$var=$call['TOTAL'];

						$total[$count][]=$var;
					}
					
				}
						
		$count++;					

		} 
	}
	$connectionrd->close();
	
	//creazione delle variabili da inserire nella tabella
	$stamp_table="<tbody><tr>";

	for($x=0;$x<sizeof($total);$x++){

		$hhpsett=0;

		$chiamate_tot=0;

		$chiamate_cat=0;

			$stamp_table.="<td>".$total[$x][0]."</td>";

			$stamp_table.="<td>".$total[$x][1]."</td>";
	

		for($y=2;$y<sizeof($total[$x]);$y++){

			$chiamate_cat+=$total[$x][$y];

			$app=$total[$x][$y]*$total[$x][$y+1];

			if($total[$x][$y+1]!=0){
			
			$hhpsett+=$total[$x][$y];
			
			}
			$chiamate_tot+=$app;

			$y++;
			

		}
			

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
		<title><?php echo $translation['label_statistics']?></title>

		<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="Semantic/semantic.min.css">
  		<script src="js/semantic.min.js"></script>

  		<script type="text/javascript">
  			$(document).ready(function(){

  				$('#annulla').on('click',function(){
	
					window.location.href = ('index.php');

				});		

  			});
  		</script>

  	</head>
	<body>

		<h3 class="ui header" style="margin-top:10px; margin-left:10px">
 		 <i class="bar chart icon"></i>
  			<div class="content">
    			<?php echo $translation['label_statistics']?>
  			</div>
		</h3>

		<table class="ui blue center aligned striped table" style="line-height:0">
			<thead>
				<tr>
					<th>
						<h3><?php echo $translation['label_category']?></h3>
					</th>
					<th>
						<h3><?php echo "#".$translation['label_songs']?></h3>
					</th>
					<th>
						<h3><?php echo "#".$translation['label_call']?></h3>
					</th>
					<th>
						<h4><?php echo $translation['label_w_reproductions']?></h4>
					</th>
					<th>
						<h4><?php echo $translation['label_d_reproductions']?></h4>
					</th>
					<th>
						<h4><?php echo $translation['label_scheduled_hour']."<br>(".$translation['label_week'].")"?></h4>
					</th>
					<th>
						<h4><?php echo $translation['label_scheduled_hour']."<br>(".$translation['label_d_average'].")"?></h4>
					</th>
					<th>
						<h4><?php echo $translation['label_separation']."<br>(".$translation['label_h_average'].")"?></h4>
					</th>
					
				</tr>
			</thead>
			<?php echo $stamp_table ?>
		</table>
		<div>
		<button id="annulla" class=" big right floated ui icon labeled button" style="margin-right:30px">
	  		<i class="reply icon"></i><label><?php echo $translation['label_close']?></label>
		</button>
		</div>
	</body>
</html>