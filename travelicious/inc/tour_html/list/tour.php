<?php
// TOURS LIST
if ( ! function_exists( 'boldthemes_get_tour_list_html' ) ) {
	function boldthemes_get_tour_list_html( $view_type = '', $gap = '', $columns = '', $pagination = true) {  
           
            if ( !empty( BoldThemesFrameworkTemplate::$tours ) ) { 
                $tour_list_type       = boldthemes_get_tour_list_type( $view_type );
                $tour_list_gap        = boldthemes_get_tour_list_gap( $gap );
                $tour_list_columns    = boldthemes_get_tour_list_columns( $columns );
                ?>
                <div class="port">
                    <div class="btTourList <?php echo $tour_list_type;?> <?php echo $tour_list_gap;?>  <?php echo $tour_list_columns;?>">
                        <?php 
                            foreach ( BoldThemesFrameworkTemplate::$tours as $tour ) {
                                if ( $tour_list_type == 'btListDesignList' ) {
                                    echo boldthemes_get_tour_single_block_list_html( $tour->ID, $tour_list_type );
                                }else{
                                    echo boldthemes_get_tour_single_block_square_html( $tour->ID, $tour_list_type );
                                }
                            }   
                        ?>
                    </div> 
                </div> 
                <?php
                if ( $pagination ) {
                    boldthemes_tour_pagination();
                }
            }else{
                 ?>
                    <div class="btTourList bt_bb_tour_list_empty">
                        <p><?php esc_html_e( 'Sorry! There are no tours matching your search.', 'bt_plugin' )?></p>
                        <p><?php esc_html_e( 'Try changing your search filters.', 'bt_plugin' )?></p>
                    </div>
                <?php
            }
            
        }        
}

// TOURS TILES
if ( ! function_exists( 'boldthemes_get_tour_tiles_html' ) ) {
	function boldthemes_get_tour_tiles_html( $view_type = '', $gap = '', $columns = '', $format = '', $pagination = true ) {  
            if ( !empty( BoldThemesFrameworkTemplate::$tours ) ) { 
                global $tour_global;
                $tour_list_type       = boldthemes_get_tour_list_type( $view_type );
                $tour_list_gap        = boldthemes_get_tour_list_gap( $gap );
                $tour_list_columns    = boldthemes_get_tour_list_columns( $columns );                
               
                if ( empty(BoldThemesFrameworkTemplate::$tours) ){
                        ?>
                            <div class="port">
                                <div class="btTourList <?php echo $tour_list_type;?> <?php echo $tour_list_gap;?>  <?php echo $tour_list_columns;?>">
                                    <div class="bt_bb_tour_list_empty">
                                        <p><?php esc_html_e( 'Sorry! There are no tours matching your search.', 'bt_plugin' )?></p>
                                        <p><?php esc_html_e( 'Try changing your search filters.', 'bt_plugin' )?></p>
                                    </div>
                                </div>
                             </div>
                        <?php
                }else{
                        $format_arr = array();
                        if ( $format != '' ) {
                                $format_arr = explode( ',', $format );
                        }
                        $auto_loading = '3000';

                        $output = '';
                        $n = 0;
                        foreach ( BoldThemesFrameworkTemplate::$tours as $tour ) {
                                $id = get_post_thumbnail_id( $tour->ID );
                                $img = wp_get_attachment_image_src( $id, 'boldthemes_large_square' );
                                if ( isset( $format_arr[ $n ] ) ) {
                                        switch ( $format_arr[ $n ] ){
                                                case '11': 
                                                        $img = wp_get_attachment_image_src( $id, 'boldthemes_large_square' );
                                                        break;
                                                case '21': 
                                                        $img = wp_get_attachment_image_src( $id, 'boldthemes_large_rectangle' );
                                                        break;
                                                case '12': 
                                                        $img = wp_get_attachment_image_src( $id, 'boldthemes_large_vertical_rectangle' );
                                                        break;
                                                case '22': 
                                                        $img = wp_get_attachment_image_src( $id, 'boldthemes_large_square' );
                                                        break;
                                                default: 
                                                        $img = wp_get_attachment_image_src( $id, 'boldthemes_large_square' );
                                                        break;
                                        }
                                }
                                $img_src = $img[0];
                                $hw = 0;
                                if ( $img_src != '' ) {
                                        $hw = $img[2] / $img[1];
                                }

                                $img_full = wp_get_attachment_image_src( $id, 'full' );
                                $img_src_full = $img_full[0];
                                if ( isset( $format_arr[ $n ] ) ) {
                                        $tile_format = 'bt_bb_tile_format';
                                        if ( $format_arr[ $n ] == '21' || $format_arr[ $n ] == '12' || $format_arr[ $n ] == '22' ) {
                                                $tile_format .= "_" . $format_arr[ $n ];
                                        } else {
                                                $tile_format .= '11';
                                        }
                                }
                                
                                $imgage_default = boldthemes_get_tour_default_image( $tour->ID, 'full' );
                                $img_src_default   = $imgage_default['img_src']; 

                                $permalink  = get_permalink($tour->ID);
                                
                                bt_get_tour_single_data( $tour->ID ); 
                                $output .= '<div data-tour-id="'.$tour->ID.'" class="bt_bb_grid_item ' . $tile_format . '" data-hw="' .  $hw  . '" data-src="' . $img_src . '" data-src-full="' . $img_src_full . '" data-title="' . $tour->post_title . '">
                                                <div class="bt_bb_grid_item_inner" data-hw="' . $hw  . '" >
                                                        <div class="bt_bb_grid_item_post_thumbnail">
                                                                <a href="' . $permalink . '" title="' . $tour->post_title . '">
                                                                        <img class="bt_bb_grid_item_inner_image" src="' . $img_src_default . '" data-src="' . $img_src . '" data-src-full="' . $img_src_full . '"  alt="' . $tour->post_title . '"  data-loaded="0"/>
                                                                </a>
                                                        </div>';
                                                        
                                                        $output .= '<div class="btSingleTourPrice">';
                                                            if ( BoldThemesFrameworkTemplate::$tour_rwmb_discount_title != '' ) { 
                                                                $output .= '<div class="btTourOffer">' . esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_discount_title) . '</div>';
                                                            }
                                                            if ( BoldThemesFrameworkTemplate::$tour_rwmb_original_price != '' || BoldThemesFrameworkTemplate::$tour_rwmb_regular_price != '' ) { 
                                                                $output .= '<div class="btTourPrice">';
                                                                    $output .= '<span class="from">From</span>';
                                                                    if ( BoldThemesFrameworkTemplate::$tour_rwmb_original_price != '' ) {
                                                                    $output .= '<del>' . esc_html(bt_get_tour_original_price()). '</del>'; 
                                                                    }
                                                                    if ( BoldThemesFrameworkTemplate::$tour_rwmb_regular_price != '' ) { 
                                                                    $output .= '<ins>' . esc_html(bt_get_tour_regular_price()) . '</ins>';
                                                                    }
                                                                $output .= '</div>';
                                                            }
                                                        $output .= '</div>';
                                                        
                                                        $output .= '<div class="bt_bb_grid_item_inner_content">
                                                             <h5 class="bt_bb_grid_item_post_title">' . $tour->post_title . '</h5> 
                                                             <div class="bt_bb_grid_item_post_excerpt">' . $tour->post_excerpt . '</div>
                                                        </div>
                                                        <div class="bt_bb_grid_item_post_title_init">
                                                            <h5>' . $tour->post_title . '</h5>
                                                        </div>
                                                </div>
                                        </div>';


                                $n++;
                        } 

                        echo $output;
                }
               
            }
            
        }        
}



