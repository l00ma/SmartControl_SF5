<?php
include_once '/usr/lib/includes/db_connect.php';
include_once '/usr/lib/includes/functions.php';

sec_session_start();
if (login_check($mysqli) == true){
	$username = $_SESSION['username'];
	$timer= 0;
	$h_on = null;
	$h_off = null;
	if (isset($_GET['debut']) && !empty($_GET['debut']) && isset($_GET['fin']) && !empty($_GET['fin'])) {
		$h_on = $_GET['debut'];
		$h_off = $_GET['fin'];
	}
		
	$stmt = $mysqli->prepare("UPDATE leds_strip SET h_on=?, h_off=?, timer=? WHERE owner='$username'");
	$stmt->bind_param('sss', $h_on, $h_off, $timer);
	$stmt->execute(); 
	$mysqli->close();
}
else {
header("Location: ../index.php");
}
?>