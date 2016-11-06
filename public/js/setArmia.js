/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/** Ustawia domyślną armię dla gracza na liście
 * 
 */

$('#gracz1, #gracz2').change(function(){
    var gracz = this.value;    //jaki gracz jest ustawiony?
    var selector = '#armia' + this.id.slice(-1);
    var armia = $('#armia_' + gracz).attr('value');
    
    $(selector).val(armia);
    $(selector).parent().find('span').text(armia);
});