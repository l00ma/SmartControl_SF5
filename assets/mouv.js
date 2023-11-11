import Highcharts from 'highcharts/highstock';
import accessibility from 'highcharts/modules/accessibility';

accessibility(Highcharts);

let refresh_graph = 5;
let allow_cam = 0;
let allow_alert = 0;

function loadDatas() {
	fetch('motion/load')
		.then(response => {
			if (!response.ok) {
				throw new Error('Erreur lors de la requête: HTTP code ' + response.status);
			}
			return response.json();
		})
		.then(data => {
			parseAndDisplay(data);
			graph(data);
		})
		.catch(error => {
			console.error('Erreur:', error);
			alert('Erreur lors de la lecture des données');
		});
}

function parseAndDisplay(data) {
	refresh_graph = data.refresh_graph;
	allow_cam = data.allow_cam;
	allow_alert = data.allow_alert;

	// valeur pour le rafraichissement du graph
	document.getElementById("refresh").value = refresh_graph;
	// valeur pour autoriser webcam
	document.getElementById("myenr_switch").checked = allow_cam;
	//valeur pour autoriser alerte
	document.getElementById("myalrt_switch").checked = allow_alert;
}

//action au click sur valeur rafraichissement graph
function changeRefreshValue() {
	refresh_graph = document.getElementById("refresh").value;
	saveDatas();
}

//action au click sur autoriser webcam
function allowCam() {
	allow_cam = document.getElementById("myenr_switch").checked ? 1 : 0;
	saveDatas();
}

//action au click sur autoriser alerte
function allowAlert() {
	allow_alert = document.getElementById("myalrt_switch").checked ? 1 : 0;
	saveDatas();
}

//fonction sauvegarde dans bdd
function saveDatas() {
	fetch('motion/save', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({
			'refresh': Number(refresh_graph),
			'cam': Number(allow_cam),
			'alert': Number(allow_alert)
		}),
	})
		.then(response => {
			if (!response.ok) {
				throw new Error('Erreur lors de la requête: HTTP code ' + response.status);
			}
			return response.json();
		})
		.then(data => {
			if (data.status === 'error') {
				alert(data.message);
			}
		})
		.catch(error => {
			console.error('Erreur:', error);
			alert('Erreur lors de la sauvegarde des données');
		});
}

//fonction trace le graph
function graph(data) {
	
	Highcharts.setOptions({
		lang: {
			months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			shortMonths: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jui', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
			weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			loading: 'Chargement...'
		},
		chart: {
			spacingBottom: 40,
			style: {
				fontFamily: 'Trebuchet, Arial, sans-serif',
				color: '#727272'
			}
		},
		title: {
			style: {
				color: '#727272'
			}
		},
		global: {
			useUTC: false
		},
		colors: ['#bb1142'],
	});

	Highcharts.stockChart('container_chart', {
		accessibility: {
			enabled: true
		},
		title: { text: 'Historique des détections sur le dernier mois' },
		exporting: 'false',
		xAxis: {
			gapGridLineWidth: 0,
			lineColor: '#727272'
		},
		plotOptions: {
			series: {
				dataGrouping: {
					dateTimeLabelFormats: {
						minute: ['%A %e %B, %H:%M', '%A %e %B, %H:%M', '-%H:%M'],
						hour: ['%A %e %B, %H:%M', '%A %e %B, %H:%M', '-%H:%M'],
						day: ['%A %e %B %Y', '%A %e %B', '-%A %e %B %Y'],
						week: ['Semaine du %A %e %B %Y', '%A %e %B', '-%A %e %B %Y'],
						month: ['%B %Y', '%B', '-%B %Y'],
						year: ['%Y', '%Y', '-%Y']
					},
				},
				lineWidth: 1,
				states: {
					hover: {
						enabled: true,
						lineWidth: 2 // largeur de la ligne quand hovered
					}
				}
			}
		},
		yAxis: { floor: 0 },
		rangeSelector: {
			buttonTheme: { // styles for the buttons
				fill: 'none',
				stroke: 'none',
				'stroke-width': 0,
				r: 8,
				states: {
					hover: {
					},
					select: {
						fill: '#ffe4e4',
					}
				}
			},
			buttons: [{
				type: 'hour',
				count: 1,
				text: '1h'
			}, {
				type: 'hour',
				count: 12,
				text: '12h'
			}, {
				type: 'day',
				count: 1,
				text: '1j'
			}, {
				type: 'day',
				count: 7,
				text: '7j'
			}, {
				type: 'day',
				count: 15,
				text: '15j'
			}, {
				type: 'all',
				count: 1,
				text: 'tout'
			}],
			// se positionne par defaut sur 1 jour
			selected: 2,
			inputEnabled: false
		},
		series: [{
			name: 'Niveau',
			type: 'areaspline',
			data: data.data_pir,
			gapSize: 0,
			tooltip: {
				valueDecimals: 0,
				xDateFormat: '%A %e %B à %H:%M'//format date dans l'infobulle (voir http://php.net/manual/en/function.strftime.php)
			},
			fillColor: {
				linearGradient: {
					x1: 0,
					y1: 0,
					x2: 0,
					y2: 1
				},
				stops: [
					[0, Highcharts.getOptions().colors[0]],
					[1, new Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
				]
			},
			threshold: 0
		}],
		navigator: {
			series: {
				color: '#bb1142',
				lineWidth: 1
			},
			xAxis: {
				lineColor: '#727272',
				dateTimeLabelFormats: { day: '%d/%m' } //format date dans le navigator
			}
		}
	});
}

// actions
document.addEventListener("DOMContentLoaded", function () {
	loadDatas();
	setInterval(function () { loadDatas(); }, refresh_graph * 60000);

	//action au click sur valeur rafraichissement graph
	document.getElementById("refresh").addEventListener("change", changeRefreshValue);

	//action au click sur autoriser webcam
	document.getElementById("myenr_switch").addEventListener("change", allowCam);

	//action au click sur autoriser alerte
	document.getElementById("myalrt_switch").addEventListener("change", allowAlert);
});