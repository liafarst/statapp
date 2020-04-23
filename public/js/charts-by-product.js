$( document ).ready(function() {
  var myChart = null;
  var ctx = document.getElementById('chart').getContext('2d');

  // var dataX = [];
  // var dataY = [];
  // var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  // var prices = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
  //
  // for(var i = 11; i >= 0; i--){
  //   // set X
  //   var d = new Date();
  //   d.setMonth(new Date().getMonth() - i);
  //
  //   // set Y
  //   orders.forEach(function(o){
  //     if(new Date(o.bill.created_at).getMonth() == d.getMonth() && new Date(o.bill.created_at).getYear() == d.getYear()){
  //       prices[i] += o.price;
  //     }
  //   });
  //
  //   dataX.push(months[d.getMonth()] + " " + (d.getYear() + 1900));
  //   dataY.push(prices[i]);
  // }


  // updateChart(ctx, dataX, dataY);

  // myChart.canvas.parentNode.style.height = '1600px';
  // myChart.canvas.parentNode.style.width = '80%';


  $('#period').on('change', function(){
    if($(this).val() == "specific"){
      $('#specific-period').show();
      var month = $('#specific-month').children('.active').attr('value');
      var year = $('#specific-year').children('.active').text();
      var product = $('#product-name').val();
      plotSpecific(ctx, product, month, year);
    } else {
      $('#specific-period').hide();
      var product = $('#product-name').val();
      switch($(this).val()){
        case "lw":
          plotLW(ctx, product);
          break;
        case "lm":
          plotLM(ctx, product);
          break;
        case "ly":
          plotLY(ctx, product);
          break;
        default:
          alert('empty');
          break;
      }
    }
  });

  $('#specific-year').children().on('click', function(){
    $('#specific-year').children('.active').removeClass('active');
    $(this).addClass('active');
    var product = $('#product-name').val();
    var month = $('#specific-month').children('.active').attr('value');
    var year = $(this).text();
    plotSpecific(ctx, product, month, year);
  });

  $('#specific-month').children().on('click', function(){
    $('#specific-month').children('.active').removeClass('active');
    $(this).addClass('active');
    var product = $('#product-name').val();
    var month = $(this).val();
    var year = $('#specific-year').children('.active').text();
    plotSpecific(ctx, product, month, year);
  });

// easyAutocomplete

  // config

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
  $('#product-name').easyAutocomplete(options);

  $('.product-name').change(function(){
    var product = $('#product-name').val();
    switch($('#period').val()){
      case "lw":
        plotLW(ctx, product);
        break;
      case "lm":
        plotLM(ctx, product);
        break;
      case "ly":
        plotLY(ctx, product);
        break;
      case "specific":
        var month = $('#specific-month').children('.active').attr('value');
        var year = $('#specific-year').children('.active').text();
        console.log(month + " " + year);
        plotSpecific(ctx, product, month, year);
        break;
      default:
        break;
    }
  });

  plotLW(ctx, "");

});

function plotLW(ctx, product){
  var dataX = [];
  var dataY = [];
  var prices = new Array(0, 0, 0, 0, 0, 0, 0);
  var d = new Date();
  $('#info').text("Last week");
  d.setDate(d.getDate() - 7);
  for(var i = 0; i < 7; i++){
    d.setDate(d.getDate() + 1);
    orders.forEach(function(o){
      if(new Date(o.bill.created_at).getDate() == d.getDate() && new Date(o.bill.created_at).getMonth() == d.getMonth() && new Date(o.bill.created_at).getYear() == d.getYear() && product == o.product.name){
        prices[i] += o.price;
      }
    });
    if(i == 6){
      dataX.push(dayToText(d.getDay()) + " (now)");
    } else {
      dataX.push(dayToText(d.getDay()));
    }
    dataY.push(prices[i]);
  }

  updateChart(ctx, dataX, dataY);
}

