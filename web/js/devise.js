$(document).ready(function() {

  $("#sltDevise").change(function () {
    url = Routing.generate('commun_devise_get_ajax', {'id':$(this).val()})
    $.ajax({
      url : url,
      type : 'GET',
      dataType : 'json',
      success : function(json, statut){ // success est toujours en place, bien sûr !
        console.log(json)
      },

      error : function(resultat, statut, erreur){
        console.log('*****erreur*****');
        console.log(resultat);
        console.log(statut);
        console.log(erreur);
        console.log('**********');
      }

    });
  });
});