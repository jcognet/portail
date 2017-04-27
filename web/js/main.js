$(document).ready(function () {
    // Mise en place d'événements
    changeSort();
    alerteSiSuppression();
});
// Gestion des erreurs ajax
$( document ).ajaxError(function( event, request, settings ) {
    console.log('*******Erreur*******');
    console.log(event);
    console.log(request);
    console.log(settings);
    console.log('********************');
});
// Lance l'ajax
function setAjaxWorking(divId) {
    var divAjax = $("#" + divId);
    var loader = $("#loader_ajax").clone();
    divAjax.addClass('ajax');
    loader.css('margin-left', divAjax.width()/2 + 'px');
    loader.css('margin-top', divAjax.height()/2 + 'px');
    loader.addClass('loader');
    loader.attr('id', 'loader_ajax_' + divId).show().prependTo("#" + divId);
}
// Arrête l'Ajax
function unsetAjaxWorking(divId) {
    $("#" + divId).removeClass('ajax');
    $("#loader_ajax_" + divId).remove();
}
// Gère les tris
function changeSort(){
    $('.sorted .asc').each(function(){
        $(this).append('<span class="glyphicon glyphicon-arrow-down"></span>');
    });
    $('.sorted .desc').each(function(){
        $(this).append('<span class="glyphicon glyphicon-arrow-up"></span>');
    });
}
// Gère l'alerte de suppression
function alerteSiSuppression(){
    $('.lnk_suppression').each(function(){
        $(this).click( function(e){
            if(!confirm('Voulez-vous vraiment supprimer cet élément ? ')){
                e.preventDefault();
                return false;
            }
        });
    });
}
