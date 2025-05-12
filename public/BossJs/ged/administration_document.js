$(document).ready(function () {
	$('#close_warning').on('click', function () {
		$("#warning").find("div").hide();
	});
	// -------------------------------------------------------------
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
