$( document ).ready(function() {

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

// Progress
  var maxSteps = 20;
  var stepID = 1;

  // event listeners
  $('.step-indicator').children().on('click', function(e){
    oldStepID = stepID;
    stepID = ($(this).attr('id').substring($(this).attr('id').indexOf("p") + 1));
    checkStep(oldStepID);
    changeStep();
  });

  $('#button-left').on('click', function(e){
    if(stepID > 1){
      oldStepID = stepID;
      stepID--;
      checkStep(oldStepID);
      changeStep();
    }
  });

  $('#button-right').on('click', function(e){
    if(stepID < maxSteps){
      oldStepID = stepID;
      stepID++;
      checkStep(oldStepID);
      changeStep();
    }
  });

  $('.main-cat').change(function(){
    var mainCatID = $(this).find(':selected').val();
    var specificCatID = $('#specific-cat' + stepID).find(':selected').val();
    $('#specific-cat' + stepID).children().remove();
    $('#specific-cat' + stepID).append('<option value="0"></option>');
    if(mainCatID == "0"){
      $('#catImage' + stepID).attr('src','../public/images/Default.png');
    } else {
      var mainCatName;
      mainCat.forEach(function(m){
        if(m['id'] == mainCatID){
          mainCatName = m['name'];
        }
      });
      $('#catImage' + stepID).attr('src','../public/images/' + mainCatName + '.png');
    }
    specificCat.forEach(function(s){
      if(s['main_cat_id'] == mainCatID){
        var sID = s['id'];
        var sName = s['name'];
        $('#specific-cat' + stepID).append('<option value="' + sID + '">' + sName + '</option>');
      }
    });
  });

  $('.specific-cat').change(function(){
    var mainCatID = $('#main-cat' + stepID).find(':selected').val();
    var specificCatID = $(this).find(':selected').val();
    if(mainCatID == "0" && specificCatID == "0"){
      $('#catImage' + stepID).attr('src','../public/images/Default.png');
    } else if(specificCatID == "0"){
      var mainCatName;
      mainCat.forEach(function(m){
        if(m['id'] == mainCatID){
          mainCatName = m['name'];
        }
      });
      $('#catImage' + stepID).attr('src','../public/images/' + mainCatName + '.png');
    } else {
      var specificCatName;
      var mainCatID;
      specificCat.forEach(function(s){
        if(s['id'] == specificCatID){
          specificCatName = s['name'];
          mainCatID = s['main_cat_id'];
        }
      });
      $('#catImage' + stepID).attr('src','../public/images/' + specificCatName + mainCatID + '.png');
    }
  });

  $('.product-name-block').change(function(){
    checkProducts($(this).find('input').val());
  });

  $('.product-name-block').keyup(function(){
    checkProducts($(this).find('input').val());
  });

  $('#submit').on('click', function(){
    var orders = [];
    for(var i = 1; i <= maxSteps; i++){
      var productName = $('#product-name' + i).val();
      var mainCatID = $('#main-cat' + i).val();
      var specificCatID = $('#specific-cat' + i).val();
      var quantity = $('#quantity' + i).val();
      var quantityType = $('#quantity-type' + i).val();
      var price = $('#price' + i).val();
      if(productName == "" || mainCatID == "0" || specificCatID == "0" || quantity == "" || quantityType == "0" || price == ""){
        continue;
      }
      orders.push({'product_name' : productName, 'main_cat_id' : mainCatID, 'specific_cat_id' : specificCatID, 'quantity' : quantity, 'quantity_type' : quantityType, 'price' : price});
    }
    if(orders.length == 0){
      alert('You haven\'t given any data.');
    } else {
      $.ajax({
        type: 'POST',
        url: '/create-bill',
        data: {orders: orders},
        success: function (data) {
          window.location.href = "/";
        },
        error: function () {
          alert("Error!");
        }
      });
    }
  });

  // end event listeners

  // helpers

  function checkStep(oldStepID){
    if($('#product-name' + oldStepID).val() == "" || $('#main-cat' + oldStepID).val() == "0" || $('#specific-cat' + oldStepID).val() == "0" || $('#quantity' + oldStepID).val() == "" || $('#quantity-type' + oldStepID).val() == "0" || $('#price' + oldStepID).val() == "") {
      if($('#product-name' + oldStepID).val() != "" || $('#main-cat' + oldStepID).val() != "0" || $('#specific-cat' + oldStepID).val() != "0" || $('#quantity' + oldStepID).val() != "" || $('#quantity-type' + oldStepID).val() != "0" || $('#price' + oldStepID).val() != "") {
        $('#step' + oldStepID).removeClass('ready');
        makeUnfinished(oldStepID);
      } else {
        resetStep(oldStepID);
      }
    } else {
      makeReady(oldStepID);
    }
  }

  function resetStep(oldStepID){
    $('#step' + oldStepID).text('Product name');
    $('#step' + oldStepID).removeClass('ready');
    $('#step' + oldStepID).removeClass('unfinished');
    $('#styleStep' + oldStepID).text('');
  }

  function makeUnfinished(oldStepID){
    $('#step' + oldStepID).text('Product name');
    $('#step' + oldStepID).removeClass('ready');
    $('#step' + oldStepID).addClass('unfinished');
    $('#styleStep' + oldStepID).text('');
  }

  function makeReady(oldStepID){
    var productName = $('#name' + oldStepID).val();
    $('#step' + oldStepID).text(productName);
    $('#step' + oldStepID).removeClass('unfinished');
    $('#step' + oldStepID).addClass('ready');
    var imageName = $('#catImage' + oldStepID).attr('src').substring($('#catImage' + oldStepID).attr('src').lastIndexOf('/') + 1);
    $('#styleStep' + oldStepID).text('#step' + oldStepID + ':before{background-image: url("../public/images/' + imageName + '") !important;}');
  }

  function changeStep(){
    for(var i = 1; i <= maxSteps; i++) {
      $('#product' + i).addClass('d-none');
      $('#step' + i).removeClass('active');
    }
    $('#product' + stepID).removeClass('d-none');
    $('#step' + stepID).addClass('active');
  }

  function checkProducts(productName){
    products.forEach(function(p){
      if(productName == p['name']){
        $('#main-cat' + stepID).val(p['main_cat_id']);
        $('#main-cat' + stepID).trigger('change');
        $('#specific-cat' + stepID).val(p['specific_cat_id']);
        $('#specific-cat' + stepID).trigger('change');
      }
    });
  }

  // end helpers


// easyAutocomplete

  // config

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

  // execute
  for(var i = 1; i <= maxSteps; i++) {
      $("#product-name" + i).easyAutocomplete(options);
  }

});
