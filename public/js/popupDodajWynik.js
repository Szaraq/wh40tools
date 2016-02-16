/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$( document ).on( "pagecreate", function() {
    $( "#popupDodajWynik" ).on({
        popupbeforeposition: function() {
            var maxHeight = $( window ).height() - 200 + "px";
            var maxWidth = $( window ).width() / 3 + "px";
            $( "#popupDodajWynik form" ).css( "max-height", maxHeight );
            $( "#popupDodajWynik form" ).css( "max-width", maxWidth);
        }
    });
});