function plotLM(ctx, product){
  var dataX = [];
  var dataY = [];
  var d = new Date();
  $('#info').text("Last month");
  d.setMonth(d.getMonth() - 1);
  var days = daysInMonth(d.getMonth() + 1, d.getYear());
  var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  var prices = new Array(days);
  for(var i = 0; i < prices.length; i++){
    prices[i] = 0;
  }
  for(var i = 0; i < days; i++){
    d.setDate(d.getDate() + 1);
    orders.forEach(function(o){
      if(new Date(o.bill.created_at).getDate() == d.getDate() && new Date(o.bill.created_at).getMonth() == d.getMonth() && new Date(o.bill.created_at).getYear() == d.getYear() && product == o.product.name){
        prices[i] += o.price;
      }
    });
    dataX.push(d.getDate());
    dataY.push(prices[i]);
  }

  updateChart(ctx, dataX, dataY);
}

function plotLY(ctx, product){
  var dataX = [];
  var dataY = [];
  var prices = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
  var d = new Date();
  $('#info').text("Last year");
  d.setYear(d.getYear() - 1 + 1900);
  for(var i = 0; i < 12; i++){
    d.setMonth(d.getMonth() + 1);
    orders.forEach(function(o){
      if(new Date(o.bill.created_at).getMonth() == d.getMonth() && new Date(o.bill.created_at).getYear() == d.getYear() && product == o.product.name){
        prices[i] += o.price;
      }
    });
    dataX.push(monthToText(d.getMonth()) + " " + (d.getYear() + 1900));
    dataY.push(prices[i]);
  }

  updateChart(ctx, dataX, dataY);
}

function plotSpecific(ctx, product, month, year){
  var dataX = [];
  var dataY = [];
  if(month == 0){
    $('#info').text(year);
    var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    var prices = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    for(var i = 0; i < 12; i++){
      // set X
      var d = new Date(year, 0, 1);
      d.setMonth(d.getMonth() + i);

      // set Y
      orders.forEach(function(o){
        if(new Date(o.bill.created_at).getMonth() == d.getMonth() && new Date(o.bill.created_at).getYear() == d.getYear() && product == o.product.name){
          prices[i] += o.price;
        }
      });

      dataX.push(months[i] + " " + (d.getYear() + 1900));
      dataY.push(prices[i]);
    }
  } else {
    $('#info').text(monthToText(month) + " " + year);
    var prices = new Array(daysInMonth(month, year));
    // console.log(month);
    for(var i = 0; i < prices.length; i++){
      prices[i] = 0;
      var d = new Date(year, month - 1, i + 1);
      orders.forEach(function(o){
        if(new Date(o.bill.created_at).getDate() == d.getDate() && new Date(o.bill.created_at).getMonth() == d.getMonth() && new Date(o.bill.created_at).getYear() == d.getYear() && product == o.product.name){
          prices[i] += o.price;
        }
      });
      dataX.push(d.getDate());
      dataY.push(prices[i]);
    }
  }
  updateChart(ctx, dataX, dataY);
}

function updateChart(ctx, dataX, dataY){
  if(typeof myChart !== 'undefined'){
    myChart.destroy();
  }
  myChart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: dataX,
          datasets: [{
              label: 'spendings in \u20AC',
              data: dataY,
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)',
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
          // resposive: true,
          tooltips: {
              callbacks: {
                  label: function(tooltipItem, data) {
                      return 'Spendings: ' + fancyPrice(tooltipItem.yLabel) + ' \u20AC';
                  }
              }
          },
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true
                  }
              }]
          }
      }
    });
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
  return price + "";
}

function monthToText(month){
  switch(month){
    case 0:
      return "January";
    case 1:
      return "February";
    case 2:
      return "March";
    case 3:
      return "April";
    case 4:
      return "May";
    case 5:
      return "June";
    case 6:
      return "July";
    case 7:
      return "August";
    case 8:
      return "September";
    case 9:
      return "October";
    case 10:
      return "November";
    case 11:
      return "December";
    default:
      return "empty";
  }
}

function dayToText(day){
  switch(day){
    case 0:
      return "Sunday";
    case 1:
      return "Monday";
    case 2:
      return "Tuesday";
    case 3:
      return "Wednesday";
    case 4:
      return "Thursday";
    case 5:
      return "Friday";
    case 6:
      return "Saturday";
  }
}

function daysInMonth(month, year){
  if(month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12){
    return 31;
  } else if(month == 4 || month == 6 || month == 9 || month == 11) {
    return 30;
  } else if(month == 2) {
    if(year % 4 == 0){
      return 29;
    } else {
      return 28;
    }
  } else {
    return 0;
  }
}
