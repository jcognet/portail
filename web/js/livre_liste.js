// Id du block avec le détail du livre
var blockDetailId = 'detail_livre';
// Id du block avec la liste des livres
var blockListeId = 'liste_livre';
// Formulaire de recherche
var formRecherche = null;
// On load
$(document).ready(function () {
    formRecherche = $("#recherche_livre form")
    // Gestion de la zone de recherche
    gereAffichageZoneRechercheLivre(zone_recherche_visible);
    addEvent();
    // Action sur la zone d'ouverture de la recherche
    $('#recherche_livre_bloc small').on('click', function(e){
        gereAffichageZoneRechercheLivre(!zoneRechechercheVisible());
    });
    // Lance la recherche
    $('#recherche_livre button').on('click', function(e){
        rechercheLivre();
    });
});

function getDetailLivre(id){
    setAjaxWorking(blockDetailId);
    console.log('start')
    url = Routing.generate('livre_ajax_detail', {'id':id});
    console.log(url)
    $.ajax({
        url : url,
        type : 'GET',
        dataType : 'html',
        success : function(block_html, statut){ // success est toujours en place, bien sûr !
            console.log('success')
            $('#'+blockDetailId).html(block_html);
            unsetAjaxWorking(blockDetailId);
            enableDdlDevise();
        },

        error : function(resultat, statut, erreur){
            console.log('*****erreur*****');
            console.log(resultat);
            console.log(statut);
            console.log(erreur);
            console.log('**********');
            unsetAjaxWorking(blockDetailId);
            enableDdlDevise();
        }

    });
}
function rechercheLivre(){
    setAjaxWorking(blockListeId);
    console.log(formRecherche.serialize());
    alert('go')
    unsetAjaxWorking(blockListeId);
}
// Gère la zone d'affichage de la recherche
function gereAffichageZoneRechercheLivre(inOuvre){
    if(inOuvre){
        $("#recherche_livre").slideDown();
        $('#recherche_livre_ouvrir').hide();
        $('#recherche_livre_fermer').slideDown();
    }else{
        $("#recherche_livre").slideUp();
        $('#recherche_livre_ouvrir').slideDown();
        $('#recherche_livre_fermer').hide();
    }
}
// Retourne true si la zone de recherche est visible
function zoneRechechercheVisible(){
    return $("#recherche_livre").is(':visible');
}
// Ajoute les événements sur la page
function addEvent(){
    // Gestion de la zone de détail d'un livre
    $('#liste_livres tr').on('click', function(e){
        var livreId = $(this).attr('data-id');
        if(livreId>0){
            getDetailLivre(livreId);
        }
    });
}