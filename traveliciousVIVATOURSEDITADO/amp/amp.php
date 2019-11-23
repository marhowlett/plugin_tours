<?php

add_filter( 'amp_post_template_file', 'bt_amp_set_custom_template', 10, 3 );
function bt_amp_set_custom_template( $file, $type, $post ) {
    if ( is_single() ) {
	if ( 'single' === $type && 'tour' === $post->post_type ) {
		$file = dirname( __FILE__ ) . '/templates/single.php';
	}
        
        if ( 'meta-comments-link' === $type && 'tour' === $post->post_type ) {
		$file = dirname( __FILE__ ) . '/templates/meta-comments-link.php';
	}
        if ( 'footer' === $type && 'tour' === $post->post_type ) {
		$file = dirname( __FILE__ ) . '/templates/footer.php';
	}
    }
    return $file;
}

add_action( 'amp_post_template_head', 'bt_amp_post_template_add_custom');
function bt_amp_post_template_add_custom( $amp_template ) {
        $url = get_site_url(null, '/wp-content/plugins/travelicious/amp/js/single-tour-amp.js', 'http');     
        ?>
        <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script  src="<?php echo $url;?>"></script>
        <?php
}


// SINGLE TOUR : INFO
if ( ! function_exists( 'boldthemes_get_tour_single_info_amp_html' ) ) {
	function boldthemes_get_tour_single_info_amp_html() {
            ?>
            <div class="btSingleTourInfo btSingleTourInfoAmp">
                <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_discount_title ) { ?>
                    <div class="btPromoPrice"><?php echo esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_discount_title);?></div>
                <?php } ?>
                <div class="btSingleTourInfoInner">
                        <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_regular_price != '' ) { ?>
                            <div class="btTourIcon btTourPrice">
                                    <div class="btIcon"><span class="iconHolder"></span></div>
                                    <div class="btTourInfo">
                                            <div class="btTourTitle"><?php echo esc_html__( 'Price', 'bt_plugin' );?></div>
                                            <div class="btTourDesc"><?php echo esc_html(bt_get_tour_regular_price());?> <em><?php echo esc_html__( 'per', 'bt_plugin' );?> <span><?php echo esc_html__( 'person', 'bt_plugin' );?></span></em></div>
                                    </div>
                            </div>
                        <?php } ?>
                        <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_duration ) { ?>
                           <div class="btTourIcon btTourDuration">
                                   <div class="btIcon"><span class="iconHolder"></span></div>
                                   <div class="btTourInfo">
                                           <div class="btTourTitle"><?php echo esc_html__( 'Duration', 'bt_plugin' );?></div>
                                           <div class="btTourDesc"><?php echo esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_duration);?></div>
                                   </div>
                           </div>
                        <?php } ?>
                        <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_destination_text ) { ?>
                           <div class="btTourIcon btTourDestination">
                                   <div class="btIcon"><span class="iconHolder"></span></div>
                                   <div class="btTourInfo">
                                           <div class="btTourTitle"><?php echo esc_html__( 'Destination', 'bt_plugin' );?></div>
                                           <div class="btTourDesc">
                                                   <?php echo esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_destination_text);?>
                                           </div>
                                   </div>
                           </div>
                        <?php } ?>
                        <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_travellers ) { ?>
                            <div class="btTourIcon btTourTravellers">
                                    <div class="btIcon"><span class="iconHolder"></span></div>
                                    <div class="btTourInfo">
                                            <div class="btTourTitle"><?php echo esc_html__( 'Travellers', 'bt_plugin' );?></div>
                                            <div class="btTourDesc"><?php echo esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_travellers);?></div>
                                    </div>
                            </div>
                        <?php } ?>
                        <?php
                            $tour_show_rating	= boldthemes_get_option( 'tour_show_rating' ); 
                        ?>
                        <?php if ( BoldThemesFrameworkTemplate::$post_user_rating['rating'] > 0 && $tour_show_rating ) { ?>
                        <div class="btTourIcon btTourReviews">
                                <div class="btIcon"><span class="iconHolder"></span></div>
                                <div class="btTourInfo">
                                        <div class="btTourTitle"><?php echo BoldThemesFrameworkTemplate::$tour_comments_number;?> <?php echo esc_html__( 'Reviews', 'bt_plugin' );?></div>
                                        <div class="btTourDesc">
                                                <div itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( esc_attr__( 'Rated %d out of 5', 'bt_plugin' ), BoldThemesFrameworkTemplate::$post_user_rating['rating'] ) ?>">
                                                        <span style="width:<?php echo wp_kses_post(BoldThemesFrameworkTemplate::$post_user_rating['percent']);?>%"><strong itemprop="ratingValue"><?php echo BoldThemesFrameworkTemplate::$post_user_rating['rating'];?></strong> <?php echo esc_html__( 'out of 5', 'bt_plugin' );?></span>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        <?php } ?>
                </div>
        </div>
            <?php
        }
}

