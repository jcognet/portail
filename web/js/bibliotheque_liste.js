// Quelques constantes
var ISBN_INPUT_ID = 'livrebundle_rechercheIBSNLivre_isbn'; // ID du champ input de l'isbn
var ISBN_BUTTON_ID = 'livrebundle_rechercheIBSNLivre_btnAjouterLivre'; // ID du button qui "valide" le formulaire
var ISBN_INPUT = null; // Input ISBN (chargé dans le onload)
var ISBN_BUTTON = null; // Button (chargé dans le onload)
// Quelques variables globales à la page
var isbnInputTimer;
var dureeAttenteSaisie = 5000;
$(document).ready(function(){
    // Mise a jour des champs
    ISBN_INPUT =$('#'+ISBN_INPUT_ID);
    ISBN_BUTTON = $('#'+ISBN_BUTTON);

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
    }else{
        console.log('not isbn')
    }
}

// Vérifie si une donnée est un code ISBN
// Source : https://neilang.com/articles/how-to-check-if-an-isbn-is-valid-in-javascript/
function isISBN (isbn) {
    isbn = isbn.replace(/[^\dX]/gi, '');
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
    };
    return ((sum % 11) == 0);
}
