// Id du block avec le détail du livre
var blockId = 'detail_livre';
$(document).ready(function () {
    $('#liste_livres tr').on('click', function(e){
        var livreId = $(this).attr('data-id');
        console.log(livreId);
        if(livreId>0){
            getDetailLivre(livreId);
        }
    });
});

function getDetailLivre(id){
    setAjaxWorking(blockId);
    console.log('start')
    url = Routing.generate('book_ajax_edit', {'id':id});
    console.log(url)
    $.ajax({
        url : url,
        type : 'GET',
        dataType : 'html',
        success : function(block_html, statut){ // success est toujours en place, bien sûr !
            console.log('success')
            $('#'+blockId).html(block_html);
            unsetAjaxWorking(blockId);
            enableDdlDevise();
        },

        error : function(resultat, statut, erreur){
            console.log('*****erreur*****');
            console.log(resultat);
            console.log(statut);
            console.log(erreur);
            console.log('**********');
            unsetAjaxWorking(blockId);
            enableDdlDevise();
        }

    });
}