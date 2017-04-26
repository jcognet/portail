// Quelques constantes
var ISBN_INPUT_ID = 'livrebundle_rechercheIBSNLivre_isbn'; // ID du champ input de l'isbn
var ISBN_BUTTON_ID = 'livrebundle_rechercheIBSNLivre_btnAjouterLivre'; // ID du button qui "valide" le formulaire
var ISBN_DIV_ID = 'form_ajout'; // id de la div avec le champ ISBN
var LISTE_AJOUT_DIV_ID = 'liste_livre_ajout'; // id de la div après laquelle le nouveau livre sera ajouté
var ERREUR_DIV_ID = 'form_ajout_erreur'; // id de la div avec les erreurs
var MODAL_DETAIL_ID = 'modalDetail'; // id de la div de la modele

var ROUTE_AJOUT = 'livre_bibliotheque_ajout'; // Route Symfony gérant l'ajout
var ROUTE_DETAIL_POP_IN = 'livre_detail_pop_in';

var ISBN_INPUT = null;
var ISBN_BUTTON = null;
var ISBN_FORM_JS = null;
var LISTE_AJOUT_DIV = null;
var ERREUR_DIV = null;
var MODAL_DETAIL = null;
// Quelques variables globales à la page
var isbnInputTimer;
var dureeAttenteSaisie = 2000; // En seconde
$(document).ready(function(){
    // Mise a jour des champs
    ISBN_INPUT =$('#'+ISBN_INPUT_ID);
    ISBN_BUTTON = $('#'+ISBN_BUTTON_ID);
    ISBN_FORM_JS = ISBN_INPUT.parents('form').get(0);
    LISTE_AJOUT_DIV = $('#'+LISTE_AJOUT_DIV_ID);
    ERREUR_DIV =  $('#'+ERREUR_DIV_ID);
    MODAL_DETAIL = $('#'+MODAL_DETAIL_ID);

    // Evenements
    // saisi dans le champ ISBN
    ISBN_INPUT.on('keydown', function(e){
        clearTimeout(isbnInputTimer);
        attenteInputISBN();
    });
    // Click sur le bouton
    ISBN_BUTTON .on('click', function(e){
        gereInputISBN();
    });
    addEvent();

    ERREUR_DIV.hide();
    MODAL_DETAIL.hide();
});

// function ajout livre à la bibliotheque
function ajouteLivre(){
    ERREUR_DIV.slideUp();
    // On retire les mauvais caractères
    isbn = nettoieISBN(ISBN_INPUT.val());
    ISBN_INPUT.val(isbn);
    // Lancement de l'ajax
    setAjaxWorking(ISBN_DIV_ID);
    var formAjout = new FormData(ISBN_FORM_JS);
    url = Routing.generate(ROUTE_AJOUT);
    $.ajax({
        url: url,
        type: 'POST',
        data: formAjout,
        contentType: false,
        cache: false,
        processData: false,
        success: function (retour, statut) { // success est toujours en place, bien sûr !
            var code = retour.code;
            var html = retour.html;
            if(code == 200) {
                LISTE_AJOUT_DIV.append(html);
                ISBN_INPUT.val('');
                addEvent();
            }else{
                console.log(ERREUR_DIV);
                console.log(html);
                ERREUR_DIV.html(html);
                ERREUR_DIV.slideDown();
                ISBN_INPUT.trigger('blur');
            }
            unsetAjaxWorking(ISBN_DIV_ID);
        },

        error: function (resultat, statut, erreur) {
            console.log('*****erreur*****');
            console.log(resultat);
            console.log(statut);
            console.log(erreur);
            console.log('**********');
            unsetAjaxWorking(ISBN_DIV_ID);
        }
    });

}
// Gere le timer de l'input isbn
function attenteInputISBN(){
    isbnInputTimer = setTimeout(gereInputISBN, dureeAttenteSaisie);
}
// Gere la saiei de l'utilisateur
function gereInputISBN(){
    if(isISBN(ISBN_INPUT.val())){
        ajouteLivre();
    }else{
        console.log('no isbn')
    }
}
// Nettoie l'iSBN
function nettoieISBN(isbn){
    return isbn.trim().replace(/[^\dX]/gi, '');;
}

// Vérifie si une donnée est un code ISBN
// Source : https://neilang.com/articles/how-to-check-if-an-isbn-is-valid-in-javascript/
function isISBN (isbn) {
    isbn = nettoieISBN(isbn)
    if(isbn.length != 10){
        return false;
    }
    var chars = isbn.split('');
    if(chars[9].toUpperCase() == 'X'){
        chars[9] = 10;
    }
    var sum = 0;
    for (var i = 0; i < chars.length; i++) {
        sum += ((10-i) * parseInt(chars[i]));
    }
    return ((sum % 11) == 0);
}
// Affiche la modale de détail d'un livre
 function afficheModalLivre(baseLivreId, divParent){
     MODAL_DETAIL.modal('hide');
     setAjaxWorking(divParent);
     url = Routing.generate(ROUTE_DETAIL_POP_IN, {'id': baseLivreId});
     $.ajax({
         url: url,
         type: 'POST',
         contentType: false,
         cache: false,
         processData: false,
         success: function (retour, statut) { // success est toujours en place, bien sûr !
             var titre = retour.titre;
             var html = retour.html;
             MODAL_DETAIL.find('.modal-title').html(titre)
             MODAL_DETAIL.find('.modal-body').html(html)
             MODAL_DETAIL.modal('show');
             unsetAjaxWorking(divParent);
         },

         error: function (resultat, statut, erreur) {
             console.log('*****erreur*****');
             console.log(resultat);
             console.log(statut);
             console.log(erreur);
             console.log('**********');
             unsetAjaxWorking(divParent);
         }
     });

     console.log('show')
 }
 // Ajout les événements
 function addEvent(){
     $('.titre_livre').on('mouseover', function(){
         console.log($(this).parents('.row'));
         var livreId =$(this).attr('data-livre-id');
         afficheModalLivre(livreId, $(this).parents('.row'));
     })
 }