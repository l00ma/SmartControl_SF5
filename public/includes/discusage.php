<?php
include_once '/usr/lib/includes/db_connect.php';
include_once '/usr/lib/includes/functions.php';

sec_session_start();
if (login_check($mysqli) == true){
	$username = $_SESSION['username'];
	$stmt = $mysqli->query("SELECT espace_total, espace_dispo, taux_utilisation FROM mouvement_pir WHERE owner='$username'");
	$arr = $stmt->fetch_array(MYSQLI_ASSOC);
	$array = array ( $arr['espace_total'], $arr['espace_dispo'], $arr['taux_utilisation']);
	$mysqli->close();
	echo json_encode($array);
}
else {
header("Location: ../index.php");
}
?>