// TOUR SINGLE BLOCK type 2
if ( ! function_exists( 'boldthemes_get_tour_single_block_square_html' ) ) {
	function boldthemes_get_tour_single_block_square_html( $post_id = 0, $type = '' ) {             
            if ( $post_id == 0){
                return false;
            }             
            bt_get_tour_single_data( $post_id );
            
            $permalink      = get_permalink($post_id);
            
            bt_get_tour_image_by_type( $post_id, $type );
            $img_src        = BoldThemesFrameworkTemplate::$feat_image_tour_list['img_src'];
            $img_src_full   = BoldThemesFrameworkTemplate::$feat_image_tour_list['img_src_full']; 
            
            $imgage_default = boldthemes_get_tour_default_image_by_type( $post_id, $type );
            $img_src_default   = $imgage_default['img_src']; 
            
            $rating = boldthemes_get_post_user_rating( $post_id );
            ?>
                <div class="btSingleTourBlock" data-tour-id="<?php echo $post_id;?>">
                        <div class="btSingleTourBlockInner">
                                <div class="btSingleTourImage">
                                        <div class="btImageWrapper">
                                                <a href="<?php echo esc_url( $permalink ); ?>">
                                                    <img data-src="<?php echo esc_attr($img_src);?>" data-src-full="<?php echo esc_attr($img_src_full);?>" src="<?php echo $img_src_default;?>" alt="<?php echo BoldThemesFrameworkTemplate::$title;?>"  data-loaded="0">
                                                </a>
                                        </div>
                                </div>
                                <?php echo bt_get_tour_single_price_html();?>
                                <div class="btSingleTourContent">
                                        <div class="btSingleTourHeadline"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo BoldThemesFrameworkTemplate::$title;?></a></div>
                                         <?php if ( !empty(BoldThemesFrameworkTemplate::$tour_categories) ){ ?>
                                            <div class="btSingleTourCategories">
                                                <?php 
                                                    foreach ( BoldThemesFrameworkTemplate::$tour_categories as $tour_category) { 
                                                        $category_link = get_term_link($tour_category);
                                                        echo '<a href="' . esc_url( $category_link ) . '">' . $tour_category->name . '</a>'; 
                                                    } 
                                                ?>
                                            </div>
                                        <?php } ?>
                                        <div class="btSingleTourMeta">
                                                <?php if ( BoldThemesFrameworkTemplate::$post_user_rating['rating'] > 0 ) { ?>
                                                    <div class="btTourRating">
                                                            <div itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( esc_attr__( 'Rated %d out of 5', 'bt_plugin' ), BoldThemesFrameworkTemplate::$post_user_rating['rating'] ) ?>">
                                                                    <span style="width:<?php echo wp_kses_post(BoldThemesFrameworkTemplate::$post_user_rating['percent']);?>%"><strong itemprop="ratingValue"><?php echo BoldThemesFrameworkTemplate::$post_user_rating['rating'];?></strong> <?php echo esc_html__( 'out of 5', 'bt_plugin' );?></span>
                                                            </div>
                                                            <span class="btNumberOfReviews"><?php echo BoldThemesFrameworkTemplate::$tour_comments_number;?> <?php echo esc_html__( 'Reviews', 'bt_plugin' );?></span>
                                                    </div>
                                                <?php } ?>
                                                <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_duration != '') { ?>
                                                    <div class="btTourDuration"><?php echo BoldThemesFrameworkTemplate::$tour_rwmb_duration;?></div>
                                                <?php } ?>
                                        </div>
                                </div>
                        </div>
                </div>
                <?php
        }
}

