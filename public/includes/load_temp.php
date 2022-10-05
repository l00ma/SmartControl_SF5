<?php
include_once '/usr/lib/includes/db_connect.php';
include_once '/usr/lib/includes/functions.php';

sec_session_start();
if (login_check($mysqli) == true){
	$fileContent_int = file_get_contents('/var/smartcontrol_sensor_data/temp_sensor.data');
	$fileContent_int = '{"data_int":' . $fileContent_int.',';
	$fileContent_ext = file_get_contents('/var/smartcontrol_sensor_data/temp_ext_sensor.data');
	$fileContent_ext = ' "data_ext":' . $fileContent_ext.',';
	$fileContent_bas = file_get_contents('/var/smartcontrol_sensor_data/temp_bas_sensor.data');
	$fileContent_bas = ' "data_bas":' . $fileContent_bas.'}';
	$fileContent = $fileContent_int . $fileContent_ext . $fileContent_bas;
	echo json_encode($fileContent);
}
else {
header("Location: ../index.php");
}
?>

