<?php  



/*



Plugin Name: Voucher Tec - Integração de hotéis E-htl

Plugin URI: https://github.com/TravelTec/bookinghotels

GitHub Plugin URI: https://github.com/TravelTec/bookinghotels 

Description: Voucher Tec - Integração de hotéis E-htl é um plugin desenvolvido para agências e operadoras de turismo que precisam tratar diárias de hospedagem de fornecedores, com integração ao fornecedor E-htl.

Version: 1.0.0

Author: Travel Tec

Author URI: https://traveltec.com.br

License: GPLv2



*/  
session_start(); 

require 'plugin-update-checker-4.10/plugin-update-checker.php';
require_once plugin_dir_path(__FILE__) . 'includes/results-functions.php'; 

add_action( 'admin_init', 'ehtl_update_checker_setting' );  

function ehtl_update_checker_setting() { 
	
	register_setting( 'vouchertec-ehtl', 'serial' ); 

    if ( ! is_admin() || ! class_exists( 'Puc_v4_Factory' ) ) {  
        return;  
    }  

    $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker( 
        'https://github.com/TravelTec/vouchertec-ehtl',  
        __FILE__,  
        'ehtl'  
    );  

    $myUpdateChecker->setBranch('main'); 

}

add_action( 'wp_enqueue_scripts', 'enqueue_form_ehtl' ); 
function enqueue_form_ehtl() {

    wp_enqueue_style( 
      'flatpickr-style-ehtl', 
      'https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css'
    );

    wp_enqueue_style( 
      'carousel-style-ehtl', 
      'https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css'
    );

    wp_enqueue_style( 
      'carousel-principal-style-ehtl', 
      'https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css'
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

    wp_enqueue_script( 
        'carousel-script-ehtl',
        'https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/owl.carousel.js',
        array( 'jquery' )
    );

    wp_enqueue_script( 'sweetalert-ehtl', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js');

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
      CURLOPT_POSTFIELDS => "username=120278&password=agenciaws@0457174388050792022",
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
	            $valores[] = array("destino"=>$resultados[$i]['id'],"sigla"=>$resultados[$i]["attributes"]['namePt'],"end"=>utf8_encode($estado) );
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
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?ver=1.0">';

	$retorno .= '<style>
		.search-sec{padding:2rem}
.search-slt{display:block;width:100%;font-size:.875rem;line-height:1.5;color:#55595c;background-color:#fff;background-image:none;border:1px solid #ccc;height:calc(3rem + 2px)!important;border-radius:0}
.wrn-btn{width:100%;font-size:16px;font-weight:400;text-transform:capitalize;height:calc(3rem + 2px)!important;border-radius:0 4px 4px 0;}
.wrn-btn:focus{outline:none;box-shadow:none;border:none;}
@media (min-width:992px){.search-sec{position:absolute;bottom:0px;width:100%;background:rgba(26,70,104,.51);z-index:9;}
}
@media (max-width:992px){.search-sec{position:relative;bottom:0px;width:100%;background:#1a4668;z-index:9;}
.owl-carousel.main_banner{position:relative !important;}
.custom_header{position:relative !important;top:0;z-index:99;width:100%;background: rgba(26,70,104,.51) !important;border-radius:0;}
}
.custom-search-input-2{background-color:#fff;-webkit-border-radius:5px;-moz-border-radius:5px;-ms-border-radius:5px;border-radius:5px;margin-top:15px;box-shadow: 0 0 0 6px rgba(255,255,255,.25);}
@media (max-width: 991px){.custom-search-input-2{background:none;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none}
}
.custom-search-input-2 input{border:0;height:50px;padding-left:15px;border-right:1px solid #d2d8dd;font-weight:500}
@media (max-width: 991px){.custom-search-input-2 input{border:none}
}
.custom-search-input-2 input:focus{box-shadow:none;border-right:1px solid #d2d8dd}
@media (max-width: 991px){.custom-search-input-2 input:focus{border-right:none}
}
.custom-search-input-2 select{display:none}
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
.panel-dropdown .panel-dropdown-content{opacity:0;visibility:hidden;transition:all 0.3s;position:absolute;top:58px;left:0px;z-index:99;background:#fff;border-radius:4px;padding:15px 15px 0 15px;white-space:normal;width:280px;box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;border:none;}
.panel-dropdown .panel-dropdown-content:after{bottom:100%;left:15px;border:solid transparent;content:" ";height:0;width:0;position:absolute;pointer-events:none;border-bottom-color:#fff;border-width:7px;margin-left:-7px}
.panel-dropdown .panel-dropdown-content.right{left:auto;right:0}
.panel-dropdown .panel-dropdown-content.right:after{left:auto;right:15px}
.panel-dropdown.active .panel-dropdown-content{opacity:1;visibility:visible}
.qtyButtons{display:flex;margin:0 0 13px 0}
.qtyButtons input{outline:0;font-size:16px;font-size:1rem;text-align:center;width:50px;height:36px !important;color:#333;line-height:36px;margin:0 !important;padding:0 5px !important;border:none;box-shadow:none;pointer-events:none;display:inline-block;border:none !important}
.qtyButtons label{font-weight:400;line-height:36px;padding-right:15px;display:block;flex:1;color:#626262}
.qtyInc,.qtyDec{width:36px;height:36px;line-height:36px;font-size:28px;font-size:1.75rem;background-color:#f2f2f2;-webkit-text-stroke:1px #f2f2f2;color:#333;display:inline-block;text-align:center;border-radius:50%;cursor:pointer;}
.qtyInc:hover,.qtyDec:hover{background:#DC3545;}
.qtyInc:hover:before, .qtyDec:hover:before{color:#fff}
.qtyInc:before{content:"\002B";font-size:32px;font-weight:900;line-height: 30px;}
.qtyDec:before{content:"\2212";font-size:32px;font-weight:900;line-height: 30px;}
.qtyTotal{background-color:#66676b;border-radius:50%;color:#fff;display:inline-block;font-size:11px;font-weight:600;font-family:"Open Sans", sans-serif;line-height:18px;text-align:center;position:relative;top:1px;left:7px;height:18px;width:18px}
.rotate-x{animation-duration:.5s;animation-name:rotate-x}
@keyframes rotate-x{from{transform:rotateY(0deg)}
to{transform:rotateY(360deg)}
}
.daterangepicker{box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important;border:none;}
.daterangepicker td.in-range{background-color:#dc354529;}
.daterangepicker td.active, .daterangepicker td.active:hover {background-color:#DC3545;border-color:transparent;color:#fff;}
.daterangepicker td.available:hover, .daterangepicker th.available:hover{background-color:#dc3545e0;color:#fff;}
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
section.banner{position:relative;}
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
a:hover{text-decoration:none;color:#bd2130}
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
	</style>';

	$retorno .= '
	<section class="banner">
	<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light custom_header navbar-toggler py-3">
		<a class="navbar-brand" href="#">Logo</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
			<ul class="navbar-nav ml-auto my-lg-0 mt-2 mt-lg-0">
				<li class="nav-item active">
					<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Hotels</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Services</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Advantures</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Travels</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Foods</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Extra</a>
				</li>
			</ul>
		</div>
	</nav>
	
	<div class="owl-carousel owl-theme main_banner">
		<div class="item"><img src="https://pbs.twimg.com/media/EGHYvttU4AAYKb7?format=jpg&name=large" alt="" /></div>
		<div class="item"><img src="https://pbs.twimg.com/media/EGHYvtkUcAAuc8T?format=jpg&name=large" alt="" /></div>
		<div class="item"><img src="https://pbs.twimg.com/media/EGHYvtjU0AAO8w1?format=jpg&name=large" alt="" /></div>
	</div>

	<div class="search-sec bg-transparent d-none d-sm-block" style="top:50%;">
		<div class="container text-center tag_line">
			<h3>Book unique experiences</h3>
			<p>Expolore top rated tours, hotels and restaurants around the world</p>
		</div>
	</div>
	<div class="search-sec">
		<div class="container">
			<form>
				<div class="row no-gutters custom-search-input-2">
					<div class="col-lg-4">
						<div class="form-group">
							<div class="custom-select-form">
								<select class="w-100" name="city" id="city">
									<option value="" selected>Select your Country, City...</option>
									<option value="Europe">Europe</option>
									<option value="United states">United states</option>
									<option value="South America">South America</option>
									<option value="Oceania">Oceania</option>
									<option value="Asia">Asia</option>
									<option value="Europe">Europe</option>
									<option value="United states">United states</option>
									<option value="South America">South America</option>
									<option value="Oceania">Oceania</option>
									<option value="Asia">Asia</option>
									<option value="Europe">Europe</option>
									<option value="United states">United states</option>
									<option value="South America">South America</option>
									<option value="Oceania">Oceania</option>
									<option value="Asia">Asia</option>
									<option value="Europe">Europe</option>
									<option value="United states">United states</option>
									<option value="South America">South America</option>
									<option value="Oceania">Oceania</option>
									<option value="Asia">Asia</option>
								</select>
							</div>
							<i class="fa fa-map-marker"></i>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<input class="form-control search-slt" type="text" name="dates" placeholder="Select Date...">
							<i class="fa fa-calendar"></i>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="panel-dropdown">
							<a href="#">Guests <span class="qtyTotal">1</span></a>
							<div class="panel-dropdown-content">
								<div class="qtyButtons">
									<label>Adults</label>
									<input type="text" name="qtyInput" value="1">
								</div>
								<div class="qtyButtons">
									<label>Childrens</label>
									<input type="text" name="qtyInput" value="0">
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-2">
						<button type="submit" class="btn_search btn btn-danger wrn-btn ripple"><span>Search </span></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>

<section class="container-fluid container-custom margin_80_0 py-4">
	<div class="main_title_2 py-4 text-center">
		<span><em></em></span>
		<h2>Our Popular Tours</h2>
		<p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
	</div>
	<div id="places" class="owl-carousel owl-theme places pt-4 position-relative">
		<div class="item">
			<div class="box_grid">
				<figure>
					<a href="#!" class="wish_bt"></a>
					<a href="#!"><img src="http://www.ansonika.com/panagea/img/tour_3.jpg" class="img-fluid" alt="" width="800" height="533"><div class="read_more"><span>Read more</span></div></a>
					<small>Historic</small>
				</figure>
				<div class="wrapper">
					<h3><a href="#!">Arc Triomphe</a></h3>
					<p>Id placerat tacimates definitionem sea, prima quidam vim no. Duo nobis persecuti cu.</p>
					<span class="price">From <strong>$54</strong> /per person</span>
				</div>
				<ul>
					<li><i class="fa fa-clock-o"></i> 1h 30min</li>
					<li><div class="score"><span>Superb<em>350 Reviews</em></span><strong>8.9</strong></div></li>
				</ul>
			</div>
		</div>
		
		<div class="item">
			<div class="box_grid">
				<figure>
					<a href="#!" class="wish_bt"></a>
					<a href="#!"><img src="http://www.ansonika.com/panagea/img/tour_2.jpg" class="img-fluid" alt="" width="800" height="533"><div class="read_more"><span>Read more</span></div></a>
					<small>Churches</small>
				</figure>
				<div class="wrapper">
					<h3><a href="#!">Notredam</a></h3>
					<p>Id placerat tacimates definitionem sea, prima quidam vim no. Duo nobis persecuti cu.</p>
					<span class="price">From <strong>$124</strong> /per person</span>
				</div>
				<ul>
					<li><i class="fa fa-clock-o"></i> 1h 30min</li>
					<li><div class="score"><span>Good<em>350 Reviews</em></span><strong>7.0</strong></div></li>
				</ul>
			</div>
		</div>
		
		<div class="item">
			<div class="box_grid">
				<figure>
					<a href="#!" class="wish_bt"></a>
					<a href="#!"><img src="http://www.ansonika.com/panagea/img/tour_1.jpg" class="img-fluid" alt="" width="800" height="533"><div class="read_more"><span>Read more</span></div></a>
					<small>Historic</small>
				</figure>
				<div class="wrapper">
					<h3><a href="#!">Versailles</a></h3>
					<p>Id placerat tacimates definitionem sea, prima quidam vim no. Duo nobis persecuti cu.</p>
					<span class="price">From <strong>$25</strong> /per person</span>
				</div>
				<ul>
					<li><i class="fa fa-clock-o"></i> 1h 30min</li>
					<li><div class="score"><span>Good<em>350 Reviews</em></span><strong>7.0</strong></div></li>
				</ul>
			</div>
		</div>
		
		<div class="item">
			<div class="box_grid">
				<figure>
					<a href="#!" class="wish_bt"></a>
					<a href="#!"><img src="http://www.ansonika.com/panagea/img/tour_4.jpg" class="img-fluid" alt="" width="800" height="533"><div class="read_more"><span>Read more</span></div></a>
					<small>Historic</small>
				</figure>
				<div class="wrapper">
					<h3><a href="#!">Versailles</a></h3>
					<p>Id placerat tacimates definitionem sea, prima quidam vim no. Duo nobis persecuti cu.</p>
					<span class="price">From <strong>$25</strong> /per person</span>
				</div>
				<ul>
					<li><i class="fa fa-clock-o"></i> 1h 30min</li>
					<li><div class="score"><span>Good<em>350 Reviews</em></span><strong>7.0</strong></div></li>
				</ul>
			</div>
		</div>
		
		<div class="item">
			<div class="box_grid">
				<figure>
					<a href="#!" class="wish_bt"></a>
					<a href="#!"><img src="http://www.ansonika.com/panagea/img/tour_3.jpg" class="img-fluid" alt="" width="800" height="533"><div class="read_more"><span>Read more</span></div></a>
					<small>Museum</small>
				</figure>
				<div class="wrapper">
					<h3><a href="#!">Pompidue Museum</a></h3>
					<p>Id placerat tacimates definitionem sea, prima quidam vim no. Duo nobis persecuti cu.</p>
					<span class="price">From <strong>$45</strong> /per person</span>
				</div>
				<ul>
					<li><i class="fa fa-clock-o"></i> 2h 30min</li>
					<li><div class="score"><span>Superb<em>350 Reviews</em></span><strong>9.0</strong></div></li>
				</ul>
			</div>
		</div>
		
		<div class="item">
			<div class="box_grid">
				<figure>
					<a href="#!" class="wish_bt"></a>
					<a href="#!"><img src="http://www.ansonika.com/panagea/img/tour_1.jpg" class="img-fluid" alt="" width="800" height="533"><div class="read_more"><span>Read more</span></div></a>
					<small>Walking</small>
				</figure>
				<div class="wrapper">
					<h3><a href="#!">Tour Eiffel</a></h3>
					<p>Id placerat tacimates definitionem sea, prima quidam vim no. Duo nobis persecuti cu.</p>
					<span class="price">From <strong>$65</strong> /per person</span>
				</div>
				<ul>
					<li><i class="fa fa-clock-o"></i> 1h 30min</li>
					<li><div class="score"><span>Good<em>350 Reviews</em></span><strong>7.5</strong></div></li>
				</ul>
			</div>
		</div>
	</div>
</section>

<div class="backdrop" style="display: none;"></div>';

	$retorno .= '<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script src="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/owl.carousel.js"></script>
	<script src="https://www.jqueryscript.net/demo/Customizable-Animated-Dropdown-Plugin-with-jQuery-CSS3-Nice-Select/js/jquery.nice-select.js"></script>';

	return $retorno;

}

add_shortcode('TTBOOKING_RESULTADOS_RESERVA', 'shortcode_resultados_reserva');  

function shortcode_resultados_reserva(){
	$retorno = '';

	$retorno .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">';
	$retorno .= '<link rel="preconnect" href="https://fonts.googleapis.com">
				<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
				<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">';

	$retorno .= '<style>
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

		@media(min-width:767px){
			.rowMeioInterna{
				padding: 0 20px !important;
			}
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
	</style>';

	$retorno .= '<div class="container">
		<div class="row">
			<div class="col-lg-12 col-12 dadosGerais">
				<h4>Resultados da pesquisa</h4>
				<span class="dados_pesquisa"></span>
				<span class="dados_resultado" style="font-size:13px"></span>
				<hr style="margin-top:9px">
			</div>
		</div>
		<div id="show_results">
			<div class="loader">
				<br>
				<h6 style="text-align:center">Aguarde... <br> Estamos buscando as melhores ofertas.</h6>
				<img src="'.plugin_dir_url( __FILE__ ) . 'includes/assets/img/loader.gif" style="margin: 0 auto"> 
			</div>
			<div class="results" style="display:none">

			</div>
		</div>
	</div>';

	$retorno .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>';
	$retorno .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" crossorigin="anonymous"></script>';			

	return $retorno;
}

global $wpdb;

$check_page_exist = get_page_by_title('Pesquisa de Hotéis', 'OBJECT', 'page');  

if(empty($check_page_exist)) {

    $wpdb->insert('wp_posts', array( 
        'comment_status' => 'close', 
        'ping_status'    => 'close', 
        'post_author'    => 1, 
        'post_title'     => ucwords('Pesquisa de Hotéis'), 
        'post_name'      => 'pesquisa-de-hoteis', 
        'post_status'    => 'publish', 
        'post_content'   => '[TTBOOKING_RESULTADOS_RESERVA]', 
        'post_type'      => 'page' 
    ));

}

add_action( 'wp_ajax_save_product_ehtl', 'save_product_ehtl' );
add_action( 'wp_ajax_nopriv_save_product_ehtl', 'save_product_ehtl' );

function save_product_ehtl(){

	$nome_hotel = $_POST['nome'].'<br><strong>Apto.: </strong>'.$_POST['apartamento'].' <br> <strong>Regime:</strong> '.$_POST['regime'].'<br> <strong>Período: </strong>'.$_POST['checkin'].' a '.$_POST['checkout'].'<br> <strong>Pax:</strong> '.$_POST['adultos']. ' '.($_POST['adultos'] > 1 ? 'adultos' : 'adulto').' e '.$_POST['criancas']. ' '.($_POST['criancas'] > 1 ? 'criancas' : 'crianca');

	$product = get_page_by_title( $nome_hotel, 'OBJECT', 'product' );

	if(empty($product)) {
		global $wpdb;  
	    
	    $price_hotel = $_POST['preco_sem_formatacao'];  
	    $descricao = '';

	    $post = array( 
	        'post_content' => "",
	        'post_status' => "publish",
	        'post_title' => $nome_hotel,
	        'post_parent' => '',
	        'post_type' => "product",
	    );

	    //Create post
	    $post_id = wp_insert_post( $post, $wp_error ); 

	    //wp_set_object_terms( $post_id, 'Integrado', 'product_cat' );
	    wp_set_object_terms( $post_id, 'simple', 'product_type');

	    //wp_set_object_terms($post_id, $tag, 'product_tag'); 
	         
	    update_post_meta( $post_id, '_visibility', 'visible' );
	    update_post_meta( $post_id, '_stock_status', 'instock');
	    update_post_meta( $post_id, 'total_sales', '0');
	    update_post_meta( $post_id, '_downloadable', 'yes');
	    update_post_meta( $post_id, '_virtual', 'yes');
	    update_post_meta( $post_id, '_regular_price', $price_hotel );
	    update_post_meta( $post_id, '_sale_price', '' );
	    update_post_meta( $post_id, '_purchase_note', "" );
	    update_post_meta( $post_id, '_featured', "no" );
	    update_post_meta( $post_id, '_weight', "" );
	    update_post_meta( $post_id, '_length', "" );
	    update_post_meta( $post_id, '_width', "" );
	    update_post_meta( $post_id, '_height', "" );
	    update_post_meta( $post_id, '_sku', '');
	    update_post_meta( $post_id, '_product_attributes', '');
	    update_post_meta( $post_id, '_sale_price_dates_from', "" );
	    update_post_meta( $post_id, '_sale_price_dates_to', "" );
	    update_post_meta( $post_id, '_price', $price_hotel );
	    update_post_meta( $post_id, '_sold_individually', "" );
	    update_post_meta( $post_id, '_manage_stock', "no" );
	    update_post_meta( $post_id, '_backorders', "no" );
	    update_post_meta( $post_id, '_stock', "" );  

	    echo $post_id; 
	}else{
		global $wpdb;

	    $post_title = strval($title);

	    $post_table = $wpdb->prefix . "posts";
	    $result = $wpdb->get_col("
	        SELECT ID
	        FROM $post_table
	        WHERE post_title LIKE '$post_title'
	        AND post_type LIKE 'product'
	    ");

	    // We exit if title doesn't match
	    if( empty( $result[0] ) ) 
	        return;
	    else
	        echo $result[0];
	}
}

	add_action( 'wp_ajax_session_ehtl', 'session_ehtl' );
    add_action( 'wp_ajax_nopriv_session_ehtl', 'session_ehtl' );

    function session_ehtl(){ 

        $_SESSION['tipo_tarifario'] = 0;
    }

    add_action( 'woocommerce_before_calculate_totals', 'custom_cart_items_prices_ehtl', 10, 1 );
    function custom_cart_items_prices_ehtl( $cart ) {

        if ( is_admin() && ! defined( 'DOING_AJAX' ) )
            return;

        if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
            return;

        // Loop through cart items
        foreach ( $cart->get_cart() as $cart_item ) {

            // Get an instance of the WC_Product object
            $product = $cart_item['data']; 
            $quantity =  $cart_item['quantity']; 

            // Get the product name (Added Woocommerce 3+ compatibility)
            $original_name = method_exists( $product, 'get_name' ) ? $product->get_name() : $product->post->post_title;

            // SET THE NEW NAME
            $new_name = $product->post->post_title.' <br> '.$_SESSION['teste'];

            // Set the new name (WooCommerce versions 2.5.x to 3+)
            if( method_exists( $product, 'set_name' ) )
                $product->set_name( $new_name );
            else
                $product->post->post_title = $new_name;
        }

    } 

    function register_cotacao_arrival_order_status() {
        register_post_status( 'wc-arrival-shipment', array(
            'label'                     => 'Cotação',
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false,
            'label_count'               => _n_noop( 'Cotação <span class="count">(%s)</span>', 'Cotação <span class="count">(%s)</span>' )
        ) );
    }
    add_action( 'init', 'register_cotacao_arrival_order_status' );

    function add_cotacao_to_order_statuses( $order_statuses ) {
        $new_order_statuses = array();
        foreach ( $order_statuses as $key => $status ) {
            $new_order_statuses[ $key ] = $status;
            if ( 'wc-processing' === $key ) {
                $new_order_statuses['wc-arrival-shipment'] = 'Cotação';
            }
        }
        return $new_order_statuses;
    }
    add_filter( 'wc_order_statuses', 'add_cotacao_to_order_statuses' );

    //  Disable gateway based on country
    function payment_gateway_disable_country( $available_gateways ) {
        //1 - cartão | 2 - boleto | 3 - pix
        if ($_SESSION['tipo_tarifario'] == 0) { 
            unset(  $available_gateways['itau-shopline'] );
            unset(  $available_gateways['checkout-cielo'] );
            return $available_gateways; 
        }else{
            if($_SESSION['methodo'] == 1){
                unset(  $available_gateways['itau-shopline'] );
                unset(  $available_gateways['cod'] );
                return $available_gateways;
            }else if($_SESSION['methodo'] == 2){
                unset(  $available_gateways['checkout-cielo'] );
                unset(  $available_gateways['cod'] );
                return $available_gateways;
            }else{
                unset(  $available_gateways['itau-shopline'] );
                unset(  $available_gateways['checkout-cielo'] );
                return $available_gateways;
            } 
        }
    }
    add_filter( 'woocommerce_available_payment_gateways', 'payment_gateway_disable_country' );

    add_filter( 'woocommerce_order_button_text', 'njengah_change_checkout_button_text' );
 
    function njengah_change_checkout_button_text( $button_text ) {

        if ($_SESSION['tipo_tarifario'] == 1) { 
        
            return 'Finalizar reserva'; // Replace this text in quotes with your respective custom button text

        }else{

            return 'Solicitar cotação';

        }
       
    }

    add_filter( 'wc_add_to_cart_message_html', '__return_false' ); 
    
    add_filter(  'gettext',  'change_conditionally_checkout_heading_text_shipping', 10, 3 );
    function change_conditionally_checkout_heading_text_shipping( $translated, $text, $domain  ) {
        if( $text === 'Your order' && is_checkout() && ! is_wc_endpoint_url() ){
            if ($_SESSION['tipo_tarifario'] == 1) { 
                return __( 'Efetuar reserva', $domain );
            }else{
                return __( 'Solicitar cotação', $domain );
            }
        }
        return $translated;
    }

    add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
    function custom_override_checkout_fields( $fields ) {
        if ($_SESSION['tipo_tarifario'] == 0) {  
            unset($fields['billing']['billing_company']);
            unset($fields['billing']['billing_address_1']);
            unset($fields['billing']['billing_address_2']);
            unset($fields['billing']['billing_city']);
            unset($fields['billing']['billing_postcode']);
            unset($fields['billing']['billing_country']);
            unset($fields['billing']['billing_state']); 
            unset($fields['order']['order_comments']); 
            unset($fields['account']['account_username']);
            unset($fields['account']['account_password']);
            unset($fields['account']['account_password-2']);
            
        }
        return $fields;
    }

    function hide_coupon_field_on_cart( $enabled ) {
    if ( is_checkout() ) {
        $enabled = false;
    }
    return $enabled;
    }
    add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_cart' );

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



 



     



    function gerador_de_conteudo_ehtl() {



        ?>



        <div class="wrap">



            <h1><?php echo esc_html( get_admin_page_title() ); ?> </h1> 



            



                <div id="tabs">



                    <h2 class="nav-tab-wrapper">



                        <a href="#tab-about" class="nav-tab">Sobre</a> 

 



                    </h2>







                    <div id="tab-about" class="tab-content" style="padding: 30px 10px">



                        <img src="https://traveltec.com.br/wp-content/uploads/2021/08/Logotipo-Pequeno.png" style="height: 35px">



                        <p style="font-size:17px;line-height: 1.8"> O Plugin <strong>Travel Tec - Hospedagem</strong> é um plugin desenvolvido para agências e operadoras de turismo que precisam tratar diárias de hospedagem de fornecedores, com integração ao fornecedor E-htl. </p>

                        <p style="font-size:17px;line-height: 1.8">Use o shortcode [TTBOOKING_MOTOR_RESERVA] para adicionar o motor de reserva em qualquer página.</p>



			



 

                    </div>  



                </div> 



            



        </div>



        <?php



    } 