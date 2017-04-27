$(document).ready(function () {
    addEventNews();
});
function chargeNews(newsId){
    var divId = 'block_news';
    // Init des variables
    url = Routing.generate('commun_news_charge_ajax', {'id':newsId});
    setAjaxWorking(divId);
    $.ajax({
        url : url,
        type : 'GET',
        dataType : 'html',
        success : function(data, statut){ // success est toujours en place, bien sûr !
            $('#'+divId).html(data);
            addEventNews();
            unsetAjaxWorking(divId);
        },

        error : function(resultat, statut, erreur){
            unsetAjaxWorking(divId);
        }

    });
}

function addEventNews(){
    $('.div_news_lien').on('click', function(e){
        if($(this)[0].hasAttribute('data-news-id')){
            chargeNews($(this).attr('data-news-id'));
        }
    })
}