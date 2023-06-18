/********** Panel_Dropdown ***********/
function close_panel_dropdown() {
    jQuery(".panel-dropdown").removeClass("active")
}
jQuery(".panel-dropdown a").on("click", function (event) {
    if (jQuery(this).parent().is(".active")) {
        close_panel_dropdown()
    } else {
        close_panel_dropdown();
        jQuery(this).parent().addClass("active")
    };
    event.preventDefault()
});
var mouse_is_inside = false;
jQuery(".panel-dropdown").hover(function () {
    mouse_is_inside = true
}, function () {
    mouse_is_inside = false
});
jQuery("body").mouseup(function () {
    if (!mouse_is_inside) {
        close_panel_dropdown()
    }
});


/********** Quality ***********/
function qtySum(){
    var arr = document.getElementsByName('qtyInput');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
        tot += parseInt(arr[i].value);
    }
    var cardQty = document.querySelector(".qtyTotal");
    var url_atual = window.location.href;
    if(url_atual.indexOf("/hotels/") != -1){
        cardQty.innerHTML = tot+' '+(tot > 1 ? 'hóspedes' : 'hóspede');
    }else{
        cardQty.innerHTML = tot;
    }

    var qtd_adt = 0;
    var qtd_chd = 0;
    for(var i=1; i<7; i++){
        qtd_adt += parseInt(jQuery("#panel"+i+" .qtyAdt input").val());
        qtd_chd += parseInt(jQuery("#panel"+i+" .qtyChd input").val());
    }
    jQuery("#adultos").val(qtd_adt);
    jQuery("#criancas").val(qtd_chd);
} 

function qtySumHotel(){
    var arr = document.getElementsByName('qtyInputHotel'); 
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
        tot += parseInt(arr[i].value);
    } 
    var cardQty = document.querySelector(".qtyTotalHotel");
    var url_atual = window.location.href;
    cardQty.innerHTML = tot;

    var qtd_adt = 0;
    var qtd_chd = 0;
    for(var i=1; i<7; i++){
        qtd_adt += parseInt(jQuery("#panel"+i+"adtHotel .qtyAdtHotel input").val());
        qtd_chd += parseInt(jQuery("#panel"+i+"chdHotel .qtyChdHotel input").val());
    }
    jQuery("#adultos").val(qtd_adt);
    jQuery("#criancas").val(qtd_chd);
} 

var url_atual = window.location.href;
if(jQuery("#type_motor").val() == 3){ 
    qtySumHotel();
}else{ 
    qtySum();
}

jQuery(function() { 
   jQuery(".qtyDec, .qtyInc").on("click", function() { 

        var jQuerybutton = jQuery(this);
        var oldValue = jQuerybutton.parent().find("input").val();  

        if (jQuerybutton.hasClass('qtyInc')) {
            var newVal = parseFloat(oldValue) + 1; 

            if (newVal > 4) {
                newVal = 4;
            } 
            
            if(jQuerybutton.parent().hasClass("qtyAdt")){ 
                var currentPanel = jQuery(this).parents('div[class^="panel"]').eq(0)[0].className;
                jQuery("#"+currentPanel+"adt").val(newVal);
            }

            if(jQuerybutton.parent().hasClass("qtyChd")){   
                var currentPanel = jQuery(this).parents('div[class^="panel"]').eq(0)[0].className;
 
                jQuery("#"+currentPanel+" .idade_chd"+newVal).attr("style", "");
                jQuery("#"+currentPanel+"chd").val(newVal);
            } 
        } else {                     
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
            
            if(jQuerybutton.parent().hasClass("qtyAdt")){ 
                var currentPanel = jQuery(this).parents('div[class^="panel"]').eq(0)[0].className;
                jQuery("#"+currentPanel+" #"+currentPanel+"adt").val(newVal);
            }

            if(jQuerybutton.parent().hasClass("qtyChd")){  
                var currentPanel = jQuery(this).parents('div[class^="panel"]').eq(0)[0].className;
 
                jQuery("#"+currentPanel+" .idade_chd"+(oldValue == 0 ? 1 : oldValue)).attr("style", "display:none");
                jQuery("#"+currentPanel+" #"+currentPanel+"adt").val(newVal);
            } 
        } 

        jQuerybutton.parent().find("input").val(newVal);
        qtySum();
        jQuery(".qtyTotal").addClass("rotate-x");
   });

    function removeAnimation() { 
        jQuery(".qtyTotal").removeClass("rotate-x"); 
        jQuery(".qtyRoom").removeClass("rotate-x"); 
    }
    const counter = document.querySelector(".qtyTotal");
    const counterRoom = document.querySelector(".qtyRoom");
    counter.addEventListener("animationend", removeAnimation);
});


