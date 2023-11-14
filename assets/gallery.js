import JSZip from "jszip";
import JSZipUtils from "jszip-utils";


var espace_total, espace_dispo, taux_utilisation;

function loadValues() {
	$.ajax({
		type: 'get',
		async: false,
		url: 'gallery/discusage',
		dataType: 'json',
		success: function (result) {
			traiteEtAffiche(result);
		},
		error: function () {
			alert('erreur lors du chargement des paramètres.');
		}
	});
}

//fonction zip
function deferredAddZip(url, filename, zip) {
	var deferred = $.Deferred();
	JSZipUtils.getBinaryContent(url, function (err, data) {
		if (err) {
			deferred.reject(err);
		} else {
			zip.file(filename, data, { binary: true });
			deferred.resolve(data);
		}
	});
	return deferred;
}
function traiteEtAffiche(data) {
	espace_total = data[0];
	espace_dispo = data[1];
	taux_utilisation = data[2];
	$("#bar").html('<div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="' + taux_utilisation + '" aria-valuemin="0" aria-valuemax="100" style="height: 15px" title="Espace disque total: ' + espace_total + ' Go,&#10;Espace disque dispo: ' + espace_dispo + ' Go"> <div class="progress-bar bg-success overflow-visible text-light petite" style="width: ' + taux_utilisation + '%">' + taux_utilisation + '%</div></div> <div class="xpetite">Espace disque utilisé</div> ');



	if (!JSZip.support.blob) {
		alert('Installez une version plus récente de votre navigateur car il est trop ancien pour supporter cette fonction !');
		return;
	}
}

//actions=====================================
//action au chargement de la page
$(document).ready(function () {
	//$('#cadre_foreground').append('<div class="foreground"></div>');
	loadValues();

	//action button tout selectionner
	$('#but_all').click(function () {
		$('#tableau div').find('input:checkbox').prop('checked', true);
	});

	//action button tout déselectionner
	$('#but_none').click(function () {
		$('#tableau div').find('input:checkbox').prop('checked', false);
	});

	//fonction supprimer
	$('#but_supprime').on('click', function () {
		var checked_values = [];
		//on stock le nom des videos cochées dans le tableau checked_values
		$('#tableau').find(":checked").each(function () {
			var $this = $(this);
			var id_nb = $this.data("id_nb") + "#" + $this.data("name");
			checked_values.push(id_nb);
		});
		//si le tableau checked_values contient au moins une video, on l'efface.
		if (checked_values.length > 0) {
			if (checked_values.length === 1) {
				var response = confirm('Confirmer la suppression de cette video ?');
				if (response == false)
					return;
			}
			else {
				var response = confirm('Confirmer la suppression de ' + checked_values.length + ' videos ?');
				if (response == false)
					return;
			}

			$('#cadre_foreground').append('<div class="foreground"></div>');

			checked_values = checked_values.toString();
			$.ajax({
				type: 'get',
				async: false,
				url: 'gallery/erase',
				data: { value: checked_values },
				success: function (result) {
					//if (result !== 'done') { alert('Erreur lors de la suppression') };
				}
			});
			window.location.href = '../gallery';
		}
		//si le tableau checked_values ne contient aucune video, on avertit l'utilisateur et on quitte
		else {
			alert('Veuillez sélectionner au moins une video.');
		}
		$('.foreground').remove();
	});

	//fonction telecharger
	$('#but_download').on('click', function () {
		var zip = new JSZip();
		var deferreds = [];
		// find every checked item
		$('#tableau').find(":checked").each(function () {
			var $this = $(this);
			var url = $this.data("url");
			//alert (url);
			var filename = url.replace(/.*\//g, "");
			deferreds.push(deferredAddZip(url, filename, zip));
		});

		//si le tableau deferreds contient au moins une video, on l'efface.
		if (deferreds.length > 0) {
			var response = confirm('Confirmer le téléchargement de ' + deferreds.length + ' video(s) ?');
			if (response == false) {
				return;
			}

			$('#cadre_foreground').append('<div class="foreground"></div>');

			// when everything has been downloaded, we can trigger the dl
			$.when.apply($, deferreds).done(function () {
				var blob = zip.generate({ type: 'blob' });
				// see FileSaver.js
				saveAs(blob, 'video.zip');
				$('.foreground').remove();
			});
		}
		//si le tableau deferreds ne contient aucune video, on avertit l'utilisateur et on quitte
		else {
			alert('Veuillez sélectionner au moins une video.');
		}
	});

});