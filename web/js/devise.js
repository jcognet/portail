$(document).ready(function() {

  $("#sltDevise").change(function () {
      disableDdlDevise();
      afficheBlockDevise(divIdDevise, $(this).val());
  });
  $("#frmConnexion").submit(function () {
    $("#hidDevise").val($("#sltDevise").val());
  });

  $(".acces_rapide").click(function (e) {
    var deviseId =$(this).attr('data-devise-id')
    $("#sltDevise").val(deviseId);
    disableDdlDevise();
    afficheBlockDevise(divIdDevise, deviseId);
    e.preventDefault();
  });


});

function disableDdlDevise(){
  $("#sltDevise").prop("disabled", true);
}
function enableDdlDevise(){
  $("#sltDevise").prop("disabled", false);
}

function disableInput(input){
  input.prop("disabled", true);
}
function enableInput(input){
  input.prop("disabled", false);
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

function calculeSomme(input){
  // Init des variables
  var valeurEuros = 0;
  var valeurAutre = 0;
  var deviseId = 0;
  var divId = '';
  var inputResultat = '';
  // Gestion du dom
  divId = input.parents('.tr_calcul').attr('id');
  // Récupération des données
  if(input.hasClass('input_devise_euro')){
    valeurEuros = input.val();
    inputResultat = input.parents('.tr_calcul').find('.input_devise_autre');
  }else{
    valeurAutre = input.val();
    inputResultat = input.parents('.tr_calcul').find('.input_devise_euro');
  }
  deviseId = input.parents('.block_devise').find('.input_devise_id').val();
  // Aucune valeur => rien à faire
  if(valeurEuros.length ==0 && valeurAutre.length ==0 ){
    return;
  }
  if(deviseId.length == 0){
    return;
  }
  setAjaxWorking(divId);
  disableInput(inputResultat);

  url = Routing.generate('commun_devise_calcul_ajax', {'id':deviseId, 'valeurEuros':valeurEuros, 'valeurAutre':valeurAutre});
  $.ajax({
    url : url,
    type : 'GET',
    dataType : 'json',
    success : function(data, statut){ // success est toujours en place, bien sûr !
      inputResultat.val(data);
      unsetAjaxWorking(divId);
      enableInput(inputResultat);
    },

    error : function(resultat, statut, erreur){
      console.log('*****erreur*****');
      console.log(resultat);
      console.log(statut);
      console.log(erreur);
      console.log('**********');
      unsetAjaxWorking(divId);
      enableInput(inputResultat);
    }

  });
}

function enregistreSuiviDevise(input){
  // Init des variables
  var deviseId = input.parents('.block_devise').find('.input_devise_id').val();
  var seuil = input.val();
  var seuilMax = false;
  var divId = input.parents('tr').attr('id');
  if(input.hasClass('input_seuil_max')){
    seuilMax = true
  }
  if(seuil.length ==0){
      seuil = 0;
  }
  url = Routing.generate('commun_devise_sauve_ajax', {'id':deviseId, 'seuilMax':seuilMax, 'seuil':seuil});
  setAjaxWorking(divId);
  $.ajax({
    url : url,
    type : 'GET',
    dataType : 'json',
    success : function(data, statut){ // success est toujours en place, bien sûr !
      unsetAjaxWorking(divId);
    },

    error : function(resultat, statut, erreur){
      console.log('*****erreur*****');
      console.log(resultat);
      console.log(statut);
      console.log(erreur);
      console.log('**********');
      unsetAjaxWorking(divId);
    }

  });
}