<?php
// [bt_header_slider]
function bt_header_slider_func( $atts ) {
	extract( shortcode_atts( array(
            'images'    	=> '',
            'videos'    	=> '',
            'height'    	=> 'keep-height',
            'show_arrows'     	=> 'show',
            'show_dots'     	=> 'show',
            'animation' 	=> 'fade',
            'slides_to_show' 	=> '1',
            'auto_play' 	=> '3000',
            'images_size'       => 'boldthemes_tour_header_slider_rectangle',
            'video_width'       => absint('1920'),
            'video_height'      => absint('770'),
            'post_id'           => get_the_ID(),
            'el_id'             => 'tour_carousel_header'
	), $atts, 'bt_header_slider_func' ) );
        
        $shortcode  = 'bt_header_slider';
        $prefix     = 'bt_bb_';
        $el_style   = '';
        $el_class   = 'bt_bb_slider';        
        
        $tour               = get_post( $post_id );
        $tour_categories    = get_the_terms( $post_id, 'tour_category' );
        
        $title  = !empty($tour) ? $tour->post_title  : '';         
        $supertitle = '';
        if ( !empty($tour_categories) ){ 
                foreach ( $tour_categories as $tour_category) { 
                    $category_link = get_term_link($tour_category);
                    $supertitle .= '<a href="' . esc_url( $category_link ) . '">' . $tour_category->name . '</a> '; 
                } 
        }       
        $dash = '';
        $subtitle = '';
        $heading_html =  boldthemes_get_heading_html( 
                array(
                        'superheadline' => $supertitle,
                        'headline' => $title,
                        'subheadline' => $subtitle,
                        'html_tag' => "h1",
                        'size' => apply_filters( 'boldthemes_header_headline_size', 'extra_large' ),
                        'dash' => $dash,
                        'el_style' => '',
                        'el_class' => ''
                )
        );
        
        $slider_class = array( 'slick-slider' );
        $class = array( $shortcode );

        if ( $el_class != '' ) {
                $class[] = $el_class;
        }

        $id_attr = '';
        if ( $el_id != '' ) {
                $id_attr = ' ' . 'id="' . $el_id . '"';
        }

        $style_attr = '';
        if ( $el_style != '' ) {
                $style_attr = ' ' . 'style="' . $el_style . '"';
        }

        if ( $height != '' ) {
                $class[] = $prefix . 'height' . '_' . $height;
        }	

        $data_slick = ' ' . 'data-slick=\'{ "lazyLoad": "progressive", "cssEase": "ease-out", "speed": "500"';
		
        if ( $animation == 'fade' ) {
                $data_slick .= ', "fade": true';
                $slider_class[] = 'fade';
                $slides_to_show = 1;
        }

        if ( $show_dots != 'hide' ) {
                $data_slick .= ', "dots": true' ;
                $class[] = $prefix . 'show_dots' . '_' . $show_dots;
        }

        if ( $show_arrows != 'hide' ) {
                $data_slick  .= ', "prevArrow": "&lt;button type=\"button\" class=\"slick-prev\"&gt;", "nextArrow": "&lt;button type=\"button\" class=\"slick-next\"&gt;"';
        } else {
                $data_slick  .= ', "prevArrow": "", "nextArrow": ""';
        }

        if ( $slides_to_show > 1 ) {
                $data_slick .= ',"slidesToShow": ' . intval( $slides_to_show );
                $class[] = $prefix . 'multiple_slides';
        }
		
        if ( $auto_play != '' ) {
                $data_slick .= ',"autoplay": true, "autoplaySpeed": ' . intval( $auto_play );
        }

        if ( is_rtl() ) {
                $data_slick .= ', "rtl": true' ;
        }

        if ( $slides_to_show > 1 ) {
                $data_slick .= ', "responsive": [';
                if ( $slides_to_show > 1 ) {
                        $data_slick .= '{ "breakpoint": 480, "settings": { "slidesToShow": 1, "slidesToScroll": 1 } }';	
                }
                if ( $slides_to_show > 2 ) {
                        $data_slick .= ',{ "breakpoint": 768, "settings": { "slidesToShow": 2, "slidesToScroll": 2 } }';	
                }
                if ( $slides_to_show > 3 ) {
                        $data_slick .= ',{ "breakpoint": 920, "settings": { "slidesToShow": 3, "slidesToScroll": 3 } }';	
                }
                if ( $slides_to_show > 4 ) {
                        $data_slick .= ',{ "breakpoint": 1024, "settings": { "slidesToShow": 3, "slidesToScroll": 3 } }';	
                }				
                $data_slick .= ']';
        }
		
        $data_slick = $data_slick . '}\' ';

        $class = apply_filters( $shortcode . '_class', $class, $atts );

        $output = '';
  
        if ( $images != '' ) {
                $image_array = explode( ',', $images );
                foreach( $image_array as $image ) {
                        $caption = "";
                        if ( is_numeric($image) ) {
                                $post_image = get_post( $image );
                                $caption = get_post( $image )->post_excerpt;
                                $image = wp_get_attachment_image_src( $image, $images_size );
                                $image = $image[0];
                                
                                $output .= '<div class="bt_bb_slider_item" style="background-image:url(\'' . $image . '\')">';
                                $output .= '<div class="bt_bb_separator bt_bb_border_style_none"></div>';
                                $output .= '</div>';
                        }
                }
        }

        $output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $heading_html . '<div class="' . implode( ' ', $slider_class ) . '" ' . $data_slick . '>' . $output . '</div></div>';

        $output = apply_filters( 'bt_bb_general_output', $output, $atts );
        $output = apply_filters( $shortcode . '_output', $output, $atts );

        return $output;
        
}

add_shortcode( 'bt_header_slider', 'bt_header_slider_func' );