// SINGLE TOUR : PROMO TITLE
if ( ! function_exists( 'boldthemes_get_tour_single_promo_title_amp_html' ) ) {
	function boldthemes_get_tour_single_promo_title_amp_html() {
            if ( BoldThemesFrameworkTemplate::$tour_rwmb_supertitle != '' || BoldThemesFrameworkTemplate::$tour_rwmb_title != '' || BoldThemesFrameworkTemplate::$tour_rwmb_subtitle != '' ) { 
                ?>
                    <div class="btTourPromoTitle btTourPromoTitleAmp">
                            <header class="bt_bb_headline bt_bb_font_weight_normal bt_bb_size_large bt_bb_superheadline bt_bb_subheadline bt_bb_align_center">
                                    <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_supertitle != '' || BoldThemesFrameworkTemplate::$tour_rwmb_title != '' ) { ?>
                                        <h2>
                                                <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_supertitle != '' ) { ?>
                                                    <span class="bt_bb_headline_superheadline"><?php echo BoldThemesFrameworkTemplate::$tour_rwmb_supertitle;?></span>
                                                <?php } ?>
                                                <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_title != '' ) { ?>
                                                    <span class="bt_bb_headline_content"><span><?php echo BoldThemesFrameworkTemplate::$tour_rwmb_title;?></span></span>
                                                <?php } ?>
                                        </h2>
                                     <?php } ?>
                                     <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_subtitle != '' ) { ?>
                                        <div class="bt_bb_headline_subheadline"><?php echo BoldThemesFrameworkTemplate::$tour_rwmb_subtitle;?></div>
                                     <?php } ?>
                            </header>
                    </div>
                <?php                 
            }
        }
}

