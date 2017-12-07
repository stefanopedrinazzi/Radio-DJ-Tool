<?php


	include("FunctionNew.php");

	$connectionrd=DBrd_connection();
	$connectionap=DBap_connection();

	global $db_namerd;
	global $db_nameap;

	mysqli_set_charset($connectionrd,"utf8");
	mysqli_set_charset($connectionap,"utf8");

	mysqli_select_db($connectionrd,$db_namerd);
	mysqli_select_db($connectionap,$db_nameap);

		$ID_cat = $_POST['ID_cat'];

		$ID_subcat = $_POST['ID_subcat'];

		$ID_genre = $_POST['ID_genre'];

		$search = $_POST['Search'];

		$draw = $_POST['draw'];

		$length = $_POST['length'];

		$start = $_POST['start'];

		$explode = explode('~', $ID_cat);

		$category_ID=$explode[0];

		if($category_ID!=0){
		$category=$explode[1];
		}

		$song ="";

		$limit=" LIMIT ".$length . " OFFSET " . $start;

		$array=Number_exception();


		if($category_ID=="0" && $ID_genre=="0" && $ID_subcat=="0"){

		$song="SELECT songs.ID, songs.artist, songs.title FROM songs ORDER BY songs.title ".$limit;

		$count="SELECT COUNT(*) AS total FROM songs";

		}elseif ($ID_subcat=="0" && $ID_genre=="0"){
		
			$song="SELECT songs.ID,songs.artist, songs.title FROM category JOIN subcategory ON category.ID=subcategory.parentid JOIN songs ON songs.id_subcat=subcategory.ID WHERE category.ID='$category_ID' ORDER BY songs.title ".$limit;
			
			$count="SELECT COUNT(*) AS total FROM category JOIN subcategory ON category.ID=subcategory.parentid JOIN songs ON songs.id_subcat=subcategory.ID WHERE category.ID='$category_ID'";

		}elseif($ID_subcat=="0" && $ID_genre!="0"){
			
			$song="SELECT songs.ID,songs.artist, songs.title FROM category JOIN subcategory ON subcategory.parentid=category.ID JOIN songs ON songs.id_subcat=subcategory.ID WHERE songs.id_genre='$ID_genre' AND category.ID='$category_ID' ORDER BY songs.title ".$limit;

			$count="SELECT COUNT(*) AS total FROM category JOIN subcategory ON subcategory.parentid=category.ID JOIN songs ON songs.id_subcat=subcategory.ID WHERE songs.id_genre='$ID_genre' AND category.ID='$category_ID'";
		
		}elseif($ID_subcat!="0" && $ID_genre=="0"){
			
			$song="SELECT songs.ID,songs.artist, songs.title FROM category JOIN subcategory ON subcategory.parentid=category.ID JOIN songs ON songs.id_subcat=subcategory.ID WHERE songs.id_subcat='$ID_subcat' AND category.ID='$category_ID' ORDER BY songs.title ".$limit;

			$count="SELECT COUNT(*) AS total FROM category JOIN subcategory ON subcategory.parentid=category.ID JOIN songs ON songs.id_subcat=subcategory.ID WHERE songs.id_subcat='$ID_subcat' AND category.ID='$category_ID'";

		
		}elseif($ID_subcat!="0" && $ID_genre!="0"){
			
			$song="SELECT songs.ID,songs.artist, songs.title FROM category JOIN subcategory ON subcategory.parentid=category.ID JOIN songs ON songs.id_subcat=subcategory.ID WHERE songs.id_genre='$ID_genre' AND songs.id_subcat='$ID_subcat' AND category.ID='$category_ID' ORDER BY songs.title ".$limit;

			$count="SELECT COUNT(*) AS total FROM  category JOIN subcategory ON subcategory.parentid=category.ID JOIN songs ON songs.id_subcat=subcategory.ID WHERE songs.id_genre='$ID_genre' AND songs.id_subcat='$ID_subcat' AND category.ID='$category_ID'";
		
		}

		
		
		if($search!="" && $category_ID=="0"){
			
			$app="";

			$app=" WHERE songs.title LIKE '%$search%' OR songs.artist LIKE '%$search%' ORDER BY songs.title ".$limit;

			$song = $song."".$app;
		
			$count = $count."".$app;

		}
		if($search!="" && $category_ID!="0"){

			$app="";

			$app=" AND (songs.title LIKE '%$search%' OR songs.artist LIKE '%$search%')  ORDER BY songs.title ".$limit;

			$song = $song."".$app;

			$count = $count."".$app;

		}

		if($countquery=$connectionrd->query($count)){

			$query=$countquery->fetch_assoc();
	
			$total=$query['total'];

		}
		$elenco_songs=array('draw'=> $draw,'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>array( ));

		if($songquery=$connectionrd->query($song)){

	
			while($riga=$songquery->fetch_assoc()){

			error_reporting(E_ERROR | E_WARNING | E_PARSE);
			$number=is_null($array[$riga['ID']]) ? 0 : $array[$riga['ID']];
			error_reporting(E_ALL);
				

				array_push($elenco_songs['data'], array('Titolo' => $riga['title'], 'Artista' => $riga['artist'], 'Eccezioni' => $number, 'Azione'=>$riga['ID']));

			}

		}		


		echo json_encode($elenco_songs);
?>
