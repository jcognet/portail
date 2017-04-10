$(document).ready(function () {
    changeSort();
    alerteSiSuppression();
});

function setAjaxWorking(divId) {
    $("#" + divId).addClass('ajax');
    var loader = $("#loader_ajax").clone();
    loader.css('margin-left', $("#" + divId).width()/2 + 'px');
    loader.css('margin-top', $("#" + divId).height()/2 + 'px');
    loader.addClass('loader');
    loader.attr('id', 'loader_ajax_' + divId).show().prependTo("#" + divId);
}

function unsetAjaxWorking(divId) {
    $("#" + divId).removeClass('ajax');
    $("#loader_ajax_" + divId).remove();
}

function changeSort(){
    $('.sorted .asc').each(function(){
        $(this).append('<span class="glyphicon glyphicon-arrow-down"></span>');
    });
    $('.sorted .desc').each(function(){
        $(this).append('<span class="glyphicon glyphicon-arrow-up"></span>');
    });
}

function alerteSiSuppression(){
    $('.lnk_suppression').each(function(){
        $(this).click( function(e){
            if(!confirm('Voulez-vous vraiment supprimer cet élément ? ')){
                e.preventDefault();
                return false;
            }
        });
    });
}