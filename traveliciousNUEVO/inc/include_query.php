<?php
if ( ! function_exists( 'boldthemes_get_query_items' ) ) {
	function boldthemes_get_query_items( $params = array() ) {
            $TxQuery = array(); 
            
            $search_start_date   = ''; 
            if ( isset($params['search_start_date']) && $params["search_start_date"] != '' ){                
                $params["search_start_date"] = preg_replace('/\s+/', '', $params["search_start_date"]);                
                $bt_tour_search_date_format = isset($params['search_date_format']) && $params['search_date_format'] != '' ? $params['search_date_format'] : '';
                if ( $bt_tour_search_date_format == '' ){
                    $bt_tour_search_date_format = BoldThemesFrameworkTemplate::$datepicker_format;
                }                
                try {
                    $date = DateTime::createFromFormat( bt_set_datepicker_to_wp_format( $bt_tour_search_date_format ), $params['search_start_date'] );
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                $search_show_months = isset($params['search_show_months']) && $params['search_show_months'] > 0 ? 1 : 0;
                
                $search_start_date = $search_show_months ? $date->format('Y-m') : $date->format('Y-m-d');
            }
                                   
            $paged                  = isset($params["paged"]) && $params["paged"] != '' ? $params["paged"] : 1; 
            
            //search limit
            $search_limit           = isset($params["search_limit"]) && $params["search_limit"] != '' ? $params["search_limit"] : -1;            
            //search keyword
            $search_keyword         = isset($params["search_keyword"]) && $params["search_keyword"] != '' ? $params["search_keyword"] : '';            
            //search destination
            $search_destination     = isset($params['search_destination']) && $params["search_destination"] != '' ?  $params['search_destination'] :  array();
            //search start date
            $search_start_date      = isset($params['search_start_date']) && $params["search_start_date"] != '' ? $search_start_date : '';
            //search price from
            $search_price_from      = isset($params['search_price_from']) && $params["search_price_from"] != '' ? $params['search_price_from'] : '';
            //search price to
            $search_price_to        = isset($params['search_price_to']) && $params["search_price_to"] != '' ? $params['search_price_to'] : '';            
            // search categories
            $search_categories      = isset($params['search_categories']) && !empty($params["search_categories"]) ? $params['search_categories'] : array();
            //search tours by slugs in tour list shortcode
            $search_tour_slug       = isset($params["search_tour_slug"]) && $params["search_tour_slug"] != '' ? $params["search_tour_slug"] : ''; 
            
            $include_cats_dests = '';
            //vacation-in-portugal; tasteful-mauritius
            $posts_per_page          = isset($params["posts_per_page"]) && $params["posts_per_page"] != '' ? $params["posts_per_page"] : -1;  
            
            // search sorting
            $search_orderby         = isset($params["search_orderby"]) && $params["search_orderby"] != ''? $params["search_orderby"] : 'post_date';
            $search_order           = isset($params["search_order"]) && $params["search_order"] != '' ? $params["search_order"] : 'DESC';  
            
            if ( $search_limit > 0 ) {
                BoldThemesFrameworkTemplate::$posts_per_page = $search_limit;
                BoldThemesFrameworkTemplate::$max_page   = 1;
            }
            
            if ( $search_tour_slug != '' ){
                $search_categories  = array();
                $search_destination = array();
            }
                        
            /* taxonomy wp query */            
            $TxQuery_categories = array();
            if ( !empty($search_categories) ) {
                $TxQuery_categories = array(
                    'taxonomy'		=> 'tour_category',
                    'terms'             => $search_categories,
                    'field'             => 'term_id',
                    'include_children'	=> true,
                    'operator'		=> 'IN'
                );
            } 
            
            $TxQuery_destinations = array();
            if ( !empty($search_destination) ) {
                $TxQuery_destinations = array(
                    'taxonomy'		=> 'tour_destination',
                    'terms'             => $search_destination,
                    'field'             => 'slug',
                    'include_children'	=> true,
                    'operator'		=> 'IN'
                );
            } 
            
            
            if ( !empty($search_categories) && !empty($search_destination) ){
                $TxQuery = array(
                        'relation' => 'AND',
                        $TxQuery_categories,
                        $TxQuery_destinations,
                );
            }else if(!empty($search_categories)){
                $TxQuery = array(
                        'relation' => 'AND',
                        $TxQuery_categories,
                );
            }else if( !empty($search_destination) ){
                $TxQuery = array(
                        'relation' => 'AND',
                        $TxQuery_destinations,
                );
            }else{
               $TxQuery = array();
            }

            /* wp query */	  
            $args = array(
                    'post_type'         => 'tour',
                    'post_status'       => 'publish',
                    'posts_per_page'	=> $posts_per_page,
                    'paged'             => $paged,
                    'search_keyword'    => $search_keyword,
                    'search_price_from' => $search_price_from,
                    'search_price_to'   => $search_price_to,
                    'search_start_date' => $search_start_date,
                    'search_post_name'  => $search_tour_slug,
            );
            
            if ( is_array( $TxQuery ) && !empty( $TxQuery ) ) {
                $args = array_merge($args, 
                            array( 
                                'tax_query'         => $TxQuery
                            )
                    );
            }
            
            if ( $search_orderby == 'price_from' ){
                    $args = array_merge($args, 
                            array( 
                                'meta_key'   => 'tour_regular_price',
                                'orderby'    => 'meta_value_num',
                                'order'      => $search_order,
                                'meta_query' => array(
                                        array(
                                                'key'     => 'tour_regular_price',
                                        ),
                                ),
                            )
                    );
            }else{
                    $args = array_merge($args, 
                            array( 
                                "orderby" => $search_orderby, 
                                'order' => $search_order
                            )
                    );
            }
            
            add_filter( 'posts_join', 'boldthemes_posts_join_filter', 10, 2 ); 
            add_filter( 'posts_where', 'boldthemes_posts_where_filter', 10, 2 ); 

            $tour_query	= new WP_Query($args);
            //var_dump($tour_query->request);    
            remove_filter( 'posts_join', 'boldthemes_posts_join_filter', 10, 2 ); 
            remove_filter( 'posts_where', 'boldthemes_posts_where_filter', 10, 2 );
            
            BoldThemesFrameworkTemplate::$max_page   = $tour_query->max_num_pages;
            $tours   = $tour_query->posts;
            wp_reset_postdata();
            reset($tours);
            return $tours;
        }
}


/* join filter  */
if ( ! function_exists( 'boldthemes_posts_join_filter' ) ) {
	function boldthemes_posts_join_filter( $join, $wp_query ) {
		global $wpdb;
		$meta_keys = array('tour_original_price');
	   
		$search_price_from   = $wp_query->get( 'search_price_from' );
		$search_price_to     = $wp_query->get( 'search_price_to' );  
                $search_start_date   = $wp_query->get( 'search_start_date' );  
                  
		if ( $search_price_from || $search_price_to ) {
                    $join .= " INNER JOIN $wpdb->postmeta AS bt_postmeta_price ON ($wpdb->posts.ID = bt_postmeta_price.post_id AND  "
                            . "( "
                                    . "bt_postmeta_price.meta_key = 'tour_regular_price')"
                            . ") "; 
                      
		}
                
                if ( $search_start_date ) {
                    $join .= " INNER JOIN $wpdb->postmeta AS bt_postmeta_start_date ON ($wpdb->posts.ID = bt_postmeta_start_date.post_id AND  "
                            . "( "
                                    . "bt_postmeta_start_date.meta_key = 'tour_dt')"
                            . ") "; 
                      
		}
                
                		
		return $join;
	}
}

/*where filter - keyword, price */
if ( ! function_exists( 'boldthemes_posts_where_filter' ) ) {
	function boldthemes_posts_where_filter($where, $wp_query){
		$search_keyword         = $wp_query->get( 'search_keyword' );
                $search_price_from      = $wp_query->get( 'search_price_from' );
		$search_price_to        = $wp_query->get( 'search_price_to' );
                $search_start_date      = $wp_query->get( 'search_start_date' ); 
                $search_post_name       = $wp_query->get( 'search_post_name' );
                $search_post_name_include_cats_dests = $wp_query->get( 'search_post_name_include_cats_dests' );
                
                if ( $search_keyword ){
			$where .= boldthemes_posts_where_filter_keyword($search_keyword);
		}
                
                if ( $search_price_from || $search_price_to ) {
			$meta_keys = array('tour_regular_price');
			$where .= boldthemes_posts_where_filter_price($meta_keys, $search_price_from, $search_price_to);
		}
                
                if ( $search_start_date ){
			$where .= boldthemes_posts_where_filter_start_date($search_start_date);
		}
                
                if ( $search_post_name ){
			$where .= boldthemes_posts_where_filter_post_name($search_post_name, $search_post_name_include_cats_dests);
		}
                                
                return $where;
        }
}

/*helper where filter - keyword */
if ( ! function_exists( 'boldthemes_posts_where_filter_keyword' ) ) {
	function boldthemes_posts_where_filter_keyword($bt_tour_search_keyword){
		$clause = '';
		global $wpdb;
		if ( $bt_tour_search_keyword ) {
			$clause .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $bt_tour_search_keyword ) ) . '%\'';
			$clause .= ' OR ' . $wpdb->posts . '.post_excerpt LIKE \'%' . esc_sql( $wpdb->esc_like( $bt_tour_search_keyword ) ) . '%\'';
			$clause .= ' OR ' . $wpdb->posts . '.post_content LIKE \'%' . esc_sql( $wpdb->esc_like( $bt_tour_search_keyword ) ) . '%\')';                   
		}		
		return $clause;    
	}
}

