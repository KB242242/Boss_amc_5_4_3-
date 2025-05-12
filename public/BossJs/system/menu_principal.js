$(document).ready(function () {
	$('#notification_msg').on('click', function () {
		$("#notification_msg").hide();
	});
	// Gestion du menu principal
	$('#1_100_0').click(function () { // Affaires
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "1_100_0", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	$('#1_400_0').click(function () { // Gestion clients
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "1_400_0", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	$('#1_500_0').click(function () { // Contrats clients
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "1_500_0", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	$('#1_600_0').click(function () { // Services / Produits
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "1_600_0", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	$('#1_700_0').click(function () { // Marketing
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "1_700_0", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	//----------------------------------------------------------

	//----------------------------------------------------------
	//Admin. system
	$('#0_100_3').click(function () { // Modification mot de passe
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "0_100_3", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	$('#disconnect').click(function () {
		// 3-disconnect
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "-1", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	// -------------------------------------------------------------
	function disconnectPage() {
		// Code to disconnect from the page goes here
		let context = [$('#nodeCourant').val()];
		let contextJson = JSON.stringify(context);
		// ----------------------------------------------------------------
		let params = [$('#nodeCourant').val(), "-1", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	}
	// -------------------------------------------------------------
	// Commandes outillage
	$('#ged').click(function () { // Marketing
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "20_100_0", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	$('#activite').click(function () { // Marketing
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "30_100_0", "0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = $("#routeRouteur").val() + "?params=" + paramJson;
		window.open(href, '_self');
	});
	window.setTimeout(disconnectPage, $("#timeOut").val());
}); // ready end
