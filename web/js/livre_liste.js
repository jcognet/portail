// Id du block avec le détail du livre
var blockDetailId = 'detail_livre';
// Id du block avec la liste des livres
var blockListeId = 'liste_livre';
// Route pour le détail d'une entité
var route_detail = 'livre_ajax_detail';
// Route de recherche
var route_recherche = 'livre_ajax_recherche';
// Formulaire de recherche
var formRecherche = null;
// On load
$(document).ready(function () {
    formRecherche = $("#recherche_livre form");
    // Gestion de la zone de recherche
    gereAffichageZoneRechercheLivre(zone_recherche_visible);
    // Action sur la zone d'ouverture de la recherche
    $('#recherche_livre_titre').on('click', function (e) {
        gereAffichageZoneRechercheLivre(!zoneRechechercheVisible());
    });
    // Lance la recherche
    $('#recherche_livre button').on('click', function (e) {
        rechercheObjet(this.form);
    });

});
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
