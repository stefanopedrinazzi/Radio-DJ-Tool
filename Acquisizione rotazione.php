<?php

	include("Function_rotation.php");

	$var = $_POST['rotazione'];

	$explode = explode('~', $var);

	$rotation_name=$explode[1];

	$rotation_ID=$explode[0];


	DBrd_connection();

	print_rotation_list($rotation_name,$rotation_ID);

	echo "ok!";

?>