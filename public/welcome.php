<?php

include_once '/usr/lib/includes/db_connect.php';
include_once '/usr/lib/includes/functions.php';

sec_session_start();
?>
<?php if (login_check($mysqli) == true) : ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Bienvenue</title>
        <link rel="stylesheet" href="css/site.css" />
	<link rel="stylesheet" href="css/weather-icons.min.css" />
    </head>
    <body id="page_welcome">
	<header>
		<span id="logo"><img src="css/images/raspberry_petit.png" alt="L" height="26" width="29"> Smart Control</span>
		<span id="menu_haut">
			<button id="but_logout" title="Se déconnecter" >Déconnexion</button>
		</span>
	</header>	
	<section>
		<nav>
			<h1>Bienvenue <?php echo htmlentities($_SESSION['username']); ?></h1>
			<p>Menu:</p>
			<ul>
				<li><a href="mouvement.php">Détection de mouvements</a></li>
				<li><a href="gallery.php">Vidéos enregistrées</a></li>
				<li><a href="live.php">Caméra en direct</a></li>
				<li><a href="temp.php">Suivi température</a></li>
				<li><a href="leds.php">Controle éclairage</a></li>
				<li><a href="register.php">Modifier mes données</a></li>
			</ul>
		</nav>
		<div id="historique">
			<h1>détections récentes:</h1>
			<p id="histo_liste"></p>
		</div>
		<aside>
			<p id="heure"></p>
			<p id="lieu"></p>
			<p id="previsions"></p>
			<div id="temp"></div>
			<p id="etat_detection"></p>
			<p id="temp"><span id="etat_alerte"></span></p>
			<p id="etat_leds"></p>
		</aside>
	</section>    
	<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="js/welcome.js"></script>
    </body>
</html>
<?php else : 
header("Location: index.php");
endif; ?>
