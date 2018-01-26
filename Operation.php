<?php

	include("languages/eng.php");

	include("FunctionNew.php");

		$connectionrd=DBrd_connection();

		//global $db_namerd;

		mysqli_select_db($connectionrd,$db_namerd);


		$ID_cat = $_POST['ID_cat'];

		//$ID_subcat = $_POST['ID_subcat'];

		//$ID_genre = $_POST['ID_genre'];

		$explode = explode('~', $ID_cat);

		if($ID_cat!=0){
		$category=$explode[1];
		}

		$category_ID=$explode[0];

		$subcategory_query=("SELECT subcategory.name, subcategory.ID FROM category JOIN subcategory ON subcategory.parentid=category.ID WHERE parentid='$category_ID' ORDER BY subcategory.name");

		$stamp_subcategory = "<option value=\"0\" selected=\"selected\">All</option>";
		
		if($subcategory=$connectionrd->query($subcategory_query)){
		
			while($riga =$subcategory->fetch_assoc()){
		    
		    $stamp_subcategory .= "<option value=\"".$riga['ID']."\">" . $riga['name'] ."</option>" ;

		    }  
		}
		
		echo ($stamp_subcategory);


		
?>