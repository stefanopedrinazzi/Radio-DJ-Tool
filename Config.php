<?php 
	
	include("languages/eng.php");

	include("FunctionNew.php");

	$db_namerd=$_POST['nomedb'];

	$hostnamerd=$_POST['nomehost'];

	$usernamerd=$_POST['usr'];
	
	$passwordrd=$_POST['pwd'];

	$usernameap=$_POST['toolusr'];
	
	$passwordap=$_POST['toolpwd'];

	$path=$_POST['path'];
	
	//$_SESSION['rootdirectory']=$_POST['rootdirectory'];

	$config = fopen("config.txt", "w") or die("Unable to open file!");
		
		fwrite($config, $db_namerd.PHP_EOL);
		fwrite($config, $hostnamerd.PHP_EOL);
		fwrite($config, $usernamerd.PHP_EOL);
		fwrite($config, $passwordrd.PHP_EOL);
		fwrite($config, $usernameap.PHP_EOL);
		fwrite($config, $passwordap.PHP_EOL);
		fwrite($config, $path.PHP_EOL);


		fclose($config);

	echo $usernameap;


?>
