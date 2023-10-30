<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* 

	Plugin Name: Voucher Tec - Integração de hotéis E-htl

	Plugin URI: https://github.com/TravelTec/bookinghotels

	GitHub Plugin URI: https://github.com/TravelTec/bookinghotels 

	Description: Voucher Tec - Integração de hotéis E-htl é um plugin desenvolvido para agências e operadoras de turismo que precisam tratar diárias de hospedagem de fornecedores, com integração ao fornecedor E-htl.

	Version: 1.1.11

	Author: Travel Tec

	Author URI: https://traveltec.com.br

	License: GPLv2 

*/ 



require 'plugin-update-checker-4.10/plugin-update-checker.php';
require 'helpers/mail/Custom_Mailer.php';



add_action( 'admin_init', 'ehtl_update_checker_setting' );  



function ehtl_update_checker_setting() { 

	

	register_setting( 'vouchertec-ehtl', 'serial' ); 



    if ( ! is_admin() || ! class_exists( 'Puc_v4_Factory' ) ) {  

        return;  

    }  



    $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker( 

        'https://github.com/TravelTec/TT-Ehtl',  

        __FILE__,  

        'TT-Ehtl'  

    );  



    $myUpdateChecker->setBranch('main'); 



}



add_action( 'wp_enqueue_scripts', 'enqueue_form_ehtl' ); 

