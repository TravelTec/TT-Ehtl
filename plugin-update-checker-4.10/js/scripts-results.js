jQuery(document).ready(function(){ 
  	jQuery('[data-toggle="tooltip"]').tooltip()

	var url_atual = window.location.href;

	if(url_atual.indexOf("/hotels/") != -1){

		preenche_dados_motor();

		show_dados_pesquisa();

    }

});

var url_atual = window.location.href;

if(url_atual.indexOf("/hotels/") != -1){  

    const deleteAccount = async () => {
	  	try {
	    	await list_top_results_ehtl();
	    	await list_results_ehtl();
	  	} catch (err) {
	    	console.error(err);
	  	}
	}

	deleteAccount();
} 

function show_dados_pesquisa(){

	var destino = localStorage.getItem("DESTINO_EHTL");
	var id_destino = localStorage.getItem("ID_DESTINO_EHTL");
	var checkin = localStorage.getItem("CHECKIN_EHTL");
	var checkout = localStorage.getItem("CHECKOUT_EHTL");
	var adt = localStorage.getItem("ADT_EHTL");
	var chd = localStorage.getItem("CHD_EHTL");

	var data_chd = '';
	if(chd > 0){
		data_chd = ' e '+chd+' '+(chd > 1 ? 'crianças' : 'criança');
	}

	var html = '';

	html += 'Mostrando resultados para '+destino+'<br>';
	html += checkin+ ' a ' +checkout+'<br>';
	html += adt+' '+(adt > 1 ? 'adultos' : 'adulto')+' '+data_chd;

	jQuery(".dados_pesquisa").html(html);
}

function list_top_results_ehtl(){

	var destino = localStorage.getItem("DESTINO_EHTL");
	var id_destino = localStorage.getItem("ID_DESTINO_EHTL");

	var checkin = localStorage.getItem("CHECKIN_EHTL");
	checkin = moment(checkin, 'DD-MM-YYYY').format('YYYY-MM-DD');
	var checkout = localStorage.getItem("CHECKOUT_EHTL");
	checkout = moment(checkout, 'DD-MM-YYYY').format('YYYY-MM-DD');
    var endDate = moment(checkin, 'YYYY-MM-DD');
    var startDate = moment(checkout, 'YYYY-MM-DD'); 
    var days = startDate.diff(endDate, 'days');

	var adt = localStorage.getItem("ADT_EHTL");
	var chd = localStorage.getItem("CHD_EHTL");
	var quarto = localStorage.getItem("QTD_QUARTOS");
	var quartos = JSON.parse(localStorage.getItem("QUARTOS")); 

	var pax = parseInt(adt)+parseInt(chd);

	var access_token = localStorage.getItem("ACCESS_TOKEN"); 

	var myHeaders = new Headers();
	myHeaders.append("Content-Type", "application/json");
	myHeaders.append("Authorization", "Bearer "+access_token);
 
    var raw = JSON.stringify({
	  	"data": {
	    	"attributes": {
		      	"destinationId": id_destino,
		      	"checkin": checkin,
		      	"nights": days,
		      	"roomsAmount": quarto,
		      	"rooms": quartos,
		      	"signsInvoice": 0,
		      	"onlyAvailable": true,
		      	"perPage": 15
	    	}
	  	}
	});

	var requestOptions = {
	  	method: 'POST',
	  	headers: myHeaders,
	  	body: raw,
	  	redirect: 'follow'
	};

	fetch("https://quasar.e-htl.com.br/booking/hotels-availabilities", requestOptions)
	  	.then(response => response.text()) 
	  	.then(
	  		result => show_data(JSON.parse(result), pax, days)
	  	)
	  	.catch(error => console.log('error', error));

} 

