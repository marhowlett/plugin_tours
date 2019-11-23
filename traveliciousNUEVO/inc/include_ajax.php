<?php
/* SEARCH TOURS AJAX */
add_action('wp_ajax_bt_get_tour_search_action', 'bt_get_tour_search_action_callback'); 
add_action('wp_ajax_nopriv_bt_get_tour_search_action', 'bt_get_tour_search_action_callback'); 

if ( ! function_exists( 'bt_get_tour_search_action_callback' ) ) {
    function bt_get_tour_search_action_callback(){        
        $params = array();
        if (isset($_GET)){            
               foreach($_GET as $field => $value) {
                       $params[$field] = $value;
               }
        } 
        tours_search_list_ajax_dump( $params );  
        die; 
    }
}

if ( ! function_exists( 'tours_search_list_ajax_dump' ) ) {
	function tours_search_list_ajax_dump( $params = array() ) {

           bt_get_tour_params_data( $params );           
           BoldThemesFrameworkTemplate::$tours  = boldthemes_get_query_items(
                    array(
                            'search_limit'       => BoldThemesFrameworkTemplate::$search_limit,
                            'search_orderby'     => BoldThemesFrameworkTemplate::$search_orderby,
                            'search_order'       => BoldThemesFrameworkTemplate::$search_order,
                            'search_keyword'     => BoldThemesFrameworkTemplate::$search_keyword,            
                            'search_destination' => BoldThemesFrameworkTemplate::$search_destination,
                            'search_start_date'  => BoldThemesFrameworkTemplate::$search_start_date,
                            'search_price_from'  => BoldThemesFrameworkTemplate::$search_price_from,
                            'search_price_to'    => BoldThemesFrameworkTemplate::$search_price_to,
                            'search_categories'  => BoldThemesFrameworkTemplate::$search_categories,
                            'paged'              => BoldThemesFrameworkTemplate::$paged,
                            'posts_per_page'     => BoldThemesFrameworkTemplate::$posts_per_page,
                            'search_show_months' => BoldThemesFrameworkTemplate::$search_show_months,
                            'search_date_format' => BoldThemesFrameworkTemplate::$search_date_format
                    ) 
            );
            echo boldthemes_get_tour_list_html( );                      
	}
}


// DESTINATIONS AUTOCOMPLETE
add_action('wp_ajax_bt_get_destinations_autocomplete_action', 'bt_get_destinations_autocomplete_action_callback'); 
add_action('wp_ajax_nopriv_bt_get_destinations_autocomplete_action', 'bt_get_destinations_autocomplete_action_callback'); 

if ( ! function_exists( 'bt_get_destinations_autocomplete_action_callback' ) ) {
    function bt_get_destinations_autocomplete_action_callback(){
        $s = $_GET['term'];
       
        $comma = _x( ',', 'page delimiter' );
        if ( ',' !== $comma )
            $s = str_replace( $comma, ',', $s );
        if ( false !== strpos( $s, ',' ) ) {
            $s = explode( ',', $s );
            $s = $s[count( $s ) - 1];
        }
        $s = trim( $s );

        $term_search_min_chars = 1;
        
        $args = array(
            'taxonomy'      => array( 'tour_destination' ), 
            'orderby'       => 'post_title', 
            'order'         => 'ASC',
            'hide_empty'    => false,
            'fields'        => 'all',
            'hierarchical'  => true,
            'search_name'   => $s
        );
        
        $terms = get_terms( $args );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){        
             foreach ($terms as $term) {
                 $results[] = array( $term->name );
             }             
            $response = $_GET["callback"]."(". json_encode($results) .")"; 
            echo $response;  
            exit;
        }
       wp_die();        
    }
}
// filter for start letters in term name
function wpse_178511_get_terms_fields( $clauses, $taxonomies, $args ) {
    if ( ! empty( $args['search_name'] ) ) {
        global $wpdb;
        $search_like = $wpdb->esc_like( $args['search_name'] );
        if ( ! isset( $clauses['where'] ) )
            $clauses['where'] = '1=1';

        $clauses['where'] .= $wpdb->prepare( " AND t.name LIKE %s", "$search_like%" );
    }
    return $clauses;
}
add_filter( 'terms_clauses', 'wpse_178511_get_terms_fields', 10, 3 );

// DESTINATIONS ALL AUTOCOMPLETE
add_action('wp_ajax_bt_get_destinations_all_autocomplete_action', 'bt_get_destinations_all_autocomplete_action_callback'); 
add_action('wp_ajax_nopriv_bt_get_destinations_all_autocomplete_action', 'bt_get_destinations_all_autocomplete_action_callback'); 

if ( ! function_exists( 'bt_get_destinations_all_autocomplete_action_callback' ) ) {
    function bt_get_destinations_all_autocomplete_action_callback(){
        $args = array(
            'taxonomy'      => array( 'tour_destination' ), 
            'orderby'       => 'post_title', 
            'order'         => 'ASC',
            'hide_empty'    => false,
            'fields'        => 'all',
            'hierarchical'  => true,
            'search_name'   => ''
        );
        
        $terms = get_terms( $args );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){        
             foreach ($terms as $term) {
                 $results[] = array( $term->name );
             }             
            $response = $_GET["callback"]."(". json_encode($results) .")"; 
            echo $response;  
            exit;
        }
       wp_die();        
    }
}

