<?php
/**
 * Plugin Name: Travelicious Plugin
 * Description: Shortcodes and widgets by BoldThemes.
 * Version: 1.0.2
 * Author: BoldThemes
 * Author URI: http://bold-themes.com
 */

// plugin framework
require_once( 'framework_bt_plugin/framework.php' );

// meta boxes
require_once( 'inc/tour-meta-boxes.php' );

// query
require_once( 'inc/include_query.php' );

// html
require_once( 'inc/tour_html/single/tour.php' );
require_once( 'inc/tour_html/single/comment.php' );
require_once( 'inc/tour_html/list/tour.php' );
require_once( 'inc/tour_html/widget/tour.php' );

// ajax
require_once( 'inc/include_ajax.php' );

// widgets
require_once( 'widgets/widgets.php' );

// shortcodes
require_once( 'shortcodes/bt_header_slider.php' );
require_once( 'shortcodes/bt_header_video.php' );
require_once( 'shortcodes/bt_header_video_slider.php' );
require_once( 'shortcodes/bt_masonry_image_grid.php' );

// amp
require_once( 'amp/amp.php' );


function bt_enqueue_scripts() {
    wp_enqueue_script( 'jquery' );    
    wp_enqueue_style( 'tour-jquery-ui-css', "//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css", array(), false, 'screen' );
    wp_enqueue_script( 'tour-jquery-ui-js', "https://code.jquery.com/ui/1.12.1/jquery-ui.js", array( 'jquery' ), '', true ); 
    wp_enqueue_script( 'tour_js', plugin_dir_url( __FILE__ ) . 'assets/js/tour.js', array( 'jquery' ), '', false );
    
    wp_enqueue_style( 'date_dropdowns_css', plugin_dir_url( __FILE__ ) . 'assets/css/jquery.date-dropdowns.css', array(), false, 'screen' );
   
    wp_register_script( 'date_dropdowns_js', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.date-dropdowns.js', array( 'jquery' ), '', false );
    wp_localize_script( 'date_dropdowns_js', 'date_dropdowns_js_object', array( 
                'label_day'             => esc_html__( 'Day', 'bt_plugin' ),
                'label_month'           => esc_html__( 'Month', 'bt_plugin' ),
                'label_year'            => esc_html__( 'Year', 'bt_plugin' ),
        ) 
    );
    wp_enqueue_script( 'date_dropdowns_js' );
}
add_action( 'wp_enqueue_scripts', 'bt_enqueue_scripts' );

$bt_plugin_dir = plugin_dir_path( __FILE__ );

function bt_load_plugin_textdomain() {
	$domain = 'bt_plugin';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'plugins_loaded', 'bt_load_plugin_textdomain' );

function bt_widget_areas() {
	register_sidebar( array (
		'name' 		=> esc_html__( 'Header Left Widgets', 'bt_plugin' ),
		'id' 		=> 'header_left_widgets',
		'before_widget' => '<div class="btTopBox %2$s">', 
		'after_widget' 	=> '</div>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Header Right Widgets', 'bt_plugin' ),
		'id' 		=> 'header_right_widgets',
		'before_widget' => '<div class="btTopBox %2$s">',
		'after_widget' 	=> '</div>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Header Menu Widgets', 'bt_plugin' ),
		'id' 		=> 'header_menu_widgets',
		'before_widget' => '<div class="btTopBox %2$s">',
		'after_widget' 	=> '</div>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Header Logo Widgets', 'bt_plugin' ),
		'id' 		=> 'header_logo_widgets',
		'before_widget' => '<div class="btTopBox %2$s">',
		'after_widget' 	=> '</div>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Footer Widgets', 'bt_plugin' ),
		'id' 		=> 'footer_widgets',
		'before_widget' => '<div class="btBox %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h4><span>',
		'after_title' 	=> '</span></h4>',
	));
}
add_action( 'widgets_init', 'bt_widget_areas', 30 );

/* Portfolio */
if ( ! function_exists( 'bt_create_portfolio' ) ) {
	function bt_create_portfolio() {
            
                $labels = array(
                    'name'               => __( 'Destinations', 'post type general name', 'bt_plugin' ),
                    'singular_name'      => __( 'Destination Item', 'post type singular name', 'bt_plugin' ),
                    'menu_name'          => __( 'Destinations', 'admin menu', 'bt_plugin' ),
                    'name_admin_bar'     => __( 'Destination', 'add new on admin bar', 'bt_plugin' ),
                    'add_new'            => __( 'Add New Destination', 'destination', 'bt_plugin' ),
                    'add_new_item'       => __( 'Add New Destination', 'bt_plugin' ),
                    'new_item'           => __( 'New Destination', 'bt_plugin' ),
                    'edit_item'          => __( 'Edit Destination', 'bt_plugin' ),
                    'view_item'          => __( 'View Destination', 'bt_plugin' ),
                    'all_items'          => __( 'All Destinations', 'bt_plugin' ),
                    'search_items'       => __( 'Search Destinations', 'bt_plugin' ),
                    'parent_item_colon'  => __( 'Parent Destinations:', 'bt_plugin' ),
                    'not_found'          => __( 'No destinations found.', 'bt_plugin' ),
                    'not_found_in_trash' => __( 'No destinations found in Trash.', 'bt_plugin' )
                );
                
		register_post_type( 'portfolio',
			array(
                                'labels'        => $labels,
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'supports'      => array( 'title', 'editor', 'thumbnail', 'author', 'comments', 'excerpt' ),
				'rewrite'       => array( 'with_front' => false, 'slug' => 'portfolio' )
			)
		);
		register_taxonomy( 'portfolio_category', 'portfolio', array( 'hierarchical' => true, 'label' => __( 'Destination Categories', 'bt_plugin' ) ) );
	}
}
add_action( 'init', 'bt_create_portfolio' );

/* Tour */
if ( ! function_exists( 'bt_create_tour' ) ) {
	function bt_create_tour() {
            
                $labels = array(
                    'name'               => __( 'Tours', 'post type general name', 'bt_plugin' ),
                    'singular_name'      => __( 'Tour Item', 'post type singular name', 'bt_plugin' ),
                    'menu_name'          => __( 'Tours', 'admin menu', 'bt_plugin' ),
                    'name_admin_bar'     => __( 'Tour', 'add new on admin bar', 'bt_plugin' ),
                    'add_new'            => __( 'Add New Tour', 'tour', 'bt_plugin' ),
                    'add_new_item'       => __( 'Add New Tour', 'bt_plugin' ),
                    'new_item'           => __( 'New Tour', 'bt_plugin' ),
                    'edit_item'          => __( 'Edit Tour', 'bt_plugin' ),
                    'view_item'          => __( 'View Tour', 'bt_plugin' ),
                    'all_items'          => __( 'All Tours', 'bt_plugin' ),
                    'search_items'       => __( 'Search Tours', 'bt_plugin' ),
                    'parent_item_colon'  => __( 'Parent Tours:', 'bt_plugin' ),
                    'not_found'          => __( 'No tours found.', 'bt_plugin' ),
                    'not_found_in_trash' => __( 'No tours found in Trash.', 'bt_plugin' )
                );
             
		register_post_type( 'tour',
			array(
                                'labels'        => $labels,
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 6,
				'supports'      => array( 'title', 'editor', 'revisions', 'thumbnail', 'author', 'comments', 'excerpt' ),
				'rewrite'       => array( 'with_front' => true, 'slug' => 'tours', 'hierarchical' => true )
			)
		);
                register_taxonomy( 'tour_category', 'tour', array( 'hierarchical' => true, 'label' => __( 'Tour Categories', 'bt_plugin' ) ) );
		register_taxonomy( 'tour_destination', 'tour', array( 'hierarchical' => true, 'label' => __( 'Tour Destinations', 'bt_plugin' ) ) );
                register_taxonomy( 'tour_includes', 'tour', array( 'hierarchical' => false, 'label' => __( 'Tour Includes', 'bt_plugin' ) , 'show_in_admin_bar' => true, 'show_in_menu' => true, 'show_ui' => true ) );
                register_taxonomy( 'tour_tag', 'tour', array( 'hierarchical' => false, 'label' => __( 'Tour Tags', 'bt_plugin' ), 'singular_name' => __( 'Tag', 'bt_plugin' ) ) );

	}
}
add_action( 'init', 'bt_create_tour' );

