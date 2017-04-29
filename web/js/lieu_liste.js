// Quelques constantes
var TYPE_LIEU_INPUT_ID = 'livrebundle_lieu_btnChoix'; // ID du bouton du formulaire de rajout d'un lieu
var AJOUTER_LIEU_ID = 'div_ajouter_lieu';
var MODAL_AJOUTER_LIEU_ID = 'modalAjouterLieu';

var ROUTE_LIEU_AJOUT = 'livre_lieu_nouveau_lieu_ajax'; // Route Symfony gérant l'ajout

var TYPE_LIEU_INPUT = null;
var MODAL_AJOUTER_LIEU  = null;
var FORM_AJOUTER_LIEU = null;
var AJOUTER_LIEU = null;
$(document).ready(function () {
    // Mise en place des constantes
    TYPE_LIEU_INPUT = $('#'+TYPE_LIEU_INPUT_ID);
    MODAL_AJOUTER_LIEU = $('#'+MODAL_AJOUTER_LIEU_ID);
    AJOUTER_LIEU = $('#'+AJOUTER_LIEU_ID);
    FORM_AJOUTER_LIEU = AJOUTER_LIEU.find('form');

    // Evenements
    TYPE_LIEU_INPUT.on('click', function(){
        gereFormulaireLieu();
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