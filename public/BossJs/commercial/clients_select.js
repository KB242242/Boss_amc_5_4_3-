$(document).ready(function () {
	//Loader is off
	$('#loader').css('visibility', 'hidden');
	// ----------------------------------------------------------------
	// Declaration de la table
	refUser = $("#refUser").val();
	// Appelle fichier des donnees de la table
	let selected = [];
	let context = [$('#id_node_courant').val(), selected];
	let contextJson = JSON.stringify(context);
	let params = [$('#id_node_courant').val(), $("#idDataTableAjax").val(), contextJson, 1];

	let paramJson = JSON.stringify(params);
	let routeAjax = $("#id_route").val() + "?params=" + paramJson;
	var maTable = new DataTable('#table', { ajax: routeAjax });

	$('#cmdContextPart').hide();
	// ----------------------------------------------------------------
	// 1 gestion de la visibilite des colonnes
	// maTable.columns(0).visible(false);

	// 1-2-Reponse a un evenement clik sur la dataTable
	var tSelection = ""; var tPage = [];
	var indexRowSel = 0;
	var msgHeader = ""; var listColMsg = "";
	var selection = new selectionLigne($("#max_sel").val(), msgHeader, listColMsg);
	var isConnex = false;
	var isContextGene = false;
	var isContextPart = false;
	var isZoneContextPart = false;
	//--------------------------------------------------------------
	// 1-Detection evenement click dataTable

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

		// 1-3 Enregistre la selection
		tPage = selection.getSelDataTable();

		// 1-4 Changement de la couleur de fond de la ligne
		if (selection.getChangeColor()) { $('td', this).css('background-color', selection.getCouleurLigne()); }

		//1-5 Le bouton d'acces au contexte particulier devient visisible
		// Non implemente dans ce cas!

		//1-6 Notification MaxSelect
		if (selection.getMaxSelect()) { alert("Vous avez atteint le nombre maximum de selection sur la table "); }
	}); // Fin detection evenement click dataTable
	// ----------------------------------------------------------------
	//00- select
	
					$('#select').click(function () {
						let params = [$('#id_node_courant').val(), '104', tPage];
						let paramJson = JSON.stringify(params);
						let href = $('#id_route').val() + '?params=' + paramJson;
						window.open(href, '_self');
						$('#loader').css('display', 'inline');
			});
	//01- retour
	
				   $('#retour').click(function () {	
						let params = [$('#id_node_courant').val(), '104', []];
						let paramJson = JSON.stringify(params);
						let href = $('#id_route').val() + '?params=' + paramJson;
						window.open(href, '_self');
						$('#loader').css('display', 'inline');
					});
	// ----------------------------------------------------------------
	//Appel des outils
	$('#ged').click(function () {

		alert("La page n'est pas implementee");
		/**
		* la page doit s'ouvrir dans un onglet et doit pouvoir etre refermee
		* automatiquement a la deconnexion de l'utilisateur
		* voir https://www.w3schools.com/jsref/met_win_close.asp
		**/

		//let params = [$('#id_node_courant').val(), "ged", tPage];
		//let paramJson = JSON.stringify(params); console.log(paramJson);
		//let href = $("#id_route").val() + "?params=" + paramJson;
		//let gedWindow = window.open(href, '_blank');
		//$("#loader").css("display", "inline");
	});
	$('#note').click(function () {
		alert("La page n'est pas implementee");
		// voir la fonction $('#ged').click(function () precedente
	});
	$('#activite').click(function () {
		alert("La page n'est pas implementee");
		// voir la fonction $('#ged').click(function () precedente

	});
	$('#kpi').click(function () {
		alert("La page n'est pas implementee");
		// voir la fonction $('#ged').click(function () precedente

	});



	function disconnectPage() {
		// Code to disconnect from the page goes here
		let context = [$('#id_node_courant').val(), tPage];
		let contextJson = JSON.stringify(context);
		let params = [$('#id_node_courant').val(), "-1", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#id_route").val() + "?params=" + paramJson;
		window.open(href, '_self');
	}
	window.setTimeout(disconnectPage, $("#timeOut").val());
}); // ready end
