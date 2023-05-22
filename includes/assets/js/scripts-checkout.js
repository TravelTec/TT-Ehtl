jQuery(document).ready(function(){ 

	moment.locale('pt-br');

	var url_atual = window.location.href;

	if(url_atual.indexOf("order-hotels") != -1){

		jQuery("#celularTitular").mask("(99) 99999-9999");
		jQuery("#cpfTitular").mask("999.999.999-99");

		set_details_order();
		set_rooms();

		var rooms = JSON.parse(localStorage.getItem("QUARTOS")); 
		jQuery(rooms).each(function(i, item) {   

			for(var adt = 0; adt < item.adults; adt++){
				jQuery("#nasc_adt_"+i+"_"+adt).mask("99/99/9999");
			}

			if(item.children > 0){
				for(var chd = 0; chd < item.children; chd++){
					jQuery("#nasc_chd_"+i+"_"+chd).mask("99/99/9999");
				}
			}

		});

		jQuery("#cep").mask("99999-999");

	}

});

function set_details_order(){

	var order = JSON.parse(localStorage.getItem("ORDER_EHTL")); 
	var xx = new Intl.NumberFormat('pt-BR', { 
	  	currency: 'BRL',
	  	minimumFractionDigits: 2,
	  	maximumFractionDigits: 2
	});
 
	jQuery(".data_order").html("Hospedagem para "+order[3]+' '+(order[3] > 1 ? 'pessoas' : 'pessoa'));
	jQuery(".value_without_tax").html("R$ "+order[5]);
	jQuery(".tax").html("R$ "+xx.format(order[4]));
	jQuery(".value_total").html('<span class="currency">R$</span> '+order[10]);

	jQuery(".resume-price").removeClass("row-is-loading");

	jQuery(".title-hotel").html(order[6]);
	var stars = ""; 
	if(order[8] <= 1){
		stars = '<i class="fa fa-star" style="color: #f4a01a;"></i>';
	}else if(order[8] > 1 && order[8] <= 2){
		stars = '<i class="fa fa-star" style="color: #f4a01a;"></i> <i class="fa fa-star" style="color: #f4a01a;"></i>';
	}else if(order[8] > 2 && order[8] <= 3){
		stars = '<i class="fa fa-star" style="color: #f4a01a;"></i> <i class="fa fa-star" style="color: #f4a01a;"></i> <i class="fa fa-star" style="color: #f4a01a;"></i>';
	}else if(order[8] > 3 && order[8] <= 4){
		stars = '<i class="fa fa-star" style="color: #f4a01a;"></i> <i class="fa fa-star" style="color: #f4a01a;"></i> <i class="fa fa-star" style="color: #f4a01a;"></i> <i class="fa fa-star" style="color: #f4a01a;"></i>';
	}else if(order[8] > 4){
		stars = '<i class="fa fa-star" style="color: #f4a01a;"></i> <i class="fa fa-star" style="color: #f4a01a;"></i> <i class="fa fa-star" style="color: #f4a01a;"></i> <i class="fa fa-star" style="color: #f4a01a;"></i> <i class="fa fa-star" style="color: #f4a01a;"></i>';
	}
	jQuery(".star-hotel").html(stars); 
	jQuery(".address-hotel").html(order[7]);

	jQuery(".checkin").html(moment(localStorage.getItem("CHECKIN_EHTL"), 'DD-MM-YYYY').format("D MMM YYYY"));
	jQuery(".checkout").html(moment(localStorage.getItem("CHECKOUT_EHTL"), 'DD-MM-YYYY').format("D MMM YYYY"));

	jQuery(".detail_trip").html(order[2]+' '+(order[2] > 1 ? 'noites' : 'noite')+', '+localStorage.getItem("ADT_EHTL")+' '+(localStorage.getItem("ADT_EHTL") > 1 ? 'adultos' : 'adultos')+' '+(localStorage.getItem("CHD_EHTL") > 0 ? ' e '+localStorage.getItem("CHD_EHTL")+' '+(localStorage.getItem("CHD_EHTL") > 1 ? 'crianças' : 'criança') : ''));
	jQuery(".name_room").html(order[9]);
	jQuery(".name_room").attr("style", "text-transform: capitalize");

	jQuery(".detail").removeClass("row-is-loading");

	if(jQuery("#type_reserva_ehtl").val() == 2){
 
		var hotel = JSON.parse(localStorage.getItem("HOTEL_SELECTED_EHTL"));
		var price = hotel.priceRoomWithTax;

		var installments = "";
		for(var i = 1; i < 7; i++){
			if(i == 1){
				var option_name = 'À vista no valor de R$ '+xx.format(price);
			}else{
				var option_name = 'Em '+i+' vezes no valor de R$ '+xx.format((price/i))+' cada parcela';
			}
			installments += '<option value="'+i+';'+(price/i)+'" '+(i == 1 ? 'selected' : '')+'>'+option_name+'</option>';
		}
		jQuery("#installments").html(installments); 

	}

}

