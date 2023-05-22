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
		jQuery("#data_cancel").attr("style", "");
	}

	jQuery("#endereco_hotel").html(hotel["hotelAdressComplete"]);
	jQuery("#mapa_hotel").html('<iframe width="" height="150" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" id="gmap_canvas" src="https://maps.google.com/maps?height=150&amp;hl=en&amp;q='+hotel["hotelAdressComplete"]+'+('+order[6]+')&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>');

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
