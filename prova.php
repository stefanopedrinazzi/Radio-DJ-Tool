<?php

	include("FunctionNew.php");

	$id=2;

	$id_subcat=2; //get_subcat_from_ID_song($id);

	echo $id_subcat;
	echo "<br>";
	$minmax=get_min_max_playout($id_subcat);
	print_r($minmax);

	echo "<br>";

	echo randomize($minmax);

?>