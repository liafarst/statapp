$( document ).ready(function() {

// Progress

  var maxSteps = 20;
  var stepID = 1;

  // event listeners
  $('.step-indicator').children().on('click', function(e){
    oldStepID = stepID;
    stepID = ($(this).attr('id').substring($(this).attr('id').indexOf("p") + 1));
    if(isFinished(oldStepID)){
      saveChanges(oldStepID);
    }
    changeStep();
  });

  $('.button-left').on('click', function(e){
    if(stepID > 1){
      oldStepID = stepID;
      stepID--;
      if(isFinished(oldStepID)){
        saveChanges(oldStepID);
      }
      changeStep();
    }
  });

  $('.button-right').on('click', function(e){
    if(stepID < maxSteps){
      oldStepID = stepID;
      stepID++;
      if(isFinished(oldStepID)){
        saveChanges(oldStepID);
      }
      changeStep();
    }
  });

  $('.main-cat').change(function(){
    var mainCatID = $(this).find(':selected').val();
    $('#specific-cat' + stepID).children().remove();
    $('#specific-cat' + stepID).append('<option value="0"></option>');
    specificCat.forEach(function(s){
      if(s['main-cat-id'] == mainCatID){
        console.log(s['name']);
        var sID = s['id'];
        var sName = s['name'];
        $('#specific-cat' + stepID).append('<option value="' + sID + '">' + sName + '</option>');
      }
    });
  });

  function isFinished(oldStepID) {
    if($('#name' + oldStepID).val() == "" || $('#main-cat' + oldStepID).val() == "0" || $('#specific-cat' + oldStepID).val() == "0" || $('#quantity' + oldStepID).val() == "" || $('#quantity-type' + oldStepID).val() == "" || $('#price' + oldStepID).val() == "") {
      $('#step' + oldStepID).removeClass('ready');
      return false;
    }
    return true;
  }

  function saveChanges(oldStepID) {
    var productName = $('#name' + oldStepID).val();
    $('#step' + oldStepID).text(productName);
    $('#step' + oldStepID).addClass('ready');
  }

  function changeStep() {
    for(var i = 1; i <= maxSteps; i++) {
      $('#product' + i).addClass('d-none');
      $('#step' + i).removeClass('active');
    }
    $('#product' + stepID).removeClass('d-none');
    $('#step' + stepID).addClass('active');
  }


// easyAutocomplete

  var options = {

    data: products,

    theme: "bootstrap",

    getValue: "name",

    cssClasses: "sheroes",

    template: {
      type: "iconRight",
      fields: {
        iconSrc: "icon"
      }
    },

    list: {
      match: {
        enabled: true
      },
      showAnimation: {
        type: "slide"
      },
      hideAnimation: {
        type: "slide"
      }
    }

  };

  for(var i = 1; i <= maxSteps; i++) {
      $("#name" + i).easyAutocomplete(options);
  }

});
