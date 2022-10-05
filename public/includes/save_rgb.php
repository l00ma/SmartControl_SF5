<?php
include_once '/usr/lib/includes/db_connect.php';
include_once '/usr/lib/includes/functions.php';
 
sec_session_start();
if (login_check($mysqli) == true){
	if (isset($_GET['save']) && !empty($_GET['save'])) {
		$tab_save = explode (",", $_GET['save']);
		for ( $i=0; $i<count($tab_save); $i++ ) {
			$rouge = $tab_save[0];
			$vert = $tab_save[1];
			$bleu = $tab_save[2];
			$etat = $tab_save[3];
			$email = $tab_save[4];
			$effet = $tab_save[5];
		}
	}
	$username = $_SESSION['username'];
	$couleur_rgb = $rouge .','. $vert .','. $bleu;
	$stmt = $mysqli->prepare("UPDATE leds_strip SET rgb=?, etat=?, email=?, effet=? WHERE owner='$username'");
	$stmt->bind_param('ssss', $couleur_rgb, $etat, $email, $effet);
	$stmt->execute(); 
	$mysqli->close();
}
else {
header("Location: ../index.php");
}
?>
