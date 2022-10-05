<?php
include_once '/usr/lib/includes/db_connect.php';
include_once '/usr/lib/includes/functions.php';

sec_session_start();
if (login_check($mysqli) == true){

	function resultToArray($result,$type) {
	    $rows = array();
	    while($row = $result->fetch_assoc()) {
	        $rows[] = $type.' '.$row['time_stamp'];
	    }
	    while (count($rows) < 5 ) {
	    	$rows[] = 'vide';
	    }
	    return $rows;
	}

	$username = $_SESSION['username'];
	$stmt = $mysqli->query("SELECT etat, temp, temp_ext, temp_bas FROM leds_strip WHERE owner='$username'");
	$arr = $stmt->fetch_array(MYSQLI_ASSOC);
	$array1 = array ( 
		'etat' => $arr['etat'], 
		'temp_int' => $arr['temp'],
		'temp_ext' => $arr['temp_ext'],
		'temp_bas' => $arr['temp_bas'],);

	$stmt = $mysqli->query("SELECT enreg, enreg_detect, alert FROM mouvement_pir WHERE owner='$username'");
	$arr = $stmt->fetch_array(MYSQLI_ASSOC);
	$array2 = array ( 
		'enreg' => $arr['enreg'], 
		'enreg_detect' => $arr['enreg_detect'], 
		'alert' => $arr['alert']);
	
	$stmt = $mysqli->query("SELECT pression, vitesse_vent, direction_vent, location, humidite, weather, icon_id, leve_soleil, couche_soleil, temp_f1, temp_f2, temp_f3, time_f1, time_f2, time_f3, weather_f1, weather_f2, weather_f3, icon_f1, icon_f2, icon_f3 FROM meteo WHERE owner='$username'");
	$arr = $stmt->fetch_array(MYSQLI_ASSOC);
	$array3 = array (  
		'pression' => $arr['pression'], 
		'vitesse_vent' => $arr['vitesse_vent'], 
		'direction_vent' => $arr['direction_vent'], 
		'location' => $arr['location'], 
		'humidite' => $arr['humidite'], 
		'weather' => $arr['weather'], 
		'icon_id' => $arr['icon_id'], 
		'leve_soleil' => $arr['leve_soleil'], 
		'couche_soleil' => $arr['couche_soleil'], 
		'temp_f1' => $arr['temp_f1'], 
		'temp_f2' => $arr['temp_f2'], 
		'temp_f3' => $arr['temp_f3'], 
		'time_f1' => $arr['time_f1'], 
		'time_f2' => $arr['time_f2'], 
		'time_f3' => $arr['time_f3'], 
		'weather_f1' => $arr['weather_f1'], 
		'weather_f2' => $arr['weather_f2'], 
		'weather_f3' => $arr['weather_f3'], 
		'icon_f1' => $arr['icon_f1'], 
		'icon_f2' => $arr['icon_f2'], 
		'icon_f3' => $arr['icon_f3'] );

	$stmt = $mysqli->query("SELECT time_stamp FROM security WHERE file_type='8' ORDER BY id DESC LIMIT 5");
	$array4 = resultToArray($stmt,'film');

	$stmt = $mysqli->query("SELECT time_stamp FROM security WHERE file_type='2' ORDER BY id DESC LIMIT 5");
	$array5 = resultToArray($stmt,'mail');

	$mysqli->close();
	$row_data = array_merge ($array1, $array2, $array3, $array4, $array5 );
	echo json_encode($row_data);
}
else {
header("Location: ../index.php");
}
?>

