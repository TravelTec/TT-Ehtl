<?php
	
	add_action( 'wp_ajax_list_results_ehtl', 'list_results_ehtl' );
	add_action( 'wp_ajax_nopriv_list_results_ehtl', 'list_results_ehtl' );

	function list_results_ehtl(){

		$destino    = $_POST['destino'];
		$id_destino = $_POST['id_destino'];
		$checkin    = implode("-", array_reverse(explode("/", $_POST['checkin'])));
		$checkout   = implode("-", array_reverse(explode("/", $_POST['checkout'])));
		$adt        = $_POST['adt'];
		$chd        = $_POST['chd'];
		$qtd_quarto = $_POST['quarto'];   
		$quartos    = $_POST['quartos'];   

		$curl = curl_init();

	    curl_setopt_array($curl, array(
	      	CURLOPT_URL => "https://quasar.e-htl.com.br/oauth/access_token",
	      	CURLOPT_RETURNTRANSFER => TRUE,
	      	CURLOPT_ENCODING => "",
	      	CURLOPT_MAXREDIRS => 10,
	      	CURLOPT_TIMEOUT => 50000,
	      	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      	CURLOPT_CUSTOMREQUEST => "POST",
	      	CURLOPT_POSTFIELDS => "username=370188&password=agenciaws@0922361083299732023",
	      	CURLOPT_HTTPHEADER => array(
	        	"cache-control: no-cache", 
	        	"x-detailed-error: "
	      	),
	    ));

	    $response = curl_exec($curl);
	    $err = curl_error($curl);

	    curl_close($curl);

	    if ($err) {
	        echo "cURL Error #:" . $err;
	    } else { 
	        $itens = json_decode($response, true);
	        $token = $itens["access_token"]; 
	        $access = $itens["access_token"]; 

		    $data_inicio = new \DateTime($checkin);
		    $data_fim = new \DateTime($checkout);

		    // Resgata diferença entre as datas
		    $diferenca_data = $data_inicio->diff($data_fim);
		    $qtd_diarias = $diferenca_data->days; 

	        $curl = curl_init(); 
		    curl_setopt_array($curl, array( 
		        CURLOPT_URL => 'https://quasar.e-htl.com.br/booking/hotels-availabilities', 
		        CURLOPT_RETURNTRANSFER => true, 
		        CURLOPT_ENCODING => "", 
		        CURLOPT_MAXREDIRS => 10, 
		        CURLOPT_TIMEOUT => 50000, 
		        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
		        CURLOPT_CUSTOMREQUEST => "POST", 
		        CURLOPT_POSTFIELDS => '{"data": {"attributes": { "destinationId": "'.$id_destino.'", "checkin": "'.$checkin.'", "nights": '.$qtd_diarias.', "roomsAmount": '.$qtd_quarto.', "rooms": '.json_encode($quartos).', "signsInvoice": 0, "onlyAvailable": true, "perPage": 10}}}', 
		        CURLOPT_HTTPHEADER => array( 
		            "authorization: Bearer ".$token, 
		            "cache-control: no-cache", 
		            "content-type: application/json" 
		        ), 
		    ));

		    $response = curl_exec($curl); 
		    $err = curl_error($curl);

		    curl_close($curl);

		    if ($err) { 
		        echo "cURL Error #:" .
		        $err; 
		    } else { 
		    	echo $response;
		    }

	   	}

	}

?>