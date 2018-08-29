jQuery(document).ready(function() {
    if('#advanced_overview' === window.location.hash) {
        show_advanced_overview()
    }

})

function show_advanced_overview(){
    "use strict";

    let localizedObject = wpApiAdvanced // change this object to the one named in ui-menu-and-enqueue.php

    let chartDiv = jQuery('#chart') // retrieves the chart div in the metrics page

    chartDiv.empty().html(`
        <span class="section-header">`+ localizedObject.translations.title +`</span>
        
        <hr style="max-width:100%;">
        
        <button type="button" onclick="sample_api_call('Yeh successful response from API!')" class="button" id="sample_button">Sample API Call</button>
        `)
}

function sample_api_call( button_data ) {


    let localizedObject = wpApiAdvanced // change this object to the one named in ui-menu-and-enqueue.php
    let button = jQuery('#sample_button')

    button.append(localizedObject.spinner)

    let data = { "button_data": button_data };
    return jQuery.ajax({
        type: "POST",
        data: JSON.stringify(data),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        url: localizedObject.root + 'dt/v1/advanced/sample',
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