function select_installment(){
	var installment = (jQuery("#installments").val()).split(";");
	localStorage.setItem("INSTALLMENT_EHTL", installment[0]);
	localStorage.setItem("PRICE_INSTALLMENT_EHTL", installment[1]);
}

function set_rooms(){

	var retorno = "";

	var rooms = JSON.parse(localStorage.getItem("QUARTOS")); 
	var contador = 0;
	jQuery(rooms).each(function(i, item) {  

		var count_room = i+1;

		retorno += '<div class="row '+(i < (rooms.length-1)? 'rowGuest' : '')+'">  ';
			retorno += '<div class="col-lg-12 col-12"> ';
				retorno += '<strong class="qt">Quarto '+count_room+'</strong> ';
				retorno += '<br> ';
				retorno += '<br> ';

				for(var adt = 0; adt < item.adults; adt++){
					var count_adt = adt+1;

					retorno += '<p class="guest">Adulto '+count_adt+'</p>  '; 
					retorno += '<div class="row"> ';
						retorno += '<div class="col-lg-6 col-12"> ';
							retorno += '<label>Nome</label> ';
							retorno += '<div class="input-group mb-4"> ';
							  	retorno += '<div class="input-group-prepend"> ';
							    	retorno += '<i class="fa fa-user"></i> ';
							  	retorno += '</div> ';
							  	retorno += '<input type="text" id="nome_adt_'+i+'_'+adt+'" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1"> ';
							retorno += '</div> ';
						retorno += '</div> ';
						retorno += '<div class="col-lg-6 col-12"> ';
							retorno += '<label>Sobrenome</label> ';
							retorno += '<div class="input-group mb-4"> ';
							  	retorno += '<div class="input-group-prepend"> ';
							    	retorno += '<i class="fa fa-user"></i> ';
							  	retorno += '</div> ';
							  	retorno += '<input type="text" id="sobrenome_adt_'+i+'_'+adt+'" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1"> ';
							retorno += '</div> ';
						retorno += '</div> ';
						retorno += '<div class="col-lg-6 col-12"> ';
							retorno += '<label>E-mail</label> ';
							retorno += '<div class="input-group mb-4"> ';
							  	retorno += '<div class="input-group-prepend"> ';
							    	retorno += '<i class="fa fa-envelope"></i> ';
							  	retorno += '</div> ';
							  	retorno += '<input type="text" id="email_adt_'+i+'_'+adt+'" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1"> ';
							retorno += '</div> ';
						retorno += '</div> ';
						retorno += '<div class="col-lg-6 col-12"> ';
							retorno += '<label>Nascimento</label> ';
							retorno += '<div class="input-group mb-4"> ';
							  	retorno += '<div class="input-group-prepend"> ';
							    	retorno += '<i class="fa fa-calendar"></i> ';
							  	retorno += '</div> ';
							  	retorno += '<input type="text" id="nasc_adt_'+i+'_'+adt+'" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1"> ';
							retorno += '</div> ';
						retorno += '</div> ';
					retorno += '</div>  '; 
					
				}

				if(item.children > 0){
					for(var chd = 0; chd < item.children; chd++){
						var count_chd = chd+1;

						retorno += '<p class="guest">Criança '+count_chd+'</p>  '; 
						retorno += '<div class="row"> ';
							retorno += '<div class="col-lg-6 col-12"> ';
								retorno += '<label>Nome</label> ';
								retorno += '<div class="input-group mb-4"> ';
								  	retorno += '<div class="input-group-prepend"> ';
								    	retorno += '<i class="fa fa-user"></i> ';
								  	retorno += '</div> ';
								  	retorno += '<input type="text" id="nome_chd_'+i+'_'+chd+'" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1"> ';
								retorno += '</div> ';
							retorno += '</div> ';
							retorno += '<div class="col-lg-6 col-12"> ';
								retorno += '<label>Sobrenome</label> ';
								retorno += '<div class="input-group mb-4"> ';
								  	retorno += '<div class="input-group-prepend"> ';
								    	retorno += '<i class="fa fa-user"></i> ';
								  	retorno += '</div> ';
								  	retorno += '<input type="text" id="sobrenome_chd_'+i+'_'+chd+'" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1"> ';
								retorno += '</div> ';
							retorno += '</div> ';
							retorno += '<div class="col-lg-6 col-12"> ';
								retorno += '<label>E-mail</label> ';
								retorno += '<div class="input-group mb-4"> ';
								  	retorno += '<div class="input-group-prepend"> ';
								    	retorno += '<i class="fa fa-envelope"></i> ';
								  	retorno += '</div> ';
								  	retorno += '<input type="text" id="email_chd_'+i+'_'+chd+'" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1"> ';
								retorno += '</div> ';
							retorno += '</div> ';
							retorno += '<div class="col-lg-6 col-12"> ';
								retorno += '<label>Nascimento</label> ';
								retorno += '<div class="input-group mb-4"> ';
								  	retorno += '<div class="input-group-prepend"> ';
								    	retorno += '<i class="fa fa-calendar"></i> ';
								  	retorno += '</div> ';
								  	retorno += '<input type="text" id="nasc_chd_'+i+'_'+chd+'" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1"> ';
								retorno += '</div> ';
							retorno += '</div> ';
						retorno += '</div>  '; 
						
					}
				}

			retorno += '</div>  ';
		retorno += '</div> ';

		contador++;

	});

	jQuery("#set_room").html(retorno);

}

