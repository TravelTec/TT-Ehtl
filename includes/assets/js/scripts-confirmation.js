jQuery(document).ready(function(){ 



	moment.locale('pt-br');



	var url_atual = window.location.href;



	if(url_atual.indexOf("confirm-order") != -1){

		list_data_confirmation();

	}



});



function list_data_confirmation(){



	var order = JSON.parse(localStorage.getItem("ORDER_EHTL")); 

	var hotel = JSON.parse(localStorage.getItem("HOTEL_SELECTED_EHTL"));  

	var order_accepted = JSON.parse(localStorage.getItem("ORDER_ACCEPTED")); 



	var xx = new Intl.NumberFormat('pt-BR', { 

	  	currency: 'BRL',

	  	minimumFractionDigits: 2,

	  	maximumFractionDigits: 2

	});



	var destino = localStorage.getItem("DESTINO_EHTL").split(" ");

	jQuery("#local_reserva").html(localStorage.getItem("DESTINO_EHTL"));

	jQuery(".hotel_reserva").html('<strong>'+order[6]+'</strong>');

	jQuery("#checkin_reserva").html(moment(localStorage.getItem("CHECKIN_EHTL"), 'DD-MM-YYYY').format("DD [de] MMMM [de] YYYY"));



	if(hotel["rooms"][0]["irrevocableGuarantee"] === false){

		jQuery("#info_cancelamento").html('Você pode cancelar até '+moment(hotel["rooms"][0]["cancellationDeadline"], 'YYYY-MM-DD').format("DD [de] MMMM [de] YYYY [às] h:mm"));

		jQuery("#data_cancel").attr("style", "margin-bottom:0");

	}



	jQuery("#endereco_hotel").html(hotel["hotelAdressComplete"]);

	jQuery("#mapa_hotel").html('<iframe width="" height="150" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" id="gmap_canvas" src="https://maps.google.com/maps?height=150&amp;hl=en&amp;q='+hotel["hotelAdressComplete"]+'+('+order[6]+')&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" style="width:100%"></iframe>');



	jQuery("#desc_dia_room_reserva").html((parseInt(order[2])+1)+' '+((parseInt(order[2])+1) > 1 ? 'diárias' : 'diária')+', '+localStorage.getItem("QTD_QUARTOS")+' '+(localStorage.getItem("QTD_QUARTOS") > 1 ? 'quartos' : 'quarto'));

	jQuery("#desc_sua_reserva_para").html(localStorage.getItem("ADT_EHTL")+' '+(localStorage.getItem("ADT_EHTL") > 1 ? 'adultos' : 'adultos')+' '+(localStorage.getItem("CHD_EHTL") > 0 ? ' e '+localStorage.getItem("CHD_EHTL")+' '+(localStorage.getItem("CHD_EHTL") > 1 ? 'crianças' : 'criança') : ''));

	jQuery("#desc_sua_reserva_checkin").html(moment(localStorage.getItem("CHECKIN_EHTL"), 'DD-MM-YYYY').format("DD [de] MMMM [de] YYYY"));

	jQuery("#desc_sua_reserva_checkout").html(moment(localStorage.getItem("CHECKOUT_EHTL"), 'DD-MM-YYYY').format("DD [de] MMMM [de] YYYY")); 



	jQuery("#desc_room_reserva").html(localStorage.getItem("QTD_QUARTOS")+' '+(localStorage.getItem("QTD_QUARTOS") > 1 ? 'quartos' : 'quarto')+' '+order[9]);

	jQuery("#desc_taxa_reserva").html('Taxa de R$ '+xx.format(order[4])+' inclusa');

	jQuery("#price_total").html('R$ '+order[10]);



	jQuery("#desc_titular").html(order_accepted.data.attributes.customerName);

	jQuery("#desc_qtd_rooms").html(localStorage.getItem("QTD_QUARTOS")+' '+(localStorage.getItem("QTD_QUARTOS") > 1 ? 'quartos' : 'quarto'));

	jQuery("#desc_tipo_room").html(order[9]);


	if(jQuery("#type_reserva").val() == 2){
		var titular = localStorage.getItem("HOLDER_EHTL");

		var number = localStorage.getItem("NUMBER_EHTL");

		var month = localStorage.getItem("MONTH_EHTL");

		var year = localStorage.getItem("YEAR_EHTL"); 



		jQuery("#desc_titular_card").html(localStorage.getItem("HOLDER_EHTL"));

		jQuery("#desc_number_card").html('**** **** **** '+localStorage.getItem("NUMBER_EHTL").substr(localStorage.getItem("NUMBER_EHTL").length - 4));

		jQuery("#desc_validade_card").html(localStorage.getItem("MONTH_EHTL")+'/'+localStorage.getItem("YEAR_EHTL"));

		if(localStorage.getItem("INSTALLMENT_EHTL") == 1){

			var parcelas = 'À vista';

		}else{

			var parcelas = localStorage.getItem("INSTALLMENT_EHTL")+'x no valor de R$ '+xx.format(localStorage.getItem("PRICE_INSTALLMENT_EHTL"))+' cada parcela';

		}

		jQuery("#desc_parcelas_card").html(parcelas);
	}

	/* SEND MAIL */ 

		var plugin_dir_url = jQuery("#plugin_dir_url").val();
		var color_ehtl = jQuery("#color_ehtl").val();
		var destino = localStorage.getItem("DESTINO_EHTL");
		var hotel_reserva = order[6];
		var checkin = moment(localStorage.getItem("CHECKIN_EHTL"), "DD-MM-YYYY").format("DD [de] MMMM [de] YYYY"); 
		var checkout = moment(localStorage.getItem("CHECKOUT_EHTL"), "DD-MM-YYYY").format("DD [de] MMMM [de] YYYY");
		var irrevocableGuarantee = hotel["rooms"][0]["irrevocableGuarantee"];
		var cancellationDeadline = moment(hotel["rooms"][0]["cancellationDeadline"], "YYYY-MM-DD").format("DD [de] MMMM [de] YYYY [às] h:mm");
		var hotelAdressComplete = hotel["hotelAdressComplete"];
		var diaria = (parseInt(order[2])+1)+" "+((parseInt(order[2])+1) > 1 ? "diárias" : "diária");
		var quartos = localStorage.getItem("QTD_QUARTOS")+" "+(localStorage.getItem("QTD_QUARTOS") > 1 ? "quartos" : "quarto");
		var pax = localStorage.getItem("ADT_EHTL")+" "+(localStorage.getItem("ADT_EHTL") > 1 ? "adultos" : "adultos")+" "+(localStorage.getItem("CHD_EHTL") > 0 ? " e "+localStorage.getItem("CHD_EHTL")+" "+(localStorage.getItem("CHD_EHTL") > 1 ? "crianças" : "criança") : ""); 
		var tipo_quarto = order[9];
		var taxa = xx.format(order[4]);
		var total = order[10];
		var customer = order_accepted.data.attributes.customerName;
		var type_reserva = jQuery("#type_reserva").val();
		if(jQuery("#type_reserva").val() == 2){
			var holder = localStorage.getItem("HOLDER_EHTL");
			var number = localStorage.getItem("NUMBER_EHTL").substr(localStorage.getItem("NUMBER_EHTL").length - 4);
			var month = localStorage.getItem("MONTH_EHTL")+"/"+localStorage.getItem("YEAR_EHTL"); 
		}else{
			var holder = "";
			var number = "";
			var month = "";
		}
		var order = jQuery("#order").val();
		var img_hotel = hotel["gallery"][0];
		console.log(img_hotel);
		var email_order = order_accepted.data.attributes.costumerEmail;
		var tel_order = order_accepted.data.attributes.cutomerPhone;
		var cpf_order = order_accepted.data.attributes.customerIdentity;

		if(localStorage.getItem("INSTALLMENT_EHTL") == 1){ 
			var parcelas = "À vista"; 
		}else{ 
			var parcelas = localStorage.getItem("INSTALLMENT_EHTL")+"x no valor de R$ "+xx.format(localStorage.getItem("PRICE_INSTALLMENT_EHTL"))+" cada parcela"; 
		} 

	jQuery.ajax({ 
        url : wp_ajax_ehtl_confirmation.ajaxurl,

        type : 'post', 

        data : {'action': 'send_mail_confirmation', plugin_dir_url:plugin_dir_url, color_ehtl:color_ehtl, destino:destino, hotel_reserva:hotel_reserva, checkin:checkin, checkout:checkout, irrevocableGuarantee:irrevocableGuarantee, cancellationDeadline:cancellationDeadline, hotelAdressComplete:hotelAdressComplete, diaria:diaria, quartos:quartos, pax:pax, tipo_quarto:tipo_quarto, taxa:taxa, total:total, customer:customer, type_reserva:type_reserva, holder:holder, number:number, month:month, parcelas:parcelas, order:order, img_hotel:img_hotel, email_order:email_order, tel_order:tel_order, cpf_order:cpf_order },

        success : function( resposta ) {

        }

    });

} 