jQuery(function() { 
   jQuery(".qtyDecHotel, .qtyIncHotel").on("click", function() { 

        var jQuerybutton = jQuery(this);
        var oldValue = jQuerybutton.parent().find("input").val();  

        if (jQuerybutton.hasClass('qtyIncHotel')) {
            var newVal = parseFloat(oldValue) + 1; 

            if (newVal > 4) {
                newVal = 4;
            } 
            
            if(jQuerybutton.parent().hasClass("qtyAdtHotel")){ 
                var currentPanel = jQuery(this).parents('div[class^="panel"]').eq(0)[0].className;
                jQuery("#"+currentPanel+"adtHotel").val(newVal);
            }

            if(jQuerybutton.parent().hasClass("qtyChdHotel")){   
                var currentPanel = jQuery(this).parents('div[class^="panel"]').eq(0)[0].className;
 
                jQuery("#"+currentPanel+" .idade_chd"+newVal).attr("style", "");
                jQuery("#"+currentPanel+"chdHotel").val(newVal);
            } 
        } else {                     
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
            
            if(jQuerybutton.parent().hasClass("qtyAdtHotel")){ 
                var currentPanel = jQuery(this).parents('div[class^="panel"]').eq(0)[0].className;
                jQuery("#"+currentPanel+" #"+currentPanel+"adtHotel").val(newVal);
            }

            if(jQuerybutton.parent().hasClass("qtyChdHotel")){  
                var currentPanel = jQuery(this).parents('div[class^="panel"]').eq(0)[0].className;
 
                jQuery("#"+currentPanel+" .idade_chd"+(oldValue == 0 ? 1 : oldValue)).attr("style", "display:none");
                jQuery("#"+currentPanel+" #"+currentPanel+"adtHotel").val(newVal);
            } 
        } 

        jQuerybutton.parent().find("input").val(newVal);
        qtySumHotel();
        jQuery(".qtyTotalHotel").addClass("rotate-x");
   });

    function removeAnimation() { 
        jQuery(".qtyTotalHotel").removeClass("rotate-x"); 
        jQuery(".qtyRoomHotel").removeClass("rotate-x"); 
    }
    const counter = document.querySelector(".qtyTotalHotel");
    const counterRoom = document.querySelector(".qtyRoomHotel");
    counter.addEventListener("animationend", removeAnimation);
});

function add_room(){
    var qtd_room_add = jQuery("#qtd_room_add").val();
    qtd_room_add = parseInt(qtd_room_add)+1;

    jQuery("#qtd_room_add").val(qtd_room_add);  
    jQuery("#quartos").val(qtd_room_add);  

    for(var i=1; i<7; i++){
        jQuery(".btnRemoverQuarto"+i).attr("style", "display:none;");
    }
    qtySum();

    jQuery(".qtyTotal").addClass("rotate-x");

    jQuery(".qtyRoom").addClass("rotate-x");
    var url_atual = window.location.href;
    if(url_atual.indexOf("/hotels/") != -1){
        jQuery(".qtyRoom").html(qtd_room_add+' '+(qtd_room_add > 1 ? 'quartos' : 'quarto'));
    }else{
        jQuery(".qtyRoom").html(qtd_room_add);
    }

    jQuery(".btnRemoverQuarto"+qtd_room_add).attr("style", "float: right;padding: 0;");
    jQuery("#panel"+qtd_room_add).attr("style", "padding:15px 15px 0 15px;");
    jQuery("#panel"+qtd_room_add+"qts").val(1);

    if(qtd_room_add == 6){
        jQuery(".spanButtonAddRoom").attr("style", "display:none");
    }

    jQuery("#adultos").val(jQuery("#panel"+qtd_room_add+" .qtyAdt input").val());
}