if ( ! function_exists( 'boldthemes_posts_where_filter_post_name' ) ) {
	function boldthemes_posts_where_filter_post_name($search_post_name, $search_post_name_include_cats_dests){
		$clause = '';
		global $wpdb;
                
		if ( $search_post_name ) {
                        $search_post_names = explode( ';', $search_post_name );                        
                        $search_post_names = array_filter($search_post_names, function($value) { return $value !== ''; });
                        
                        if ( is_array($search_post_names) ) {
                            $clause .= ' AND ( ';
                                $i = 1;
                                foreach ( $search_post_names as $post_name ) {                                    
                                    if ( $post_name != '' ) {
                                        $clause .= ' (' . $wpdb->posts . '.post_name = \'' . esc_sql( $wpdb->esc_like( trim($post_name )) ) . '\')';
                                        if ( $i < count($search_post_names) ) {
                                            $clause .= ' OR ';
                                        }
                                    }                                       
                                    $i++;
                                }
                            $clause .= ' ) ';    
                        }       
		}		
		return $clause;    
	}
}

/*helper where filter - price */
if ( ! function_exists( 'boldthemes_posts_where_filter_price' ) ) {
	function boldthemes_posts_where_filter_price($meta_keys, $price_range_from, $price_range_to){
		$clause = '';
		if ( $price_range_from || $price_range_to ) {
		   $clause .= ' AND ';
		}
                
                if ( $price_range_from && $price_range_to ) {                
                    if ( $price_range_from  ) {
                        $clause .= ' ((bt_postmeta_price.meta_key = "tour_regular_price" AND bt_postmeta_price.meta_value >= ' . $price_range_from . ')'; 
                    }
                    if ( $price_range_from && $price_range_to ) {
                        $clause .= ' AND ';
                    }
                    if ( $price_range_to ) {   
                        $clause .= ' (bt_postmeta_price.meta_key = "tour_regular_price" AND bt_postmeta_price.meta_value <= ' . $price_range_to . ')'; 
                    }

                    if ( $price_range_from || $price_range_to ) {
                       $clause .= ' )';
                    }                
                }else{                
                    if ( $price_range_from ) {
                        $clause .= ' ((bt_postmeta_price.meta_key = "tour_regular_price" AND bt_postmeta_price.meta_value >= ' . $price_range_from . ')';
                        $clause .= ' OR ';
                        $clause .= ' (bt_postmeta_price.meta_key = "tour_regular_price" AND bt_postmeta_price.meta_value >= ' . $price_range_from . '))';
                    }
                    if ( $price_range_to ) {
                        $clause .= ' ((bt_postmeta_price.meta_key = "tour_regular_price"  AND bt_postmeta_price.meta_value <> "" AND bt_postmeta_price.meta_value <= ' . $price_range_to . ')';
                        $clause .= ' OR ';
                        $clause .= ' (bt_postmeta_price.meta_key = "tour_regular_price"  AND bt_postmeta_price.meta_value <> "" AND bt_postmeta_price.meta_value <= ' . $price_range_to . '))';
                    }
                }
                
		return $clause;
	}
}

