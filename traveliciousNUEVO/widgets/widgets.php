<?php
//
// bt tours WIDGETS
//
function bt_enqueue_scripts_widgets() {
    bt_get_tour_options_data();
    
    BoldThemesFrameworkTemplate::$tour_search_show_only_months      = boldthemes_get_option( 'tour_search_show_only_months' ) != ''
                    ? boldthemes_get_option( 'tour_search_show_only_months' ) : BoldThemes_Customize_Default::$data['tour_search_show_only_months'];
    $tour_search_show_only_months = BoldThemesFrameworkTemplate::$tour_search_show_only_months ? 1 : 0;            

    BoldThemesFrameworkTemplate::$datepicker_format = bt_set_wp_to_datepicker_format( get_option( 'date_format' ), $tour_search_show_only_months );
                
    wp_register_script( 'tour_widgets_js', dirname(plugin_dir_url( __FILE__ )) . '/assets/js/widgets.js' );
    wp_localize_script( 'tour_widgets_js', 'ajax_widgets_object', array(
                'ajax_url'              => admin_url( 'admin-ajax.php' ),
                'show_only_months'      => $tour_search_show_only_months,
                'label_current_month'   => esc_html__( 'Current month', 'bt_plugin' ),
                'label_clear'           => esc_html__( 'Clear', 'bt_plugin' ),
                'label_more_cats'       => esc_html__( 'More categories', 'bt_plugin' ),
                'label_less_cats'       => esc_html__( 'Less categories', 'bt_plugin' ),
                'date_format'           => bt_set_wp_to_datepicker_format( get_option( 'date_format' ),$tour_search_show_only_months ),
            )
    );
    wp_enqueue_script( 'tour_widgets_js' );
    
}
add_action( 'wp_enqueue_scripts', 'bt_enqueue_scripts_widgets' );

function tour_register_listing_widgets() {
	register_widget( 'BT_SearchTours_Widget' );
	register_widget( 'BT_Tours_Widget' );
}
add_action( 'widgets_init', 'tour_register_listing_widgets' );