// TOUR SINGLE BLOCK
if ( ! function_exists( 'boldthemes_get_tour_single_block_list_html' ) ) {
	function boldthemes_get_tour_single_block_list_html( $post_id = 0, $type = '' ) { 
            if ( $post_id == 0){
                return false;
            } 
            bt_get_tour_single_data( $post_id );
           
            $permalink      = get_permalink($post_id);
            
            bt_get_tour_image_by_type( $post_id, $type );
            $img_src        = BoldThemesFrameworkTemplate::$feat_image_tour_list['img_src'];
            $img_src_full   = BoldThemesFrameworkTemplate::$feat_image_tour_list['img_src_full'];
            
            $imgage_default = boldthemes_get_tour_default_image_by_type( $post_id, $type );
            $img_src_default   = $imgage_default['img_src']; 
            
            $rating = boldthemes_get_post_user_rating( $post_id );
            ?>
            <div class="btSingleTourBlock" data-tour-id="<?php echo $post_id;?>">
                    <div class="btSingleTourBlockInner">
                            <div class="btSingleTourImage">
                                    <div class="btImageWrapper">
                                            <a href="<?php echo esc_url( $permalink ); ?>">
                                                <img data-src="<?php echo esc_attr($img_src);?>" data-src-full="<?php echo esc_attr($img_src_full);?>" src="<?php echo $img_src_default;?>" alt="<?php echo BoldThemesFrameworkTemplate::$title;?>" data-loaded="0">
                                            </a>
                                    </div>
                            </div>
                            <?php echo bt_get_tour_single_price_html();?>
                            <div class="btSingleTourContent">
                                    <div class="btListWrapper">
                                            <div class="btListDetails">
                                                    <div class="btSingleTourHeadline">
                                                        <a href="<?php echo esc_url( $permalink ); ?>"><?php echo BoldThemesFrameworkTemplate::$title;?></a>
                                                    </div>
                                                    <?php if ( !empty(BoldThemesFrameworkTemplate::$tour_categories) ){ ?>
                                                        <div class="btSingleTourCategories">
                                                            <?php 
                                                                foreach ( BoldThemesFrameworkTemplate::$tour_categories as $tour_category) { 
                                                                    $category_link = get_term_link($tour_category);
                                                                    echo '<a href="' . esc_url( $category_link ) . '">' . $tour_category->name . '</a>'; 
                                                                } 
                                                            ?>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="btSingleTourExcerpt"><?php echo BoldThemesFrameworkTemplate::$excerpt;?></div>
                                            </div>
                                            <div class="btViewDetails"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html__( 'View Details', 'bt_plugin' );?></a></div>
                                    </div>
                                    <div class="btSingleTourMeta">
                                            <?php if ( BoldThemesFrameworkTemplate::$post_user_rating['rating'] > 0 ) { ?>
                                                <div class="btTourRating">
                                                        <div itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( esc_attr__( 'Rated %d out of 5', 'bt_plugin' ), BoldThemesFrameworkTemplate::$post_user_rating['rating'] ) ?>">
                                                                <span style="width:<?php echo wp_kses_post(BoldThemesFrameworkTemplate::$post_user_rating['percent']);?>%"><strong itemprop="ratingValue"><?php echo BoldThemesFrameworkTemplate::$post_user_rating['rating'];?></strong> <?php echo esc_html__( 'out of 5', 'bt_plugin' );?></span>
                                                        </div>
                                                        <span class="btNumberOfReviews"><?php echo BoldThemesFrameworkTemplate::$tour_comments_number;?> <?php echo esc_html__( 'Reviews', 'bt_plugin' );?></span>
                                                </div>
                                            <?php } ?>
                                            <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_duration != '') { ?>
                                                <div class="btTourDuration"><?php echo BoldThemesFrameworkTemplate::$tour_rwmb_duration;?></div>
                                            <?php } ?>
                                            <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_destination_text != '') { ?>
                                                <div class="btTourLocation"><?php echo BoldThemesFrameworkTemplate::$tour_rwmb_destination_text;?></div>
                                            <?php } ?>
                                            <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_travellers != '') { ?>
                                                <div class="btTourTravellers"><?php echo BoldThemesFrameworkTemplate::$tour_rwmb_travellers;?></div>
                                            <?php } ?>
                                    </div>
                            </div>
                    </div>
            </div>
            <?php            
        }
}

