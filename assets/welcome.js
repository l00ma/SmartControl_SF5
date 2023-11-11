import './styles/welcome.scss';

let videoHistory = [];
let emailHistory = [];

function loadValues() {
	videoHistory = [];
	emailHistory = [];
	fetch('welcome/load')
		.then(response => {
			if (!response.ok) {
				throw new Error('Erreur lors de la requête: HTTP code ' + response.status);
			}
			return response.json();
		})
		.then(data => {
			parseAndDisplay(data);
		})
		.catch(error => {
			console.error('Erreur:', error);
			alert('Erreur lors de la lecture des données');
		});
}

function parseAndDisplay(data) {
	for (let i = 0; i < 5; i++) {
		const videoValue = data[i];
		const emailValue = data[(5 + i)];
		if (videoValue !== '0') {
			videoHistory.push(videoValue);
		}
		if (emailValue !== '0') {
			emailHistory.push(emailValue);
		}
	}

	data.weather = decodeURIComponent(encodeURIComponent(data.weather));

	data.etat === 'true' ? document.getElementById("etat_leds").innerHTML = '<span class="gadget_allume">Leds allumées</span>' : document.getElementById("etat_leds").innerHTML = 'Leds éteintes';

	if (data.enreg == '1' || data.alert == '1') {
		document.getElementById("etat_detection").innerHTML = '<span class="gadget_allume">Détection activée</span>';
		data.enreg_detect === '1' ? document.getElementById("etat_alerte").innerHTML = '<span class="alerte_allume">Alerte en cours</span>' : document.getElementById("etat_alerte").innerHTML = 'Pas d\'alerte';
	}
	else {
		document.getElementById("etat_detection").innerHTML = 'Détection désactivée';
		document.getElementById("etat_alerte").innerHTML = '';
	}

	document.getElementById("lieu").innerHTML = '<span class="moyenne">' + data.location + '</span>';
	document.getElementById("previsions").innerHTML = '<table><tr><th class="grande text-danger"><i class="wi ' + data.icon_id + '"></i></th><th class="moyenne"><i class="wi ' + data.icon_f1 + '" title="' + data.weather_f1 + '"></i></th><th class="moyenne"><i class="wi ' + data.icon_f2 + '" title="' + data.weather_f2 + '"></i></th><th class="moyenne"><i class="wi ' + data.icon_f3 + '" title="' + data.weather_f3 + '"></i></th></tr><tr><td class="petite" >' + data.weather + '</td><td class="petite" >' + data.temp_f1 + '°c</td><td class="petite" >' + data.temp_f2 + '°c</td><td class="petite" >' + data.temp_f3 + '°c</td></tr><tr><td></td><td class="petite">' + data.time_f1 + '</td><td class="petite" >' + data.time_f2 + '</td><td class="petite" >' + data.time_f3 + '</td></tr></table>';

	document.getElementById("temp").innerHTML = '<table><tr><td class="moyenne" title="température"><i class="wi wi-thermometer"></i></td><td title="temp bas:' + data.temp_bas + '" ><span class="petite">ext </span><span class="moyenne">' + data.temp_ext + '</span><span class="petite"> °c</span>&nbsp;&nbsp;&nbsp;<span class="petite">int </span><span class="moyenne">' + data.temp_int + '</span><span class="petite"> °c</span></td></tr><tr><td class="moyenne" title="humidité"><i class="wi wi-humidity"></i></td><td><span class="moyenne" title="humidité">' + data.humidite + '</span><span class="petite"> %</span></td></tr><tr><td class="moyenne" title="pression"><i class="wi wi-barometer"></i></td><td><span class="moyenne" title="pression">' + data.pression + '</span><span class="petite"> Hpa</span></td></tr><tr><td class="moyenne" title="vent"><i class="wi wi-strong-wind"></i></td><td><span class="moyenne" title="vent">' + data.vitesse_vent + '</span><span class="petite"> km/h</span>&nbsp;&nbsp;&nbsp;<span class="moyenne">' + data.direction_vent + '</span></td></tr><tr><td align="center"  colspan="2" ><i class="wi wi-sunrise" title="heure de levé du soleil"></i>&nbsp;&nbsp;<span class="moyenne">' + data.leve_soleil + '</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="wi wi-sunset" title="heure de couché du soleil"></i>&nbsp;&nbsp;<span class="moyenne">' + data.couche_soleil + '</span></td></tr></table>';

	let videoDisplay, emailDisplay;
	videoHistory.length === 0 ? videoDisplay = 'aucun' : videoDisplay = videoHistory.join('<br>');
	emailHistory.length === 0 ? emailDisplay = 'aucun' : emailDisplay = emailHistory.join('<br>');

	document.getElementById("histo").innerHTML = '<table><tr><td>enregistrements vidéo:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>emails envoyés:</td></tr><tr><td>' + videoDisplay + '</td><td></td><td>' + emailDisplay + '</td></tr></table>';

	setTimeout(loadValues, 10000)
}

function displayDateAndTime() {
	let nowDate = new Date();
	let dateOptions = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
	let timeOptions = { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: false };
	// Date et heure formatées en français
	let formattedDate = new Intl.DateTimeFormat('fr-FR', dateOptions).format(nowDate);
	let formattedTime = new Intl.DateTimeFormat('fr-FR', timeOptions).format(nowDate);

	document.getElementById('heure').innerHTML = "<div class='moyenne'>" + formattedDate + "</div><div class='grande'>" + formattedTime + "</div>";
	setTimeout(displayDateAndTime, 1000)
}

document.addEventListener("DOMContentLoaded", function () {
	loadValues();
	displayDateAndTime();
});