<?php


	include("FunctionNew.php");

	$connectionrd=DBrd_connection();
	$connectionap=DBap_connection();

	global $db_namerd;
	global $db_nameap;

	mysqli_select_db($connectionrd,$db_namerd);
	mysqli_select_db($connectionap,$db_nameap);

		$ID_cat = $_POST['ID_cat'];

		$ID_subcat = $_POST['ID_subcat'];

		$ID_genre = $_POST['ID_genre'];

		$search = $_POST['Search'];

		$explode = explode('~', $ID_cat);

		$category_ID=$explode[0];

		if($category_ID!=0){
		$category=$explode[1];
		}

		$song ="";

		$array=Number_exception();


		if($category_ID=="0" && $ID_genre=="0" && $ID_subcat=="0"){

		$song="SELECT songs.ID, songs.artist, songs.title FROM songs";


		}elseif ($ID_subcat=="0" && $ID_genre=="0"){
		
			$song="SELECT songs.ID,songs.artist, songs.title FROM category JOIN subcategory ON category.ID=subcategory.parentid JOIN songs ON songs.id_subcat=subcategory.ID WHERE category.ID='$category_ID'";

		}elseif($ID_subcat=="0" && $ID_genre!="0"){
			
			$song="SELECT songs.ID,songs.artist, songs.title FROM category JOIN subcategory ON subcategory.parentid=category.ID JOIN songs ON songs.id_subcat=subcategory.ID WHERE songs.id_genre='$ID_genre' AND category.ID='$category_ID'";
		
		}elseif($ID_subcat!="0" && $ID_genre=="0"){
			
			$song="SELECT songs.ID,songs.artist, songs.title FROM category JOIN subcategory ON subcategory.parentid=category.ID JOIN songs ON songs.id_subcat=subcategory.ID WHERE songs.id_subcat='$ID_subcat' AND category.ID='$category_ID'";
		
		}elseif($ID_subcat!="0" && $ID_genre!="0"){
			
			$song="SELECT songs.ID,songs.artist, songs.title FROM category JOIN subcategory ON subcategory.parentid=category.ID JOIN songs ON songs.id_subcat=subcategory.ID WHERE songs.id_genre='$ID_genre' AND songs.id_subcat='$ID_subcat' AND category.ID='$category_ID'";
		
		}

		
		
		if($search!="" && $category_ID=="0"){
			
			$app="";

			$app=" WHERE songs.title LIKE '%$search%' OR songs.artist LIKE '%$search%'";

			$song = $song."".$app;
		
		}
		if($search!="" && $category_ID!="0"){

			$app="";

			$app=" AND (songs.title LIKE '%$search%' OR songs.artist LIKE '%$search%')";

			$song = $song."".$app;

		}

		
		$stamp_song="<thead><tr><th><i class=\"music icon\"></i>Titolo</th><th><i class=\"user icon\"></i>Artista</th><th><i class=\"hashtag icon\"></i>Eccezioni</th><th><i class=\"setting icon\"></i>Azione</th></tr></thead><tbody>";
		

		if($songquery=$connectionrd->query($song)){

	
			while($riga =$songquery->fetch_assoc()){

			error_reporting(E_ERROR | E_WARNING | E_PARSE);
			$number=$array[$riga['ID']];
			error_reporting(E_ALL);
					
				if($number!=0){
					$button="";
					$button="<button class=\"mini ui icon labeled primary button\" name=\"get_song\" value=\"".$riga['ID']."\"><i class=\"setting icon\"></i>Modifica</button>";
				}else{
					$button="";
					$button="<button class=\"mini ui icon labeled green button\" name=\"get_song\" value=\"".$riga['ID']."\"><i class=\"icon plus\"></i>Aggiungi</button>";
				}

				//$array_number=Get_exception($riga['ID']);


				$stamp_song .= "<tr>
								<td>".$riga['title']."</td>
								<td>".$riga['artist']."</td>
								<td>".$number."</td>
								<td>".$button."</td></tr>";
			}

		}
		$stamp_song .="</tbody>";

		echo ($stamp_song);
?>
