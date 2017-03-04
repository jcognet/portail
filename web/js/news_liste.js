$(document).ready(function () {
    addEventTr();
});

function addEventTr(){
    $("#table_news tr").on('click', function(e){
        if($(this)[0].hasAttribute('data-news-id')){
            var divNews = $(this).attr('data-news-id');
            divNews.toggle();
        }
    });
}