function show_data(data, pax, days){ 

	/* mais sobre o hotel */
	/*
	/* imagem principal
	/* is featured
	/* nome do quarto
	/* hotel
	/* reviews
	/* estrelas
	/* inclusos
	/* qtd noites
	/* qtd pax
	/* valor do quarto
	/* café da manhã incluso
	/* garantia irrevogável
	/*
	/* ************************
	/*
		/* galeria de imagens
		/* nome do hotel
		/* is featured
		/* endereço
		/* categoria
		/* cnpj
		/* descrição
		/* estrelas
		/* inclusos
		/* id hotel
		/* hotelLatitude
		/* hotelLongitude
	/* 
	*/ 

	var dataJson = [];
	jQuery(data.data).each(function(i, item) {  

    	var innerObj = {};

		var rooms = item.attributes.hotelRooms; 
		innerObj["hotelCode"] = item.id;

		innerObj["breakfastIncluded"] = (item.attributes.breakfastIncluded ? 1 : 0);
		innerObj["image"] = item.attributes.hotelImages[0];

		innerObj["featured"] = (item.attributes.featured ? 1 : 0);
		innerObj["hotel"] = item.attributes.hotel;
		innerObj["hotelAdress"] = item.attributes.hotelNeighborhood;
		innerObj["hotelAdressComplete"] = item.attributes.hotelAddress;
		innerObj["reviews"] = (item.attributes.reviewRating == null ? 0 : item.attributes.reviewRating);
		innerObj["stars"] = item.attributes.hotelStars;
		innerObj["included"] = item.attributes.inclusions; 
		innerObj["hotelCategory"] = item.attributes.hotelType; 

		innerObj["night"] = days;
		innerObj["pax"] = pax; 
		innerObj["fromPrice"] = parseFloat(item.attributes.hotelLowerPrice); 
 
		innerObj["priceRoomWithoutTax"] = parseFloat(item.attributes.hotelRooms[0].totalRoomsPrice); 
		innerObj["priceRoomWithTax"] = parseFloat(item.attributes.hotelRooms[0].totalRoomsPriceWithTax); 
		innerObj["tax"] = parseFloat(item.attributes.hotelRooms[0].totalRoomsPriceWithTax)-parseFloat(item.attributes.hotelRooms[0].totalRoomsPrice); 

		/* PÁGINA DE DETALHES */

			innerObj["gallery"] = item.attributes.hotelImages; 
			innerObj["nameRoom"] = item.attributes.hotelRooms[0].roomsDetail[0].roomName; 
			innerObj["cancellationDeadline"] = (item.attributes.hotelRooms[0].irrevocableGuarantee ? item.attributes.hotelRooms[0].cancellationDeadline : 0);       

			var vaimeal = [];
			jQuery(item.attributes.hotelRooms).each(function(x, meal) {  
				vaimeal.push(item.attributes.hotelRooms[x].roomsDetail[0].regime); 
			});
			innerObj["meal"] = [];
			innerObj["meal"].push(vaimeal);  

			innerObj["description"] = item.attributes.hotelDescription;
			innerObj["rooms"] = item.attributes.hotelRooms; 
			innerObj["hotelLatitude"] = (item.attributes.hotelLatitude == null ? 0 : item.attributes.hotelLatitude); 
			innerObj["hotelLongitude"] = (item.attributes.hotelLongitude == null ? 0 : item.attributes.hotelLongitude);  

		/* ****************** */

		dataJson.push(innerObj); 

	}); 

	dataJson.sort(sortArrayByPriceMinus);

	var retorno = "";

	var xx = new Intl.NumberFormat('pt-BR', { 
	  	currency: 'BRL',
	  	minimumFractionDigits: 2,
	  	maximumFractionDigits: 2
	});

	jQuery(dataJson).each(function(i, item) { 

		var stars = "";

		if(item.stars <= 1){
			stars = '<i class="fa fa-star"></i>';
		}else if(item.stars > 1 && item.stars <= 2){
			stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i>';
		}else if(item.stars > 2 && item.stars <= 3){
			stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
		}else if(item.stars > 3 && item.stars <= 4){
			stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
		}else if(item.stars > 4){
			stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
		}

		var item_included = "";

		jQuery(item.included).each(function(x, include) { 
			if(include.indexOf("Internet") != -1 || include.indexOf("WiFi") != -1 || include.indexOf("Wi-Fi") != -1 || include.indexOf("Wifi") != -1 || include.indexOf("Wi Fi") != -1){
				item_included += '<i class="fa fa-wifi"></i> ';
			}
			if(include.indexOf("Estacionamento") != -1){
				item_included += '<i class="fa fa-car"></i> ';
			}
		}); 

		var tax = parseFloat(item.priceRoomWithTax)-parseFloat(item.priceRoomWithoutTax);

		retorno += '<div class="row rowHotel">'
			retorno += '<div class="col-lg-4 col-12 colImage" style="background: url('+item.image+');background-size: cover;">' 
			retorno += '</div>'
			retorno += '<div class="col-lg-5 col-12 colDetails">'
				retorno += '<h5>'+(item.featured == 1 ? '<img src="https://br.staticontent.com/accommodations/public/assets/images/hotel-preferential-icon.svg" data-toggle="tooltip" data-placement="bottom" title="Esta hospedagem destaca-se por ter uma excelente relação entre preço, qualidade e serviço. Por isso, e pelos comentários dos clientes, recomendamos para você.">' : '')+' '+item.hotel+'</h5>'
				retorno += '<h6><i class="fa fa-building"></i> '+item.hotelAdress+'</h6>'
				retorno += '<br>'
				retorno += '<div>'
					retorno += '<span class="review text-center" style="'+(item.reviews < 1 ? 'display:none' : '')+'">'+item.reviews+'</span> '
					retorno += stars
					retorno += '<span class="inclusion" style="'+(item.included == "" ? 'display:none' : '')+'">'
						retorno += item_included
					retorno += '</span>'
				retorno += '</div>'
			retorno += '</div>'
			retorno += '<div class="col-lg-3 col-12 colSelect">'
				retorno += '<small class="desc">'+item.night+' '+(item.night > 1 ? 'noites' : 'noite')+', '+item.pax+' '+(item.pax > 1 ? 'pessoas' : 'pessoa')+'</small>'
				retorno += '<br>'
				retorno += '<p class="price">'
					retorno += '<small class="currency">R$</small> '+xx.format(item.priceRoomWithoutTax)
				retorno += '</p> '
				retorno += '<small class="tax">Impostos não inclusos</small>'
				retorno += '<br>'
				retorno += '<div class="included_price">'
					retorno += '<a onclick="see_details_price('+item.priceRoomWithTax+', '+item.priceRoomWithoutTax+', \''+item.night+' '+(item.night > 1 ? 'noites' : 'noite')+', '+item.pax+' '+(item.pax > 1 ? 'pessoas' : 'pessoa')+'\', \''+item.night+'\', '+tax+')"><span>O que o preço total inclui?</span></a>'
					retorno += '<a onclick="see_details_hotel('+i+')"><button class="btn btnSelect">Ver detalhe</button></a>'
				retorno += '</div>'
			retorno += '</div>'
		retorno += '</div>';

	});

	retorno += '<div class="container" style="margin: 30px 0px;font-family: \'Montserrat\';">';
		retorno += '<div class="row justify-content-center">';
			retorno += '<div class="col-lg-12 col-12 text-center">';

				retorno += '<div class="">';

					retorno += "<input type='hidden' id='pageActive' value=''>";

					var total_pages = data.header.pagination.totalPages;

					for(var i = 1; i <= total_pages; i++){
						if(i == 1){
							retorno += '<span style="padding: 10px;font-size: 17px;font-weight: 800;color: #000000;cursor:pointer" onclick="show_page('+i+')">'+i+'</span>';
						}else{
							retorno += '<span style="padding: 10px;font-size: 17px;cursor:pointer" onclick="show_page('+i+')">'+i+'</span>';
						}
					}

				retorno += '</div>';

			retorno += '</div>';
		retorno += '</div>';
	retorno += '</div>';

	jQuery("#show_results .resultsHotel").html(retorno);

	setTimeout(function(){

      	start_filters();

   	}, 2200); 

}

