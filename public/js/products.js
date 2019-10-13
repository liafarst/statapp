$( document ).ready(function() {

  var idx = 1;

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // event listeners
  $('.favourite-product').on('click', function(){
    if($(this).hasClass('far')){
      $(this).removeClass('far');
      $(this).addClass('fas');
    } else {
      $(this).removeClass('fas');
      $(this).addClass('far');
    }
  });

  $('.delete-product').on('click', function(){
    var productID = $(this).attr('id').split('-')[1];
    var product = $('#productName-' + productID).val();
    $('#products-to-delete').html("<li>" + product + "</li>");
    $('#confirm-delete-modal').modal('show');
    $('#confirm-delete').on('click', function(){
      $('#confirm-delete-modal').modal('hide');
      $.ajax({
        type: 'POST',
        url: '/delete-product',
        data: {productID: productID},
        success: function (data) {
          var productName = "";
          for(var p of products){
            if(p['id'] == productID){
              productName = p['name'];
            }
          }
          $('#product-' + productID).remove();
          $('#deleted-alert').text(productName + ' has been deleted successfully.');
          $('#deleted-alert').slideDown();
          $('#deleted-alert').delay(3000).fadeOut();
        },
        error: function () {
          alert('Could not delete product. Please try again later.');
        }
      });
    });
  });

  $('#save-products').on('click', function(){
    var productIDs = [];
    var productNamesOld = [];
    var productFavoritesOld = [];
    var productMainCatsOld = [];
    var productSpecificCatsOld = [];
    var productNamesNew = [];
    var productFavoritesNew = [];
    var productMainCatsNew = [];
    var productSpecificCatsNew = [];
    var allowed = true;
    $('.old-products').each(function(){
      productIDs.push($(this).attr('id').split('-')[1]);
    });
    $('.product-name').each(function(){
      if($(this).val() == ""){
        allowed = false;
      }
      productNamesOld.push($(this).val());
    });
    $('.main-cat').each(function(){
      if($(this).val() == "0"){
        allowed = false;
      }
      productMainCatsOld.push($(this).val());
    });
    $('.specific-cat').each(function(){
      if($(this).val() == "0"){
        allowed = false;
      }
      productSpecificCatsOld.push($(this).val());
    });
    $('.favourite-product').each(function(){
      if($(this).hasClass('fas')){
        productFavoritesOld.push(true);
      } else {
        productFavoritesOld.push(false);
      }
    });
    $('.product-name-extra').each(function(){
      if($(this).val() == ""){
        allowed = false;
      }
      productNamesNew.push($(this).val());
    });
    $('.main-cat-extra').each(function(){
      if($(this).val() == "0"){
        allowed = false;
      }
      productMainCatsNew.push($(this).val());
    });
    $('.specific-cat-extra').each(function(){
      if($(this).val() == "0"){
        allowed = false;
      }
      productSpecificCatsNew.push($(this).val());
    });
    $('.favourite-product-extra').each(function(){
      if($(this).hasClass('fas')){
        productFavoritesNew.push(true);
      } else {
        productFavoritesNew.push(false);
      }
    });

    // console.log(productIDs);
    // console.log(productNamesOld);
    // console.log(productMainCatsOld);
    // console.log(productSpecificCatsOld);
    // console.log(productFavoritesOld);
    // console.log(productNamesNew);
    // console.log(productMainCatsNew);
    // console.log(productSpecificCatsNew);
    // console.log(productFavoritesNew);
    // console.log(allowed);

    if(allowed){
      if(hasDuplicates(productNamesOld.concat(productNamesNew))){
        $('#fill-alert').text('Products must have unique names.');
        $("html, body").animate({ scrollTop: 0 }, 500);
        $('#fill-alert').slideDown();
        $('#fill-alert').delay(3000).fadeOut();
      } else {
        $.ajax({
          type: 'POST',
          url: '/update-products',
          data: {productIDs: productIDs, productNamesOld: productNamesOld, productFavoritesOld: productFavoritesOld, productMainCatsOld: productMainCatsOld, productSpecificCatsOld: productSpecificCatsOld, productNamesNew: productNamesNew, productMainCatsNew: productMainCatsNew, productSpecificCatsNew: productSpecificCatsNew, productFavoritesNew: productFavoritesNew},
          success: function (data) {
            window.location.href = "/my-products?status=successfulEdit";
          },
          error: function () {
            window.location.href = "/my-products?status=failedEdit";
          }
        });
      }
    } else {
      $('#fill-alert').text('Please fill all fields.');
      $("html, body").animate({ scrollTop: 0 }, 500);
      $('#fill-alert').slideDown();
      $('#fill-alert').delay(3000).fadeOut();
      return;
    }
  });

  $('.main-cat').change(function(){
    var productID = $(this).attr('id').split('-')[1];
    var mainCatID = $(this).find(':selected').val();
    var specificCatID = $('#specificCat-' + productID).find(':selected').val();
    $('#specificCat-' + productID).children().remove();
    $('#specificCat-' + productID).append('<option value="0"></option>');
    if(mainCatID == "0"){
      $('#catImage' + productID).attr('src','../public/images/Default.png');
    } else {
      var mainCatName;
      mainCat.forEach(function(m){
        if(m['id'] == mainCatID){
          mainCatName = m['name'];
        }
      });
      $('#catImage' + productID).attr('src','../public/images/' + mainCatName + '.png');
    }
    specificCat.forEach(function(s){
      if(s['main_cat_id'] == mainCatID){
        var sID = s['id'];
        var sName = s['name'];
        $('#specificCat-' + productID).append('<option value="' + sID + '">' + sName + '</option>');
      }
    });
  });

  $('.specific-cat').change(function(){
    var productID = $(this).attr('id').split('-')[1];
    var mainCatID = $('#mainCat-' + productID).find(':selected').val();
    var specificCatID = $(this).find(':selected').val();
    if(mainCatID == "0" && specificCatID == "0"){
      $('#catImage' + productID).attr('src','../public/images/Default.png');
    } else if(specificCatID == "0"){
      var mainCatName;
      mainCat.forEach(function(m){
        if(m['id'] == mainCatID){
          mainCatName = m['name'];
        }
      });
      $('#catImage' + productID).attr('src','../public/images/' + mainCatName + '.png');
    } else {
      var specificCatName;
      var mainCatID;
      specificCat.forEach(function(s){
        if(s['id'] == specificCatID){
          specificCatName = s['name'];
          mainCatID = s['main_cat_id'];
        }
      });
      $('#catImage' + productID).attr('src','../public/images/' + specificCatName + mainCatID + '.png');
    }
  });

  $('#add-product').on('click', function(){
    $('.card ul').append('<li id="productExtra-' + idx + '" class="old-orders"><div class="row"><div class="col-1 text-center"><img id="catImage-extra' + idx + '" src="/public/images/Default.png" width="40" height="40"></div><div class="col-1 text-center"><i id="favouriteExtra-{{ $product->id }}" class="far fa-star favourite-product-extra" style="cursor:pointer"></i></div><div class="col-2 text-center"><input type="text" id="productNameExtra-' + idx + '" class="product-name-extra form-control" contenteditable="true">Product name</span></div><div class="col-3 text-center"><span class="custom-dropdown"><select id="mainCatExtra-' + idx + '" class="main-cat-extra products-select"></select></span></div><div class="col-3 text-center"><span class="custom-dropdown"><select id="specificCatExtra-' + idx + '" class="specific-cat-extra products-select"></select></span></div><div class="col-2 text-center"><div class="card-edit non-selectable text-center"><a id="productDeleteExtra-' + idx + '" class="delete-product-extra">Delete</a></div></div></div></li>');
    $('#mainCatExtra-' + idx).append('<option value="0"></option>');
    for(var m of mainCat){
      $('#mainCatExtra-' + idx).append('<option value="' + m['id'] + '">' + m['name'] + '</option>');
    }
    $('#specificCatExtra-' + idx).append('<option value="0"></option>');
    idx++;
  });
  // end event listeners

  // helpers

  function hasDuplicates(array){
    for(var i = 0; i < array.length; i++){
      for(var j = 0; j < array.length; j++){
        if(array[i] == array[j] && i != j){
          return true;
        }
      }
    }
    return false;
  }

  // end helpers

});