/*helper where filter function - start date */
if ( ! function_exists( 'boldthemes_posts_where_filter_start_date' ) ) {
	function boldthemes_posts_where_filter_start_date($search_start_date){
            global $wpdb;
            $clause = '';
             
            BoldThemesFrameworkTemplate::$tour_search_date_before_after_days = boldthemes_get_option( 'tour_search_date_before_after_days' ) != '' ? 
                boldthemes_get_option( 'tour_search_date_before_after_days' ) : BoldThemes_Customize_Default::$data['tour_search_date_before_after_days']; 
            $date_last_next_no = BoldThemesFrameworkTemplate::$tour_search_date_before_after_days;  
            
            if ( $search_start_date != '' ){
                $clause .= ' AND ((bt_postmeta_start_date.meta_id IS NOT NULL) AND (CAST(bt_postmeta_start_date.meta_value as CHAR)   LIKE \'%' . esc_sql( $wpdb->esc_like( $search_start_date ) ) . '%\')';
                $clause_last = '';
                $clause_next = '';
                for ($i = 1; $i < $date_last_next_no + 1; $i++) {
                    $date_last = date('Y-m-d', strtotime("-$i days", strtotime(date($search_start_date))));;
                    $clause_last .= ' OR (bt_postmeta_start_date.meta_id IS NOT NULL) AND (CAST(bt_postmeta_start_date.meta_value as CHAR)   LIKE \'%' . esc_sql( $wpdb->esc_like( $date_last ) ) . '%\')';
                }
                for ($i = 1; $i < $date_last_next_no + 1; $i++) {
                    $date_next = date('Y-m-d', strtotime("+$i days", strtotime(date($search_start_date))));
                    $clause_next .= ' OR (bt_postmeta_start_date.meta_id IS NOT NULL) AND (CAST(bt_postmeta_start_date.meta_value as CHAR)   LIKE \'%' . esc_sql( $wpdb->esc_like( $date_next ) ) . '%\')';
                }
                
                $clause .= $clause_last . $clause_next;
                
                $clause .= ')';
            }
            
            return $clause;
        }
}

/*helper where filter function - start date */
if ( ! function_exists( 'boldthemes_posts_where_filter_start_date2' ) ) {
	function boldthemes_posts_where_filter_start_date2($search_start_date, $tours){
            
            if ( $search_start_date != '' ){
                    $c = 0;
                    foreach ( $tours as $tour ) {
                        $tour_rwmb_dts    = !empty(rwmb_meta('tour_dt', null, $tour->ID )) ? rwmb_meta('tour_dt', null, $tour->ID ) : array();
                        
                        if ( is_array($tour_rwmb_dts) && !empty($tour_rwmb_dts) ){
                            $old = true;
                            foreach ( $tour_rwmb_dts as $tour_rwmb_dt ) {
                                if (new DateTime($search_start_date) <= new DateTime($tour_rwmb_dt)) {
                                   $old = false;
                                   break;
                                }
                            }

                            if ( $old ) {
                                unset($tours[$c]);
                            }
                        }
                        $c++;
                    }
            }
            
            return $tours;
        }
}

