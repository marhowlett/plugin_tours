<?php

add_filter( 'register_post_type_args', 'boldthemes_update_portfolio_slug', 10, 2 );
add_action( 'init', 'boldthemes_portfolio_category_slug', 11 );

add_filter( 'wp_get_attachment_image_src', 'boldthemes_fix_wp_get_attachment_image_svg', 10, 4 ); 


/**
 * Change portfolio slug
 *
 * @return array
 */

function boldthemes_update_portfolio_slug( $args, $post_type ) {
	if ( function_exists( 'boldthemes_get_option' ) ) {
		if ( 'portfolio' === $post_type && boldthemes_get_option( 'pf_slug' ) != '' ) {
			$new_args = array(
				'rewrite' => array( 'slug' => boldthemes_get_option( 'pf_slug' ) )
			);
			return array_merge( $args, $new_args );
		}
	}
	return $args;
}

function boldthemes_portfolio_category_slug() {
	if ( function_exists( 'boldthemes_get_option' ) ) {
		if ( boldthemes_get_option ( 'pf_category_slug' ) != '' ) {
			$portfolio_category_args = get_taxonomy( 'portfolio_category' ); // returns an object
			$portfolio_category_args->rewrite['slug'] = boldthemes_get_option( 'pf_category_slug' );
			register_taxonomy( 'portfolio_category', 'portfolio', (array) $portfolio_category_args );
		}
	}
}
// hook it up to 11 so that it overrides the original register_taxonomy function


// helper for decode

function boldthemes_decode( $code ) {
    return base64_decode( $code );
}

//helper for curl data
function boldthemes_get_curl($args) {
	$retValue	=  array();
	$curl_url	=  isset($args['curl_url']) && $args['curl_url'] != '' ? $args['curl_url'] : '';
	$curl_data	=  isset($args['curl_data']) && !empty($args['curl_data']) ? $args['curl_data'] : array();

	if ( $curl_url != '' ) {
		$session = curl_init($curl_url);
		curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
		$json = curl_exec($session);
		if ( $json === false ) {
			$retValue = $curl_data;
		}else{
			$retValue = json_decode( $json, true );
		}
		curl_close($session);		
	}

	return $retValue;
}

/*
 * Fix svg attachment dimensions
 */
function boldthemes_fix_wp_get_attachment_image_svg($image, $attachment_id, $size, $icon) {
        if ( is_array($image) && preg_match('/\.svg$/i', $image[0]) && $image[1] <= 1 ) {			
                $image[1] = $image[2] = null;                
                if ( is_array($size) ) {
                        $image[1] = $size[0];
                        $image[2] = $size[1];
                } else {
                        $allow_url = ini_get('allow_url_fopen');
                        if ( $allow_url ){
                            $xml = simplexml_load_file($image[0]);
                        } else {
                            $args['curl_url'] = $image[0];
                            $xml = boldthemes_get_curl($args);
                            $xml = simplexml_load_string($xml);
                        }
                        $attr = $xml->attributes();
                        $viewbox = explode(' ', $attr->viewBox);
                        $image[1] = isset($attr->width) && preg_match('/\d+/', $attr->width, $value) ? (int) $value[0] : (count($viewbox) == 4 ? (int) $viewbox[2] : null);
                        $image[2] = isset($attr->height) && preg_match('/\d+/', $attr->height, $value) ? (int) $value[0] : (count($viewbox) == 4 ? (int) $viewbox[3] : null);
                }
        }
        return $image;
}

// Remove styles one by one

add_action( 'wp_enqueue_scripts', 'boldthemes_remove_woo_scripts', 100 );

function boldthemes_remove_woo_scripts() {
	if ( class_exists( 'woocommerce' ) ) {
		wp_dequeue_style( 'select2' );
		wp_deregister_style( 'select2' );
		wp_dequeue_script( 'select2' );
		wp_deregister_script( 'select2' );
		wp_dequeue_style( 'selectWoo' );
		wp_deregister_style( 'selectWoo' );
		wp_dequeue_script( 'selectWoo' );
		wp_deregister_script( 'selectWoo' );
	}
}