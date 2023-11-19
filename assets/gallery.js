import "./player";
import {forEach} from "core-js/stable/dom-collections";

function loadDatas() {
	fetch('gallery/discusage')
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
	let totalSpace, freeSpace, usedRate;
	[totalSpace, freeSpace, usedRate] = data;

	document.getElementById("bar").innerHTML = '<div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="' + usedRate + '" aria-valuemin="0" aria-valuemax="100" style="height: 15px" title="Espace disque total: ' + totalSpace + ' Go,&#10;Espace disque dispo: ' + freeSpace + ' Go"> <div class="progress-bar bg-success overflow-visible text-light petite" style="width: ' + usedRate + '%">' + usedRate + '%</div></div> <div class="xpetite">Espace disque utilisé</div> ';
}

//action au chargement de la page
document.addEventListener("DOMContentLoaded", function () {
	loadDatas();
	//action button tout selectionner
	document.getElementById("but_all").addEventListener("click", function (){
		forEach.call( document.querySelectorAll('input[type="checkbox"]'),function(el){
				el.checked=true;
			}
		);
	});

	//action button tout déselectionner
	document.getElementById("but_none").addEventListener("click", function (){
		forEach.call( document.querySelectorAll('input[type="checkbox"]'),function(el){
				el.checked=false;
			}
		);
	});
});