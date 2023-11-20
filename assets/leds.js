import 'clockpicker/dist/jquery-clockpicker';
import './styles/leds.scss';

let red, green, blue, etat, rgbString, debut_time, fin_time, effet;
let email = false;
const leds = 'leds/save';
const timer = 'leds/timer';

function loadDatas() {
	fetch('leds/load')
		.then(response => {
			if (!response.ok) {
				throw new Error('Erreur lors de la requête: HTTP code ' + response.status);
			}
			return response.json();
		})
		.then(data => {
			[red, green, blue] = data[0].split(',').map(Number);
			[etat, debut_time, fin_time, email, effet] = data.slice(1);
			InitTimerValues();
			display();
		})
		.catch(error => {
			console.error('Erreur:', error);
			alert('Erreur lors de la lecture des données');
		});
}

async function saveDatas(url, data){
	try {
		await fetch( url, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(data),
		});

	} catch (error) {
		console.error("Erreur :", error);
	}
}

function display() {

	document.getElementById("myonoffswitch").checked = etat;

	document.getElementById("rSlider").value = red;
	document.getElementById("gSlider").value = green;
	document.getElementById("bSlider").value = blue;
	rgbString = formRGB(red, green, blue);
	document.getElementById( 'colorBox' ).style.backgroundColor = "rgb(" + rgbString + ")";

	document.getElementById("effet" + effet).checked = true;

	if (debut_time !== '0') {
		document.getElementById('controlbtn').style.display = 'none';
		document.getElementById("list-timer").style.display = '';
		document.getElementById('heure_deb').innerHTML = debut_time;
		document.getElementById('heure_fin').innerHTML = fin_time;
		document.getElementById("email").checked = email;
	}
	else {
		document.getElementById('controlbtn').style.display = '';
		document.getElementById("list-timer").style.display = 'none';
		document.getElementById('heure_deb').innerHTML = '';
		document.getElementById('heure_fin').innerHTML = '';
	}
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

function storeTimer() {
	//on verifie le format de l'heure et minutes
	var regexTime = /^([01]\d|2[0-3]):([0-5]\d)$/;
	email = false;
	if (regexTime.test(document.getElementById('debut_time').value) && regexTime.test(document.getElementById('fin_time').value)) {
		debut_time = document.getElementById('debut_time').value;
		fin_time = document.getElementById('fin_time').value;
		// on initialise les valeurs
		const data = {
			'h_on': debut_time,
			'h_off': fin_time,
			'timer': true,
			'email': false
		};
		saveDatas(timer, data);
	}
	else {
		alert('Le format saisi n\'est pas valide!');
	}
	display();
}

function InitTimerValues() {
	document.getElementsByClassName('timepicker_deb').disabled = false;
	document.getElementsByClassName('timepicker_fin').disabled = true;
}

function eraseTimer() {
	// on initialise les valeurs
	debut_time = '0';
	fin_time = '0';
	email = false;
	const data = {
		'h_on': debut_time,
		'h_off': fin_time,
		'timer': false,
		'email': email
	};
	//on sauvegarde les valeurs
	saveDatas(timer, data);
	display();
}

function formRGB(r, g, b) {
	return r + ',' + g + ',' + b;
}

function refreshOnoff() {
	etat =  document.getElementById("myonoffswitch").checked;
	rgbString = formRGB(red, green, blue);
	saveDatas(leds, {
		'rgb': rgbString,
		'etat': etat,
		'email': email,
		'effet': Number(effet)
	});
}

function refreshAll() {
	etat = document.getElementById("myonoffswitch").checked;
	saveDatas(leds, {
		'rgb': rgbString,
		'etat': etat,
		'email': email,
		'effet': Number(effet)
	});
}

//action au chargement de la page
document.addEventListener("DOMContentLoaded", function () {
	initiTimePicker();
	loadDatas();

	//action au click sur on/off
	document.getElementById("myonoffswitch").addEventListener("change", refreshOnoff);

	//action sur les sliders RGB
	let sliders = document.querySelectorAll('#rSlider, #gSlider, #bSlider');
	sliders.forEach(function (slider) {
		// Pendant le deplacement du curseur
		slider.addEventListener('input', function () {
			red = document.getElementById('rSlider').value;
			green = document.getElementById('gSlider').value;
			blue = document.getElementById('bSlider').value;
			display();
		});
		// Apres le deplacement du curseur
		slider.addEventListener('change', function () {
			red = document.getElementById('rSlider').value;
			green = document.getElementById('gSlider').value;
			blue = document.getElementById('bSlider').value;
			refreshAll();
		});
	});

	//action des boutons radio 'Effet'
	let checkBoxes = document.querySelectorAll('#effet1, #effet2, #effet3, #effet4, #effet5, #effet6, #effet0');
	checkBoxes.forEach(function (checkBox) {
		checkBox.addEventListener('click', function () {
			if(this.checked) {
				effet = this.getAttribute('num');
				rgbString = formRGB(red, green, blue);
				saveDatas(leds, {
					'rgb': rgbString,
					'etat': etat,
					'email': email,
					'effet': Number(effet)
				});
			}
		});
	});

	//action radiobutton email
	document.getElementById('email').addEventListener('click', function () {
		email = document.getElementById('email').checked;
		rgbString = formRGB(red, green, blue);
		saveDatas(leds, {
			'rgb': rgbString,
			'etat': etat,
			'email': email,
			'effet': Number(effet)
		});
	});

	//action button effacer
	document.getElementById("but_efface").addEventListener("click", [initiTimePicker, InitTimerValues]);

	//action button enregistrer
	document.getElementById("but_enregistre").addEventListener("click", storeTimer);

	//action button eraser (efface l'enregistrement)
	document.getElementById('icon_eraser').addEventListener('click', function () {
		eraseTimer();
	});
});