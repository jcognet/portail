function setAjaxWorking(divId){
  console.log(divId);
  $("#"+divId).addClass('ajax');
}

function unsetAjaxWorking(divId){
  $("#"+divId).removeClass('ajax');
}