if ( ! function_exists( 'bt_get_tour_single_price_html' ) ) {
    function bt_get_tour_single_price_html() { 
        if ( BoldThemesFrameworkTemplate::$tour_currency_before_price ){
            $price_text_original = BoldThemesFrameworkTemplate::$tour_currency . bt_get_tour_original_price();
            $price_text_regular  = BoldThemesFrameworkTemplate::$tour_currency . bt_get_tour_regular_price();
        }else{
            $price_text_original = bt_get_tour_original_price().BoldThemesFrameworkTemplate::$tour_currency;
            $price_text_regular  = bt_get_tour_regular_price().BoldThemesFrameworkTemplate::$tour_currency;
        }
        ?>
            <div class="btSingleTourPrice">
                <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_discount_title != '' ) { ?>
                    <div class="btTourOffer"><?php echo esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_discount_title);?></div>
                <?php } ?>
                <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_original_price != '' || BoldThemesFrameworkTemplate::$tour_rwmb_regular_price != '' ) { ?>
                    <div class="btTourPrice">
                        <span class="from">From</span>
                        <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_original_price != '' ) { ?>
                        <del><?php echo esc_html( bt_get_tour_original_price());?></del> 
                        <?php } ?>
                         <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_regular_price != '' ) { ?>
                        <ins><?php echo esc_html(bt_get_tour_regular_price());?></ins>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php 
    }
}

/*
*
* Generate HTML for pagination in search result list
*/
if ( ! function_exists( 'boldthemes_tour_pagination' ) ) {
    function boldthemes_tour_pagination( $numpages = '' ){        
        $numpages = BoldThemesFrameworkTemplate::$max_page;
        $paged = bt_tour_get_current_page(); 
        if ($numpages == '') { $numpages = '1'; }        
        if ( $numpages == '1' ) { return false; }
        
        BoldThemesFrameworkTemplate::$tour_listing_pagination = boldthemes_get_option( 'tour_listing_pagination' ) != '' ? 
                boldthemes_get_option( 'tour_listing_pagination' ) : BoldThemes_Customize_Default::$data['tour_listing_pagination'];
        
        if ( BoldThemesFrameworkTemplate::$tour_listing_pagination == 'loadmore' ){                             
             echo '<div class="bt_bb_listing_box bt_bb_loadmore_box"><div class="bt_bb_loadmore" data-paged="1" data-maxpage="' . $numpages . '">' . esc_html__('Load more tours', 'bt_plugin') . '</div></div>';   
        }else{            
            $format = true ? '?page=%#%' : '/page/%#%';           
            $pagination_args = array(
                    'base'		 => get_site_url() . '/tour/' . '%_%',
                    'format'             => $format,
                    'current'            => max(1, $paged),
                    'total'		 => $numpages,
                    'show_all'           => false,
                    'end_size'           => 1,
                    'mid_size'           => 2,
                    'prev_next'          => true,
                    'prev_text'          => esc_html__( 'Previous', 'bt_plugin' ),
                    'next_text'          => esc_html__( 'Next', 'bt_plugin' ), 
                    'type'               => 'plain',
                    'add_args'           => false,
                    'add_fragment'       => '',
                    'before_page_number' => '',
                    'after_page_number'  => ''
              );

              $paginate_links = paginate_links( $pagination_args );
              if ( $paginate_links ) {
                    echo '<div class="btPagination boldSection gutter"><div class="port">';
                      echo $paginate_links;
                    echo '</div></div>';
              }
        }     
    }
}