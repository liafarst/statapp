$( document ).ready(function() {

  var idx = 1;

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // event listeners
  $('.location-link').on('click', function(){
    for(var location of locations){
      if(location['id'] == $(this).attr('id')){
        $('#location-name').html(location['name']);
        $('#location-address').html(location['address']);
        var openingHours = location['opening_hours'].split(",");
        $('#mo').html(openingHours[0]);
        $('#tu').html(openingHours[1]);
        $('#we').html(openingHours[2]);
        $('#th').html(openingHours[3]);
        $('#fr').html(openingHours[4]);
        $('#sa').html(openingHours[5]);
        $('#su').html(openingHours[6]);
        // $('#location-opening-hours').html(location['opening_hours']);
      }
    }
    $('#location-name').val(location['name']);
    $('#location-info').modal('show');
  });

  $('.delete-bill').on('click', function(){
    var billID = $(this).attr('id').split('-')[1];
    $.ajax({
      type: 'POST',
      url: '/delete-bill',
      data: {billID: billID},
      success: function (data) {
        window.location.href = "/bill-history?status=successfulDelete";
      },
      error: function () {
        window.location.href = "/bill-history?status=failedDelete";
      }
    });
  });

  $('.product-name-block').change(function(){
    checkOldProducts($(this).find('input').val(), $(this).find('input').attr('id').split('-').pop().trim());
  });

  $('.product-name-block').keyup(function(){
    checkOldProducts($(this).find('input').val(), $(this).find('input').attr('id').split('-').pop().trim());
  });

  $('#add-extra').on('click', function(){
    $('.card ul').append('<li id="extra' + idx + '"><div class="row"><div class="col-2 text-center"><span class="bullet-img"><img id="catImage-extra' + idx + '" src="/public/images/Default.png" width="40" height="40"></span></div><div class="col-3 text-center product-name-block"><input id="product-name-extra-' + idx + '" autocomplete="off" name="hidden" type="text" class="form-control product-name-ea product-name-extra" /></div><div class="col-3 text-center">( <input id="quantity-extra' + idx + '" type="number" class="form-control quantity-extra text-right" style="width:60px;display:inline;"><select id="quantity-type-extra' + idx + '" class="form-control quantity-type-extra" style="width:100px;display:inline"><option value="0"></option><option value="piece">piece(s)</option><option value="meter">meter(s)</option><option value="kg">kg</option><option value="liter">liter(s)</option></select> )</div><div class="col-2 text-center">( <input id="price-extra' + idx + '" type="number" class="form-control price-box text-right price-extra" style="width:80px;display:inline;"> &euro; )</div><div class="col-2 text-center"><div class="card-edit text-center non-selectable"><a id="orderHide-' + idx + '" class="hide-order">Delete</a></div></div></div></li>');
    $(".product-name-ea").easyAutocomplete(options);
    idx++;
  });

  $('.delete-order').on('click', function(){
    var orderID = $(this).attr('id').split('-').pop().trim();
    $.ajax({
      type: 'POST',
      url: '/delete-order',
      data: {orderID: orderID},
      success: function (data) {
        $('#order-' + orderID).remove();
        var sum = 0;
        $('.price-box').each(function(){
          var num = parseFloat($(this).val());
          if(isNaN(num)){
            num = 0;
          }
          sum += num;
        });
        sum = Math.round(sum * 100) / 100;
        $('#total').text(fancyPrice(sum));
      },
      error: function () {
        alert('Could not delete order. Please try again later.');
      }
    });
  });

  $('#save-orders').on('click', function (callback){
    var orderIDsOld = [];
    var productsOld = [];
    var quantitiesOld = [];
    var quantityTypesOld = [];
    var pricesOld = [];
    var productsNew = [];
    var quantitiesNew = [];
    var quantityTypesNew = [];
    var pricesNew = [];
    var date = $('#date').val();
    var locationID = $('#location').val();
    var confirmProducts = [];

    var allowed = true;
    $('.old-orders').each(function(){
      orderIDsOld.push($(this).attr('id').split('-').pop().trim());
    });
    $('.product-name').each(function(){
      if($(this).val() == ""){
        allowed = false;
      }
      productsOld.push($(this).val());
    });
    $('.quantity').each(function(){
      if($(this).val() == ""){
        allowed = false;
      }
      quantitiesOld.push($(this).val());
    });
    $('.quantity-type').each(function(){
      if($(this).val() == "0"){
        allowed = false;
      }
      quantityTypesOld.push($(this).val());
    });
    $('.price').each(function(){
      if($(this).val() == ""){
        allowed = false;
      }
      pricesOld.push($(this).val());
    });
    $('.product-name-extra').each(function(){
      if($(this).val() == ""){
        allowed = false;
      }
      productsNew.push($(this).val());
    });
    $('.quantity-extra').each(function(){
      if($(this).val() == ""){
        allowed = false;
      }
      quantitiesNew.push($(this).val());
    });
    $('.quantity-type-extra').each(function(){
      if($(this).val() == "0"){
        allowed = false;
      }
      quantityTypesNew.push($(this).val());
    });
    $('.price-extra').each(function(){
      if($(this).val() == ""){
        allowed = false;
      }
      pricesNew.push($(this).val());
    });

    productsOld.concat(productsNew).forEach(function(p1){
      if(!exists(p1)){
        confirmProducts.push(p1);
      }
    });

    if(!allowed){
      $('#fill-alert').slideDown();
      $('#fill-alert').delay(3000).fadeOut();
      return;
    } else {
      if(confirmProducts.length == 0){
        $.ajax({
          type: 'POST',
          url: '/update-bill',
          data: {date: date, locationID: locationID, orderIDsOld: orderIDsOld, productsOld: productsOld, quantitiesOld: quantitiesOld, quantityTypesOld: quantityTypesOld, pricesOld: pricesOld, productsNew: productsNew, quantitiesNew: quantitiesNew, quantityTypesNew: quantityTypesNew, pricesNew: pricesNew},
          success: function (data) {
            window.location.href = "/bill-history?status=successfulEdit";
          },
          error: function () {
            window.location.href = "/bill-history?status=failedEdit";
          }
        });
      } else {
        var productList = "";
        confirmProducts.forEach(function(p){
          productList += "<li>" + p + "</li>";
        });
        $('#products-to-confirm').html(productList);
        $('#confirm-new-products').modal('show');
        $('#confirm-changes').on('click', function(){
          $('#confirm-new-products').modal('hide');
          $.ajax({
            type: 'POST',
            url: '/update-bill',
            data: {date: date, location: location, orderIDsOld: orderIDsOld, productsOld: productsOld, quantitiesOld: quantitiesOld, quantityTypesOld: quantityTypesOld, pricesOld: pricesOld, productsNew: productsNew, quantitiesNew: quantitiesNew, quantityTypesNew: quantityTypesNew, pricesNew: pricesNew},
            success: function (data) {
              window.location.href = "/bill-history?status=successfulEdit";
            },
            error: function () {
              window.location.href = "/bill-history?status=failedEdit";
            }
          });
        });
      }
    }

    // console.log(confirmProducts);
    // console.log(orderIDsOld);
    // console.log(productsOld);
    // console.log(quantitiesOld);
    // console.log(quantityTypesOld);
    // console.log(pricesOld);
    // console.log(productsNew);
    // console.log(quantitiesNew);
    // console.log(quantityTypesNew);
    // console.log(pricesNew);

  });

  $('#confirm-new-products').on('hidden.bs.modal', function(){
    $('#confirm-changes').off();
  });

  // event listeners end

  // helpers
  function checkOldProducts(productName, billID){
    var found = false;
    for(var p of products){
      if(productName == p['name']){
        if(p['main_cat_id'] == 0){
          $('#catImage' + billID).attr('src','/public/images/Default.png');
        } else if (p['specific_cat_id'] == 0){
          var mainCatName = "";
          mainCat.forEach(function(m){
            if(m['id'] == p['main_cat_id']){
              mainCatName = m['name'];
            }
          });
          $('#catImage' + billID).attr('src','/public/images/' + mainCatName + '.png');
        } else {
          var specificCatName = "";
          specificCat.forEach(function(s){
            if(s['id'] == p['specific_cat_id']){
              specificCatName = s['name'];
            }
          });
          $('#catImage' + billID).attr('src','/public/images/' + specificCatName + p['main_cat_id'] + '.png');
        }
        found = true;
        break;
      }
    }
    if(!found){
      $('#catImage' + billID).attr('src','/public/images/Default.png');
    }
  }
  // end Helpers

  // easyAutocomplete

  //config

  var mainCat1 = [];
  var mainCat2 = [];
  var mainCat3 = [];
  var mainCat4 = [];
  var mainCat5 = [];
  var mainCat6 = [];

  products.forEach(function(p){
    switch(p['main_cat_id']){
      case 1:
        mainCat1.push(p);
        break;
      case 2:
        mainCat2.push(p);
        break;
      case 3:
        mainCat3.push(p);
        break;
      case 4:
        mainCat4.push(p);
        break;
      case 5:
        mainCat5.push(p);
        break;
      default:
        mainCat6.push(p);
        break;
    }
  });

  var newProducts = {
    "1" : mainCat1,
    "2" : mainCat2,
    "3" : mainCat3,
    "4" : mainCat4,
    "5" : mainCat5,
    "6" : mainCat6
  };

  var options = {
    data: newProducts,
    theme: "bootstrap",
    getValue: "name",
    cssClasses: "sheroes",
    template: {
      type: "iconRight",
      fields: {
        iconSrc: "icon"
      }
    },
    categories: [
      {listLocation: "1", header: "-- Food & drink --"},
      {listLocation: "2", header: "-- Household goods --"},
      {listLocation: "3", header: "-- Electronic devices --"},
      {listLocation: "4", header: "-- Clothing --"},
      {listLocation: "5", header: "-- Other --"},
      {listLocation: "6", header: "-- No category --"}
    ],
    list: {
      match: {
        enabled: true
      },
      showAnimation: {
        type: "slide",
        time: 50,
      },
      hideAnimation: {
        type: "slide",
        time: 50,
      }
    }
  };
  // execute
  $(".product-name-ea").easyAutocomplete(options);


});

