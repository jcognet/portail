$(document).ready(function () {
    $('.news_content').hide();
    addEventTr();
});

function addEventTr(){
    $("#table_news tr").on('click', function(e){
        if($(this)[0].hasAttribute('data-news-id')){
            var divNews = $('#news_content_'+$(this).attr('data-news-id'));
            divNews.fadeToggle();
        }
    });
}