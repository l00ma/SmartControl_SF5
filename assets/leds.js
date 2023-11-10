import 'clockpicker/dist/jquery-clockpicker';
import './styles/leds.scss';

let red, green, blue, etat, rgbString, debut_time, fin_time, email, effet;

function loadValues() {
	$.ajax({
		type: 'get',
		async: false,
		url: 'leds/load',
		dataType: 'json',
		success: function (result) {
			traiteEtAffiche(result);
		},
		error: function () {
			alert('erreur lors du chargement des paramètres.');
		}
	});
}

function saveValues() {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: 'leds/save',
		data: { 'rgb': rgbString, 'etat': etat, 'email': email, 'effet': effet },
		success: function (response) {
			if (response.status === 'error') {
				alert(response.message);
			}
		}
	});
}

function traiteEtAffiche(data) {
	rgbString = data[0];
	let rgb = rgbString.split(',');
	red = parseInt(rgb[0]);
	green = parseInt(rgb[1]);
	blue = parseInt(rgb[2]);
	etat = data[1];
	debut_time = data[2];
	fin_time = data[3];
	email = data[4];
	effet = data[5];

	InitTimerValues();
	if (effet == '0') {
		$('#effetstop').prop('checked', true);
	}
	else {
		$("#effet" + effet).prop('checked', true);
	}
	if (debut_time != '0') {
		$('#heure_deb').html(debut_time);
		$('#heure_fin').html(fin_time);
		if (email == '0') {
			$('#email').prop('checked', false);
		}
		else {
			$('#email').prop('checked', true);
		}
		$('#list-timer').show();
	}
	else {
		$('#list-timer').hide();
	}
	if (etat === 'true') {
		$('#myonoffswitch').prop('checked', true);
	}
	else {
		$('#myonoffswitch').prop('checked', false);
	}

	$('#rSlider').val(red);
	$('#gSlider').val(green);
	$('#bSlider').val(blue);

	rgbString = formRGB(red, green, blue);
	$('#colorBox').css('background-color', 'rgb(' + rgbString + ')');

	$('#timepicker_fin').prop('disabled', false);

}

function initiTimePicker() {
	$(function () {
		$('.timepicker_deb').clockpicker({
			placement: 'bottom',
			align: 'right',
			'default': 'now',
			autoclose: true,
			afterDone: function () {
				if ($('#debut_time').val().length > 0) {
					$('.timepicker_fin').prop('disabled', false);
					$('.timepicker_deb').prop('disabled', true);
				}
			}
		});
	});


	$(function () {
		$('.timepicker_fin').clockpicker({
			placement: 'bottom',
			align: 'right',
			'default': 'now',
			autoclose: true,
		});
	});
}



function storeTimer() {
	//on verifie le format de l'heure et minutes
	if (/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/.test($('#debut_time').val()) && /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/.test($('#fin_time').val())) {
		debut_time = $('#debut_time').val();
		fin_time = $('#fin_time').val();

		//ici on peut demander a timer.php de stocker ds la bd puis d'agir
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: 'leds/timer',
			data: { 'h_on': debut_time, 'h_off': fin_time },
			success: function (response) {
				if (response.status === 'error') {
					alert(response.message);
				}
			}
		});
		initiTimePicker();
		$('#list-timer').show();
		$('#heure_deb').html(debut_time);
		$('#heure_fin').html(fin_time);
		$('#icon_eraser').button({ icons: { primary: 'ui-icon-trash' }, text: false });
		if (email == '0') {
			$('#email').prop('checked', false);
		}
		else {
			$('#email').prop('checked', true);
		}
		InitTimerValues();
	}
	else {
		alert('Le format saisi n\'est pas valide!');
		InitTimerValues();
	}
}

function InitTimerValues() {
	$('#debut_time').val('');
	$('#fin_time').val('');
	$('.timepicker_deb').prop('disabled', false);
	$('.timepicker_fin').prop('disabled', true);
}

function eraseTimer() {
	$('#list-timer').hide();
	//ici on peut demander a timer.php de vider la bd puis d'agir
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: 'leds/timer',
		data: { 'h_on': '0', 'h_off': '0' },
		success: function (response) {
			if (response.status === 'error') {
				alert(response.message);
			}
		}
	});
}

function formRGB(r, g, b) {
	let text = r + ',' + g + ',' + b;
	return text;
}

function refreshOnoff() {
	etat = $('#myonoffswitch').is(':checked');
	rgbString = formRGB(red, green, blue);
	saveValues();
}

function refreshSwatch() {
	rgbString = formRGB(red, green, blue);
	$('#colorBox').css('background-color', 'rgb(' + rgbString + ')');
}

function refreshAll() {
	etat = $('#myonoffswitch').is(':checked');
	saveValues();
}


//actions=====================================

//action au chargement de la page
document.addEventListener("DOMContentLoaded", function () {
	initiTimePicker();
	loadValues();

	//action au click sur on/off
	$('#myonoffswitch').on('change', function () {
		refreshOnoff();
	});

	//action sur les sliders RGB pendant le deplacement du curseur
	$(document).on('input', '#rSlider, #gSlider, #bSlider', function () {
		red = $('#rSlider').val();
		green = $('#gSlider').val();
		blue = $('#bSlider').val();
		refreshSwatch();
	});

	//action sur les sliders RGB apres le deplacement du curseur
	$(document).on('change', '#rSlider, #gSlider, #bSlider', function () {
		red = $('#rSlider').val();
		green = $('#gSlider').val();
		blue = $('#bSlider').val();
		refreshAll();
	});

	//action des boutons radio 'Effet' 
	$(document).on('click touchend', '#effet1, #effet2, #effet3, #effet4, #effet5, #effet6, #effetstop', function () {
		if ($(this).is(':checked')) {
			effet = $(this).attr('num');
			rgbString = formRGB(red, green, blue);
			saveValues();
		}
	});

	//action radiobutton email
	$('#email').click(function () {
		if ($('#email').is(':checked')) { email = 1; }
		else { email = 0; }
		rgbString = formRGB(red, green, blue);
		saveValues();
	});

	//action button effacer
	$('#but_efface').click(function () {
		initiTimePicker();
		InitTimerValues();
	});

	//action button enregistrer
	$('#but_enregistre').click(function () {
		storeTimer();
	});

	//action button eraser (efface l'enregistrement)
	$('#icon_eraser').click(function () {
		eraseTimer();
	});
});