function remove_room(id){
    var qtd_room_add = jQuery("#qtd_room_add").val();

    for(var i=2; i<7; i++){
        jQuery(".btnRemoverQuarto"+i).attr("style", "display:none;");
    }

    jQuery("#panel"+qtd_room_add).attr("style", "display:none;");

    jQuery("#panel"+qtd_room_add+" .qtyAdt input").val(0);
    jQuery("#panel"+qtd_room_add+" .qtyChd input").val(0);
    jQuery("#panel"+qtd_room_add+"qts").val(0);
    qtySum();
    jQuery(".qtyTotal").addClass("rotate-x");
    jQuery(".qtyRoom").addClass("rotate-x");

    qtd_room_add = parseInt(qtd_room_add)-1;

    jQuery(".btnRemoverQuarto"+qtd_room_add).attr("style", "float: right;padding: 0;");
    jQuery("#qtd_room_add").val(qtd_room_add);  
    jQuery("#quartos").val(qtd_room_add);  
 
    var url_atual = window.location.href;
    if(url_atual.indexOf("/hotels/") != -1){
        jQuery(".qtyRoom").html(qtd_room_add+' '+(qtd_room_add > 1 ? 'quartos' : 'quarto'));
    }else{
        jQuery(".qtyRoom").html(qtd_room_add);
    }

    jQuery(".spanButtonAddRoom").attr("style", "");
}

jQuery(function() {
    moment.locale('pt-br');

    var url_atual = window.location.href;

    'use strict';

    if(url_atual.indexOf("/hotels/") != -1){

        if(localStorage.getItem("CHECKIN_EHTL") !== ""){
            var checkin = localStorage.getItem("CHECKIN_EHTL");
            var start = moment(checkin, 'DD-MM-YYYY').format('MM/DD/YYYY'); 
            var past = moment(checkin, 'DD-MM-YYYY').format('YYYY-MM-DD'); 
        }else{ 
            var start = moment().format('DD/MM/YYYY');
            var past = start;
        }

        if(localStorage.getItem("CHECKOUT_EHTL") !== ""){
            var checkout = localStorage.getItem("CHECKOUT_EHTL");
            var end = moment(checkout, 'DD-MM-YYYY').format('MM/DD/YYYY'); 
            var now = moment(checkout, 'DD-MM-YYYY').format('YYYY-MM-DD'); 
        }else{ 
            var end = moment().format('DD/MM/YYYY');
            var now = end;
        }
      
        var endDate = moment(past, 'YYYY-MM-DD');
        var startDate = moment(now, 'YYYY-MM-DD'); 
        var days = startDate.diff(endDate, 'days');
        
        jQuery(".date label").html((parseInt(days) == 1 ? parseInt(days)+' noite' : parseInt(days)+' noites'));
        jQuery('input[name="dates"]').attr("value", (endDate.format('DD/MM/YYYY') + ' > ' + startDate.format('DD/MM/YYYY')));
    }

    jQuery('input[name="dates"]').daterangepicker({
        autoUpdateInput: false,
        startDate: start,
        endDate: end,
        minDate: moment(),
        format: 'DD/MM/YYYY', 
        autoApply: true,
        separator: ' - ',
        locale: {
            cancelLabel: 'Cancelar',
            applyLabel: 'Aplicar',
            fromLabel: 'De',
            toLabel: 'Até',
            customRangeLabel: 'Opção',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            firstDay: 1
        }
    }); 
    jQuery('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
        const now = moment(picker.endDate.format('YYYY-MM-DD')); // Data de hoje
        const past = moment(picker.startDate.format('YYYY-MM-DD')); // Outra data no passado
        const duration = moment.duration(now.diff(past));

        // Mostra a diferença em dias
        const days = duration.asDays();
        const noites = (parseInt(days) == 1 ? parseInt(days)+' noite' : parseInt(days)+' noites'); 

        jQuery(".date label").html(noites);

        if(url_atual.indexOf("/hotels/") != -1){
            jQuery(this).val(picker.startDate.format('DD/MM/YYYY') + ' > ' + picker.endDate.format('DD/MM/YYYY'));
        }else{
            jQuery(this).val(noites+' - '+picker.startDate.format('DD/MM/YYYY') + ' > ' + picker.endDate.format('DD/MM/YYYY'));
        }
        jQuery("#field_date_checkin_ehtl").val(picker.startDate.format('DD/MM/YYYY'));
        jQuery("#field_date_checkout_ehtl").val(picker.endDate.format('DD/MM/YYYY')); 
    });
    jQuery('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
        jQuery(this).val('');
    });
}); 

