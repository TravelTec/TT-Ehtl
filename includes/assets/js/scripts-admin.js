jQuery(document).ready(function(){
	jQuery("#profile #submit").attr("onclick", "save_data_ehtl()");
	jQuery("#contact #submit").attr("onclick", "save_data_licenca()");
});

function save_data_ehtl(){

	var user_ehtl      = jQuery("#user_ehtl").val();
	var pass_ehtl      = jQuery("#pass_ehtl").val();
	var cor_ehtl       = jQuery("#cor_ehtl").val();
	var cor_botao_ehtl = jQuery("#cor_botao_ehtl").val();
	var type_reserva   = jQuery("#type_reserva_ehtl").val(); 

	var licenca = jQuery("#chave_licenca_ehtl").val();

	if(jQuery("#chave_licenca_ehtl").val() == ""){

        swal({
            title: "É necessário informar uma licença para utilizar o plugin.",
            icon: "warning",
        }); 
        return false;

	}else{

		if(user_ehtl == ""){

	        swal({
	            title: "É necessário informar a credencial de Usuário.",
	            icon: "warning",
	        }); 
	        return false;

		}else if(pass_ehtl == ""){

	        swal({
	            title: "É necessário informar a Senha da credencial.",
	            icon: "warning",
	        }); 
	        return false;

		}else if(type_reserva == ""){

	        swal({
	            title: "É necessário informar um tipo de reserva.",
	            icon: "warning",
	        }); 
	        return false;

		}else{

			jQuery.ajax({
		        type: "POST",
		        url: wp_ajax.ajaxurl,
		        data: { action: "save_data_ehtl", user_ehtl:user_ehtl, pass_ehtl:pass_ehtl, cor_ehtl:cor_ehtl, cor_botao_ehtl:cor_botao_ehtl, type_reserva:type_reserva, licenca:licenca },
		        success: function( data ) { 
			        swal({
	                    title: "Dados salvos com sucesso!", 
	                    icon: "success"
	                }).then((value) => {
					  	window.location.reload();
					});
		        }
		    });

		}

	}
}

function set_type_reserva(type){
	jQuery("#type_reserva_ehtl").val(type);
}

function save_data_licenca(){

	var user_ehtl      = jQuery("#user_ehtl").val();
	var pass_ehtl      = jQuery("#pass_ehtl").val();
	var cor_ehtl       = jQuery("#cor_ehtl").val();
	var cor_botao_ehtl = jQuery("#cor_botao_ehtl").val();
	var type_reserva   = jQuery("#type_reserva_ehtl").val(); 

	var licenca = jQuery("#chave_licenca_ehtl").val();
	
	var keys = [
		"EC98562EDKSOWK7895SE",
		"2MLWUUCSVNIPECMZCLUQ",
		"1DFMXQOUSYPPCKZFEVJI",
		"0DNITTPKWEVBUDLMDWCE",
		"4AHWMPHHTPMTBLVSVQEL"
	]; 

	if(jQuery("#chave_licenca_ehtl").val() == "" || jQuery.inArray(licenca, keys) == -1){

        swal({
            title: "É necessário informar uma licença válida para utilizar o plugin.",
            icon: "warning",
        }); 
        return false;

	}else{
		jQuery("#contact #submit").val("Aguarde...");
		jQuery("#contact #submit").attr("disabled", "disabled");
		jQuery("#contact #submit").prop("disabled", true);

		setTimeout(function(){

	      	jQuery.ajax({
		        type: "POST",
		        url: wp_ajax.ajaxurl,
		        data: { action: "save_data_ehtl", user_ehtl:user_ehtl, pass_ehtl:pass_ehtl, cor_ehtl:cor_ehtl, cor_botao_ehtl:cor_botao_ehtl, type_reserva:type_reserva, licenca:licenca },
		        success: function( data ) { 
			        swal({
	                    title: "Cliente validado!",  
	                    icon: "success"
	                }).then((value) => {
					  	window.location.reload();
					});
		        }
		    });

	   	}, 2200); 

	}
}