$(document).on('click', '.delete-product-extra' , function() {
  var productID = $(this).attr('id').split('-')[1];
  $('#productExtra-' + productID).remove();
});

$(document).on('change', '.main-cat-extra', function(){
  var productID = $(this).attr('id').split('-')[1];
  var mainCatID = $(this).find(':selected').val();
  var specificCatID = $('#specificCatExtra-' + productID).find(':selected').val();
  $('#specificCatExtra-' + productID).children().remove();
  $('#specificCatExtra-' + productID).append('<option value="0"></option>');
  if(mainCatID == "0"){
    $('#catImage-extra' + productID).attr('src','../public/images/Default.png');
  } else {
    var mainCatName;
    mainCat.forEach(function(m){
      if(m['id'] == mainCatID){
        mainCatName = m['name'];
      }
    });
    $('#catImage-extra' + productID).attr('src','../public/images/' + mainCatName + '.png');
  }
  specificCat.forEach(function(s){
    if(s['main_cat_id'] == mainCatID){
      var sID = s['id'];
      var sName = s['name'];
      $('#specificCatExtra-' + productID).append('<option value="' + sID + '">' + sName + '</option>');
    }
  });
});

$(document).on('change', '.specific-cat-extra', function(){
  var productID = $(this).attr('id').split('-')[1];
  var mainCatID = $('#mainCat-' + productID).find(':selected').val();
  var specificCatID = $(this).find(':selected').val();
  if(mainCatID == "0" && specificCatID == "0"){
    $('#catImage-extra' + productID).attr('src','../public/images/Default.png');
  } else if(specificCatID == "0"){
    var mainCatName;
    mainCat.forEach(function(m){
      if(m['id'] == mainCatID){
        mainCatName = m['name'];
      }
    });
    $('#catImage-extra' + productID).attr('src','../public/images/' + mainCatName + '.png');
  } else {
    var specificCatName;
    var mainCatID;
    specificCat.forEach(function(s){
      if(s['id'] == specificCatID){
        specificCatName = s['name'];
        mainCatID = s['main_cat_id'];
      }
    });
    $('#catImage-extra' + productID).attr('src','../public/images/' + specificCatName + mainCatID + '.png');
  }
});

$(document).on('click', '.favourite-product-extra', function(){
  if($(this).hasClass('far')){
    $(this).removeClass('far');
    $(this).addClass('fas');
  } else {
    $(this).removeClass('fas');
    $(this).addClass('far');
  }
});