$(document).on('click', '.hide-order' , function() {
  var orderID = $(this).attr('id').split('-')[1];
  $('#extra' + orderID).remove();
});

$(document).on('keyup', '.price-box' , function() {
  var sum = 0;
  $('.price-box').each(function(){
    var num = parseFloat($(this).val());
    if(isNaN(num)){
      num = 0;
    }
    sum += num;
  });
  sum = Math.round(sum * 100) / 100;
  $('#total').text(fancyPrice(sum));
});

$(document).on('change', '.product-name-block' , function() {
  checkNewProducts($(this).find('input').val(), $(this).find('input').attr('id').split('-').pop().trim());
});

$(document).on('keyup', '.product-name-block' , function() {
  checkNewProducts($(this).find('input').val(), $(this).find('input').attr('id').split('-').pop().trim());
});

// helpers
function checkNewProducts(productName, billID){
  var found = false;
  for(var p of products){
    if(productName == p['name']){
      if(p['main_cat_id'] == 0){
        $('#catImage-extra' + billID).attr('src','/public/images/Default.png');
      } else if (p['specific_cat_id'] == 0){
        var mainCatName = "";
        mainCat.forEach(function(m){
          if(m['id'] == p['main_cat_id']){
            mainCatName = m['name'];
          }
        });
        $('#catImage-extra' + billID).attr('src','/public/images/' + mainCatName + '.png');
      } else {
        var specificCatName = "";
        specificCat.forEach(function(s){
          if(s['id'] == p['specific_cat_id']){
            specificCatName = s['name'];
          }
        });
        $('#catImage-extra' + billID).attr('src','/public/images/' + specificCatName + p['main_cat_id'] + '.png');
      }
      found = true;
      break;
    }
  }
  if(!found){
    $('#catImage-extra' + billID).attr('src','/public/images/Default.png');
  }
}

function fancyPrice(price) {
  if(!price.toString().split(".")[1]){
    return price + ".00";
  }
  switch(price.toString().split(".")[1].length){
    case 0:
      price += ".00";
      break;
    case 1:
      price += "0";
      break;
    default:
      break;
  }
  return price;
}

function exists(product){
  var found = false;
  products.forEach(function(p){
    if(product == p['name']){
      found = true;
    }
  });
  return found;
}
// end Helpers