function checkCard(num){
	var msg = Array();
	var tipo = null;
	
	if(num.length > 16 || num[0]==0){
		
		msg.push("Número de cartão inválido");
		
	} else {
		
		var total = 0;
		var arr = Array();
		
		for(i=0;i<num.length;i++){
			if(i%2==0){
				dig = num[i] * 2;
					
				if(dig > 9){
					dig1 = dig.toString().substr(0,1);
					dig2 = dig.toString().substr(1,1);
					arr[i] = parseInt(dig1)+parseInt(dig2);
				} else {
					arr[i] = parseInt(dig);
				}
							
				total += parseInt(arr[i]);
	
			} else {
	
				arr[i] =parseInt(num[i]);
				total += parseInt(arr[i]);
			} 
		}
				
		switch(parseInt(num[0])){
			case 0:
				msg.push("Número incorreto");
				break;
			case 1:
				tipo = "Empresas Aéreas";
				break;
			case 2:
				tipo = "Empresas Aéreas";
				break
			case 3:
				tipo = "Viagens e Entretenimento";
				if(parseInt(num[0]+num[1]) == 34 || parseInt(num[0]+num[1])==37){	operadora = "Amex";	} 
				if(parseInt(num[0]+num[1]) == 36){	operadora = "Diners";	} 
				break
			case 4:
				tipo = "Bancos e Instituições Financeiras";
				operadora = "visa";
				break
			case 5:
				if(parseInt(num[0]+num[1]) >= 51 && parseInt(num[0]+num[1])<=55){	operadora = "Mastercard";	} 
				tipo = "Bancos e Instituições Financeiras";
				operadora = "Mastercard"
				break;
			case 6:
				tipo = "Bancos e Comerciais";
				operadora = "";
				break
			case 7:
				tipo = "Companhias de petróleo";
				operadora = "";
				break
			case 8:
				tipo = "Companhia de telecomunicações";
				operadora = "";
				break
			case 9:
				tipo = "Nacionais";
				operadora = "";
				break
			default:
				msg.push("Número incorreto");
				break;
		}

	}
	return operadora;

}

