<?php
$this->load_parts( array( 'html-start' ) );


bt_get_tour_options_data();
bt_get_tour_single_data( get_the_ID() );
?>

<?php $this->load_parts( array( 'header' ) ); ?>

<header class="amp-wp-article-header">
        <h1 class="amp-wp-title"><?php echo esc_html( $this->get( 'post_title' ) ); ?></h1>
        <?php $this->load_parts( apply_filters( 'amp_post_article_header_meta', array( 'meta-author', 'meta-time' ) ) ); ?>
</header>

<article class="amp-wp-article">

	<?php $this->load_parts( array( 'featured-image' ) ); ?>
        
	<div class="amp-wp-article-content">
                <?php
                    echo  boldthemes_get_tour_single_promo_title_amp_html();
                    echo  boldthemes_get_tour_single_info_amp_html();         
                ?>
            
                <?php if ( boldthemes_show_tour_information_tab_amp() ) { ?>
                    <div class="bt_bb_tab_item btTourInformationTab on">
                            <div class="bt_bb_tab_content">
                                    <?php echo boldthemes_get_tour_information_tab_amp_html();?>								
                            </div>
                    </div>
                <?php } ?>
            
		<?php echo $this->get( 'post_amp_content' ); ?>
            
                <?php if ( boldthemes_show_tour_plan_tab_amp() ) { ?>
                   <div class="bt_bb_tab_item btTourPlanTab">
                           <div class="bt_bb_tab_content">
                                   <?php echo boldthemes_get_tour_plan_tab_amp_html();?>
                           </div>
                   </div>
               <?php } ?>
	</div>

	<footer class="amp-wp-article-footer">
		<?php $this->load_parts( apply_filters( 'amp_post_article_footer_meta', array( 'meta-taxonomy', 'meta-comments-link' ) ) ); ?>
	</footer>
</article>

<?php $this->load_parts( array( 'footer' ) ); ?>

<?php
$this->load_parts( array( 'html-end' ) );
