// Quelques constantes
var BTN_CHOIX_LIEU_ID = 'livrebundle_lieu_btnChoix'; // ID du bouton du formulaire de rajout d'un lieu
var AJOUTER_LIEU_ID = 'div_ajouter_lieu';
var MODAL_AJOUTER_LIEU_ID = 'modalAjouterLieu';
var DDL_TYPE_LIEU_ID = 'livrebundle_lieu_typeLieu';
var BTN_ENREGISTRER_LIEU_ID = 'btnEnregistrerLieu';
var PROGRESS_BAR_DIV_ID = 'progress_bar';
var DIV_MES_LIEUX_ID = 'div_mes_lieux';
var HID_TYPE_LIEU_ID = 'hidTypeLieu';
var HID_LIEU_ID = 'hidLieuId';

var ROUTE_LIEU_AJOUT = 'livre_lieu_form_ajax'; // Route Symfony gérant l'ajout
var ROUTE_LIEU_ENREGISTRE = 'livre_lieu_enregistre_ajax'; // Route Symfony gérant l'ajout
var ROUTE_MAJ_ARBRE = 'livre_lieu_affiche_arbre_lieu_ajax';
var ROUTE_FORM_ENREGISTRE = 'livre_lieu_form_update_ajax';
var ROUTE_FORM_SUPPRIMER = 'livre_lieu_form_supprime_ajax';

var BTN_CHOIX_LIEU = null;
var MODAL_AJOUTER_LIEU = null;
var FORM_AJOUTER_LIEU = null;
var AJOUTER_LIEU = null;
var DDL_TYPE_LIEU = null;
var BTN_ENREGISTRER_LIEU = null;
var PROGRESS_BAR_DIV = null;
var PROGRESS_BAR = null;
var DIV_MES_LIEUX = null;
var HID_TYPE_LIEU = null;
var HID_LIEU = null;

