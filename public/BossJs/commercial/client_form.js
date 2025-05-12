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