// BT_SearchTours_Widget
if ( ! class_exists( 'BT_SearchTours_Widget' ) ) {
	// Search Tours
	class BT_SearchTours_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_searchtours_widget', // Base ID
				__( 'BT Search Tours', 'bt_plugin' ), // Name
				array( 
					'description' => __( 'Displays a search form and define it\'s fields and parameters.', 'bt_plugin' )
				) 
			);
		}

		public function widget( $args, $instance ) {
                        $class = array( 'btSearchToursWidget' );
                        echo $args['before_widget'];
                            if ( ! empty( $instance['title'] ) ) {
                                    echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
                            }                            	
                            echo '<div class="' .  implode( ' ', $class ) . '">';
                                    require_once( 'bt_searchtours_widget.php' );
                            echo  '</div>';
                        echo $args['after_widget'];			
		}

		public function form( $instance ) {
			$title                  = ! empty( $instance['title'] ) ? $instance['title'] : '';
                        $label_keyword          = ! empty( $instance['label_keyword'] ) ? $instance['label_keyword'] : '';
                        $label_destination      = ! empty( $instance['label_destination'] ) ? $instance['label_destination'] : '';
                        $label_departure_date   = ! empty( $instance['label_departure_date'] ) ? $instance['label_departure_date'] : '';
                        $label_price_from       = ! empty( $instance['label_price_from'] ) ? $instance['label_price_from'] : '';
                        $label_price_to         = ! empty( $instance['label_price_to'] ) ? $instance['label_price_to'] : '';
                        $label_search_button    = ! empty( $instance['label_search_button'] ) ? $instance['label_search_button'] : '';
                        $show_keyword		= ! empty( $instance['show_keyword'] ) ? $instance['show_keyword'] : '';
                        $show_departure_date	= ! empty( $instance['show_departure_date'] ) ? $instance['show_departure_date'] : '';
                        $show_prices_filters	= ! empty( $instance['show_prices_filters'] ) ? $instance['show_prices_filters'] : '';
                        $show_categories	= ! empty( $instance['show_categories'] ) ? $instance['show_categories'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p> 
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'label_keyword' ) ); ?>"><?php _e( 'Label for Keyword:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label_keyword' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label_keyword' ) ); ?>" type="text" value="<?php echo esc_attr( $label_keyword ); ?>">
			</p> 
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'label_destination' ) ); ?>"><?php _e( 'Label for Destination:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label_destination' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label_destination' ) ); ?>" type="text" value="<?php echo esc_attr( $label_destination ); ?>">
			</p>
			 <p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'label_departure_date' ) ); ?>"><?php _e( 'Label for Departure Date:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label_departure_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label_departure_date' ) ); ?>" type="text" value="<?php echo esc_attr( $label_departure_date ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'label_price_from' ) ); ?>"><?php _e( 'Label for Price From:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label_price_from' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label_price_from' ) ); ?>" type="text" value="<?php echo esc_attr( $label_price_from ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'label_price_to' ) ); ?>"><?php _e( 'Label for Price To:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label_price_to' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label_price_to' ) ); ?>" type="text" value="<?php echo esc_attr( $label_price_to ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'label_search_button' ) ); ?>"><?php _e( 'Label for Search Button:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label_search_button' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label_search_button' ) ); ?>" type="text" value="<?php echo esc_attr( $label_search_button ); ?>">
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_keyword'], 'on' ); ?> id="<?php echo $this->get_field_id('show_keyword'); ?>" name="<?php echo $this->get_field_name('show_keyword'); ?>" /> 
				<label for="<?php echo $this->get_field_id('show_keyword'); ?>"><?php _e( 'Show Keyword Filter', 'bt_plugin' ); ?></label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_departure_date'], 'on' ); ?> id="<?php echo $this->get_field_id('show_departure_date'); ?>" name="<?php echo $this->get_field_name('show_departure_date'); ?>" /> 
				<label for="<?php echo $this->get_field_id('show_departure_date'); ?>"><?php _e( 'Show Departure Date Filter', 'bt_plugin' ); ?></label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_prices_filters'], 'on' ); ?> id="<?php echo $this->get_field_id('show_prices_filters'); ?>" name="<?php echo $this->get_field_name('show_prices_filters'); ?>" /> 
				<label for="<?php echo $this->get_field_id('show_prices_filters'); ?>"><?php _e( 'Show Prices Filter', 'bt_plugin' ); ?></label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_categories'], 'on' ); ?> id="<?php echo $this->get_field_id('show_categories'); ?>" name="<?php echo $this->get_field_name('show_categories'); ?>" /> 
				<label for="<?php echo $this->get_field_id('show_categories'); ?>"><?php _e( 'Show Categories Filter', 'bt_plugin' ); ?></label>
			</p>
			<p class="bt-admin-description bt-background-description bt-link-underline-bold">
				<a href="http://documentation.bold-themes.com/travelicious/widgets/#bt-search-tours" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
				<?php _e( 'For more help with this widget, please', 'bt_plugin' ); ?> <a href="http://documentation.bold-themes.com/travelicious/widgets/#bt-search-tours" target="_blank"><?php _e( 'click here', 'bt_plugin' ); ?></a>.
			</p>
			<?php 
		}
                
              

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title']                  = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
                        $instance['label_keyword']          = ( ! empty( $new_instance['label_keyword'] ) ) ? strip_tags( $new_instance['label_keyword'] ) : '';
                        $instance['label_destination']      = ( ! empty( $new_instance['label_destination'] ) ) ? strip_tags( $new_instance['label_destination'] ) : '';
                        $instance['label_departure_date']   = ( ! empty( $new_instance['label_departure_date'] ) ) ? strip_tags( $new_instance['label_departure_date'] ) : '';
                        $instance['label_price_from']       = ( ! empty( $new_instance['label_price_from'] ) ) ? strip_tags( $new_instance['label_price_from'] ) : '';
                        $instance['label_price_to']         = ( ! empty( $new_instance['label_price_to'] ) ) ? strip_tags( $new_instance['label_price_to'] ) : '';
                        $instance['label_search_button']    = ( ! empty( $new_instance['label_search_button'] ) ) ? strip_tags( $new_instance['label_search_button'] ) : '';
                        $instance['show_keyword']           = ( ! empty( $new_instance['show_keyword'] ) ) ? strip_tags( $new_instance['show_keyword'] ) : '';
                        $instance['show_departure_date']    = ( ! empty( $new_instance['show_departure_date'] ) ) ? strip_tags( $new_instance['show_departure_date'] ) : '';
                        $instance['show_prices_filters']    = ( ! empty( $new_instance['show_prices_filters'] ) ) ? strip_tags( $new_instance['show_prices_filters'] ) : '';
                        $instance['show_categories']        = ( ! empty( $new_instance['show_categories'] ) ) ? strip_tags( $new_instance['show_categories'] ) : '';

			return $instance;
		}
	}	
}

