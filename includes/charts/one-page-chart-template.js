(function() {
  "use strict";

  jQuery(document).ready(function() {
    if('#template_overview' === window.location.hash) {
      show_template_overview()
    }
  })

  function show_template_overview(){

    let localizedObject = window.wpApiTemplate // change this object to the one named in ui-menu-and-enqueue.php
    let translations = localizedObject.translations

    let chartDiv = jQuery('#chart') // retrieves the chart div in the metrics page

    chartDiv.empty().html(`
      <span class="section-header">${localizedObject.translations.title}</span>

      <hr style="max-width:100%;">
      
      <div id="chartdiv"></div>
      
      <hr style="max-width:100%;">

      <button type="button" onclick="sample_api_call('Yeh successful response from API!')" class="button" id="sample_button">${translations["Sample API Call"]}</button>
    `)

    // Create chart instance
    var chart = am4core.create("chartdiv", am4charts.PieChart);

    // Add data
    chart.data = [{
      "country": "Lithuania",
      "litres": 501.9
    }, {
      "country": "Czech Republic",
      "litres": 301.9
    }, {
      "country": "Ireland",
      "litres": 201.1
    }, {
      "country": "Germany",
      "litres": 165.8
    }, {
      "country": "Australia",
      "litres": 139.9
    }, {
      "country": "Austria",
      "litres": 128.3
    }, {
      "country": "UK",
      "litres": 99
    }, {
      "country": "Belgium",
      "litres": 60
    }, {
      "country": "The Netherlands",
      "litres": 50
    }];

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "litres";
    pieSeries.dataFields.category = "country";
  }

  window.sample_api_call = function sample_api_call( button_data ) {


    let localizedObject = window.wpApiTemplate // change this object to the one named in ui-menu-and-enqueue.php

    let button = jQuery('#sample_button')

    button.append(localizedObject.spinner)

    let data = { "button_data": button_data };
    return jQuery.ajax({
      type: "POST",
      data: JSON.stringify(data),
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      url: localizedObject.root + 'dt/v1/example/'+localizedObject.name_key+'/sample',
      beforeSend: function(xhr) {
        xhr.setRequestHeader('X-WP-Nonce', localizedObject.nonce);
      },
    })
    .done(function (data) {
      button.empty().append(data)
      console.log( 'success' )
      console.log( data )
    })
    .fail(function (err) {
      console.log("error");
      console.log(err);
    })
  }
})();
