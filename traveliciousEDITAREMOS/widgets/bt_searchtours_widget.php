<?php
$listing_form_action_page   = get_post_type_archive_link( 'tour' ) ? get_post_type_archive_link( 'tour' ) : '#' ;
$tour_categories            = get_terms( array('taxonomy' => 'tour_category','hide_empty' => false,'parent' => 0) );

$bt_tour_single_design              = boldthemes_get_tour_list_type();
$tour_similar_tours_list_gap        = boldthemes_get_tour_list_gap();
$tour_similar_tours_list_columns    = boldthemes_get_tour_list_columns();

BoldThemesFrameworkTemplate::$search_show_months      = boldthemes_get_option( 'tour_search_show_only_months' ) != ''
                    ? boldthemes_get_option( 'tour_search_show_only_months' ) : BoldThemes_Customize_Default::$data['tour_search_show_only_months'];
$tour_search_show_only_months = BoldThemesFrameworkTemplate::$search_show_months ? 1 : 0;

BoldThemesFrameworkTemplate::$datepicker_format = bt_set_wp_to_datepicker_format( get_option( 'date_format' ), $tour_search_show_only_months );
?>
<style>
#auto-bt_tour_search_destination_widget {
  position: absolute;
}

</style>
<form id="tour_search_form" name="tour_search_form"  action="<?php echo esc_attr($listing_form_action_page);?>" method='get'>
    <input type="hidden" name="bt_tour_search_list_count" id="bt_tour_search_list_count" value="<?php echo esc_attr(BoldThemesFrameworkTemplate::$tour_list_count) ;?>" />
    <input type="hidden" name="bt_tour_search_list_view"  id="bt_tour_search_list_view"  value="<?php echo esc_attr(BoldThemesFrameworkTemplate::$tour_list_view);?>" />
    <input type="hidden" name="bt_tour_search_type"  id="bt_tour_search_type"  value="<?php echo esc_attr(BoldThemesFrameworkTemplate::$tour_search_action_type);?>" />    
    <input type="hidden" name="bt_tour_single_design"  id="bt_tour_single_design"  value="<?php echo esc_attr(bt_tour_single_design);?>" />
    <input type="hidden" name="bt_tour_search_list_gap"  id="bt_tour_search_list_gap"  value="<?php echo esc_attr($tour_similar_tours_list_gap);?>" />
    <input type="hidden" name="bt_tour_search_list_columns"  id="bt_tour_search_list_columns"  value="<?php echo esc_attr($tour_similar_tours_list_columns);?>" />
    <input type="hidden" name="bt_tour_search_show_months"  id="bt_tour_search_show_months"  value="<?php echo esc_attr($tour_search_show_only_months);?>" />
    <input type="hidden" name="bt_tour_search_date_format"  id="bt_tour_search_date_format"  value="<?php echo esc_attr(BoldThemesFrameworkTemplate::$datepicker_format);?>" />
    <div class="tour_search gutter">
            <div class="port">
                    <div class="btSearchToursRow">
                            <?php if ( $instance['show_keyword'] ){  ?>
                                <div class="btSearchField btFieldKeyword">
                                        <label for="bt_tour_search_keyword"><?php esc_html_e( 'Tour Keywords', 'bt_plugin' ); ?></label>
                                        <div class="btFieldWrapper">
                                                <input id="bt_tour_search_keyword" name="bt_tour_search_keyword" type="text" value='' placeholder="<?php echo esc_attr( $instance['label_keyword'], 'bt_plugin' ); ?>" />
                                        </div>
                                </div>
                            <?php } ?>                            
                            <div class="btSearchField btFieldDestination btFieldDestinationWidget">
                                    <label for="bt_tour_search_destination_widget"><?php esc_html_e( 'Select your Destination', 'bt_plugin' ); ?></label>
                                    <div class="btFieldWrapper btFieldWrapperWidget">
                                        <span class="bt_tour_search_destination_widget_span"></span>
                                            <input id="bt_tour_search_destination_widget" name="bt_tour_search_destination" class="bt_tour_search_destination_widget" type="text" value='' placeholder="<?php echo esc_attr( $instance['label_destination'], 'bt_plugin' ); ?>" />
                                            <div id="auto-bt_tour_search_destination_widget"></div>
                                    </div>
                            </div>                             
                            <?php if ( $instance['show_departure_date'] ){  ?>
                                <div class="btSearchField btFieldDate btFieldDateWidget">
                                        <label for="bt_tour_search_date"><?php esc_html_e( 'Departure Date', 'bt_plugin' ); ?></label>
                                        <div class="btFieldWrapper btFieldWrapperWidget">
                                            <span class="bt_tour_search_date_widget_span"></span>
                                                <input id="bt_tour_search_date_widget" name="bt_tour_search_date" class="bt_tour_search_date_widget" type="text" placeholder="<?php echo esc_attr( $instance['label_departure_date'], 'bt_plugin' ); ?>" value='' onfocus="" onblur="" />
                                        </div>
                                </div>
                             <?php } ?>
                            <?php if ( $instance['show_prices_filters'] ){  ?>
                                <div class="btSearchField btFieldPrice btFieldPriceFrom">
                                        <label for="bt_tour_search_price_from"><?php esc_html_e( 'Price From', 'bt_plugin' ); ?></label>
                                        <input id="bt_tour_search_price_from" name="bt_tour_search_price_from" type="number" value='' min="0" placeholder="<?php echo esc_attr( $instance['label_price_from'], 'bt_plugin' ); ?>" step="any" />
                                </div>
                                <div class="btSearchField btFieldPrice btFieldPriceTo">
                                        <label for="bt_tour_search_price_to"><?php esc_html_e( 'Price To', 'bt_plugin' ); ?></label>
                                        <input id="bt_tour_search_price_to" name="bt_tour_search_price_to" type="number" value='' min="0" placeholder="<?php echo esc_attr( $instance['label_price_to'], 'bt_plugin' ); ?>" step="any" />
                                </div>
                            <?php } ?>
                            <div class="btSearchField btSearchButton">
                                    <button  id="bt_bb_tour_search_submit"><span><?php esc_html_e( $instance['label_search_button'], 'bt_plugin' ); ?></span></button>
                            </div>
                    </div>
                    <?php if ( $instance['show_categories'] ) { ?>
                        <div class="btSearchToursRow btSearchCategories">
                                <div class="btCategories">
                                        <div class="btLabelCategories"><?php esc_html_e( 'Categories', 'bt_plugin' ); ?>:</div>
                                        <?php 
                                        $i = 1;
                                        foreach ( $tour_categories as $tour_category ) {  
                                            $tour_category_name = preg_replace('/\s+/', '_', $tour_category->name);
                                            ?>
                                                <div class="btSingleCategory">
                                                       <input type="checkbox" id="bt_tour_search_category_<?php echo $tour_category_name;?>" name="bt_tour_search_category[]"  value="<?php echo $tour_category->term_id;?>"/>
                                                       <label for="bt_tour_search_category_<?php echo $tour_category_name;?>"><?php echo $tour_category->name;?></label>
                                               </div>
                                            <?php
                                            $i++;
                                            if ( $i > 5 ){
                                                break;
                                            }
                                        } 
                                        ?>
                                        <span class="btSearchCategoriesIncludeLink btSearchCategoriesIncludeLinkViewOtherCategories"><a href="#"><?php esc_html_e( 'More categories', 'bt_plugin' ); ?> </a></span>
                                        <div class="btSearchCategoriesIncludeContentOtherCategories" id="btSearchCategoriesIncludeContentOtherCategories" style="display:none;">
                                            <?php
                                                foreach ( array_slice($tour_categories, 5) as $tour_category ) {
                                                    $tour_category_name = preg_replace('/\s+/', '_', $tour_category->name);
                                                   ?>
                                                        <div class="btSingleCategory">
                                                               <input type="checkbox" id="bt_tour_search_category_<?php echo $tour_category_name;?>" name="bt_tour_search_category[]"  value="<?php echo $tour_category->term_id;?>"/>
                                                               <label for="bt_tour_search_category_<?php echo $tour_category_name;?>"><?php echo $tour_category->name;?></label>
                                                       </div>
                                                    <?php
                                                 }
                                            ?>
                                        </div>
                                </div>
                        </div>
                    <?php } ?>
            </div>
    </div>
</form>