function select_credit_card(){
	var number = jQuery("#number-card").val();
	
	var operadora = checkCard(number);
	var img = "";
	if(operadora == "Mastercard"){
		img = '<img src="https://logodownload.org/wp-content/uploads/2014/07/mastercard-logo-6-1.png" style="width: 58%;float: right;">';
	}else if(operadora == "visa"){
		img = '<img src="https://logodownload.org/wp-content/uploads/2016/10/visa-logo-19.png" style="width: 58%;float: right;">';
	}else if(operadora == "Diners"){
		img = '<img src="https://logodownload.org/wp-content/uploads/2016/10/Diners-Club-Logo-5.png" style="width: 58%;float: right;">';
	}else if(operadora == "Amex"){
		img = '<img src="https://logodownload.org/wp-content/uploads/2014/04/amex-american-express-logo-4.png" style="width: 58%;float: right;">';
	}
	jQuery(".bank-card__operadora").html(img);
}

function limpa_formulário_cep() {
    // Limpa valores do formulário de cep.
    jQuery("#endereco").val("");
    jQuery("#bairro").val("");
    jQuery("#cidade").val("");
    jQuery("#estado").val(""); 
}

//Quando o campo cep perde o foco.
jQuery("#cep").blur(function() {

    //Nova variável "cep" somente com dígitos.
    var cep = jQuery(this).val().replace('-', '').replace(/\D/g, ''); 

    //Verifica se campo cep possui valor informado.
    if (cep != "") { 

        //Preenche os campos com "..." enquanto consulta webservice.
        jQuery("#endereco").val("...");
        jQuery("#bairro").val("...");
        jQuery("#cidade").val("...");
        jQuery("#estado").val("..."); 

        //Consulta o webservice viacep.com.br/
        jQuery.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

            if (!("erro" in dados)) {
                //Atualiza os campos com os valores da consulta.
                jQuery("#endereco").val(dados.logradouro);
                jQuery("#bairro").val(dados.bairro);
                jQuery("#cidade").val(dados.localidade);
                jQuery("#estado").val(dados.uf); 
            } //end if.
            else {
                //CEP pesquisado não foi encontrado.
                limpa_formulário_cep();
                swal({
		            title: "CEP não encontrado.",
		            icon: "warning",
		        }); 
		        return false;
            }
        });
         
    } //end if.
    else {
        //cep sem valor, limpa formulário.
        limpa_formulário_cep();
    }
});

