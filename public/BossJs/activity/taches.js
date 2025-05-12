$(document).ready(function () {
	$('#close_warning').on('click', function () {
		$("#warning").find("div").hide();
	});

	// -------------------------------------------------------------
	// 0-Declaration de la table
	let nodeCourant = $('#nodeCourant').val();
	let tableAjax = "20_120_3";
	let params = [nodeCourant, tableAjax,'0', []];
	let paramJson = JSON.stringify(params);
	let routeAjax = $("#routeRouteur").val() + "?params=" + paramJson;
	var maTable = new DataTable('#table', { ajax: routeAjax });
	// ----------------------------------------------------------------
	// 1-Detection evenement click dataTable
	// initialisation des globalesReponse a un evenement clik sur la dataTable
	var tPage = [];
	var tSelection = [];
	var indexRowSel = 0;
	var msgHeader = ""; var listColMsg = "";
	//Initialisation de l'objet selection dans js_utilities.js
	var selection = new selectionLigne($("#maxSel").val(), msgHeader, listColMsg);
	$('#table tbody').on('click', 'tr', function () {
		$('html, body').animate({
			scrollTop: 0
		}, 'slow');
		// 1-1-Enregistrement des parametres ligne selectionnee de la table 

		for (let i = 0; i < $("#nbColumns").val(); i++) {
			tSelection[i] = $('td', this).eq(i).text()
		}

		indexRowSel = $(this).index();
		// 1-2 Enregistre toutes les colonnes de la ligne selectionnées (indexRowSel) dans tTable[lgn]
		// ! si une ligne deja selection (couleur jaune) est reselectionnee, 
		//celle-ci est supprimee de la selection  dans  selDataTable[lgn][col]
		selection.getRowSelection(tSelection, indexRowSel);

		// 1-3 Enregistre la selection dans la table tPage
		tPage = selection.getSelDataTable();

		// 1-4 Changement de la couleur de fond de la ligne
		if (selection.getChangeColor()) { $('td', this).css('background-color', selection.getCouleurLigne()); }
		//-------------------------------------------------------------
		//1-5 La zone des bouton de cmde du contexte particulier devient visisible si une seule selection
		if (!selection.getIsFirstSel()) {
			$('#zoneContextPart').show(); $('#formulaire').show();
			//Appel ajax de preparation du formulaire 
			let params = [$('#nodeCourant').val(), '20_120_1', tPage];
			let paramJson = JSON.stringify(params);
			let urlAppele = $('#routeRouteur').val() + '?params=' + paramJson;
		}
		else { $('#zoneContextPart').hide(); $('#formulaire').hide(); }
		//-------------------------------------------------------------
		//1-5 La zone des bouton de cmde du contexte particulier devient visisible si une seule selection
		if (!selection.getIsFirstSel()) { $('#zoneContextPart').show(); } else $('#zoneContextPart').hide();
	});
	// ----------------------------------------------------------------

	// ----------------------------------------------------------------
	// 3-Menu de la page
	$('#gedMenuPrincipal').click(function () {
		let params = [$('#nodeCourant').val(), '20_100_0', tPage];
		let paramJson = JSON.stringify(params);
		let href = $('#routeRouteur').val() + '?params=' + paramJson;
		window.open(href, '_self');
		$('#loader').css('display', 'inline');
	});

	$('#reserveDocuments').click(function () { // section 110-reserve_documents
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "20_110_0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});

	$('#file_select_enreg').click(function () { // section 120-ged_document_form crud 1-creat
		let context = tPage;
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "20_120_1", "1", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});

	$('#rechercheDocument').click(function () { // section 110-reserve_documents
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "20_130_0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});

	$('#adminDocument').click(function () { // section 110-reserve_documents
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "20_130_0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});

	$('#disconnect').click(function () { // section 110-reserve_documents
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "-1", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});

	// ----------------------------------------------------------------
	// 4-Appel formulaire
	//  Methode utilisees
	// ----------------------------------------------------------------
	// 6-Fonctions utilitaires 
	function appelAjax(params, isJson) {
		$retour = [];
		//let params = [$('#nodeCourant').val(), '20_120_4', [valOption_13]];
		let paramJson = JSON.stringify(params);
		let urlAppele = $('#routeRouteur').val() + '?params=' + paramJson;

		// On appelle la fonction qui retourne une liste de choix en fontion du choix precedent
		$.ajax({
			url: urlAppele, // L'URL de votre fichier JSON
			type: 'GET', // La méthode HTTP (GET, POST, etc.)
			dataType: 'text', // Le type de données attendu en retour
		});

	}

	function disconnectPage() {
		// Code to disconnect from the page goes here
		let context = [$('#nodeCourant').val(), tPage];
		let contextJson = JSON.stringify(context);
		// ----------------------------------------------------------------
		let params = [$('#nodeCourant').val(), "-1", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	}

	// ----------------------------------------------------------------
	window.setTimeout(disconnectPage, $("#timeOut").val());
}); // ready end
