<?php
include_once '/usr/lib/includes/db_connect.php';
include_once '/usr/lib/includes/functions.php';

sec_session_start();
if (login_check($mysqli) == true){
	$username = $_SESSION['username'];
	$stmt = $mysqli->query("SELECT rgb, etat, h_on, h_off, email, effet FROM leds_strip WHERE owner='$username'");
	$arr = $stmt->fetch_array(MYSQLI_ASSOC);

	$row_data = array ( $arr['rgb'],$arr['etat'],$arr['h_on'],$arr['h_off'], $arr['email'], $arr['effet'] );

	$mysqli->close();

	echo json_encode($row_data);
}
else {
header("Location: ../index.php");
}
?>

