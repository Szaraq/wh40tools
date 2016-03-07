/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//$( document ).on( "pagecreate", "#background", function() {
    $( ".details" ).on( "click", function() {
        var target = $( this ),
            heightPp = $( window ).height() / 3,
            widthPp = $( window ).width() / 3,
            detailsAddr = $( "#detailsAddress").val();
            short = target.attr( "details-to-show" ),
            closebtn = '',//'<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>',
            header = '<div data-role="header" data-theme="b" style="border-bottom-color: white"><h1>' + 'Szczegóły potyczki' + '</h1></div>',
            img = '<iframe style="border-style: none" id="iframe_gry" class="ui-body ui-body-b ui-corner-all" data-form="ui-body-b" data-theme="b" src="' + detailsAddr + short + '" seamless="" height="' + heightPp + '" width="' + widthPp + '"></iframe>';
            popup = '<div data-role="popup" id="popup-' + short + '" data-short="' + short +'" class="ppDetails" data-overlay-theme="b" data-theme="b" data-tolerance="15,15" class="ui-content">';
        // Create the popup.
        $( header )
            .appendTo( $( popup )
                .appendTo( $.mobile.activePage )
                .popup() )
            .toolbar()
            .before( closebtn )
            .after( img );
        // Wait with opening the popup until the popup image has been loaded in the DOM.
        // This ensures the popup gets the correct size and position
        $( "#popup-" + short).load(function() {
            // Open the popup
            $( "#popup-" + short ).popup( "open" );
            // Clear the fallback
            clearTimeout( fallback );
        });
        // Fallback in case the browser doesn't fire a load event
        var fallback = setTimeout(function() {
            $( "#popup-" + short ).popup( "open" );
        }, 100);
    });
    // Set a max-height to make large images shrink to fit the screen.
    $( document ).on( "popupbeforeposition", ".ui-popup", function() {
        var height = 400,
            width = $( window ).width();
        // Set height and width attribute of the image
        $( this ).attr({ "height": height, "width": width });
        // 68px: 2 * 15px for top/bottom tolerance, 38px for the header.
        var maxHeight = $( window ).height() - 68 + "px";
    });
    // Remove the popup after it has been closed to manage DOM size
    $( document ).on( "popupafterclose", ".ui-popup", function() {
        $( this ).remove();
    });
//});