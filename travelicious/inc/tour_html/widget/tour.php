<?php
// NEW TOURS WIDGET
if ( ! function_exists( 'boldthemes_get_new_tours_widget_html' ) ) {
	function boldthemes_get_new_tours_widget_html( $tours = array(), $type = '', $gap = '', $columns = '', $format = '' ) { 
            if ( !empty($tours) ){
                $tour_list_type       = boldthemes_get_tour_list_type( $type );
                $tour_list_gap        = boldthemes_get_tour_list_gap( $gap );
                $tour_list_columns    = boldthemes_get_tour_list_columns( $columns );
                      
                switch ($tour_list_type){
                    case 'btListDesignList':
                        boldthemes_get_tours_widget_list_html( $tours, $tour_list_type, $tour_list_gap, $tour_list_columns );
                        break;
                    case 'btListDesignRegular':
                        boldthemes_get_tours_widget_regular_html( $tours, $tour_list_type, $tour_list_gap, $tour_list_columns );
                        break;
                    case 'btListDesignGallery':
                        boldthemes_get_tours_widget_gallery_html( $tours, $tour_list_type, $tour_list_gap, $tour_list_columns );
                        break;
                    case 'btListDesignTiles':
                        boldthemes_get_tours_widget_tiles_html( $tours, $tour_list_type, $tour_list_gap, $tour_list_columns );
                        break;
                    default:
                        boldthemes_get_tours_widget_list_html( $tours, $tour_list_type, $tour_list_gap, $tour_list_columns );
                        break;
                }                
            }
        }
}

// GREAT DESTINATIONS WIDGET
if ( ! function_exists( 'boldthemes_get_great_destinations_widget_html' ) ) {
	function boldthemes_get_great_destinations_widget_html( $tours = array(), $type = '', $gap = '', $columns = '', $format = '' ) {  
            if ( !empty($tours) ){
                $tour_list_type       = boldthemes_get_tour_list_type( $type );
                $tour_list_gap        = boldthemes_get_tour_list_gap( $gap );
                $tour_list_columns    = boldthemes_get_tour_list_columns( $columns );
                     
                switch ($tour_list_type){
                    case 'btListDesignList':
                        boldthemes_get_tours_widget_list_html( $tours, $tour_list_type, $tour_list_gap, $tour_list_columns );
                        break;
                    case 'btListDesignRegular':
                        boldthemes_get_tours_widget_regular_html( $tours, $tour_list_type, $tour_list_gap, $tour_list_columns );
                        break;
                    case 'btListDesignGallery':
                        boldthemes_get_tours_widget_gallery_html( $tours, $tour_list_type, $tour_list_gap, $tour_list_columns );
                        break;
                    case 'btListDesignTiles':
                        boldthemes_get_tours_widget_tiles_html( $tours, $tour_list_type, $tour_list_gap, $tour_list_columns );
                        break;
                    default:
                        boldthemes_get_tours_widget_list_html( $tours, $tour_list_type, $tour_list_gap, $tour_list_columns );
                        break;
                }                
            }
        }
}

// TOURS WIDGET LIST - TYPE LIST
if ( ! function_exists( 'boldthemes_get_tours_widget_list_html' ) ) {
	function boldthemes_get_tours_widget_list_html( $tours = array(), $type = '', $gap = '', $columns = '', $format = '' ) { 
                if ( !empty( $tours ) ) {  
                    echo '<div class="btImageTextWidgetWraper"><ul>';                    
                    foreach ( $tours as $tour ) {
                            $tour_discount_title    = rwmb_meta('tour_discount_title', null, $tour->ID ) != '' ? rwmb_meta('tour_discount_title', null, $tour->ID ) : '' ;
                            $tour_original_price    = rwmb_meta('tour_original_price', null, $tour->ID ) != '' ? rwmb_meta('tour_original_price', null, $tour->ID ) : '' ;
                            $tour_regular_price     = rwmb_meta('tour_regular_price', null, $tour->ID ) != '' ? rwmb_meta('tour_regular_price', null, $tour->ID ) : '' ;
                            $link   = get_permalink( $tour->ID );
                            
                            $img    = get_the_post_thumbnail( $tour->ID, 'thumbnail' );
                            if ( $img == '' ){
                                    $img_default = boldthemes_get_tour_default_image( $tour->ID, 'thumbnail' );  
                                    $img = '<img src="' . $img_default['img_src'] . '" alt="' . $tour->post_title . '">';
                            }
                        
                            echo '<li><div class="btImageTextWidget">';
                            if ( $img != '' ) {
                                    echo '<div class="btImageTextWidgetImage"><a href="' . esc_url( $link ) . '">' . $img . '</a></div>';
                            }
                            $supertitle  = $tour_discount_title;
                            $subheadline = $tour_regular_price ? 'From <ins>' . bt_get_tour_price($tour_regular_price) . '</ins>' : '';
                            echo '<div class="btImageTextWidgetText">' . do_shortcode( '[bt_bb_headline superheadline="' . $supertitle . '" headline="' . $tour->post_title . '" subheadline="'.$subheadline . '" url="' . $link . '" size="small" html_tag="h4"]' ) . '</div>';
           
                            echo '</div></li>';
                    }                    
                    echo '</ul></div>';
               }
        }
}