if ( ! function_exists( 'boldthemes_show_tour_information_tab_amp' ) ) {
	function boldthemes_show_tour_information_tab_amp() {
            $show = false;
            if (   
                    trim(BoldThemesFrameworkTemplate::$tour_rwmb_destination_text) != '' || 
                    trim(BoldThemesFrameworkTemplate::$content) != '' ||
                    BoldThemesFrameworkTemplate::$tour_rwmb_start_location ||
                    BoldThemesFrameworkTemplate::$tour_rwmb_departure_location ||
                    BoldThemesFrameworkTemplate::$tour_rwmb_return_location ||
                    ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_dt) && count(BoldThemesFrameworkTemplate::$tour_rwmb_dt) > 0 ) ||
                    ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_price_include) && count(BoldThemesFrameworkTemplate::$tour_rwmb_price_include) > 0 ) ||
                    ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_price_no_include) && count(BoldThemesFrameworkTemplate::$tour_rwmb_price_no_include) > 0 ) ||
                    ( array_filter(BoldThemesFrameworkTemplate::$tour_rwmb_additional_prices,  'bt_filter_array_empty') && is_array(BoldThemesFrameworkTemplate::$tour_rwmb_additional_prices) && count(BoldThemesFrameworkTemplate::$tour_rwmb_additional_prices) > 0 ) ||
                    ( array_filter(BoldThemesFrameworkTemplate::$tour_rwmb_additional_info,  'bt_filter_array_empty') && is_array(BoldThemesFrameworkTemplate::$tour_rwmb_additional_info) && count(BoldThemesFrameworkTemplate::$tour_rwmb_additional_info) > 0 ) ||
                    ( array_filter(BoldThemesFrameworkTemplate::$tour_rwmb_additional_custom,  'bt_filter_array_empty') && is_array(BoldThemesFrameworkTemplate::$tour_rwmb_additional_custom) && count(BoldThemesFrameworkTemplate::$tour_rwmb_additional_custom) > 0 ) 
            ){
                $show = true;
            }
            return $show;
        }
}
if ( ! function_exists( 'boldthemes_get_tour_information_tab_amp_html' ) ) {
	function boldthemes_get_tour_information_tab_amp_html() {
            ?>
                <?php if ( BoldThemesFrameworkTemplate::$excerpt ) { ?>
                    <div class="btTourExcerpt btTourExcerptAmp"><?php echo BoldThemesFrameworkTemplate::$excerpt;?></div>
                <?php } ?>
                <div class="btTourIncludes btTourIncludesAmp">
                        <h3><?php echo esc_html__( 'What\'s included', 'bt_plugin' );?></h3>
                        
                        <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_destination_text ) { ?>
                            <div class="btTourSingleInclude">
                                    <div class="btTourSingleIncludeTitle"><?php echo esc_html__( 'Destination', 'bt_plugin' );?></div>
                                    <div class="btTourSingleIncludeContent">                                            
                                            <?php 
                                            if ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_destination) && count(BoldThemesFrameworkTemplate::$tour_rwmb_destination) > 1  ){
                                                $destination_arr = array();
                                                foreach ( BoldThemesFrameworkTemplate::$tour_rwmb_destination as $destination ){
                                                    array_push($destination_arr,$destination->name);
                                                }
                                                if ( is_array($destination_arr) ) {
                                                    BoldThemesFrameworkTemplate::$tour_rwmb_destination_text = implode(' , ', $destination_arr);
                                                }
                                            }
                                            
                                            echo '<span>' . esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_destination_text) . '</span>';
                                            
                                            $page_link = '';
                                            $text_link = esc_html__( 'Discover Destinations', 'bt_plugin' );
                                            if ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_destination) && count(BoldThemesFrameworkTemplate::$tour_rwmb_destination) == 1  ){
                                                if ( isset(BoldThemesFrameworkTemplate::$tour_rwmb_destination[0]) ){
                                                    if ( isset( BoldThemesFrameworkTemplate::$tour_rwmb_destination[0]->page_slug) &&  BoldThemesFrameworkTemplate::$tour_rwmb_destination[0]->page_slug != '' ){
                                                        $destination_page_slug = BoldThemesFrameworkTemplate::$tour_rwmb_destination[0]->page_slug;
                                                        $page_link = bt_bb_get_permalink_by_slug_tour( $destination_page_slug, 'portfolio' ) != '' ? bt_bb_get_permalink_by_slug_tour( $destination_page_slug, 'portfolio' ) : '';
                                                        ?>
                                                            <span class="btTourSingleIncludeLink"><a href="#" class="btDiscoverDestinationLink" data-link="<?php echo esc_attr($page_link);?>"><?php echo esc_html__( $text_link, 'bt_plugin' );?></a></span>
                                                        <?php
                                                    }
                                                 }                                                
                                            }
                                            ?>
                                            
                                    </div>
                            </div>
                        <?php } ?>
                        <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_start_location ) { ?>
                            <div class="btTourSingleInclude">
                                    <div class="btTourSingleIncludeTitle"><?php echo esc_html__( 'Tour Start Location', 'bt_plugin' );?></div>
                                    <div class="btTourSingleIncludeContent">
                                            <span><?php echo esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_start_location);?></span>
                                    </div>
                            </div>
                        <?php } ?>
                        
                        <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_departure_location ) { ?>
                            <div class="btTourSingleInclude">
                                    <div class="btTourSingleIncludeTitle"><?php echo esc_html__( 'Departure Location', 'bt_plugin' );?></div>
                                    <div class="btTourSingleIncludeContent">
                                            <span><?php echo esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_departure_location);?></span>
                                    </div>
                            </div>
                        <?php } ?>
                        
                        <?php if ( BoldThemesFrameworkTemplate::$tour_rwmb_return_location ) { ?>
                            <div class="btTourSingleInclude">
                                    <div class="btTourSingleIncludeTitle"><?php echo esc_html__( 'Return Location', 'bt_plugin' );?></div>
                                    <div class="btTourSingleIncludeContent">
                                            <span><?php echo esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_return_location);?></span>
                                    </div>
                            </div>
                        <?php } ?>
                        <?php if ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_dt) && count(BoldThemesFrameworkTemplate::$tour_rwmb_dt) > 0 ) { ?>
                            <div class="btTourSingleInclude">
                                    <div class="btTourSingleIncludeTitle"><?php echo esc_html__( 'Tour Start Date &amp; Time', 'bt_plugin' );?></div>
                                    <div class="btTourSingleIncludeContent">
                                            <span><?php echo esc_html(BoldThemesFrameworkTemplate::$tour_rwmb_dt_text);?></span>
                                            <?php if ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_dt) && count(array_slice(BoldThemesFrameworkTemplate::$tour_rwmb_dt, 1)) > 0 ){ ?>
                                                <span class="btTourSingleIncludeLink btTourSingleIncludeLinkViewOtherTime"><a href="#"><?php echo esc_html__( 'View other Times', 'bt_plugin' );?></a></span>                                                                                        
                                                <div class="btTourSingleIncludeContentOtherTimes" id="btTourSingleIncludeContentOtherTimes" style="display:none;">
                                                    <?php
                                                    echo "<ul>";
                                                        foreach ( array_slice(BoldThemesFrameworkTemplate::$tour_rwmb_dt, 1) as $tour_rwmb_dt ) {
                                                            echo '<li><span>' . date( BoldThemesFrameworkTemplate::$tour_rwmb_dt_format,  strtotime($tour_rwmb_dt) ) . '</span></li>';                                                                                                    
                                                        }
                                                    echo "</ul>";
                                                    ?>
                                                </div>
                                            <?php } ?>
                                    </div>
                            </div>
                        <?php } ?>
                        
                        <?php if ( array_filter(BoldThemesFrameworkTemplate::$tour_rwmb_additional_info,  'bt_filter_array_empty') && is_array(BoldThemesFrameworkTemplate::$tour_rwmb_additional_info) && count(BoldThemesFrameworkTemplate::$tour_rwmb_additional_info) > 0 ) { ?>
                            <div class="btTourSingleInclude">
                                    <div class="btTourSingleIncludeTitle"><?php echo esc_html__( 'Additional Information', 'bt_plugin' );?></div>
                                    <div class="btTourSingleIncludeContent">                                        
                                            <?php foreach( BoldThemesFrameworkTemplate::$tour_rwmb_additional_info as $tour_rwmb_additional_info) { ?>
                                                <span class="btTourSingleIncludeContentAdditionalInformation"><strong><?php echo $tour_rwmb_additional_info['title'];?>:</strong> <?php echo $tour_rwmb_additional_info['description'];?></span>
                                            <?php } ?>                                       
                                    </div>
                            </div>
                        <?php } ?>
                        
                        <?php if ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_price_include) && count(BoldThemesFrameworkTemplate::$tour_rwmb_price_include) > 0 ) { ?>
                            <div class="btTourSingleInclude">
                                    <div class="btTourSingleIncludeTitle"><?php echo esc_html__( 'Price includes', 'bt_plugin' );?></div>
                                    <div class="btTourSingleIncludeContent">
                                            <ul class="btIncludedItems">
                                                <?php foreach ( BoldThemesFrameworkTemplate::$tour_rwmb_price_include as $tour_rwmb_price_include ) { ?>
                                                    <li><span><?php echo $tour_rwmb_price_include;?></span></li>
                                                <?php } ?>
                                            </ul>
                                    </div>
                            </div>
                        <?php } ?>
                        
                        <?php if ( is_array(BoldThemesFrameworkTemplate::$tour_rwmb_price_no_include) && count(BoldThemesFrameworkTemplate::$tour_rwmb_price_no_include) > 0 ) { ?>
                            <div class="btTourSingleInclude">
                                    <div class="btTourSingleIncludeTitle"><?php echo esc_html__( 'Price does not include', 'bt_plugin' );?></div>
                                    <div class="btTourSingleIncludeContent">
                                            <ul class="btExcludedItems">
                                                 <?php foreach ( BoldThemesFrameworkTemplate::$tour_rwmb_price_no_include as $tour_rwmb_price_no_include ) { ?>
                                                    <li><span><?php echo $tour_rwmb_price_no_include;?></span></li>
                                                    <?php } ?>
                                            </ul>
                                    </div>
                            </div>
                         <?php } ?>
                        
                        <?php if ( array_filter(BoldThemesFrameworkTemplate::$tour_rwmb_additional_custom,  'bt_filter_array_empty') && is_array(BoldThemesFrameworkTemplate::$tour_rwmb_additional_custom) && count(BoldThemesFrameworkTemplate::$tour_rwmb_additional_custom) > 0 ) { ?>
                            <div class="btTourSingleInclude">
                                    <div class="btTourSingleIncludeTitle"><?php echo esc_html__( 'Complementaries', 'bt_plugin' );?></div>
                                    <div class="btTourSingleIncludeContent">
                                           <?php foreach( BoldThemesFrameworkTemplate::$tour_rwmb_additional_custom as $tour_rwmb_additional_custom) { ?>                                                
                                                    <span class="btTourSingleIncludeContentAdditionalCustomInformation">
														<span>
                                                        <?php if ( $tour_rwmb_additional_custom['title'] != '' ) { ?>
                                                            <strong><?php echo $tour_rwmb_additional_custom['title'];?>:</strong> 
                                                        <?php } ?>
                                                        <?php if ( $tour_rwmb_additional_custom['description'] != '' ) { ?>
                                                            <?php echo $tour_rwmb_additional_custom['description'];?>
                                                        <?php } ?>
														</span>
                                                    </span>                                                
                                          <?php } ?>
                                    </div>
                            </div>
                        <?php } ?>
                       
                        <?php if ( array_filter(BoldThemesFrameworkTemplate::$tour_rwmb_additional_prices,  'bt_filter_array_empty') && is_array(BoldThemesFrameworkTemplate::$tour_rwmb_additional_prices) && count(BoldThemesFrameworkTemplate::$tour_rwmb_additional_prices) > 0 ) { ?>
                            <div class="btTourSingleInclude">
                                    <div class="btTourSingleIncludeTitle"><?php echo esc_html__( 'Additional Prices', 'bt_plugin' );?></div>
                                    <div class="btTourSingleIncludeContent">
                                            <?php foreach( BoldThemesFrameworkTemplate::$tour_rwmb_additional_prices as $tour_rwmb_additional_prices) { ?>
                                                <?php if ( $tour_rwmb_additional_prices['title'] != '' ) { ?>
                                                <span class="btTourSingleIncludeContentAdditionalPrice">
                                                        <strong><?php echo $tour_rwmb_additional_prices['title'];?>:</strong> 
                                                        <?php echo bt_get_tour_price($tour_rwmb_additional_prices['price']);?>
                                                </span>
                                                <?php } ?>
                                            <?php } ?>
                                    </div>
                            </div>
                        <?php } ?>
                </div>
                <?php
        }
}


