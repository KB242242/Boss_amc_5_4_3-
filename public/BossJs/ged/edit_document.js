$(document).ready(function () {
	$('#close_warning').on('click', function () {
		$("#warning").find("div").hide();
	});
	$('#downLoad').click(function () { //Telechargement du fichier pour ayant droit
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "20_130_0","6",contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	$('#retour').click(function () { //Telechargement du fichier pour ayant droit
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "20_130_0","1",contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});

// ----------------------------------------------------------------
	// 6-Fonctions utilitaires 

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
