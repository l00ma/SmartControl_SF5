(function($){

var red, green, blue, etat, rgbString, debut_time, fin_time, email, effet ;
  
function loadValues () {
	$.ajax({ 
		type:'get', 
		async: false,
		url: 'includes/load_rgb.php',
		dataType: 'json',
		success: function(result){
			if(result === 'redirectUser') {
                             window.location.href = '../index.php'
                        }else {
                            traiteEtAffiche(result);
                        }
		}
	});
}
  
function traiteEtAffiche(data){ 
	var rgb = data[0].split(',');
	red = parseInt(rgb[0]);
	green = parseInt(rgb[1]);
	blue = parseInt(rgb[2]);
	etat = data[1];
	debut_time = data[2];
	fin_time = data[3];
	email = data [4];
	effet = data[5];
	
	$('#myTabs').tabs();
	if (effet === '0') {
		$('#effetstop').prop('checked', true);
	}
	else {
		$("#effet"+effet).prop('checked', true);
	}
	if (debut_time.length > 0) {
		$('#heure_deb').html(debut_time);
		$('#heure_fin').html(fin_time);
		$('#icon_eraser').button({icons: {primary: 'ui-icon-trash'}, text: false});
		if (email === '0') {
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
		$('#myTabs').tabs('option', 'disabled', [] );
	} 
	else { 
		$('#myonoffswitch').prop('checked', false);
		$('#myTabs').tabs('option', 'active', 0 );
	}
	
	rgbString = formRGB( red, green, blue );
	$('#colorBox').css('background-color', rgbString);
	
	//sliders r v b
	$( '#rSlider').slider({
		min: 0,
		max: 255,
		value: red,
		animate: 'slow',
		slide: refreshSwatch,
		change: refreshAll
	});
	$( '#gSlider').slider({
		min: 0,
		max: 255,
		value: green,
		animate: 'slow',
		slide: refreshSwatch,
		change: refreshAll
	});
	$( '#bSlider').slider({
		min: 0,
		max: 255,
		value: blue,
		animate: 'slow',
		slide: refreshSwatch,
		change: refreshAll
	});
}

function initiTimePicker() {
	
	$('#timepicker_deb').timepicker({
		hourText: 'Heures',
		minuteText: 'Minutes',
		amPmText: ['AM', 'PM'],
		timeSeparator: 'h',
		nowButtonText: 'Maintenant',
		showNowButton: true,
		closeButtonText: 'Fermer',
		showCloseButton: true,
		deselectButtonText: 'Désélectionner',
		showDeselectButton: true,

		onClose: function(time, inst) {
			if ($('#timepicker_deb').val().length > 0) {
				$('#timepicker_fin').prop('disabled', false);
				$('#timepicker_deb').prop('disabled', true);
			}
            //alert ('onSelect triggered with time : ' + time + ' for instance id : ' + inst.id);
         }
	});
		$('#timepicker_fin').timepicker({
		hourText: 'Heures',
		minuteText: 'Minutes',
		amPmText: ['AM', 'PM'],
		timeSeparator: 'h',
		showNowButton: false,
		closeButtonText: 'Fermer',
		showCloseButton: true,
		deselectButtonText: 'Désélectionner',
		showDeselectButton: true,
			
		onClose: function(time, inst) {
			if ($('#timepicker_deb').val().length > 0 &&  $('#timepicker_fin').val().length > 0) {
				$('#timepicker_fin').prop('disabled', true);
				$('#but_enregistre').prop('disabled', false);
			}
            //log_event('onSelect triggered with time : ' + time + ' for instance id : ' + inst.id);
         }
	});
	$('#timepicker_deb').val();
	$('#timepicker_fin').val();
	$('#timepicker_deb').prop('disabled', false);
	$('#timepicker_fin').prop('disabled', true);
	$('#but_enregistre').prop('disabled', true);
}



function storeTimer() {
		//on verifie le format de l'heure et minutes
	if ( /^([0-1]?[0-9]|2[0-3])h([0-5][0-9])(:[0-5][0-9])?$/.test($('#timepicker_deb').val()) && /^([0-1]?[0-9]|2[0-3])h([0-5][0-9])(:[0-5][0-9])?$/.test($('#timepicker_fin').val()) ) {
		debut_time = $('#timepicker_deb').val();
		fin_time = $('#timepicker_fin').val();
		
		//ici on peut demander a timer.php de stocker ds la bd puis d'agir
		$.ajax({ 
			type:'get',
			url: 'includes/timer.php',
			data: {debut: debut_time , fin: fin_time},
			success: function(result){
			if(result === 'redirectUser') {
                             window.location.href = '../index.php'
                        }
		}
		});
		initiTimePicker();
		$('#list-timer').show();
		$('#heure_deb').html(debut_time);
		$('#heure_fin').html(fin_time);
		$('#icon_eraser').button({icons: {primary: 'ui-icon-trash'}, text: false });
		if (email === '0') {
			$('#email').prop('checked', false);
		}
		else {
			$('#email').prop('checked', true);
		}
		InitTimerValues();
	}
	else {
		alert ('Le format saisi n\'est pas valide!');
		InitTimerValues();
	}
}

function InitTimerValues() {
	$('#timepicker_deb').val(null);
	$('#timepicker_fin').val(null);
	$('#timepicker_deb').prop('disabled', false);
	$('#timepicker_fin').prop('disabled', true);
}
 
function eraseTimer() {
	$('#list-timer').hide();
	//ici on peut demander a timer.php de vider la bd puis d'agir
	$.ajax({ 
		type:'get',
		url: 'includes/timer.php',
		success: function(result){
			if(result === 'redirectUser') {
                             window.location.href = '../index.php'
                        }
		}
	});
}
  
function formRGB(r, g, b) {
    var text = 'rgb(' + r + ',' + g + ',' + b + ')';
    return text;
}
  
function refreshOnoff() {
	etat = $('#myonoffswitch').is(':checked');
	$.ajax({
		type:'get',
		url: 'includes/save_rgb.php',
		data: {save: red + ',' + green + ',' + blue + ',' + etat + ',' + email + ',' + effet },
		success: function(result){
			if(result === 'redirectUser') {
                             window.location.href = '../index.php'
                        }
		}
	});
}
  
function refreshSwatch() {
    red = $('#rSlider').slider('value'),
    green = $('#gSlider').slider('value'),
    blue = $('#bSlider').slider('value'),
    rgbString = formRGB( red, green, blue );
    $('#colorBox').css('background-color', rgbString);
}
  
function refreshAll() {
    etat = $('#myonoffswitch').is(':checked');
    red = $('#rSlider').slider('value'),
    green = $('#gSlider').slider('value'),
    blue = $('#bSlider').slider('value'),
    rgbString = formRGB( red, green, blue );
	$('#colorBox').css('backgroundColor', rgbString );
    $.ajax({ 
		type:'get',
		url: 'includes/save_rgb.php',
		data: {save: red + ',' + green + ',' + blue + ',' + etat + ',' + email + ',' + effet },
		success: function(result){
			if(result === 'redirectUser') {
                             window.location.href = '../index.php'
                        }
		}
	});
}



//actions=====================================
	
//action au chargement de la page
$(document).ready (function () {
	initiTimePicker();
	loadValues();
});

//action au click sur on/off
$('#myonoffswitch').on('change', function() {
	refreshOnoff();
});

//action du bouton radio 'Effet1' 
$('#effet1').click(function() {
	if ( $('#effet1').is( ':checked' )) {
		effet = 1;
		$.ajax({
			type:'get',
			url: 'includes/save_rgb.php',
			data: {save: red + ',' + green + ',' + blue + ',' + etat + ',' + email + ',' + effet },
			success: function(result){
				if(result === 'redirectUser') {
								 window.location.href = '../index.php'
				}
			}
		});
	}
});


//action du bouton radio 'Effet2' 
$('#effet2').click(function() {
	if ( $('#effet2').is( ':checked' )) {
		effet = 2;
		$.ajax({
			type:'get',
			url: 'includes/save_rgb.php',
			data: {save: red + ',' + green + ',' + blue + ',' + etat + ',' + email + ',' + effet },
			success: function(result){
				if(result === 'redirectUser') {
								 window.location.href = '../index.php'
				}
			}
		});
	}
});
	
//action du bouton radio 'Effet3'
$('#effet3').click(function() {
	if ( $('#effet3').is( ':checked' )) {
		effet = 3;
		$.ajax({
			type:'get',
			url: 'includes/save_rgb.php',
			data: {save: red + ',' + green + ',' + blue + ',' + etat + ',' + email + ',' + effet },
			success: function(result){
				if(result === 'redirectUser') {
								 window.location.href = '../index.php'
				}
			}
		});;
	}
});

//action du bouton radio 'Effet4'
$('#effet4').click(function() {
	if ( $('#effet4').is( ':checked' )) {
		effet = 4;
		$.ajax({
			type:'get',
			url: 'includes/save_rgb.php',
			data: {save: red + ',' + green + ',' + blue + ',' + etat + ',' + email + ',' + effet },
			success: function(result){
				if(result === 'redirectUser') {
								 window.location.href = '../index.php'
				}
			}
		});
	}
});

//action du bouton radio 'Effet5' 
$('#effet5').click(function() {
	if ( $('#effet5').is( ':checked' )) {
		effet = 5;
		$.ajax({
			type:'get',
			url: 'includes/save_rgb.php',
			data: {save: red + ',' + green + ',' + blue + ',' + etat + ',' + email + ',' + effet },
			success: function(result){
				if(result === 'redirectUser') {
								 window.location.href = '../index.php'
				}
			}
		});
	}
});

//action du bouton radio 'Effet6' 
$('#effet6').click(function() {
	if ( $('#effet6').is( ':checked' )) {
		effet = 6;
		$.ajax({
			type:'get',
			url: 'includes/save_rgb.php',
			data: {save: red + ',' + green + ',' + blue + ',' + etat + ',' + email + ',' + effet },
			success: function(result){
				if(result === 'redirectUser') {
								 window.location.href = '../index.php'
				}
			}
		});
	}
});

//action du bouton radio 'Stop' 
$('#effetstop').click(function() {
	if ( $('#effetstop').is( ':checked' )) {
		effet = 0;
		$.ajax({
			type:'get',
			url: 'includes/save_rgb.php',
			data: {save: red + ',' + green + ',' + blue + ',' + etat + ',' + email + ',' + effet },
			success: function(result){
				if(result === 'redirectUser') {
								 window.location.href = '../index.php'
				}
			}
		});
	}
});

//action radiobutton email
$('#email').click(function() {
	if ($('#email').is( ':checked' )) { email = 1;}
	else { email = 0;}
		$.ajax({
			type:'get',
			url: 'includes/save_rgb.php',
			data: {save: red + ',' + green + ',' + blue + ',' + etat + ',' + email + ',' + effet },
			success: function(result){
				if(result === 'redirectUser') {
								 window.location.href = '../index.php'
				}
			}
		});

});



//action button effacer
$('#but_efface').click(function() {
	initiTimePicker();
	InitTimerValues();
});

//action button enregistrer
$('#but_enregistre').click(function() {
	storeTimer();
});

//action button eraser (efface l'enregistrement)
$('#icon_eraser').click(function() {
	eraseTimer();
});

//action button deconnecter
$('#but_logout').click(function() {
	location.href='includes/logout.php';
});
//action button menu
$('#but_menu').click(function() {
	location.href='welcome.php';
});

})( jQuery );