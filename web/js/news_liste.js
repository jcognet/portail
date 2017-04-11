// Id du block avec le détail du livre
var blockDetailId = 'detail_news';
// Id du block avec la liste des livres
var blockListeId = 'liste_news';
// Route pour le détail d'une entité
var route_detail = 'commun_news_ajax_detail';
// Formulaire de recherche
var formRecherche = null;
$(document).ready(function () {
    addEventDatagrid();
});