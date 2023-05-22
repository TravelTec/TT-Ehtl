jQuery(document).ready(function(){ 

	var url_atual = window.location.href;

	if(url_atual.indexOf("/hotels-detail/") != -1){

		list_details_hotel();

	}

});

// Open the Modal
function openModal() {
	document.getElementById("myModal").style.display = "block";
}

// Close the Modal
function closeModal() {
	document.getElementById("myModal").style.display = "none";
}

var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
	showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
	showSlides(slideIndex = n);
}

function showSlides(n) {
	var i;
	var slides = document.getElementsByClassName("mySlides");
	var dots = document.getElementsByClassName("demo");
	var captionText = document.getElementById("caption");
	if (n > slides.length) {slideIndex = 1}
	if (n < 1) {slideIndex = slides.length}
	for (i = 0; i < slides.length; i++) {
	slides[i].style.display = "none";
	}
	for (i = 0; i < dots.length; i++) {
	dots[i].className = dots[i].className.replace(" active", "");
	}
	slides[slideIndex-1].style.display = "block";
	dots[slideIndex-1].className += " active";
	captionText.innerHTML = dots[slideIndex-1].alt;
}

function list_details_hotel(){
	var hotel = JSON.parse(localStorage.getItem("HOTEL_SELECTED_EHTL"));

	jQuery(".imgPrincipal div").attr("style", "background-image: url("+hotel.image+");background-size: cover;height:100%"); 
	jQuery(".imgSecundaria div").attr("style", "background-image: url("+hotel.gallery[1]+");background-size: cover;height:100%");
	jQuery(".imgTres div").attr("style", "background-image: url("+hotel.gallery[2]+");background-size: cover;height:100%");
	jQuery(".imgQuatro div").attr("style", "background-image: url("+hotel.gallery[3]+");background-size: cover;height:100%");

	if(hotel.gallery.length > 3){
		var modal = "";

		modal += '<span class="close cursor" onclick="closeModal()">&times;</span>';
		modal += '<div class="modal-content">';
			jQuery(hotel.gallery).each(function(i, item) { 

				var contador = i+1; 

				modal += '<div class="mySlides">';
			      	modal += '<div class="numbertext">'+contador+' / '+hotel.gallery.length+'</div>';
			      	modal += '<img src="'+item+'" style="width:100%">';
			    modal += '</div>';

			});
			modal += '<a class="prev" onclick="plusSlides(-1)">&#10094;</a>';
    		modal += '<a class="next" onclick="plusSlides(1)">&#10095;</a> ';
  		modal += '</div>';
	}
	jQuery("#myModal").html(modal);

	jQuery("#nameHotel").html((hotel.featured == 0 ? '' : '<img src="https://br.staticontent.com/accommodations/public/assets/images/hotel-preferential-icon.svg" class="img-featured"> ')+' '+hotel.hotel);

	var stars = ""; 
	if(hotel.stars <= 1){
		stars = '<i class="fa fa-star"></i>';
	}else if(hotel.stars > 1 && hotel.stars <= 2){
		stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i>';
	}else if(hotel.stars > 2 && hotel.stars <= 3){
		stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
	}else if(hotel.stars > 3 && hotel.stars <= 4){
		stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
	}else if(hotel.stars > 4){
		stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
	}
	jQuery("#starsHotel").html(stars);

	jQuery("#addressHotel").html(hotel.hotelAdressComplete);

	var desc_regime = '';
	switch(hotel.meal[0][0]){
		case 'not_included':
			desc_regime = 'Não incluso';
		break; 
		case 'breakfast':
			desc_regime = 'Café da manhã';
		break; 
		case 'half_board':
			desc_regime = 'Meia pensão';
		break; 
		case 'full_board':
			desc_regime = 'Pensão completa';
		break; 
		case 'all_inclusive':
			desc_regime = 'All inclusive';
		break; 
	}
	jQuery("#mealHotel").html(desc_regime);
	jQuery("#nameRoom").html(hotel.nameRoom); 
	jQuery("#detailNights").html(hotel.night+' '+(hotel.night > 1 ? 'noites' : 'noite')+', '+hotel.pax+' '+(hotel.pax > 1 ? 'pessoas' : 'pessoa'));

	var xx = new Intl.NumberFormat('pt-BR', { 
	  	currency: 'BRL',
	  	minimumFractionDigits: 2,
	  	maximumFractionDigits: 2
	});
	jQuery("#price").html('<small class="currency">R$</small> '+xx.format(hotel.priceRoomWithTax));

	jQuery("#imgPrincipal div").removeClass("row-is-loading");
	jQuery("#imgSecundaria div").removeClass("row-is-loading");
	jQuery("#imgTres div").removeClass("row-is-loading");
	jQuery("#imgQuatro div").removeClass("row-is-loading");

	jQuery("#nameHotel").removeClass("row-is-loading");
	jQuery("#starsHotel").removeClass("row-is-loading");
	jQuery("#addressHotel").removeClass("row-is-loading");
 
	jQuery("#mealHotel").removeClass("row-is-loading");
	jQuery("#detailRoom").removeClass("row-is-loading");

	var tax = parseFloat(hotel.priceRoomWithTax)-parseFloat(hotel.priceRoomWithoutTax);
	jQuery(".questionRoom").html('<a style="cursor:pointer;" onclick=\"see_details_price('+hotel.priceRoomWithTax+', '+hotel.priceRoomWithoutTax+', \''+hotel.night+' '+(hotel.night > 1 ? 'noites' : 'noite')+', '+hotel.pax+' '+(hotel.pax > 1 ? 'pessoas' : 'pessoa')+'\', \''+hotel.night+'\', '+tax+')\"><strong>O que este preço inclui?</strong></a>');

	var retorno_included = ""; 
	if(hotel.included.length > 0){
		jQuery(hotel.included).each(function(i, item) {  
			var icon_item = ""; 
			if(hotel.included[i].indexOf("Internet") != -1 || hotel.included[i].indexOf("WiFi") != -1 || hotel.included[i].indexOf("Wi-Fi") != -1 || hotel.included[i].indexOf("Wifi") != -1 || hotel.included[i].indexOf("Wi Fi") != -1 || hotel.included[i].indexOf("Wi-fi") != -1){
				icon_item = '<i class="fa fa-wifi"></i> '; 
			}
			if(hotel.included[i].indexOf("Estacionamento") != -1){
				icon_item = '<i class="fa fa-car"></i> '; 
			}
			retorno_included += '<div class="col-lg-2 col-6 text-center offers" style="height:90px">';
				retorno_included += icon_item;
				retorno_included += '<br>';
				retorno_included += '<p>'+hotel.included[i]+'</p>';
			retorno_included += '</div>';  
		});

		jQuery(".rowOfferServices").html(retorno_included);

		jQuery(".rowOfferHotel").attr("style", "");
	}

	jQuery("#knowHotel").removeClass("row-is-loading");

	jQuery("#descriptionHotel").html("<p>"+hotel.description+"</p>");
	jQuery("#mapHotel").html('<iframe width="600" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" id="gmap_canvas" src="https://maps.google.com/maps?width=600&amp;height=350&amp;hl=en&amp;q='+hotel.hotelAdressComplete+'+('+hotel.hotel+')&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe> ');

	var rooms = "";
	if(hotel.rooms.length > 0){
		jQuery(hotel.rooms).each(function(i, item) {   

			rooms += '<input type="hidden" id="roomCode'+i+'" value="'+item.roomCode+'">';
			rooms += '<input type="hidden" id="hotelCode'+i+'" value="'+hotel.hotelCode+'">';

			rooms += '<input type="hidden" id="nights'+i+'" value="'+hotel.night+'">';
			rooms += '<input type="hidden" id="pax'+i+'" value="'+hotel.pax+'">';
			rooms += '<input type="hidden" id="tax'+i+'" value="'+hotel.tax+'">';
			rooms += '<input type="hidden" id="priceRoomWithoutTax'+i+'" value="'+xx.format(item.totalRoomsPrice)+'">';

			rooms += '<input type="hidden" id="hotelName'+i+'" value="'+hotel.hotel+'">';
			rooms += '<input type="hidden" id="hotelAddress'+i+'" value="'+hotel.hotelAdressComplete+'">';
			rooms += '<input type="hidden" id="stars'+i+'" value="'+hotel.stars+'">';

			rooms += '<input type="hidden" id="nameRoom'+i+'" value="'+item.roomsDetail[0].roomName+'">';
			rooms += '<input type="hidden" id="cancelationDeadline'+i+'" value="'+item.roomsDetail[0].cancellationDeadline+'">';
			rooms += '<input type="hidden" id="breakfast'+i+'" value="'+item.roomsDetail[0].breakfast+'">';
			rooms += '<input type="hidden" id="irrevocableGuarantee'+i+'" value="'+item.roomsDetail[0].irrevocableGuarantee+'">';
			rooms += '<input type="hidden" id="priceRoom'+i+'" value="'+xx.format(item.totalRoomsPriceWithTax)+'">';

			rooms += '<div class="row rowRoom">'; 
				rooms += '<div class="col-lg-12 col-12"> '; 
					rooms += '<div class="row" style="'+((i % 2 != 0) ? 'background-color:#f0f0f0' : '')+'">'; 
						rooms += '<div class="col-lg-1 col-1 text-center checkRoom"> '; 
							rooms += '<label class="radio"> '; 
							  	rooms += '<input type="radio" name="room" onclick="selectedHotel('+i+')">'; 
							  	rooms += '<span class="checkround"></span>'; 
							rooms += '</label>'; 
						rooms += '</div>'; 
						rooms += '<div class="col-lg-3 col-8">'; 
							rooms += '<img src="'+hotel.image+'">'; 
							rooms += '<p class="roomName" style="text-transform:capitalize;"><strong>'+item.roomsDetail[0].roomName+'</strong></p> '; 
						rooms += '</div>'; 
						rooms += '<div class="col-lg-5 col-12">'; 
							rooms += '<p class="roomDescription">'+item.roomsDetail[0].roomDescription+'</p> '; 
						rooms += '</div>'; 
						rooms += '<div class="col-lg-3 col-12"> '; 
							rooms += '<p class="detailRoom"> '; 
								rooms += '<span style="font-size: 12px;">'+hotel.night+' '+(hotel.night > 1 ? 'noites' : 'noite')+', '+hotel.pax+' '+(hotel.pax > 1 ? 'pessoas' : 'pessoa')+'</span>'; 
								rooms += '<br>'; 
								rooms += '<span class="price" style="color:'+jQuery("#cor_ehtl").val()+'"><small class="currency" style="color:#333">R$</small> '+xx.format(item.totalRoomsPriceWithTax)+'</span>'; 
								rooms += '<br>'; 
								rooms += '<small style="font-size: 12px;">Impostos inclusos</small>'; 
							rooms += '</p>'; 
						rooms += '</div>'; 
					rooms += '</div> '; 
				rooms += '</div> '; 
			rooms += '</div>';
		});
	}
	jQuery("#rowRoomOffers .col-lg-9").html(rooms);

	var room_selected = "";

	room_selected += '<p class="detailRoom">'; 
		room_selected += '<span style="font-size: 12px;">'+hotel.night+' '+(hotel.night > 1 ? 'noites' : 'noite')+', '+hotel.pax+' '+(hotel.pax > 1 ? 'pessoas' : 'pessoa')+'</span>';
		room_selected += '<br>';
		room_selected += '<span id="priceHotelSelected" class="price" style="color:'+jQuery("#cor_ehtl").val()+'"><small class="currency" style="color:#333">R$</small> '+xx.format(hotel.rooms[0].totalRoomsPriceWithTax)+'</span>';
		room_selected += '<br>';
		room_selected += '<small style="font-size: 12px;">Impostos inclusos</small>';
	room_selected += '</p>'; 
	room_selected += '<a href="/order-hotels/" class="btnReserva" style="display:none"><button class="btn btnSelect">Reservar</button></a>';
	room_selected += '<hr style="margin: 20px 0">';
	room_selected += '<p style="color: #333;margin-bottom: 8px;font-weight: 600;">Informação da sua reserva</p> ';

	if(hotel.rooms[0].roomsDetail[0].breakfast == true){
		room_selected += '<span id="refeicaoQuartoSelected" style="color:#333"><i class="fa fa-check" style="color: #0c9d79;margin-right: 5px;"></i> Café da manhã</span>';
	}else{
		room_selected += '<span id="refeicaoQuartoSelected" style="color:#333"> <i class="fa fa-times" style="color: #9d0c0c;margin-right: 5px;"></i> Sem café da manhã</span>';
	}
	room_selected += '<br>'; 
	if(hotel.rooms[0].roomsDetail[0].irrevocableGuarantee == true){
		room_selected += '<span id="cancelamentoQuartoSelected" style="color:#333"><i class="fa fa-times" style="color: #9d0c0c;margin-right: 5px;"></i> Não permite alterações ou cancelamento</span>';
	}else{
		if(hotel.rooms[0].roomsDetail[0].cancellationDeadline == 0){
			room_selected += '<span id="cancelamentoQuartoSelected" style="color:#333"><i class="fa fa-check" style="color: #0c9d79;margin-right: 5px;"></i> Você pode cancelar ou alterar essa reserva entrando em contato com a hospedagem até 10 dias antes.</span>';
		}else{
			room_selected += '<span id="cancelamentoQuartoSelected" style="color:#333"><i class="fa fa-check" style="color: #0c9d79;margin-right: 5px;"></i> Você pode cancelar ou alterar essa reserva até o dia <strong>'+moment(hotel.rooms[0].roomsDetail[0].cancellationDeadline, 'YYYY-MM-DD').format('DD/MM/YYYY')+'</strong>. </span>';
		}
	} 

	jQuery("#roomSelectedReservation").removeClass("row-is-loading");
	jQuery("#roomSelectedReservation").html(room_selected);
}

