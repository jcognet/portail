$(document).ready(function () {

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