function enqueue_form_ehtl() {



    wp_enqueue_style( 

      'flatpickr-style-ehtl', 

      'https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css'

    );



    wp_enqueue_script( 

        'mask-script-ehtl',

        'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js',

        array( 'jquery' )

    );



    wp_enqueue_script( 

        'paginate-ehtl',

        'https://cdn.jsdelivr.net/npm/jquery-paginate@1.0.1/jquery-paginate.min.js',

        array( 'jquery' )

    );



    wp_enqueue_script( 

        'flatpickr-script-ehtl',

        'https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js',

        array( 'jquery' )

    );



    wp_enqueue_script( 'sweetalert-ehtl', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js');



    if($_SERVER['REQUEST_URI'] !== "/hotels-detail/" && $_SERVER['REQUEST_URI'] !== "/order-hotels/" && $_SERVER['REQUEST_URI'] !== "/confirm-order/"){



	    wp_enqueue_script( 

	        'scripts-shortcode-ehtl',

	        plugin_dir_url( __FILE__ ) . 'includes/assets/js/scripts-shortcode.js?v='.date("dmYHis"),

	        array( 'jquery' ),

	        false,

	        true

	    );



	    wp_localize_script( 

	        'scripts-shortcode-ehtl',

	        'wp_ajax_ehtl_shortcode',

	        array( 

	            'ajaxurl' => admin_url( 'admin-ajax.php' ),

	            'dede' => 1234

	        )                 

	    );



	}



    if($_SERVER['REQUEST_URI'] == '/hotels/'){



	    wp_enqueue_script( 

	        'scripts-results-ehtl',

	        plugin_dir_url( __FILE__ ) . 'includes/assets/js/scripts-results.js?v='.date("dmYHis"),

	        array( 'jquery' ),

	        false,

	        true

	    );



	    wp_localize_script( 

	        'scripts-results-ehtl',

	        'wp_ajax_ehtl_results',

	        array( 

	            'ajaxurl' => admin_url( 'admin-ajax.php' ),

	            'dede' => 1234

	        )                 

	    ); 



	}



    if($_SERVER['REQUEST_URI'] == '/hotels-detail/'){



		wp_enqueue_style( 

		  'carousel-style-ehtl', 

		  'https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css'

		);



		wp_enqueue_style( 

		  'carousel-principal-style-ehtl', 

		  'https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css'

		);



		wp_enqueue_script( 

			'carousel-script-ehtl',

			'https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/owl.carousel.js',

			array( 'jquery' )

		);



	    wp_enqueue_script( 

	        'scripts-details-ehtl',

	        plugin_dir_url( __FILE__ ) . 'includes/assets/js/scripts-details.js?v='.date("dmYHis"),

	        array( 'jquery' ),

	        false,

	        true

	    );



	    wp_localize_script( 

	        'scripts-details-ehtl',

	        'wp_ajax_ehtl_details',

	        array( 

	            'ajaxurl' => admin_url( 'admin-ajax.php' ),

	            'dede' => 1234

	        )                 

	    ); 



	}



    if($_SERVER['REQUEST_URI'] == '/order-hotels/'){



	    wp_enqueue_script( 

	        'scripts-checkout-ehtl',

	        plugin_dir_url( __FILE__ ) . 'includes/assets/js/scripts-checkout.js?v='.date("dmYHis"),

	        array( 'jquery' ),

	        false,

	        true

	    );



	    wp_localize_script( 

	        'scripts-checkout-ehtl',

	        'wp_ajax_ehtl_checkout',

	        array( 

	            'ajaxurl' => admin_url( 'admin-ajax.php' ),

	            'dede' => 1234

	        )                 

	    ); 



	} 


    if($_SERVER['REQUEST_URI'] == '/confirm-order/'){



	    wp_enqueue_script( 

	        'scripts-confirmation-ehtl',

	        plugin_dir_url( __FILE__ ) . 'includes/assets/js/scripts-confirmation.js?v='.date("dmYHis"),

	        array( 'jquery' ),

	        false,

	        true

	    );



	    wp_localize_script( 

	        'scripts-confirmation-ehtl',

	        'wp_ajax_ehtl_confirmation',

	        array( 

	            'ajaxurl' => admin_url( 'admin-ajax.php' ),

	            'dede' => 1234

	        )                 

	    ); 



	} 

} 



add_action( 'admin_enqueue_scripts', 'enqueue_scripts_admin_ehtl' ); 

function enqueue_scripts_admin_ehtl() {



    wp_enqueue_script( 'sweetalert-ehtl', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js');



    wp_enqueue_script( 

        'scripts-admin-ehtl',

        plugin_dir_url( __FILE__ ) . 'includes/assets/js/scripts-admin.js?v='.date("dmYHis"),

        array( 'jquery' ),

        false,

        true

    );



    wp_localize_script( 

        'scripts-admin-ehtl',

        'wp_ajax',

        array( 

            'ajaxurl' => admin_url( 'admin-ajax.php' ),

            'dede' => 1234

        )                 

    );



} 



add_action( 'wp_ajax_get_destinos_ehtl', 'get_destinos_ehtl' );

add_action( 'wp_ajax_nopriv_get_destinos_ehtl', 'get_destinos_ehtl' );



function get_destinos_ehtl() { 

    $curl = curl_init();



    curl_setopt_array($curl, array(

      CURLOPT_URL => "https://quasar.e-htl.com.br/oauth/access_token",

      CURLOPT_RETURNTRANSFER => TRUE,

      CURLOPT_ENCODING => "",

      CURLOPT_MAXREDIRS => 10,

      CURLOPT_TIMEOUT => 30,

      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

      CURLOPT_CUSTOMREQUEST => "POST",

      CURLOPT_POSTFIELDS => "username=".get_option( 'user_ehtl' )."&password=".get_option( 'pass_ehtl' ),

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

    }  



    function tirarAcentosHoteisEhtl($string){

		return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);

		} 

 

  	$local = tirarAcentosHoteisEhtl(str_replace(" ", "%20", $_POST['local']));



    $curl = curl_init(); 



	curl_setopt_array($curl,

		array(

			CURLOPT_URL => "https://quasar.e-htl.com.br/destinations/search?query=".$local."&limit=8",

			CURLOPT_RETURNTRANSFER => true,

			CURLOPT_ENCODING => "",

			CURLOPT_MAXREDIRS => 10,

			CURLOPT_TIMEOUT => 30,

			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

			CURLOPT_CUSTOMREQUEST => "GET", 

			CURLOPT_HTTPHEADER => array(

				"authorization: Bearer $token",

				"cache-control: no-cache",

				"content-type: application/json"

			),

		)

	);



	$response = curl_exec($curl);



	$err = curl_error($curl);



	curl_close($curl);



	if ($err) {



	echo "cURL Error #:" .

	$err;



	} else {



	    $itens = json_decode($response, true);

	    $resultados = $itens["data"];



	    for ($i=0; $i < count($resultados); $i++) { 



	        if($resultados[$i]["attributes"]["destinationType"] == "city"){

	            $valores[] = array("token" => $token, "destino"=>$resultados[$i]['id'],"sigla"=>$resultados[$i]["attributes"]['namePt'],"end"=>utf8_encode($estado) );

	        }



	    }

	    

	    echo json_encode($valores); 

	} 

}



add_shortcode('TTBOOKING_MOTOR_RESERVA', 'shortcode_motor_reserva');  



function shortcode_motor_reserva(){



	$retorno = ''; 



	$retorno .= '<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">';

	$retorno .= '<link rel="preconnect" href="https://fonts.googleapis.com">

				<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

				<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

		<link rel="stylesheet" href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css">

		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" />

		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?ver=1.0"> '; 



	$retorno .= '<style>

		.search-sec{padding:2.5rem 2rem}

		.search-slt{display:block;width:100%;font-size:.875rem;line-height:1.5;color:#55595c;background-color:#fff;background-image:none;border:1px solid #ccc;height:calc(3rem + 2px)!important;border-radius:0}

		.wrn-btn{width:100%;font-size:16px;font-weight:400;text-transform:capitalize;height:calc(3rem + 2px)!important;border-radius:0 4px 4px 0;}

		.wrn-btn:focus{outline:none;box-shadow:none;border:none;}

		@media (min-width:992px){.search-sec{bottom:0px;width:100%;background:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';z-index:9;border-radius: 15px;box-shadow: 4px 4px 8px #dadada;font-family: \'Montserrat\';} .daterangepicker{width:49.5% !important} .daterangepicker .drp-calendar.left{margin-right: 49px;}

		}

			.form-control:disabled, .form-control[readonly]{

				background-color: #fff !important;

			}

		@media (max-width:992px){

			.banner{

				margin: 5px !important;

			}

			.ripple{

				    margin-top: 15px;

			}

			.panel-dropdown .panel-dropdown-content{

				width: 245px !important;

			}

			.qtyButtons label{

				font-size: 16px !important;

			}

			.daterangepicker{

				width: 330px !important;

			}

			.search-sec{bottom:0px;width:100%;background:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'c2;z-index:9;border-radius: 15px;box-shadow: 4px 4px 8px #dadada;font-family: \'Montserrat\';}.custom-search-input-2 .form-group {margin-bottom: 15px !important;}

		.owl-carousel.main_banner{position:relative !important;}

		.custom_header{position:relative !important;top:0;z-index:99;width:100%;background: rgba(26,70,104,.51) !important;border-radius:0;}

		}

		.custom-search-input-2{background-color:#fff;-webkit-border-radius:5px;-moz-border-radius:5px;-ms-border-radius:5px;border-radius:5px;margin-top:15px;box-shadow: 0 0 0 6px rgba(255,255,255,.25);}

		@media (max-width: 991px){.custom-search-input-2{background:none;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none}

		}

		.custom-search-input-2 input{font-family: \'Montserrat\' !important;font-size: .875rem !important;border:0;height:50px;padding-left:15px;border-right:1px solid #d2d8dd;font-weight:500}

		@media (max-width: 991px){.custom-search-input-2 input{border:none}

		}

		.custom-search-input-2 input:focus{box-shadow:none;border-right:1px solid #d2d8dd}

		@media (max-width: 991px){.custom-search-input-2 input:focus{border-right:none}

		}

		.custom-search-input-2 select{font-size: 13px;padding: 5px;height: 30px !important;}

		.custom-search-input-2 .nice-select .current{font-weight:500;color:#6f787f}

		.custom-search-input-2 .form-group{margin:0}

		@media (max-width: 991px){.custom-search-input-2 .form-group{margin-bottom:5px}

		}

		.custom-search-input-2 i{-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;font-size:18px;font-size:1.125rem;position:absolute;background-color:#fff;line-height:50px;top:0;right:1px;padding-right:15px;display:block;width:20px;box-sizing:content-box;height:50px;z-index:9;color:#999}

		@media (max-width: 991px){.custom-search-input-2 i{padding-right:10px}

		}

		.custom-search-input-2 input[type=\'submit\']{-moz-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;transition:all 0.3s ease-in-out;color:#fff;font-weight:600;font-size:14px;font-size:0.875rem;border:0;padding:0 25px;height:50px;cursor:pointer;outline:none;width:100%;-webkit-border-radius:0 3px 3px 0;-moz-border-radius:0 3px 3px 0;-ms-border-radius:0 3px 3px 0;border-radius:0 3px 3px 0;background-color:#fc5b62;margin-right:-1px}

		@media (max-width: 991px){.custom-search-input-2 input[type=\'submit\']{margin:20px 0 0 0;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px}

		}

		.custom-search-input-2 input[type=\'submit\']:hover{background-color:#FFC107;color:#222}

		.custom-search-input-2.inner{margin-bottom:30px;-webkit-box-shadow:0px 0px 30px 0px rgba(0,0,0,0.1);-moz-box-shadow:0px 0px 30px 0px rgba(0,0,0,0.1);box-shadow:0px 0px 30px 0px rgba(0,0,0,0.1)}

		@media (max-width: 991px){.custom-search-input-2.inner{margin:0 0 20px 0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none}

		}

		.custom-search-input-2.inner-2{margin:0 0 20px 0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;background:none}

		.custom-search-input-2.inner-2 .form-group{margin-bottom:10px}

		.custom-search-input-2.inner-2 input{border:1px solid #ededed}

		.custom-search-input-2.inner-2 input[type=\'submit\']{-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;margin-top:10px}

		.custom-search-input-2.inner-2 i{padding-right:10px;line-height:48px;height:48px;top:1px}

		.custom-search-input-2.inner-2 .nice-select{border:1px solid #ededed}

		.panel-dropdown{position:relative;text-align:left;padding:15px 10px 0 15px}

		@media (max-width: 991px){.panel-dropdown{background-color:#fff;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;height:50px}

		}

		.panel-dropdown a{color:#727b82;font-weight:500;transition:all 0.3s;display:flex;align-items:center;justify-content:flex-start;position:relative}

		.panel-dropdown a:after{content:"\25BE";font-size:1.7rem;color:#999;font-weight:500;-moz-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;transition:all 0.3s ease-in-out;position:absolute;right:0;top:-8px;}

		.panel-dropdown.active a:after{transform:rotate(180deg);}

		.panel-dropdown .panel-dropdown-content{opacity:0;visibility:hidden;transition:all 0.3s;position:absolute;top:58px;left:0px;z-index:99;background:#fff;border-radius:4px;white-space:normal;width:310px;box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;border:none;}

		.panel-dropdown .panel-dropdown-content:after{bottom:100%;left:15px;border:solid transparent;content:" ";height:0;width:0;position:absolute;pointer-events:none;border-bottom-color:#fff;border-width:7px;margin-left:-7px}

		.panel-dropdown .panel-dropdown-content.right{left:auto;right:0}

		.panel-dropdown .panel-dropdown-content.right:after{left:auto;right:15px}

		.panel-dropdown.active .panel-dropdown-content{opacity:1;visibility:visible}

		.qtyButtons{display:flex;margin:0 0 13px 0}

		.qtyButtons input{outline:0;font-size:16px;font-size:1rem;text-align:center;width:50px;height:36px !important;color:#333;line-height:36px;margin:0 !important;padding:0 5px !important;border:none;box-shadow:none;pointer-events:none;display:inline-block;border:none !important}

		.qtyButtons label{font-weight:400;line-height:36px;padding-right:15px;display:block;flex:1;color:#626262;    font-size: 18px;}

		.qtyInc,.qtyDec{width:28px;height:28px;line-height:22px;font-size:15px;background-color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'bd;-webkit-text-stroke:1px #fff;color:#333;display:inline-block;text-align:center;border-radius:50%;cursor:pointer;}

		.qtyInc:hover,.qtyDec:hover{background:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'bd;}

		.qtyInc:hover:before, .qtyDec:hover:before{color:#fff}

		.qtyInc:before{content:"\002B";font-size:19px;font-weight:900;line-height: 29px;}

		.qtyDec:before{content:"\2212";font-size:19px;font-weight:900;line-height: 29px;}

		.qtyTotal, .qtyRoom{border-radius:50%;color:#66676b;display:inline-block;font-size:14px;font-weight:600;font-family:\'Montserrat\', sans-serif;line-height:18px;text-align:center;position:relative;top:1px;left:7px;height:18px;width:18px;margin-right:15px}

		.rotate-x{animation-duration:.5s;animation-name:rotate-x}

		@keyframes rotate-x{from{transform:rotateY(0deg)}

		to{transform:rotateY(360deg)}

		}

		.daterangepicker{box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important;border:none;}

		.daterangepicker td.in-range{background-color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'54;cor:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'54;}

		.daterangepicker td.active, .daterangepicker td.active:hover {background-color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';border-color:transparent;color:#fff;}

		.daterangepicker td.available:hover, .daterangepicker th.available:hover{background-color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'99;color:#fff;border-radius:40px}

		.btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle{background-color:#c82333;border-color:#c82333;}

		.ripple{font-family:\'Montserrat\';position:relative;overflow:hidden;transform:translate3d(0,0,0)}

		.ripple:after{content:"";display:block;position:absolute;width:100%;height:100%;top:0;left:0;pointer-events:none;background-image:radial-gradient(circle,#000 10%,transparent 10.01%);background-repeat:no-repeat;background-position:50%;transform:scale(10,10);opacity:0;transition:transform .5s,opacity 1s}

		.ripple:active:after{transform:scale(0,0);opacity:.2;transition:0s}

		.btn-primary{color:#fff;background-color:#DC3545;border-color:#DC3545;}

		.btn-primary:hover{background-color:#c82333;border-color:#bd2130;}

		.btn-primary:focus{background-color:#c82333;border-color:#bd2130;box-shadow:0 0 0 0.2rem rgba(200, 35, 51, 0.5)!important;}

		.nice-select.wide{width:100%}

		.nice-select.wide .list{left:0 !important;right:0 !important}

		.custom-select-form .nice-select{-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;border:1px solid #d2d8dd;height:45px;line-height:42px}

		.custom-select-form .nice-select:hover{border-color:#d2d8dd}

		.custom-select-form .nice-select:active,.custom-select-form .nice-select.open,.custom-select-form .nice-select:focus{border-color:#80bdff;outline:0;box-shadow:0 0 0 0.2rem rgba(0,123,255,0.25)}

		.custom-select-form select{display:none} 

		.custom-select-form .nice-select{border:none;height:50px;line-height:50px;border-radius:4px 0 0 4px;border-right:1px solid #d2d8dd !important;}

		.nice-select .list{box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important;width:100%;}

		.nice-select.open .list{height:250px;overflow-y:auto;}

		.custom-select-form .nice-select:active, .custom-select-form .nice-select.open, .custom-select-form .nice-select:focus {border-color:#fff;outline:0;box-shadow:none;}

		.wrn-btn span{cursor:pointer;display:inline-block;position:relative;transition:.5s}

		.wrn-btn span:after{content:\'\00bb\';position:absolute;opacity:0;top:-8px;right:-20px;transition:.5s;font-size:24px;}

		.wrn-btn:hover span{padding-right:20px}

		.wrn-btn:hover span:after{opacity:1;right:0}

		.wrapper-grid{padding:0 20px}

		.box_grid{background-color:#fff;display:block;position:relative;margin-bottom:30px;-webkit-box-shadow:0px 0px 20px 0px rgba(0,0,0,0.1);-moz-box-shadow:0px 0px 20px 0px rgba(0,0,0,0.1);box-shadow:0px 0px 20px 0px rgba(0,0,0,0.1)}

		.box_grid .price{display:inline-block;font-weight:500;color:#999}

		.box_grid .price strong{color:#32a067}

		.box_grid a.wish_bt{position:absolute;right:15px;top:15px;z-index:1;background-color:#000;background-color:rgba(0,0,0,0.6);padding:7px 10px 7px 10px;display:inline-block;color:#fff;line-height:1;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px}

		.box_grid a.wish_bt:after{content:"\2661";-moz-transition:all 0.5s ease;-o-transition:all 0.5s ease;-webkit-transition:all 0.5s ease;-ms-transition:all 0.5s ease;transition:all 0.5s ease;font-size:20px;}

		.box_grid a.wish_bt.liked:after{content:"\e089";color:#fc5b62}

		.box_grid a.wish_bt:hover.liked:after{color:#fc5b62}

		.box_grid a.wish_bt:hover:after{content:"\e089";color:#fff}

		.box_grid figure{margin-bottom:0;overflow:hidden;position:relative;height:210px}

		.box_grid figure small{position:absolute;background-color:#000;background-color:rgba(0,0,0,0.6);left:20px;top:22px;text-transform:uppercase;color:#ccc;font-weight:600;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;padding:5px 10px 5px 10px;line-height:1}

		.box_grid figure .read_more{position:absolute;top:50%;left:0;margin-top:-12px;-webkit-transform:translateY(10px);-moz-transform:translateY(10px);-ms-transform:translateY(10px);-o-transform:translateY(10px);transform:translateY(10px);text-align:center;opacity:0;visibility:hidden;width:100%;-webkit-transition:all 0.6s;transition:all 0.6s;z-index:2}

		.box_grid figure .read_more span{background-color:#fcfcfc;background-color:rgba(255,255,255,0.8);-webkit-border-radius:20px;-moz-border-radius:20px;-ms-border-radius:20px;border-radius:20px;display:inline-block;color:#222;font-size:12px;font-size:0.75rem;padding:5px 10px}

		.box_grid figure:hover .read_more{opacity:1;visibility:visible;-webkit-transform:translateY(0);-moz-transform:translateY(0);-ms-transform:translateY(0);-o-transform:translateY(0);transform:translateY(0)}

		.box_grid figure a img{position:absolute;left:50%;top:50%;-webkit-transform:translate(-50%, -50%) scale(1.1);-moz-transform:translate(-50%, -50%) scale(1.1);-ms-transform:translate(-50%, -50%) scale(1.1);-o-transform:translate(-50%, -50%) scale(1.1);transform:translate(-50%, -50%) scale(1.1);-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;-ms-backface-visibility:hidden;-o-backface-visibility:hidden;backface-visibility:hidden;width:100%;-moz-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;transition:all 0.3s ease-in-out}

		.box_grid figure a:hover img{-webkit-transform:translate(-50%, -50%) scale(1);-moz-transform:translate(-50%, -50%) scale(1);-ms-transform:translate(-50%, -50%) scale(1);-o-transform:translate(-50%, -50%) scale(1);transform:translate(-50%, -50%) scale(1)}

		.box_grid .wrapper{padding:25px}

		.box_grid .wrapper h3{font-size:20px;font-size:1.25rem;margin-top:0}

		.box_grid ul{padding:20px 15px;border-top:1px solid #ededed}

		.box_grid ul li{display:inline-block;margin-right:15px}

		.box_grid ul li .score{margin-top:-10px}

		.box_grid ul li:last-child{margin-right:0;float:right}

		.score strong{background-color:#0054a6;color:#fff;line-height:1;-webkit-border-radius:5px 5px 5px 0;-moz-border-radius:5px 5px 5px 0;-ms-border-radius:5px 5px 5px 0;border-radius:5px 5px 5px 0;padding:10px;display:inline-block}

		.score span{display:inline-block;position:relative;top:7px;margin-right:8px;font-size:12px;font-size:0.75rem;text-align:right;line-height:1.1;font-weight:500}

		.score span em{display:block;font-weight:normal;font-size:11px;font-size:0.6875rem}

		.main_title_2 h2{margin:25px 0 0 0;color:#333;}

		.main_title_2 h3{margin:25px 0 0 0;color:#727272;}

		.main_title_2 p{margin:8px 0 0 0;color:#727272;}

		p{color:#727272;font-size:15px;line-height:20px;}

		a{color:#DC3545;}

		a:hover{text-decoration:none;color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'}

		.owl-carousel .owl-nav button.owl-next,.owl-carousel .owl-nav button.owl-prev,.owl-carousel button.owl-dot{background:rgba(0, 84, 166, 0.85)!important;color:inherit;border:none;padding:5px 14px!important;position:absolute;top:50%;color:#fff!impotant;border-radius:3px!impotant}

		.owl-carousel .owl-nav .owl-prev{left:0;}

		.owl-carousel .owl-nav .owl-prev span{font-size:20px;line-height:22px;}

		.owl-carousel .owl-nav .owl-prev:focus{outline:none;border:none;box-shadow:none}

		.owl-carousel .owl-nav .owl-next{right:0}

		.owl-carousel .owl-nav .owl-next span{font-size:20px;line-height:22px;}

		.owl-carousel .owl-nav .owl-next:focus{outline:none;border:none;box-shadow:none}

		#places{margin-top:40px}

		@media (max-width: 767px){#places{margin-top:0}

		}

		#places .item{margin:0 15px}

		#places .owl-item{opacity:0.5;transform:scale(0.85);-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;-ms-backface-visibility:hidden;-o-backface-visibility:hidden;backface-visibility:hidden;-webkit-transform:translateZ(0) scale(0.85, 0.85);transition:all 0.3s ease-in-out 0s;overflow:hidden}

		#places .owl-item.active.center{opacity:1;-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;-ms-backface-visibility:hidden;-o-backface-visibility:hidden;backface-visibility:hidden;-webkit-transform:translateZ(0) scale(1, 1);transform:scale(1)}

		#places .owl-item.active.center .item .title h4,#places .owl-item.active.center .item .views{opacity:1}

		.owl-theme .owl-dots{margin-top:10px !important;margin-bottom:25px}

		.search-sec .tag_line h3{font-size: 2.625rem;text-shadow: 4px 4px 12px rgba(0,0,0,0.3);color:#fff;margin:0;text-transform:uppercase;font-weight:700;}

		.search-sec .tag_line p{font-size: 21px;text-shadow: 4px 4px 12px rgba(0,0,0,0.3);color:#fff;margin:5px 0 0 0;font-weight:400;}

		.custom_header{position:absolute;top:0;z-index:99;width:100%;background: rgba(26,70,104,.51) !important;border-radius:0;}

		.navbar .navbar-brand{color:#fff!important;font-size:30px;}

		.navbar .navbar-nav li a{color:#fff!important;}

		.navbar .navbar-nav li.active a{color:#DC3545!important;}

		#side-menu{display:none;position:fixed;width:320px;top:0;right:-300px;height:100%;overflow-y:auto;z-index:99999;background:#fff;padding:20px 15px;color:#333;transition:.4s;box-shadow:-5px 0 20px rgba(0, 0, 0, 0.2);}

		body.side-menu-visible #side-menu{transform:translateX(-300px);overflow:hidden;}

		#side-menu .logo{max-width:65%;}

		#side-menu .contents{margin-top:00px;border-top:1px solid #eee;padding-top:20px;}

		#side-menu li.nav-item:before{content:\'\203A\';position:absolute;left:2px;top:7px;}

		#side-menu li.nav-item{padding-left:20px;}

		#side-menu .nav-link{color:#333;font-size:14px;font-weight:600;padding:10px 0}

		#side-menu .nav-link:hover{opacity:.8;color:#1b820a;}

		#side-menu li.nav-item.dropdown.show{border-bottom:1px solid #eee;padding-bottom:10px;margin-bottom:10px;}

		#side-menu .close{font-size:36px;font-weight:400;position:absolute;top:5px;right:15px;}

		#side-menu .contact a, #side-menu .contact .fa{padding:5px 0px;background:#fff;font-size:14px;color:#727272;}

		#side-menu .contact a:hover, #side-menu .contact .fa:hover{color: #28ab13 !important;}

		#side-menu .contact a:focus, #side-menu .contact .fa:focus{color: #28ab13 !important;}

		.dados{position:absolute;}

		.dados ul li{margin-top: 0 !important;}

		table td, table th{padding:9px;font-family: \'Montserrat\';font-weight: 600;}

		table caption+thead tr:first-child td, table caption+thead tr:first-child th, table colgroup+thead tr:first-child td, table colgroup+thead tr:first-child th, table thead:first-child tr:first-child td, table thead:first-child tr:first-child th{

			border-top:none !important;

			font-size:17px !important;

		    text-transform: capitalize;

		}

		.daterangepicker .calendar-table th, .daterangepicker .calendar-table td{text-transform:uppercase}

		.daterangepicker td.start-date{border-radius:40px 0px 0px 40px}

		.daterangepicker td.end-date{border-radius:0px 40px 40px 0px}

		.daterangepicker.show-calendar .drp-buttons{font-family: \'Montserrat\'}

		.daterangepicker td.start-date.end-date{border-radius:40px}

		.cancelBtn{color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'bd !important}

		.cancelBtn:hover{background-color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'bd !important;color:#fff !important}

		.applyBtn{background-color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).' !important;border-color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).' !important}

		.applyBtn:hover{background-color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'bd !important}

		.daterangepicker .drp-selected{display:none !important;}

		.btnAddRoom{font-size: 13px;font-weight: 700;color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';padding:6px 0;background-color:#fff;font-family: \'Montserrat\'}

		.btnAddRoom:hover{color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'ee;background-color:#fff;}

		.btnApplyRoom{background-color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';color: #fff;font-size: 14px;font-weight: 600;border-radius: 40px;padding: 5px 30px;float: right;}

		.btnApplyRoom:hover{background-color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'d4;}

		.ripple{background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).' !important;border:transparent !important}

		.ripple:hover{background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'80 !important}

		.dados:after{

			bottom: 100%;

		    left: 15px;

		    border: solid transparent;

		    content: " ";

		    height: 0;

		    width: 0;

		    position: absolute;

		    pointer-events: none;

		    border-bottom-color: #fff;

		    border-width: 7px;

		    margin-left: -7px;

		}

		.dados ul li:hover{

			background-color: #f1f1f1;

		}

		.banner{

		margin: 10px 50px;

		} 

		</style>';



	$retorno .= '

	<section class="banner">  

		<div class="search-sec container">



			<input type="hidden" id="field_date_checkin_ehtl" value="">

			<input type="hidden" id="field_date_checkout_ehtl" value=""> 

			<input type="hidden" id="adultos" value="2"> 

			<input type="hidden" id="criancas" value=""> 

			<input type="hidden" id="quartos" value="1"> 

			<div class="row">

				<form class="col-lg-12 col-12">

					<h4 style="color: #fff;font-weight: 600;font-size: 19px;margin-bottom: 22px;">Hospedagens</h4>

					<div class="row no-gutters custom-search-input-2"> 

						<div class="col-lg-4">

							<div class="form-group">

								<div class="custom-select-form">

									<input type="text" class="form-control" id="field_name_ehtl" autocomplete="off" placeholder="Informe a cidade ou hotel..." onfocus="this.value=\'\'">

									<div class="dados">

										<ul style="padding:0;margin: 0;"></ul>

									</div>

								</div>

								<i class="fa fa-map-marker"></i>

							</div>

						</div>

						<div class="col-lg-4">

							<div class="form-group">

								<input class="form-control search-slt" type="text" name="dates" placeholder="Selecione as datas..." autocomplete="off" readonly="readonly">

								<i class="fa fa-calendar"></i>

							</div>

						</div>

						<div class="col-lg-2">

							<div class="panel-dropdown">

								<a href="#"><i class="fa fa-bed" style="position: unset;padding: 0;line-height: 1;height: auto;"></i> <span class="qtyRoom">1</span> | <i class="fa fa-user" style="position: unset;padding: 0;line-height: 1;height: auto;margin-left: 8px;"></i> <span class="qtyTotal">2</span></a>

								<div class="panel-dropdown-content">

									<input type="hidden" id="qtd_room_add" value="1">

									<div class="rooms_add">

										<div id="panel1" class="panel1" style="padding:15px 15px 0 15px;">

											<input type="hidden" id="panel1qts" value="1">

											<h6>Quarto 1</h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel1adt" value="2">

												<label>Adultos</label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="2">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel1chd" value="0">

												<label style="line-height:1">

													Menor <br> 

													<small style="font-weight: 500;font-size: 12px;">Até 17 anos</small>

												</label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0" max="4">

												<div class="qtyInc"></div>

											</div> 

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

										<div id="panel2" class="panel2" style="display:none;padding:15px 15px 0 15px;">

											<input type="hidden" id="panel2qts" value="0">

											<h6>Quarto 2 <span class="btn btnAddRoom btnRemoverQuarto2" onclick="remove_room(2)" style="display:none;">Remover</span></h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel2adt" value="0">

												<label>Adultos</label>

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel2chd" value="0">

												<label style="line-height:1">Menor <br> <small style="font-weight: 500;font-size: 12px;">Até 17 anos</small></label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

										<div id="panel3" class="panel3" style="display:none;padding:15px 15px 0 15px;">

											<input type="hidden" id="panel3qts" value="0">

											<h6>Quarto 3 <span class="btn btnAddRoom btnRemoverQuarto3" onclick="remove_room(3)" style="display:none;">Remover</span></h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel3adt" value="0">

												<label>Adultos</label>

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel3chd" value="0">

												<label style="line-height:1">Menor <br> <small style="font-weight: 500;font-size: 12px;">Até 17 anos</small></label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

										<div id="panel4" class="panel4" style="display:none;padding:15px 15px 0 15px;">

											<input type="hidden" id="panel4qts" value="0">

											<h6>Quarto 4 <span class="btn btnAddRoom btnRemoverQuarto4" onclick="remove_room(4)" style="display:none;">Remover</span></h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel4adt" value="0">

												<label>Adultos</label>

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel4chd" value="0">

												<label style="line-height:1">Menor <br> <small style="font-weight: 500;font-size: 12px;">Até 17 anos</small></label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

										<div id="panel5" class="panel5" style="display:none;padding:15px 15px 0 15px;">

											<input type="hidden" id="panel5qts" value="0">

											<h6>Quarto 5 <span class="btn btnAddRoom btnRemoverQuarto5" onclick="remove_room(5)" style="display:none;">Remover</span></h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel5adt" value="0">

												<label>Adultos</label>

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel5chd" value="0">

												<label style="line-height:1">Menor <br> <small style="font-weight: 500;font-size: 12px;">Até 17 anos</small></label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

										<div id="panel6" class="panel6" style="display:none;padding:15px 15px 0 15px;">

											<input type="hidden" id="panel6qts" value="0">

											<h6>Quarto 6 <span class="btn btnAddRoom btnRemoverQuarto6" onclick="remove_room(6)" style="display:none;">Remover</span></h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel6adt" value="0">

												<label>Adultos</label>

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel6chd" value="0">

												<label style="line-height:1">Menor <br> <small style="font-weight: 500;font-size: 12px;">Até 17 anos</small></label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

									</div>

									<div class="apply" style="border-top: 1px solid #ccc;padding:15px;">

										<div class="row ">

											<div class="col-lg-12 col-12" style="text-align:right;">

												<span class="btn btnAddRoom spanButtonAddRoom" onclick="add_room()">Adicionar quarto</span>

											</div> 

										</div>

									</div>

								</div>

							</div>

						</div>

						<div class="col-lg-2">

							<button type="submit" class="btn_search btn btn-danger wrn-btn ripple" onclick="search_results()"><span>Buscar </span></button>

						</div>

					</div>

				</form>

			</div>

		</div>

	</section> ';



	$retorno .= '<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>';



	return $retorno;



}



add_shortcode('TTBOOKING_MOTOR_RESERVA_LATERAL', 'shortcode_motor_reserva_lateral');  



function shortcode_motor_reserva_lateral(){



	$retorno = '';



	$retorno .= '<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">';

	$retorno .= '<link rel="preconnect" href="https://fonts.googleapis.com">

				<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

				<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

		<link rel="stylesheet" href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css">

		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" />

		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?ver=1.0"> '; 



	$retorno .= '<style>.search-sec{padding:2.5rem 2rem}

.search-slt{display:block;width:100%;font-size:.875rem;line-height:1.5;color:#55595c;background-color:#fff;background-image:none;border:1px solid #ccc;height:calc(3rem + 2px)!important;border-radius:0}

.wrn-btn{width:100%;font-size:16px;font-weight:400;text-transform:capitalize;height:calc(3rem + 2px)!important;border-radius:0 4px 4px 0;}

.wrn-btn:focus{outline:none;box-shadow:none;border:none;}

@media (min-width:992px){.search-sec{bottom:0px;width:100%;background:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).';z-index:9;border-radius: 15px;box-shadow: 4px 4px 8px #dadada;font-family: \'Montserrat\';} .daterangepicker{width:49.5% !important} .daterangepicker .drp-calendar.left{margin-right: 49px;}

}

	.form-control:disabled, .form-control[readonly]{

		background-color: #fff !important;

	}

@media (max-width:992px){

	.banner{

		margin: 5px !important;

	}

	.ripple{

		    margin-top: 15px;

	}

	.panel-dropdown .panel-dropdown-content{

		width: 245px !important;

	}

	.qtyButtons label{

		font-size: 16px !important;

	}

	.daterangepicker{

		width: 330px !important;

	}

	.search-sec{bottom:0px;width:100%;background:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'c2;z-index:9;border-radius: 15px;box-shadow: 4px 4px 8px #dadada;font-family: \'Montserrat\';}.custom-search-input-2 .form-group {margin-bottom: 15px !important;}

.owl-carousel.main_banner{position:relative !important;}

.custom_header{position:relative !important;top:0;z-index:99;width:100%;background: rgba(26,70,104,.51) !important;border-radius:0;}

}

.custom-search-input-2{background-color:#fff;-webkit-border-radius:5px;-moz-border-radius:5px;-ms-border-radius:5px;border-radius:5px;margin-top:15px;box-shadow: 0 0 0 6px rgba(255,255,255,.25);}

@media (max-width: 991px){.custom-search-input-2{background:none;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none}

}

.custom-search-input-2 input{font-size: .875rem !important;border:0;height:50px;padding-left:15px;border-right:1px solid #d2d8dd;font-weight:500}

@media (max-width: 991px){.custom-search-input-2 input{border:none}

}

.custom-search-input-2 input:focus{box-shadow:none;border-right:1px solid #d2d8dd}

@media (max-width: 991px){.custom-search-input-2 input:focus{border-right:none}

}

.custom-search-input-2 select{font-size: 13px;padding: 5px;height: 30px !important;}

.custom-search-input-2 .nice-select .current{font-weight:500;color:#6f787f}

.custom-search-input-2 .form-group{margin:0}

@media (max-width: 991px){.custom-search-input-2 .form-group{margin-bottom:5px}

}

.custom-search-input-2 i{-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;font-size:18px;font-size:1.125rem;position:absolute;background-color:#fff;line-height:50px;top:0;right:1px;padding-right:15px;display:block;width:20px;box-sizing:content-box;height:50px;z-index:9;color:#999}

@media (max-width: 991px){.custom-search-input-2 i{padding-right:10px}

}

.custom-search-input-2 input[type=\'submit\']{-moz-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;transition:all 0.3s ease-in-out;color:#fff;font-weight:600;font-size:14px;font-size:0.875rem;border:0;padding:0 25px;height:50px;cursor:pointer;outline:none;width:100%;-webkit-border-radius:0 3px 3px 0;-moz-border-radius:0 3px 3px 0;-ms-border-radius:0 3px 3px 0;border-radius:0 3px 3px 0;background-color:#fc5b62;margin-right:-1px}

@media (max-width: 991px){.custom-search-input-2 input[type=\'submit\']{margin:20px 0 0 0;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px}

}

.custom-search-input-2 input[type=\'submit\']:hover{background-color:#FFC107;color:#222}

.custom-search-input-2.inner{margin-bottom:30px;-webkit-box-shadow:0px 0px 30px 0px rgba(0,0,0,0.1);-moz-box-shadow:0px 0px 30px 0px rgba(0,0,0,0.1);box-shadow:0px 0px 30px 0px rgba(0,0,0,0.1)}

@media (max-width: 991px){.custom-search-input-2.inner{margin:0 0 20px 0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none}

}

.custom-search-input-2.inner-2{margin:0 0 20px 0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;background:none}

.custom-search-input-2.inner-2 .form-group{margin-bottom:10px}

.custom-search-input-2.inner-2 input{border:1px solid #ededed}

.custom-search-input-2.inner-2 input[type=\'submit\']{-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;margin-top:10px}

.custom-search-input-2.inner-2 i{padding-right:10px;line-height:48px;height:48px;top:1px}

.custom-search-input-2.inner-2 .nice-select{border:1px solid #ededed}

.panel-dropdown{position:relative;text-align:left;padding:15px 10px 0 15px}

@media (max-width: 991px){.panel-dropdown{background-color:#fff;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;height:50px}

}

.panel-dropdown a{color:#727b82;font-weight:500;transition:all 0.3s;display:flex;align-items:center;justify-content:flex-start;position:relative}

.panel-dropdown a:after{content:"\25BE";font-size:1.7rem;color:#999;font-weight:500;-moz-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;transition:all 0.3s ease-in-out;position:absolute;right:0;top:-8px;}

.panel-dropdown.active a:after{transform:rotate(180deg);}

.panel-dropdown .panel-dropdown-content{opacity:0;visibility:hidden;transition:all 0.3s;position:absolute;top:58px;left:0px;z-index:99;background:#fff;border-radius:4px;white-space:normal;width:310px;box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;border:none;}

.panel-dropdown .panel-dropdown-content:after{bottom:100%;left:15px;border:solid transparent;content:" ";height:0;width:0;position:absolute;pointer-events:none;border-bottom-color:#fff;border-width:7px;margin-left:-7px}

.panel-dropdown .panel-dropdown-content.right{left:auto;right:0}

.panel-dropdown .panel-dropdown-content.right:after{left:auto;right:15px}

.panel-dropdown.active .panel-dropdown-content{opacity:1;visibility:visible}

.qtyButtons{display:flex;margin:0 0 13px 0}

.qtyButtons input{outline:0;font-size:16px;font-size:1rem;text-align:center;width:50px;height:36px !important;color:#333;line-height:36px;margin:0 !important;padding:0 5px !important;border:none;box-shadow:none;pointer-events:none;display:inline-block;border:none !important}

.qtyButtons label{font-weight:400;line-height:36px;padding-right:15px;display:block;flex:1;color:#626262;    font-size: 18px;}

.qtyInc,.qtyDec{width:28px;height:28px;line-height:22px;font-size:15px;background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'bd;-webkit-text-stroke:1px #fff;color:#333;display:inline-block;text-align:center;border-radius:50%;cursor:pointer;}

.qtyInc:hover,.qtyDec:hover{background:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'bd;}

.qtyInc:hover:before, .qtyDec:hover:before{color:#fff}

.qtyInc:before{content:"\002B";font-size:19px;font-weight:900;line-height: 29px;}

.qtyDec:before{content:"\2212";font-size:19px;font-weight:900;line-height: 29px;}

.qtyTotal, .qtyRoom{border-radius:50%;color:#66676b;display:inline-block;font-size:14px;font-weight:600;font-family:\'Montserrat\', sans-serif;line-height:18px;text-align:center;position:relative;top:1px;left:7px;height:18px;width:18px;margin-right:15px}

.rotate-x{animation-duration:.5s;animation-name:rotate-x}

@keyframes rotate-x{from{transform:rotateY(0deg)}

to{transform:rotateY(360deg)}

}

.daterangepicker{box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important;border:none;}

.daterangepicker td.in-range{background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'54;cor:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'54;}

.daterangepicker td.active, .daterangepicker td.active:hover {background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).';border-color:transparent;color:#fff;}

.daterangepicker td.available:hover, .daterangepicker th.available:hover{background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'99;color:#fff;border-radius:40px}

.btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle{background-color:#c82333;border-color:#c82333;}

.ripple{position:relative;overflow:hidden;transform:translate3d(0,0,0)}

.ripple:after{content:"";display:block;position:absolute;width:100%;height:100%;top:0;left:0;pointer-events:none;background-image:radial-gradient(circle,#000 10%,transparent 10.01%);background-repeat:no-repeat;background-position:50%;transform:scale(10,10);opacity:0;transition:transform .5s,opacity 1s}

.ripple:active:after{transform:scale(0,0);opacity:.2;transition:0s}

.btn-primary{color:#fff;background-color:#DC3545;border-color:#DC3545;}

.btn-primary:hover{background-color:#c82333;border-color:#bd2130;}

.btn-primary:focus{background-color:#c82333;border-color:#bd2130;box-shadow:0 0 0 0.2rem rgba(200, 35, 51, 0.5)!important;}

.nice-select.wide{width:100%}

.nice-select.wide .list{left:0 !important;right:0 !important}

.custom-select-form .nice-select{-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;border:1px solid #d2d8dd;height:45px;line-height:42px}

.custom-select-form .nice-select:hover{border-color:#d2d8dd}

.custom-select-form .nice-select:active,.custom-select-form .nice-select.open,.custom-select-form .nice-select:focus{border-color:#80bdff;outline:0;box-shadow:0 0 0 0.2rem rgba(0,123,255,0.25)}

.custom-select-form select{display:none} 

.custom-select-form .nice-select{border:none;height:50px;line-height:50px;border-radius:4px 0 0 4px;border-right:1px solid #d2d8dd !important;}

.nice-select .list{box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important;width:100%;}

.nice-select.open .list{height:250px;overflow-y:auto;}

.custom-select-form .nice-select:active, .custom-select-form .nice-select.open, .custom-select-form .nice-select:focus {border-color:#fff;outline:0;box-shadow:none;}

.wrn-btn span{cursor:pointer;display:inline-block;position:relative;transition:.5s}

.wrn-btn span:after{content:\'\00bb\';position:absolute;opacity:0;top:-8px;right:-20px;transition:.5s;font-size:24px;}

.wrn-btn:hover span{padding-right:20px}

.wrn-btn:hover span:after{opacity:1;right:0}

.wrapper-grid{padding:0 20px}

.box_grid{background-color:#fff;display:block;position:relative;margin-bottom:30px;-webkit-box-shadow:0px 0px 20px 0px rgba(0,0,0,0.1);-moz-box-shadow:0px 0px 20px 0px rgba(0,0,0,0.1);box-shadow:0px 0px 20px 0px rgba(0,0,0,0.1)}

.box_grid .price{display:inline-block;font-weight:500;color:#999}

.box_grid .price strong{color:#32a067}

.box_grid a.wish_bt{position:absolute;right:15px;top:15px;z-index:1;background-color:#000;background-color:rgba(0,0,0,0.6);padding:7px 10px 7px 10px;display:inline-block;color:#fff;line-height:1;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px}

.box_grid a.wish_bt:after{content:"\2661";-moz-transition:all 0.5s ease;-o-transition:all 0.5s ease;-webkit-transition:all 0.5s ease;-ms-transition:all 0.5s ease;transition:all 0.5s ease;font-size:20px;}

.box_grid a.wish_bt.liked:after{content:"\e089";color:#fc5b62}

.box_grid a.wish_bt:hover.liked:after{color:#fc5b62}

.box_grid a.wish_bt:hover:after{content:"\e089";color:#fff}

.box_grid figure{margin-bottom:0;overflow:hidden;position:relative;height:210px}

.box_grid figure small{position:absolute;background-color:#000;background-color:rgba(0,0,0,0.6);left:20px;top:22px;text-transform:uppercase;color:#ccc;font-weight:600;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;padding:5px 10px 5px 10px;line-height:1}

.box_grid figure .read_more{position:absolute;top:50%;left:0;margin-top:-12px;-webkit-transform:translateY(10px);-moz-transform:translateY(10px);-ms-transform:translateY(10px);-o-transform:translateY(10px);transform:translateY(10px);text-align:center;opacity:0;visibility:hidden;width:100%;-webkit-transition:all 0.6s;transition:all 0.6s;z-index:2}

.box_grid figure .read_more span{background-color:#fcfcfc;background-color:rgba(255,255,255,0.8);-webkit-border-radius:20px;-moz-border-radius:20px;-ms-border-radius:20px;border-radius:20px;display:inline-block;color:#222;font-size:12px;font-size:0.75rem;padding:5px 10px}

.box_grid figure:hover .read_more{opacity:1;visibility:visible;-webkit-transform:translateY(0);-moz-transform:translateY(0);-ms-transform:translateY(0);-o-transform:translateY(0);transform:translateY(0)}

.box_grid figure a img{position:absolute;left:50%;top:50%;-webkit-transform:translate(-50%, -50%) scale(1.1);-moz-transform:translate(-50%, -50%) scale(1.1);-ms-transform:translate(-50%, -50%) scale(1.1);-o-transform:translate(-50%, -50%) scale(1.1);transform:translate(-50%, -50%) scale(1.1);-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;-ms-backface-visibility:hidden;-o-backface-visibility:hidden;backface-visibility:hidden;width:100%;-moz-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;transition:all 0.3s ease-in-out}

.box_grid figure a:hover img{-webkit-transform:translate(-50%, -50%) scale(1);-moz-transform:translate(-50%, -50%) scale(1);-ms-transform:translate(-50%, -50%) scale(1);-o-transform:translate(-50%, -50%) scale(1);transform:translate(-50%, -50%) scale(1)}

.box_grid .wrapper{padding:25px}

.box_grid .wrapper h3{font-size:20px;font-size:1.25rem;margin-top:0}

.box_grid ul{padding:20px 15px;border-top:1px solid #ededed}

.box_grid ul li{display:inline-block;margin-right:15px}

.box_grid ul li .score{margin-top:-10px}

.box_grid ul li:last-child{margin-right:0;float:right}

.score strong{background-color:#0054a6;color:#fff;line-height:1;-webkit-border-radius:5px 5px 5px 0;-moz-border-radius:5px 5px 5px 0;-ms-border-radius:5px 5px 5px 0;border-radius:5px 5px 5px 0;padding:10px;display:inline-block}

.score span{display:inline-block;position:relative;top:7px;margin-right:8px;font-size:12px;font-size:0.75rem;text-align:right;line-height:1.1;font-weight:500}

.score span em{display:block;font-weight:normal;font-size:11px;font-size:0.6875rem}

.main_title_2 h2{margin:25px 0 0 0;color:#333;}

.main_title_2 h3{margin:25px 0 0 0;color:#727272;}

.main_title_2 p{margin:8px 0 0 0;color:#727272;}

p{color:#727272;font-size:15px;line-height:20px;}

a{color:#DC3545;}

a:hover{text-decoration:none;color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'}

.owl-carousel .owl-nav button.owl-next,.owl-carousel .owl-nav button.owl-prev,.owl-carousel button.owl-dot{background:rgba(0, 84, 166, 0.85)!important;color:inherit;border:none;padding:5px 14px!important;position:absolute;top:50%;color:#fff!impotant;border-radius:3px!impotant}

.owl-carousel .owl-nav .owl-prev{left:0;}

.owl-carousel .owl-nav .owl-prev span{font-size:20px;line-height:22px;}

.owl-carousel .owl-nav .owl-prev:focus{outline:none;border:none;box-shadow:none}

.owl-carousel .owl-nav .owl-next{right:0}

.owl-carousel .owl-nav .owl-next span{font-size:20px;line-height:22px;}

.owl-carousel .owl-nav .owl-next:focus{outline:none;border:none;box-shadow:none}

#places{margin-top:40px}

@media (max-width: 767px){#places{margin-top:0}

}

#places .item{margin:0 15px}

#places .owl-item{opacity:0.5;transform:scale(0.85);-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;-ms-backface-visibility:hidden;-o-backface-visibility:hidden;backface-visibility:hidden;-webkit-transform:translateZ(0) scale(0.85, 0.85);transition:all 0.3s ease-in-out 0s;overflow:hidden}

#places .owl-item.active.center{opacity:1;-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;-ms-backface-visibility:hidden;-o-backface-visibility:hidden;backface-visibility:hidden;-webkit-transform:translateZ(0) scale(1, 1);transform:scale(1)}

#places .owl-item.active.center .item .title h4,#places .owl-item.active.center .item .views{opacity:1}

.owl-theme .owl-dots{margin-top:10px !important;margin-bottom:25px}

.search-sec .tag_line h3{font-size: 2.625rem;text-shadow: 4px 4px 12px rgba(0,0,0,0.3);color:#fff;margin:0;text-transform:uppercase;font-weight:700;}

.search-sec .tag_line p{font-size: 21px;text-shadow: 4px 4px 12px rgba(0,0,0,0.3);color:#fff;margin:5px 0 0 0;font-weight:400;}

.custom_header{position:absolute;top:0;z-index:99;width:100%;background: rgba(26,70,104,.51) !important;border-radius:0;}

.navbar .navbar-brand{color:#fff!important;font-size:30px;}

.navbar .navbar-nav li a{color:#fff!important;}

.navbar .navbar-nav li.active a{color:#DC3545!important;}

#side-menu{display:none;position:fixed;width:320px;top:0;right:-300px;height:100%;overflow-y:auto;z-index:99999;background:#fff;padding:20px 15px;color:#333;transition:.4s;box-shadow:-5px 0 20px rgba(0, 0, 0, 0.2);}

body.side-menu-visible #side-menu{transform:translateX(-300px);overflow:hidden;}

#side-menu .logo{max-width:65%;}

#side-menu .contents{margin-top:00px;border-top:1px solid #eee;padding-top:20px;}

#side-menu li.nav-item:before{content:\'\203A\';position:absolute;left:2px;top:7px;}

#side-menu li.nav-item{padding-left:20px;}

#side-menu .nav-link{color:#333;font-size:14px;font-weight:600;padding:10px 0}

#side-menu .nav-link:hover{opacity:.8;color:#1b820a;}

#side-menu li.nav-item.dropdown.show{border-bottom:1px solid #eee;padding-bottom:10px;margin-bottom:10px;}

#side-menu .close{font-size:36px;font-weight:400;position:absolute;top:5px;right:15px;}

#side-menu .contact a, #side-menu .contact .fa{padding:5px 0px;background:#fff;font-size:14px;color:#727272;}

#side-menu .contact a:hover, #side-menu .contact .fa:hover{color: #28ab13 !important;}

#side-menu .contact a:focus, #side-menu .contact .fa:focus{color: #28ab13 !important;}

.dados{position:absolute;}

table td, table th{padding:9px;font-family: \'Montserrat\';font-weight: 600;}

table caption+thead tr:first-child td, table caption+thead tr:first-child th, table colgroup+thead tr:first-child td, table colgroup+thead tr:first-child th, table thead:first-child tr:first-child td, table thead:first-child tr:first-child th{

	border-top:none !important;

	font-size:17px !important;

    text-transform: capitalize;

}

.daterangepicker .calendar-table th, .daterangepicker .calendar-table td{text-transform:uppercase}

.daterangepicker td.start-date{border-radius:40px 0px 0px 40px}

.daterangepicker td.end-date{border-radius:0px 40px 40px 0px}

.daterangepicker.show-calendar .drp-buttons{font-family: \'Montserrat\'}

.daterangepicker td.start-date.end-date{border-radius:40px}

.cancelBtn{color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'bd !important}

.cancelBtn:hover{background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'bd !important;color:#fff !important}

.applyBtn{background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).' !important;border-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).' !important}

.applyBtn:hover{background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'bd !important}

.daterangepicker .drp-selected{display:none !important;}

.btnAddRoom{font-size: 13px;font-weight: 700;color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).';padding:6px 0}

.btnAddRoom:hover{color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'ee;}

.btnApplyRoom{background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).';color: #fff;font-size: 14px;font-weight: 600;border-radius: 40px;padding: 5px 30px;float: right;}

.btnApplyRoom:hover{background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'d4;}

.ripple{background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).' !important;border:transparent !important}

.ripple:hover{background-color:'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'80 !important}

.dados:after{

	bottom: 100%;

    left: 15px;

    border: solid transparent;

    content: " ";

    height: 0;

    width: 0;

    position: absolute;

    pointer-events: none;

    border-bottom-color: #fff;

    border-width: 7px;

    margin-left: -7px;

}

.dados ul li:hover{

	background-color: #f1f1f1;

}

.banner{

margin: 10px 50px;

}

		.custom-search-input-2 {

    		background-color: transparent !important;

    		box-shadow: none !important;

    	}

    	.search-sec {

		    padding: 2rem 1rem !important;

		}

    	.no-gutters .col-lg-12{

    		margin-bottom: 13px !important;

    	}

    	.panel-dropdown{

    		padding: 15px 4px 15px 15px !important;

    		    background-color: #fff !important;

    		border-radius: 5px !important;

    	}

    	.ripple {

		    background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).' !important;

		    border: transparent !important;

		    border-radius: 5px !important;

		}

		.ripple:hover{

			background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'80 !important;

		}

		.custom-search-input-2 input {

    		font-size: .815rem !important;

    		border-right: 0;

    	}

    	.search-slt{

    		height: 30px !important;

    	}

    	.custom-search-input-2 i{

    		padding-right: 4px;

    	}

    	.qtyTotal, .qtyRoom{

    		width: auto;

    		font-size: 12px !important;

    	}

    	.dados ul li{

    		font-size:13px !important;

    	}

    	.dados ul li img{

    		height: 19px  !important;

    	} 

	</style>';



	$retorno .= '

	<section class="banner">  

		<div class="search-sec container" style="background-color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'c7">



			<input type="hidden" id="field_date_checkin_ehtl" value="">

			<input type="hidden" id="field_date_checkout_ehtl" value=""> 

			<input type="hidden" id="adultos" value="2"> 

			<input type="hidden" id="criancas" value=""> 

			<input type="hidden" id="quartos" value="1"> 

			<div class="row">

				<form class="col-lg-12 col-12">

					<h4 style="color: #fff;font-weight: 600;font-size: 19px;margin-bottom: 22px;">Hospedagens</h4>

					<div class="row no-gutters custom-search-input-2"> 

						<div class="col-lg-12">

							<div class="form-group">

								<div class="custom-select-form">

									<input type="text" class="form-control" id="field_name_ehtl" autocomplete="off" placeholder="Informe a cidade" onfocus="this.value=\'\'">

									<div class="dados">

										<ul style="padding:0"></ul>

									</div>

								</div>

								<i class="fa fa-map-marker"></i>

							</div>

						</div>

						<div class="col-lg-12 date">

							<div class="form-group" style="background-color: #fff;padding: 16px;border-radius: 4px;">

								<label style="margin:0;font-size:.805rem !important;font-weight:600">Noites</label>

								<input class="form-control search-slt" type="text" name="dates" id="dates" placeholder="Selecione as datas" autocomplete="off" style="padding: 0;" readonly="readonly">

								<i class="fa fa-calendar" style="height: 83px;line-height: 83px;"></i>

							</div>

						</div>

						<div class="col-lg-12">

							<div class="panel-dropdown">

								<a href="#"><i class="fa fa-bed" style="position: unset;padding: 0;line-height: 1;height: auto;font-size: 15px;margin-right: -3px;"></i> <span class="qtyRoom">1 quarto</span> | <i class="fa fa-user" style="position: unset;padding: 0;line-height: 1;height: auto;margin-left: 5px;font-size: 15px;margin-right: -7px;"></i> <span class="qtyTotal">2 hóspedes</span></a>

								<div class="panel-dropdown-content">

									<input type="hidden" id="qtd_room_add" value="1">

									<div class="rooms_add">

										<div id="panel1" class="panel1" style="padding:15px 15px 0 15px;">

											<input type="hidden" id="panel1qts" value="1">

											<h6>Quarto 1</h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel1adt" value="2">

												<label>Adultos</label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="2">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel1chd" value="0">

												<label style="line-height:1">

													Menor <br> 

													<small style="font-weight: 500;font-size: 12px;">Até 17 anos</small>

												</label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0" max="4">

												<div class="qtyInc"></div>

											</div> 

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

										<div id="panel2" class="panel2" style="display:none;padding:15px 15px 0 15px;">

											<input type="hidden" id="panel2qts" value="0">

											<h6>Quarto 2 <span class="btn btnAddRoom btnRemoverQuarto2" onclick="remove_room(2)" style="display:none;">Remover</span></h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel2adt" value="0">

												<label>Adultos</label>

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel2chd" value="0">

												<label style="line-height:1">Menor <br> <small style="font-weight: 500;font-size: 12px;">Até 17 anos</small></label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

										<div id="panel3" class="panel3" style="display:none;padding:15px 15px 0 15px;">

											<input type="hidden" id="panel3qts" value="0">

											<h6>Quarto 3 <span class="btn btnAddRoom btnRemoverQuarto3" onclick="remove_room(3)" style="display:none;">Remover</span></h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel3adt" value="0">

												<label>Adultos</label>

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel3chd" value="0">

												<label style="line-height:1">Menor <br> <small style="font-weight: 500;font-size: 12px;">Até 17 anos</small></label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

										<div id="panel4" class="panel4" style="display:none;padding:15px 15px 0 15px;">

											<input type="hidden" id="panel4qts" value="0">

											<h6>Quarto 4 <span class="btn btnAddRoom btnRemoverQuarto4" onclick="remove_room(4)" style="display:none;">Remover</span></h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel4adt" value="0">

												<label>Adultos</label>

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel4chd" value="0">

												<label style="line-height:1">Menor <br> <small style="font-weight: 500;font-size: 12px;">Até 17 anos</small></label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

										<div id="panel5" class="panel5" style="display:none;padding:15px 15px 0 15px;">

											<input type="hidden" id="panel5qts" value="0">

											<h6>Quarto 5 <span class="btn btnAddRoom btnRemoverQuarto5" onclick="remove_room(5)" style="display:none;">Remover</span></h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel5adt" value="0">

												<label>Adultos</label>

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel5chd" value="0">

												<label style="line-height:1">Menor <br> <small style="font-weight: 500;font-size: 12px;">Até 17 anos</small></label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

										<div id="panel6" class="panel6" style="display:none;padding:15px 15px 0 15px;">

											<input type="hidden" id="panel6qts" value="0">

											<h6>Quarto 6 <span class="btn btnAddRoom btnRemoverQuarto6" onclick="remove_room(6)" style="display:none;">Remover</span></h6>

											<hr style="margin:16px 0">

											<div class="qtyButtons qtyAdt">

												<input type="hidden" id="panel6adt" value="0">

												<label>Adultos</label>

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="qtyButtons qtyChd">

												<input type="hidden" id="panel6chd" value="0">

												<label style="line-height:1">Menor <br> <small style="font-weight: 500;font-size: 12px;">Até 17 anos</small></label> 

												<div class="qtyDec"></div>

												<input type="text" name="qtyInput" value="0">

												<div class="qtyInc"></div>

											</div>

											<div class="idade_chd1" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd2" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd3" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

											<div class="idade_chd4" style="display:none">

												<div class="row">

													<div class="col-lg-7 col-12">

														<label style="line-height:1;font-size: 16px;">Idade<br> <small style="font-weight: 500;font-size: 12px;">Ao finalizar a viagem</small></label> 

													</div>

													<div class="col-lg-5 col-12"> 

														<select class="form-control">

															<option value="">Selecione...</option>

															<option value="1">Até 1 ano</option>

															<option value="2">2 anos</option>

															<option value="3">3 anos</option>

															<option value="4">4 anos</option>

															<option value="5">5 anos</option>

															<option value="6">6 anos</option>

															<option value="7">7 anos</option>

															<option value="8">8 anos</option>

															<option value="9">9 anos</option>

															<option value="10">10 anos</option>

															<option value="11">11 anos</option>

															<option value="12">12 anos</option>

															<option value="13">13 anos</option>

															<option value="14">14 anos</option>

															<option value="15">15 anos</option>

															<option value="16">16 anos</option>

															<option value="17">17 anos</option>

														</select>

													</div>

												</div>

											</div>

										</div>

									</div>

									<div class="apply" style="border-top: 1px solid #ccc;padding:15px;">

										<div class="row ">

											<div class="col-lg-12 col-12" style="text-align:right;">

												<span class="btn btnAddRoom spanButtonAddRoom" onclick="add_room()">Adicionar quarto</span>

											</div> 

										</div>

									</div>

								</div>

							</div>

						</div>

						<div class="col-lg-12">

							<button type="submit" class="btn_search btn btn-danger wrn-btn ripple" onclick="search_results()"><span>Buscar </span></button>

						</div>

					</div>

				</form>

			</div>

		</div>

	</section> ';



	$retorno .= '<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

	<script src="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/owl.carousel.js"></script>

	<script src="https://www.jqueryscript.net/demo/Customizable-Animated-Dropdown-Plugin-with-jQuery-CSS3-Nice-Select/js/jquery.nice-select.js"></script>

	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>';



	return $retorno;



}



add_shortcode('TTBOOKING_RESULTADOS_RESERVA', 'shortcode_resultados_reserva');  



function shortcode_resultados_reserva(){

	$retorno = '';



	$retorno .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">';

	$retorno .= '<link rel="preconnect" href="https://fonts.googleapis.com">

				<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

				<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

				<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">'; 



	$retorno .= '<style>

		body{

			background-color: #efefef !important;

		}

		.dadosGerais{

			font-family: \'Montserrat\', sans-serif !important;

		}



		.rowGeral{

			margin: 15px 0px;

			border: 1px solid #eee;

    		padding: 20px;

    		font-family: \'Montserrat\', sans-serif;

		}

		.rowGeral:hover{

    		box-shadow: 2px 2px 6px #fafafa;

		}

		.rowInterna label{

			text-transform: uppercase;

			font-weight: 700;

			font-size: 13px;

			background-color: #0c9dbf;

			padding: 5px 0px;

    		color: #fff;

    		width: 100%;

		}

		.rowInterna p{

    		font-size: 15px;

    		margin: 10px 0px; 

		}

		.rowPrice{

			font-size: 13px;

		    width: 100%;

		    padding: 12px;

		    margin: 13px 0;

		    background-color: #f8f8f8;

		}

		@media(max-width: 766px){

			.rowHotel{

				margin: 20px 0 !important

			}

			.rowHotel .colImage img{

				width: 100% !important;

				border-radius: 10px 10px 0px 0px !important;

			}

			.rowHotel .colDetails, .rowHotel .colSelect{

				text-align: center !important;

			}

			.rowHotel .colDetails div{

				margin: 0 !important;

			}

			.rowHotel .colSelect .desc{

				font-size: 16px !important; 

			}

			.rowHotel .colSelect .price{

				font-size: 29px !important;

			}

			.rowHotel .colSelect .included_price{

				margin-top: 20px !important;

			}

			.rowHotel .colDetails .payment span, .rowHotel .colSelect .included_price span{

				font-size: 13px !important;

			}

			.divisor{

				display: none;

			}

			.responsiveBR{

				display: inherit;

			}

			#show_results{

				padding: 10px;

			}

		}



		@media(min-width:767px){

			.modal-content{

				width: 450px !important;

			}

			.rowMeioInterna{

				padding: 0 20px !important;

			}

			.responsivePadding{

				padding-left: 0 !important;

			}



			.rowHotel{

				height: 274px;

				margin-left: 0px !important;

			}

			.rowHotel .colImage img{

				border-radius: 10px 0px 0px 10px;

				height: 273px;

				width: 100% !important;

			}

			.rowHotel .colDetails h5{

				font-size: 20px;

			    line-height: 27px;

			}  

			.rowHotel .colDetails div{

				margin: 12px 0;

			}

			.rowHotel .colDetails .payment{

				position: absolute;

				bottom: 0;

			}

			.rowHotel .colSelect{

				border-left: 1px solid #ddd;

			}

			.rowHotel .colSelect .included_price{

				margin-top: 20px;

			} 

			.rowHotel .colSelect .included_price{

				position: absolute;

				bottom: 7px;

    			margin-right: 14px;

			}

			.divisor{

				display: contents;

			}

			.responsiveBR{

				display: none;

			}

			.resultsOrder .selectOrder{

				width: 80%;

			}

			div.range{

				padding: 0px 28px;

			}

			div.filter-price{

				min-height: 160px

			}

			.accordion-body{

				padding: 5px 20px;

			}

			.accordion-button{

				padding: 20px 0px 20px 20px;

			}

		}



		.rowHotel{

			background-color: #fff;

    		border-radius: 10px;

    		border: 1px solid #ddd;

    		margin-bottom: 20px;

    		font-family: \'Montserrat\'

		}

		.rowHotel .colImage{

			padding: 0

		}

		.rowHotel .colDetails, .rowHotel .colSelect{

			padding: 15px;

		}

		.rowHotel .colDetails h5{

		    font-weight: 600;

		    color: #3e3e3e;

		    margin-bottom: 0;

		}

		.rowHotel .colDetails h5 img{

			display: inline;

    		margin-top: -4px;

		}

		.rowHotel .colDetails h6{ 

		    color: #3e3e3e;

		    margin-top: 5px;

		    margin-bottom: 0;

		    font-size: 13px;

		}

		.rowHotel .colDetails span.address, .rowHotel .colSelect .desc{

			font-size: 12px;

		    line-height: 16px;

		    font-weight: 500;

		}

		.rowHotel .colDetails .review{

			padding: 5px;

		    background-color: #03a691;

		    font-size: 12px;

		    color: #fff;

		    margin-right: 5px;

		    border-radius: 6px;

		    font-weight: 600;

		    width: 27px;

		    display: inline-block;

		    height: 27px;

		}

		.rowHotel .colDetails .fa-star{

			font-size: 12px;

    		color: #f3ae0c;

    		margin-right:2px;

		}

		.rowHotel .colDetails .inclusion{

			border-left:1px solid #ddd;

			margin-left: 3px;

    		padding-left: 6px;

		}

		.rowHotel .colDetails .inclusion i{

			font-size: 16px;

    		color: #3e3e3e;

    		padding-right: 4px;

		}

		.rowHotel .colDetails .payment span, .rowHotel .colSelect .included_price span{

			font-size: 11px;

    		font-weight: 700;

    		letter-spacing: 0.5px;

    		color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';

    		cursor: pointer;

		}

		.rowHotel .colDetails .payment span:hover, .rowHotel .colSelect .included_price span:hover{

			color: #575658;

		}

		.rowHotel .colSelect .desc{

			color: #000;

		}

		.rowHotel .colSelect .price{

			margin-top: 9px;

    		font-size: 24px;

    		color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';

    		font-weight: 700;

    		margin-bottom: 0;

		}

		.rowHotel .colSelect .price .currency{ 

   			font-size: 14px; 

    		font-weight: 500;

		}

		.rowHotel .colSelect .tax{

			font-size: 11px; 

    		letter-spacing: 0.5px; 

		}

		.rowHotel .btnSelect{

			background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'d4;

		    margin: 10px 0;

		    width: 100%;

		    border-radius: 40px;

		    color: #fff;

		    font-weight: 700;

		    letter-spacing: 0.2px;

		    font-size: 15px;

		}

		.rowHotel .btnSelect:hover{

			background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'; 

		}



		.blog .carousel-indicators {

			left: 0;

			top: auto;

    		bottom: -40px; 

		}



		/* The colour of the indicators */

		.blog .carousel-indicators li {

		    background: #a3a3a3;

		    border-radius: 50%;

		    width: 8px;

		    height: 8px;

		}



		.blog .carousel-indicators .active {

			background: #707070;

		}



		.banner{

			margin: 0 !important;

		}



		.resultsOrder{

			margin-bottom: 20px;

		}

		.resultsOrder .selectOrder{

			border-radius: 8px;

		    font-family: \'Montserrat\';

		    font-size: 13px;

		    cursor:pointer;

		    border: 1px solid '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';

		}

		.resultsOrder label{

			font-size: 10px;

		    font-weight: 700;

		    font-family: \'Montserrat\';

		    margin-bottom: 0;

		    color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';

		}

		.filter hr{

			margin: 20px 0;

    		border-top: 1px solid #6f6f6f; 

		}

		.filter .accordion-button, .filter .accordion-body{

			background-color: #f0f0f0 !important;

			color: #000 !important;

			border: none !important;

			font-family: \'Montserrat\' !important;

			box-shadow: none !important;

		}

		.filter .accordion-button{

			font-weight: 700;

		} 

		.noUi-horizontal .noUi-tooltip{

			bottom: -150%

		}

		.price-range-right, .price-range-left{

			font-weight: 700;

    		font-size: 13px;

		}

		.info-flex{

			font-size: 11px;

		    letter-spacing: 0.2px;

		    line-height: 1.4;

		    margin-bottom: 0;

		}

		.form-check-input{

			width: 1.5em;

			height: 1.5em;

		}

		.span-qty{

			width: 2rem;

		    height: 1.6rem;

		    border: 1px solid #c7c7c7;

		    border-radius: 5px;

		    font-size: 12px;

		    padding: 3px 6px;

		    font-weight: 700;

		    margin-bottom: 0;

		}

		.accordion-body .row{

			margin-bottom: 8px;

		}

		.form-check-label{

			margin-left: 8px;

		}

		.accordion-body .fa{

			color: #575757;

		}



		.row-is-loading h5, .row-is-loading h6, .row-is-loading .div-review, .row-is-loading .payment, .row-is-loading .colSelect .desc, .row-is-loading .colSelect .price, .row-is-loading .colSelect .tax, .row-is-loading .colSelect .included_price, .row-is-loading .colImage{

			background: #eee;

		    background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);

		    border-radius: 5px;

		    background-size: 200% 100%;

		    animation: 1.5s shine linear infinite;

		}

		.row-is-loading .filter-price, .row-is-loading .filter-stars, .row-is-loading .filter-refeicao, .row-is-loading .filter-acomodacao{

			background: #eee;

		    background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);

		    border-radius: 5px;

		    background-size: 200% 100%;

		    animation: 1.5s shine linear infinite;

		}



		.row-is-loading .filter-price{

			height: 160px;

		}

		.row-is-loading .filter-stars, .row-is-loading .filter-refeicao, .row-is-loading .filter-acomodacao{

			height: 60px;

		}



		.row-is-loading .colSelect .desc, .row-is-loading .colSelect .tax{

			height: 15px;

		}

		.row-is-loading .colSelect .price{

			height: 24px;

		} 

		.row-is-loading .colSelect .included_price{

			height: 42px;

			width: 84%;

		}

		.row-is-loading .payment{

			height: 22px;

			width: 91%;

		}

		.row-is-loading h6{

			height: 20px;

		}

		.row-is-loading .div-review{

			height: 22px;

		}

		.row-is-loading .colImage{

			height: 272px;

		}



		@keyframes shine {

		  	to {

		    	background-position-x: -200%;

		  	}

		}



		.modal-open .modal{

			font-family: \'Montserrat\';

		}

		.bootbox-close-button{

			background-color: transparent !important;

			color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).' !important;

		}



	</style>';



	$retorno .= '<div class="container">

		<div class="row">

			<div class="col-lg-3 col-12 responsivePadding">';

				$retorno .= do_shortcode('[TTBOOKING_MOTOR_RESERVA_LATERAL]'); 



				$retorno .= '<div id="filter" class="filter row-is-loading">



					<hr> 



					<div class="filter-price">

						 

					</div>



					<hr> 



					<div class="filter-stars">

						 

					</div>



					<hr> 



					<div class="filter-refeicao">

						 

					</div>



					<hr> 



					<div class="filter-acomodacao">

						 

					</div>



				</div>';

			$retorno .= '</div>

			<div class="col-lg-9 col-12">

				<div id="show_results">

					<div class="loader" style="display:none">

						<br>

						<h6 style="text-align:center;font-family: \'Montserrat\';line-height: 1.4;">Aguarde... <br> Estamos buscando as melhores ofertas.</h6>

						<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/loader.gif" style="margin: 0 auto"> 

					</div>

					<div class="results">

						<div class="resultsOrder">

							<div class="row">

								<div class="col-lg-4 col-12">

									<label>ORDENAR POR</label>

									<select class="form-control selectOrder" onchange="changeOrder()">

										<option value="1">Melhor pontuação</option>

										<option value="3">Preço: maior para o menor</option>

										<option value="2" selected>Preço: menor para o maior</option>

										<option value="4">Estrelas: maior para o menor</option>

										<option value="5">Estrelas: menor para o maior</option>

									</select>

								</div>

							</div>

						</div>

						<div class="resultsHotel">';

							for($i=0; $i<10; $i++){

								$retorno .= '<div class="row rowHotel row-is-loading">

									<div class="col-lg-4 col-12 colImage">

										 

									</div>

									<div class="col-lg-5 col-12 colDetails">

										<h5> </h5>

										<h6> </h6>

										<br>

										<div class="div-review">

											 

										</div>

										<br>

										<div class="payment">

											 

										</div>

									</div>

									<div class="col-lg-3 col-12 colSelect">

										<small class="desc"> </small>

										<br>

										<p class="price">

											 

										</p> 

										<small class="tax"> </small>

										<br>

										<div class="included_price">

											 

										</div>

									</div>

								</div>';

							}

						$retorno .= '</div>

					</div>

				</div> 

			</div> 

		</div>

	</div>';



	$retorno .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>';

	$retorno .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" crossorigin="anonymous"></script>';	

	$retorno .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js" crossorigin="anonymous"></script>';		

	$retorno .= '<script src="https://refreshless.com/nouislider/documentation/assets/wNumb.js" crossorigin="anonymous"></script>';	 		



	return $retorno;

}



add_shortcode('TTBOOKING_DETALHE_RESULTADOS_RESERVA', 'shortcode_detalhe_resultados_reserva');  



function shortcode_detalhe_resultados_reserva(){

	$retorno = '';



	$retorno .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">';

	$retorno .= '<link rel="preconnect" href="https://fonts.googleapis.com">

				<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

				<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

				<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">'; 



	$retorno .= '<style>

		@media(max-width: 766px){

			.show-desktop{

				display: none;

			}



			.imgPrincipal{

				height: 200px;

				margin-bottom: 20px;

			}

			.row-data-hotel .stars i{

				font-size: 15px;

    			color: #ff9f1d;

			}

			.row-data-hotel .address{

				font-size: 13px; 

			}

			.row-data-hotel .infoRoom{

			    box-shadow: 2px 2px 6px #40404040;

			    border-radius: 10px; 

			    min-height: 150px;  

			    background-color: #fff;

			    width: 70%;

			    padding: 20px;

			    margin: 0 auto 20px auto;

	    		text-align: center;

			}

			.row-data-hotel .regim{

				position: relative;

			    border: 1px solid #b8b8b8;

			    padding: 4px 15px;

			    border-radius: 4px;

			    top: -13px;

			    background-color: #fff;

			    font-size: 12px;

			    font-weight: 600;

			}

			.container-detail-hotel hr{

				margin: 20px 0;

			}

			.btnSelect{

				background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'d4;

			    margin: 10px 0;

			    width: 100%;

			    border-radius: 40px;

			    color: #fff;

			    font-weight: 700;

			    letter-spacing: 0.2px;

			    font-size: 15px;

			}

			.btnSelect:hover{

				background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'; 

				color: #fff;

			}

			.detailRoom, .rowOfferHotel, .rowMoreHotel, .rowConditionsHotel{

				text-align: center;

			}

			.detailsRoomGeneral{

				padding: 20px 0;

				text-align: center;

			}

			.detailRoom .price{

				color: #333;

				font-size: 24px;

    			font-weight: 700;

			}

			.detailRoom .currency{

				font-size: 15px;

    			font-weight: 300;

			}

			.questionRoom{

				margin: 6px 0;

	    		font-size: 11px;

	    		color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';

	    		text-align: center;

			}

			.rowRoom{

				padding: 10px;

			    border: 1px solid #dfdfdf;

			    border-radius: 10px;

			    margin: 10px

			} 

			button.gallery{

				position: absolute;

			    right: 28px;

			    background-color: #fff;

			    color: #333;

			    border-radius: 24px;

			    font-size: 13px;

			    font-family: \'Montserrat\';

			    font-weight: 600;

			    padding: 5px 20px;

			    top: 14px;

			    cursor: pointer;

			}

			button.gallery:hover{

				box-shadow: 2px 2px 4px #00000070;

			    color: #fff;

			    background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).' !important;

			}

		}

		.modal-open .modal{

			font-family: \'Montserrat\';

		}

		.bootbox-close-button{

			background-color: transparent !important;

			color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).' !important;

			border: none;

			font-size: 23px;

		}

		@media(min-width: 767px){

			.modal-content{

				width: 450px !important;

			} 

			.imgPrincipal img{

				height: 100%;

			} 

			.rowSecundaria .col-12, .imgPrincipal{

				padding: 2px; 

			} 

			.rowSecundaria, .imgPrincipal{ 

				height: 570px;

			} 

			button.gallery{

				position: absolute;

			    right: 14px;

			    background-color: #fff;

			    color: #333;

			    border-radius: 24px;

			    font-size: 14px;

			    font-family: \'Montserrat\';

			    font-weight: 600;

			    padding: 5px 20px;

			    top: 14px;

			    cursor: pointer;

			}

			button.gallery:hover{

				box-shadow: 2px 2px 4px #00000070;

			    color: #fff;

			    background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).' !important;

			}

			.row-data-hotel{

				margin: 35px 0;

				height: 180px;

			}

			.rowOfferHotel, .rowMoreHotel, .rowConditionsHotel, .rowRoomOffers{

				margin: 35px 0;

				padding: 30px 0;

			}

			.rowOfferHotel h4, .rowMoreHotel h4, .rowConditionsHotel h4{

				color: #333;

			    font-weight: 600;

			    font-size: 22px;

			    margin-bottom: 30px;

			}

			.rowMoreHotel p{

				font-size: 15px;

			    color: #000;

			    font-weight: 500;

			    line-height: 2;

			}



			.row-data-hotel h4{

				font-size: 32px;

				line-height: 36px;

				color: #333;

				font-weight: 700;

			}

			.row-data-hotel .img-featured{

				height: 25px;

			}

			.row-data-hotel .stars{

				margin: 12px 0;

			}

			.row-data-hotel .stars i{

				font-size: 17px;

    			color: #ff9f1d;

			}

			.row-data-hotel .address{

				font-size: 13px;

				font-weight: 600;

			}

			.row-data-hotel .infoRoom{

			    box-shadow: 2px 2px 6px #40404040;

			    border-radius: 10px;

				position: relative;

			    min-height: 150px;

			    right: -22px;

			    bottom: 65px;

			    background-color: #fff;

			    width: 250px;

			    padding: 20px;

			}

			.row-data-hotel .regim{

				position: absolute;

			    border: 1px solid #b8b8b8;

			    padding: 4px 15px;

			    border-radius: 4px;

			    top: -13px;

			    background-color: #fff;

			    font-size: 12px;

			    font-weight: 600;

			}

			.btnSelect{

				background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'d4;

			    margin: 10px 0;

			    width: 100%;

			    border-radius: 40px;

			    color: #fff;

			    font-weight: 700;

			    letter-spacing: 0.2px;

			    font-size: 15px;

			}

			.btnSelect:hover{

				background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'; 

				color: #fff;

			}

			.row-data-hotel .detailRoom{

				margin-top: 2px;

    			font-size: 13px;

			}

			.detailRoom .price{

				color: #333;

				font-size: 24px;

    			font-weight: 700;

			}

			.detailRoom .currency{

				font-size: 15px;

    			font-weight: 300;

			}

			.questionRoom{

				margin: 6px 0;

	    		font-size: 11px;

	    		color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';

			}

			.offers i{

				color: #333;

				font-size: 18px;

			}

			.offers p{

				color: #333; 

			    font-size: 15px;

			    margin: 10px;

			}

			.rowRoom .detailRoom{

				text-align: right;

			} 

			.rowRoom .col-lg-12 .row {

			    background-color: #fff;

			    border: 1px solid #ddd; 

			    padding: 20px; 

			    margin-left: 14px;

			}

			.rowRoom .col-lg-12 .row:hover {

				box-shadow: 2px 2px 4px #ddd;

			    border: 2px solid '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';  

			}

			.rowRoom img{

				height: 90px;

				margin-bottom: 8px;

			}

			.rowRoom .roomName{

				color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';

			}

			.rowRoom .roomDescription{

				font-size: 13.5px;

			}

			.checkRoom{

				padding: 50px 10px;

			}

			.offerDetailTitle{ 

			    color: #333;

			    text-align: center;

			    margin-bottom: 12px;

			    padding: 14px;

			    box-shadow: 0px 3px 4px #f0f0f0; 

			}

			.detailOffer .detailsRoomGeneral{

				border: 1px solid #f0f0f0; 

			}

			.detailOffer div .detailsRoomSelected{ 

				padding: 20px;

			} 

		}

		.rowOffers{

			background-color: #f0f0f052;

    		padding: 40px 10px;

		}

		.img-featured{

			display: inline;

		}

		.container-detail-hotel{

			font-family: \'Montserrat\';

		} 

		.rowRoom .roomActive{

			border: 2px solid '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).' !important;   

		}

		/* The radio */

		.radio {

		 

		        display: inline;

		    position: relative;

		    padding-left: 15px;

		    margin-bottom: 12px;

		    cursor: pointer;

		    font-size: 20px;

		    -webkit-user-select: none;

		    -moz-user-select: none;

		    -ms-user-select: none;

		    user-select: none

		}



		/* Hide the browsers default radio button */

		.radio input {

		    position: absolute;

		    opacity: 0;

		    cursor: pointer;

		}



		/* Create a custom radio button */

		.checkround {



		    position: absolute;

		    top: 6px;

		    left: 0;

		    height: 16px;

		    width: 16px;

		    background-color: #fff ;

		    border-color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';

		    border-style:solid;

		    border-width:2px;

		     border-radius: 50%;

		}





		/* When the radio button is checked, add a blue background */

		.radio input:checked ~ .checkround {

		    background-color: #fff;

		}



		/* Create the indicator (the dot/circle - hidden when not checked) */

		.checkround:after {

		    content: "";

		    position: absolute;

		    display: none;

		}



		/* Show the indicator (dot/circle) when checked */

		.radio input:checked ~ .checkround:after {

		    display: block;

		}



		/* Style the indicator (dot/circle) */

		.radio .checkround:after {

		     left: 2px;

		    top: 2px;

		    width: 8px;

		    height: 8px;

		    border-radius: 50%;

		    background:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';

		    

		 

		}



		.row-is-loading{

			background: #eee;

		    background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);

		    border-radius: 5px;

		    background-size: 200% 100%;

		    animation: 1.5s shine linear infinite;

		}



		/* Create four equal columns that floats next to eachother */

		.column {

		  	float: left;

		  	width: 25%;

		}



		/* The Modal (background) */

		.modal {

		  	display: none;

		  	position: fixed;

		  	z-index: 1;

		  	padding-top: 100px;

		  	left: 0;

		  	top: 0;

		  	width: 100%;

		  	height: 100%;

		  	overflow: auto;

		  	background-color: #000000d6;

		}



		/* Modal Content */

		.modal-content {

		  	position: relative;

		  	background-color: #fefefe;

		  	margin: auto;

		  	padding: 0;

		  	width: 90%;

		  	max-width: 1200px;

		}



		/* The Close Button */

		.close {

		  	color: white;

		  	position: absolute;

		  	top: 10px;

		  	right: 25px;

		  	font-size: 35px;

		  	font-weight: bold;

		}



		.close:hover,

		.close:focus {

		  	color: #999;

		  	text-decoration: none;

		  	cursor: pointer;

		}



		/* Hide the slides by default */

		.mySlides {

		  	display: none;

		}



		/* Next & previous buttons */

		.prev,

		.next {

		  	cursor: pointer;

		  	position: absolute;

		  	top: 50%;

		  	width: auto;

		  	padding: 16px;

		  	margin-top: -50px;

		  	color: white;

		  	font-weight: bold;

		  	font-size: 20px;

		  	transition: 0.6s ease;

		  	border-radius: 0 3px 3px 0;

		  	user-select: none;

		  	-webkit-user-select: none;

		}



		/* Position the "next button" to the right */

		.next {

		  	right: 0;

		  	border-radius: 3px 0 0 3px;

		}



		/* On hover, add a black background color with a little bit see-through */

		.prev:hover,

		.next:hover {

		  	background-color: rgba(0, 0, 0, 0.8);

		}



		/* Number text (1/3 etc) */

		.numbertext {

		  	color: #f2f2f2;

		  	font-size: 12px;

		  	padding: 8px 12px;

		  	position: absolute;

		  	top: 0;

		}



		/* Caption text */

		.caption-container {

		  	text-align: center;

		  	background-color: black;

		  	padding: 2px 16px;

		  	color: white;

		}



		img.demo {

		  	opacity: 0.6;

		}



		.active,

		.demo:hover {

		  	opacity: 1;

		}



		img.hover-shadow {

		  	transition: 0.3s;

		}



		.hover-shadow:hover {

		  	box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);

		}

	</style>';	



	$retorno .= '<input type="hidden" id="type_reserva" value="'.get_option( 'type_reserva_ehtl' ).'">';
	$retorno .= '<div class="container container-detail-hotel">



		<div class="row">



			<div id="imgPrincipal" class="col-lg-6 col-12 imgPrincipal ">

				<button class="btn gallery show-mobile" onclick="openModal();currentSlide(1)">Ver galeria</button>

				<div class="row-is-loading"></div>

			</div>  

			<div class="col-lg-6 col-12 show-desktop">



				<div class="row rowSecundaria">

					<div id="imgSecundaria" class="col-lg-12 col-12 imgSecundaria">

						<button class="btn gallery" onclick="openModal();currentSlide(1)">Ver galeria</button>



						<div class="row-is-loading"></div>

					</div>

					<div id="imgTres" class="col-lg-6 col-12 imgTres">

						<div class="row-is-loading"></div>

					</div>

					<div id="imgQuatro" class="col-lg-6 col-12 imgQuatro">

						<div class="row-is-loading"></div>

					</div>

				</div>



			</div> 



		</div>



		<div id="myModal" class="modal">



		</div>



		<div class="row row-data-hotel">



			<div class="col-lg-9 col-12">

				<h4 id="nameHotel" class="row-is-loading"></h4>

				<p id="starsHotel" class="stars row-is-loading"></p> 

				<p id="addressHotel" class="address row-is-loading"></p>

			</div>

			<div class="col-lg-3 col-12 infoRoom">



				<span id="mealHotel" class="regim row-is-loading" style="height: 28px;width:140px;"> </span>

				<p id="detailRoom" class="detailRoom row-is-loading" style="height:115px;">

					<strong id="nameRoom" class="nameRoom" style="text-transform:capitalize"> </strong>

					<br>

					<span id="detailNights" class="detailNights"> </span>

					<br>

					<span id="price" class="price"> </span>

					<br>

					<small>Impostos inclusos</small>

				</p> 

				<a href="#rowRoomOffers"><button class="btn btnSelect">Ver quartos</button></a>



			</div>



		</div>



		<div class="row">

			<div class="col-lg-12 col-12">

				<hr>

			</div>

		</div>



		<div class="row rowOfferHotel" style="display: none;">

			<div class="col-lg-12 col-12">

				<h4>A hospedagem oferece</h4>



				<div class="row rowOfferServices">

					<div class="col-lg-12 col-12 text-center offers row-is-loading" style="height:90px">

						

					</div>  

				</div>



			</div>

		</div>



		<div class="row">

			<div class="col-lg-12 col-12">

				<hr>

			</div>

		</div>



		<div class="row rowMoreHotel" style="min-height: 100px;">

			<div class="col-lg-12 col-12">

				<h4>Conheça um pouco mais</h4>



				<div id="knowHotel" class="row row-is-loading">

					<div class="col-lg-8 col-12" id="descriptionHotel">

						<p> </p>

					</div>



					<div class="col-lg-4 col-12" id="mapHotel">

						



					</div>

				</div>



			</div>

		</div>



		<div class="row">

			<div class="col-lg-12 col-12">

				<hr>

			</div>

		</div>



		<div class="row rowConditionsHotel">

			<div class="col-lg-12 col-12">

				<h4>Condições da hospedagem</h4>



				<div class="row justify-content-center">

					<div class="col-lg-12 col-12 offers"> 

						<p><i class="fa fa-wifi" style="margin-right: 7px"></i> Horário de Check-in: 14:00</p>

						<p><i class="fa fa-wifi" style="margin-right: 7px"></i> Horário de Check-out: 12:00</p>

					</div> 

				</div>



			</div>

		</div>



		<div class="row">

			<div class="col-lg-12 col-12">

				<hr>

			</div>

		</div>



		<input type="hidden" id="cor_ehtl" value="'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'">

		<input type="hidden" id="cor_botao_ehtl" value="'.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'">



		<div id="rowRoomOffers" class="row rowRoomOffers">

			<div class="col-lg-9 col-12">';



				for($i=1; $i<3; $i++){ 

					$retorno .= '<div class="row rowRoom row-is-loading" style="min-height:180px"> 

						<div class="col-lg-12 col-12"> 

							 

						</div> 

					</div>';

				}



			$retorno .= '</div>



			<div class="col-lg-3 col-12 detailOffer">

				<div class="detailsRoomGeneral">

					<p class="offerDetailTitle">

						<strong>DETALHE DA SUA RESERVA</strong>

					</p>

					<div id="roomSelectedReservation" class="detailsRoomSelected row-is-loading" style="min-height:345px">

						

					</div>

				</div>

			</div>

		</div>



	</div>';



	$retorno .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>';

	$retorno .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" crossorigin="anonymous"></script>';	

	$retorno .= '<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>	';	

	$retorno .= '<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>'; 



	return $retorno;



}



add_shortcode('TTBOOKING_CHECKOUT_RESERVA', 'shortcode_checkout_reserva');  



function shortcode_checkout_reserva(){

	$retorno = '';



	$retorno .= '

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

		<link rel="preconnect" href="https://fonts.googleapis.com">

		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

		<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> '; 



	$retorno .= '<style>

		body{

			background-color: #efefef !important;

			font-family: \'Montserrat\', sans-serif !important;

		} 

		.container-fluid{ 

			font-family: \'Montserrat\', sans-serif !important;

		}



		.contact, .address, .guests, .resume-price, .detail, .pay{

			padding: 20px 30px;

		    border-radius: 10px;

		    background-color: #fff;

		    border: 1px solid #ddd;

		    margin-bottom: 25px;

		}

		.contact h5, .address h5, .guests h5{

			margin-bottom: 30px;

			color: #333;

		}

		.contact label, .address label, .guests label{

			font-weight: 600;

		    color: #333;

		    margin-bottom: 8px;

		    font-size: 13px;

		    text-transform: uppercase;

		}

    	.input-group-prepend{

    		padding: 10px 16px;

		    background-color: #f0f0f0;

		    color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';

		    border-radius: 10px 0px 0px 10px;

    	}

    	.input-group .form-control{

    		border: 1px solid #f0f0f0;

    		border-radius: 0;

    		font-size: 15px;

    	}

    	.input-group .form-control:focus{

    		box-shadow: none;

    		border: 1px solid #f0f0f0;

    	}

    	.guests .qt{

    		color: #333;

    		font-size: 16px;

    	}

    	.guests .guest{

    		color: #333;

    		font-size: 15px;

    		margin-bottom: 8px;

    	}

    	.guests .rowGuest{

    		border-bottom: 1px solid #ddd;

    		padding-bottom: 20px;

    		margin-bottom: 20px;

    	}

    	.resume-price .col-lg-8, .resume-price .col-lg-4, .resume-price .col-lg-12{

    		padding: 0 3px;

    	}

    	.data_price, .value_price{ 

		    font-size: 13px;

		    color: #333;

		    margin-bottom: 0;

    	}

    	.value_price{

    		text-align: right;

    	}

    	.resume-price hr, .detail hr{

    		margin: 15px 0;

    	}

    	.data_total_price{

    		margin-bottom: 0;

		    font-weight: 700;

		    font-size: 15px;

		    color: #333;

		    padding: 6px 0px;

    	}

    	.value_total_price{

    		margin-bottom: 0;

		    font-weight: 700;

		    font-size: 22px;

		    color: #333;

		    text-align: right;

    	}

    	.value_total_price .currency{

    		font-size: 13px;

    	}

    	.title-hotel, .date-hotel span, .date-hotel strong{

    		color: #333;

    		font-weight: 700;

    	}

    	.star-hotel{

    		margin-bottom: 5px;

    	}

		.address-hotel{

			color: #333;

			font-size: 12px;

		}

		.detailRoom p{

			margin-bottom: 02px;

			font-size: 13px;

		}

		.certiSign p{

			margin-bottom: 0;

			font-size: 13px;

			color: #333;

		}

		.btnSelect{

			background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'d4;

		    margin: 10px 0;

		    width: 100%;

		    border-radius: 40px;

		    color: #fff;

		    font-weight: 700;

		    letter-spacing: 0.2px;

		    font-size: 15px;

		}

		.btnSelect:hover{

			background-color: '.(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' )).'; 

		    color: #fff;

		}



		@media(min-width:767px){ 

			.certiSign img{

				height: 36px;

				margin-bottom: 6px;

				float:right;

			}

			.show-mobile{

				display: none;

			}

		}



		@media(max-width:767px){

			.certiSign div{

				text-align: center;

			}

			.certiSign img{

				height: 36px;

				margin-bottom: 6px;

				margin: 0 auto;

			}

			#rowPrincipal{

				flex-direction: row-reverse;

			}

			.show-desktop{

				display: none;

			}

			.h4principal{

				font-size: 17px;

				margin-bottom: 20px !important

			}

		}

		.row-is-loading{

			background: #eee;

		    background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);

		    border-radius: 5px;

		    background-size: 200% 100%;

		    animation: 1.5s shine linear infinite;

		}



		/*

		 * CSS payment card

		 */ 



		.payment-card__footer{

			text-align: center;

			margin-top: 2rem;

		}



		.bank-card{

			position: relative;

		}



		@media screen and (min-width: 481px){



		    .bank-card{

		    	height: 21rem;

		    }



		    .bank-card__side{

		    	border-radius: 10px;

		    	border: 1px solid transparent;

				  position: absolute;

				  width: 65%;

		    }



		    .bank-card__side_front{

		    	background-color: '.(empty(get_option( 'cor_ehtl' )) ? '#f0f0ee' : get_option( 'cor_ehtl' )).';

		    	padding: 5%;

		    	box-shadow: 0 0 10px #545454;

		    	border-color: #a29e97;



		    	top: 0;

		    	left: 0;

		    	z-index: 3;

		    }



		    .bank-card__side_back{

		    	background-color: #e0ddd7;

		    	padding: 20.5% 5% 11%;

		    	box-shadow: 0 0 2rem #f3f3f3;



		    	text-align: right;

		    	border-color: #dad9d6;



				  top: 12%;

		    	right: 0;

		    }



		    .bank-card__side_back:before{

		    	content: "";

		    	width: 100%;

		    	height: 25%;

		    	background-color: '.(empty(get_option( 'cor_ehtl' )) ? '#8e8b85' : get_option( 'cor_ehtl' )).';



		    	position: absolute;

		    	top: 14%;

		    	right: 0;

		    }

		}



		@media screen and (max-width: 480px){



		    .bank-card__side{

		        border: 1px solid #a29e97;

		        background-color: '.(empty(get_option( 'cor_ehtl' )) ? '#f0f0ee' : get_option( 'cor_ehtl' )).';

		        padding-left: 5%;

		        padding-right: 5%;

		    }



		    .bank-card__side_front{

		        border-radius: 10px 10px 0 0;

		        border-bottom: none;

		        padding-top: 5%;

		    }



		    .bank-card__side_back{

		        border-radius: 0 0 10px 10px;

		        border-top: none;

		        padding-bottom: 5%;

		    }

		}



		.bank-card__inner{

			margin-bottom: 4%;

		}



		.bank-card__inner:last-child{

			margin-bottom: 0;

		}



		.bank-card__label{

			display: inline-block;

			vertical-align: middle;

		}



		.bank-card__label_holder, .bank-card__label_number{

			width: 100%;

		}



		@media screen and (min-width: 481px){



		    .bank-card__month, .bank-card__year, .bank-card__operadora{

		        width: 25%;

		    }

		}



		@media screen and (max-width: 480px){



		    .bank-card__month, .bank-card__year, .bank-card__operadora{

		        width: 48%;

		    }

		}



		@media screen and (min-width: 481px){



		    .bank-card__cvc{

		        width: 25%;

		    }

		}



		@media screen and (max-width: 480px){



		    .bank-card__cvc{

		        width: 100%;

		        margin-top: 4%;

		    }

		}



		.bank-card__hint{

			position: absolute;

			left: -9999px;

		}



		.bank-card__caption{

			text-transform: uppercase;

			font-size: 1.1rem;

		  margin-left: 1%;

		}



		.bank-card__field{

			box-sizing: border-box;

			border: 1px solid #cecece !important;

			width: 100%;

			height: 44px;

			padding: 1rem;

			font-family: inherit;

			font-size: 100%;

		}



		.bank-card__field:focus{

			outline: none;

			border-color: #fdde60;

		}



		.bank-card__separator{

			font-size: 3.2rem;

			color: #c4c4c3;



			margin-left: 3%;

			margin-right: 3%;

			display: inline-block;

			vertical-align: middle;

		}



		@media screen and (max-width: 480px){



		    .bank-card__separator{

		        display: none;

		    }

		}



		@media screen and (min-width: 481px){



		    .bank-card__footer{

		        background-image: url("https://stas-melnikov.ru/demo-icons/mastercard-colored.svg"), url("https://stas-melnikov.ru/demo-icons/visa-colored.svg");

		        background-repeat: no-repeat;

		        background-position: 78% 50%, 100% 50% ;

		    }

		}



		@media screen and (max-width: 480px){



		    .bank-card__footer{

		        display: flex;

		        justify-content: space-between;

		    }

		}



		.payment-card__button{



			background-color: #ada093;

			transition: background-color .4s ease-out;



			border-radius: 5px;

			border: 3px solid transparent;

			cursor: pointer;

			padding: 1rem 6.5rem;



			font-size: 100%;

			font-family: inherit;

			color: #fff;

		}



		.payment-card__button:focus{

			outline: none;

			border-color: #fdde60;

		}



		.payment-card__button:hover, .payment-card__button:focus{

			background-color: #8e8b85;

		}



		.demo{

			margin-top: 30px;

		}



	</style>';



	$retorno .= '<input type="hidden" id="type_reserva_ehtl" value="'.get_option('type_reserva_ehtl').'">';



	$retorno .= '<div class="container-fluid" style="margin: 40px 0">

		<div class="row">

			<div class="col-lg-12 col-12">

				<h4 class="h4principal"><strong>Complete seus dados para finalizar a <span id="hTextOrder">'.(get_option('type_reserva_ehtl') == 2 ? 'reserva' : 'solicitação').'</span>!</strong></h4>

			</div>

		</div>

		<div class="row" id="rowPrincipal">



			<div class="col-lg-4 col-12 col-xs-12 show-mobile"> 



				<h5 style="color:#333">Detalhe do pagamento</h5>



				<div class="resume-price row-is-loading" style="min-height:155px;"> 

					<div class="row mb-2">

						<div class="col-lg-8 col-8">

							<p class="data_price data_order"> </p>

						</div>

						<div class="col-lg-4 col-4">

							<p class="value_without_tax value_price"> </p>

						</div>

					</div>

					<div class="row">

						<div class="col-lg-8 col-8">

							<p class="data_price">Impostos, taxas e encargos</p>

						</div>

						<div class="col-lg-4 col-4">

							<p class="value_price tax"> </p>

						</div>

					</div>

					<div class="row">

						<div class="col-lg-12 col-12">

							<hr>

						</div>

					</div>

					<div class="row">

						<div class="col-lg-6 col-8">

							<p class="data_total_price">TOTAL</p>

						</div>

						<div class="col-lg-6 col-4">

							<p class="value_total_price value_total"> </p>

						</div>

					</div>

				</div>



				<h5 style="color:#333">Detalhe da reserva</h5>



				<div class="row rowContact">

					<div class="col-lg-12 col-12">



						<div class="detail row-is-loading" style="min-height:306px;">

							<div class="row">

								<div class="col-lg-12 col-12">

									<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-hotel.png" style="height: 29px;margin-bottom: 6px;"> 

									<h5 class="title-hotel"> </h5>

									<p class="star-hotel"> </p> 

									<p class="address-hotel"> </p>

								</div>

							</div>

							<div class="row">

								<div class="col-lg-6 col-6 date-hotel">

									<label class="">Check-in</label>

									<br>

									<strong class="checkin"> </strong>

									<br>

									<span>14h00</span>

								</div>

								<div class="col-lg-6 col-6 date-hotel">

									<label class="">Check-out</label>

									<br>

									<strong class="checkout"> </strong>

									<br>

									<span>12h00</span>

								</div>

							</div>

							<div class="row">

								<div class="col-lg-12 col-12">

									<hr>

								</div>

							</div>

							<div class="row detailRoom">

								<div class="col-lg-12 col-12">

									<p class="detail_trip">

										 

									</p>

									<p class="name_room">

										 

									</p>

								</div>

							</div>

						</div>

					</div>

				</div>



			</div>



			<div class="col-lg-8 col-12 col-xs-12">



				<div class="row rowContact">

					<div class="col-lg-12 col-12">



						<div class="contact"> 

							<div class="row">

								<div class="col-lg-12 col-12">

									<h5>Dados do titular</h5>

								</div>

							</div>

							<div class="row">

								<div class="col-lg-6 col-12">

									<label>Nome completo</label>

									<div class="input-group mb-4">

									  	<div class="input-group-prepend">

									    	<i class="fa fa-user"></i>

									  	</div>

									  	<input type="text" class="form-control" placeholder="" aria-label="Insira seu nome" id="nomeTitular" aria-describedby="basic-addon1" autocomplete="off">

									</div>

								</div>



								<div class="col-lg-6 col-12">

									<label>E-mail</label>

									<div class="input-group mb-4">

									  	<div class="input-group-prepend">

									    	<i class="fa fa-envelope"></i>

									  	</div>

									  	<input type="text" class="form-control" placeholder="" aria-label="Insira seu e-mail" id="emailTitular" aria-describedby="basic-addon1" autocomplete="off">

									</div>

								</div>



								<div class="col-lg-6 col-12">

									<label>Celular</label>

									<div class="input-group mb-4">

									  	<div class="input-group-prepend">

									    	<i class="fab fa-whatsapp"></i>

									  	</div>

									  	<input type="text" class="form-control" placeholder="" aria-label="Insira seu celular" id="celularTitular" aria-describedby="basic-addon1" autocomplete="off">

									</div>

								</div>



								<div class="col-lg-6 col-12">

									<label>CPF</label>

									<div class="input-group mb-4">

									  	<div class="input-group-prepend">

									    	<i class="fa fa-cog"></i>

									  	</div>

									  	<input type="text" class="form-control" placeholder="" aria-label="Insira seu CPF" id="cpfTitular" aria-describedby="basic-addon1" autocomplete="off">

									</div>

								</div>

							</div> 

						</div>



						<div class="guests"> 

							<div class="row">

								<div class="col-lg-12 col-12">

									<h5>Dados dos hóspedes</h5>

								</div>

							</div>



							<div id="set_room">



								<div class="row rowGuest"> 

									<div class="col-lg-12 col-12">

										<strong class="qt">Quarto 1</strong>

										<br>

										<br>

										<p class="guest">Adulto 1</p> 



										<div class="row">

											<div class="col-lg-4 col-12">

												<label>Nome</label>

												<div class="input-group mb-4">

												  	<div class="input-group-prepend">

												    	<i class="fa fa-user"></i>

												  	</div>

												  	<input type="text" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1">

												</div>

											</div>

											<div class="col-lg-4 col-12">

												<label>Sobrenome</label>

												<div class="input-group mb-4">

												  	<div class="input-group-prepend">

												    	<i class="fa fa-user"></i>

												  	</div>

												  	<input type="text" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1">

												</div>

											</div> 

											<div class="col-lg-4 col-12">

												<label>Nascimento</label>

												<div class="input-group mb-4">

												  	<div class="input-group-prepend">

												    	<i class="fa fa-calendar"></i>

												  	</div>

												  	<input type="text" class="form-control" placeholder="" aria-label="Insira seu nome" aria-describedby="basic-addon1">

												</div>

											</div>

										</div> 



									</div> 

								</div> 



							</div>



						</div>';



						if(get_option('type_reserva_ehtl') == 2){

							$retorno .= '<div class="pay"> 

								<div class="row">

									<div class="col-lg-12 col-12">

										<h5>Dados de pagamento</h5>

									</div>

								</div>



								<div class="demo">

									<form class="payment-card">

										<div class="bank-card">

											<div class="bank-card__side bank-card__side_front">

												<div class="bank-card__inner">

													<label class="bank-card__label bank-card__label_holder">

														<span class="bank-card__hint">Nome do titular</span>

														<input type="text" class="bank-card__field" placeholder="Nome do titular" pattern="[A-Za-z, ]{2,}" id="holder-card" required>

													</label>

												</div>

												<div class="bank-card__inner">

													<label class="bank-card__label bank-card__label_number">

														<span class="bank-card__hint">Número do cartão</span>

														<input type="text" class="bank-card__field" placeholder="Número do cartão" pattern="[0-9]{16}" id="number-card" onfocusout="select_credit_card()"  required>

													</label>

												</div> 

												<div class="bank-card__inner bank-card__footer">

													<label class="bank-card__label bank-card__month">

														<span class="bank-card__hint">Mês</span>

														<input type="text" class="bank-card__field" placeholder="MM" maxlength="2" pattern="[0-9]{2}" id="mm-card" name="mm-card" required>

													</label>

													<span class="bank-card__separator">/</span>

													<label class="bank-card__label bank-card__year">

														<span class="bank-card__hint">Ano</span>

														<input type="text" class="bank-card__field" placeholder="YYYY" maxlength="2" pattern="[0-9]{2}" id="year-card" name="year-card" required>

													</label>

													<label class="bank-card__label bank-card__operadora">

														 

													</label>

												</div>

											</div>

											<div class="bank-card__side bank-card__side_back">

												<div class="bank-card__inner">

													<label class="bank-card__label bank-card__cvc">

														<span class="bank-card__hint">CVC</span>

														<input type="text" class="bank-card__field" placeholder="CVC" maxlength="3" pattern="[0-9]{3}" name="cvc-card" id="cvc-card" required>

													</label>

												</div>

											</div>

										</div> 

									</form>

								</div>



								<label><strong>Parcelamento:</strong></label>

								<select class="form-control" id="installments">



								</select>

							</div>'; 



							$retorno .= '<div class="address"> 

								<div class="row">

									<div class="col-lg-12 col-12">

										<h5>Dados de faturamento</h5>

									</div>

								</div>

								<div class="row">

									<div class="col-lg-6 col-12">

										<label>CEP</label>

										<div class="input-group mb-4">

											<div class="input-group-prepend">

											<i class="fa fa-map"></i>

											</div>

											<input type="text" class="form-control" placeholder="" aria-label="Insira seu CEP" aria-describedby="basic-addon1" id="cep" autocomplete="off">

										</div>

									</div>

								</div>



								<div class="row">

									<div class="col-lg-9 col-12">

										<label>Endereço</label>

										<div class="input-group mb-4">

											<div class="input-group-prepend">

											<i class="fa fa-house-user"></i>

											</div>

											<input type="text" class="form-control" placeholder="" aria-label="Insira seu endereço" id="endereco" aria-describedby="basic-addon1" autocomplete="off">

										</div>

									</div>



									<div class="col-lg-3 col-12">

										<label>Número</label>

										<div class="input-group mb-4">

											<div class="input-group-prepend">

											#

											</div>

											<input type="text" class="form-control" placeholder="" aria-label="Insira o número" id="numero" aria-describedby="basic-addon1" autocomplete="off">

										</div>

									</div>



									<div class="col-lg-12 col-12">

										<label>Complemento</label>

										<div class="input-group mb-4">

											<div class="input-group-prepend">

											<i class="fa fa-info"></i>

											</div>

											<input type="text" class="form-control" placeholder="" aria-label="Insira o complemento" id="complemento" aria-describedby="basic-addon1" autocomplete="off">

										</div>

									</div>



									<div class="col-lg-4 col-12">

										<label>Bairro</label>

										<div class="input-group mb-4">

											<div class="input-group-prepend">

											<i class="fa fa-warehouse"></i>

											</div>

											<input type="text" class="form-control" placeholder="" aria-label="Insira seu bairro" id="bairro" aria-describedby="basic-addon1" autocomplete="off">

										</div>

									</div>



									<div class="col-lg-4 col-12">

										<label>Cidade</label>

										<div class="input-group mb-4">

											<div class="input-group-prepend">

											<i class="fa fa-building"></i>

											</div>

											<input type="text" class="form-control" placeholder="" aria-label="Insira a cidade" id="cidade" aria-describedby="basic-addon1" autocomplete="off">

										</div>

									</div>



									<div class="col-lg-4 col-12">

										<label>Estado</label>

										<div class="input-group mb-4">

											<div class="input-group-prepend">

											<i class="fa fa-flag"></i>

											</div>

											<input type="text" class="form-control" placeholder="" aria-label="Insira o estado" id="estado" aria-describedby="basic-addon1" autocomplete="off">

										</div>

									</div>

								</div> 

							</div>';

						

						}



						$retorno .= '<a onclick="send_order('.get_option('type_reserva_ehtl').')"><button class="btn btnSelect show-mobile">Finalizar '.(get_option('type_reserva_ehtl') == 2 ? 'reserva' : 'solicitação').'</button></a>



					</div>

				</div>



				<div class="row certiSign">

					<div class="col-lg-8 col-12">

						<p>

							<i class="fa fa-lock"></i> <strong>Este site é um site seguro.</strong>

						</p>

						<p>

							Utilizamos conexões seguras para proteger sua informação.

						</p>

					</div>

					<div class="col-lg-4 col-12">

						<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/logo-ssl.png" style=""> 

					</div>

				</div>



			</div>



			<div class="col-lg-4 col-12 col-xs-12 show-desktop"> 



				<h5 style="color:#333">Detalhe do pagamento</h5>



				<div class="resume-price row-is-loading" style="min-height:155px;"> 

					<div class="row mb-2">

						<div class="col-lg-8 col-8">

							<p class="data_price data_order"> </p>

						</div>

						<div class="col-lg-4 col-4">

							<p class="value_without_tax value_price"> </p>

						</div>

					</div>

					<div class="row">

						<div class="col-lg-8 col-8">

							<p class="data_price">Impostos, taxas e encargos</p>

						</div>

						<div class="col-lg-4 col-4">

							<p class="value_price tax"> </p>

						</div>

					</div>

					<div class="row">

						<div class="col-lg-12 col-12">

							<hr>

						</div>

					</div>

					<div class="row">

						<div class="col-lg-6 col-8">

							<p class="data_total_price">TOTAL</p>

						</div>

						<div class="col-lg-6 col-4">

							<p class="value_total_price value_total"> </p>

						</div>

					</div>

				</div>



				<h5 style="color:#333">Detalhe da reserva</h5>



				<div class="row rowContact">

					<div class="col-lg-12 col-12">



						<div class="detail row-is-loading" style="min-height:306px;">

							<div class="row">

								<div class="col-lg-12 col-12">

									<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-hotel.png" style="height: 29px;margin-bottom: 6px;"> 

									<h5 class="title-hotel"> </h5>

									<p class="star-hotel"> </p> 

									<p class="address-hotel"> </p>

								</div>

							</div>

							<div class="row">

								<div class="col-lg-6 col-6 date-hotel">

									<label class="">Check-in</label>

									<br>

									<strong class="checkin"> </strong>

									<br>

									<span>14h00</span>

								</div>

								<div class="col-lg-6 col-6 date-hotel">

									<label class="">Check-out</label>

									<br>

									<strong class="checkout"> </strong>

									<br>

									<span>12h00</span>

								</div>

							</div>

							<div class="row">

								<div class="col-lg-12 col-12">

									<hr>

								</div>

							</div>

							<div class="row detailRoom">

								<div class="col-lg-12 col-12">

									<p class="detail_trip">

										 

									</p>

									<p class="name_room">

										 

									</p>

								</div>

							</div>

						</div>

					</div>

				</div>



				<a onclick="send_order('.get_option('type_reserva_ehtl').')"><button class="btn btnSelect show-desktop">Finalizar '.(get_option('type_reserva_ehtl') == 2 ? 'reserva' : 'solicitação').'</button></a> 



			</div>

		</div>

	</div>';



	$retorno .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>';

	$retorno .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" crossorigin="anonymous"></script>

	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>';	 

	return $retorno;

}



add_shortcode('TTBOOKING_CONFIRM_RESERVA', 'shortcode_confirm_reserva');  



function shortcode_confirm_reserva(){

	$retorno = '';



	$retorno .= ' 

		<link rel="preconnect" href="https://fonts.googleapis.com">

		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

		<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> '; 



	$retorno .= '<style type="text/css">

 		 br{

 		 	display: none;

 		 }

	</style>'; 



	$logo = esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] );

 	

 	$tipoReserva = 'solicitação';

	if(get_option( 'type_reserva_ehtl' ) == 2){

		$htmlAdicional = '<br style="display: block !important"> <span>Número de confirmação: '.$_GET['order'].'</span>';

		$tipoReserva = 'reserva'; 

	}

	$retorno .= '<input type="hidden" id="type_reserva" value="'.get_option( 'type_reserva_ehtl' ).'">';
	$retorno .= '<input type="hidden" id="order" value="'.$_GET['order'].'">';
	$retorno .= '<input type="hidden" id="plugin_dir_url" value="'.plugin_dir_url( __FILE__ ).'">';
	$retorno .= '<input type="hidden" id="color_ehtl" value="'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).'">';

	$retorno .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

		<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">

	    	<head>

		        <meta name="viewport" content="width=device-width" />

		        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

		        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>

		        <style type="text/css">  @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700); h2 { text-align: center; } p { font-size: 13px; } input { display: none; visibility: hidden; }

		            label {

			            display: block;

			            letter-spacing: 2px;

			            color: #b58952;

			            text-align: justify;

			            font-size: 14px;

			            font-weight: 700;

			            width: 96%;

		            }

		            label:hover {

			            color: #b58952;

			            text-decoration: underline;

		            }

		            label::after {

			            font-weight: bold;

			            font-size: 17px;

			            content: "-";

			            vertical-align: text-top;

			            display: inline-block;

			            float: right;

		            }

		            #expand {

			            height: 0px;

			            overflow: hidden;

			            transition: height 0.5s;

			            color: #000;

		            }

		            #toggle:checked ~ #expand {

		            	height: 90px;

		            }

		            #toggle:checked ~ label::after {

		            	content: "+";

		            }

		            @media (min-width: 961px){

			            .larguraTabel{

			            	width: 508px;

			            }

			            .alturaYoutube{

			            	padding-top: 14px !important;

			            }

		            }

		            @media (max-width: 960px){

		            	.alturaYoutube{

		            		padding-top: 36px;

		            	}

		            }

		        </style>

	       	</head>

	        <table align="center" border="0" cellpadding="0" class="larguraTabel" cellspacing="0" style="border-collapse:collapse;border: none;width: 640px;margin: 0 auto;" >

	            <tbody style="background-color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';">

	                <tr>';

		                if(!empty($logo)){

		                    $retorno .= '<td align="center" height="0" style="width:35%;border: none;" ><img src="https://traveltec.com.br/wp-content/uploads/2021/08/Logotipo-Pequeno.png" style=""></td>';

		                }

	                    $retorno .= '<td align="center" height="0" style="border: none;word-break: break-word;font-family:\'Montserrat\';color: #fff;padding: 20px;font-size: 11px;text-align: right;" ><strong>SEU PEDIDO FOI RECEBIDO COM SUCESSO!</strong> '.$htmlAdicional.'</td>

	                </tr>

	            </tbody>

	        </table>

	        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" >

	            <tbody style="background-color: #ddd;">

	                <tr>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:12px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '

	                            	<p style="margin: 0">

	                            		<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-check-round.png" style="    display: inline-flex;height: 21px;margin-right: 5px;"> <small style="font-size: 13px;font-weight: 600;">Agradecemos sua '.$tipoReserva.'!</small> <h6 style="margin: 0;font-weight: 700;">Sua '.$tipoReserva.' para <span id="local_reserva"> </span> está confirmada.</h6>

	                            	</p>



	                            	<p style="margin: 5px 0">

	                            		<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-check.png" style="    display: inline-flex;margin-right: 5px;"> <strong class="hotel_reserva"> </strong> estará à sua espera em <strong id="checkin_reserva"> </strong>.

	                            	</p>



	                            	<p style="margin: 5px 0">

	                            		<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-check.png" style="    display: inline-flex;margin-right: 5px;"> <span id="info_payment">Entraremos em contato para cuidar do pagamento.</span>

	                            	</p>



	                            	<p style="margin: 5px 0" id="data_cancel" style="display:none">

	                            		<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/icon-check.png" style="    display: inline-flex;margin-right: 5px;"> 	<span id="info_cancelamento">Você pode cancelar até 14 de agosto de 2020, às 18:00 [-03].</span>

	                            	</p> 

	                         </td>

	                     </tr>

	                     <tr>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:12px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '

	                            	<h5 class="hotel_reserva" style="margin: 14px 0;color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';font-size: 18px;font-weight:600"><strong> </strong></h5>



	                            	<p style="margin: 5px 0;font-size:13px;" id="endereco_hotel">

	                            		 

	                            	</p>



	                            	<p style="margin: 5px 0" id="mapa_hotel">

	                            		

	                            	</p>  

	                         </td>

	                     </tr>

	            </tbody>

	        </table>

	        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" >

	            <tbody style="background-color: #ddd;">

	                     <tr style="border-bottom: 1px solid #f0f0f0;">

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<strong>Sua reserva</strong>

	                         </td>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<span id="desc_dia_room_reserva"> </span>

	                         </td>

	                     </tr>

	                     <tr style="border-bottom: 1px solid #f0f0f0;">

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<strong>Sua reserva é para</strong>

	                         </td>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<span id="desc_sua_reserva_para"> </span>

	                         </td>

	                     </tr>

	                     <tr style="border-bottom: 1px solid #f0f0f0;">

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<strong>Entrada</strong>

	                         </td>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<span id="desc_sua_reserva_checkin"> </span>

	                         </td>

	                     </tr>

	                     <tr style="">

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<strong>Saída</strong>

	                         </td>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<span id="desc_sua_reserva_checkout"> </span>

	                         </td>

	                     </tr>



	            </tbody>

	        </table>



	        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" >

	            <tbody style="background-color: #ddd;">

	                     <tr style="border-bottom: 1px solid #f0f0f0;">

	                    <td align="center" height="" style="font-family:\'Montserrat\';word-break: break-word;background-color: '.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';text-align: justify;border: none;color: #fff;padding: 20px;" valign="top"  >

	                    		<p style="margin-bottom:5px;font-weight:600;font-size:14px;" id="desc_room_reserva"> </p>

	                    		<p style="margin-bottom:5px;font-weight:600;font-size:14px;" id="desc_taxa_reserva"></p>

	                    		<p style="margin:5px 0;font-weight:600;font-size:19px;">Total <span style="float:right;" id="price_total"> </span></p>



	                    		<p style="margin:5px 0; font-size:13px;">

	                    			Aguarde entrarmos em contato para cuidarmos do pagamento.

	                    		</p>

	                    		<p style="margin:5px 0; font-size:13px;">

									Por favor, observe que pedidos adicionais (por exemplo, cama extra) não estão incluídos neste valor.

	                    		</p>

								<p style="margin:5px 0; font-size:13px;">

									O preço total mostrado é o valor que você pagará à acomodação. Não cobramos dos hóspedes nenhuma taxa de reserva, administrativa ou de qualquer outro tipo.

	                    		</p>

								<p style="margin:5px 0; font-size:13px;">

									Se você cancelar, impostos aplicáveis ainda podem ser cobrados pela acomodação.

	                    		</p>

								<p style="margin:5px 0; font-size:13px;">

									Se você não comparecer sem cancelar com antecedência, a acomodação poderá cobrar o valor total da reserva.

	                    		</p>

	                         </td>

	                     </tr>



	            </tbody>

	        </table>



	        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" >

	            <tbody style="background-color: #ddd;"> 

	                     <tr>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;padding: 0px 14px;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:12px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '

	                            	<h5 class="" style="margin: 14px 0;color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';font-size: 18px;"><strong>Informações sobre os quartos</strong></h5> 

	                         </td>

	                     </tr>

	            </tbody>

	        </table>

	        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" >

	            <tbody style="background-color: #ddd;">

	                     <tr style="border-bottom: 1px solid #f0f0f0;">

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<strong>Nome do titular</strong>

	                         </td>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<span id="desc_titular"> </span>

	                         </td>

	                     </tr>

	                     <tr style="border-bottom: 1px solid #f0f0f0;">

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<strong>Quantidade de quartos</strong>

	                         </td>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<span id="desc_qtd_rooms"> </span>

	                         </td>

	                     </tr>

	                     <tr style="border-bottom: 1px solid #f0f0f0;">

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<strong>Tipo dos quartos</strong>

	                         </td>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<span id="desc_tipo_room"> </span>

	                         </td>

	                     </tr>

	                     <tr style="">

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<strong>Café da manhã</strong>

	                         </td>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '<span id="desc_meal">O café da manhã está incluso</span>

	                         </td>

	                     </tr>



	            </tbody>

	        </table>



	        <table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" >

	            <tbody style="background-color: #ddd;"> 

	                     <tr>

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: justify;border: none;padding: 0px 14px;" valign="top"  >

	                        <font style="font-family:\'Montserrat\', sans-serif;font-size:12px;color:#666666;margin:1em 0">

	                            '; 

	                            $retorno .= '

	                            	<h5 class="" style="margin: 14px 0;color:'.(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' )).';font-size: 18px;"><strong>Pagamento</strong></h5> 

	                         </td>

	                     </tr>

	            </tbody>

	        </table>';



	        if(get_option( 'type_reserva_ehtl' ) == 2){

		        $retorno .= '<table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" id="payment_card">

		            <tbody style="background-color: #ddd;">

		                     <tr style="border-bottom: 1px solid #f0f0f0;">

		                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >

		                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

		                            '; 

		                            $retorno .= '<strong>Nome do titular</strong>

		                         </td>

		                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

		                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

		                            '; 

		                            $retorno .= '<span id="desc_titular_card"> </span>

		                         </td>

		                     </tr>

		                     <tr style="border-bottom: 1px solid #f0f0f0;">

		                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >

		                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

		                            '; 

		                            $retorno .= '<strong>Número do cartão</strong>

		                         </td>

		                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

		                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

		                            '; 

		                            $retorno .= '<span id="desc_number_card"> </span>

		                         </td>

		                     </tr>

		                     <tr style="border-bottom: 1px solid #f0f0f0;">

		                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >

		                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

		                            '; 

		                            $retorno .= '<strong>Validade</strong>

		                         </td>

		                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

		                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

		                            '; 

		                            $retorno .= '<span id="desc_validade_card"> </span>

		                         </td>

		                     </tr>

		                     <tr style="">

		                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >

		                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

		                            '; 

		                            $retorno .= '<strong>Parcelas</strong>

		                         </td>

		                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: right;border: none;" valign="top"  >

		                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

		                            '; 

		                            $retorno .= '<span id="desc_parcelas_card"> </span>

		                         </td>

		                     </tr>



		            </tbody>

		        </table>';

		    }else{



		        $retorno .= '<table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" id="payment_agency">

		            <tbody style="background-color: #ddd;">

		                     <tr style=" ">

		                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >

		                        <font style="font-family:\'Montserrat\', sans-serif;font-size:14px;color:#666666;margin:1em 0">

		                            '; 

		                            $retorno .= 'Entraremos em contato para cuidar das informações de pagamento. Mas não se preocupe, sua solicitação foi recebida!

		                         </td> 

		                     </tr> 



		            </tbody>

		        </table>';

	       }



	        $retorno .= '<table align="center" border="0" cellpadding="0" class="larguraTabel" style="border-collapse:collapse;border: none;margin: 0 auto" >

	            <tbody style="background-color: #ddd;">

	                     <tr style="border-top: 1px solid #f0f0f0;">

	                    <td align="center" height="" style="word-break: break-word;background-color: #fff;text-align: left;border: none;" valign="top"  >

	                    	<p style="font-family:\'Montserrat\', sans-serif; color:#666666;text-align:center;font-size:12px;"><a href="https://traveltec.com.br" target="_blank">Travel Tec</a> © 2023. Todos os direitos reservados.</p>

	                    </td>

	                   </tr>

	                  </tbody>

	                  </table>



	        </body>

		</html>';



	$retorno .= ' 

	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script> ';	  

	return $retorno;

}

    
 // define the wp_mail_failed callback



    function action_wp_mail_failed_ehtl($wp_error)



    {



        return error_log(print_r($wp_error, true));



    }







    function wpse27856_set_content_type_ehtl(){



        return "text/html";



    }



    add_filter( 'wp_mail_content_type','wpse27856_set_content_type_ehtl' );







    // add the action



    add_action('wp_mail_failed', 'action_wp_mail_failed_ehtl', 10, 1);


add_action( 'wp_ajax_send_mail_confirmation', 'send_mail_confirmation' ); 
add_action( 'wp_ajax_nopriv_send_mail_confirmation', 'send_mail_confirmation' );

function send_mail_confirmation(){

	$headers = "From: Travel Tec <sac@traveltec.com.br>"; 

	$html = '';

 	$tipoReserva = 'solicitação de cotação';
 	$htmlAdicional = '';

	if($_POST['type_reserva'] == 2){

		$htmlAdicional = '<br style="display: block !important"> <span>Número de confirmação: '.$order.'</span>'; 
		$tipoReserva = 'reserva'; 

	}

	$plugin_dir_url = $_POST['plugin_dir_url'];
	$color_ehtl = $_POST['color_ehtl'];
	$destino = $_POST['destino'];
	$hotel_reserva = $_POST['hotel_reserva'];
	$checkin = $_POST['checkin'];
	$checkout = $_POST['checkout'];
	$irrevocableGuarantee = $_POST['irrevocableGuarantee'];
	$cancellationDeadline = $_POST['cancellationDeadline'];
	$hotelAdressComplete = $_POST['hotelAdressComplete'];
	$diaria = $_POST['diaria'];
	$quartos = $_POST['quartos'];
	$pax = $_POST['pax'];
	$tipo_quarto = $_POST['tipo_quarto'];
	$taxa = $_POST['taxa'];
	$total = $_POST['total'];
	$customer = $_POST['customer'];
	$type_reserva = $_POST['type_reserva'];
	$holder = $_POST['holder'];
	$number = $_POST['number'];
	$month = $_POST['month'];
	$parcelas = $_POST['parcelas'];

	$html .= '<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		    <head>
		        <meta charset="utf-8">
		        <meta name="viewport" content="width=device-width">
		        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		        <meta name="x-apple-disable-message-reformatting">

				<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
		        <title></title>
		        <style>
		            /* What it does: Remove spaces around the email design added by some email clients. */
		            /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
		            html,
		            body {
		                margin: 0 auto !important;
		                padding: 0 !important;
		                height: 100% !important;
		                width: 100% !important;
		                background: #f1f1f1;
		                font-family: \'Montserrat\'
		            }

		            /* What it does: Stops email clients resizing small text. */
		            * {
		                -ms-text-size-adjust: 100%;
		                -webkit-text-size-adjust: 100%;
		            }

		            /* What it does: Centers email on Android 4.4 */
		            div[style*="margin: 16px 0"] {
		                margin: 0 !important;
		            }

		            /* What it does: Stops Outlook from adding extra spacing to tables. */
		            table,
		            td {
		                mso-table-lspace: 0pt !important;
		                mso-table-rspace: 0pt !important;
		            }

		            /* What it does: Fixes webkit padding issue. */
		            table {
		                border-spacing: 0 !important;
		                border-collapse: collapse !important;
		                table-layout: fixed !important;
		                margin: 0 auto !important;
		            }

		            /* What it does: Uses a better rendering method when resizing images in IE. */
		            img {
		                -ms-interpolation-mode: bicubic;
		            }

		            /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
		            a {
		                text-decoration: none;
		            }

		            /* What it does: A work-around for email clients meddling in triggered links. */
		            *[x-apple-data-detectors],
		            /* iOS */
		            .unstyle-auto-detected-links *,
		            .aBn {
		                border-bottom: 0 !important;
		                cursor: default !important;
		                color: inherit !important;
		                text-decoration: none !important;
		                font-size: inherit !important;
		                font-family: inherit !important;
		                font-weight: inherit !important;
		                line-height: inherit !important;
		            }

		            /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
		            .a6S {
		                display: none !important;
		                opacity: 0.01 !important;
		            }

		            /* What it does: Prevents Gmail from changing the text color in conversation threads. */
		            .im {
		                color: inherit !important;
		            }

		            /* If the above doesnt work, add a .g-img class to any image in question. */
		            img.g-img+div {
		                display: none !important;
		            }

		            /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
		            /* Create one of these media queries for each additional viewport size youd like to fix */
		            /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
		            @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
		                u~div .email-container {
		                    min-width: 320px !important;
		                }
		            }

		            /* iPhone 6, 6S, 7, 8, and X */
		            @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
		                u~div .email-container {
		                    min-width: 375px !important;
		                }
		            }

		            /* iPhone 6+, 7+, and 8+ */
		            @media only screen and (min-device-width: 414px) {
		                u~div .email-container {
		                    min-width: 414px !important;
		                }
		            }
		        </style>
		        <style>
		            .primary {
		                background: #17bebb;
		            }

		            .bg_white {
		                background: #ffffff;
		            }

		            .bg_ehtl {
		                background: '.$color_ehtl.';
		            }

		            .bg_light {
		                background: #f7fafa;
		            }

		            .bg_black {
		                background: #000000;
		            }

		            .bg_dark {
		                background: rgba(0, 0, 0, .8);
		            }

		            .email-section {
		                padding: 2.5em;
		            }

		            /*BUTTON*/
		            .btn {
		                padding: 10px 15px;
		                display: inline-block;
		            }

		            .btn.btn-primary {
		                border-radius: 5px;
		                background: #17bebb;
		                color: #ffffff;
		            }

		            .btn.btn-white {
		                border-radius: 5px;
		                background: #ffffff;
		                color: #000000;
		            }

		            .btn.btn-white-outline {
		                border-radius: 5px;
		                background: transparent;
		                border: 1px solid #fff;
		                color: #fff;
		            }

		            .btn.btn-black-outline {
		                border-radius: 0px;
		                background: transparent;
		                border: 2px solid #000;
		                color: #000;
		                font-weight: 700;
		            }

		            .btn-custom {
		                color: rgba(0, 0, 0, .3);
		                text-decoration: underline;
		            }

		            h1,
		            h2,
		            h3,
		            h4,
		            h5,
		            h6 {
		                color: #fff;
		                margin-top: 0;
		                font-weight: 600;
		                font-size: 12px;
		                font-family: \'Montserrat\'
		            }

		            body {
		                font-weight: 400;
		                font-size: 15px;
		                line-height: 1.8;
		                color: rgba(0, 0, 0, .4);
		                font-family: \'Montserrat\'
		            }

		            a {
		                color: #17bebb;
		            }

		            table {}

		            /*LOGO*/
		            .logo h1 {
		                margin: 0;
		            }

		            .logo h1 a {
		                color: #17bebb;
		                font-size: 24px;
		                font-weight: 700;
		            }

		            /*HERO*/
		            .hero {
		                position: relative;
		                z-index: 0;
		            }

		            .hero .text {
		                color: rgba(0, 0, 0, .3);
		            }

		            .hero .text h2 {
		                color: #000;
		                font-size: 14px;
		                margin-bottom: 15px;
		                font-weight: 400;
		                line-height: 1.2;
		            }

		            .hero .text h3 {
		                font-size: 12px;
    					font-weight: 600;
    					color: #000;
		            }

		            .hero .text h2 span {
		                font-weight: 600;
		                color: #000;
		            }

		            /*PRODUCT*/
		            .product-entry {
		                display: block;
		                position: relative;
		                float: left;
		                padding-top: 20px;
		            }

		            .product-entry .text {
		                width: calc(100% - 125px);
		                padding-left: 20px;
		            }

		            .product-entry .text h3 {
		                margin-bottom: 0;
		                padding-bottom: 0;
		            }

		            .product-entry .text p {
		                margin-top: 0;
		            }

		            .product-entry img,
		            .product-entry .text {
		                float: left;
		            }

		            ul.social {
		                padding: 0;
		            }

		            ul.social li {
		                display: inline-block;
		                margin-right: 10px;
		            }

		            /*FOOTER*/
		            .footer {
		                border-top: 1px solid rgba(0, 0, 0, .05);
		                color: rgba(0, 0, 0, .5);
		            }

		            .footer .heading {
		                color: #fff;
		                font-size: 18px;
		                font-weight: 600;
		                font-family: \'Montserrat\';
		            }

		            .footer p {
		                color: #fff;
		                font-size: 14px; 
		                font-family: \'Montserrat\';
		            }

		            .footer ul {
		                margin: 0;
		                padding: 0;
		            }

		            .footer ul li {
		                list-style: none;
		                margin-bottom: 10px;
		            }

		            .footer ul li a {
		                color: rgba(0, 0, 0, 1);
		            }

		            @media screen and (max-width: 500px) {}
		        </style>
		        <meta name="robots" content="noindex, follow"> 
		        <style type="text/css"></style>
		    </head>
		    <body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;">
		        <center style="width: 100%; background-color: #f1f1f1;">
		            <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;"> ‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; </div>
		            <div style="max-width: 600px; margin: 0 auto;" class="email-container">
		                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
		                    <tbody>
		                        <tr>
		                            <td valign="top" class="bg_ehtl" style="padding: 1em 2.5em 1em 2.5em;">
		                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		                                    <tbody>
		                                        <tr>
		                                            <td class="logo" style="text-align: right;text-transform:uppercase;font-size:18px;font-weight:600">
		                                                <h1> Seu pedido foi recebido com sucesso! '.$htmlAdicional.'</h1>
		                                            </td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </td>
		                        </tr>
		                        <tr>
		                            <td valign="middle" class="hero bg_white" style="padding: 2em 0 2em 0;">
		                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		                                    <tbody>
		                                        <tr>
		                                            <td style="padding: 0 2.5em; text-align: left;">
		                                                <div class="text">
		                                                    <h2><img src="cid:icon-check-round" style="height:20px"> Agradecemos sua solicitação!<br>Sua '.$tipoReserva.' para <strong>'.$destino.'</strong> foi recebida.</h2>';

		                                                    if($type_reserva == 2){

		                                                    	$html .= '<h2><img src="cid:icon-check"> <strong>'.$hotel_reserva.'</strong> estará à sua espera em <strong>'.$checkin.'</strong>.</h2>';

		                                                    }else{

		                                                    	$html .= '<h2><img src="cid:icon-check"> Solicitação feita para o hotel <strong>'.$hotel_reserva.'</strong> e dia <strong>'.$checkin.'</strong>.</h2>';

		                                                    } 

		                                                    $html .= '<h2><img src="cid:icon-check"> Entraremos em contato para cuidar do pagamento.</h2>';

		                                                    if($irrevocableGuarantee === "false"){
		                                                    	$html .= '<h2><img src="cid:icon-check"> Após a confirmação, você poderá cancelar até '.$cancellationDeadline.'</h2>';
		                                                    }

		                                                $html .= '</div>
		                                            </td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </td>
		                        </tr>
		                        <tr></tr>
		                    </tbody>
		                </table>

		                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
		                    <tbody> 
		                        <tr>
		                            <td valign="middle" class="hero bg_white" style="padding: 0;">
		                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		                                    <tbody>
		                                        <tr>
		                                            <td style="padding: 0 2.5em; text-align: left;">
		                                                <div class="text">
		                                                    <h2 style="color:'.$color_ehtl.' !important;font-weight:600;font-size:18px !important;font-family: \'Montserrat\' !important;">'.$hotel_reserva.'</h2> 

		                                                	<h3>'.$hotelAdressComplete.'</h3> 

		                                                </div>
		                                            </td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </td>
		                        </tr>
		                        <tr></tr>
		                    </tbody>
		                </table>

		                <table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		                    <tbody>
		                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
		                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Sua reserva</th>
		                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$diaria.', '.$quartos.'</th>
		                        </tr> 
		                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
		                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Sua reserva é para</th>
		                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$pax.'</th>
		                        </tr> 
		                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
		                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Entrada</th>
		                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$checkin.'</th>
		                        </tr> 
		                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
		                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Saída</th>
		                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$checkout.'</th>
		                        </tr> 
		                    </tbody>
		                </table>

		                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
		                    <tbody>
		                        <tr>
		                            <td valign="middle" class="bg_ehtl footer email-section">
		                                <table>
		                                    <tbody>
		                                        <tr>
		                                            <td valign="top" width="100%" style="padding-top: 20px;">
		                                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		                                                    <tbody>
		                                                        <tr>
		                                                            <td style="text-align: left; padding-right: 10px;">
		                                                                <h3 class="heading">
			                                                                '.$quartos.' '.$tipo_quarto.'
			                                                                <br>
			                                                                Taxa de R$ '.$taxa.' inclusa
			                                                                <br>
			                                                                Total <span style="float:right">R$ '.$total.'</span>
		                                                                </h3> 
		                                                                <p> Aguarde entrarmos em contato para cuidarmos do pagamento. </p>

																		<p> Por favor, observe que pedidos adicionais (por exemplo, cama extra) não estão incluídos neste valor. </p>

																		<p> O preço total mostrado é o valor que você pagará à acomodação. Não cobramos dos hóspedes nenhuma taxa de reserva, administrativa ou de qualquer outro tipo. </p>

																		<p> Se você cancelar, impostos aplicáveis ainda podem ser cobrados pela acomodação. </p>

																		<p> Se você não comparecer sem cancelar com antecedência, a acomodação poderá cobrar o valor total da reserva. </p>
		                                                            </td>
		                                                        </tr>
		                                                    </tbody>
		                                                </table>
		                                            </td> 
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </td>
		                        </tr> 
		                    </tbody>
		                </table>

		                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
		                    <tbody> 
		                        <tr>
		                            <td valign="middle" class="hero bg_white" style="padding: 2em 0 0 0;">
		                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		                                    <tbody>
		                                        <tr>
		                                            <td style="padding: 0 2.5em; text-align: left;">
		                                                <div class="text">
		                                                    <h2 style="color:'.$color_ehtl.' !important;font-weight:600;font-size:18px !important;font-family: \'Montserrat\' !important;">Informações sobre os quartos</h2>  

		                                                </div>
		                                            </td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </td>
		                        </tr>
		                        <tr></tr>
		                    </tbody>
		                </table>

		                <table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		                    <tbody>
		                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
		                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Titular</th>
		                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$customer.' <br> '.$_POST['email_order'].' <br> <strong>Celular:</strong> '.$_POST['tel_order'].' <br> <strong>CPF:</strong> '.$_POST['cpf_order'].'</th>
		                        </tr> 
		                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
		                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Quantidade de quartos	</th>
		                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$quartos.'</th>
		                        </tr> 
		                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
		                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Tipo dos quartos	</th>
		                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$tipo_quarto.'</th>
		                        </tr> 
		                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
		                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Café da manhã	</th>
		                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">O café da manhã está incluso</th>
		                        </tr> 
		                    </tbody>
		                </table>

		                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
		                    <tbody> 
		                        <tr>
		                            <td valign="middle" class="hero bg_white" style="padding: 2em 0 1em 0;">
		                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		                                    <tbody>
		                                        <tr>
		                                            <td style="padding: 0 2.5em; text-align: left;">
		                                                <div class="text">
		                                                    <h2 style="color:'.$color_ehtl.' !important;font-weight:600;font-size:18px !important;font-family: \'Montserrat\' !important;">Pagamento</h2>  

		                                                </div>
		                                            </td>
		                                        </tr>
		                                    </tbody>
		                                </table>
		                            </td>
		                        </tr>
		                        <tr></tr>
		                    </tbody>
		                </table>';

		                if($type_reserva == 2){
			                $html .= '<table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
			                    <tbody>
			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
			                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Nome do titular</th>
			                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$holder.'</th>
			                        </tr> 
			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
			                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Número do cartão</th>
			                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$number.'</th>
			                        </tr> 
			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
			                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Validade</th>
			                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$month.'</th>
			                        </tr> 
			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
			                            <th width="50%" style="text-align:left;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: \'Montserrat\';font-weight: 600;">Parcelas</th>
			                            <th width="50%" style="text-align:right;padding:1.5em 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">'.$parcelas.'</th>
			                        </tr> 
			                    </tbody>
			                </table>';
			            }else{
			                $html .= '<table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
			                    <tbody>
			                        <tr style="border-bottom: 1px solid rgba(0,0,0,.05);"> 
			                            <th width="100%" style="text-align:left;padding:0 2.5em;color:#000;padding-bottom:20px;font-family: Montserrat;font-weight: 400;">Entraremos em contato para cuidar das informações de pagamento. Mas não se preocupe, sua solicitação foi recebida!</th>
			                        </tr>  
			                    </tbody>
			                </table>';
			            }

		                $html .= '<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
		                    <tbody> 
		                        <tr>
		                            <td class="bg_white" style="text-align: center;">
		                                <p style="font-family: \'Montserrat\' !important;"><a href="https://traveltec.com.br" target="_blank">Travel Tec</a> © '.date("Y").'. Todos os direitos reservados.</a>
		                                </p>
		                            </td>
		                        </tr>
		                    </tbody>
		                </table>

		            </div>
		        </center> 
		    </body>
		</html>';

		/* Usage */

		/* Set mail parameters */
		$to = 'raabe@montenegroev.com.br';
		$subject = "Hospedagem - Nova cotação";
		$body = $html;
		$headers = "Content-type: text/html";
		$my_attachments = [
		    [
		        "cid" => "icon-check-round", /* used in email body */
		        "path" => plugin_dir_path(__FILE__) . 'includes/assets/img/icon-check-round.png',
		    ],
		    [
		        "cid" => "icon-check", /* used in email body */
		        "path" => plugin_dir_path(__FILE__) . 'includes/assets/img/icon-check.png',
		    ], 
		];

		$custom_mailer = new Custom_Mailer();
		$custom_mailer->send($_POST['email_order'], 'Pedido efetuado com sucesso!', $body, $headers, $my_attachments); 
		//$custom_mailer->send(get_option( 'admin_email' ), $subject, $body, $headers, $my_attachments); 

}



global $wpdb;



$check_page_exist = get_page_by_title('Hospedagens', 'OBJECT', 'page');  



if(empty($check_page_exist)) {



    $wpdb->insert('wp_posts', array( 

        'comment_status' => 'close', 

        'ping_status'    => 'close', 

        'post_author'    => 1, 

        'post_title'     => ucwords('Hospedagens'), 

        'post_name'      => 'hotels', 

        'post_status'    => 'publish', 

        'post_content'   => '[TTBOOKING_RESULTADOS_RESERVA]', 

        'post_type'      => 'page' 

    ));



}



$check_page_exist = get_page_by_title('Detalhe da Hospedagem', 'OBJECT', 'page');  



if(empty($check_page_exist)) {



    $wpdb->insert('wp_posts', array( 

        'comment_status' => 'close', 

        'ping_status'    => 'close', 

        'post_author'    => 1, 

        'post_title'     => ucwords('Detalhe da Hospedagem'), 

        'post_name'      => 'hotels-detail', 

        'post_status'    => 'publish', 

        'post_content'   => '[TTBOOKING_DETALHE_RESULTADOS_RESERVA]', 

        'post_type'      => 'page' 

    ));



}



$check_page_exist = get_page_by_title('Finalizar Pedido', 'OBJECT', 'page');  



if(empty($check_page_exist)) {



    $wpdb->insert('wp_posts', array( 

        'comment_status' => 'close', 

        'ping_status'    => 'close', 

        'post_author'    => 1, 

        'post_title'     => ucwords('Finalizar pedido'), 

        'post_name'      => 'order-hotels', 

        'post_status'    => 'publish', 

        'post_content'   => '[TTBOOKING_CHECKOUT_RESERVA]', 

        'post_type'      => 'page' 

    ));



}



$check_page_exist = get_page_by_title('Confirmação Pedido', 'OBJECT', 'page');  



if(empty($check_page_exist)) {



    $wpdb->insert('wp_posts', array( 

        'comment_status' => 'close', 

        'ping_status'    => 'close', 

        'post_author'    => 1, 

        'post_title'     => ucwords('Confirmação Pedido'), 

        'post_name'      => 'confirm-order', 

        'post_status'    => 'publish', 

        'post_content'   => '[TTBOOKING_CONFIRM_RESERVA]', 

        'post_type'      => 'page' 

    ));



} 



    add_action('admin_menu', 'menu_ehtl');







    function menu_ehtl() { 







  add_menu_page( 







      'Hospedagem E-HTL', 







      'TT - Ehtl', 







      'edit_posts', 







      'ttehtl', 







      'gerador_de_conteudo_ehtl', 







      'dashicons-open-folder' 







     );







}







 







     







    function gerador_de_conteudo_ehtl() { ?>



        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?ver=1.0">



        <style>

        	#wpcontent{

        		background-color: #f0f0f0;

        		padding: 0;

    			font-family: 'Montserrat';

        	}

        	#wpfooter{

        		display: none;

        	}

        	.header{

        		padding: 25px 30px;

        	}

        	.content{

        		padding: 25px 0;

        	}

        	.content{

        		min-height: 200px;

        	}

        	.footer{

    			padding: 20px; 

    			background-color: #fff;

    			position: absolute;

    			bottom: 0;

    			width: 100%;

        	}

        	.header h2{

        		font-size: 36px;

    			font-weight: 400;

    			font-family: 'Montserrat';

        	}

        	.header p{

        		font-family: 'Montserrat';

        		font-size: 14px;

        		margin-bottom: 0;

        	}

        	.footer p{ 

			    font-family: 'Montserrat';

			    font-size: 11px; 

        	}

        	.footer p.copyright, .footer p.links{

        		margin-bottom: 7px; 

        	}

        	.footer p.redes{

        		margin-bottom: 0px; 

        	}

        	.footer p.links .divisor{

        		font-weight: 600;

    			color: #858585;

    			margin: 0px 4px;

        	}

        	.footer p.copyright{ 

			    font-weight: 600;

			    color: #858585;

        	}

        	.footer p.redes i{

        		font-size: 16px;

    			color: #858585;

    			margin-right: 4px;

        	}



        	.nav-item{

        		margin-bottom: -1px;

        	}

        	.nav-link{

        		border: none;

			    padding: 12px 25px;

			    font-size: 14px;

			    font-weight: 600;

			    font-family: Montserrat;

        	}

        	.nav-tabs{

        		border: none;

        		padding: 0px 30px;

        	}

        	.nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover, .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{

        		border: 0;

        	}

        	.tab-content{

        		padding: 45px 30px;

        		background-color: #fff;

        	}



        	.copy-button {

			    height: 36px;

			    margin-left: -4px;

			    margin-top: -2px;

			    border-radius: 0px 5px 5px 0px;

			    margin-right: 5px;

			}



			.tip {

			    background-color: #263646;

			    padding: 0 14px;

			    line-height: 27px;

			    position: absolute;

			    border-radius: 4px;

			    z-index: 100;

			    color: #fff;

			    font-size: 12px;

			    animation-name: tip;

			    animation-duration: .6s;

			    animation-fill-mode: both

			}



			.tip:before {

			    content: "";

			    background-color: #263646;

			    height: 10px;

			    width: 10px;

			    display: block;

			    position: absolute;

			    transform: rotate(45deg);

			    top: -4px;

			    left: 17px

			}



			#copied_tip {

			    animation-name: come_and_leave;

			    animation-duration: 1s;

			    animation-fill-mode: both;

			    bottom: -35px;

			    left: 2px

			}



			.text-line {

				font-weight: 600;

			    background-color: #d5d5d5;

			    padding: 8px;

			    border-radius: 5px 0px 0px 5px;

			    margin-left: 5px;

			}



			.btn-check:active+.btn-primary:focus, .btn-check:checked+.btn-primary:focus, .btn-primary.active:focus, .btn-primary:active:focus, .show>.btn-primary.dropdown-toggle:focus{

				box-shadow: none !important;

			}



			.form-label{

				font-size: 14px;

    			font-weight: 600;

			}

			.form-control{

				height: 40px;

    			border: 1px solid #e2e2e2 !important;

    			border-radius: 0 !important;

			}



			.wp-core-ui p .button{

				padding: 10px 20px;

    			font-size: 15px;

			}

        </style>



        <div class="header">

        	<h2>Hospedagem</h2> 

        	<p>Integre facilmente o seu fornecedor de hotéis ao seu site.</p>

        </div>



        <div class="content">



	        <ul class="nav nav-tabs" id="myTab" role="tablist">

	  			<li class="nav-item" role="presentation">

	    			<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Sobre</button>

	  			</li>

	  			<li class="nav-item" role="presentation">

	    			<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Configuração</button>

	  			</li>

	  			<li class="nav-item" role="presentation">

	    			<button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Licenciamento</button>

	  			</li>

			</ul>

			<div class="tab-content" id="myTabContent">

	  			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"> 



	  				<p style="font-size:17px;line-height: 1.8"> O Plugin <strong>Travel Tec - Hospedagem</strong> é um plugin desenvolvido para agências e operadoras de turismo que precisam tratar diárias de hospedagem de fornecedores, com integração ao fornecedor E-htl. </p>



	  				<p style="font-size:17px;line-height: 1.8">Use o shortcode <span class="text-line">[TTBOOKING_MOTOR_RESERVA]</span>  <button onclick="copy('[TTBOOKING_MOTOR_RESERVA]','#copy_button_1')" id="copy_button_1" class="btn btn-sm btn-primary copy-button" data-toggle="tolltip" data-placement="top" tilte="Copiar shortcode">Copiar</button> para adicionar o motor de reserva em qualquer página.</p>

					<?php if ( (shortcode_exists( 'TTBOOKING_MOTOR_RESERVA_FLIGHTS' ) && shortcode_exists( 'TTBOOKING_MOTOR_RESERVA' ) ) || (shortcode_exists( 'TTBOOKING_MOTOR_RESERVA' ) && shortcode_exists( 'TTBOOKING_MOTOR_RESERVA_CARS' ) ) ){ ?>


                        <p style="font-size:17px;line-height: 1.8">Use o shortcode <span class="text-line">[TTBOOKING_ALL_SERVICES]</span>  <button onclick="copy('[TTBOOKING_ALL_SERVICES]','#copy_button_2')" id="copy_button_2" class="btn btn-sm btn-primary copy-button" data-toggle="tolltip" data-placement="top" tilte="Copiar shortcode">Copiar</button> para adicionar o motor de reserva com todos os serviços em qualquer página.</p>
                    <?php } ?>

	  			</div>

	  			<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">



	  				<div class="row">

	  					<div class="col-lg-6 col-12">

	  						<ul class="nav nav-tabs" id="myTabCredencial" role="tablist" style="padding: 0px;">

					  			<li class="nav-item" role="presentation">

					    			<button class="nav-link active" id="credencial-tab" data-bs-toggle="tab" data-bs-target="#credencial" type="button" role="tab" aria-controls="home" aria-selected="true" style="border: none;background-color: #ebebeb;">Credenciais</button>

					  			</li>

							</ul>

							<div class="tab-content" id="myTabContentCredencial" style="background-color: #ebebeb;height: 355px;">

					  			<div class="tab-pane fade show active" id="credencial" role="tabpanel" aria-labelledby="credencial-tab">  



				  					<div style="height: 190px;">



						  				<div class="mb-3">

											<label for="user_ehtl" class="form-label">Usuário</label>

											<input type="text" class="form-control" id="user_ehtl" name="user_ehtl" value="<?=(empty(get_option( 'user_ehtl' )) ? '' : get_option( 'user_ehtl' ))?>">

										</div>



						  				<div class="mb-3">

											<label for="pass_ehtl" class="form-label">Senha</label>

											<input type="text" class="form-control" id="pass_ehtl" name="pass_ehtl" value="<?=(empty(get_option( 'pass_ehtl' )) ? '' : get_option( 'pass_ehtl' ))?>">

										</div> 



									</div>



									<?php submit_button(); ?> 



					  			</div>

					  		</div>

	  					</div> 



	  					<div class="col-lg-6 col-12">

	  						<ul class="nav nav-tabs" id="myTabEstilo" role="tablist" style="padding: 0px;">

					  			<li class="nav-item" role="presentation">

					    			<button class="nav-link active" id="estilo-tab" data-bs-toggle="tab" data-bs-target="#estilo" type="button" role="tab" aria-controls="home" aria-selected="true" style="border: none;background-color: #ebebeb;">Estilização</button>

					  			</li> 

							</ul>

							<div class="tab-content" id="myTabContentEstilo" style="background-color: #ebebeb;height: 355px;">

					  			<div class="tab-pane fade show active" id="estilo" role="tabpanel" aria-labelledby="estilo-tab"> 



				  					<div style="height: 190px;">



				  						<input type="hidden" id="type_reserva_ehtl" value="<?=get_option( 'type_reserva_ehtl' )?>">



				  						<div class="mb-3">

					  						<div class="form-check form-check-inline">

											  	<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" style="    margin-top: 4px;" <?=(get_option( 'type_reserva_ehtl' ) == 1 ? 'checked' : '')?> onclick="set_type_reserva(1)">

											  	<label class="form-check-label" for="inlineRadio1" style="    font-size: 14px;">Cotação</label>

											</div>

											<div class="form-check form-check-inline">

											  	<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2" style="    margin-top: 4px;" <?=(get_option( 'type_reserva_ehtl' ) == 2 ? 'checked' : '')?> onclick="set_type_reserva(2)">

											  	<label class="form-check-label" for="inlineRadio2" style="    font-size: 14px;">Reserva</label>

											</div> 

											<p style="font-size: 11px;margin: 11px 0px;">Selecione o tipo da solicitação: reserva, para compra online, e cotação, para envio dos dados por e-mail.</p>

										</div> 



										<div class="row">

											<div class="col-lg-6 col-12">

								  				<div class="mb-3">

													<label for="cor_ehtl" class="form-label">Cor principal</label>

													<input type="color" class="form-control form-control-color" id="cor_ehtl" name="cor_ehtl" value="<?=(empty(get_option( 'cor_ehtl' )) ? '#000000' : get_option( 'cor_ehtl' ))?>" title="Selecione uma cor">

													<p style="font-size: 11px;margin: 11px 0px;">A cor informada será utilizada ao longo de todo o sistema.</p>

												</div> 

											</div>

											<div class="col-lg-6 col-12">

								  				<div class="mb-3">

													<label for="cor_ehtl" class="form-label">Cor dos botões</label>

													<input type="color" class="form-control form-control-color" id="cor_botao_ehtl" name="cor_botao_ehtl" value="<?=(empty(get_option( 'cor_botao_ehtl' )) ? '#000000' : get_option( 'cor_botao_ehtl' ))?>" title="Selecione uma cor"> 

												</div> 

											</div>

										</div>



									</div>



									<?php submit_button(); ?>



					  			</div>

					  		</div>

	  					</div>

	  				</div>



	  			</div>

	  			<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">



  					<div class="col-lg-6 col-12">

  						<ul class="nav nav-tabs" id="myTabCredencial" role="tablist" style="padding: 0px;">

				  			<li class="nav-item" role="presentation">

				    			<button class="nav-link active" id="credencial-tab" data-bs-toggle="tab" data-bs-target="#credencial" type="button" role="tab" aria-controls="home" aria-selected="true" style="border: none;background-color: #ebebeb;">Dados da licença</button>

				  			</li>

						</ul>

						<div class="tab-content" id="myTabContentCredencial" style="background-color: #ebebeb;height: 355px;">

				  			<div class="tab-pane fade show active" id="credencial" role="tabpanel" aria-labelledby="credencial-tab">  



			  					<div style=" ">



					  				<div class="mb-3">

										<label for="chave_licenca_ehtl" class="form-label">Chave</label>

										<input type="text" class="form-control" id="chave_licenca_ehtl" name="chave_licenca_ehtl" value="<?=(empty(get_option( 'chave_licenca_ehtl' )) ? '' : get_option( 'chave_licenca_ehtl' ))?>">

									</div> 



								</div>



								<?php submit_button(); ?> 



				  			</div>

				  		</div>

  					</div> 



	  			</div>

			</div> 



		</div>



		<div class="footer text-center"> 

			<p class="copyright">

				<img src="https://traveltec.com.br/wp-content/uploads/2021/08/Logotipo-Pequeno.png" style="height: 20px;margin-bottom: 10px;">

				<br>

				Desenvolvido por Travel Tec © 2023 - Todos os direitos reservados

			</p>

			<p class="links">

				<a href="/">Suporte</a> <span class="divisor">|</span> <a href="/">Site oficial</a> <span class="divisor">|</span> <a href="/">Outros plugins</a>

			</p>

			<p class="redes">

				<i class="fa fa-instagram"></i>

				<i class="fa fa-youtube"></i>

			</p>

		</div>



		<script>

			jQuery(function(){

				jQuery("[data-toggle='tooltip']").tooltip();



				jQuery("#copy_button_1").attr('title', 'Copiar shortcode').tooltip('_fixTitle');

			});



			function copy(text, target) {
				navigator.clipboard.writeText(text);

				jQuery(target).attr('title', 'Copiado!').tooltip('_fixTitle').tooltip('show');

				setTimeout(function() {
					jQuery(target).attr('title', 'Copiar shortcode').tooltip('_fixTitle').tooltip('show');
				}, 800);
			}

		</script>



		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> 



        <?php 



    } 



    add_action( 'wp_ajax_save_data_ehtl', 'save_data_ehtl' );

	add_action( 'wp_ajax_nopriv_save_data_ehtl', 'save_data_ehtl' );

    function save_data_ehtl(){

    	$user_ehtl      = $_POST['user_ehtl'];

		$pass_ehtl      = $_POST['pass_ehtl'];

		$cor_ehtl       = $_POST['cor_ehtl'];

		$cor_botao_ehtl = $_POST['cor_botao_ehtl'];

		$type_reserva   = $_POST['type_reserva'];

		$licenca        = $_POST['licenca'];



		update_option('user_ehtl', $user_ehtl);



		update_option('pass_ehtl', $pass_ehtl);



		update_option('cor_ehtl', $cor_ehtl);



		update_option('cor_botao_ehtl', $cor_botao_ehtl);



		update_option('type_reserva_ehtl', $type_reserva);



		update_option('chave_licenca_ehtl', $licenca);

    }

/* ***************************************** */
		/* FUNÇÃO PARA O MOTOR COM TODOS OS SERVIÇOS */
		/* VERIFICA SE O PLUGIN GERAL DE SHORTCODE ESTÁ ATIVO */
		/* SE NÃO, PEDE PRA INSTALAR */
		/* SE SIM, PROSSEGUE */
		/* Plugin criado para evitar duplicação de código e gerenciar o motor com todos os serviços em um só lugar */
		add_action( 'admin_init', 'hotel_plugin_has_parents' );
		function hotel_plugin_has_parents() {
			if (is_admin() && current_user_can('activate_plugins') && !is_plugin_active('TT-Helpers-1.0.0/helpers.php')){

			    add_action( 'admin_notices', 'hotel_plugin_notice' );

			    deactivate_plugins( plugin_basename( __FILE__) );
			    if ( isset( $_GET['activate'] ) ) {
			      	unset( $_GET['activate'] );
			    }
			}
		}
		function hotel_plugin_notice() { ?>
			<div class="error">
				<p>O plugin <strong>Vouchertec - Integração de hotéis</strong> precisa que o plugin <strong>Vouchertec - Shortcode</strong> esteja instalado e ativo para funcionar corretamente. Você pode fazer o download através <a href="https://github.com/TravelTec/TT-Helpers/archive/refs/tags/1.0.0.zip" target="_blank">deste link</a>. </p>
			</div>
		<?php }
		/* ***************************************** */



// Adiciona abas de detalhes ao plugin
function ehtl_details_tabs($links, $file) {
    // Verifica se é o plugin desejado
    if (strpos($file, 'vouchertec-ehtl.php') !== false) {
        // Adiciona a aba "Documentação" antes do link de desativar
        $documentation_link = '<span style="font-weight: bold;"><a href="https://traveltec.freshdesk.com/support/solutions/folders/43000591966" target="_blank">Documentação</a></span>';
        
        // Encontra a posição do link de desativar
        $deactivate_position = array_search('deactivate', array_keys($links));
        
        // Insere o link de documentação diretamente na posição desejada
        array_splice($links, $deactivate_position, 0, $documentation_link);
    }

    return $links;
}
add_filter('plugin_action_links', 'ehtl_details_tabs', 10, 2);
