<?php
// [bt_header_video]
function bt_header_video_func( $atts ) {
	extract( shortcode_atts( array(
            'video'             => '',
            'disable_controls'  => 'true',
            'width'             => absint('1920'),
            'height'            => absint('770'),
            'post_id'           => get_the_ID(),
            'el_id'             => ''
	), $atts, 'bt_header_video_func' ) );
        
        $shortcode  = 'bt_header_video';
        $prefix     = 'bt_bb_';
        $el_style   = '';
        $el_class   = 'bt_bb_video';
        
        $tour               = get_post( $post_id );
        $tour_categories    = get_the_terms( $post_id, 'tour_category' );
       
        //header
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
        
        if ( $disable_controls != '' ) {
                $class[] = $prefix . 'disable_controls' . '_' . $disable_controls;
        }

        $class = apply_filters( $shortcode . '_class', $class, $atts );	

        $output = '[video src = "' . $video . '" width = "'.$width.'" height = "'.$height.'"]';

        $output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $heading_html . do_shortcode( $output ) . '</div>';
        
        $output = apply_filters( 'bt_bb_general_output', $output, $atts );
        $output = apply_filters( $shortcode . '_output', $output, $atts );

        return $output;
        
}

add_shortcode( 'bt_header_video', 'bt_header_video_func' );

