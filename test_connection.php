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

	$nomedbrd=$_POST['nomedb'];

	$nomedbap="rdj_library_assistant";

	$hostname=$_POST['nomehost'];

	$usr=$_POST['usr'];

	$pwd=$_POST['pwd'];

	$toolusr=$_POST['toolusr'];

	$toolpwd=$_POST['toolpwd'];

	$control=0;

	if(!test_db_connection($nomedbrd,$hostname,$usr,$pwd)){

		$control=0;

	}else{

		if(!test_db_connection($nomedbap,$hostname,$toolusr,$toolpwd)){

			$control=0;
		}else{

			$control=1;
		}

	}

	echo $control;

?>