// TOURS WIDGET LIST - TYPE REGULAR
if ( ! function_exists( 'boldthemes_get_tours_widget_regular_html' ) ) {
	function boldthemes_get_tours_widget_regular_html( $tours = array(), $type = '', $gap = '', $columns = '', $format = '' ) {
             if ( !empty( $tours ) ) {  
                  foreach ( $tours as $tour ) {                      
                        $tour_discount_title    = rwmb_meta('tour_discount_title', null, $tour->ID ) != '' ? rwmb_meta('tour_discount_title', null, $tour->ID ) : '' ;
                        $tour_original_price    = rwmb_meta('tour_original_price', null, $tour->ID ) != '' ? rwmb_meta('tour_original_price', null, $tour->ID ) : '' ;
                        $tour_regular_price     = rwmb_meta('tour_regular_price', null, $tour->ID ) != '' ? rwmb_meta('tour_regular_price', null, $tour->ID ) : '' ;
                        $tour_duration          = rwmb_meta('tour_duration', null, $tour->ID ) != '' ?rwmb_meta('tour_duration', null, $tour->ID ) : '' ;

                        $link   = get_permalink( $tour->ID );
                        $img    = get_the_post_thumbnail( $tour->ID, 'boldthemes_tour_medium_rectangle' );
                        
                        if ( $img == '' ){
                                $img_default = boldthemes_get_tour_default_image( $tour->ID, 'boldthemes_tour_medium_rectangle' );  
                                $img = isset( $img_default['img_src'] ) ?  '<img src="' . $img_default['img_src'] . '" alt="' . $tour->post_title . '">' : '';
                        }
                      ?>
                        <div class="btTourList btListDesignRegular btTourListGapNormal">
                                <div class="btSingleTourBlock">
                                        <div class="btSingleTourBlockInner">
                                                <?php if ( $img != '' ) { ?>
                                                    <div class="btSingleTourImage">
                                                            <div class="btImageWrapper">
                                                                    <a href="<?php echo esc_url( $link );?>">
                                                                            <?php echo $img;?>
                                                                    </a>
                                                            </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if (  $tour_regular_price ) { ?>
                                                    <div class="btSingleTourPrice">
                                                            <div class="btTourPrice">
                                                                    <span class="from"><?php _e( 'From','bt_plugin' ); ?></span>
                                                                    <ins><?php echo bt_get_tour_price($tour_regular_price);?></ins>
                                                            </div>
                                                    </div>
                                                <?php } ?>                                                
                                                <div class="btSingleTourContent">
                                                        <div class="btSingleTourHeadline">
                                                                <a href="#"><?php echo $tour->post_title;?></a>
                                                        </div>
                                                        <?php if (  $tour_duration ) { ?>
                                                            <div class="btSingleTourMeta">
                                                                    <div class="btTourDuration"><?php echo $tour_duration;?></div>
                                                            </div>
                                                         <?php } ?>
                                                </div>                                               
                                        </div>
                                </div>
                        </div>
					  
                      <?php
                  }
             }
        }
}
// TOURS WIDGET LIST - TYPE GALLERY
if ( ! function_exists( 'boldthemes_get_tours_widget_gallery_html' ) ) {
	function boldthemes_get_tours_widget_gallery_html( $tours = array(), $type = '', $gap = '', $columns = '', $format = '' ) { 
          if ( !empty( $tours ) ) {  
                  foreach ( $tours as $tour ) {
                        $tour_discount_title    = rwmb_meta('tour_discount_title', null, $tour->ID ) != '' ? rwmb_meta('tour_discount_title', null, $tour->ID ) : '' ;
                        $tour_original_price    = rwmb_meta('tour_original_price', null, $tour->ID ) != '' ? rwmb_meta('tour_original_price', null, $tour->ID ) : '' ;
                        $tour_regular_price     = rwmb_meta('tour_regular_price', null, $tour->ID ) != '' ? rwmb_meta('tour_regular_price', null, $tour->ID ) : '' ;
                        $tour_duration          = rwmb_meta('tour_duration', null, $tour->ID ) != '' ?rwmb_meta('tour_duration', null, $tour->ID ) : '' ;

                        $link   = get_permalink( $tour->ID );
                        $img    = get_the_post_thumbnail( $tour->ID, 'boldthemes_tour_medium_portrait' );
                        
                        if ( $img == '' ){
                                $img_default = boldthemes_get_tour_default_image( $tour->ID, 'boldthemes_tour_medium_portrait' );  
                                $img = isset( $img_default['img_src'] ) ? '<img src="' . $img_default['img_src'] . '" alt="' . $tour->post_title . '">' : '';
                        }
                      ?>
                            <div class="btTourList btListDesignGallery btTourListGapNormal">
                                    <div class="btSingleTourBlock">
                                            <div class="btSingleTourBlockInner">
                                                    <?php if ( $img != '' ) { ?>
                                                        <div class="btSingleTourImage">
                                                                <div class="btImageWrapper">
                                                                        <a href="<?php echo esc_url( $link );?>">
                                                                                <?php echo $img;?>
                                                                        </a>
                                                                </div>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (  $tour_regular_price ) { ?>
                                                        <div class="btSingleTourPrice">
                                                                <div class="btTourPrice">
                                                                        <span class="from"><?php _e( 'From','bt_plugin' ); ?></span>
                                                                        <ins><?php echo bt_get_tour_price($tour_regular_price);?></ins>
                                                                </div>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="btSingleTourContent">
                                                            <div class="btSingleTourHeadline">
                                                                    <a href="<?php echo esc_url( $link );?>"><?php echo $tour->post_title;?></a>
                                                            </div>
                                                            <?php if (  $tour_duration ) { ?>
                                                                <div class="btSingleTourMeta">
                                                                        <div class="btTourDuration"><?php echo $tour_duration;?></div>
                                                                </div>
                                                            <?php } ?>
                                                    </div>
                                            </div>
                                    </div>
                            </div>					  
                      <?php
                  }
             }
        }
}
// TOURS WIDGET LIST - TYPE TILES
if ( ! function_exists( 'boldthemes_get_tours_widget_tiles_html' ) ) {
	function boldthemes_get_tours_widget_tiles_html( $tours = array(), $type = '', $gap = '', $columns = '', $format = '' ) { 
            if ( !empty( $tours ) ) {  
                  foreach ( $tours as $tour ) {
                    $tour_discount_title    = rwmb_meta('tour_discount_title', null, $tour->ID ) != '' ? rwmb_meta('tour_discount_title', null, $tour->ID ) : '' ;
                    $tour_original_price    = rwmb_meta('tour_original_price', null, $tour->ID ) != '' ? rwmb_meta('tour_original_price', null, $tour->ID ) : '' ;
                    $tour_regular_price     = rwmb_meta('tour_regular_price', null, $tour->ID ) != '' ? rwmb_meta('tour_regular_price', null, $tour->ID ) : '' ;
                    $tour_duration          = rwmb_meta('tour_duration', null, $tour->ID ) != '' ?rwmb_meta('tour_duration', null, $tour->ID ) : '' ;

                    $link   = get_permalink( $tour->ID );
                    $img    = get_the_post_thumbnail( $tour->ID, 'boldthemes_large_square' );

                    if ( $img == '' ){
                            $img_default = boldthemes_get_tour_default_image( $tour->ID, 'boldthemes_large_square' );  
                            $img = isset( $img_default['img_src'] ) ? '<img src="' . $img_default['img_src'] . '" alt="' . $tour->post_title . '">' : '';
                    }
                    ?>
                            <div class="btTourList btListDesignTiles btTourListGapNormal">
                                    <div class="btSingleTourBlock">
                                            <div class="btSingleTourBlockInner">
                                                    <?php if ( $img != '' ) { ?>
                                                        <div class="btSingleTourImage">
                                                                <div class="btImageWrapper">
                                                                        <a href="<?php echo esc_url( $link );?>">
                                                                                <?php echo $img;?>
                                                                        </a>
                                                                </div>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (  $tour_regular_price ) { ?>
                                                        <div class="btSingleTourPrice">
                                                                <div class="btTourPrice">
                                                                        <span class="from"><?php _e( 'From','bt_plugin' ); ?></span>
                                                                        <ins><?php echo bt_get_tour_price($tour_regular_price);?></ins>
                                                                </div>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="btSingleTourContent">
                                                            <div class="btSingleTourHeadline">
                                                                    <a href="<?php echo esc_url( $link );?>" tabindex="0"><?php echo $tour->post_title;?></a>
                                                            </div>
                                                            <?php if (  $tour_duration ) { ?>
                                                               <div class="btSingleTourMeta">
                                                                       <div class="btTourDuration"><?php echo $tour_duration;?></div>
                                                               </div>
                                                            <?php } ?>
                                                    </div>
                                            </div>
                                    </div>
                            </div>	  
                      <?php
                  }
             }
        }
}


