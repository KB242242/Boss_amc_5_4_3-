$(document).ready(function () {
	$('#loader').css('visibility', 'hidden');
	$('#myform').submit(function (event) {
		$('#loader').css('visibility', 'visible');
	});
	$('#retour').click(function () { // Services / Produits
		let context = [];
		let contextJson = JSON.stringify(context);
		let params = [$('#nodeCourant').val(), "0_130_0", contextJson];
		let paramJson = JSON.stringify(params);
		let href = "/boss_routeur" + "?params=" + paramJson;
		window.open(href, '_self');
	});
})
