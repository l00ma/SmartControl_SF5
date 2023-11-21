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

	data.etat ? document.getElementById("etat_leds").innerHTML = '<span class="gadget_allume">Leds allumées</span>' : document.getElementById("etat_leds").innerHTML = 'Leds éteintes';

	if (data.enreg === 1 || data.alert === 1) {
		document.getElementById("etat_detection").innerHTML = '<span class="gadget_allume">Détection activée</span>';
		data.enreg_detect === 1 ? document.getElementById("etat_alerte").innerHTML = '<span class="alerte_allume">Alerte en cours</span>' : document.getElementById("etat_alerte").innerHTML = 'Pas d\'alerte';
	}
	else {
		document.getElementById("etat_detection").innerHTML = 'Détection désactivée';
		document.getElementById("etat_alerte").innerHTML = '';
	}

	document.getElementById("lieu").innerHTML = '<span class="moyenne">' + data.location + '</span>';
	document.getElementById("previsions").innerHTML = '<div class="container mx-2 text-center"><div class="row"><div class="col grande text-danger"><i class="wi ' + data.icon_id + '"></i></div><div class="col moyenne"><i class="wi ' + data.icon_f1 + '" title="' + data.weather_f1 + '"></i></div><div class="col moyenne"><i class="wi ' + data.icon_f2 + '" title="' + data.weather_f2 + '"></i></div><div class="col moyenne"><i class="wi ' + data.icon_f3 + '" title="' + data.weather_f3 + '"></i></div></div><div class="row"><div class="col petite">' + data.weather + '</div><div class="col petite">' + data.temp_f1 + '°c</div><div class="col petite">' + data.temp_f2 + '°c</div><div class="col petite">' + data.temp_f3 + '°c</div></div><div class="row"><div class="col petite"></div><div class="col petite">' + data.time_f1 + '</div><div class="col petite">' + data.time_f2 + '</div><div class="col petite">' + data.time_f3 + '</div></div></div>';

	document.getElementById("temp").innerHTML = '<div class="container me-4 text-center"> <div class="row"> <div class="col moyenne text-end" title="température"> <i class="wi wi-thermometer"></i> </div> <div class="col" title="temp bas:' + data.temp_bas + '"><span class="petite">ext </span><span class="moyenne">' + data.temp_ext + '</span><span class="petite"> °c</span> </div> <div class="col"> <span class="petite">int </span><span class="moyenne">' + data.temp_int + '</span><span class="petite"> °c</span> </div> </div> <div class="row"> <div class="col-4 moyenne text-end" title="humidité"> <i class="wi wi-humidity"></i> </div> <div class="col-8"> <span class="moyenne" title="humidité">' + data.humidite + '</span><span class="petite"> %</span> </div> </div> <div class="row"> <div class="col-4 moyenne text-end" title="pression"> <i class="wi wi-barometer"></i> </div> <div class="col-8"> <span class="moyenne" title="pression">' + data.pression + '</span><span class="petite"> Hpa</span> </div> </div> <div class="row"> <div class="col moyenne text-end" title="vent"> <i class="wi wi-strong-wind"></i> </div> <div class="col text-end"> <span class="moyenne" title="vent">' + data.vitesse_vent + '</span><span class="petite"> km/h</span> </div> <div class="col text-start"> <span class="moyenne">' + data.direction_vent + '</span> </div> </div> <div class="row"> <div class="col text-end"> <i class="wi wi-sunrise" title="heure de levé du soleil"></i><span class="moyenne">' + data.leve_soleil + '</span> </div> <div class="col text-start"> <i class="wi wi-sunset" title="heure de couché du soleil"></i><span class="moyenne">' + data.couche_soleil + '</span> </div> </div> </div>';

	let videoDisplay, emailDisplay;
	videoHistory.length === 0 ? videoDisplay = 'aucun' : videoDisplay = videoHistory.join('<br>');
	emailHistory.length === 0 ? emailDisplay = 'aucun' : emailDisplay = emailHistory.join('<br>');

	document.getElementById("histo").innerHTML = '<div class="container text-center"> <div class="row"> <div class="col"> enregistrements vidéo: </div> <div class="col"> emails envoyés: </div> </div> <div class="row"> <div class="col"> ' + videoDisplay + '</div> <div class="col"> ' + emailDisplay + '</div> </div> </div>';

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