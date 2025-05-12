//Globales
var params;
var clePublique;
//Debut
$(document).ready(function () {
    //Loader is off
    $('#loader').css('visibility', 'hidden');

    //----------------------------------------------------------------------
    // Définition de la fonction 
    $.fn.Center = function () {
        this.css({
            'position': 'fixed',
            'left': '50%',
            'top': '10%',
            'font-size': '30px',
            'color': '#8da',
            'display': 'none'
        });
        this.css({
            'margin-left': -this.width() / 2 + 'px',
            'margin-top': -this.height() / 2 + 'px'
        });
    };
    //----------------------------------------------------------------------
    $('#myform').submit(function (event) {
        let contextJson = [];
        let params = [$('#nodeCourant').val(), "20_120_1", "01", contextJson];
        let paramJson = JSON.stringify(params);
        let href = $("#routeRouteur").val() + "?params=" + paramJson;
        window.open(href, '_self');
        $('#loader').css('display', 'inline');
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
    // Gestion du select dossier 
    // Met à jour la filiation lorsqu'une option de classement est sélectionnée
    $('#champ_13').on('change', updateFiliation);

    // Gestion des boutons "down" et "up" pour changer le niveau de classement
    $('#champ_13_down').click(() => updateClassement('down'));
    $(function appelAjax(params, callback) {
        $.ajax({
            url: `${routeRouteur}?params=${JSON.stringify(params)}`,
            type: 'GET',
            dataType: 'json',
            success: callback, // Exécute la fonction de rappel en cas de succès
            error: handleAjaxError, // Gère les erreurs
        });
    }'#champ_13_up').click(() => updateClassement('up'));

    // Fonction pour effectuer un appel AJAX


    // Gère les erreurs des appels AJAX
    function handleAjaxError(xhr, status, error) {
        console.error('Erreur AJAX :', error);
    }

    // Génère les options d'une liste déroulante à partir d'un tableau de données
    function generateOptions(data) {
        return data.reduce((options, item) => {
            return options + `<option value="${item.dest}">${item.lib}</option>`;
        }, '<option value="input" selected>-- Please choose an option --</option>');
    }

    // Met à jour le chemin de filiation affiché dans le formulaire
    function updateFiliation() {
        const filiation = $("#txt_selection").text(); // Filiation actuelle
        const individuText = $("#champ_13 option:selected").text(); // Texte sélectionné
        const individuValue = $("#champ_13").val(); // Valeur sélectionnée
        const individuParts = individuValue.split('_');
        const filiationParts = filiation.split('/');

        let updatedFiliation = filiation;

        // Met à jour le chemin selon la profondeur
        if (individuParts.length === filiationParts.length) {
            filiationParts[individuParts.length - 1] = individuText;
        } else if (individuParts.length > filiationParts.length) {
            updatedFiliation += `/${individuText}`;
        } else {
            filiationParts[individuParts.length - 1] = individuText;
            filiationParts.splice(individuParts.length);
        }

        $("#txt_selection").text(filiationParts.join('/'));
    }

    // Modifie les options de classement en fonction de la direction (up ou down)
    function updateClassement(direction) {
        const individuValue = $("#champ_13").val();
        const individuParts = individuValue.split('_');
        const generation = individuParts.length - 1;

        if (direction === 'down') {
            // Descendre d'un niveau dans la hiérarchie
            individuParts[0] = generation > 0 ? individuValue.replace(/input/g, "element") : individuValue;
            appelAjax([nodeCourant, '20_120_4', [individuParts.join('_')]], (response) => {
                if (response && response.length) {
                    $("#champ_13").html(generateOptions(response));
                }
            });
        } else if (direction === 'up') {
            // Remonter d'un niveau dans la hiérarchie
            individuParts.pop();
            if (generation === 1) individuParts[0] = 'input';
            appelAjax([nodeCourant, '20_120_4', [individuParts.join('_')]], (response) => {
                if (response) {
                    $("#champ_13").html(generateOptions(response));
                    $('#champ_13').trigger('change'); // Déclenche le changement pour mettre à jour la filiation
                }
            });
        }
    }
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
    //Appel des outils
    $('#ged').click(function () {

        alert("La page n'est pas implementee");
        /**
        * la page doit s'ouvrir dans un onglet et doit pouvoir etre refermee
        * automatiquement a la deconnexion de l'utilisateur
        * voir https://www.w3schools.com/jsref/met_win_close.asp
        **/

        //let params = [$('#nodeCourant').val(), "ged", tPage];
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
    // ----------------------------------------------------------------

    window.setTimeout(disconnectPage, $("#timeOut").val());
});// ready end
