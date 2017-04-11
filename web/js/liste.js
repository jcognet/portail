// Ajoute les événements sur la page
function addEventDatagrid() {
    // Gestion de la zone de détail d'un livre
    $('#' + blockListeId + ' tr').on('click', function (e) {
        var objetId = $(this).attr('data-id');
        if (objetId > 0) {
            getDetailObjet(objetId);
        }
    });

    $('ul.pagination a').on('click', function (e) {
        var sort = getParameterByName('sort', $(this).attr('href'));
        var direction = getParameterByName('direction', $(this).attr('href'));
        var page = getParameterByName('page', $(this).attr('href'));

        if(window.rechercheObjet && formRecherche && formRecherche[0])
            rechercheObjet(formRecherche[0], sort, direction, page)
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