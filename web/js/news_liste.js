// Id du block avec le détail du livre
var blockDetailId = 'detail_news';
// Id du block avec la liste des livres
var blockListeId = 'liste_news';
// Route pour le détail d'une entité
var route_detail = 'commun_news_detail_ajax';
// Route de recherche
var route_recherche = 'commun_news_recherche_ajax';
// Formulaire de recherche
var formRecherche = null;
$(document).ready(function () {
    formRecherche = $("#recherche_news form");
    // Gestion de la zone de recherche
    gereAffichageZoneRecherche(zone_recherche_visible);
    // Action sur la zone d'ouverture de la recherche
    $('#recherche_news_titre').on('click', function (e) {
        gereAffichageZoneRecherche(!zoneRechechercheVisible());
    });
    // Lance la recherche
    formRecherche.on('submit', function (e) {
        rechercheObjet(this);
        e.preventDefault();
    });
});

// Gère la zone d'affichage de la recherche
function gereAffichageZoneRecherche(inOuvre) {
    if (inOuvre) {
        $("#recherche_news").slideDown();
        $('#recherche_news_ouvrir').hide();
        $('#recherche_news_fermer').slideDown();
    } else {
        $("#recherche_news").slideUp();
        $('#recherche_news_ouvrir').slideDown();
        $('#recherche_news_fermer').hide();
    }
}
// Retourne true si la zone de recherche est visible
function zoneRechechercheVisible() {
    return $("#recherche_news").is(':visible');
}