function selectedHotel(i){ 
	jQuery("#priceHotelSelected").html('<small class="currency" style="color:#333">R$</small> '+jQuery("#priceRoom"+i).val()); 

	if(jQuery("#breakfast"+i).val() === "true"){
		jQuery("#refeicaoQuartoSelected").html('<i class="fa fa-check" style="color: #0c9d79;margin-right: 5px;"></i> Café da manhã');
	}else{
		jQuery("#refeicaoQuartoSelected").html('<i class="fa fa-times" style="color: #9d0c0c;margin-right: 5px;"></i> Sem café da manhã');
	} 

	if(jQuery("#irrevocableGuarantee"+i).val() === "true"){
		jQuery("#cancelamentoQuartoSelected").html('<i class="fa fa-times" style="color: #9d0c0c;margin-right: 5px;"></i> Não permite alterações ou cancelamento');
	}else{
		jQuery("#cancelamentoQuartoSelected").html('<i class="fa fa-check" style="color: #0c9d79;margin-right: 5px;"></i> Você pode cancelar ou alterar essa reserva até o dia <strong>'+moment(jQuery("#cancelationDeadline"+i).val(), 'YYYY-MM-DD').format('DD/MM/YYYY')+'</strong>');
	} 

	var order = [];

	order.push(jQuery("#roomCode"+i).val()); //0
	order.push(jQuery("#hotelCode"+i).val()); //1
	order.push(parseInt(jQuery("#nights"+i).val())); //2
	order.push(parseInt(jQuery("#pax"+i).val())); //3
	order.push(parseFloat(jQuery("#tax"+i).val())); //4
	order.push(jQuery("#priceRoomWithoutTax"+i).val()); //5
	order.push(jQuery("#hotelName"+i).val()); //6
	order.push(jQuery("#hotelAddress"+i).val()); //7
	order.push(parseInt(jQuery("#stars"+i).val())); //8
	order.push(jQuery("#nameRoom"+i).val()); //9
	order.push(jQuery("#priceRoom"+i).val());  //10

    localStorage.setItem("ORDER_EHTL", JSON.stringify(order)); 

    jQuery(".btnReserva").removeAttr("style");
}



