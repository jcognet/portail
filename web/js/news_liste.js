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

    // Lance la recherche
    formRecherche.on('submit', function (e) {
        rechercheObjet(this);
        e.preventDefault();
    });
});