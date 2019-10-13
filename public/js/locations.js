$( document ).ready(function() {

  var idx = 1;

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // event listeners

  $('.delete-location').on('click', function(){
    var locationID = $(this).attr('id').split('-')[1];
    $.ajax({
      type: 'POST',
      url: '/delete-location',
      data: {locationID: locationID},
      success: function (data) {
        window.location.href = "/locations?status=successfulDelete";
      },
      error: function () {
          window.location.href = "/locations?status=failedDelete";
      }
    });
  });

  $('#save-location').on('click', function(){
    var locationIDs = [];
    var namesOld = [];
    var addressesOld = [];
    var openingHoursOld = [];
    var namesNew = [];
    var addressesNew = [];
    var openingHoursNew = [];
    $('#locations').children().each(function(){
      if($(this).hasClass('old-locations')){
        locationIDs.push($(this).attr('id').split('-').pop().trim());
        namesOld.push($(this).find('.location-name').val());
        addressesOld.push($(this).find('.location-address').val());
        var openingHoursLineOld = "";
        $(this).find('.times').children('li').each(function(){
          if($(this).find('.closed').is(':checked')){
            openingHoursLineOld += "closed,";
          } else {
            openingHoursLineOld += $(this).find('.time:nth-child(1)').val() + "-" + $(this).find('.time:nth-child(2)').val() + ",";
          }
        });
        openingHoursLineOld = openingHoursLineOld.substr(0, openingHoursLineOld.length - 1);
        openingHoursOld.push(openingHoursLineOld);
      } else {
        namesNew.push($(this).find('.locationExtra-name').val());
        addressesNew.push($(this).find('.locationExtra-address').val());
        var openingHoursLineNew = "";
        $(this).find('.timesExtra').children('li').each(function(){
          if($(this).find('.closedExtra').is(':checked')){
            openingHoursLineNew += "closed,";
          } else {
            openingHoursLineNew += $(this).find('.timeExtra:nth-child(1)').val() + "-" + $(this).find('.timeExtra:nth-child(2)').val() + ",";
          }
        });
        openingHoursLineNew = openingHoursLineNew.substr(0, openingHoursLineNew.length - 1);
        openingHoursNew.push(openingHoursLineNew);
      }
    });
    console.log(locationIDs);
    console.log(namesOld);
    console.log(addressesOld);
    console.log(openingHoursOld);
    console.log(namesNew);
    console.log(addressesNew);
    console.log(openingHoursNew);

    $.ajax({
      type: 'POST',
      url: '/update-locations',
      data: {locationIDs: locationIDs, namesOld: namesOld, addressesOld: addressesOld, openingHoursOld: openingHoursOld, namesNew: namesNew, addressesNew: addressesNew, openingHoursNew: openingHoursNew},
      success: function (data) {
        window.location.href = "/locations?status=successfulEdit";
      },
      error: function () {
        window.location.href = "/locations?status=failedEdit";
      }
    });

  });

  $('.closed').on('click', function(){
    $(this).parent().children('.time').val('');
  });

  $('.time').on('change', function(){
    if($(this).val() == ""){
      $(this).parent().children('.closed').prop("checked", true);
    } else {
      $(this).parent().children('.closed').prop("checked", false);
    }
  });

  $('#add-location').on('click', function(){
    $('#locations').append('<li id="locationExtra-' + idx + '" class="extra-locations"><div class="row mb-4"><div class="col-3 text-center"><input type="text" id="locationName-' + idx + '" class="locationExtra-name form-control text-center location-elements" value="" style="height:50px;"></div><div class="col-3 text-center"><input type="text" id="locationAddress-' + idx +'" class="locationExtra-address form-control text-center location-elements" value="" style="height:50px;"></div><div class="col-4 text-center"><ul class="timesExtra" style="list-style-position: inside;"><li style="list-style-type: circle;">Mo <input type="time" class="timeExtra text-center" value=""> - <input type="time" class="timeExtra text-center" value=""><input id="Monday" checked class="closedExtra mx-2" type="radio"><label for="Monday">Closed</label></li><li style="list-style-type: circle;">Tu <input type="time" class="timeExtra text-center" value=""> - <input type="time" class="timeExtra text-center" value=""><input id="Tuesday" checked class="closedExtra mx-2" type="radio"><label for="Tuesday">Closed</label></li><li style="list-style-type: circle;">We <input type="time" class="timeExtra text-center" value=""> - <input type="time" class="timeExtra text-center" value=""><input id="Wednesday" checked class="closedExtra mx-2" type="radio"><label for="Wednesday">Closed</label></li><li style="list-style-type: circle;">Th <input type="time" class="timeExtra text-center" value=""> - <input type="time" class="timeExtra text-center" value=""><input id="Thursday" checked class="closedExtra mx-2" type="radio"><label for="Thursday">Closed</label></li><li style="list-style-type: circle;">Fr <input type="time" class="timeExtra text-center" value=""> - <input type="time" class="timeExtra text-center" value=""><input id="Friday" checked class="closedExtra mx-2" type="radio"><label for="Friday">Closed</label></li><li style="list-style-type: circle;">Sa <input type="time" class="timeExtra text-center" value=""> - <input type="time" class="timeExtra text-center" value=""><input id="Saturday" checked class="closedExtra mx-2" type="radio"><label for="Saturday">Closed</label></li><li style="list-style-type: circle;">Su <input type="time" class="timeExtra text-center" value=""> - <input type="time" class="timeExtra text-center" value=""><input id="Sunday" checked class="closedExtra mx-2" type="radio"><label for="Sunday">Closed</label></li></ul></div><div class="col-2 text-center"><div class="card-edit non-selectable text-center location-elements"><a id="locationDeleteExtra-'+ idx +'" class="delete-locationExtra">Delete</a></div></div></div></li>');
    idx++;
  });

  // end event listeners
});
$(document).on('click', '.delete-locationExtra' , function(){
  var locationID = $(this).attr('id').split('-')[1];
  $('#locationExtra-' + locationID).remove();
});

$(document).on('click', '.closedExtra' , function(){
  $(this).parent().children('.timeExtra').val('');
});

$(document).on('change', '.timeExtra' , function(){
  if($(this).val() == ""){
    $(this).parent().children('.closedExtra').prop("checked", true);
  } else {
    $(this).parent().children('.closedExtra').prop("checked", false);
  }
});
