// Id du block avec le détail du livre
var blockDetailId = 'detail_livre';
// Id du block avec la liste des livres
var blockListeId = 'liste_livre';
// Route pour le détail d'une entité
var route_detail = 'livre_detail_ajax';
// Route de recherche
var route_recherche = 'livre_recherche_ajax';
// Formulaire de recherche
var formRecherche = null;
// Input des form de recherche
var form_label = null;
var form_isbn = null;
// On load
$(document).ready(function () {
    formRecherche = $("#recherche_livre form");
    form_isbn = $('#form_isbn');
    form_label = $('#form_label');
    // Gestion de la zone de recherche
    gereAffichageZoneRechercheLivre(zone_recherche_visible);
    // Action sur la zone d'ouverture de la recherche
    $('#recherche_livre_titre').on('click', function (e) {
        gereAffichageZoneRechercheLivre(!zoneRechechercheVisible());
    });
    // Lance la recherche
    formRecherche.on('submit', function (e) {
        rechercheObjet(this);
        e.preventDefault();
    });
    gereFocusChampRecherche();
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
// Gère l'affichage des champs de recherche
function gereFocusChampRecherche(){
    // Evenement sur isbn
    form_isbn.on('keyup', function(){
        if (form_isbn.val().length ==0) {
            form_label.attr('disabled', false);
        }else{
            form_label.val('');
            form_label.attr('disabled', true);
        }
    });
    // Evenement sur label
    form_label.on('keyup', function(){
        if (form_label.val().length ==0) {
            form_isbn.attr('disabled', false);
        }else{
            form_isbn.val('');
            form_isbn.attr('disabled', true);
        }
    });
}