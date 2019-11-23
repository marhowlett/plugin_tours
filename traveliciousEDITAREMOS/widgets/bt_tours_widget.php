<?php
$search_destinations = bt_get_tour_destinations_by_slugs( $instance['slug'] );

if ( isset($instance['view_type']) ) {
      switch ( $instance['orderby'] ){
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
        
        $tours  = boldthemes_get_query_items(
                array(
                        'posts_per_page'     => $instance['number'],
                        'search_orderby'     => $listing_orderby,
                        'search_order'       => $listing_order,            
                        'search_destination' => $search_destinations,//slugs
                ) 
        );        
        boldthemes_get_new_tours_widget_html( $tours, $instance['view_type'] );
}