if ( ! function_exists( 'bt_get_tour_list_data' ) ) {
    function bt_get_tour_list_data( $params = array() ) {         
        BoldThemesFrameworkTemplate::$tour_categories   = get_terms( array('taxonomy' => 'tour_category','hide_empty' => false,'parent' => 0) );
        BoldThemesFrameworkTemplate::$tour_destinations = get_terms( array('taxonomy' => 'tour_destination','hide_empty' => false,'parent' => 0) );
    }
}

if ( ! function_exists( 'bt_get_tour_single_data' ) ) {
    function bt_get_tour_single_data( $post_id = 0 ) {        
            $post_id            = $post_id > 0 ? $post_id : get_the_ID();            
            $tour               = get_post( $post_id );
            $tour_destinations  = get_the_terms( $post_id, 'tour_destination' );
            $tour_categories    = get_the_terms( $post_id, 'tour_category' );
            $tour_tags          = get_the_terms( $post_id, 'tour_tag' );
            $meta               = function_exists( 'rwmb_meta' ) ? get_post_meta( $post_id ) : array();
            
            //tour 
            BoldThemesFrameworkTemplate::$tour_categories       = !empty($tour_categories) ? $tour_categories : array();
            BoldThemesFrameworkTemplate::$title                 = !empty($tour) ? $tour->post_title  : '';             
            BoldThemesFrameworkTemplate::$author                = !empty($tour) ? $tour->post_author  : '';
            BoldThemesFrameworkTemplate::$excerpt               = !empty($tour) ? $tour->post_excerpt  : '';
            BoldThemesFrameworkTemplate::$status                = !empty($tour) ? $tour->post_status  : 'publish';             
            BoldThemesFrameworkTemplate::$name                  = !empty($tour) ? $tour->post_name  : ''; 
            BoldThemesFrameworkTemplate::$parent                = !empty($tour) ? $tour->post_parent  : 0; 
            BoldThemesFrameworkTemplate::$guid                  = !empty($tour) ? $tour->guid  : '';  
            BoldThemesFrameworkTemplate::$tour_comments_number  = get_comments_number($post_id ); 
            
            $content_html = '';
            $post_content = !empty($tour) ? trim($tour->post_content) : '';
            if ( $post_content != '' ) {
                $content_html = apply_filters( 'the_content', $post_content ) ;            
                $content_html = $content_html != '' ? str_replace( ']]>', ']]&gt;', $content_html ) : ''; 
            }
            BoldThemesFrameworkTemplate::$content   = wp_kses_post($content_html);             
            
            BoldThemesFrameworkTemplate::$tour_rwmb_destination     = !empty($tour_destinations) && isset($tour->post_title) ? $tour_destinations : array();            
            if ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_destination)  ) {
                if ( count(BoldThemesFrameworkTemplate::$tour_rwmb_destination) > 1 ){
                     BoldThemesFrameworkTemplate::$tour_rwmb_destination_text = __('More than 1', 'bt_plugin');
                }else{
                     BoldThemesFrameworkTemplate::$tour_rwmb_destination_text = implode( ", ", array_map(function ($object) { return $object->name; }, BoldThemesFrameworkTemplate::$tour_rwmb_destination) );
                }
            }
            foreach ( BoldThemesFrameworkTemplate::$tour_rwmb_destination as &$destination){
                $t_id = $destination->term_id;
                $term_meta = get_option( "taxonomy_$t_id" );                   
                $destination->page_slug   = isset($term_meta['tour_destination_meta_page_slug']) ? $term_meta['tour_destination_meta_page_slug'] : '';                
                $destination->more_about  = isset($term_meta['tour_destination_meta_more_about']) ? $term_meta['tour_destination_meta_more_about'] : '';  
                
                $destination->map_lat         = isset($term_meta['tour_destination_meta_lat']) ? $term_meta['tour_destination_meta_lat'] : '';
                $destination->map_lng         = isset($term_meta['tour_destination_meta_lng']) ? $term_meta['tour_destination_meta_lng'] : '';  
                
                if ( isset($term_meta['tour_destination_meta_map_lat']) && $term_meta['tour_destination_meta_map_lat'] != '' ){
                    $destination->map_lat = $term_meta['tour_destination_meta_map_lat'];
                }
                if ( isset($term_meta['tour_destination_meta_map_lng']) && $term_meta['tour_destination_meta_map_lng'] != '' ){
                    $destination->map_lng = $term_meta['tour_destination_meta_map_lng'];
                }
            }
                        
            unset($destination);             
            // /tour start location and destinations
            
            // tour general settings
            BoldThemesFrameworkTemplate::$tour_rwmb_departure_location  = rwmb_meta('tour_departure_location', null, $post_id ) != '' ? rwmb_meta('tour_departure_location', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_return_location     = rwmb_meta('tour_return_location', null, $post_id ) != '' ? rwmb_meta('tour_return_location', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_supertitle          = rwmb_meta('tour_supertitle', null, $post_id ) != '' ?rwmb_meta('tour_supertitle', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_title               = rwmb_meta('tour_title', null, $post_id ) != '' ?rwmb_meta('tour_title', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_subtitle            = rwmb_meta('tour_subtitle', null, $post_id ) != '' ?rwmb_meta('tour_subtitle', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_regular_price       = rwmb_meta('tour_regular_price', null, $post_id ) != '' ?rwmb_meta('tour_regular_price', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_original_price      = rwmb_meta('tour_original_price', null, $post_id ) != '' ? rwmb_meta('tour_original_price', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_discount_title      = rwmb_meta('tour_discount_title', null, $post_id ) != '' ? rwmb_meta('tour_discount_title', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_travellers          = rwmb_meta('tour_travellers', null, $post_id ) != '' ?rwmb_meta('tour_travellers', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_time                = rwmb_meta('tour_time', null, $post_id ) != '' ?rwmb_meta('tour_time', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_duration            = rwmb_meta('tour_duration', null, $post_id ) != '' ?rwmb_meta('tour_duration', null, $post_id ) : '' ;
            
            // tour media
            BoldThemesFrameworkTemplate::$tour_rwmb_images              = rwmb_meta( 'tour_images', array( 'size' => 'boldthemes_large_square' ), $post_id );           
            BoldThemesFrameworkTemplate::$tour_rwmb_featured_images     = rwmb_meta( 'tour_featured_images', array( 'size' => 'full' ), $post_id );            
            BoldThemesFrameworkTemplate::$tour_rwmb_featured_video      = bt_check_get_tour_rwmb_data( 'tour_featured_video', $post_id );
            
            // tour informations
            BoldThemesFrameworkTemplate::$tour_rwmb_dt          = bt_check_get_tour_rwmb_data( 'tour_dt', $post_id );
            BoldThemesFrameworkTemplate::$tour_rwmb_dt_text     = __('Not specified yet', 'bt_plugin');
            if ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_dt) ){                
                $tour_rwmb_dt_arr = array();
                foreach ( BoldThemesFrameworkTemplate::$tour_rwmb_dt as $tour_rwmb_dt) {
                    $sada_dt    = new DateTime();
                    $tour_dt    = new DateTime($tour_rwmb_dt);
                    if ( $sada_dt <= $tour_dt ) {
                        array_push($tour_rwmb_dt_arr,$tour_rwmb_dt);
                    }
                }
                BoldThemesFrameworkTemplate::$tour_rwmb_dt = $tour_rwmb_dt_arr;
            }            
            if ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_dt) ){
                BoldThemesFrameworkTemplate::$tour_rwmb_dt = array_values(BoldThemesFrameworkTemplate::$tour_rwmb_dt); //reindexed
                asort(BoldThemesFrameworkTemplate::$tour_rwmb_dt);//sort by date descending
                foreach ( BoldThemesFrameworkTemplate::$tour_rwmb_dt as $tour_rwmb_dt) {
                        BoldThemesFrameworkTemplate::$tour_rwmb_dt_text = date( BoldThemesFrameworkTemplate::$tour_rwmb_dt_format,  strtotime($tour_rwmb_dt) );
                        break;
                }
            }
            
            BoldThemesFrameworkTemplate::$tour_rwmb_price_include           = rwmb_meta('tour_price_include', null, $post_id ) != '' ? rwmb_meta('tour_price_include', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_price_no_include        = rwmb_meta('tour_price_no_include', null, $post_id ) != '' ? rwmb_meta('tour_price_no_include', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_location_description    = rwmb_meta('tour_location_description', null, $post_id ) != '' ? rwmb_meta('tour_location_description', null, $post_id ) : '' ;
            BoldThemesFrameworkTemplate::$tour_rwmb_fechas_de_salida        = rwmb_meta('tour_fechas_de_salida', null, $post_id ) != '' ? rwmb_meta('tour_fechas_de_salida', null, $post_id ) : '' ;
            
            //tour plan
            BoldThemesFrameworkTemplate::$tour_rwmb_plan                    = rwmb_meta('tour_plan', null, $post_id ) != '' ? rwmb_meta('tour_plan', null, $post_id ) : '' ;            
            BoldThemesFrameworkTemplate::$tour_rwmb_plan_title              = bt_check_get_tour_rwmb_data( 'tour_plan_title', $post_id );
            BoldThemesFrameworkTemplate::$tour_rwmb_plan_headline           = bt_check_get_tour_rwmb_data( 'tour_plan_headline', $post_id );
            BoldThemesFrameworkTemplate::$tour_rwmb_plan_description        = bt_check_get_tour_rwmb_data( 'tour_plan_description', $post_id );
           
            //tour additional info
            BoldThemesFrameworkTemplate::$tour_rwmb_additional_prices       = bt_check_get_tour_rwmb_data( 'tour_additional_prices', $post_id );
            BoldThemesFrameworkTemplate::$tour_rwmb_additional_info         = bt_check_get_tour_rwmb_data( 'tour_additional_info', $post_id );
            BoldThemesFrameworkTemplate::$tour_rwmb_precios                 = bt_check_get_tour_rwmb_data( 'tour_precios', $post_id );
            BoldThemesFrameworkTemplate::$tour_rwmb_viva_plus               = bt_check_get_tour_rwmb_data( 'tour_viva_plus', $post_id );
            BoldThemesFrameworkTemplate::$tour_rwmb_suplementos             = bt_check_get_tour_rwmb_data( 'tour_suplementos', $post_id );
            BoldThemesFrameworkTemplate::$tour_rwmb_additional_custom       = bt_check_get_tour_rwmb_data( 'tour_additional_custom', $post_id );

            //map files
            BoldThemesFrameworkTemplate::$tour_rwmb_map_embed           = rwmb_meta('tour_map_embed', null, $post_id ) != '' ? rwmb_meta('tour_map_embed', null, $post_id ) : '' ;
            $tour_rwmb_map_files  = rwmb_meta('tour_map_file',array( 'limit' => 1 ), $post_id ) != '' ? rwmb_meta('tour_map_file', array( 'limit' => 1 ), $post_id ) : array() ;
            if ( is_array( $tour_rwmb_map_files ) && !empty( $tour_rwmb_map_files) ){
                $tour_rwmb_map_file = reset( $tour_rwmb_map_files );
                if ( isset( $tour_rwmb_map_file['url'] ) ){
                    BoldThemesFrameworkTemplate::$tour_rwmb_map_file = $tour_rwmb_map_file['url'];
                }
            }
               
            BoldThemesFrameworkTemplate::$post_user_rating = boldthemes_get_post_user_rating( $post_id );           
                 
            return true;
    }
}

if ( ! function_exists( 'bt_check_get_tour_rwmb_data' ) ) {
    function bt_check_get_tour_rwmb_data( $meta_key = '', $post_id = 0 ) {        
        if ( !empty( rwmb_meta( $meta_key, null, $post_id ) ) ){
            $tour_rwmb_meta_value = rwmb_meta($meta_key, null, $post_id );
            if ( isset($tour_rwmb_meta_value[0]) ){
                if ( $tour_rwmb_meta_value[0] == '' ){
                    return array();
                }
            }
        }
        return rwmb_meta( $meta_key, null, $post_id );
    }
}

if ( ! function_exists( 'bt_get_tour_options_data' ) ) {
    function bt_get_tour_options_data() {
            BoldThemesFrameworkTemplate::$tour_rwmb_dt_format  = get_option( 'date_format' ) . ' ' . get_option( 'time_format' ); 
        
            BoldThemesFrameworkTemplate::$tour_default_image   = boldthemes_get_option( 'tour_default_image' )  != '' 
                    ? boldthemes_get_option( 'tour_default_image' ) : BoldThemes_Customize_Default::$data['tour_default_image'];
            
            BoldThemesFrameworkTemplate::$tour_currency        = boldthemes_get_option( 'tour_currency' )  != '' 
                    ? boldthemes_get_option( 'tour_currency' ) : BoldThemes_Customize_Default::$data['tour_currency'];
            
            BoldThemesFrameworkTemplate::$tour_currency_before_price        = boldthemes_get_option( 'tour_currency_before_price' )  != '' 
                    ? boldthemes_get_option( 'tour_currency_before_price' ) : BoldThemes_Customize_Default::$data['tour_currency_before_price'];
            
            BoldThemesFrameworkTemplate::$tour_similar_tours_list_number     = boldthemes_get_option( 'tour_similar_tours_list_number' )  != '' 
                    ? boldthemes_get_option( 'tour_similar_tours_list_number' )  : BoldThemes_Customize_Default::$data['tour_similar_tours_list_number'];
        
           
                        
            BoldThemesFrameworkTemplate::$tour_single_design     = boldthemes_get_option( 'tour_single_design' )  != '' 
                    ? boldthemes_get_option( 'tour_single_design' )  : BoldThemes_Customize_Default::$data['tour_single_design'];
            
            BoldThemesFrameworkTemplate::$tour_similar_tours_list_gap     = boldthemes_get_option( 'tour_similar_tours_list_gap' )  != '' 
                    ? boldthemes_get_option( 'tour_similar_tours_list_gap' )  : BoldThemes_Customize_Default::$data['tour_similar_tours_list_gap'];
            
            BoldThemesFrameworkTemplate::$tour_similar_tours_list_columns     = boldthemes_get_option( 'tour_similar_tours_list_columns' )  != '' 
                    ? boldthemes_get_option( 'tour_similar_tours_list_columns' )  : BoldThemes_Customize_Default::$data['tour_similar_tours_list_columns'];
            
            BoldThemesFrameworkTemplate::$tour_pin_normal           =  boldthemes_get_option( 'tour_pin_normal' )     != '' 
                    ? boldthemes_get_option( 'tour_pin_normal' )    : '';
            
            BoldThemesFrameworkTemplate::$tour_pin_selected         =  boldthemes_get_option( 'tour_pin_selected' )   != '' 
                    ? boldthemes_get_option( 'tour_pin_selected' )  : '';
            
            BoldThemesFrameworkTemplate::$tour_grid_gallery_columns = boldthemes_get_option( 'tour_grid_gallery_columns' )  != '' 
                    ? boldthemes_get_option( 'tour_grid_gallery_columns' ): BoldThemes_Customize_Default::$data['tour_grid_gallery_columns'];
            
            BoldThemesFrameworkTemplate::$tour_grid_gallery_gap     = boldthemes_get_option( 'tour_grid_gallery_gap' )  != '' 
                    ? boldthemes_get_option( 'tour_grid_gallery_gap' )  : BoldThemes_Customize_Default::$data['tour_grid_gallery_gap'];
            
            BoldThemesFrameworkTemplate::$tour_single_author_review = boldthemes_get_option( 'tour_single_author_review' )  != '' 
                    ? boldthemes_get_option( 'tour_single_author_review' ) : BoldThemes_Customize_Default::$data['tour_single_author_review'];
            
            BoldThemesFrameworkTemplate::$custom_map_style          = boldthemes_get_option( 'tour_custom_map_style' )   != '' 
                    ? boldthemes_get_option( 'tour_custom_map_style' )     : '';
            
            BoldThemesFrameworkTemplate::$tour_api_key              = boldthemes_get_option( 'tour_api_key' )   != '' 
                    ? boldthemes_get_option( 'tour_api_key' )     : BoldThemes_Customize_Default::$data['tour_api_key'];
            
            BoldThemesFrameworkTemplate::$tour_gmap_zoom           = boldthemes_get_option( 'tour_gmap_zoom' ) != '' 
                    ? boldthemes_get_option( 'tour_gmap_zoom' ) : 12;
            
            BoldThemesFrameworkTemplate::$tour_gmap_lat           = boldthemes_get_option( 'tour_gmap_lat' ) != '' 
                    ? boldthemes_get_option( 'tour_gmap_lat' ) : BoldThemes_Customize_Default::$data['tour_gmap_lat'];
            
            BoldThemesFrameworkTemplate::$tour_gmap_lng           = boldthemes_get_option( 'tour_gmap_lng' ) != '' 
                    ? boldthemes_get_option( 'tour_gmap_lng' ) : BoldThemes_Customize_Default::$data['tour_gmap_lng'];
       
            BoldThemesFrameworkTemplate::$tour_search_action_type       = boldthemes_get_option( 'tour_search_action_type' ) != ''
                    ? boldthemes_get_option( 'tour_search_action_type' ) : BoldThemes_Customize_Default::$data['tour_search_action_type'];
            
            BoldThemesFrameworkTemplate::$tour_search_show_categories      = boldthemes_get_option( 'tour_search_show_categories' ) != ''
                    ? boldthemes_get_option( 'tour_search_show_categories' ) : BoldThemes_Customize_Default::$data['tour_search_show_categories'];
            
            BoldThemesFrameworkTemplate::$tour_search_show_only_months      = boldthemes_get_option( 'tour_search_show_only_months' ) != ''
                    ? boldthemes_get_option( 'tour_search_show_only_months' ) : BoldThemes_Customize_Default::$data['tour_search_show_only_months'];
            
            BoldThemesFrameworkTemplate::$posts_per_page    = boldthemes_get_option( 'tour_tours_per_page' ) != ''
                    ? boldthemes_get_option( 'tour_tours_per_page' ) : BoldThemes_Customize_Default::$data['tour_tours_per_page'];
            
            BoldThemesFrameworkTemplate::$tour_list_count     = BoldThemesFrameworkTemplate::$posts_per_page;
            BoldThemesFrameworkTemplate::$tour_listing_pagination = boldthemes_get_option( 'tour_listing_pagination' ) != '' ? 
                boldthemes_get_option( 'tour_listing_pagination' ) : BoldThemes_Customize_Default::$data['tour_listing_pagination'];
            
            BoldThemesFrameworkTemplate::$tour_contact_form_booking = boldthemes_get_option( 'tour_contact_form_booking' ) != '' ? 
                boldthemes_get_option( 'tour_contact_form_booking' ) : BoldThemes_Customize_Default::$data['tour_contact_form_booking'];
            
            BoldThemesFrameworkTemplate::$tour_contact_form_enquiry = boldthemes_get_option( 'tour_contact_form_enquiry' ) != '' ? 
                boldthemes_get_option( 'tour_contact_form_enquiry' ) : BoldThemes_Customize_Default::$data['tour_contact_form_enquiry'];
            
            BoldThemesFrameworkTemplate::$tour_contact_form_booking_show = boldthemes_get_option( 'tour_contact_form_booking_show' ) != '' ? 
                boldthemes_get_option( 'tour_contact_form_booking_show' ) : BoldThemes_Customize_Default::$data['tour_contact_form_booking_show'];
            
            BoldThemesFrameworkTemplate::$tour_contact_form_enquiry_show = boldthemes_get_option( 'tour_contact_form_enquiry_show' ) != '' ? 
                boldthemes_get_option( 'tour_contact_form_enquiry_show' ) : BoldThemes_Customize_Default::$data['tour_contact_form_enquiry_show'];
            
            BoldThemesFrameworkTemplate::$tour_contact_form_booking_enquiry_show = boldthemes_get_option( 'tour_contact_form_booking_enquiry_show' ) != '' ? 
                boldthemes_get_option( 'tour_contact_form_booking_enquiry_show' ) : BoldThemes_Customize_Default::$data['tour_contact_form_booking_enquiry_show'];
            
            BoldThemesFrameworkTemplate::$tour_similar_tours_list_by_type = boldthemes_get_option( 'tour_similar_tours_list_by_type' ) != '' ? 
                boldthemes_get_option( 'tour_similar_tours_list_by_type' ) : BoldThemes_Customize_Default::$data['tour_similar_tours_list_by_type']; 
            
            BoldThemesFrameworkTemplate::$tour_search_date_before_after_days = boldthemes_get_option( 'tour_search_date_before_after_days' ) != '' ? 
                boldthemes_get_option( 'tour_search_date_before_after_days' ) : BoldThemes_Customize_Default::$data['tour_search_date_before_after_days']; 
            
             BoldThemesFrameworkTemplate::$tour_single_user_review = boldthemes_get_option( 'tour_single_user_review' ) != '' ? 
                boldthemes_get_option( 'tour_single_user_review' ) : BoldThemes_Customize_Default::$data['tour_single_user_review']; 
    }
}

if ( ! function_exists( 'bt_get_tour_params_data' ) ) {
    function bt_get_tour_params_data( $params = array() ) {         

            BoldThemesFrameworkTemplate::$tour_gets = $params;
            
            BoldThemesFrameworkTemplate::$paged    = isset(BoldThemesFrameworkTemplate::$tour_gets['paged']) ? 
                    BoldThemesFrameworkTemplate::$tour_gets['paged'] : 1;                       
            
            BoldThemesFrameworkTemplate::$search_limit = boldthemes_get_tour_list_search_limit();            

            BoldThemesFrameworkTemplate::$search_keyword        = isset(BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_keyword']) ? 
                    BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_keyword'] : '';

            BoldThemesFrameworkTemplate::$search_destination    = isset(BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_destination']) ?
                    BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_destination'] : '';

            BoldThemesFrameworkTemplate::$search_start_date     =  isset(BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_date']) ?
                    BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_date'] : '';

            BoldThemesFrameworkTemplate::$search_price_from     = isset(BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_price_from']) ?
                    BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_price_from'] : '';

            BoldThemesFrameworkTemplate::$search_price_to       = isset(BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_price_to']) ?
                    BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_price_to'] : '';

            BoldThemesFrameworkTemplate::$search_categories = array();
            if ( isset(BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_category']) && is_array(BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_category'])) {
                foreach (  BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_category'] as $tour_cat_key => $tour_cat_value ){
                    array_push( BoldThemesFrameworkTemplate::$search_categories, $tour_cat_value );
                }
            }

            BoldThemesFrameworkTemplate::$search_sorting       = isset(BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_sorting']) ?
                    BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_sorting'] : '';            

            BoldThemesFrameworkTemplate::$search_orderby        = 'post_title';
            BoldThemesFrameworkTemplate::$search_order          = 'ASC';            
            if ( isset(BoldThemesFrameworkTemplate::$search_sorting) && BoldThemesFrameworkTemplate::$search_sorting > 0 ){
                    switch ( BoldThemesFrameworkTemplate::$search_sorting ){
                            case '0':	$listing_orderby = 'post_date';	
                                            $listing_order = 'DESC';break;
                            case '1':	$listing_orderby = 'post_date';	
                                            $listing_order = 'ASC';	break;
                            case '2':	$listing_orderby = 'post_title';
                                            $listing_order = 'ASC';	break;
                            case '3':	$listing_orderby = 'post_title';
                                            $listing_order = 'DESC';break;
                            case '4':	$listing_orderby = 'price_from';     
                                            $listing_order = 'ASC';break;
                            case '5':	$listing_orderby = 'price_from';     
                                            $listing_order = 'DESC';break;
                            default:	$listing_orderby = 'post_date';
                                            $listing_order = 'DESC';break;
                    }
                    BoldThemesFrameworkTemplate::$search_orderby    = $listing_orderby;
                    BoldThemesFrameworkTemplate::$search_order      = $listing_order;
            }
            
            BoldThemesFrameworkTemplate::$posts_per_page    = boldthemes_get_option( 'tour_tours_per_page' ) != ''
                    ? boldthemes_get_option( 'tour_tours_per_page' ) : BoldThemes_Customize_Default::$data['tour_tours_per_page'];
            
            BoldThemesFrameworkTemplate::$search_date_format  =  isset(BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_date_format']) ?
                    BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_date_format'] : '';
            
            BoldThemesFrameworkTemplate::$search_show_months  =  isset(BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_show_months']) ?
                    BoldThemesFrameworkTemplate::$tour_gets['bt_tour_search_show_months'] : '';
   
    }
}

/**
 * Get tour sraech list limit
 */
if ( ! function_exists( 'boldthemes_get_tour_list_search_limit' ) ) {
	function boldthemes_get_tour_list_search_limit( $limit = '' ) { 
            if( $limit != '' ) {
                BoldThemesFrameworkTemplate::$search_limit = $limit;
                return BoldThemesFrameworkTemplate::$search_limit;
            }           
            BoldThemesFrameworkTemplate::$search_limit     = boldthemes_get_option( 'tour_similar_tours_list_number' )  != '' 
                    ? boldthemes_get_option( 'tour_similar_tours_list_number' )  : BoldThemes_Customize_Default::$data['tour_similar_tours_list_number'];
            
            if ( isset($_GET['bt_tour_search_list_count']) && $_GET['bt_tour_search_list_count'] != '' ){
                BoldThemesFrameworkTemplate::$search_limit = $_GET['bt_tour_search_list_count'];
            }                        
            return BoldThemesFrameworkTemplate::$search_limit;
        }
}

/**
 * Get tour list type
 */
if ( ! function_exists( 'boldthemes_get_tour_list_type' ) ) {
	function boldthemes_get_tour_list_type( $type = '' ) { 
            
            if( $type != '' ) {
                BoldThemesFrameworkTemplate::$tour_single_design = $type;
                return BoldThemesFrameworkTemplate::$tour_single_design;
            }            
            BoldThemesFrameworkTemplate::$tour_single_design     = boldthemes_get_option( 'tour_single_design' )  != '' 
                    ? boldthemes_get_option( 'tour_single_design' )  : BoldThemes_Customize_Default::$data['tour_single_design'];
            
            if ( isset($_GET['bt_tour_single_design']) && $_GET['bt_tour_single_design'] != '' ){
                BoldThemesFrameworkTemplate::$tour_single_design = $_GET['bt_tour_single_design'];
            }  
            
            return BoldThemesFrameworkTemplate::$tour_single_design;
        }
}

/**
 * Get tour list gap
 */
if ( ! function_exists( 'boldthemes_get_tour_list_gap' ) ) {
	function boldthemes_get_tour_list_gap( $gap = '' ) {  
            if( $gap != '' ) {
                BoldThemesFrameworkTemplate::$tour_similar_tours_list_columns = $gap;
                return BoldThemesFrameworkTemplate::$tour_similar_tours_list_gap;
            }            
            BoldThemesFrameworkTemplate::$tour_similar_tours_list_gap     = boldthemes_get_option( 'tour_similar_tours_list_gap' )  != '' 
                        ? boldthemes_get_option( 'tour_similar_tours_list_gap' )  : BoldThemes_Customize_Default::$data['tour_similar_tours_list_gap'];
            
            if ( isset($_GET['tour_similar_tours_list_gap']) && $_GET['tour_similar_tours_list_gap'] != '' ){
                BoldThemesFrameworkTemplate::$tour_similar_tours_list_gap = $_GET['tour_similar_tours_list_gap'];
            }
            return BoldThemesFrameworkTemplate::$tour_similar_tours_list_gap;
        }
}

/**
 * Get tour list columns
 */
if ( ! function_exists( 'boldthemes_get_tour_list_columns' ) ) {
	function boldthemes_get_tour_list_columns( $columns = '' ) { 
            
            // param ( shortcode ), get ( ajax , GET ), option
            if( $columns != '' ) {
                BoldThemesFrameworkTemplate::$tour_similar_tours_list_columns = $columns;
                return BoldThemesFrameworkTemplate::$tour_similar_tours_list_columns;
            }            
            BoldThemesFrameworkTemplate::$tour_similar_tours_list_columns     = boldthemes_get_option( 'tour_similar_tours_list_columns' )  != '' 
                        ? boldthemes_get_option( 'tour_similar_tours_list_columns' )  : BoldThemes_Customize_Default::$data['tour_similar_tours_list_columns'];
            
            if ( isset($_GET['tour_similar_tours_list_columns']) && $_GET['tour_similar_tours_list_columns'] != '' ){
                 BoldThemesFrameworkTemplate::$tour_similar_tours_list_columns = $_GET['tour_similar_tours_list_columns'];
            }
            
            if ( BoldThemesFrameworkTemplate::$tour_single_design == 'btListDesignList' ) {
                BoldThemesFrameworkTemplate::$tour_similar_tours_list_columns = '';
            }
            return  BoldThemesFrameworkTemplate::$tour_similar_tours_list_columns;
        }
}

if ( ! function_exists( 'bt_get_tour_original_price' ) ) {
    function bt_get_tour_original_price() { 
        if ( BoldThemesFrameworkTemplate::$tour_currency_before_price ){
            $price_text_original = BoldThemesFrameworkTemplate::$tour_currency . BoldThemesFrameworkTemplate::$tour_rwmb_original_price;
        }else{
            $price_text_original = BoldThemesFrameworkTemplate::$tour_rwmb_original_price . BoldThemesFrameworkTemplate::$tour_currency;
        }
        return $price_text_original;
    }
}

if ( ! function_exists( 'bt_get_tour_regular_price' ) ) {
    function bt_get_tour_regular_price() { 
        if ( BoldThemesFrameworkTemplate::$tour_currency_before_price ){
            $price_text_regular  = BoldThemesFrameworkTemplate::$tour_currency . BoldThemesFrameworkTemplate::$tour_rwmb_regular_price;
        }else{
            $price_text_regular  = BoldThemesFrameworkTemplate::$tour_rwmb_regular_price .BoldThemesFrameworkTemplate::$tour_currency;
        }
        return $price_text_regular;
    }
}

if ( ! function_exists( 'bt_get_tour_price' ) ) {
    function bt_get_tour_price( $price = 0 ) {
        $price_text = $price;
        if ( $price > 0 ){
            if ( BoldThemesFrameworkTemplate::$tour_currency_before_price ){
                $price_text  = BoldThemesFrameworkTemplate::$tour_currency . $price;
            }else{
                $price_text  = $price .BoldThemesFrameworkTemplate::$tour_currency;
            }
        }
        return $price_text;
    }
}

if ( ! function_exists( 'bt_get_tour_destinations_by_slugs' ) ) {
    function bt_get_tour_destinations_by_slugs( $slug = '', $delimeter = ';' ) {
        $search_destinations = array();
        if ( $slug != '' ){
            $_destinations = explode($delimeter, $slug);
            if ( is_array($_destinations) && !empty($_destinations) ){                        
                foreach ( $_destinations as $_destination ){ 
                    $_destination_term = get_term_by('slug', trim($_destination), 'tour_destination');
                    if ( isset($_destination_term) && !empty($_destination_term) ){
                        array_push( $search_destinations, $_destination_term->slug );
                    }
                }                        
            }
        }
        return $search_destinations;
    }
}

if ( ! function_exists( 'bt_get_tour_categories_by_slugs' ) ) {
    function bt_get_tour_categories_by_slugs( $category = '', $delimeter = ';' ) {
        $search_categories = array();
        if ( $category != '' ){
            $_categories = explode( $delimeter, $category );
            if ( is_array($_categories) && !empty($_categories) ){                        
                foreach ( $_categories as $_category ){ 
                    $_category_term = get_term_by('slug', trim($_category), 'tour_category');
                    if ( isset($_category_term) && !empty($_category_term) ){
                        array_push( $search_categories, $_category_term->term_id );
                    }
                }                        
            }
        }        
        return $search_categories;
    }
}

if ( ! function_exists( 'bt_get_tour_image_by_type' ) ) {
    function bt_get_tour_image_by_type( $post_id = 0, $type = '' ) {       
        switch ( $type ){
                case 'btListDesignList':
                    $image_size = 'boldthemes_large_square';
                    break;
                case 'btListDesignRegular':
                    $image_size = 'boldthemes_tour_medium_rectangle';
                    break;
                case 'btListDesignGallery':
                    $image_size = 'boldthemes_tour_medium_portrait';
                    break;
                default:
                    $image_size = 'boldthemes_large_square';
                    break;
            }            
            BoldThemesFrameworkTemplate::$feat_image             = $post_id > 0 ? 
                    boldthemes_get_tour_featured_image( $post_id )  : BoldThemesFrameworkTemplate::$tour_default_image;
            BoldThemesFrameworkTemplate::$feat_image_tour_list   = $post_id > 0 ? 
                    boldthemes_get_tour_featured_image( $post_id, $image_size )  : BoldThemesFrameworkTemplate::$tour_default_image;
    }
}

if ( ! function_exists( 'boldthemes_get_tour_default_image_by_type' ) ) {
	function boldthemes_get_tour_default_image_by_type( $post_id = null, $view_type = '' ) { 
            $image_default_arr = array();            
            switch ( $view_type ){
                case 'btListDesignList':
                    $image_size = 'boldthemes_large_square';
                    break;
                case 'btListDesignRegular':
                    $image_size = 'boldthemes_tour_medium_rectangle';
                    break;
                case 'btListDesignGallery':
                    $image_size = 'boldthemes_tour_medium_portrait';
                    break;
                default:
                    $image_size = 'boldthemes_large_square';
                    break;
            }
            
            BoldThemesFrameworkTemplate::$tour_default_image        = boldthemes_get_option( 'tour_default_image' )  != '' 
                    ? boldthemes_get_option( 'tour_default_image' ) : BoldThemes_Customize_Default::$data['tour_default_image'];            
            
            $default_image_id       = bt_get_image_id(BoldThemesFrameworkTemplate::$tour_default_image);   
            $img_default            = wp_get_attachment_image_src( $default_image_id, $image_size );
            $img_src_default        = isset($img_default[0]) ? $img_default[0] : BoldThemesFrameworkTemplate::$tour_default_image;            
            $img_full_default       = wp_get_attachment_image_src( $default_image_id, 'full' );
            $img_src_full_default   = isset($img_full_default[0]) ? $img_full_default[0] : BoldThemesFrameworkTemplate::$tour_default_image;
            
            $image_default_arr['image_id']      = $default_image_id;
            $image_default_arr['img_src']       = $img_src_default;
            $image_default_arr['img_src_full']  = $img_src_full_default;
            $image_default_arr['img_size']      = $image_size;
            $image_default_arr['img_post_id']   = $post_id;
            
            return $image_default_arr;
        }
}

if ( ! function_exists( 'boldthemes_get_tour_default_image' ) ) {
	function boldthemes_get_tour_default_image( $post_id = null, $image_size = 'boldthemes_large_square' ) { 
            $image_default_arr = array();
            BoldThemesFrameworkTemplate::$tour_default_image        = boldthemes_get_option( 'tour_default_image' )  != '' 
                    ? boldthemes_get_option( 'tour_default_image' ) : BoldThemes_Customize_Default::$data['tour_default_image'];            
            
            $default_image_id       = bt_get_image_id(BoldThemesFrameworkTemplate::$tour_default_image);            
            $img_default            = wp_get_attachment_image_src( $default_image_id, $image_size );
            $img_src_default        = isset($img_default[0]) ? $img_default[0] : BoldThemesFrameworkTemplate::$tour_default_image;            
            $img_full_default       = wp_get_attachment_image_src( $default_image_id, 'full' );
            $img_src_full_default   = isset($img_full[0]) ? $img_full[0] : BoldThemesFrameworkTemplate::$tour_default_image;
            
            $image_default_arr['image_id']      = $default_image_id;
            $image_default_arr['img_src']       = $img_src_default;
            $image_default_arr['img_src_full']  = $img_src_full_default;
            $image_default_arr['img_size']      = $image_size;
            $image_default_arr['img_post_id']   = $post_id;
            
            return $image_default_arr;
        }
}



/**
 * Get post featured image
 */
if ( ! function_exists( 'boldthemes_get_tour_featured_image' ) ) {
	function boldthemes_get_tour_featured_image( $post_id = null, $image_size = 'boldthemes_large_square' ) {            
            $image_arr = array();
            
            $img_default = boldthemes_get_tour_default_image( $post_id, $image_size );
            $img_src_default = $img_default['img_src'];
            $img_src_full_default = $img_default['img_src_full'];
            
            $image_id       = get_post_thumbnail_id( $post_id );             
            $img            = wp_get_attachment_image_src( $image_id, $image_size );
            $img_src        = isset($img[0]) ? $img[0] : $img_src_default;            
            $img_full       = wp_get_attachment_image_src( $image_id, 'full' );
            $img_src_full   = isset($img_full[0]) ? $img_full[0] : $img_src_full_default;
            
            $image_arr['image_id']      = $image_id;
            $image_arr['img_src']       = $img_src;
            $image_arr['img_src_full']  = $img_src_full;
            $image_arr['img_size']      = $image_size;
            $image_arr['img_post_id']   = $post_id;
            
            return $image_arr;
        }
}

/**
 * Get image by image id
 */
if ( ! function_exists( 'boldthemes_get_image_by_id' ) ) {
	function boldthemes_get_image_by_id( $image_id = 0, $image_size = 'boldthemes_large_square' ) {            
            $image_arr = array();
            $post_id = 0;
            $img_default = boldthemes_get_tour_default_image( $post_id, $image_size );
            $img_src_default = $img_default['img_src'];
            $img_src_full_default = $img_default['img_src_full'];
            
            $img            = wp_get_attachment_image_src( $image_id, $image_size );
            $img_src        = isset($img[0]) ? $img[0] : $img_src_default;            
            $img_full       = wp_get_attachment_image_src( $image_id, 'full' );
            $img_src_full   = isset($img_full[0]) ? $img_full[0] : $img_src_full_default;
            
            $image_arr['image_id']      = $image_id;
            $image_arr['img_src']       = $img_src;
            $image_arr['img_src_full']  = $img_src_full;
            $image_arr['img_size']      = $image_size;
            $image_arr['img_post_id']   = $post_id;
            
            return $image_arr;
        }
}

// Retrieves the attachment ID from the file URL
function bt_get_image_id($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
        return isset($attachment[0]) ? $attachment[0] : 0; 
}

/**
 * Get post author review rating
 */
if ( ! function_exists( 'boldthemes_get_post_rating' ) ) {
	function boldthemes_get_post_rating( $post_id = null ) {
		$review = boldthemes_rwmb_meta( 'tour_review', array(), $post_id );
		$review_arr = explode( PHP_EOL, $review );
		$sum = 0;
		foreach( $review_arr as $r ) {
			$r_arr = explode( ';', $r );
			if ( isset( $r_arr[1] ) ) {
				$item_rating = round( floatval( $r_arr[1] ) );
			} else {
				$item_rating = 0;
			}
			$sum += $item_rating;
		}
		$rating = round( $sum / count( $review_arr ) , 1 );
		return $rating;
	}
}

/**
 * Get post user review rating
 */
if ( ! function_exists( 'boldthemes_get_post_user_rating' ) ) {
	function boldthemes_get_post_user_rating( $post_id = null, $rating_field = 'rating' ) {
                $rating_arr = array();
                
                $rating_arr['rating']           = 0;
                $rating_arr['no_of_comments']   = 0;
                $rating_arr['percent']          = 0;
                
                $tour_show_rating = boldthemes_get_option( 'tour_show_rating' ); 
                if ( $tour_show_rating ) {
                    $comments = get_comments(array(
                        'post_id'   => $post_id,
                        'status'    => 'approve',
                    ));                
                    $sum = 0;
                    foreach($comments as $comment) {
                        $commentrating = get_comment_meta( $comment->comment_ID, $rating_field, true );                    
                        if ( isset( $commentrating ) ) {
                                $item_rating = round( floatval( $commentrating ) );
                        } else {
                                $item_rating = 0;
                        }
                        $sum += $item_rating;
                    } 
                    $rating = count( $comments ) > 0 ? round( $sum / count( $comments ) , 1 ) : 0;
                    $percent = $rating > 0 ? ( $rating / 5 ) * 100 : 0;
                    $rating_arr['rating']           = $rating;
                    $rating_arr['no_of_comments']   = count( $comments );
                    $rating_arr['percent']          = $percent;
                }
               
		return $rating_arr;
	}
}

/**
 * Get post tags
 */
if ( ! function_exists( 'boldthemes_get_post_tags_html' ) ) {
	function boldthemes_get_post_tags_html( $post_id = 0 ) {
            $post_id   = $post_id > 0 ? $post_id : get_the_ID();
            $tour_tags = get_the_terms( $post_id, 'tour_tag' );
            $tags_html = '';
            if ( $tour_tags ) {
                    foreach ( $tour_tags as $tag ) {
                            $tags_html .= '<li><a href="' . esc_attr( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li>';
                    }
                    $tags_html = rtrim( $tags_html, ', ' );
                    $tags_html = '<div class="btTags"><ul>' . $tags_html . '</ul></div>';
            }
            BoldThemesFrameworkTemplate::$tags_html = $tags_html;
            return BoldThemesFrameworkTemplate::$tags_html;
        }
}

// save custom fields on tour item save
add_action( 'save_post_tour', 'bt_tour_save' );
add_action( 'edit_post_tour', 'bt_tour_save' );
function bt_tour_save( $post_id ) {
	if ( isset( $_POST['post_ID'] ) ) {                
                if ( isset( $_POST['tour_regular_price'] ) && $_POST['tour_regular_price'] == '' ){  
                    update_post_meta( $post_id, 'tour_regular_price', '');
                }
                if ( isset( $_POST['tour_original_price'] ) && $_POST['tour_original_price'] == '' ){  
                    update_post_meta( $post_id, 'tour_original_price', '');
                }
	}
}

if ( ! function_exists( 'bt_filter_array_empty' ) ) {
	function bt_filter_array_empty( $items ) {
            $new_items = array();
            foreach ( $items as $item ) {
                if ( $item != ''){
                    array_push($new_items, $item);
                }
            }
            return $new_items;
        }
}

/*
 * Pagination output for post archive
 */
if ( ! function_exists( 'boldthemes_tour_pagination' ) ) {
	function boldthemes_tour_pagination() {
	
		$prev = get_previous_posts_link( esc_html__( 'Newer Posts', 'bt_plugin' ) );
		$next = get_next_posts_link( esc_html__( 'Older Posts', 'bt_plugin' ) );
		
		$extra_slass = '';
		if ( boldthemes_get_option( 'blog_list_view' ) == 'columns' ) {
			$extra_slass = 'btPostListColumns';
		} 		
		$pattern = '/(<a href=".*">)(.*)(<\/a>)/';

		
		if ( $prev != '' || $next != '' ) {
			echo '<div class="btPagination boldSection gutter ' . $extra_slass . '">';
				echo '<div class="port">';
					if ( $prev != '' ) {
						echo '<div class="paging onLeft">';
							echo '<p class="pagePrev">';
								echo preg_replace( $pattern, '<span class="nbsItem"><span class="nbsTitle">$2</span></span>', $prev );
							echo '</p>';
						echo '</div>';
					}
					if ( $next != '' ) {
						echo '<div class="paging onRight">';
							echo '<p class="pageNext">';
								echo preg_replace( $pattern, '<span class="nbsItem"><span class="nbsTitle">$2</span></span>', $next );
							echo '</p>';
						echo '</div>';
					}
				echo '</div>';
			echo '</div>';			
		}

	}
}

/*
 * Get user reviews for tour
 */
if ( ! function_exists( 'bt_tour_get_user_reviews' ) ) {
	function bt_tour_get_user_reviews( $comment_post_id = 0 ) {
                $tour_user_review_arr = array();
                
                $tour_show_rating   = boldthemes_get_option( 'tour_show_rating' ); 
                if ( $tour_show_rating ) {
                        BoldThemesFrameworkTemplate::$tour_single_user_review = boldthemes_get_option( 'tour_single_user_review' ) != '' ? 
                                    boldthemes_get_option( 'tour_single_user_review' ) : BoldThemes_Customize_Default::$data['tour_single_user_review']; 

                        if ( $comment_post_id > 0 ) {
                            $tour_user_review   = boldthemes_rwmb_meta( 'tour_user_review', array(), $comment_post_id ) != '' ?
                                    boldthemes_rwmb_meta( 'tour_user_review', array(), $comment_post_id ) : BoldThemesFrameworkTemplate::$tour_single_user_review;
                        }else{
                            $tour_user_review   = boldthemes_rwmb_meta( 'tour_user_review' ) != '' ?
                                    boldthemes_rwmb_meta( 'tour_user_review' ) : BoldThemesFrameworkTemplate::$tour_single_user_review;
                        }
                        
                        $tour_user_review_arr    = $tour_user_review != '' ? explode( ';', $tour_user_review ) : array();                
                }
                
                return $tour_user_review_arr;
        }
}

/*
 * Get current page number
 */
if ( ! function_exists( 'bt_tour_get_current_page' ) ) {
	function bt_tour_get_current_page() {
            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
            $paged = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : $paged;
            return $paged;
        }
}

/*
 * Upload filter for svg, kml, kmz files: theme specific
 */
function bt_add_upload_mimes($mimes) {
        $mimes['kml'] = 'application/xml';
        $mimes['kmz'] = 'application/zip';
        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
        return $mimes;
}
add_filter('upload_mimes', 'bt_add_upload_mimes');

function bt_set_tours_in_global() {
    global $tour_global;
    $tour_global = array();
}
add_action( 'init', 'bt_set_tours_in_global' );

function bt_add_tour_to_global( $tour_id ) {
    global $tour_global;
    
    if ( is_array( $tour_global ) && !in_array( $tour_id, $tour_global ) ){
        array_push( $tour_global, $tour_id ); 
    }
    
    return $tour_id;
}

function bt_set_tours_to_show( $repeat_tours = false, $tours = array() ) {
    if ( $repeat_tours ){
        return $tours;
    }
    global $tour_global;
    $i = 0;
    if ( is_array( $tours ) ) {
        foreach ( $tours as &$tour ) {
            if ( is_array( $tour_global ) && in_array( $tour->ID, $tour_global ) ){
                unset( $tours[$i]);
            }else{
                array_push( $tour_global, $tour->ID ); 
            }
            $i++;
        }        
        return array_values($tours);
    }
    
    return $tours;
}

// tour list order by tour slug in tour list shortcode parameters
if ( ! function_exists( 'bt_tour_list_cmp' ) ) {
    function bt_tour_list_cmp($a, $b) 
    { 
        global $search_tour_names;
        if ( !empty( $search_tour_names ) ){
            foreach($search_tour_names as $key => $value) { 
                if( isset($a->post_name) && $a->post_name == $value) { 
                    return 0; 
                    break; 
                } 
                if( isset($b->post_name) && $b->post_name == $value) { 
                    return 1; 
                    break; 
                } 
            } 
        }
    }
}

// convert a WP date format to a jQuery UI DatePicker format  
if ( ! function_exists( 'bt_set_wp_to_datepicker_format' ) ) {
    function bt_set_wp_to_datepicker_format( $wp_date_format, $show_only_months ) 
    {
        $returnValue = '';
        if ( $show_only_months ) {
            $chars = array( 
                'd' => '', 'j' => '', 'l' => '', 'D' => '',
                'm' => 'mm', 'n' => 'm', 'F' => 'MM', 'M' => 'M', 
                'Y' => 'yy', 'y' => 'y', 
            );
            $returnValue = trim(strtr((string)$wp_date_format, $chars));
        }else{
            $chars = array( 
                'd' => 'dd', 'j' => 'd', 'l' => 'DD', 'D' => 'D',
                'm' => 'mm', 'n' => 'm', 'F' => 'MM', 'M' => 'M', 
                'Y' => 'yy', 'y' => 'y', 
            );
            $returnValue = trim(strtr((string)$wp_date_format, $chars));
        }
        
        return $returnValue; 
    }
}

if ( ! function_exists( 'bt_set_datepicker_to_wp_format' ) ) {
    function bt_set_datepicker_to_wp_format( $wp_date_format ) 
    {
        $returnValue = '';
        $chars = array( 
                'dd' => 'd', 'd' => 'j', 'DD' => 'l', 'D' => 'D',
                'mm' => 'm', 'm' => 'n', 'MM' => 'F', 'M' => 'M', 
                'yy' => 'Y', 'y' => 'y', 
        );
        $returnValue = trim(strtr((string)$wp_date_format, $chars));
        
        return $returnValue; 
    }
}

// CF7 Booking AND Enquiry FORM 
add_action( 'wpcf7_init', 'bt_wpcf7_add_form_tag_tour' ); 
function bt_wpcf7_add_form_tag_tour() { 
     wpcf7_add_form_tag('tour_date', 'bt_wpcf7_date_form_tag_tour_date_handler', array( 'name-attr' => true ));
     wpcf7_add_form_tag('tour', 'bt_wpcf7_hidden_form_tag_tour_id_handler', array( 'name-attr' => true ));
     wpcf7_add_form_tag('tour_title', 'bt_wpcf7_hidden_form_tag_tour_title_handler', array( 'name-attr' => true ));
}

if ( ! function_exists( 'bt_wpcf7_date_form_tag_tour_date_handler' ) ) {
    function bt_wpcf7_date_form_tag_tour_date_handler( $tag ) { 
        $tag = new WPCF7_FormTag($tag);  
        $validation_error = wpcf7_get_validation_error( $tag->name );
        
        $class = wpcf7_form_controls_class( $tag->type );

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}
        $atts = array();
        $atts['class'] = $tag->get_class_option( $class );
        $atts['aria-required'] = 'true';
	
        $atts['aria-invalid'] = $validation_error ? 'true' : 'false';
        
        $html = '';
        if ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_dt) && count(BoldThemesFrameworkTemplate::$tour_rwmb_dt) > 0 ) { 
            $html = '<select class="tour_date wpcf7-form-control wpcf7-tour_date wpcf7-validates-as-required '.$atts['class'].'" id="tour_date" name="tour_date" aria-required="'.$atts['aria-required'].'" aria-invalid="'.$atts['aria-invalid'].'">';
                $html .= '<option value="">'.esc_html__( 'Select Tour Date', 'bt_plugin' ).'</option>';
                foreach ( BoldThemesFrameworkTemplate::$tour_rwmb_dt as $tour_rwmb_dt ) {
                    $html .= '<option value="' . $tour_rwmb_dt . '">' . date( BoldThemesFrameworkTemplate::$tour_rwmb_dt_format,  strtotime($tour_rwmb_dt) ) . '</option>';
                }
            $html .= '</select>';
                        
        }else{
            $html = '<input type="hidden" id="tour_date" name="tour_date" class="tour_date_form">';
        }        
        return $html;
    } 
}

if ( ! function_exists( 'bt_wpcf7_hidden_form_tag_tour_id_handler' ) ) {
    function bt_wpcf7_hidden_form_tag_tour_id_handler( $tag ) { 
        $tag = new WPCF7_FormTag($tag);
        if (empty($tag->name)) {
            return '';
        }
        $atts = array();
        $class = wpcf7_form_controls_class($tag->type);
        $atts['class'] = $tag->get_class_option($class);
        $atts['id'] = $tag->get_id_option();
        $atts['value'] = (string) get_the_ID();
        $atts['type'] = 'hidden';
        $atts['name'] = $tag->name;
        $atts = wpcf7_format_atts($atts);
        $html = sprintf('<input %s />', $atts);
        return $html;
    } 
}

if ( ! function_exists( 'bt_wpcf7_hidden_form_tag_tour_title_handler' ) ) {
    function bt_wpcf7_hidden_form_tag_tour_title_handler( $tag ) { 
        $tag = new WPCF7_FormTag($tag);
        if (empty($tag->name)) {
            return '';
        }
        $atts = array();
        $class = wpcf7_form_controls_class($tag->type);
        $atts['class'] = $tag->get_class_option($class);
        $atts['id'] = $tag->get_id_option();
        $atts['value'] = (string) esc_attr(get_the_title());
        $atts['type'] = 'hidden';
        $atts['name'] = $tag->name;
        $atts = wpcf7_format_atts($atts);
        $html = sprintf('<input %s />', $atts);
        return $html;
    } 
}

add_filter( 'wpcf7_support_html5_fallback', '__return_true' );

if ( ! function_exists( 'bt_rewrite_flush' ) ) {
	function bt_rewrite_flush() {
		// First, we "add" the custom post type via the above written function.
		// Note: "add" is written with quotes, as CPTs don't get added to the DB,
		// They are only referenced in the post_type column with a post entry, 
		// when you add a post of this CPT.
		bt_create_portfolio();
                bt_create_tour();

		// ATTENTION: This is *only* done during plugin activation hook in this example!
		// You should *NEVER EVER* do this on every page load!!
		flush_rewrite_rules();
	}
}
register_activation_hook( __FILE__, 'bt_rewrite_flush' );