$(document).ready(function() {

  $("#sltDevise").change(function () {
    url = Routing.generate('commun_devise_get_ajax', {'id':$(this).val()})
    $.ajax({
      url : url,
      type : 'GET',
      dataType : 'json',
      success : function(json, statut){ // success est toujours en place, bien sûr !
        afficheChart(json);
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

function afficheChart(json){
  var listeDate = [];
  var listeCoursDevise = [];
  // TODO : vérifier unicité sur les jours
  for (i = 0; i < json.length; i++) {
    listeDate.push(json[i]['date']);
    listeCoursDevise.push(parseFloat(json[i]['cours']));
  }
  console.log(listeCoursDevise);
  console.log(listeDate);

    Highcharts.chart('container', {
      title: {
        text: 'Monthly Average Temperature',
        x: -20 //center
      },
      subtitle: {
        text: 'Source: WorldClimate.com',
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
        valueSuffix: '°C'
      },
      legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
      },
      series: [{
        name: 'Tokyo',
        data: listeCoursDevise
      }]
    });
}