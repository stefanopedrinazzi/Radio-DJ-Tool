<?php


	include("FunctionNew.php");

	$connectionap=DBap_connection();

	global $db_nameap;

	mysqli_select_db($connectionap,$db_nameap);

	$year=date("Y");
	

	$song_ID = $_POST['ID_song'];

	$exception_ID = $_POST['ExceptionID'];
	
	$get="SELECT songs_exceptions.ID_song, songs_exceptions.data_in, songs_exceptions.data_out, songs_exceptions.grid FROM songs_exceptions WHERE songs_exceptions.ID_song='$song_ID' AND songs_exceptions.ID='$exception_ID'";

	$take=$connectionap->query($get);

	$riga = $take->fetch_assoc();
	
	$day=substr($riga['data_in'],-2);

	$mese=explode($day,$riga['data_in']);

	if($mese[0]=="1" || $mese[0]=="2" || $mese[0]==2 || $mese[0]==4 || $mese[0]==5 || $mese[0]==6 || $mese[0]==7 || $mese[0]==8 || $mese[0]==9){
		$mese[0]="0".$mese[0];
	}
	
	$data_start=$year."-".$mese[0]."-".$day;

	$day=substr($riga['data_out'],-2);

	$mese=explode($day,$riga['data_out']);

	if($mese[0]=="1" || $mese[0]=="2" || $mese[0]==2 || $mese[0]==4 || $mese[0]==5 || $mese[0]==6 || $mese[0]==7 || $mese[0]==8 || $mese[0]==9){
		$mese[0]="0".$mese[0];
	}
	
	$data_end=$year."-".$mese[0]."-".$day;


	echo json_encode(array('ID_song' => $riga['ID_song'] , 'data_start' => $data_start , 'data_end' => $data_end , 'eccezione'=>$riga['grid']));

?>