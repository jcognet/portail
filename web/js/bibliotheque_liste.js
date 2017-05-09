// Quelques constantes
var ISBN_INPUT_ID = 'livrebundle_rechercheIBSNLivre_isbn'; // ID du champ input de l'isbn
var ISBN_BUTTON_ID = 'livrebundle_rechercheIBSNLivre_btnAjouterLivre'; // ID du button qui "valide" le formulaire
var ISBN_DIV_ID = 'form_ajout'; // id de la div avec le champ ISBN
var LISTE_AJOUT_DIV_ID = 'liste_livre_ajout'; // id de la div après laquelle le nouveau livre sera ajouté
var ERREUR_DIV_ID = 'form_ajout_erreur'; // id de la div avec les erreurs
var DETAIL_DIV_ID = 'detail_livre';

var ROUTE_AJOUT = 'livre_bibliotheque_ajout_ajax'; // Route Symfony gérant l'ajout
var ROUTE_DETAIL = 'livre_detail_ajax';
var ROUTE_MODIFIE = 'livre_bibliotheque_modifie_ajax';
var ROUTE_SUPPRESSION = 'livre_bibliotheque_supprime_ajax';

var ISBN_INPUT = null;
var ISBN_BUTTON = null;
var ISBN_FORM_JS = null;
var LISTE_AJOUT_DIV = null;
var ERREUR_DIV = null;
var DETAIL_DIV = null;
// Quelques variables globales à la page
var isbnInputTimer;
var dureeAttenteSaisie = 2000; // En seconde
var bufferLivre = new Array();
var ajoutLivreEnCours = false;
$(document).ready(function () {
    // Mise a jour des champs
    ISBN_INPUT = $('#' + ISBN_INPUT_ID);
    ISBN_BUTTON = $('#' + ISBN_BUTTON_ID);
    ISBN_FORM_JS = ISBN_INPUT.closest('form').get(0);
    LISTE_AJOUT_DIV = $('#' + LISTE_AJOUT_DIV_ID);
    ERREUR_DIV = $('#' + ERREUR_DIV_ID);
    DETAIL_DIV = $('#' + DETAIL_DIV_ID);

    // Evenements
    // saisi dans le champ ISBN
    ISBN_INPUT.on('keydown', function (e) {
        clearTimeout(isbnInputTimer);
        attenteInputISBN();
    });
    // Click sur le bouton
    ISBN_BUTTON.on('click', function (e) {
        clearTimeout(isbnInputTimer);
        gereInputISBN();
    });
    addEvent();

    ERREUR_DIV.hide();
});

// function ajout livre à la bibliotheque
function ajouteLivre() {
    ajoutLivreEnCours = true;
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
            if (code == 200) {
                LISTE_AJOUT_DIV.append(html);
                ISBN_INPUT.val('');
                addEvent();
            } else {
                ERREUR_DIV.html(html);
                ERREUR_DIV.slideDown();
                ISBN_INPUT.trigger('blur');
            }
            unsetAjaxWorking(ISBN_DIV_ID);
            ajoutLivreEnCours = false;
        },

        error: function (resultat, statut, erreur) {
            unsetAjaxWorking(ISBN_DIV_ID);
        }
    });

}
// Gere le timer de l'input isbn
function attenteInputISBN() {
    isbnInputTimer = setTimeout(gereInputISBN, dureeAttenteSaisie);
}
// Gere la saiei de l'utilisateur
function gereInputISBN() {
    if (isISBN(ISBN_INPUT.val()) && !ajoutLivreEnCours ) {
        ajouteLivre();
    } else {
        console.log('no isbn')
    }
}
// Nettoie l'iSBN
function nettoieISBN(isbn) {
    return isbn.trim().replace(/[^\dX]/gi, '');
}