function send_order(type_reserva){
	var order = JSON.parse(localStorage.getItem("ORDER_EHTL"));

	var nomeTitular = jQuery("#nomeTitular").val();
	var emailTitular = jQuery("#emailTitular").val();
	var celularTitular = jQuery("#celularTitular").val().replace("(", "").replace(")", "").replace(" ", "").replace("-", "");
	var cpfTitular = jQuery("#cpfTitular").val().replace(".", "").replace(".", "").replace(".", "").replace("-", "");

	var rooms = JSON.parse(localStorage.getItem("QUARTOS")); 

	var room = [];
	jQuery(rooms).each(function(i, item) {   

		var innerPax = [];
		for(var adt = 0; adt < item.adults; adt++){ 

			var nomepax = jQuery("#nome_adt_"+i+"_"+adt).val();
			var sobrenomepax = jQuery("#sobrenome_adt_"+i+"_"+adt).val();
			var emailpax = jQuery("#email_adt_"+i+"_"+adt).val();
			var nascpax = jQuery("#nasc_adt_"+i+"_"+adt).val();

			if(nomepax == ""){
				swal({
		            title: "É necessário preencher o nome do pax.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(sobrenomepax == ""){
				swal({
		            title: "É necessário preencher o sobrenome do pax.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(emailpax == ""){
				swal({
		            title: "É necessário preencher o e-mail do pax.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(nascpax == ""){
				swal({
		            title: "É necessário preencher a data de nascimento do pax.",
		            icon: "warning",
		        }); 
		        return false;
			}else{ 
				innerPax.push({
		            name: nomepax, 
		            lastName: sobrenomepax, 
		            email: emailpax, 
		            age: 20, 
		            type: "AD"
		        });
			}
		}

		if(item.children > 0){
			for(var chd = 0; chd < item.children; chd++){   

				var nomepax = jQuery("#nome_chd_"+i+"_"+chd).val();
				var sobrenomepax = jQuery("#sobrenome_chd_"+i+"_"+chd).val();
				var emailpax = jQuery("#email_chd_"+i+"_"+chd).val();
				var nascpax = jQuery("#nasc_chd_"+i+"_"+chd).val();

				if(nomepax == ""){
					swal({
			            title: "É necessário preencher o nome do pax.",
			            icon: "warning",
			        }); 
			        return false;
				}else if(sobrenomepax == ""){
					swal({
			            title: "É necessário preencher o sobrenome do pax.",
			            icon: "warning",
			        }); 
			        return false;
				}else if(emailpax == ""){
					swal({
			            title: "É necessário preencher o e-mail do pax.",
			            icon: "warning",
			        }); 
			        return false;
				}else if(nascpax == ""){
					swal({
			            title: "É necessário preencher a data de nascimento do pax.",
			            icon: "warning",
			        }); 
			        return false;
				}else{ 
					innerPax.push({
			            name: nomepax, 
			            lastName: sobrenomepax, 
			            email: emailpax, 
			            age: parseInt("05"), 
			            type: "CH"
			        });
				}
			}
		}
		room.push({roomsPassenger: innerPax});

	}); 

	var cep = jQuery("#cep").val().replace("-", "");
	var endereco = jQuery("#endereco").val();
	var numero = jQuery("#numero").val();
	var complemento = jQuery("#complemento").val();
	var bairro = jQuery("#bairro").val();
	var cidade = jQuery("#cidade").val();
	var estado = jQuery("#estado").val();

	var nameReserva = nomeTitular.split(" "); 

	if(nomeTitular == ""){
        swal({
            title: "É necessário preencher o nome do titular da reserva.",
            icon: "warning",
        }); 
        return false;
    }else if(emailTitular == ""){
        swal({
            title: "É necessário preencher o e-mail do titular da reserva.",
            icon: "warning",
        }); 
        return false;
    }else if(celularTitular == ""){
        swal({
            title: "É necessário preencher o celular do titular da reserva.",
            icon: "warning",
        }); 
        return false;
    }else if(cpfTitular == ""){
        swal({
            title: "É necessário preencher o CPF do titular da reserva.",
            icon: "warning",
        }); 
        return false;
    }else if(cep == ""){
        swal({
            title: "É necessário preencher o CEP.",
            icon: "warning",
        }); 
        return false;
    }else if(endereco == ""){
        swal({
            title: "É necessário preencher o endereço.",
            icon: "warning",
        }); 
        return false;
    }else if(numero == ""){
        swal({
            title: "É necessário preencher o número.",
            icon: "warning",
        }); 
        return false;
    }else if(bairro == ""){
        swal({
            title: "É necessário preencher o bairro.",
            icon: "warning",
        }); 
        return false;
    }else if(cidade == ""){
        swal({
            title: "É necessário preencher a cidade.",
            icon: "warning",
        }); 
        return false;
    }else if(estado == ""){
        swal({
            title: "É necessário preencher o estado.",
            icon: "warning",
        }); 
        return false;
    }else{ 

    	jQuery(".btnSelect").html('<img src="https://media.tenor.com/images/a742721ea2075bc3956a2ff62c9bfeef/tenor.gif" style="height: 22px;position:absolute;"> Finalizando...');
	    jQuery(".btnSelect").attr("disabled", "disabled");
	    jQuery(".btnSelect").prop("disabled", true);

		var jsonReserva = '{ "data": { "attributes": {"name": "'+nameReserva[0]+'", "lastName": "'+nameReserva[1]+'", "email": "'+emailTitular+'", "phone": '+celularTitular+', "passengers": '+JSON.stringify(room)+', "paymentsTypes": "invoice_only_daily", "customerName": "'+nomeTitular+'", "costumerEmail": "'+emailTitular+'", "customerIdentity": "'+cpfTitular+'", "customerAddress": "'+endereco+'", "customerState": "'+estado+'", "customerCity": "'+cidade+'", "customerPostalCode": "'+cep+'", "cutomerPhone": '+celularTitular+', "customerStreetComplement": "'+complemento+'" } } }'; 
		localStorage.setItem("ORDER_ACCEPTED", jsonReserva);

		if(type_reserva == 2){

	    	var holder = jQuery("#holder-card").val();
			var number = jQuery("#number-card").val();
			var month = jQuery("#mm-card").val();
			var year = jQuery("#year-card").val();
			var cvc = jQuery("#cvc-card").val();

			if(holder == ""){
				swal({
		            title: "É necessário preencher o titular do cartão.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(number == ""){
				swal({
		            title: "É necessário preencher o número do cartão.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(month == ""){
				swal({
		            title: "É necessário preencher o mês.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(year == ""){
				swal({
		            title: "É necessário preencher o ano.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(year < 23){
				swal({
		            title: "É necessário preencher o ano da forma correta.",
		            icon: "warning",
		        }); 
		        return false;
			}else if(cvc == ""){
				swal({
		            title: "É necessário preencher o CVC.",
		            icon: "warning",
		        }); 
		        return false;
			}else{ 

				localStorage.setItem("HOLDER_EHTL", holder);
				localStorage.setItem("NUMBER_EHTL", number);
				localStorage.setItem("MONTH_EHTL", month);
				localStorage.setItem("YEAR_EHTL", year);
				localStorage.setItem("CVC_EHTL", cvc);

				var access_token = localStorage.getItem("ACCESS_TOKEN"); 

				var myHeaders = new Headers();
				myHeaders.append("Content-Type", "application/json");
				myHeaders.append("Authorization", "Bearer "+access_token);

			    var raw = JSON.stringify({
				  	"data": {
				    	"attributes": {
					      	"hotelCode": order[1],
					      	"roomCode": order[0] 
				    	}
				  	}
				});

				var requestOptions = {
				  	method: 'POST',
				  	headers: myHeaders,
				  	body: raw,
				  	redirect: 'follow'
				};

				fetch("https://quasar.e-htl.com.br/booking/detail", requestOptions)
				  	.then(response => response.text()) 
				  	.then(
				  		result => console.log(result)
				  	)
				  	.catch(error => console.log('error', error));

				var requestOptions = {
				  	method: 'POST',
				  	headers: myHeaders,
				  	body: raw,
				  	redirect: 'follow'
				};

				fetch("https://quasar.e-htl.com.br/booking/book-hotel", requestOptions)
				  	.then(response => response.text()) 
				  	.then(
				  		result => save_order_result(JSON.parse(result))
				  	)
				  	.catch(error => console.log('error', error));

			}
		}else{ 
			window.location.href = '/confirm-order/';
		}

    }
}

function save_order_result(data){
	localStorage.setItem("ORDER_RESULT_EHTL", JSON.stringify(data));

	if(data.id){
		window.location.href = '/confirm-order/?order='+data.id;
	}else{
		window.location.href = '/confirm-order/';
	}
}