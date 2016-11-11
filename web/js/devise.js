$(document).ready(function() {

  $("#sltDevise").change(function () {
      disableDdlDevise();
      afficheBlockDevise(divIdDevise, $(this).val());
  });
});

function disableDdlDevise(){
  $("#sltDevise").prop("disabled", true);
}
function enableDdlDevise(){
  $("#sltDevise").prop("disabled", false);
}

function afficheChart(json, divChart){
  var listeDate = [];
  var listeCoursDevise = [];
  // TODO : vérifier unicité sur les jours
  for (i = 0; i < json['cours'].length; i++) {
    listeDate.push(json['cours'][i]['date']);
    listeCoursDevise.push(parseFloat(json['cours'][i]['taux']));
  }

    Highcharts.chart(divChart, {
      title: {
        text: 'Cours par rapport à l\'euro',
        x: -20 //center
      },
      subtitle: {
        text: 'Source : http://www.fixer.io/',
        x: -20
      },
      xAxis: {
        categories: listeDate
      },
      yAxis: {
        title: {
          text: 'Cours'
        },
        plotLines: [{
          value: 0,
          width: 1,
          color: '#808080'
        }]
      },
      tooltip: {
        valueSuffix: json['symbole']
      },
      legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
      },
      series: [{
        name: json['label'],
        data: listeCoursDevise
      }]
    });
}

function afficheBlockDevise(blockId, deviseId){
  setAjaxWorking(blockId);
  url = Routing.generate('commun_devise_affiche_ajax', {'id':deviseId});
  $.ajax({
    url : url,
    type : 'GET',
    dataType : 'html',
    success : function(block_html, statut){ // success est toujours en place, bien sûr !
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