// Id du block avec le détail du livre
var blockDetailId = 'detail_livre';
// Id du block avec la liste des livres
var blockListeId = 'liste_livre';
// Route pour le détail d'une entité
var route_detail = 'livre_ajax_detail';
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
        rechercheObjet(this.form);
    });
    afficheDetailLivreSiUnique();
});
// Recherche des livres
function rechercheObjet(formRecherche, sort, direction, page) {
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
// Affiche un livre s'il n'y a qu'un élément
function afficheDetailLivreSiUnique() {
    var ligne = $('#'+blockListeId+' table tbody tr');
    if (ligne.length == 1) {
        var livreId = ligne.attr('data-id');
        if (livreId > 0) {
            getDetailLivre(livreId);
        }
    }
}
