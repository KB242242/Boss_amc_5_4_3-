$(document).ready(function () {
	// ----------------------------------------------------------------
	// Declaration de la table
	let nodeCourant = $('#nodeCourant').val();
	let tableAjax = $('#dataTableAjax').val();
	let params = [nodeCourant, tableAjax,"0", ['vide']];
	let paramJson = JSON.stringify(params);
	let routeAjax = $("#routeRouteur").val() + "?params=" + paramJson;
	var maTable = new DataTable('#table', { ajax: routeAjax });
	//--------------------------------------------------------------
	// Gestion des notifications
	$("#notification_msg").click(function () {
		//display: none;
		$('#notification_msg').hide();
	});
	// ----------------------------------------------------------------
	// 0-Action preparatoire
	// ----------------------------------------------------------------
	//Loader is off
	$('#loader').css('visibility', 'hidden');
	// ----------------------------------------------------------------
	// 1-Detection evenement click dataTable
	// initialisation des globalesReponse a un evenement clik sur la dataTable
	var tPage = [];
	var tSelection = [];
	var indexRowSel = 0;
	var msgHeader = ""; var listColMsg = "";
	//Initialisation de l'objet selection dans js_utilities.js
	var selection = new selectionLigne($("#maxSel").val(), msgHeader, listColMsg);


	/**
	* ------------------------------
	* fichier js_utilities.js
	* 	class selectionLigne
	*  		getRowSelection(tSelection, indexRowSel)
	*
	*		getSelDataTable() -- retourne toutes les selections
	*		getCouleurLigne() -- retourne la couleur de ligne de la selection courante
	* 		getChangeColor() --  retourne si un changement de couleur a eu  lieu 

	*		getBoutonContextView() -- retourne la visibilite du bouton de la zone contextPart
	*		getMaxSelect() -- retourne MaxSelect 
	
	*		getVisibiliteCmde(pageCmde, tSelection) -- retourne visibilite de tous les boutons de service
	* ------------------------------ 
	**/
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
		if (selection.getIsOneSel()) { $('#zoneContextPart').show(); } else $('#zoneContextPart').hide();
	});
	// ----------------------------------------------------------------
	// 2-Gestion du menu
	$('#menuPrincipal').click(function () {
		// Debug context
		let context = [$('#nodeCourant').val(), tPage];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "0_130_0", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
		$("#loader").css("display", "inline");
	});
	$('#disconnect').click(function () {
		// Debug context
		let context = [$('#nodeCourant').val(), tPage];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "-1", "0",  contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	// ----------------------------------------------------------------
	// Reponse aux boutons de cmde
	// ----------------------------------------------------------------
	// 0_outils
	$('#ged').click(function () {
		let params = [$('#nodeCourant').val(), '20_100_0', "0",  tPage];
		let paramJson = JSON.stringify(params);
		let href = $('#routeRouteur').val() + '?params=' + paramJson;
		window.open(href, '_self');
		$('#loader').css('display', 'inline');
	});
	$('#note').click(function () {
		let params = [$('#nodeCourant').val(), '30_100_0', "0",  tPage];
		let paramJson = JSON.stringify(params);
		let href = $('#routeRouteur').val() + '?params=' + paramJson;
		window.open(href, '_self');
		$('#loader').css('display', 'inline');
	});
	$('#activite').click(function () {
		let params = [$('#nodeCourant').val(), '40_100_0', "0",  tPage];
		let paramJson = JSON.stringify(params);
		let href = $('#routeRouteur').val() + '?params=' + paramJson;
		window.open(href, '_self');
		$('#loader').css('display', 'inline');
	});
	$('#kpi').click(function () {
		let params = [$('#nodeCourant').val(), '50_100_0', "0",  tPage];
		let paramJson = JSON.stringify(params);
		let href = $('#routeRouteur').val() + '?params=' + paramJson;
		window.open(href, '_self');
		$('#loader').css('display', 'inline');
	});

	// 01_connexe
	$('#client_select').click(function () {
		let params = [$('#nodeCourant').val(), '1_400_0', "3",  tPage];
		let paramJson = JSON.stringify(params);
		let href = $('#routeRouteur').val() + '?params=' + paramJson;
		window.open(href, '_self');
		$('#loader').css('display', 'inline');
	});

	// ----------------------------------------------------------------
	// 02_context general
	$('#client_add').click(function () {
		let tPageJson = JSON.stringify(tPage)
		let params = [$('#nodeCourant').val(), '1_400_1', "1",  tPageJson];
		let paramJson = JSON.stringify(params);
		let href = $('#routeRouteur').val() + '?params=' + paramJson;
		window.open(href, '_self');
		$('#loader').css('display', 'inline');
	});
	// ----------------------------------------------------------------
	// 03_context particulier

	$('#client_modif').click(function () {
		let tPageJson = JSON.stringify(tPage);
		let params = [$('#nodeCourant').val(), '1_400_1', "2",  tPageJson];
		let paramJson = JSON.stringify(params);
		let href = $('#routeRouteur').val() + '?params=' + paramJson;
		window.open(href, '_self');
		$('#loader').css('display', 'inline');
	});
	$('#client_supp').click(function () {
		let tPageJson = JSON.stringify(tPage);
		let params = [$('#nodeCourant').val(), '1_400_2', "0",  tPageJson];
		let paramJson = JSON.stringify(params);
		let href = $('#routeRouteur').val() + '?params=' + paramJson;
		window.open(href, '_self');
		$('#loader').css('display', 'inline');
	});
	// ----------------------------------------------------------------
	// 6-Fonctions utilitaires

	function disconnectPage() {
		// Code to disconnect from the page goes here
		let context = [$('#nodeCourant').val(), tPage];
		let contextJson = JSON.stringify(context);
		// ----------------------------------------------------------------
		let params = [$('#nodeCourant').val(), "-1", "0",  contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	}

	// ----------------------------------------------------------------
	window.setTimeout(disconnectPage, $("#timeOut").val());
}); // ready end