var temporiza;

jQuery("#field_name_ehtl").on("input", function(){

   clearTimeout(temporiza);

   temporiza = setTimeout(function(){

      pesquisar_destinos_ehtl();

   }, 100);

});


function replaceSpecialChars(str) {

    str = str.replace(/[ÀÁÂÃÄÅ]/, "A");

    str = str.replace(/[àáâãäå]/, "a");

    str = str.replace(/[ÈÉÊË]/, "E");

    str = str.replace(/[ÍÌ]/, "I");

    str = str.replace(/[íì]/, "i");

    str = str.replace(/[ÒÓÔÕ]/, "O");

    str = str.replace(/[òóôõ]/, "o");

    str = str.replace(/[Ú]/, "U");

    str = str.replace(/[ú]/, "u");

    str = str.replace(/[Ç]/, "C");

    str = str.replace(/[ç]/, "c");



    // o resto



    return str;

} 



function clear_value(){

    jQuery("#field_name_ehtl").val('');

    jQuery('.dados').attr('style', 'display:none;');  

}



function pesquisar_destinos_ehtl(){ 

	var contador_exibicao = 0;



    var destino = jQuery("#field_name_ehtl").val();



    var contador = 0;

    if (destino.length >= 3 && contador_exibicao == 0) {  

        jQuery('.dados').attr('style', 'background-color:#fff;min-height: 25px;position:absolute;width: 100%;z-index: 9999;top: 58px;');  

        jQuery('.dados ul').html('<li style="border-bottom: none;padding: 12px 16px;font-size: 14px;font-family: \'Montserrat\';, sans-serif;cursor:pointer;list-style:none;margin: 0;"><img src="https://media.tenor.com/images/a742721ea2075bc3956a2ff62c9bfeef/tenor.gif" style="height: 22px;margin-right: 5px;position:absolute;"> <span style="margin-left: 34px;">Buscando resultados...</span></li>');
 

        var data = {

            'action': 'get_destinos_ehtl',

            'local': jQuery("#field_name_ehtl").val()

        };



        jQuery.ajax({

            url : wp_ajax_ehtl_shortcode.ajaxurl,

            type : 'post',

            data : data,

            success : function( resposta ) {

                var escrever = jQuery.parseJSON(resposta.slice(0,-1));

                localStorage.setItem("ACCESS_TOKEN", escrever[0].token);

                

                // RECEBE EM JSON A RESPOSTA DO ARQUIVO PHP

                // ESCREVE CADA ITEM SEPARADAMENTE, ATRAVÉS DO EACH, RECEBENDO OS DADOS SIGLA, DESTINO E ENDEREÇO

                var contador = 1;

                var retorno = '';

                jQuery(escrever).each(function(i, item) {



                    var destino_pesquisar = item.sigla;

                    var codigo_pesquisar = replaceSpecialChars(destino_pesquisar.toUpperCase());



                    var valor_pesquisado = replaceSpecialChars(destino.toUpperCase());

                    if (codigo_pesquisar.indexOf(valor_pesquisado) != -1) {

						if(i < 6){

							contador = contador+1;

                        	retorno += "<li style='border-bottom: none;padding: 12px 16px;font-size: 14px;font-family: \"Montserrat\", sans-serif;cursor:pointer;list-style:none;' onclick=\"selecionar_destino_ehtl('"+item.sigla+"\',\'"+item.destino+"\',\'"+item.end+"')\"  style='line-height: 20px;font-size: 14px;' id='sigla' value='"+item.destino+"'>"+item.sigla+""+item.end+"</li>";

						}

                    } 

                });

				contador_exibicao = 1;

                jQuery(".dados ul").html(retorno);

            }

        }); 

    }else{

		contador_exibicao = 0;

        jQuery('.dados').attr('style', 'background-color:#fff;min-height: 25px;position:absolute;width: 100%;z-index: 9999;top: 58px;');  

        jQuery('.dados ul').html('<li style="border-bottom: none;padding: 12px 16px;font-size: 14px;font-family: \'Montserrat\', sans-serif;cursor:pointer;list-style:none;color: #e82121;font-weight: 700;">Digite pelo menos 3 letras.</li>');

    }

}