function start_filters(){

	var data = JSON.parse(localStorage.getItem("JSONDATARESULT")); 

	/* FILTER PRICE */
		var retorno_price = "";
		retorno_price += '<input type="hidden" id="count_results" value="'+data.header.totalHotels+'">';

		retorno_price += '<input type="hidden" id="min_price" value="'+data.header.search.price.min+'">';
		retorno_price += '<input type="hidden" id="max_price" value="'+data.header.search.price.max+'">';

		retorno_price += '<input type="hidden" id="stars_filter" value="all">';

		retorno_price += '<input type="hidden" id="meal_filter" value="all">';

		retorno_price += '<input type="hidden" id="acomodacao_filter" value="all">';

		retorno_price += '<div class="accordion accordion-flush" id="accordionFlushPrice">';
			retorno_price += '<div class="accordion-item">';
			    retorno_price += '<h2 class="accordion-header" id="flush-headingPrice">';
			      	retorno_price += '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-price" aria-expanded="true" aria-controls="flush-collapseOne">';
			        	retorno_price += 'Preço';
			      	retorno_price += '</button>';
			    retorno_price += '</h2>';
			    retorno_price += '<div id="flush-price" class="accordion-collapse collapse show" aria-labelledby="flush-headingPrice" data-bs-parent="#accordionFlushPrice">';
			      	retorno_price += '<div class="accordion-body"> ';
			      		retorno_price += '<div class="row">';
			      			retorno_price += '<div class="col-lg-6 col-6">';
			      				retorno_price += '<label class="price-range-left">Mín.</label>';
			      			retorno_price += '</div>';
			      			retorno_price += '<div class="col-lg-6 col-6 text-right">';
			      				retorno_price += '<label class="price-range-right">Máx.</label>';
			      			retorno_price += '</div>';
			      		retorno_price += '</div>';
			      		retorno_price += '<div class="row">';
			      			retorno_price += '<div class="col-lg-12 col-12 range">';
								retorno_price += '<div id="steps-slider" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr">';
								retorno_price += '</div>  ';
							retorno_price += '</div>  ';
						retorno_price += '</div>    ';
			      	retorno_price += '</div>';
			    retorno_price += '</div>';
			retorno_price += '</div>';
		retorno_price += '</div>';

		jQuery(".filter-price").html(retorno_price);

		var stepsSlider = document.getElementById('steps-slider');
		var input0 = document.getElementById('input-with-keypress-0');
		var input1 = document.getElementById('input-with-keypress-1');
		var inputs = [input0, input1];

		noUiSlider.create(stepsSlider, {
		    start: [data.header.search.price.min, data.header.search.price.max],
		    connect: true,
		    tooltips: [true, wNumb({decimals: 1})],
		    range: {
		        'min': data.header.search.price.min,
		        'max': data.header.search.price.max
		    }
		});

		stepsSlider.noUiSlider.on('change', function (values, handle) { 
		    if(handle == 0){
		    	jQuery("#min_price").val(values[handle]); 
		    }
		    if(handle == 1){ 
		    	jQuery("#max_price").val(values[handle]);
		    }
		    filter_results();
		}); 
	/* ************ */

	/* FILTER STARS */
		var retorno_stars = "";

		retorno_stars += '<div class="accordion accordion-flush" id="accordionFlushStars">';
			retorno_stars += '<div class="accordion-item">';
			    retorno_stars += '<h2 class="accordion-header" id="flush-headingStars">';
			      	retorno_stars += '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-stars" aria-expanded="false" aria-controls="flush-collapseOne">';
			        	retorno_stars += 'Estrelas';
			      	retorno_stars += '</button>';
			    retorno_stars += '</h2>';
			    retorno_stars += '<div id="flush-stars" class="accordion-collapse collapse" aria-labelledby="flush-headingStars" data-bs-parent="#accordionFlushStars">';
			      	retorno_stars += '<div class="accordion-body"> ';

			      		retorno_stars += '<div class="row all-stars">';
			      			retorno_stars += '<div class="col-lg-12 col-12">';
			      				retorno_stars += '<div class="form-check form-check-inline">';
								  	retorno_stars += '<input class="form-check-input" type="checkbox" id="inlineCheckbox6" value="all" checked disabled onclick="change_filter_stars(\'all\')">';
								  	retorno_stars += '<label class="form-check-label" for="inlineCheckbox6">Todas as estrelas</label>';
								retorno_stars += '</div>';
			      			retorno_stars += '</div> ';
			      		retorno_stars += '</div> ';

			      		var array = data.header.search.stars; 
			      		array.sort(sortArrayByStarsFilter);

			      		jQuery(array).each(function(i, item) { 
			      			var desc_star = '';
			      			var qty_stars = '';
			      			switch(item){
			      				case 1:
			      					desc_star = 'one';
			      					qty_stars = '<i class="fa fa-star"></i>';
			      				break;
			      				case 2:
			      					desc_star = 'two';
			      					qty_stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			      				break;
			      				case 3:
			      					desc_star = 'three';
			      					qty_stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			      				break;
			      				case 4:
			      					desc_star = 'four';
			      					qty_stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			      				break;
			      				case 5:
			      					desc_star = 'five';
			      					qty_stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			      				break;
			      			}
				      		retorno_stars += '<div class="row '+desc_star+'-stars">';
				      			retorno_stars += '<div class="col-lg-12 col-12">';
				      				retorno_stars += '<div class="form-check form-check-inline">';
									  	retorno_stars += '<input class="form-check-input" type="checkbox" id="inlineCheckbox'+i+'" value="'+item+'" onclick="change_filter_stars(\''+item+'\')">';
									  	retorno_stars += '<label class="form-check-label" for="inlineCheckbox'+i+'">'+qty_stars+'</label>';
									retorno_stars += '</div>';
				      			retorno_stars += '</div> ';
				      		retorno_stars += '</div>   ';
				      	});

			      	retorno_stars += '</div>';
			    retorno_stars += '</div>';
			retorno_stars += '</div>';
		retorno_stars += '</div>'; 

		jQuery(".filter-stars").html(retorno_stars);
	/* ************ */

	/* FILTER MEAL */
		var retorno_meal = "";

		retorno_meal += '<div class="accordion accordion-flush" id="accordionFlushRefeicao">';
			retorno_meal += '<div class="accordion-item">';
			    retorno_meal += '<h2 class="accordion-header" id="flush-headingRefeicao">';
			      	retorno_meal += '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-refeicao" aria-expanded="false" aria-controls="flush-collapseOne">';
			        	retorno_meal += 'Refeição';
			      	retorno_meal += '</button>';
			    retorno_meal += '</h2>';
			    retorno_meal += '<div id="flush-refeicao" class="accordion-collapse collapse" aria-labelledby="flush-headingRefeicao" data-bs-parent="#accordionFlushRefeicao">';
			      	retorno_meal += '<div class="accordion-body"> ';
			      		retorno_meal += '<div class="row">';
			      			retorno_meal += '<div class="col-lg-12 col-12">';
			      				retorno_meal += '<div class="form-check form-check-inline">';
								  	retorno_meal += '<input class="form-check-input" type="checkbox" id="inlineCheckboxMeal" value="option1" checked disabled onclick="change_filter_meal(\'all\')">';
								  	retorno_meal += '<label class="form-check-label" for="inlineCheckbox1">Todas as opções</label>';
								retorno_meal += '</div>';
			      			retorno_meal += '</div> ';
			      		retorno_meal += '</div>  ';

			      		jQuery(data.header.search.regime).each(function(i, item) { 
			      			var desc_regime = '';
			      			switch(item){
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
				      		retorno_meal += '<div class="row">';
				      			retorno_meal += '<div class="col-lg-12 col-12">';
				      				retorno_meal += '<div class="form-check form-check-inline">';
									  	retorno_meal += '<input class="form-check-input" type="checkbox" id="inlineCheckboxMeal'+i+'" value="'+item+'" onclick="change_filter_meal(\''+item+'\')">';
									  	retorno_meal += '<label class="form-check-label" for="inlineCheckbox'+i+'">'+desc_regime+'</label>';
									retorno_meal += '</div>';
				      			retorno_meal += '</div> ';
				      		retorno_meal += '</div>  ';
				      	});

			      	retorno_meal += '</div>';
			    retorno_meal += '</div>';
			retorno_meal += '</div>';
		retorno_meal += '</div>';

		jQuery(".filter-refeicao").html(retorno_meal);
	/* *********** */

	/* FILTER ACOMODACAO */
		var retorono_acomodacao = "";

		retorono_acomodacao += '<div class="accordion accordion-flush" id="accordionFlushAcomodacao">';
			retorono_acomodacao += '<div class="accordion-item">';
			    retorono_acomodacao += '<h2 class="accordion-header" id="flush-headingAcomodacao">';
			      	retorono_acomodacao += '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-acomodacao" aria-expanded="false" aria-controls="flush-collapseOne">';
			        	retorono_acomodacao += 'Acomodação';
			      	retorono_acomodacao += '</button>';
			    retorono_acomodacao += '</h2>';
			    retorono_acomodacao += '<div id="flush-acomodacao" class="accordion-collapse collapse" aria-labelledby="flush-headingAcomodacao" data-bs-parent="#accordionFlushAcomodacao">';
			      	retorono_acomodacao += '<div class="accordion-body"> ';
			      		retorono_acomodacao += '<div class="row">';
			      			retorono_acomodacao += '<div class="col-lg-12 col-12">';
			      				retorono_acomodacao += '<div class="form-check form-check-inline">';
								  	retorono_acomodacao += '<input class="form-check-input" type="checkbox" id="inlineCheckboxAcomodacao" value="all" checked disabled onclick="change_filter_acomodacao(\'all\')">';
								  	retorono_acomodacao += '<label class="form-check-label" for="inlineCheckbox10">Todas as opções</label>';
								retorono_acomodacao += '</div>';
			      			retorono_acomodacao += '</div> ';
			      		retorono_acomodacao += '</div>  ';

			      		jQuery(data.header.search.hotelTypes).each(function(i, item) { 
				      		retorono_acomodacao += '<div class="row">';
				      			retorono_acomodacao += '<div class="col-lg-12 col-12">';
				      				retorono_acomodacao += '<div class="form-check form-check-inline">';
									  	retorono_acomodacao += '<input class="form-check-input" type="checkbox" id="inlineCheckboxAcomodacao'+i+'" value="'+item+'" onclick="change_filter_acomodacao(\''+item+'\')">';
									  	retorono_acomodacao += '<label class="form-check-label" for="inlineCheckboxAcomodacao'+i+'">'+item+'</label>';
									retorono_acomodacao += '</div>';
				      			retorono_acomodacao += '</div> ';
				      		retorono_acomodacao += '</div>  ';
				      	});

			      	retorono_acomodacao += '</div>';
			    retorono_acomodacao += '</div>';
			retorono_acomodacao += '</div>';
		retorono_acomodacao += '</div>';

		jQuery(".filter-acomodacao").html(retorono_acomodacao);
	/* ***************** */

	jQuery("#filter").removeClass("row-is-loading");

}

function change_filter_stars(qtyStars){
	var val_filter = jQuery("#stars_filter").val();

	var desc_val_filter = [];
	if(qtyStars !== "all"){
		jQuery("#inlineCheckbox6").removeAttr("disabled");
		jQuery("#inlineCheckbox6").removeAttr("checked");

		var innerObj = {};
		for(var i = 0; i < 5; i++){
			if (jQuery("#inlineCheckbox"+i).is(':checked') == true) {
				desc_val_filter.push(parseInt(jQuery("#inlineCheckbox"+i).val()));
			}
		}

		jQuery("#stars_filter").val(JSON.stringify(desc_val_filter));
	}else{
		for(var i = 0; i < 5; i++){
			jQuery("#inlineCheckbox"+i).removeAttr("checked");
		}

		jQuery("#inlineCheckbox6").attr("disabled", "disabled");
		jQuery("#inlineCheckbox6").prop("disabled", true);
		jQuery("#stars_filter").val("all");
	}

	if(jQuery("#inlineCheckbox0").is(':checked') == false && jQuery("#inlineCheckbox1").is(':checked') == false && jQuery("#inlineCheckbox2").is(':checked') == false && jQuery("#inlineCheckbox3").is(':checked') == false && jQuery("#inlineCheckbox4").is(':checked') == false){ 

		jQuery("#inlineCheckbox6").attr("disabled", "disabled");
		jQuery("#inlineCheckbox6").prop("disabled", true);

		jQuery("#inlineCheckbox6").attr("checked", "checked");
		jQuery("#inlineCheckbox6").prop("checked", true);

		jQuery("#stars_filter").val("all");

	}

	filter_results();
}

function change_filter_meal(typeMeal){
	var val_filter = jQuery("#meal_filter").val();

	var desc_val_filter = [];
	if(typeMeal !== "all"){
		jQuery("#inlineCheckboxMeal").removeAttr("disabled");
		jQuery("#inlineCheckboxMeal").removeAttr("checked");

		var innerObj = {};
		for(var i = 0; i < 5; i++){
			if (jQuery("#inlineCheckboxMeal"+i).is(':checked') == true) {
				desc_val_filter.push(jQuery("#inlineCheckboxMeal"+i).val());
			}
		}

		jQuery("#meal_filter").val(JSON.stringify(desc_val_filter));
	}else{
		for(var i = 0; i < 5; i++){
			jQuery("#inlineCheckboxMeal"+i).removeAttr("checked");
		}

		jQuery("#inlineCheckboxMeal").attr("disabled", "disabled");
		jQuery("#inlineCheckboxMeal").prop("disabled", true);
		jQuery("#meal_filter").val("all");
	}

	if(jQuery("#inlineCheckboxMeal0").is(':checked') == false && jQuery("#inlineCheckboxMeal1").is(':checked') == false && jQuery("#inlineCheckboxMeal2").is(':checked') == false && jQuery("#inlineCheckboxMeal3").is(':checked') == false && jQuery("#inlineCheckboxMeal4").is(':checked') == false){ 

		jQuery("#inlineCheckboxMeal").attr("disabled", "disabled");
		jQuery("#inlineCheckboxMeal").prop("disabled", true);

		jQuery("#inlineCheckboxMeal").attr("checked", "checked");
		jQuery("#inlineCheckboxMeal").prop("checked", true);

		jQuery("#meal_filter").val("all");

	}

	filter_results();
}

function change_filter_acomodacao(acommodationType){
	var val_filter = jQuery("#acomodacao_filter").val();

	var desc_val_filter = [];
	if(acommodationType !== "all"){
		jQuery("#inlineCheckboxAcomodacao").removeAttr("disabled");
		jQuery("#inlineCheckboxAcomodacao").removeAttr("checked");

		var innerObj = {};
		for(var i = 0; i < 5; i++){
			if (jQuery("#inlineCheckboxAcomodacao"+i).is(':checked') == true) {
				desc_val_filter.push(jQuery("#inlineCheckboxAcomodacao"+i).val());
			}
		}

		jQuery("#acomodacao_filter").val(JSON.stringify(desc_val_filter));
	}else{
		for(var i = 0; i < 5; i++){
			jQuery("#inlineCheckboxAcomodacao"+i).removeAttr("checked");
		}

		jQuery("#inlineCheckboxAcomodacao").attr("disabled", "disabled");
		jQuery("#inlineCheckboxAcomodacao").prop("disabled", true);
		jQuery("#acomodacao_filter").val("all");
	}

	if(jQuery("#inlineCheckboxAcomodacao0").is(':checked') == false && jQuery("#inlineCheckboxAcomodacao1").is(':checked') == false && jQuery("#inlineCheckboxAcomodacao2").is(':checked') == false && jQuery("#inlineCheckboxAcomodacao3").is(':checked') == false && jQuery("#inlineCheckboxAcomodacao4").is(':checked') == false){ 

		jQuery("#inlineCheckboxAcomodacao").attr("disabled", "disabled");
		jQuery("#inlineCheckboxAcomodacao").prop("disabled", true);

		jQuery("#inlineCheckboxAcomodacao").attr("checked", "checked");
		jQuery("#inlineCheckboxAcomodacao").prop("checked", true);

		jQuery("#acomodacao_filter").val("all");

	}

	filter_results();
}

function filter_results(type = null){

	if(type == 1){ 
		for(var i = 0; i < 5; i++){
			jQuery("#inlineCheckboxAcomodacao"+i).removeAttr("checked");
		}

		for(var i = 0; i < 5; i++){
			jQuery("#inlineCheckboxMeal"+i).removeAttr("checked");
		}

		for(var i = 0; i < 5; i++){
			jQuery("#inlineCheckbox"+i).removeAttr("checked");
		}

		jQuery("#inlineCheckboxAcomodacao").attr("disabled", "disabled");
		jQuery("#inlineCheckboxAcomodacao").prop("disabled", true); 
		jQuery("#inlineCheckboxAcomodacao").attr("checked", "checked");
		jQuery("#inlineCheckboxAcomodacao").prop("checked", true);

		jQuery("#inlineCheckboxMeal").attr("disabled", "disabled");
		jQuery("#inlineCheckboxMeal").prop("disabled", true);  
		jQuery("#inlineCheckboxMeal").attr("checked", "checked");
		jQuery("#inlineCheckboxMeal").prop("checked", true);

		jQuery("#inlineCheckbox6").attr("disabled", "disabled");
		jQuery("#inlineCheckbox6").prop("disabled", true);  
		jQuery("#inlineCheckbox6").attr("checked", "checked");
		jQuery("#inlineCheckbox6").prop("checked", true);
	}

	var checkin = localStorage.getItem("CHECKIN_EHTL");
	checkin = moment(checkin, 'DD-MM-YYYY').format('YYYY-MM-DD');
	var checkout = localStorage.getItem("CHECKOUT_EHTL");
	checkout = moment(checkout, 'DD-MM-YYYY').format('YYYY-MM-DD');
    var endDate = moment(checkin, 'YYYY-MM-DD');
    var startDate = moment(checkout, 'YYYY-MM-DD'); 
    var days = startDate.diff(endDate, 'days');

	var adt = localStorage.getItem("ADT_EHTL");
	var chd = localStorage.getItem("CHD_EHTL"); 

	var pax = parseInt(adt)+parseInt(chd);

	var data = JSON.parse(localStorage.getItem("JSONDATARESULT")); 

	var dataJson = [];
	jQuery(data.data).each(function(i, item) {  

    	var innerObj = {};

		var rooms = item.attributes.hotelRooms; 
		innerObj["hotelCode"] = item.id;

		innerObj["breakfastIncluded"] = (item.attributes.breakfastIncluded ? 1 : 0);
		innerObj["image"] = item.attributes.hotelImages[0];

		innerObj["featured"] = (item.attributes.featured ? 1 : 0);
		innerObj["hotel"] = item.attributes.hotel;
		innerObj["hotelAdress"] = item.attributes.hotelNeighborhood;
		innerObj["hotelAdressComplete"] = item.attributes.hotelAddress;
		innerObj["reviews"] = (item.attributes.reviewRating == null ? 0 : item.attributes.reviewRating);
		innerObj["stars"] = item.attributes.hotelStars;
		innerObj["included"] = item.attributes.inclusions; 
		innerObj["hotelCategory"] = item.attributes.hotelType; 

		innerObj["night"] = days;
		innerObj["pax"] = pax; 
		innerObj["fromPrice"] = parseFloat(item.attributes.hotelLowerPrice); 
 
		innerObj["priceRoomWithoutTax"] = parseFloat(item.attributes.hotelRooms[0].totalRoomsPrice); 
		innerObj["priceRoomWithTax"] = parseFloat(item.attributes.hotelRooms[0].totalRoomsPriceWithTax); 
		innerObj["tax"] = parseFloat(item.attributes.hotelRooms[0].totalRoomsPriceWithTax)-parseFloat(item.attributes.hotelRooms[0].totalRoomsPrice); 

		/* PÁGINA DE DETALHES */

			innerObj["gallery"] = item.attributes.hotelImages; 
			innerObj["nameRoom"] = item.attributes.hotelRooms[0].roomsDetail[0].roomName; 
			innerObj["cancellationDeadline"] = (item.attributes.hotelRooms[0].irrevocableGuarantee ? item.attributes.hotelRooms[0].cancellationDeadline : 0);     

			var vaimeal = [];
			jQuery(item.attributes.hotelRooms).each(function(x, meal) {  
				vaimeal.push(item.attributes.hotelRooms[x].roomsDetail[0].regime); 
			});
			innerObj["meal"] = [];
			innerObj["meal"].push(vaimeal);  

			innerObj["description"] = item.attributes.hotelDescription;
			innerObj["rooms"] = item.attributes.hotelRooms; 
			innerObj["hotelLatitude"] = (item.attributes.hotelLatitude == null ? 0 : item.attributes.hotelLatitude); 
			innerObj["hotelLongitude"] = (item.attributes.hotelLongitude == null ? 0 : item.attributes.hotelLongitude);  

		/* ****************** */

		dataJson.push(innerObj); 

	});
	dataJson.sort(sortArrayByPriceMinus);

	/* PARÂMETROS FILTROS */

		var min_price = jQuery("#min_price").val();
		var max_price = jQuery("#max_price").val();

		if(jQuery("#stars_filter").val() == "all"){
			var stars_filter = "all";
		}else{
			var stars_filter = JSON.parse(jQuery("#stars_filter").val());
		}

		if(jQuery("#meal_filter").val() == "all"){
			var meal_filter = "all";
		}else{
			var meal_filter = JSON.parse(jQuery("#meal_filter").val());
		}

		if(jQuery("#acomodacao_filter").val() == "all"){
			var acomodacao_filter = "all";
		}else{
			var acomodacao_filter = JSON.parse(jQuery("#acomodacao_filter").val());
		}

	/* PARÂMETROS FILTROS */

	var contador = 0;
	var data = [];

	if(stars_filter == "all"){
		var array_stars = [5, 4, 3, 2, 1];
	}else{
		var array_stars = stars_filter;
	}

	if(meal_filter == "all"){
		var array_meal = ["not_included", "breakfast", "half_board", "full_board", "all_inclusive"];
	}else{
		var array_meal = meal_filter;
	}

	if(acomodacao_filter == "all"){
		var array_acomodacao = ["Flat", "Hotel", "Hotel de charme", "Hotel luxo", "Resorts"];
	}else{
		var array_acomodacao = acomodacao_filter;
	}

	jQuery(dataJson).each(function(i, item) {  

		var innerObj = {};  

		if((item.priceRoomWithoutTax <= max_price && item.priceRoomWithoutTax >= min_price) && (jQuery.inArray(item.stars, array_stars) !== -1) && (findArrayInArray(array_meal, item.meal[0])) && (jQuery.inArray(item.hotelCategory, array_acomodacao) !== -1)){
			innerObj = item;

			data.push(innerObj); 

			contador = contador+1;
		}

	});

	jQuery("#count_results").val(contador);

	jQuery(".selectOrder").val(2);
	data.sort(sortArrayByPriceMinus);

	localStorage.setItem("JSONRESULT", JSON.stringify(data));

	show_results_data(data);
}

function findArrayInArray(array1, array2) {
         
    // Loop for array1
    for(let i = 0; i < array1.length; i++) {
         
        // Loop for array2
        for(let j = 0; j < array2.length; j++) {
             
            // Compare the element of each and
            // every element from both of the
            // arrays
            if(array1[i] === array2[j]) {
             
                // Return if common element found
                return true;
            }
        }
    }
     
    // Return if no common element exist
    return false;
}

function sortArrayByReviews(b, a){
	var aName = a.reviews;
  	var bName = b.reviews; 
  	return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
}
function sortArrayByPriceMinus(a, b){
	var aName = a.priceRoomWithTax;
  	var bName = b.priceRoomWithTax; 
  	return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
}
function sortArrayByPriceMaxis(b, a){
	var aName = a.priceRoomWithTax;
  	var bName = b.priceRoomWithTax; 
  	return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
}
function sortArrayByStarsMinus(b, a){
	var aName = a.stars;
  	var bName = b.stars; 
  	return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
}
function sortArrayByStarsMaxis(a, b){
	var aName = a.stars;
  	var bName = b.stars; 
  	return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
}
function sortArrayByStarsFilter(b, a){
	var aName = a;
  	var bName = b; 
  	return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
}

function changeOrder(){
	/* tipos de ordenação 
	/* 1 = melhor pontuação
	/* 2 = preço - menor para o maior
	/* 3 = preço - maior para o menor
	/* 4 = estrelas - maior para o menor
	/* 5 = estrelas - menor para o menor
	*/
	var data = JSON.parse(localStorage.getItem("JSONRESULT"));
	var type = jQuery(".selectOrder").val();

	if(type == 1){
		data.sort(sortArrayByReviews);
	}else if(type == 2){
		data.sort(sortArrayByPriceMinus);
	}else if(type == 3){
		data.sort(sortArrayByPriceMaxis);
	}else if(type == 4){
		data.sort(sortArrayByStarsMinus);
	}else if(type == 5){
		data.sort(sortArrayByStarsMaxis);
	} 
	
	localStorage.setItem("JSONRESULT", JSON.stringify(data));

	show_results_data(data);

}

function show_results_data(dataJson){

	var data = JSON.parse(localStorage.getItem("JSONDATARESULT"));

	var retorno = "";

	var xx = new Intl.NumberFormat('pt-BR', { 
	  	currency: 'BRL',
	  	minimumFractionDigits: 2,
	  	maximumFractionDigits: 2
	});

	jQuery(dataJson).each(function(i, item) { 

		if(i < 15){

			var stars = "";

			if(item.stars <= 1){
				stars = '<i class="fa fa-star"></i>';
			}else if(item.stars > 1 && item.stars <= 2){
				stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			}else if(item.stars > 2 && item.stars <= 3){
				stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			}else if(item.stars > 3 && item.stars <= 4){
				stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			}else if(item.stars > 4){
				stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			}

			var item_included = "";

			jQuery(item.included).each(function(x, include) { 
				if(include.indexOf("Internet") != -1 || include.indexOf("WiFi") != -1 || include.indexOf("Wi-Fi") != -1 || include.indexOf("Wifi") != -1 || include.indexOf("Wi Fi") != -1){
					item_included += '<i class="fa fa-wifi"></i> ';
				}
				if(include.indexOf("Estacionamento") != -1){
					item_included += '<i class="fa fa-car"></i> ';
				}
			}); 

			var tax = parseFloat(item.priceRoomWithTax)-parseFloat(item.priceRoomWithoutTax);

			retorno += '<div class="row rowHotel">'
				retorno += '<div class="col-lg-4 col-12 colImage" style="background: url('+item.image+');background-size: cover;">' 
				retorno += '</div>'
				retorno += '<div class="col-lg-5 col-12 colDetails">'
					retorno += '<h5>'+(item.featured == 1 ? '<img src="https://br.staticontent.com/accommodations/public/assets/images/hotel-preferential-icon.svg" data-toggle="tooltip" data-placement="bottom" title="Esta hospedagem destaca-se por ter uma excelente relação entre preço, qualidade e serviço. Por isso, e pelos comentários dos clientes, recomendamos para você.">' : '')+' '+item.hotel+'</h5>'
					retorno += '<h6><i class="fa fa-building"></i> '+item.hotelAdress+'</h6>'
					retorno += '<br>'
					retorno += '<div>'
						retorno += '<span class="review text-center" style="'+(item.reviews < 1 ? 'display:none' : '')+'">'+item.reviews+'</span> '
						retorno += stars
						retorno += '<span class="inclusion" style="'+(item.included == "" ? 'display:none' : '')+'">'
							retorno += item_included
						retorno += '</span>'
					retorno += '</div>'
				retorno += '</div>'
				retorno += '<div class="col-lg-3 col-12 colSelect">'
					retorno += '<small class="desc">'+item.night+' '+(item.night > 1 ? 'noites' : 'noite')+', '+item.pax+' '+(item.pax > 1 ? 'pessoas' : 'pessoa')+'</small>'
					retorno += '<br>'
					retorno += '<p class="price">'
						retorno += '<small class="currency">R$</small> '+xx.format(item.priceRoomWithoutTax)
					retorno += '</p> '
					retorno += '<small class="tax">Impostos não inclusos</small>'
					retorno += '<br>'
					retorno += '<div class="included_price">'
						retorno += '<a onclick="see_details_price('+item.priceRoomWithTax+', '+item.priceRoomWithoutTax+', \''+item.night+' '+(item.night > 1 ? 'noites' : 'noite')+', '+item.pax+' '+(item.pax > 1 ? 'pessoas' : 'pessoa')+'\', \''+item.night+'\', '+tax+')"><span>O que o preço total inclui?</span></a>'
						retorno += '<a onclick="see_details_hotel('+i+')"><button class="btn btnSelect">Ver detalhe</button></a>'
					retorno += '</div>'
				retorno += '</div>'
			retorno += '</div>';

		}

	});

	retorno += '<div class="container" style="margin: 30px 0px;font-family: \'Montserrat\';">';
		retorno += '<div class="row justify-content-center">';
			retorno += '<div class="col-lg-12 col-12 text-center">';

				retorno += '<div class="">';

					retorno += "<input type='hidden' id='pageActive' value=''>";

					var total_pages = parseInt(jQuery("#count_results").val())/15;

					for(var i = 1; i <= total_pages; i++){
						if(i == 1){
							retorno += '<span style="padding: 10px;font-size: 17px;font-weight: 800;color: #000000;cursor:pointer" onclick="show_page('+i+')">'+i+'</span>';
						}else{
							retorno += '<span style="padding: 10px;font-size: 17px;cursor:pointer" onclick="show_page('+i+')">'+i+'</span>';
						}
					}

				retorno += '</div>';

			retorno += '</div>';
		retorno += '</div>';
	retorno += '</div>';

	if(dataJson.length == 0){
		console.log('json vazio');
		retorno = "";

		var data = JSON.parse(localStorage.getItem("JSONDATARESULT")); 

		jQuery("#count_results").val(data.header.totalHotels); 
		jQuery("#min_price").val(data.header.search.price.min); 
		jQuery("#max_price").val(data.header.search.price.max);
		jQuery("#stars_filter").val("all");
		jQuery("#meal_filter").val("all");
		jQuery("#acomodacao_filter").val("all");

		retorno += '<div class="container" style="margin: 0;font-family: \'Montserrat\';background-color: #ffdfbf;border: 1px solid #f2cca5;border-radius: 7px;color: #000;">';
			retorno += '<div class="row justify-content-center">';
				retorno += '<div class="col-lg-12 col-12" style="padding: 20px;">';
					retorno += '<h4>';
					retorno += '<i class="fa fa-exclamation"></i> Não encontramos resultados para os filtros selecionados.</h4>';
					retorno += '<a onclick="filter_results(1)" style="color:#000081;cursor:pointer"><strong style="color:#000081;cursor:pointer">Remover os filtros para ver todos os resultados.</strong></a>';
				retorno += '</div>';
			retorno += '</div>';
		retorno += '</div>';
	}

	jQuery("#show_results .resultsHotel").html(retorno);

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

function list_results_ehtl(){

	var destino = localStorage.getItem("DESTINO_EHTL");
	var id_destino = localStorage.getItem("ID_DESTINO_EHTL");

	var checkin = localStorage.getItem("CHECKIN_EHTL");
	checkin = moment(checkin, 'DD-MM-YYYY').format('YYYY-MM-DD');
	var checkout = localStorage.getItem("CHECKOUT_EHTL");
	checkout = moment(checkout, 'DD-MM-YYYY').format('YYYY-MM-DD');
    var endDate = moment(checkin, 'YYYY-MM-DD');
    var startDate = moment(checkout, 'YYYY-MM-DD'); 
    var days = startDate.diff(endDate, 'days');

	var adt = localStorage.getItem("ADT_EHTL");
	var chd = localStorage.getItem("CHD_EHTL");
	var quarto = localStorage.getItem("QTD_QUARTOS");
	var quartos = JSON.parse(localStorage.getItem("QUARTOS")); 

	var pax = parseInt(adt)+parseInt(chd);

	var access_token = localStorage.getItem("ACCESS_TOKEN"); 

	var myHeaders = new Headers();
	myHeaders.append("Content-Type", "application/json");
	myHeaders.append("Authorization", "Bearer "+access_token);
 
    var raw = JSON.stringify({
	  	"data": {
	    	"attributes": {
		      	"destinationId": id_destino,
		      	"checkin": checkin,
		      	"nights": days,
		      	"roomsAmount": quarto,
		      	"rooms": quartos,
		      	"signsInvoice": 0,
		      	"onlyAvailable": true,
		      	"perPage": 100
	    	}
	  	}
	});

	var requestOptions = {
	  	method: 'POST',
	  	headers: myHeaders,
	  	body: raw,
	  	redirect: 'follow'
	};

	fetch("https://quasar.e-htl.com.br/booking/hotels-availabilities", requestOptions)
	  	.then(response => response.text()) 
	  	.then(
	  		result => storage_data(JSON.parse(result), pax, days)
	  	)
	  	.catch(error => console.log('error', error));

} 

function storage_data(data, pax, days){
	localStorage.setItem("JSONDATARESULT", JSON.stringify(data));

	/* mais sobre o hotel */
	/*
	/* imagem principal
	/* is featured
	/* nome do quarto
	/* hotel
	/* reviews
	/* estrelas
	/* inclusos
	/* qtd noites
	/* qtd pax
	/* valor do quarto
	/* café da manhã incluso
	/* garantia irrevogável
	/*
	/* ************************
	/*
		/* galeria de imagens
		/* nome do hotel
		/* is featured
		/* endereço
		/* categoria
		/* cnpj
		/* descrição
		/* estrelas
		/* inclusos
		/* id hotel
		/* hotelLatitude
		/* hotelLongitude
	/* 
	*/ 

	var dataJson = [];
	jQuery(data.data).each(function(i, item) {  

    	var innerObj = {};

		var rooms = item.attributes.hotelRooms; 
		innerObj["hotelCode"] = item.id;

		innerObj["breakfastIncluded"] = (item.attributes.breakfastIncluded ? 1 : 0);
		innerObj["image"] = item.attributes.hotelImages[0];

		innerObj["featured"] = (item.attributes.featured ? 1 : 0);
		innerObj["hotel"] = item.attributes.hotel;
		innerObj["hotelAdress"] = item.attributes.hotelNeighborhood;
		innerObj["hotelAdressComplete"] = item.attributes.hotelAddress;
		innerObj["reviews"] = (item.attributes.reviewRating == null ? 0 : item.attributes.reviewRating);
		innerObj["stars"] = item.attributes.hotelStars;
		innerObj["included"] = item.attributes.inclusions; 
		innerObj["hotelCategory"] = item.attributes.hotelType; 

		innerObj["night"] = days;
		innerObj["pax"] = pax; 
		innerObj["fromPrice"] = parseFloat(item.attributes.hotelLowerPrice); 
 
		innerObj["priceRoomWithoutTax"] = parseFloat(item.attributes.hotelRooms[0].totalRoomsPrice); 
		innerObj["priceRoomWithTax"] = parseFloat(item.attributes.hotelRooms[0].totalRoomsPriceWithTax); 
		innerObj["tax"] = parseFloat(item.attributes.hotelRooms[0].totalRoomsPriceWithTax)-parseFloat(item.attributes.hotelRooms[0].totalRoomsPrice); 

		/* PÁGINA DE DETALHES */

			innerObj["gallery"] = item.attributes.hotelImages; 
			innerObj["nameRoom"] = item.attributes.hotelRooms[0].roomsDetail[0].roomName; 
			innerObj["cancellationDeadline"] = (item.attributes.hotelRooms[0].irrevocableGuarantee ? item.attributes.hotelRooms[0].cancellationDeadline : 0);      

			var vaimeal = [];
			jQuery(item.attributes.hotelRooms).each(function(x, meal) {  
				vaimeal.push(item.attributes.hotelRooms[x].roomsDetail[0].regime); 
			});
			innerObj["meal"] = [];
			innerObj["meal"].push(vaimeal);  

			innerObj["description"] = item.attributes.hotelDescription;
			innerObj["rooms"] = item.attributes.hotelRooms; 
			innerObj["hotelLatitude"] = (item.attributes.hotelLatitude == null ? 0 : item.attributes.hotelLatitude); 
			innerObj["hotelLongitude"] = (item.attributes.hotelLongitude == null ? 0 : item.attributes.hotelLongitude);  

		/* ****************** */

		dataJson.push(innerObj); 

	});
	localStorage.setItem("JSONRESULT", JSON.stringify(dataJson));
}

function show_page(page){

	jQuery("#pageActive").val(page);

	var retorno = localStorage.getItem("JSONRESULT"); 
	var data = JSON.parse(localStorage.getItem("JSONDATARESULT"));
 
    var jsonResult = JSON.parse(retorno);

	var retorno = '';

	var xx = new Intl.NumberFormat('pt-BR', { 
	  	currency: 'BRL',
	  	minimumFractionDigits: 2,
	  	maximumFractionDigits: 2
	});

	var contador_prox = page*15;
	var contador_prev = contador_prox-15;

	jQuery(jsonResult).each(function(i, item) { 

		if(i < contador_prox && i > contador_prev){

			var stars = "";

			if(item.stars <= 1){
				stars = '<i class="fa fa-star"></i>';
			}else if(item.stars > 1 && item.stars <= 2){
				stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			}else if(item.stars > 2 && item.stars <= 3){
				stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			}else if(item.stars > 3 && item.stars <= 4){
				stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			}else if(item.stars > 4){
				stars = '<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
			}

			var item_included = "";

			jQuery(item.included).each(function(x, include) { 
				if(include.indexOf("Internet") != -1 || include.indexOf("WiFi") != -1 || include.indexOf("Wi-Fi") != -1 || include.indexOf("Wifi") != -1 || include.indexOf("Wi Fi") != -1){
					item_included += '<i class="fa fa-wifi"></i> ';
				}
				if(include.indexOf("Estacionamento") != -1){
					item_included += '<i class="fa fa-car"></i> ';
				}
			}); 

			var tax = parseFloat(item.priceRoomWithTax)-parseFloat(item.priceRoomWithoutTax);

			retorno += '<div class="row rowHotel">'
				retorno += '<div class="col-lg-4 col-12 colImage" style="background: url('+item.image+');background-size: cover;">' 
				retorno += '</div>'
				retorno += '<div class="col-lg-5 col-12 colDetails">'
					retorno += '<h5>'+(item.featured == 1 ? '<img src="https://br.staticontent.com/accommodations/public/assets/images/hotel-preferential-icon.svg" data-toggle="tooltip" data-placement="bottom" title="Esta hospedagem destaca-se por ter uma excelente relação entre preço, qualidade e serviço. Por isso, e pelos comentários dos clientes, recomendamos para você.">' : '')+' '+item.hotel+'</h5>'
					retorno += '<h6><i class="fa fa-building"></i> '+item.hotelAdress+'</h6>'
					retorno += '<br>'
					retorno += '<div>'
						retorno += '<span class="review text-center" style="'+(item.reviews < 1 ? 'display:none' : '')+'">'+item.reviews+'</span> '
						retorno += stars
						retorno += '<span class="inclusion" style="'+(item.included == "" ? 'display:none' : '')+'">'
							retorno += item_included
						retorno += '</span>'
					retorno += '</div>'
				retorno += '</div>'
				retorno += '<div class="col-lg-3 col-12 colSelect">'
					retorno += '<small class="desc">'+item.night+' '+(item.night > 1 ? 'noites' : 'noite')+', '+item.pax+' '+(item.pax > 1 ? 'pessoas' : 'pessoa')+'</small>'
					retorno += '<br>'
					retorno += '<p class="price">'
						retorno += '<small class="currency">R$</small> '+xx.format(item.priceRoomWithoutTax)
					retorno += '</p> '
					retorno += '<small class="tax">Impostos não inclusos</small>'
					retorno += '<br>'
					retorno += '<div class="included_price">'
						retorno += '<a onclick="see_details_price('+item.priceRoomWithTax+', '+item.priceRoomWithoutTax+', \''+item.night+' '+(item.night > 1 ? 'noites' : 'noite')+', '+item.pax+' '+(item.pax > 1 ? 'pessoas' : 'pessoa')+'\', \''+item.night+'\', '+tax+')"><span>O que o preço total inclui?</span></a>'
						retorno += '<a onclick="see_details_hotel('+i+')"><button class="btn btnSelect">Ver detalhe</button></a>'
					retorno += '</div>'
				retorno += '</div>'
			retorno += '</div>';

		}

	});

	retorno += '<div class="container" style="margin: 30px 0px;font-family: \'Montserrat\';">';
		retorno += '<div class="row justify-content-center">';
			retorno += '<div class="col-lg-12 col-12 text-center">';

				retorno += '<div class="">';

					retorno += "<input type='hidden' id='pageActive' value=''>";

					var total_pages = data.header.pagination.to/15;

					for(var i = 1; i <= total_pages; i++){
						if(i == page){
							retorno += '<span style="padding: 10px;font-size: 17px;font-weight: 800;color: #000000;cursor:pointer" onclick="show_page('+i+')">'+i+'</span>';
						}else{
							retorno += '<span style="padding: 10px;font-size: 17px;cursor:pointer" onclick="show_page('+i+')">'+i+'</span>';
						}
					}

				retorno += '</div>';

			retorno += '</div>';
		retorno += '</div>';
	retorno += '</div>';

	jQuery("html, body").animate({ scrollTop: 0 }, "slow"); 

	jQuery("#show_results .resultsHotel").html(retorno);

}

function see_details_hotel(id){
	var retorno = localStorage.getItem("JSONRESULT"); 
    var jsonResult = JSON.parse(retorno);
    localStorage.setItem("HOTEL_SELECTED_EHTL", JSON.stringify(jsonResult[id]));
    window.location.href = '/hotels-detail';
}

function show_details_selected(id){

	var jsonDiarias = (jQuery("#diarias_"+id).val() != "undefined" ? JSON.parse(jQuery("#diarias_"+id).val()) : '');
	var jsonIncluso = (jQuery("#incluso_"+id).val() != "undefined" ? JSON.parse(jQuery("#incluso_"+id).val()) : '');
	var garantia = jQuery("#garantia_"+id).val();

	var retorno = '';

	retorno += '<div class="container">';

	if(jsonDiarias != ""){
		retorno += '<div class="row">';
			retorno += '<div class="col-lg-12 col-12">';
				retorno += '<h5>Valores por noite </h5>';
			retorno += '</div>';
		retorno += '</div>';

		jQuery(jsonDiarias).each(function(i, item) {

			for(var i = 0; i < item.data.length; i++){

				retorno += '<div class="row">';
					retorno += '<div class="col-lg-6 col-6">';
						retorno += item.data[i];
					retorno += '</div>';

					retorno += '<div class="col-lg-6 col-6">';
						retorno += '<strong>'+item.valor[i]+'</strong>';
					retorno += '</div>';
				retorno += '</div>';

			}

		});
	}

	if(jsonIncluso != ""){
		retorno += '<div class="row">';
			retorno += '<div class="col-lg-12 col-12">';
				retorno += '<hr>';
				retorno += '<h5>Incluso na diária </h5>';
			retorno += '</div>';
		retorno += '</div>';

		jQuery(jsonIncluso).each(function(i, item) { 

			retorno += '<div class="row">';
				retorno += '<div class="col-lg-12 col-12">';
					retorno += item;
				retorno += '</div>';
			retorno += '</div>'; 

		});
	}

	if(garantia != ""){

		retorno += '<div class="row">';
			retorno += '<div class="col-lg-12 col-12">';
				retorno += '<hr>';
				retorno += '<h5>Garantia Irrevogável </h5>';
				retorno += '<p>'+garantia+'</p>'
			retorno += '</div>';
		retorno += '</div>';

	}

	retorno += '</div>';

	bootbox.dialog({
      	title: 'Mais detalhes',
      	message: retorno
  	});

}

function get_checkout(id){

	var nome = jQuery("#nome_"+id).val();
	var apartamento = jQuery("#apartamento_"+id).val();
	var categoria = jQuery("#categoria_"+id).val();
	var regime = jQuery("#regime_"+id).val();
	var adultos = jQuery("#adultos_"+id).val();
	var criancas = jQuery("#criancas_"+id).val();
	var preco_sem_formatacao = jQuery("#preco_sem_formatacao_"+id).val();
	var checkin = jQuery("#checkin_"+id).val();
	var checkout = jQuery("#checkout_"+id).val();

	jQuery.ajax({
        type: "POST",
        url: wp_ajax_ehtl_results.ajaxurl,
        data: { action: "save_product_ehtl", nome:nome, apartamento: apartamento, categoria: categoria, regime:regime, adultos:adultos, criancas:criancas, preco_sem_formatacao:preco_sem_formatacao, checkin:checkin, checkout:checkout },
        success: function( data ) {
            var id = data.slice(0,-1); 
            jQuery.get('/?add-to-cart=' + id +'&quantity=1', function(response) { 
                window.location.href = '/finalizar-compra';
            });
        }
    });
}

function preenche_dados_motor(){

	var destino = localStorage.getItem("DESTINO_EHTL"); 

	jQuery("#field_name_ehtl").val(destino);

	var quartos = parseInt(localStorage.getItem("QTD_QUARTOS")); 
	var pax = parseInt(localStorage.getItem("ADT_EHTL"))+parseInt(localStorage.getItem("CHD_EHTL")); 

	jQuery(".qtyRoom").html(quartos+' '+(quartos > 1 ? 'quartos' : 'quarto'));
	jQuery(".qtyTotal").html(pax+' '+(pax > 1 ? 'hóspedes' : 'hóspede'));

	var data_room = JSON.parse(localStorage.getItem("QUARTOS"));
	for(var i = 1; i < (quartos+1); i++){
		jQuery("#panel"+i).attr("style", "padding:15px 15px 0 15px;");

		var contador = (i-1); 
		jQuery("#panel"+i+" .qtyAdt input").val(data_room[contador].adults);
		jQuery("#panel"+i+" .qtyChd input").val(data_room[contador].children);
		if(data_room[contador].children > 0){
			for(var x = 1; x < (parseInt(data_room[contador].children)+1); x++){
				jQuery("#panel"+i+" .idade_chd"+x).attr("style", "");

				var contador_childrenage = (x-1); 
				jQuery("#panel"+i+" .idade_chd"+x+" select").val(data_room[contador].childrenages[contador_childrenage]);
			}
		}
	}
}