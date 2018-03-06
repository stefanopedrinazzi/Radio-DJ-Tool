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

		//connesione al database di radiodj
		$connectionrd=DBrd_connection();

		global $db_namerd;

		mysqli_select_db($connectionrd,$db_namerd);

		$ID_cat = $_POST['ID_cat'];

		$explode = explode('~', $ID_cat);

		if($ID_cat!=0){
		$category=$explode[1];
		}

		$category_ID=$explode[0];

		//nome e ID delle sottocategorie della categoria selezionata
		$subcategory_query=("SELECT subcategory.name, subcategory.ID FROM category JOIN subcategory ON subcategory.parentid=category.ID WHERE parentid='$category_ID' ORDER BY subcategory.name");

		$stamp_subcategory = "<option value=\"0\" selected=\"selected\">All</option>";
		
		if($subcategory=$connectionrd->query($subcategory_query)){
		
			while($riga =$subcategory->fetch_assoc()){
		    
		    //creazione select box con le informazioni ottenute dalla query
		    $stamp_subcategory .= "<option value=\"".$riga['ID']."\">" . $riga['name'] ."</option>" ;

		    }  
		}
		
		echo ($stamp_subcategory);


		
?>