$(document).ready(function () {
    addEventDatagrid();
    afficheDetailSiUnique();
});
// Ajoute les événements sur la page
function addEventDatagrid() {
    // Gestion de la zone de détail d'un livre
    $('#' + blockListeId + ' tr').on('click', function (e) {
        var objetId = $(this).attr('data-id');
        if (objetId > 0) {
            getDetailObjet(objetId);
        }
    });

    $('table.fe_liste th a').on('click', function(e){
        lanceRecherche($(this))
        e.preventDefault();
    });

    $('ul.pagination a').on('click', function (e) {
        lanceRecherche($(this))
        e.preventDefault();
    });
}

// Affiche les détails d'un objet
function getDetailObjet(id) {
    setAjaxWorking(blockDetailId);
    url = Routing.generate(route_detail, {'id': id});
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
function createQueryStringPaginatior(sort, direction, page) {
    var queryString = "?"
    if (!sort) {
        sort = '';
    } else {
        queryString = queryString + 'sort=' + sort + '&'
    }
    if (!direction) {
        direction = '';
    } else {
        queryString = queryString + 'direction=' + direction + '&'
    }
    if (!page) {
        page = '';
    } else {
        queryString = queryString + 'page=' + page + '&'
    }
    return queryString;
}
// Affiche une entité s'il n'y a qu'un élément
function afficheDetailSiUnique() {
    var ligne = $('#'+blockListeId+' table tbody tr');
    if (ligne.length == 1) {
        var objetId = ligne.attr('data-id');
        if (objetId > 0) {
            getDetailObjet(objetId);
        }
    }
}
// Recherche des livres
function rechercheObjet(formRechercheObjetJS, sort, direction, page) {
    setAjaxWorking(blockListeId);
    var queryString = createQueryStringPaginatior(sort, direction, page);

    var formRechercheData = new FormData(formRechercheObjetJS);
    url = Routing.generate(route_recherche)+queryString;
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
            addEventDatagrid();
            afficheDetailSiUnique();
        },

        error: function (resultat, statut, erreur) {
            console.log('*****erreur*****');
            console.log(resultat);
            console.log(statut);
            console.log(erreur);
            console.log('**********');
            unsetAjaxWorking(blockListeId);
            addEventDatagrid();
        }
    });
}
// Lance la recherche
function lanceRecherche(lien){
    var sort = getParameterByName('sort', lien.attr('href'));
    var direction = getParameterByName('direction', lien.attr('href'));
    var page = getParameterByName('page', lien.attr('href'));

    var formRechercheObjetJS = null;
    if(formRecherche && formRecherche.length>0)
        formRechercheObjetJS = formRecherche[0];
    rechercheObjet(formRechercheObjetJS, sort, direction, page);


}