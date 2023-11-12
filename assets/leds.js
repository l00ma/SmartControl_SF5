import 'clockpicker/dist/jquery-clockpicker';
import './styles/leds.scss';

let red, green, blue, etat, rgbString, debut_time, fin_time, effet;
let email = false;
function loadDatas() {
	fetch('leds/load')
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

function saveDatas() {
	fetch('leds/save', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({
			'rgb': rgbString,
			'etat': etat,
			'email': email,
			'effet': Number(effet)
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

function parseAndDisplay(data) {

	[red, green, blue] = data[0].split(',').map(Number);
	[etat, debut_time, fin_time, email, effet] = data.slice(1);

	InitTimerValues();
	document.getElementById("effet" + effet).checked = true;

	if (debut_time !== '0') {
		document.getElementById('heure_deb').innerHTML = debut_time;
		document.getElementById('heure_fin').innerHTML = fin_time;

		document.getElementById("email").checked = email;
		document.getElementById("list-timer").style.visibility = 'visible';
	}
	else {
		document.getElementById("list-timer").style.visibility = 'hidden';
	}
	document.getElementById("myonoffswitch").checked = etat;

	document.getElementById("rSlider").value = red;
	document.getElementById("gSlider").value = green;
	document.getElementById("bSlider").value = blue;

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
		});
	});

	$(function () {
		$('.timepicker_fin').clockpicker({
			placement: 'top',
			align: 'right',
			'default': 'now',
			autoclose: true,
		});
	});
}

function saveTimerData(data){
	fetch('leds/timer', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify(data),
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

function storeTimer() {
	//on verifie le format de l'heure et minutes
	var regexTime = /^([01]\d|2[0-3]):([0-5]\d)$/;
	email = false;
	if (regexTime.test(document.getElementById('debut_time').value) && regexTime.test(document.getElementById('fin_time').value)) {
		debut_time = document.getElementById('debut_time').value;
		fin_time = document.getElementById('fin_time').value;
		// on initialise les valeurs
		let data = {
			'h_on': debut_time,
			'h_off': fin_time,
			'timer': true,
			'email': false
		};
		//on sauvegarde les valeurs
		saveTimerData(data)

		document.getElementById("list-timer").style.visibility = 'visible';
		document.getElementById("heure_deb").innerHTML = debut_time;
		document.getElementById("heure_fin").innerHTML = fin_time;

		$('#icon_eraser').button({ icons: { primary: 'ui-icon-trash' }, text: false });
		document.getElementById("email").checked = email;
		InitTimerValues();
	}
	else {
		alert('Le format saisi n\'est pas valide!');
		InitTimerValues();
	}
}

function InitTimerValues() {
	document.getElementById('debut_time').value = '';
	document.getElementById('fin_time').value = '';
	document.getElementsByClassName('timepicker_deb').disabled = false;
	document.getElementsByClassName('timepicker_fin').disabled = true;
}

function eraseTimer() {
	document.getElementById("list-timer").style.visibility = 'hidden';
	// on initialise les valeurs
	let data = {
		'h_on': '0',
		'h_off': '0',
		'timer': false,
		'email': false
	};
	//on sauvegarde les valeurs
	saveTimerData(data);
}

function formRGB(r, g, b) {
	return r + ',' + g + ',' + b;
}

function refreshOnoff() {
	etat =  document.getElementById("myonoffswitch").checked;
	rgbString = formRGB(red, green, blue);
	saveDatas();
}

function refreshSwatch() {
	rgbString = formRGB(red, green, blue);
	$('#colorBox').css('background-color', 'rgb(' + rgbString + ')');
}

function refreshAll() {
	etat = document.getElementById("myonoffswitch").checked;
	saveDatas();
}


//actions=====================================

//action au chargement de la page
document.addEventListener("DOMContentLoaded", function () {
	initiTimePicker();
	loadDatas();

	//action au click sur on/off
	document.getElementById("myonoffswitch").addEventListener("change", refreshOnoff);

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
	$(document).on('click touchend', '#effet1, #effet2, #effet3, #effet4, #effet5, #effet6, #effet0', function () {
		if ($(this).is(':checked')) {
			effet = $(this).attr('num');
			rgbString = formRGB(red, green, blue);
			saveDatas();
		}
	});

	//action radiobutton email
	$('#email').click(function () {
		if ($('#email').is(':checked')) { email = true; }
		else { email = false; }
		rgbString = formRGB(red, green, blue);
		saveDatas();
	});

	//action button effacer
	document.getElementById("but_efface").addEventListener("click", [initiTimePicker, InitTimerValues]);

	//action button enregistrer
	document.getElementById("but_enregistre").addEventListener("click", storeTimer);

	//action button eraser (efface l'enregistrement)
	$('#icon_eraser').click(function () {
		email = false;
		eraseTimer();
	});
});