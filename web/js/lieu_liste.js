// Quelques constantes
var TYPE_LIEU_INPUT_ID = 'livrebundle_lieu_btnChoix'; // ID du bouton du formulaire de rajout d'un lieu
var AJOUTER_LIEU_ID = 'div_ajouter_lieu';
var MODAL_AJOUTER_LIEU_ID = 'modalAjouterLieu';
var DDL_TYPE_LIEU_ID = 'livrebundle_lieu_typeLieu';
var BTN_AJOUTER_LIEU_ID = 'btnAjouterLieu';

var ROUTE_LIEU_AJOUT = 'livre_lieu_form_ajax'; // Route Symfony gérant l'ajout
var ROUTE_LIEU_ENREGISTRE = 'livre_lieu_enregistre_ajax'; // Route Symfony gérant l'ajout

var TYPE_LIEU_INPUT = null;
var MODAL_AJOUTER_LIEU  = null;
var FORM_AJOUTER_LIEU = null;
var AJOUTER_LIEU = null;
var DDL_TYPE_LIEU = null;
var BTN_AJOUTER_LIEU = null;
$(document).ready(function () {
    // Mise en place des constantes
    TYPE_LIEU_INPUT = $('#'+TYPE_LIEU_INPUT_ID);
    MODAL_AJOUTER_LIEU = $('#'+MODAL_AJOUTER_LIEU_ID);
    AJOUTER_LIEU = $('#'+AJOUTER_LIEU_ID);
    FORM_AJOUTER_LIEU = AJOUTER_LIEU.find('form');
    DDL_TYPE_LIEU = $('#'+DDL_TYPE_LIEU_ID);
    BTN_AJOUTER_LIEU = $('#'+BTN_AJOUTER_LIEU_ID);

    // Evenements
    TYPE_LIEU_INPUT.on('click', function(){
        gereFormulaireLieu();
    });
    BTN_AJOUTER_LIEU.on('click', function(){
        enregistreFormulaireLieu();
    });


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
    setAjaxWorking(MODAL_AJOUTER_LIEU_ID);
    var url = Routing.generate(ROUTE_LIEU_ENREGISTRE, {'typeLieu': DDL_TYPE_LIEU.val()});
    console.log(url);
    var formLieuData = new FormData(MODAL_AJOUTER_LIEU.find('form').get(0));
    $.ajax({
        url: url,
        type: 'POST',
        data: formLieuData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (retour, statut) {
            unsetAjaxWorking(MODAL_AJOUTER_LIEU_ID);
            var code = retour.code;
            var html = retour.html;
            if(code == 200){
                MODAL_AJOUTER_LIEU.modal('hide');
            }else {
                MODAL_AJOUTER_LIEU.find('.modal-body').html(html);
            }
        },

        error: function (resultat, statut, erreur) {
            unsetAjaxWorking(MODAL_AJOUTER_LIEU_ID);
        }
    });
}