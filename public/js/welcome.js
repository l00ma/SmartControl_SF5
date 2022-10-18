// fonctions

var histo_film = new Array();
var histo_mail = new Array();

function loadValues() {
	$.ajax({
		type: 'get',
		async: false,
		url: 'welcome/load',
		dataType: 'json',
		success: (function (result) {
			traiteEtAffiche(result);
		}),
		error: (function () {
			setTimeout(loadValues, 10000);
		}),
	});
}

function traiteEtAffiche(data) {
	histo_film[4] = data['0'];
	histo_film[3] = data['1'];
	histo_film[2] = data['2'];
	histo_film[1] = data['3'];
	histo_film[0] = data['4'];

	histo_mail[4] = data['5'];
	histo_mail[3] = data['6'];
	histo_mail[2] = data['7'];
	histo_mail[1] = data['8'];
	histo_mail[0] = data['9'];
	data.weather = decodeURIComponent(escape(data.weather));

	var nombre_film = 0; var nombre_mail = 0;
	for (i = 0; i < histo_film.length; i++) {
		if (histo_film[i] != '0') {
			nombre_film++;
		}
		else { histo_film[i] = ""; }

		if (histo_mail[i] != '0') {
			nombre_mail++;
		}
		else { histo_mail[i] = ""; }
	}


	if (data.etat === 'true') {
		$("#etat_leds").html('<span class="gadget_allume">Leds allumées</span>');
	}
	else {
		$("#etat_leds").html('Leds éteintes');
	}
	if (data.enreg == '1' || data.alert == '1') {
		$("#etat_detection").html('<span class="gadget_allume">Détection activée</span>');
		if (data.enreg_detect === '1') {
			$("#etat_alerte").html('<span class="alerte_allume">Alerte en cours</span>');
		}
		else {
			$("#etat_alerte").html('Pas d\'alerte');
		}
	}
	else {
		$("#etat_detection").html('Détection désactivée');
		$("#etat_alerte").html('');
	}
	$("#lieu").html('<span class="moyenne">' + data.location + '</span>');
	$("#previsions").html('<table><tr><th class="grande text-danger"><i class="wi ' + data.icon_id + '"></i></th><th class="moyenne"><i class="wi ' + data.icon_f1 + '" title="' + data.weather_f1 + '"></i></th><th class="moyenne"><i class="wi ' + data.icon_f2 + '" title="' + data.weather_f2 + '"></i></th><th class="moyenne"><i class="wi ' + data.icon_f3 + '" title="' + data.weather_f3 + '"></i></th></tr><tr><td class="petite" >' + data.weather + '</td><td class="petite" >' + data.temp_f1 + '°c</td><td class="petite" >' + data.temp_f2 + '°c</td><td class="petite" >' + data.temp_f3 + '°c</td></tr><tr><td></td><td class="petite">' + data.time_f1 + '</td><td class="petite" >' + data.time_f2 + '</td><td class="petite" >' + data.time_f3 + '</td></tr></table>');

	$("#temp").html('<table><tr><td class="moyenne" title="température"><i class="wi wi-thermometer"></i></td><td title="temp bas:' + data.temp_bas + '" ><span class="petite">ext </span><span class="moyenne">' + data.temp_ext + '</span><span class="petite"> °c</span>&nbsp;&nbsp;&nbsp;<span class="petite">int </span><span class="moyenne">' + data.temp_int + '</span><span class="petite"> °c</span></td></tr><tr><td class="moyenne" title="humidité"><i class="wi wi-humidity"></i></td><td><span class="moyenne" title="humidité">' + data.humidite + '</span><span class="petite"> %</span></td></tr><tr><td class="moyenne" title="pression"><i class="wi wi-barometer"></i></td><td><span class="moyenne" title="pression">' + data.pression + '</span><span class="petite"> Hpa</span></td></tr><tr><td class="moyenne" title="vent"><i class="wi wi-strong-wind"></i></td><td><span class="moyenne" title="vent">' + data.vitesse_vent + '</span><span class="petite"> km/h</span>&nbsp;&nbsp;&nbsp;<span class="moyenne">' + data.direction_vent + '</span></td></tr><tr><td align="center"  colspan="2" ><i class="wi wi-sunrise" title="heure de levé du soleil"></i>&nbsp;&nbsp;<span class="moyenne">' + data.leve_soleil + '</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="wi wi-sunset" title="heure de couché du soleil"></i>&nbsp;&nbsp;<span class="moyenne">' + data.couche_soleil + '</span></td></tr></table>');

	if (nombre_film === 0) {
		tableau_film = 'aucun';
	} else {
		tableau_film = histo_film[4] + '<br>' + histo_film[3] + '<br>' + histo_film[2] + '<br>' + histo_film[1] + '<br>' + histo_film[0];
	}

	if (nombre_mail === 0) {
		tableau_mail = 'aucun';
	} else {
		tableau_mail = histo_mail[4] + '<br>' + histo_mail[3] + '<br>' + histo_mail[2] + '<br>' + histo_mail[1] + '<br>' + histo_mail[0];
	}

	$('.histo').html('<table><tr><td>enregistrements vidéo:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>emails envoyés:</td></tr><tr><td>' + tableau_film + '</td><td></td><td>' + tableau_mail + '</td></tr></table>');

	setTimeout(loadValues, 10000)
}


//fonction affiche l'heure
function AfficheHeure() {
	dows = ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];
	mois = ["janv", "f&eacute;v", "mars", "avril", "mai", "juin", "juillet", "ao&ucirc;t", "sept", "oct", "nov", "d&eacute;c"];
	sep = ":";
	now = new Date;
	heure = now.getHours();
	min = now.getMinutes();
	sec = now.getSeconds();
	jour_semaine = dows[now.getDay()];
	jour = now.getDate();
	mois = mois[now.getMonth()];
	annee = now.getFullYear();

	if (sec < 10) { sec0 = "0"; } else { sec0 = ""; }
	if (min < 10) { min0 = "0"; } else { min0 = ""; }
	if (heure < 10) { heure0 = "0"; } else { heure0 = ""; }

	horloge_heure = heure0 + heure + sep + min0 + min + sep + sec0 + sec;
	horloge_date = jour_semaine + " " + jour + " " + mois + " " + annee;
	horloge_content = "<div class='moyenne'>" + horloge_date + "</div><div class='grande'>" + horloge_heure + "</div>";
	document.getElementById('heure').innerHTML = horloge_content;
	setTimeout(AfficheHeure, 1000)
}

// actions
$(document).ready(function () {
	loadValues();
	AfficheHeure();
});