function selecionar_destino_ehtl(destino, id, end){

    jQuery("#field_name_ehtl").val(destino);



    localStorage.setItem("DESTINO_EHTL", destino);

    localStorage.setItem("ID_DESTINO_EHTL", id);



    jQuery('.dados').attr('style', 'display:none;');  

}

function show_div_idade_chd(){
    var id = jQuery("#criancas").val(); 

    if(id > 6){
        id = 6;
        jQuery("#criancas").val(6);
    }

    for(var i = 1; i < 7; i++){
        jQuery(".idadeChd"+i).attr("style", "display:none");
    }

    if(id > 0){

        for(var i = 1; i <= id; i++){
            jQuery(".idadeChd"+i).attr("style", "");
        }

    }
}

/***************************************************************************/

function search_results(){

    var eventhandler = function(e) { 
        e.preventDefault();   
    } 

    jQuery("form").bind('submit', eventhandler);

    jQuery(".ripple").html('<img src="https://media.tenor.com/images/a742721ea2075bc3956a2ff62c9bfeef/tenor.gif" style="height: 20px;margin-right: 0px;padding: 0px 10px;position: absolute;margin-left: 4px;"> <span style="margin-left:18px">Buscando... </span>');
    jQuery("button.ripple").attr("disabled", "disabled");
    jQuery("button.ripple").prop("disabled", true);

    if(jQuery("#field_name_ehtl").val() == ""){

        jQuery(".ripple").html('<span>Buscar </span>');
        jQuery(".ripple").removeAttr("disabled");
        swal({
            title: "É necessário informar um destino.",
            icon: "warning",
        }); 
        return false;

    }else if(jQuery("#field_date_checkin_ehtl").val() == ""){

        jQuery(".ripple").html('<span>Buscar </span>');
        jQuery(".ripple").removeAttr("disabled");
        swal({
            title: "É necessário informar uma data de check-in",
            icon: "warning",
        }); 
        return false;

    }else if(jQuery("#field_date_checkout_ehtl").val() == ""){

        jQuery(".ripple").html('<span>Buscar </span>');
        jQuery(".ripple").removeAttr("disabled");
        swal({
            title: "É necessário informar uma data de check-out.",
            icon: "warning",
        }); 
        return false;

    }else if(jQuery("#adultos").val() < 1){

        jQuery(".ripple").html('<span>Buscar </span>');
        jQuery(".ripple").removeAttr("disabled");
        swal({
            title: "É necessário selecionar ao menos 1 hóspede.",
            icon: "warning",
        }); 
        return false;

    }else{

        var checkin = jQuery("#field_date_checkin_ehtl").val();
        var checkout = jQuery("#field_date_checkout_ehtl").val();
        var adt = jQuery("#adultos").val();
        var chd = jQuery("#criancas").val();

        for(var i = 1; i <= chd; i++){
            localStorage.setItem("IDADE_CHD_EHTL"+i, 5);
        }

        localStorage.setItem("CHECKIN_EHTL", checkin);
        localStorage.setItem("CHECKOUT_EHTL", checkout);
        localStorage.setItem("ADT_EHTL", adt);
        localStorage.setItem("CHD_EHTL", chd); 

        var quartos = [];
        var quarto = 0;
        for(var i=1; i < 7; i++){
            if(parseInt(jQuery("#panel"+i+"qts").val()) == 1){
                quarto += parseInt(jQuery("#panel"+i+"qts").val());
                var innerObj = {};
                              
                innerObj["adults"] = parseInt(jQuery("#panel"+i+"adt").val());
                innerObj["children"] = parseInt(jQuery("#panel"+i+"chd").val());
                if(parseInt(jQuery("#panel"+i+"chd").val()) > 0){
                    var count = parseInt(jQuery("#panel"+i+"chd").val())+1;
                    var innerObjQts = [];
                    for(var x=1; x < count; x++){
                        if(jQuery("#panel"+i+" .idade_chd"+x+" select").val() !== ""){
                            innerObjQts.push(jQuery("#panel"+i+" .idade_chd"+x+" select").val());
                        }
                    }
                    innerObj["childrenages"] = innerObjQts;
                }
                quartos.push(innerObj);  
            }
        }

        localStorage.setItem("QTD_QUARTOS", quarto); 
        localStorage.setItem("QUARTOS", JSON.stringify(quartos));  

        window.location.href = '/hotels/';

    }

}
function search_results_hotel(){

    var eventhandler = function(e) { 
        e.preventDefault();   
    } 

    jQuery("form").bind('submit', eventhandler);

    jQuery(".ripple").html('<img src="https://media.tenor.com/images/a742721ea2075bc3956a2ff62c9bfeef/tenor.gif" style="height: 20px;margin-right: 0px;padding: 0px 10px;position: absolute;margin-left: 4px;"> <span style="margin-left:18px">Buscando... </span>');
    jQuery("button.ripple").attr("disabled", "disabled");
    jQuery("button.ripple").prop("disabled", true);

    if(jQuery("#field_name_ehtl").val() == ""){

        jQuery(".ripple").html('<span>Buscar </span>');
        jQuery(".ripple").removeAttr("disabled");
        swal({
            title: "É necessário informar um destino.",
            icon: "warning",
        }); 
        return false;

    }else if(jQuery("#field_date_checkin_ehtl").val() == ""){

        jQuery(".ripple").html('<span>Buscar </span>');
        jQuery(".ripple").removeAttr("disabled");
        swal({
            title: "É necessário informar uma data de check-in",
            icon: "warning",
        }); 
        return false;

    }else if(jQuery("#field_date_checkout_ehtl").val() == ""){

        jQuery(".ripple").html('<span>Buscar </span>');
        jQuery(".ripple").removeAttr("disabled");
        swal({
            title: "É necessário informar uma data de check-out.",
            icon: "warning",
        }); 
        return false;

    }else if(jQuery("#adultos").val() < 1){

        jQuery(".ripple").html('<span>Buscar </span>');
        jQuery(".ripple").removeAttr("disabled");
        swal({
            title: "É necessário selecionar ao menos 1 hóspede.",
            icon: "warning",
        }); 
        return false;

    }else{

        var checkin = jQuery("#field_date_checkin_ehtl").val();
        var checkout = jQuery("#field_date_checkout_ehtl").val();
        var adt = jQuery("#adultos").val();
        var chd = jQuery("#criancas").val();

        for(var i = 1; i <= chd; i++){
            localStorage.setItem("IDADE_CHD_EHTL"+i, 5);
        }

        localStorage.setItem("CHECKIN_EHTL", checkin);
        localStorage.setItem("CHECKOUT_EHTL", checkout);
        localStorage.setItem("ADT_EHTL", adt);
        localStorage.setItem("CHD_EHTL", chd); 

        var quartos = [];
        var quarto = 0;
        for(var i=1; i < 7; i++){
            if(parseInt(jQuery("#panel"+i+"qts").val()) == 1){
                quarto += parseInt(jQuery("#panel"+i+"qts").val());
                var innerObj = {};
                              
                innerObj["adults"] = parseInt(jQuery("#panel"+i+"adt").val());
                innerObj["children"] = parseInt(jQuery("#panel"+i+"chd").val());
                if(parseInt(jQuery("#panel"+i+"chd").val()) > 0){
                    var count = parseInt(jQuery("#panel"+i+"chd").val())+1;
                    var innerObjQts = [];
                    for(var x=1; x < count; x++){
                        if(jQuery("#panel"+i+" .idade_chd"+x+" select").val() !== ""){
                            innerObjQts.push(jQuery("#panel"+i+" .idade_chd"+x+" select").val());
                        }
                    }
                    innerObj["childrenages"] = innerObjQts;
                }
                quartos.push(innerObj);  
            }
        }

        localStorage.setItem("QTD_QUARTOS", quarto); 
        localStorage.setItem("QUARTOS", JSON.stringify(quartos));  

        window.location.href = '/hotels/';

    }

}