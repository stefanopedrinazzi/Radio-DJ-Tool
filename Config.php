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

	//acquizione dati con $_POST da impostazioni.php
	$db_namerd=$_POST['nomedb'];

	$hostnamerd=$_POST['nomehost'];

	$usernamerd=$_POST['usr'];
	
	$passwordrd=$_POST['pwd'];

	$usernameap=$_POST['toolusr'];
	
	$passwordap=$_POST['toolpwd'];

	$path=$_POST['path'];

	$language=$_POST['language'];

	//scrittura del file config.txt con i nuovi dati inseriti
	$config = fopen("config.txt", "w") or die("Unable to open file!");
		
	fwrite($config, $db_namerd.PHP_EOL);
	fwrite($config, $hostnamerd.PHP_EOL);
	fwrite($config, $usernamerd.PHP_EOL);
	fwrite($config, $passwordrd.PHP_EOL);
	fwrite($config, $usernameap.PHP_EOL);
	fwrite($config, $passwordap.PHP_EOL);
	fwrite($config, $path.PHP_EOL);
	fwrite($config, $language.PHP_EOL);

	fclose($config);

?>
