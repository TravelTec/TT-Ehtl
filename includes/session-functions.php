<?php  
    session_start(); 
    
    add_action( 'wp_ajax_session_ehtl', 'session_ehtl' );
    add_action( 'wp_ajax_nopriv_session_ehtl', 'session_ehtl' );

    function session_ehtl(){
        unset($_SESSION['data']);
        $_SESSION['data'] .= '<strong>Apto.: </strong>'.$_POST['apartamento'].' <br> <strong>Regime:</strong> '.$_POST['regime'].'<br>'; 
        $_SESSION['data'] .= '<strong>Período: </strong>'.$_POST['checkin'].' a '.$_POST['checkout'].'<br>'; 
        $_SESSION['teste'] .= '<strong>Pax:</strong> '.$_POST['adultos']. ' '.($_POST['adultos'] > 1 ? 'adultos' : 'adulto').' e '.$_POST['criancas']. ' '.($_POST['criancas'] > 1 ? 'criancas' : 'crianca');

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
?> 