// SINGLE TOUR TAB AMP: TOUR PLAN
if ( ! function_exists( 'boldthemes_show_tour_plan_tab_amp' ) ) {
	function boldthemes_show_tour_plan_tab_amp() {
            $show = false;
            if (  !empty(BoldThemesFrameworkTemplate::$tour_rwmb_plan_title) && is_array(BoldThemesFrameworkTemplate::$tour_rwmb_plan_title) ){
                $show = true;
            }
            return $show;
        }
}

if ( ! function_exists( 'boldthemes_get_tour_plan_tab_amp_html' ) ) {
	function boldthemes_get_tour_plan_tab_amp_html() {
            ?>
            <div class="btTourPlan btTourPlanAmp">
                    <?php
                     $i_tour_rwmb_plan_title = 1; 
                     for ( $i =0; $i < count(BoldThemesFrameworkTemplate::$tour_rwmb_plan_title); $i++) {
                         $title         = isset(BoldThemesFrameworkTemplate::$tour_rwmb_plan_title[$i]) ? BoldThemesFrameworkTemplate::$tour_rwmb_plan_title[$i] : '';
                         $headline      = isset(BoldThemesFrameworkTemplate::$tour_rwmb_plan_headline[$i]) ? BoldThemesFrameworkTemplate::$tour_rwmb_plan_headline[$i] : '';;
                         $description   = isset(BoldThemesFrameworkTemplate::$tour_rwmb_plan_description[$i]) ? BoldThemesFrameworkTemplate::$tour_rwmb_plan_description[$i] : '';
                         ?>
                            <div class="btTourPlanDay">
                                    <div class="btPlanDay" id="tourPlanDay<?php echo $i_tour_rwmb_plan_title;?>"></div>
                                    <div class="btDayTitle"><?php echo $title;?></div>
                                    <div class="btDayHeadline"><h3><?php echo $headline;?></h3></div>
                                    <div class="btDayContent"><?php echo $description;?></div>
                            </div>
                         <?php
                         $i_tour_rwmb_plan_title++;
                     }
                    ?>
            </div>    
            <?php
        }
}