function see_details_price(price, priceWithoutTax, detail, noites, tax){

	var xx = new Intl.NumberFormat('pt-BR', { 
	  	currency: 'BRL',
	  	minimumFractionDigits: 2,
	  	maximumFractionDigits: 2
	});

	var retorno = "";

	retorno += '<div class="row" style="font-size:13px;">';
		retorno += '<div class="col-lg-8 col-8">';
			retorno += '<span>Final por noite</span>';
		retorno += '</div>';
		retorno += '<div class="col-lg-4 col-4" style="text-align:right">';
			retorno += '<span>R$ '+xx.format(price/noites)+'</span>';
		retorno += '</div>';
	retorno += '</div>';

	retorno += '<div class="row">';
		retorno += '<div class="col-lg-12 col-12">';
			retorno += '<hr style="margin: 6px 0;border-top: 1px solid #bfbfbf">';
		retorno += '</div>';
	retorno += '</div>';

	retorno += '<div class="row" style="font-size:13px;">';
		retorno += '<div class="col-lg-8 col-8">';
			retorno += '<span>'+detail+'</span>';
		retorno += '</div>';
		retorno += '<div class="col-lg-4 col-4" style="text-align:right">';
			retorno += '<span>R$ '+xx.format(priceWithoutTax)+'</span>';
		retorno += '</div>';
	retorno += '</div>';

	retorno += '<div class="row" style="font-size:13px;">';
		retorno += '<div class="col-lg-8 col-8">';
			retorno += '<span>Impostos taxas e encargos</span>';
		retorno += '</div>';
		retorno += '<div class="col-lg-4 col-4" style="text-align:right">';
			retorno += '<span>R$ '+xx.format(tax)+'</span>';
		retorno += '</div>';
	retorno += '</div>';

	retorno += '<div class="row">';
		retorno += '<div class="col-lg-12 col-12">';
			retorno += '<hr style="margin: 6px 0;border-top: 1px solid #bfbfbf;">';
		retorno += '</div>';
	retorno += '</div>';

	retorno += '<div class="row" style="font-size:15px;">';
		retorno += '<div class="col-lg-8 col-8">';
			retorno += '<span><strong>Total</strong></span>';
		retorno += '</div>';
		retorno += '<div class="col-lg-4 col-4" style="text-align:right">';
			retorno += '<span><strong>R$ '+xx.format(price)+'</strong></span>';
		retorno += '</div>';
	retorno += '</div>';

	retorno += '<div class="row">';
		retorno += '<div class="col-lg-12 col-12">';
			retorno += '<hr style="margin: 6px 0;border-top: 1px solid #bfbfbf;">';
		retorno += '</div>';
	retorno += '</div>';

	bootbox.dialog({
      	title: 'Detalhes da reserva',
      	message: retorno
  	});
}
