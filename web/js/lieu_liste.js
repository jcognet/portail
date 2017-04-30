// Quelques constantes
var AJOUTER_LIEU_ID = 'div_ajouter_lieu';
var MODAL_AJOUTER_LIEU_ID = 'modalAjouterLieu';
var DDL_TYPE_LIEU_ID = 'livrebundle_lieu_typeLieu';
var BTN_AJOUTER_LIEU_ID = 'btnAjouterLieu';
var PROGRESS_BAR_DIV_ID = 'progress_bar';
var DIV_MES_LIEUX_ID = 'div_mes_lieux';

var ROUTE_LIEU_AJOUT = 'livre_lieu_form_ajax'; // Route Symfony gérant l'ajout
var ROUTE_LIEU_ENREGISTRE = 'livre_lieu_enregistre_ajax'; // Route Symfony gérant l'ajout
var ROUTE_MAJ_ARBRE = 'livre_lieu_affiche_arbre_lieu_ajax';

var TYPE_LIEU_INPUT = null;
var MODAL_AJOUTER_LIEU  = null;
var FORM_AJOUTER_LIEU = null;
var AJOUTER_LIEU = null;
var DDL_TYPE_LIEU = null;
var BTN_AJOUTER_LIEU = null;
var PROGRESS_BAR_DIV = null;
var PROGRESS_BAR = null;
var DIV_MES_LIEUX = 'div_mes_lieux';

var dureeAttenteChargement = 500;
var chargementTimeout = null;
$(document).ready(function () {
    // Mise en place des constantes
    MODAL_AJOUTER_LIEU = $('#'+MODAL_AJOUTER_LIEU_ID);
    AJOUTER_LIEU = $('#'+AJOUTER_LIEU_ID);
    FORM_AJOUTER_LIEU = AJOUTER_LIEU.find('form');
    DDL_TYPE_LIEU = $('#'+DDL_TYPE_LIEU_ID);
    BTN_AJOUTER_LIEU = $('#'+BTN_AJOUTER_LIEU_ID);
    PROGRESS_BAR_DIV = $('#'+PROGRESS_BAR_DIV_ID);
    PROGRESS_BAR =  PROGRESS_BAR_DIV.find('.progress-bar');
    DIV_MES_LIEUX = $('#'+DIV_MES_LIEUX_ID);

    // Evenements
    DDL_TYPE_LIEU.on('change', function(){
        gereFormulaireLieu();
    });
    BTN_AJOUTER_LIEU.on('click', function(){
        enregistreFormulaireLieu();
    });
    PROGRESS_BAR_DIV.hide();
    eventArbreLieu();

});
// Gere le formulaire d'un lieu
function gereFormulaireLieu(){
    setAjaxWorking(AJOUTER_LIEU_ID);
    var url = Routing.generate(ROUTE_LIEU_AJOUT);
    var formLieuData = new FormData(FORM_AJOUTER_LIEU.get(0));
    $.ajax({
        url: url,
        type: 'POST',
        data: formLieuData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (retour, statut) {
            MODAL_AJOUTER_LIEU.find('.modal-body').html(retour);
            MODAL_AJOUTER_LIEU.modal('show');
            unsetAjaxWorking(AJOUTER_LIEU_ID);
        },

        error: function (resultat, statut, erreur) {
            unsetAjaxWorking(AJOUTER_LIEU_ID);
        }
    });
}
// Enregistre un lieu
function enregistreFormulaireLieu(){
    lanceTimerEnregistrement();
    var url = Routing.generate(ROUTE_LIEU_ENREGISTRE, {'typeLieu': DDL_TYPE_LIEU.val()});
    var formLieuData = new FormData(MODAL_AJOUTER_LIEU.find('form').get(0));
    $.ajax({
        url: url,
        type: 'POST',
        data: formLieuData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (retour, statut) {
            arreteTimerEnregistrement();
            var code = retour.code;
            var html = retour.html;
            if(code == 200){
                MODAL_AJOUTER_LIEU.modal('hide');
                updateArbreLieu();
            }else {
                MODAL_AJOUTER_LIEU.find('.modal-body').html(html);
            }
        },

        error: function (resultat, statut, erreur) {
            arreteTimerEnregistrement();
        }
    });
}
// Gère le timer de la modal
function timerEnregistrement(){
    PROGRESS_BAR_DIV.show();
    var pourcentage = parseInt( PROGRESS_BAR.attr('aria-valuenow'));
    if(pourcentage>=100)
        pourcentage = 0;
    else
        pourcentage = 20+pourcentage;
    setPourcentageTimer(pourcentage);
    chargementTimeout = setTimeout(timerEnregistrement, dureeAttenteChargement);
}
// Lancement le timer d'enregisterment
function lanceTimerEnregistrement(){
    setPourcentageTimer(0);
    BTN_AJOUTER_LIEU.attr('disabled', true);
    timerEnregistrement();
}
// Arrêt du timer
function arreteTimerEnregistrement(){
    clearTimeout(chargementTimeout);
    PROGRESS_BAR.find('.progress-bar');
    PROGRESS_BAR_DIV.hide();
    BTN_AJOUTER_LIEU.attr('disabled', false);
}
// Gère la progression du timer
function setPourcentageTimer(pourcentage){
    PROGRESS_BAR.attr('aria-valuenow', pourcentage );
    PROGRESS_BAR.width(pourcentage+'%' );
}
// Gère l'arbre de données
function eventArbreLieu(){
    //Source :  http://jsfiddle.net/jhfrench/GpdgF/
    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Fermer');
    $('.tree li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Ouvrir').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
        } else {
            children.show('fast');
            $(this).attr('title', 'Fermer').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
        }
        e.stopPropagation();
    });
}
// Met à jour l'adre des données
function updateArbreLieu(){
    setAjaxWorking(DIV_MES_LIEUX_ID);
    var url = Routing.generate(ROUTE_MAJ_ARBRE);
    $.ajax({
        url: url,
        type: 'POST',
        contentType: false,
        cache: false,
        processData: false,
        success: function (retour, statut) {
            DIV_MES_LIEUX.html(retour);
            unsetAjaxWorking(DIV_MES_LIEUX_ID);
        },

        error: function (resultat, statut, erreur) {
            unsetAjaxWorking(DIV_MES_LIEUX_ID);
        }
    });
}