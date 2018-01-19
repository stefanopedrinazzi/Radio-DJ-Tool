<?php


	include("FunctionNew.php");

	$connectionrd=DBrd_connection();

	global $db_namerd;

	mysqli_select_db($connectionrd,$db_namerd);

	$obj= $_POST['obj'];

	$stamp=(json_decode($obj,true));


	for($x=1;$x<=7;$x++){

		for($y=0;$y<=23;$y++){

			$day="&".$x;

			$hours="&".$y;

			switch ($x) {
				case '1':
					$name="Lun";
					break;
				case '2':
					$name="Mar";
					break;
				case '3':
					$name="Mer";
					break;
				case '4':
					$name="Gio";
					break;
				case '5':
					$name="Ven";
					break;
				case '6':
					$name="Sab";
					break;
				case '7':
					$name="Dom";
					break;
			}


			if($y<10){
									
				$name.="0".$y;
									
			}else{

				$name.=$y;
									
			}

			$array[]=$stamp[$name];
				
		}
		
			
	}

	
	//print_r($array);

	$index=0;

	for($x=1;$x<=7;$x++){

		for($y=0;$y<=23;$y++){

			$day="&".$x;

			$hours="&".$y;

			switch ($x) {
				case '1':
					$name="Lun";
					break;
				case '2':
					$name="Mar";
					break;
				case '3':
					$name="Mer";
					break;
				case '4':
					$name="Gio";
					break;
				case '5':
					$name="Ven";
					break;
				case '6':
					$name="Sab";
					break;
				case '7':
					$name="Dom";
					break;
			}

			if($y<10){
									
				$name.="0".$y;
									
			}else{

				$name.=$y;
									
			}


			if($array[$index]==="0"){
			
				$update="UPDATE events SET events.data='Clear Playlist!' WHERE events.name='$name'";

				//echo $update."\n";
			
			}else{
			
				$rot_ID=get_id_from_rotation($array[$index]);

				echo $rot_ID."\n";

			$update="UPDATE events SET events.data='Clear Playlist!
Load Rotation|$rot_ID|$array[$index]' WHERE events.name='$name'";

				//echo $update."\n";
			
			}

			$connectionrd->query($update);

		$index++;
		}
	}

?>