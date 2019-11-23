<?php
/**** METABOXES CONTROLS SETTINGS ****/
if(!function_exists('bt_metabox_render')){
    function bt_metabox_render($post, $metabox) {
        global $post;    
        $options = get_post_meta($post->ID);        
        ?>
        <input type="hidden" name="bt_meta_box_nonce" value="<?php echo wp_create_nonce(basename(__FILE__));?>" />
        <table class="form-table bt-metaboxes">
            <tbody>					
                    <?php
                    foreach ($metabox['args'] as $settings) {
                        $settings['value'] = isset($options[$settings['id']]) ? $options[$settings['id']] : (isset($settings['std']) ? $settings['std'] : '');
                        call_user_func('bt_settings_'.$settings['type'], $settings);	
                    }
                    ?>
            </tbody>
        </table>
        <?php 
    }
}

/*** METABOX STATIC CONTROL ***/
if (!function_exists('bt_settings_static')) {
    function bt_settings_static($settings){ ?>
        <tr id="lp_field_<?php echo wp_kses_post($settings['id']); ?>">
            <th>
                <label for="<?php echo wp_kses_post($settings['id']); ?>">
                    <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                </label>
            </th>
            <td>
                <?php 
                    if( is_array($settings['value']) && count($settings['value']) > 0 ){
                            $value = $settings['value'][0];
                    }
                    else{
                            $value = $settings['value'];
                    }
                ?>
                <input type="text" name="<?php echo wp_kses_post($settings['id']); ?>" id="<?php echo wp_kses_post($settings['id']); ?>" value="<?php echo wp_kses_post($value); ?>" size="138" />
				<p class="description bt-admin-description"><?php echo wp_kses_post($settings['desc']); ?></p>
            </td>
        </tr><?php
    }
}

/*** METABOX TEXTAREA CONTROL ***/
if (!function_exists('bt_settings_textarea')) {
    function bt_settings_textarea($settings){ 
        $rows = isset($settings['rows']) ? $settings['rows'] : 3;
        $cols = isset($settings['cols']) ? $settings['cols'] : 140;
        ?>
        <tr id="<?php echo wp_kses_post($settings['id']); ?>">
            <th>
                <label for="<?php echo wp_kses_post($settings['id']); ?>">
                    <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                    <p class="description bt-admin-description"><?php echo wp_kses_post($settings['desc']); ?></p>
                </label>
            </th>
            <td>
                <?php 
                    if( is_array($settings['value']) && count($settings['value']) > 0 ){
                            $value = $settings['value'][0];
                    }
                    else{
                            $value = $settings['value'];
                    }
                ?>
                <textarea rows="<?php echo esc_attr($rows); ?>" cols="<?php echo esc_attr($cols); ?>" name="<?php echo esc_attr($settings['id']); ?>"><?php echo $value; ?></textarea>
            </td>
        </tr><?php
    }
}

/*** METABOX TEXT CONTROL ***/
if (!function_exists('bt_settings_text')) {
    function bt_settings_text($settings){ ?>
        <tr id="lp_field_<?php echo wp_kses_post($settings['id']); ?>">
            <th>
                <label for="<?php echo wp_kses_post($settings['id']); ?>">
                    <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                    <span><?php echo wp_kses_post($settings['desc']); ?></span>
                </label>
            </th>
            <td>
                <input type="text" name="<?php echo wp_kses_post($settings['id']); ?>" id="<?php echo wp_kses_post($settings['id']); ?>" value="<?php echo wp_kses_post($settings['value']); ?>" />
            </td>
        </tr><?php
    }
}

