// fonctions

var refresh_graph = 200, allow_cam, allow_alert, value_alert;

function loadValues() {
	$.ajax({
		type: 'get',
		async: false,
		url: 'includes/load_mouv.php',
		dataType: 'json',
		success: function (result) {
			if (result === 'redirectUser') {
				window.location.href = '../index.php'
			} else {
				traiteEtAffiche(result);
			}
		}
	});
}

function traiteEtAffiche(data) {
	//refresh_graph = data[0];
	allow_cam = data[1];
	allow_alert = data[2];
	value_alert = data[3];

	//bouton retour home
	$('#menu').button({ icons: { primary: " ui-icon-home grey", text: false } });
	//bouton logout
	$('#logout').button({ icons: { primary: " ui-icon-closethick grey", text: false } });
	// valeur pour le rafraichissement du graph
	$("#refresh").val(refresh_graph);
	// valeur pour autoriser webcam
	if (allow_cam === '1') {
		$('#myenr_switch').prop('checked', true);

	}
	else {
		$('#myenr_switch').prop('checked', false);
		$('.recam_select').hide();
	}

	//valeur pour autoriser alerte
	if (allow_alert === '1') {
		$('#myalrt_switch').prop('checked', true);
	}
	else {
		$('#myalrt_switch').prop('checked', false);
		$('.alrt_select').hide();
	}
	//valeur pour declenchement alerte
	$('#alert').val(value_alert);
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
		$('.recam_select').fadeIn('slow');
	}
	else {
		allow_cam = 0;
		$('.recam_select').fadeOut('slow');
	}
	saveValues();
}

//action au click sur autoriser alerte
function allowAlert() {
	if ($('#myalrt_switch').is(':checked')) {
		allow_alert = 1;
		$('.alrt_select').fadeIn('slow');
	}
	else {
		allow_alert = 0;
		$('.alrt_select').fadeOut('slow');
	}
	saveValues();
}
//action au click sur valeur declenchement alerte
function changeAlertValue() {
	value_alert = $('#alert').val();
	saveValues();
}
//fonction sauvegarde dans bdd
function saveValues() {
	$.ajax({
		type: 'get',
		url: 'includes/save_mouv.php',
		data: { save: refresh_graph + ',' + allow_cam + ',' + allow_alert + ',' + value_alert },
		success: function (result) {
			if (result === 'redirectUser') {
				window.location.href = '../index.php'
			}
		}
	});
}
//fonction trace le graph
function graph() {
	$.ajax({
		type: 'get',
		//async: false,
		url: '/datas/pir_sensor.data.orig',
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
			$('#container').highcharts('StockChart', {
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
	//loadValues();
	console.log("executer");
	graph();
	setInterval(function () { graph(); }, refresh_graph * 60000);

	//boutons en haut à droite
	$('#menu').click(function () {
		location.href = 'welcome.php';
	});
	$('#logout').click(function () {
		location.href = 'includes/logout.php';
	});

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
