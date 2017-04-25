// Quelques constantes
var ISBN_INPUT_ID = 'livrebundle_rechercheIBSNLivre_isbn'; // ID du champ input de l'isbn
var ISBN_BUTTON_ID = 'livrebundle_rechercheIBSNLivre_btnAjouterLivre'; // ID du button qui "valide" le formulaire
var ISBN_DIV_ID = 'form_ajout'; // id de la div avec le champ ISBN
var LISTE_AJOUT_DIV_ID = 'liste_livre_ajout'; // id de la div après laquelle le nouveau livre sera ajouté

var ROUTE_AJOUT = 'livre_bibliotheque_ajout'; // Route Symfony gérant l'ajout

var ISBN_INPUT = null; // Input ISBN (chargé dans le onload)
var ISBN_BUTTON = null; // Button (chargé dans le onload)
var ISBN_FORM_JS = null;
var LISTE_AJOUT_DIV = null;
// Quelques variables globales à la page
var isbnInputTimer;
var dureeAttenteSaisie = 5000;
$(document).ready(function(){
    // Mise a jour des champs
    ISBN_INPUT =$('#'+ISBN_INPUT_ID);
    ISBN_BUTTON = $('#'+ISBN_BUTTON_ID);
    ISBN_FORM_JS = ISBN_INPUT.parents('form').get(0);
    LISTE_AJOUT_DIV = $('#'+LISTE_AJOUT_DIV_ID);

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
});

// function ajout livre à la bibliotheque
function ajouteLivre(){
    // On retire les mauvais caractères
    isbn = nettoieISBN(ISBN_INPUT.val());
    ISBN_INPUT.val(isbn);
    // Lancement de l'ajax
    setAjaxWorking(ISBN_DIV_ID);
    var formAjout = new FormData(ISBN_FORM_JS);
    url = Routing.generate(ROUTE_AJOUT)
    $.ajax({
        url: url,
        type: 'POST',
        data: formAjout,
        contentType: false,
        cache: false,
        processData: false,
        success: function (block_html, statut) { // success est toujours en place, bien sûr !
            LISTE_AJOUT_DIV.append(block_html);
            ISBN_INPUT.val('');
            unsetAjaxWorking(ISBN_DIV_ID);
        },

        error: function (resultat, statut, erreur) {
            console.log('*****erreur*****');
            console.log(resultat);
            console.log(statut);
            console.log(erreur);
            console.log('**********');
            unsetAjaxWorking(blockListeId);
            addEventDatagrid();
        }
    });

    console.log('ajoute livre');
}
// Gere le timer de l'input isbn
function attenteInputISBN(){
    isbnInputTimer = setTimeout(gereInputISBN, dureeAttenteSaisie);
}
// Gere la saiei de l'utilisateur
function gereInputISBN(){
    if(isISBN(ISBN_INPUT.val())){
        ajouteLivre();
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