/*** METABOX SELECT CONTROL ***/
if (!function_exists('bt_settings_select')) {
    function bt_settings_select($settings){ 
        if (!empty($settings['options'])){
            $current_value = ( is_array($settings['value']) && count($settings['value']) > 0 ) ? $settings['value'][0] : '' ;
            ?>        
                <tr id="<?php echo wp_kses_post($settings['id']); ?>">
                    <th>
                        <label for="<?php echo wp_kses_post($settings['id']); ?>">
                            <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                            <span><?php echo wp_kses_post($settings['desc']); ?></span>
                        </label>
                    </th>
                    <td>
                        <div class="type_select add_item_medium">
                            <select class="medium" name="<?php echo wp_kses_post($settings['id']); ?>" data-value="<?php echo $current_value;?>">
                                <?php
                                    foreach($settings['options'] as $key=>$value) {                                
                                        if ($key == $current_value) {
                                                $selected =  "selected";
                                        }else{
                                                $selected = '';
                                        }  
                                        echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
            <?php
        }
    }
}


/*** METABOX LISTING CONTROL ***/
if (!function_exists('bt_settings_listing')) {
    function bt_settings_listing($settings){
        $current_value = ( !empty($settings['value']) && is_array($settings['value']) && count($settings['value']) > 0 ) ? $settings['value'][0] : '' ;
	?>
        <tr id="<?php echo wp_kses_post($settings['id']); ?>">
            <th>
                <label for="<?php echo wp_kses_post($settings['id']); ?>">
                    <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                    <span><?php echo wp_kses_post($settings['desc']); ?></span>
                </label>
            </th>
            <td>
                <div class="type_listing add_item_medium">
                    <select class="medium" name="<?php echo wp_kses_post($settings['id']); ?>" data-value="<?php echo $current_value;?>">
                            <?php
                                if(!empty($settings['options'])){
                                        foreach($settings['options'] as $key=>$value) { 
                                            if ($key == $current_value) {
                                                    $selected =  "selected";
                                            }else{
                                                    $selected = '';
                                            }  
                                            echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                                        }
                                }
                                else{
                                        if(!empty($settings['value'])){
                                            $selected =  "selected";
                                            echo '<option '.$selected.' value="'.$current_value.'">'.get_the_title($current_value).'</option>';
                                        }
                                }
                            ?>
                    </select>  
                </div>
            </td>
        </tr><?php
    }
}

/*** METABOX MAP CONTROL ***/
if (!function_exists('bt_settings_map')) {
    function bt_settings_map($settings){
        global $post; 
         
        $std = explode(',',$settings['std']);
        $lat = get_post_meta($post->ID,'lat',true);
        $lng = get_post_meta($post->ID,'lng',true);
        
        $lat    = $lat == '' ? $std[0] : $lat;
        $lng    = $lng == '' ? $std[1] : $lng;
        $zoom   = $std[2];
        
        $helper = array(
            'lat' => $lat,
            'lng' => $lng,
            'zoom'=> $zoom
        );
        
        if ( $settings["api_key"] != '' ){
            wp_enqueue_script('google-map-api','https://maps.googleapis.com/maps/api/js?v=3.exp&key='.wp_kses_post($settings["api_key"]).'');
        }else{
           wp_enqueue_script('google-map-api','https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
        }
        
        wp_enqueue_style('gmaps-meta-box-css', plugins_url() . '/travelicious/assets/css/map-meta-box.css');
        wp_register_script('bt-gmaps-meta-box-js', plugins_url() . '/travelicious/assets/js/map-meta-box.js');
        wp_localize_script('bt-gmaps-meta-box-js','helper',$helper);
        wp_enqueue_script( 'bt-gmaps-meta-box-js' );

        ?>
        <tr id="<?php echo wp_kses_post($settings['id']); ?>">
            <th>
                <label for="<?php echo wp_kses_post($settings['id']); ?>">
                    <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                    <span><?php echo wp_kses_post($settings['desc']); ?></span>
                </label>
            </th>
            <td>
                <div class="type_listing add_item_medium">
                        <div class="maparea" id="map-canvas"></div>
                        <input type="hidden" name="glat" id="latitude" value="<?php echo $lat; ?>">
                        <input type="hidden" name="glng" id="longitude" value="<?php echo $lng; ?>">
                </div>
            </td>
        </tr>    
        <?php
    }
}

/*** METABOX IMAGE ADVANCED CONTROL ***/
if (!function_exists('bt_settings_image_advanced')) {
    function bt_settings_image_advanced($settings){
        global $post; 
        
        ?>
        <tr id="<?php echo wp_kses_post($settings['id']); ?>">
            <th>
                <label for="<?php echo wp_kses_post($settings['id']); ?>">
                    <strong><?php echo wp_kses_post($settings['name']); ?></strong>
                    <span><?php echo wp_kses_post($settings['desc']); ?></span>
                </label>
            </th>
            <td>
               image advanced
            </td>
        </tr>    
        <?php
        
    }
}

//add_action( 'admin_enqueue_scripts', 'bt_custom_js_css' );
if (!function_exists('bt_custom_js_css')) {
    function bt_custom_js_css() {
            global $post;
            
            wp_enqueue_script('google-map-api','https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
            wp_enqueue_style('gmaps-meta-box-css', plugin_dir_url( __FILE__ ) . 'assets/css/map-meta-box.css');
            
            wp_register_script('gmaps-meta-box-js', plugin_dir_url( __FILE__ ) . 'assets/js/map-meta-box.js');
            $helper = array(
                'lat' => get_post_meta($post->ID,'lat',true),
                'lng' => get_post_meta($post->ID,'lng',true)
            );
            $helper = array(
                'lat' => 39,
                'lng' => 22
            );
            
            wp_localize_script('gmaps-meta-box-js','helper',$helper);
            wp_enqueue_script( 'gmaps-meta-box-js' );
    }
}
/**** /METABOXES CONTROLS SETTINGS ****/


/**
 * Returns array of ID and post titles from specific custom post type, returns null if found no results.
 *
 * @param string $custom_post_type Custom post type
 */
if (!function_exists('bt_output_post_type_list')) {
    function bt_output_post_type_list($custom_post_type) {
        global $wpdb; 
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );
        if ( ! $results )
            return;

        if ( !empty($results) ){
            $retArray = array();
            foreach($results as $result) { 
                $retArray[$result["ID"]] = $result["post_title"];
            }
            return $retArray;
        }
        return;
    }
}

/**
 * Add new metabox value for specific post metabox
 *
 * @param string $name Metabox name
 * @param string $val  Metabox value
 * @param int $post_id Specific post ID
 */
if (!function_exists('bt_set_metabox')) {
    function bt_set_metabox($name, $val, $post_id) {
        if ($post_id) {
            $metabox = get_post_meta($post_id);
            if (isset($metabox[$name])) {               
                $val = wp_filter_nohtml_kses( $val );
                $retVal = update_post_meta( $post_id, $name, $val );
                return $retVal;
            }
        }
        return false;               
    }
}


/**
 * Update post properties
 *
 * @param array $update_data Array of updates values
 * @param array $where  Array of where conditions
 */
if (!function_exists('bt_metabox_update_post')) {
    function bt_metabox_update_post($update_data = array(), $where = array() ) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $update_format = array('%s');         
        $wpdb->update($prefix . 'posts', $update_data, $where, $update_format);
    }
}


/**
 * Save tour metaboxes in WP admin
 *
 * @param int $post_id Post ID 
 */
add_action('save_post_tour', 'bt_save_tour_meta');
if (!function_exists('bt_save_tour_meta')) {
    function bt_save_tour_meta($post_id){
        $post_type = get_post_type($post_id);
        if ( $post_type != "tour" ){
                return;
        }
        if ( !isset( $_POST['bt_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['bt_meta_box_nonce'], basename( __FILE__ ) ) ){
                return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ){
                return;
        }
        
        $fields = array(
            'tour_review_summary',
            'tour_review',
            'tour_user_review',
            'tour_supertitle',
            'tour_title',
            'tour_subtitle',
            'tour_regular_price',
            'tour_original_price',
            'tour_discount_title',
            'tour_travellers',
            'tour_time',
            'tour_duration',
            'tour_departure_location',
            'tour_return_location',
            'tour_map_embed',
            'tour_plan_title',
            'tour_plan_headline',
            'tour_plan_description',
        );
        
       
        
        foreach( $fields as $field){
            if (!empty($_POST[$field]) && isset($_POST[$field])) {
                $tour_field  = $_POST[$field];
                if ( is_array($tour_field) ){
                    $tour_field = array_values($tour_field);
                }
                update_post_meta( $post_id, $field, $tour_field );
            }else{
                //update_post_meta( $post_id, $field, '' );
                delete_post_meta( $post_id, $field);
            }
        }
        //die; 
        $oldlat = get_post_meta($post_id, "lat", true);
        $newlat = isset( $_POST['glat'] ) ? $_POST["glat"] : $oldlat; 
        if ($newlat != $oldlat) {
            update_post_meta($post_id, "lat", $newlat);
        }
        
        $oldlng = get_post_meta($post_id, "lng", true);
        $newlng = isset( $_POST['glng'] ) ? $_POST["glng"] : $oldlng; 
        if ($newlng != $oldlng) {
            update_post_meta($post_id, "lng", $newlng);
        }   
        
        return 1;
    }
}


/**
 * Custom MetaBox Input used for custom Tour Plan meta values
 */
if ( ! class_exists( 'RWMB_BoldThemesTourPlan_Field' ) && class_exists( 'RWMB_Field' ) ) {
	class RWMB_BoldThemesTourPlan_Field extends RWMB_Field {
	
		static function admin_enqueue_scripts() {
			wp_enqueue_editor();
			wp_enqueue_script( 
				'boldthemes_tourplan',
                                plugins_url() . '/travelicious/assets/js/boldthemes_plan.js',
				array( 'jquery' ),
				'',
				true
			);
		}

		static function html( $meta, $field ) { 
                        $meta_titles         = isset( $_GET['post'] ) ? get_post_meta( $_GET['post'], 'tour_plan_title', true ) : '';
                        $meta_headlines      = isset( $_GET['post'] ) ? get_post_meta( $_GET['post'], 'tour_plan_headline', true ) : '';
                        $meta_descriptions   = isset( $_GET['post'] ) ? get_post_meta( $_GET['post'], 'tour_plan_description', true ) : '';
                        
                        $title = '';
                        $headline = '';
                        $description = '';                                              
                        $limit = is_array($meta_titles) && count($meta_titles) > 0 ? count($meta_titles) : 1;
                        
                        $output = '<div id="sortable">';   
                            for ( $i = 0; $i <= $limit; $i++ ) {                            
                                $title          = isset($meta_titles[$i]) ? $meta_titles[$i] : '';
                                $headline       = isset($meta_headlines[$i]) ? $meta_headlines[$i] : '';
                                $description    = isset($meta_descriptions[$i]) ? $meta_descriptions[$i] : '';                              
                                if ( $title != '' || $i == 0 ) { 

                                        $output  .= '<div class="rwmb-clone boldthemes_tourplan_wrapper ui-state-default" data-number="' . $limit . '">';
                                            $output  .= '<span>' . __('Title:', 'bt_plugin') . '</span><input type="text" style="width:100%;" class="boldthemes_key" name="tour_plan_title[' . $i . ']" id="tour_plan_title_' . $i . '" value="' . esc_attr( $title ) . '" placeholder="' . esc_attr( 'Title', 'bt_plugin' ) . '">';			
                                            $output  .= '<span>' . __('Headline:', 'bt_plugin') . '</span><input type="text" style="width:100%;" class="boldthemes_key" name="tour_plan_headline[' . $i . ']" id="tour_plan_headline_' . $i . '" value="' . esc_attr( $headline ) . '" placeholder="' . esc_attr( 'Headline', 'bt_plugin' ) . '">';
                                            $output  .= '<span>' . __('Description:', 'bt_plugin') . '</span><textarea rows="8" cols="40" name="tour_plan_description[' . $i . ']"  id="tour_plan_description_' . $i . '" class="boldthemes_key cn-wp-editor">' . esc_attr( $description ) . '</textarea>';
                                            $output  .= '<a href="#" class="rwmb-button remove-clone"><i class="dashicons dashicons-minus"></i></a>';
                                         

                                            ?>
                                                <script>
                                                    $( document ).ready(function() {
                                                        var editor_id = 'tour_plan_description_<?php echo $i;?>'; 
                                                        wp.editor.initialize(  editor_id, {
                                                                tinymce: true
                                                        });
                                                        return;
                                                    });
                                                </script>
                                            <?php
                                     $output  .= '</div>'; 
                                }
                            }
                        $output  .= '</div>';
                        
                        $output  .= '<a href="#" class="rwmb-button button-primary add-clone-tour">+ Add more</a>';
                        return $output;
		}
		
		static function normalize_field( $field ) {                     
			$field = wp_parse_args( $field, array(
				'size'        => 30,
				'datalist'    => false,
				'placeholder' => '',
			) );
                        return $field;
                }

		static function datalist_html( $field ) {
			return '';
		}
	}
}















