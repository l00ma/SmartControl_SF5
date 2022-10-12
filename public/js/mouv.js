// fonctions

var refresh_graph = 5, allow_cam = 0, allow_alert = 0;

function loadValues() {

	$.ajax({
		type: 'post',
		url: 'motion/load',
		dataType: 'json',
		success: function (result) {
			traiteEtAffiche(result);
		},
		error: function () {
			alert('erreur lors du chargement des paramètres.');
		}
	});
}

function traiteEtAffiche(data) {
	refresh_graph = data['refresh_graph'];
	allow_cam = data['allow_cam'];
	allow_alert = data['allow_alert'];
	//$("#lieu").html('<span class=\'donnees_meteo\'>' + refresh_graph + ' ' + allow_cam + ' ' + allow_alert + '</span>');

	// valeur pour le rafraichissement du graph
	$("#refresh").val(refresh_graph);
	// valeur pour autoriser webcam
	if (allow_cam == '1') {
		$('#myenr_switch').prop('checked', true);
	}
	else {
		$('#myenr_switch').prop('checked', false);
	}

	//valeur pour autoriser alerte
	if (allow_alert == '1') {
		$('#myalrt_switch').prop('checked', true);
	}
	else {
		$('#myalrt_switch').prop('checked', false);
	}
}
//action au click sur valeur rafraichissement graph
function changeRefreshValue() {
	refresh_graph = $('#refresh').val();
	saveValues();
}
//action au click sur autoriser webcam
function allowCam() {
	if ($('#myenr_switch').is(':checked')) {
		allow_cam = 1;
	}
	else {
		allow_cam = 0;
	}
	saveValues();
}

//action au click sur autoriser alerte
function allowAlert() {
	if ($('#myalrt_switch').is(':checked')) {
		allow_alert = 1;
	}
	else {
		allow_alert = 0;
	}
	saveValues();
}

//fonction sauvegarde dans bdd
function saveValues() {
	$.ajax({
		type: 'post',
		url: 'motion/save',
		dataType: 'json',
		data: { 'refresh': refresh_graph, 'cam': allow_cam, 'alert': allow_alert },
		success: function (response) {
			if (response.status === 'error') {
				alert(response.message);
			}
		}
	});
}
//fonction trace le graph
function graph() {
	$.ajax({
		type: 'get',
		url: '/datas/pir_sensor.data',
		dataType: 'json',
		success: function (data) {
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
			$('#container_chart').highcharts('StockChart', {
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
					// se positionne pa defaut sur 12h
					selected: 1,
					inputEnabled: false
				},
				series: [{
					name: 'Niveau',
					type: 'areaspline',
					data: data,
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
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
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
		},
		error: function (err) {
			console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
		}
	});
}

// actions
$(document).ready(function () {
	loadValues();
	graph();
	setInterval(function () { graph(); }, refresh_graph * 60000);

	//action au click sur valeur rafraichissement graph
	$('#refresh').on('change', function () {
		changeRefreshValue();

	});

	//action au click sur autoriser webcam
	$('#myenr_switch').on('change', function () {
		allowCam();
	});

	//action au click sur valeur declenchement webcam
	$('.recam_select').on('change', function () {
		changeWebcamValue();
	});

	//action au click sur autoriser alert
	$('#myalrt_switch').on('change', function () {
		allowAlert();
	});

	//action au click sur valeur declenchement alerte
	$('.alrt_select').on('change', function () {
		changeAlertValue();
	});
});