<?php

	include("FunctionNew.php");

	$nomedbrd=$_POST['nomedb'];

	$nomedbap="rdj_library_assistant";

	$hostname=$_POST['nomehost'];

	$usr=$_POST['usr'];

	$pwd=$_POST['pwd'];

	$toolusr=$_POST['toolusr'];

	$toolpwd=$_POST['toolpwd'];

	$control="";

	if(!test_db_connection($nomedbrd,$hostname,$usr,$pwd)){

		$control=0;
	}else{

		$control=1;
	}

	if(!test_db_connection($nomedbap,$hostname,$toolusr,$toolpwd)){

		$control=0;
	}else{

		$control=1;
	}

	echo $control;

?>