// Vérifie si une donnée est un code ISBN
// Source : https://neilang.com/articles/how-to-check-if-an-isbn-is-valid-in-javascript/
function isISBN(isbn) {
    isbn = nettoieISBN(isbn)
    if (isbn.length != 10) {
        return false;
    }
    var chars = isbn.split('');
    if (chars[9].toUpperCase() == 'X') {
        chars[9] = 10;
    }
    var sum = 0;
    for (var i = 0; i < chars.length; i++) {
        sum += ((10 - i) * parseInt(chars[i]));
    }
    return ((sum % 11) == 0);
}
// Affiche le détail d'un livre
function afficheDetailLivre(baseLivreId, divParent) {
    // Cas où le livre n'est pas encore dans le buffer
    if (!(baseLivreId in bufferLivre)) {
        return rechercheLivre(baseLivreId, divParent);
    }
    afficheLivre(baseLivreId);
}
// Ajout les événements
function addEvent() {
    var rowLivre =$('#liste_livre .row');
    // Ajout de l'action pour apparaîte la pop in
    $('.lien_pop_in input').off('click');
    $('.lien_pop_in').on('click', function () {
        var rowParent = $(this).closest('.row');
        var livreId = rowParent.attr('data-base-livre-id');
        afficheDetailLivre(livreId, rowParent);
    });
    // Mise en place du style alterné
    var iRowLivre = 0;
    rowLivre.each(function(e){
        $(this).removeClass('active');
        if(iRowLivre%2 == 0)
            $(this).addClass('active');
        iRowLivre++;

    });
    // Correction de la hauteur de la première ligne car elle n'a pas de input
    if(rowLivre.length>=2){
        rowLivre.get(0).style.height =rowLivre.get(1).offsetHeight+'px';
    }
    // Modification d'un livre
    var liste_input = $('#liste_livre input');
    liste_input.off('change');
    liste_input.on('change', function () {
        var rowParent = $(this).closest('.row');
        var livreId = rowParent.attr('data-livre-id');
        modifieLivre(livreId, rowParent);
    });

    // Suppression d'un livre
    var liste_suppression =$('#liste_livre .lnk_suppression_livre');
    liste_suppression.off('click');
    liste_suppression.on('click', function () {
        if(!confirm('Voulez-vous vraiment supprimer cet élément ? ')){
            e.preventDefault();
            return false;
        }

        var rowParent = $(this).closest('.row');
        var livreId = rowParent.attr('data-livre-id');
        supprimeLivre(livreId, rowParent);
    });

    gereLieuLivre();
    gereAffichageLieuInSelect();
}
// Affiche un livre en pop in
function afficheLivre(baseLivreId) {
    if (baseLivreId in bufferLivre) {
        DETAIL_DIV.html(bufferLivre[baseLivreId]);
    }
    livreBuffer();
}
// Recherche le livre en baseLivreId
function rechercheLivre(baseLivreId, divParent) {
    setAjaxWorking(divParent.attr('id'));
    var url = Routing.generate(ROUTE_DETAIL, {'id': baseLivreId});
    $.ajax({
        url: url,
        type: 'POST',
        contentType: false,
        cache: false,
        processData: false,
        success: function (retour, statut) { // success est toujours en place, bien sûr !
            unsetAjaxWorking(divParent.attr('id'));
            bufferLivre[baseLivreId] = retour;
            afficheLivre(baseLivreId);
        },

        error: function (resultat, statut, erreur) {
            unsetAjaxWorking(divParent.attr('id'));
        }
    });
}
// Affiche les livres connus dans le buffer
function livreBuffer(){
    // Affichage d'une icone avant chaque élément dans le buffer
    for (var livreId in bufferLivre) {
        var element =  $("div[data-base-livre-id="+livreId+"]");
        console.log(livreId)
        var caseName = $(element.find('.lien_pop_in').get(0));
        console.log(caseName)
        if(caseName.find('.glyphicon-record').length ==0)
            caseName.prepend('<span class="glyphicon glyphicon-record"></span>');
    }
}
// Modifie un livre
function modifieLivre(livreId, divParent){
    setAjaxWorking(divParent.attr('id'));
    //var formModifieData = new FormData(divParent.find('form').get(0));
    var formModifieData = (divParent.find('form')).serialize();
    var url = Routing.generate(ROUTE_MODIFIE, {'id': livreId});
    $.ajax({
        url: url,
        type: 'POST',
        data: formModifieData,
        cache: false,
        success: function (block_html, statut) { // success est toujours en place, bien sûr !
            divParent.replaceWith(block_html);
            unsetAjaxWorking(divParent.attr('id'));
            addEvent();
        },

        error: function (resultat, statut, erreur) {
            unsetAjaxWorking(divParent.attr('id'));
            addEvent();
        }
    });
}
// Supprime un livre
function supprimeLivre(livreId, divParent){
    setAjaxWorking(divParent.attr('id'));
    var url = Routing.generate(ROUTE_SUPPRESSION, {'id': livreId});
    $.ajax({
        url: url,
        type: 'POST',
        cache: false,
        success: function (block_html, statut) { // success est toujours en place, bien sûr !
            unsetAjaxWorking(divParent.attr('id'));
            divParent.remove();
            addEvent();
        },

        error: function (resultat, statut, erreur) {
            unsetAjaxWorking(divParent.attr('id'));
            addEvent();
        }
    });
}
// Gère le changement de lieu
function gereLieuLivre(){
    // Gestion des menu déroulants cachés
    var ddl_lieu_main = $('.ddl_lieu_main');
    ddl_lieu_main.off('change');
    ddl_lieu_main.on('change', function(e){
        var lieuChoisi = $(this).find(":selected");
        var lieuType =  lieuChoisi.attr('data-type');
        var lieuId = lieuChoisi.attr('data-id');
        var formLieu =$(this).closest('form');
        // Protection
        if(lieuType.length == 0 || lieuType.length == 0)
            return;
        // Remise a 0 de tous les menus déroulants
        formLieu.find('.ddl_lieu_class').val('');
        formLieu.find('select[data-type='+lieuType+']').val(lieuId);
    });
    // Gestion de l'envoi du formulaire
    var select_lieu = $('#liste_livre  select.ddl_lieu_main');
    select_lieu.off('change');
    select_lieu.on('change', function () {
        var rowParent = $(this).closest('.row');
        var livreId = rowParent.attr('data-livre-id');
        modifieLivre(livreId, rowParent);
    });
}
// Ajout des paddings devant les lieux
function gereAffichageLieuInSelect(){
    $('option[data-type="piece"]').css('padding-left', 5);
    $('option[data-type="meuble"]').css('padding-left', 10);
    $('option[data-type="etagere"]').css('padding-left', 15);
}