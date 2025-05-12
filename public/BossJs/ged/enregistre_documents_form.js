//Globales

//Debut
$(document).ready(function () {
	 $("#dossier_select").text($('#form_dossier option:selected').text());

$('#form_dossier').change(function() {
			event.preventDefault();
        // champ form dossier Mise a jour de la filiation
        let ref_individu = $(this).val();
        //1-On recherche la generation de l'individu
        let tIndividu = ref_individu.split('_');
        let generation_individu = tIndividu.length - 1;

        //1-On recherche la generation de la filiation
        let filiation = $("#dossier_select").text(); // balise p
        let tFiliation = filiation.split('/');
        let generation_filiation = tFiliation.length;

        // cas_1 generation_individu == generation_filiation
        // on reste au meme niveau, on remplace  le dernier nom de la filiation par le nouveau
        let nom_individu = $('#form_dossier option:selected').text()
       if (generation_individu == generation_filiation) {
            tFiliation[generation_individu -1] = nom_individu;
            filiation = tFiliation.join('/');
        }
        // cas_2 generation_individu > generation_filiation
        // On ajoute un niveau, on rajoute le nom de l'individu a la filiation
        if (generation_individu > generation_filiation) {
            filiation = filiation + '/' + nom_individu;
        }
        // cas_3 on remonte d'un niveau, on supprime un niveau a la filiation
        // generation_individu < generation_filiation
        if (generation_individu < generation_filiation) {
			let offset = generation_individu < 2 ?0:generation_filiation - generation_individu;
			let nbSupp = 2;
            tFiliation.splice(offset,2,nom_individu );
            filiation = tFiliation.join('/');
        }
        // On affiche la filiation
        $("#dossier_select").text(filiation);
    });
	//----------------------------------------------------------------------
    $('#select_down').click(function () { // champ form 13-classement bouton down
		//Appel ajax ave la filiation en parametre et crud 
		event.preventDefault();
        let individu = $('#form_dossier').val(); // Récupère la valeur
        // c'est un element de la generation 1
        let params = ['20_120_1', '20_120_1', '3',individu];
        let paramJson = JSON.stringify(params);
        let urlAppele = $('#routeRouteur').val() + '?params=' + paramJson;
        $.ajax({
            url: urlAppele, // L'URL de votre fichier JSON
            type: 'GET', // La méthode HTTP (GET, POST, etc.)
            dataType: 'text', // Le type de données attendu en retour
            success: function (response) {
				let options = JSON.parse(response);
				if (!$.isEmptyObject(options)) {
					// Vider les options actuelles
					$('#form_dossier').empty();
				}
				// Ajouter les nouvelles options
				$.each(options, function(key, value) {
				  $('#form_dossier').append($('<option>', {
					value: key,
					text: value
				  }));
				});
			},
            error: function(error) {
				console.error('Erreur:', error);
			}
        });
    });
	//-----------------
    $('#select_up').click(function () { // champ form 13-classement bouton down
		//Appel ajax ave la filiation en parametre et crud 
		event.preventDefault();
        let individu = $('#form_dossier').val(); // Récupère la valeur
        // c'est un element de la generation 1
        let params = ['20_120_1', '20_120_1', '4',individu];
        let paramJson = JSON.stringify(params);
        let urlAppele = $('#routeRouteur').val() + '?params=' + paramJson;
        $.ajax({
            url: urlAppele, // L'URL de votre fichier JSON
            type: 'GET', // La méthode HTTP (GET, POST, etc.)
            dataType: 'text', // Le type de données attendu en retour
            success: function (response) {
				let options = JSON.parse(response);
				if (!$.isEmptyObject(options)) {
					// Vider les options actuelles
					$('#form_dossier').empty();
				}
				// Ajouter les nouvelles options
				$.each(options, function(key, value) {
				  $('#form_dossier').append($('<option>', {
					value: key,
					text: value
				  }));
				});
			},
            error: function(error) {
				console.error('Erreur:', error);
			}
        });
    });
	//----------------------------------------------------------------------
    $('#submit').click(function (event) {
		event.preventDefault();
		let titre = $('#form_titre').val();
		let gisement = $('#form_gisement').val();
		let dossier = $('#form_dossier').val();
		let mots_cles = $('#form_mots_cles').val();
		let rem = $('#form_rem').val();

        let params = ['20_120_1', '20_120_1', '5',[titre,gisement,dossier,mots_cles,rem]];
        let paramJson = JSON.stringify(params);
        let urlAppele = $('#routeRouteur').val() + '?params=' + paramJson;
        $.ajax({
                type: 'POST',
                url: urlAppele, 
				dataType: 'json', // Spécifiez le type de données attendu en réponse (JSON dans cet exemple)
                success: function(response) {
                    // Gérez la réponse du serveur en cas de succès
                   let context = [];
					let contextJson = JSON.stringify(context);
					let params = [$('#nodeCourant').val(), "20_120_0", "0", contextJson];
					let paramJson = JSON.stringify(params);
					let href = $("#routeRouteur").val() + "?params=" + paramJson;
					window.open(href, '_self');
				}
			});
	});
    $('#retour').click(function (event) {
        // Debug context
        event.preventDefault();
        let context = [$('#nodeCourant').val()];
        let contextJson = JSON.stringify(context);
        let params = [$('#nodeCourant').val(), "1_400_0", "0", contextJson];
        let paramJson = JSON.stringify(params);
        let href = $("#routeRouteur").val() + "?params=" + paramJson;
        window.open(href, '_self');
        $("#loader").css("display", "inline");
    });
     //-------------------------------------------------------------------------
    $('#disconnect').click(function () {
        //10 disconnect params = '0-paquet;1-branchement;2-appelant;3-nodeCourant;';
        params = '10;10;' + $("#ref_node_courant").val() + ';' + $("#ref_node_courant").val() + ';';
        params = encodeChaine(params, clePublique);
        var href = $("#route_retour").val() + "?params=" + params;
        window.open(href, '_self');
        $("#loader").css("display", "inline");
        return false;
    });
    function disconnectPage() {
        // Code to disconnect from the page goes here
        let context = [$('#nodeCourant').val()];
        let contextJson = JSON.stringify(context);
        let params = [$('#nodeCourant').val(), "-1", contextJson];
        let paramJson = JSON.stringify(params);
        let href = $("#id_route").val() + "?params=" + paramJson;
        window.open(href, '_self');
        $("#loader").css("display", "inline");
    }
   // ----------------------------------------------------------------

    window.setTimeout(disconnectPage, $("#timeOut").val());
});// ready end