var dureeAttenteChargement = 500;
var chargementTimeout = null;
$(document).ready(function () {
    // Mise en place des constantes
    BTN_CHOIX_LIEU = $('#' + BTN_CHOIX_LIEU_ID);
    MODAL_AJOUTER_LIEU = $('#' + MODAL_AJOUTER_LIEU_ID);
    AJOUTER_LIEU = $('#' + AJOUTER_LIEU_ID);
    FORM_AJOUTER_LIEU = AJOUTER_LIEU.find('form');
    DDL_TYPE_LIEU = $('#' + DDL_TYPE_LIEU_ID);
    BTN_ENREGISTRER_LIEU = $('#' + BTN_ENREGISTRER_LIEU_ID);
    PROGRESS_BAR_DIV = $('#' + PROGRESS_BAR_DIV_ID);
    PROGRESS_BAR = PROGRESS_BAR_DIV.find('.progress-bar');
    DIV_MES_LIEUX = $('#' + DIV_MES_LIEUX_ID);
    HID_TYPE_LIEU = $('#' + HID_TYPE_LIEU_ID);
    HID_LIEU = $('#' + HID_LIEU_ID);

    // Evenements
    DDL_TYPE_LIEU.on('change', function () {
        HID_TYPE_LIEU.val(DDL_TYPE_LIEU.val());
        if(HID_TYPE_LIEU.val().length>0)
            gereFormulaireLieu();
    });
    BTN_CHOIX_LIEU.on('click', function(){
        HID_TYPE_LIEU.val(DDL_TYPE_LIEU.val());
        if(HID_TYPE_LIEU.val().length>0)
            gereFormulaireLieu();
    });
    BTN_ENREGISTRER_LIEU.on('click', function () {
        enregistreFormulaireLieu();
    });
    PROGRESS_BAR_DIV.hide();
    eventArbreLieu();
});
// Gere le formulaire d'un lieu
function gereFormulaireLieu() {
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
function enregistreFormulaireLieu() {
    lanceTimerEnregistrement();
    var url = Routing.generate(ROUTE_LIEU_ENREGISTRE, {'typeLieu': HID_TYPE_LIEU.val(), 'id': HID_LIEU.val() });
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
            if (code == 200) {
                MODAL_AJOUTER_LIEU.modal('hide');
                updateArbreLieu();
            } else {
                MODAL_AJOUTER_LIEU.find('.modal-body').html(html);
            }
        },

        error: function (resultat, statut, erreur) {
            arreteTimerEnregistrement();
        }
    });
}
// Gère le timer de la modal
function timerEnregistrement() {
    PROGRESS_BAR_DIV.show();
    var pourcentage = parseInt(PROGRESS_BAR.attr('aria-valuenow'));
    if (pourcentage >= 100)
        pourcentage = 0;
    else
        pourcentage = 20 + pourcentage;
    setPourcentageTimer(pourcentage);
    chargementTimeout = setTimeout(timerEnregistrement, dureeAttenteChargement);
}
// Lancement le timer d'enregisterment
function lanceTimerEnregistrement() {
    setPourcentageTimer(0);
    BTN_ENREGISTRER_LIEU.attr('disabled', true);
    timerEnregistrement();
}
// Arrêt du timer
function arreteTimerEnregistrement() {
    clearTimeout(chargementTimeout);
    PROGRESS_BAR.find('.progress-bar');
    PROGRESS_BAR_DIV.hide();
    BTN_ENREGISTRER_LIEU.attr('disabled', false);
}
// Gère la progression du timer
function setPourcentageTimer(pourcentage) {
    PROGRESS_BAR.attr('aria-valuenow', pourcentage);
    PROGRESS_BAR.width(pourcentage + '%');
}
// Gère l'arbre de données
function eventArbreLieu() {
    addEventArbre();
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
function updateArbreLieu() {
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
            eventArbreLieu();
            unsetAjaxWorking(DIV_MES_LIEUX_ID);
        },

        error: function (resultat, statut, erreur) {
            unsetAjaxWorking(DIV_MES_LIEUX_ID);
        }
    });
}
// Gère les évènements de l'ardre
function addEventArbre() {
    // Mise à jour d'un lieu
    $('.lieu_update').on('click', function (e) {
        var typeLieu = $(this).closest('li').attr('data-type');
        var lieuId = $(this).closest('li').attr('data-id');
        updateLieu(typeLieu, lieuId);
        e.preventDefault();
    });
    // Suppression d'un lieu
    $('.lieu_supprime').on('click', function (e) {
        var typeLieu = $(this).closest('li').attr('data-type');
        var lieuId = $(this).closest('li').attr('data-id');
        if(confirm("Souhaitez-vous supprimer cet élément et ses enfants ? "))
            supprimeLieu(typeLieu, lieuId);
        e.preventDefault();
    });
}
// Met à jour un lieu
function updateLieu(typeLieu, lieuId) {
    setAjaxWorking(DIV_MES_LIEUX_ID); // Le unset est géré dans updateArbreLieu
    var url = Routing.generate(ROUTE_FORM_ENREGISTRE, {'id': lieuId, 'typeLieu': typeLieu});
    HID_TYPE_LIEU.val(typeLieu);
    HID_LIEU.val(lieuId);
    $.ajax({
        url: url,
        type: 'POST',
        contentType: false,
        cache: false,
        processData: false,
        success: function (retour, statut) {
            MODAL_AJOUTER_LIEU.find('.modal-body').html(retour);
            MODAL_AJOUTER_LIEU.modal('show');
            updateArbreLieu();
        },

        error: function (resultat, statut, erreur) {
            updateArbreLieu();
        }
    });
}
// Supprime un lieu
function supprimeLieu(typeLieu, lieuId){
    var url = Routing.generate(ROUTE_FORM_SUPPRIMER, {'id': lieuId, 'typeLieu': typeLieu});
    $.ajax({
        url: url,
        type: 'POST',
        contentType: false,
        cache: false,
        processData: false,
        success: function (retour, statut) {
            updateArbreLieu();
        },

        error: function (resultat, statut, erreur) {
            updateArbreLieu();
        }
    });
}