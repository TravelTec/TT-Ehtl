<?php 
	// Define que o arquivo terá a codificação de saída no formato CSS
ini_set("display_errors", 1);
	header("Content-type: text/css");
	$cor = $_GET['color'];
	print_r('AQUI '.get_option( 'cor_ehtl' ));
?>
  
	.search-sec{padding:2.5rem 2rem}
.search-slt{display:block;width:100%;font-size:.875rem;line-height:1.5;color:#55595c;background-color:#fff;background-image:none;border:1px solid #ccc;height:calc(3rem + 2px)!important;border-radius:0}
.wrn-btn{width:100%;font-size:16px;font-weight:400;text-transform:capitalize;height:calc(3rem + 2px)!important;border-radius:0 4px 4px 0;}
.wrn-btn:focus{outline:none;box-shadow:none;border:none;}
@media (min-width:992px){.search-sec{bottom:0px;width:100%;background:<?php echo $cor?>;z-index:9;border-radius: 15px;box-shadow: 4px 4px 8px #dadada;font-family: 'Montserrat';} .daterangepicker{width:49.5% !important} .daterangepicker .drp-calendar.left{margin-right: 49px;}
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
	.search-sec{bottom:0px;width:100%;background:<?php echo $cor?>c2;z-index:9;border-radius: 15px;box-shadow: 4px 4px 8px #dadada;font-family: 'Montserrat';}.custom-search-input-2 .form-group {margin-bottom: 15px !important;}
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
.custom-search-input-2 input[type='submit']{-moz-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;transition:all 0.3s ease-in-out;color:#fff;font-weight:600;font-size:14px;font-size:0.875rem;border:0;padding:0 25px;height:50px;cursor:pointer;outline:none;width:100%;-webkit-border-radius:0 3px 3px 0;-moz-border-radius:0 3px 3px 0;-ms-border-radius:0 3px 3px 0;border-radius:0 3px 3px 0;background-color:#fc5b62;margin-right:-1px}
@media (max-width: 991px){.custom-search-input-2 input[type='submit']{margin:20px 0 0 0;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px}
}
.custom-search-input-2 input[type='submit']:hover{background-color:#FFC107;color:#222}
.custom-search-input-2.inner{margin-bottom:30px;-webkit-box-shadow:0px 0px 30px 0px rgba(0,0,0,0.1);-moz-box-shadow:0px 0px 30px 0px rgba(0,0,0,0.1);box-shadow:0px 0px 30px 0px rgba(0,0,0,0.1)}
@media (max-width: 991px){.custom-search-input-2.inner{margin:0 0 20px 0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none}
}
.custom-search-input-2.inner-2{margin:0 0 20px 0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;background:none}
.custom-search-input-2.inner-2 .form-group{margin-bottom:10px}
.custom-search-input-2.inner-2 input{border:1px solid #ededed}
.custom-search-input-2.inner-2 input[type='submit']{-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;margin-top:10px}
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
.qtyInc,.qtyDec{width:28px;height:28px;line-height:22px;font-size:15px;background-color:<?php echo $cor?>bd;-webkit-text-stroke:1px #fff;color:#333;display:inline-block;text-align:center;border-radius:50%;cursor:pointer;}
.qtyInc:hover,.qtyDec:hover{background:<?php echo $cor?>bd;}
.qtyInc:hover:before, .qtyDec:hover:before{color:#fff}
.qtyInc:before{content:"\002B";font-size:19px;font-weight:900;line-height: 29px;}
.qtyDec:before{content:"\2212";font-size:19px;font-weight:900;line-height: 29px;}
.qtyTotal, .qtyRoom{border-radius:50%;color:#66676b;display:inline-block;font-size:14px;font-weight:600;font-family:'Montserrat', sans-serif;line-height:18px;text-align:center;position:relative;top:1px;left:7px;height:18px;width:18px;margin-right:15px}
.rotate-x{animation-duration:.5s;animation-name:rotate-x}
@keyframes rotate-x{from{transform:rotateY(0deg)}
to{transform:rotateY(360deg)}
}
.daterangepicker{box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important;border:none;}
.daterangepicker td.in-range{background-color:<?php echo $cor?>54;cor:<?php echo $cor?>54;}
.daterangepicker td.active, .daterangepicker td.active:hover {background-color:<?php echo $cor?>;border-color:transparent;color:#fff;}
.daterangepicker td.available:hover, .daterangepicker th.available:hover{background-color:<?php echo $cor?>99;color:#fff;border-radius:40px}
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
.wrn-btn span:after{content:'\00bb';position:absolute;opacity:0;top:-8px;right:-20px;transition:.5s;font-size:24px;}
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
a:hover{text-decoration:none;color:<?php echo $cor?>}
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
#side-menu li.nav-item:before{content:'\203A';position:absolute;left:2px;top:7px;}
#side-menu li.nav-item{padding-left:20px;}
#side-menu .nav-link{color:#333;font-size:14px;font-weight:600;padding:10px 0}
#side-menu .nav-link:hover{opacity:.8;color:#1b820a;}
#side-menu li.nav-item.dropdown.show{border-bottom:1px solid #eee;padding-bottom:10px;margin-bottom:10px;}
#side-menu .close{font-size:36px;font-weight:400;position:absolute;top:5px;right:15px;}
#side-menu .contact a, #side-menu .contact .fa{padding:5px 0px;background:#fff;font-size:14px;color:#727272;}
#side-menu .contact a:hover, #side-menu .contact .fa:hover{color: #28ab13 !important;}
#side-menu .contact a:focus, #side-menu .contact .fa:focus{color: #28ab13 !important;}
.dados{position:absolute;}
table td, table th{padding:9px;font-family: 'Montserrat';font-weight: 600;}
table caption+thead tr:first-child td, table caption+thead tr:first-child th, table colgroup+thead tr:first-child td, table colgroup+thead tr:first-child th, table thead:first-child tr:first-child td, table thead:first-child tr:first-child th{
	border-top:none !important;
	font-size:17px !important;
    text-transform: capitalize;
}
.daterangepicker .calendar-table th, .daterangepicker .calendar-table td{text-transform:uppercase}
.daterangepicker td.start-date{border-radius:40px 0px 0px 40px}
.daterangepicker td.end-date{border-radius:0px 40px 40px 0px}
.daterangepicker.show-calendar .drp-buttons{font-family: 'Montserrat'}
.daterangepicker td.start-date.end-date{border-radius:40px}
.cancelBtn{color:<?php echo $cor?>bd !important}
.cancelBtn:hover{background-color:<?php echo $cor?>bd !important;color:#fff !important}
.applyBtn{background-color:<?php echo $cor?> !important;border-color:<?php echo $cor?> !important}
.applyBtn:hover{background-color:<?php echo $cor?>bd !important}
.daterangepicker .drp-selected{display:none !important;}
.btnAddRoom{font-size: 13px;font-weight: 700;color: <?php echo $cor?>;padding:6px 0}
.btnAddRoom:hover{color: <?php echo $cor?>ee;}
.btnApplyRoom{background-color: <?php echo $cor?>;color: #fff;font-size: 14px;font-weight: 600;border-radius: 40px;padding: 5px 30px;float: right;}
.btnApplyRoom:hover{background-color: <?php echo $cor?>d4;}
.ripple{background-color:<?php echo $cor?> !important;border:transparent !important}
.ripple:hover{background-color:<?php echo $cor?>80 !important}
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