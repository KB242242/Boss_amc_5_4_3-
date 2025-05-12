$(document).ready(function () {
	// Gestion de la fenetre notification
	$('#close_warning').on('click', function () {
		$("#warning").find("div").hide();
	});
	// -------------------------------------------------------------
	// Commandes appel section  reserve_documents
	$('#reserveDocuments').click(function () {
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "20_110_0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});

	// Commandes section  enregistre_document
	$('#enregistreDocument').click(function () {
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "20_120_0", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});

	// Commandes appel section recherche_documents
	$('#rechercheDocument').click(function () {
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "20_130_0", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});

	// Commandes appel disconnect
	$('#disconnect').click(function () {
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "-1", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	// ----------------------------------------------------------------
	// 6-Fonctions utilitaires
	function disconnectPage() {
		// Code to disconnect from the page goes here
		let context = [$('#nodeCourant').val(), []];
		let contextJson = JSON.stringify(context);
		// ----------------------------------------------------------------
		let params = [$('#nodeCourant').val(), "-1", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	}
	window.setTimeout(disconnectPage, $("#timeOut").val());
}); // ready end
