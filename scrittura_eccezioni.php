<?php


	include("FunctionNew.php");

	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);


	$song_ID= $_POST['ID_song'];

	$date_start=$_POST['date_start'];

	$date_end=$_POST['date_end'];

	$eccezione=$_POST['eccezione'];

	$modify=$_POST['modify'];

	$ExceptionID= $_POST['ExceptionID'];

	$array_exc=Convert_exception($eccezione);

	$data_start=Convert_date($date_start);

	$data_end=Convert_date($date_end);

	if($data_start>1200 && $data_end<131){

		$data_start1=$data_start;

		$data_end1=1231;

		$data_start2=101;

		$data_end2=$data_end;

		$exception=Set_exceptions($song_ID,$data_start1,$data_end1,$array_exc,$modify,$ExceptionID);
	
		$exception.=Set_exceptions($song_ID,$data_start2,$data_end2,$array_exc,$modify,$ExceptionID);
	
	}else{

		$exception=Set_exceptions($song_ID,$data_start,$data_end,$array_exc,$modify,$ExceptionID);
	}


	

	echo($exception);

?>