if ( ! class_exists( 'BT_Tours_Widget' ) ) {
	// Tours
	class BT_Tours_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_tours_widget', // Base ID
				__( 'BT Tours', 'bt_plugin' ), // Name
				array( 
					'description' => __( 'Show a number of Tours in a sidebar or widget area filtered by Destination, define it\'s design and sorting options.', 'bt_plugin' )
				) 
			);
		}

		public function widget( $args, $instance ) {
                        $class = array( 'btNewToursWidget' );                    
                        echo $args['before_widget'];
                            if (  $instance['title'] != '' ) {
                                    echo $args['before_title'] . apply_filters( 'widget_title', $instance['title']  ). $args['after_title'];
                            }
                            $instance['number']     = !empty( $instance['number'] ) ? $instance['number'] : 5;
                            $instance['view_type']  = !empty( $instance['view_type'] ) ? $instance['view_type'] : 'btListDesignList';
                            
                            echo '<div class="' .  implode( ' ', $class ) . '">';                            
                                    require( 'bt_tours_widget.php' );
                            echo  '</div>';
                        echo $args['after_widget'];
		}

		public function form( $instance ) {
			$title		= ! empty( $instance['title'] ) ? $instance['title'] : '';
			$slug           = ! empty( $instance['slug'] ) ? $instance['slug'] : '';
                        $number         = ! empty( $instance['number'] ) ? $instance['number'] : '';
                        $view_type      = ! empty( $instance['view_type'] ) ? $instance['view_type'] : '';
                        $orderby        = ! empty( $instance['orderby'] ) ? $instance['orderby'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'slug' ) ); ?>"><?php _e( 'Destination Slug (or slugs separated by ";")', 'slug' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'slug' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slug' ) ); ?>" type="text" value="<?php echo esc_attr( $slug ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Max Number of Tours To Show:', 'slug' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'view_type' ) ); ?>"><?php _e( 'Single Tour Design:', 'bt_plugin' ); ?></label> 
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_type' ) ); ?>">
					<?php
					$tours_list_type_arr = array(
						esc_html__( 'List', 'bt_plugin' ) => "btListDesignList", 
						esc_html__( 'Regular', 'bt_plugin' ) => "btListDesignRegular", 
						esc_html__( 'Gallery', 'bt_plugin' ) => "btListDesignGallery",
						esc_html__( 'Tiles', 'bt_plugin' ) => 'btListDesignTiles'
					);
					foreach( $tours_list_type_arr as $key => $value ) {
							if ( $value == $view_type ) {
									echo '<option value="' . $value . '" selected>' . $key . '</option>';
							} else {
									echo '<option value="' . $value . '">' . $key . '</option>';
							}
					}
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php _e( 'Order By:', 'bt_plugin' ); ?></label> 
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
					<option value="0" <?php if ( $orderby == 0 ) { echo 'selected';}?>><?php echo esc_html__( 'Sort by date ascending', 'bt_plugin' ); ?></option>
					<option value="1" <?php if ( $orderby == 1 ) { echo 'selected';}?>><?php echo esc_html__( 'Sort by date descending', 'bt_plugin' ); ?></option>
					<option value="2" <?php if ( $orderby == 2 ) { echo 'selected';}?>><?php echo esc_html__( 'Sort by name - A to Z', 'bt_plugin' ); ?></option>
					<option value="3" <?php if ( $orderby == 3 ) { echo 'selected';}?>><?php echo esc_html__( 'Sort by name - Z to A', 'bt_plugin' ); ?></option>
					<option value="4" <?php if ( $orderby == 4 ) { echo 'selected';}?>><?php echo esc_html__( 'Sort by price - low to high', 'bt_plugin' ); ?></option>
					<option value="5" <?php if ( $orderby == 5 ) { echo 'selected';}?>><?php echo esc_html__( 'Sort by price - high to low', 'bt_plugin' ); ?></option>
				 </select>
			</p>
			<p class="bt-admin-description bt-background-description bt-link-underline-bold">
				<a href="http://documentation.bold-themes.com/travelicious/widgets/#bt-tours" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
				<?php _e( 'For more help with this widget, please', 'bt_plugin' ); ?> <a href="http://documentation.bold-themes.com/travelicious/widgets/#bt-tours" target="_blank"><?php _e( 'click here', 'bt_plugin' ); ?></a>.
			</p>
			<?php 
		}
                
                 
                
                

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title']      = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['slug']       = ( ! empty( $new_instance['slug'] ) ) ? strip_tags( $new_instance['slug'] ) : '';
                        $instance['number']     = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
                        $instance['view_type']  = ( ! empty( $new_instance['view_type'] ) ) ? strip_tags( $new_instance['view_type'] ) : '';
                        $instance['orderby']    = ( ! empty( $new_instance['orderby'] ) ) ? strip_tags( $new_instance['orderby'] ) : '';

			return $instance;
		}
	}	
}
