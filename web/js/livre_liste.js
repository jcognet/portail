// Id du block avec le détail du livre
var blockDetailId = 'detail_livre';
// Id du block avec la liste des livres
var blockListeId = 'liste_livre';
// Formulaire de recherche
var formRecherche = null;
// On load
$(document).ready(function () {
    formRecherche = $("#recherche_livre form");
    // Gestion de la zone de recherche
    gereAffichageZoneRechercheLivre(zone_recherche_visible);
    addEvent();
    // Action sur la zone d'ouverture de la recherche
    $('#recherche_livre_titre').on('click', function (e) {
        gereAffichageZoneRechercheLivre(!zoneRechechercheVisible());
    });
    // Lance la recherche
    $('#recherche_livre button').on('click', function (e) {
        rechercheLivre(this.form);
    });
    afficheDetailLivreSiUnique();
});
// Affiche les détails d'un livre
function getDetailLivre(id) {
    setAjaxWorking(blockDetailId);
    url = Routing.generate('livre_ajax_detail', {'id': id});
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'html',
        success: function (block_html, statut) { // success est toujours en place, bien sûr !
            $('#' + blockDetailId).html(block_html);
            unsetAjaxWorking(blockDetailId);
        },

        error: function (resultat, statut, erreur) {
            console.log('*****erreur*****');
            console.log(resultat);
            console.log(statut);
            console.log(erreur);
            console.log('**********');
            unsetAjaxWorking(blockDetailId);
        }

    });
}
// Recherche des livres
function rechercheLivre(formRecherche, sort, direction, page) {
    setAjaxWorking(blockListeId);
    var queryString = createQueryStringPaginatior(sort, direction, page);

    var formRechercheData = new FormData(formRecherche);
    url = Routing.generate('livre_ajax_recherche')+queryString;
    for (var pair of formRechercheData.entries()) {
        console.log(pair[0]+ ', ' + pair[1]);
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: formRechercheData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (block_html, statut) { // success est toujours en place, bien sûr !
            $('#' + blockListeId).html(block_html);
            unsetAjaxWorking(blockListeId);
            addEvent();
            afficheDetailLivreSiUnique();
        },

        error: function (resultat, statut, erreur) {
            console.log('*****erreur*****');
            console.log(resultat);
            console.log(statut);
            console.log(erreur);
            console.log('**********');
            unsetAjaxWorking(blockListeId);
            addEvent();
        }
    });
}
// Gère la zone d'affichage de la recherche
function gereAffichageZoneRechercheLivre(inOuvre) {
    if (inOuvre) {
        $("#recherche_livre").slideDown();
        $('#recherche_livre_ouvrir').hide();
        $('#recherche_livre_fermer').slideDown();
    } else {
        $("#recherche_livre").slideUp();
        $('#recherche_livre_ouvrir').slideDown();
        $('#recherche_livre_fermer').hide();
    }
}
// Retourne true si la zone de recherche est visible
function zoneRechechercheVisible() {
    return $("#recherche_livre").is(':visible');
}
// Ajoute les événements sur la page
function addEvent() {
    // Gestion de la zone de détail d'un livre
    $('#liste_livre_bloc tr').on('click', function (e) {
        var livreId = $(this).attr('data-id');
        if (livreId > 0) {
            getDetailLivre(livreId);
        }
    });

    $('ul.pagination a').on('click', function (e) {
        var sort = getParameterByName('sort', $(this).attr('href'));
        var direction = getParameterByName('direction', $(this).attr('href'));
        var page = getParameterByName('page', $(this).attr('href'));


        rechercheLivre(formRecherche[0], sort, direction, page)
        e.preventDefault();
    });
}
// Affiche un livre s'il n'y a qu'un élément
function afficheDetailLivreSiUnique() {
    var ligne = $('#liste_livre table tbody tr');
    if (ligne.length == 1) {
        var livreId = ligne.attr('data-id');
        if (livreId > 0) {
            getDetailLivre(livreId);
        }
    }
}
// Get parametre from url
function getParameterByName(name, url) {
    if (!url) {
        url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
// Create query string for paginator
function createQueryStringPaginatior( sort, direction, page){
    var queryString = "?"
    if (!sort) {
        sort = '';
    }else{
        queryString = queryString +'sort='+sort+'&'
    }
    if (!direction){
        direction = '';
    }else{
        queryString = queryString +'direction='+direction+'&'
    }
    if (!page){
        page = '';
    }else{
        queryString = queryString +'page='+page+'&'
    }
    return queryString;
}