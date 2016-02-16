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
    var armia;
    switch(gracz) {
        //w zależności od tego jaki gracz jest ustawiony, zmienna armia przyjmuje odpowiednią wartość
        case "Domin": armia = "Orcs"; break;
        case "Kosma": armia = "Chaos Space Marines"; break;
        case "Mandi": armia = "Dark Eldar"; break;
        case "Mixer": armia = "Necrons"; break;
        case "Ojciec": armia = "Grey Knights"; break;
        case "Osik": armia = "Skitarii"; break;
        case "Konrad": armia = "Orcs"; break;
        case "Misiek": armia = "Tau Empire"; break;
        case "Sergiusz": armia = "Astra Militarum"; break;
        default: armia = "wybierz armię..."; break;
    }
    
    $(selector).val(armia);
    $(selector).parent().find('span').text(armia);
});