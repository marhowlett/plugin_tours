<?php
// [bt_masonry_image_grid] - gallery images + featured videos in gallery
function bt_masonry_image_grid_func( $atts ) {
	extract( shortcode_atts( array(
            'images'         => '',
            'columns'       => '',
            'format'        => '',
            'gap'           => '',
            'no_lightbox'   => '',
            'images_size'   => 'boldthemes_large_square',
            'post_id'       => get_the_ID(),
            'el_id'         => 'tour_masonry_image_grid'
	), $atts, 'bt_masonry_image_grid_func' ) );
        
        $shortcode  = 'bt_masonry_image_grid';
        $prefix     = 'bt_bb_';
        $el_style   = '';
        $class[]    = $shortcode; 
        $class[]    = 'bt_grid_container';
        
        $class[]    = 'bt_bb_masonry_image_grid';
        $class[]    = 'bt_bb_grid_container';
        
        wp_enqueue_script( 'jquery-masonry' );

        wp_enqueue_script( 
                'bt_masonry_image_grid',
                plugin_dir_url( __DIR__ ) . 'assets/js/bt_masonry_image_grid.js',
                array( 'jquery' )
        );
                
        $id_attr = '';
        if ( $el_id != '' ) {
                $id_attr = ' ' . 'id="' . $el_id . '"';
        }
        
        $style_attr = '';
        if ( $el_style != '' ) {
                $style_attr = ' ' . 'style="' . $el_style . '"';
        }

        if ( $columns != '' ) {
                $class[] = $prefix . 'columns' . '_' . $columns;
        }

        if ( $gap != '' ) {
                $class[] = $prefix . 'gap' . '_' . $gap;
        }

        if ( $no_lightbox == 'no_lightbox' ) {
                $class[] = $prefix . 'no_lightbox';
        }

        $class = apply_filters( $shortcode . '_class', $class, $atts );
        
        $output = '';
        
        $output .= '<div class="bt_bb_grid_sizer"></div>';

        $images_arr = explode( ',', $images );
        $format_arr = explode( ',', $format );
        
        BoldThemesFrameworkTemplate::$tour_default_image        = boldthemes_get_option( 'tour_default_image' )  != '' 
                    ? boldthemes_get_option( 'tour_default_image' ) : BoldThemes_Customize_Default::$data['tour_default_image'];            
            
        $default_image_id       = bt_get_image_id(BoldThemesFrameworkTemplate::$tour_default_image);            
        $img_default            = wp_get_attachment_image_src( $default_image_id, $images_size );
        $img_src_default        = isset($img_default[0]) ? $img_default[0] : '';            
        $img_full_default       = wp_get_attachment_image_src( $default_image_id, 'full' );
        $img_src_full_default   = isset($img_full[0]) ? $img_full[0] : '';

        $n = 0;        
        $videos  = BoldThemesFrameworkTemplate::$tour_rwmb_featured_video;
        if ( is_array($videos) && !empty($videos) ){
            foreach( $videos as $video ) {
                $video_src = $video;
                $youtube_video_id = explode('?v=', $video);
                $youtube_video_thumb_src = isset($youtube_video_id[1]) ? 'http://img.youtube.com/vi/' . $youtube_video_id[1] . '/hqdefault.jpg' : $img_src_default;

                if ( isset( $format_arr[ $n ] ) ) {
                            $tile_format = 'bt_tile_format';
                            if ( $format_arr[ $n ] == '21' ) {
                                    $tile_format .= "_" . $format_arr[ $n ];
                            } else {
                                    $tile_format .= '11';
                            }
                }
                $data_hw = '1';
                $data_title = '';
                
                $youtube_video_src = $video;
               

                $output .= '<div class="bt_bb_grid_item ' . $tile_format . ' video-link" data-type="video-link" data-hw="' . $data_hw . '" data-src="' . $youtube_video_thumb_src . '" data-src-full="' . $youtube_video_src . '" data-title="' . $data_title . '">'
                            . '<div class="bt_bb_grid_item_inner" data-hw="' . $data_hw . '" >'
                                . '<div class="bt_bb_grid_item_inner_image"></div>'
                                . '<div class="bt_bb_grid_item_inner_content"></div>'
                            . '</div>'
                        . '</div>';
            }
        }
        if ( is_array($images_arr) && !empty($images_arr) ){
            foreach( $images_arr as $id ) {
                    $img = wp_get_attachment_image_src( $id, $images_size );
                    $img_src =  isset($img[0]) ? $img[0] : $img_src_default;   
                    $img_full = wp_get_attachment_image_src( $id, 'full' );
                    $img_src_full = isset($img_full[0]) ? $img_full[0] : $img_src_full_default;

                    $image_post = get_post( $id );
                    if ( isset( $format_arr[ $n ] ) ) {
                            $tile_format = 'bt_tile_format';
                            if ( $format_arr[ $n ] == '21' ) {
                                    $tile_format .= "_" . $format_arr[ $n ];
                            } else {
                                    $tile_format .= '11';
                            }
                    }
                    $data_hw = '';
                    if ( $img[1] > 0 ) {
                            $data_hw = $img[2] / $img[1];
                    }
                    $data_title = '';
                    if ( is_object( $image_post ) ) {
                            $data_title = $image_post->post_title;
                    }

                    $output .= '<div class="bt_bb_grid_item ' . $tile_format . '" data-hw="' . $data_hw . '" data-src="' . $img_src . '" data-src-full="' . $img_src_full . '" data-title="' . $data_title . '"><div class="bt_bb_grid_item_inner" data-hw="' . $data_hw . '" ><div class="bt_bb_grid_item_inner_image"></div><div class="bt_bb_grid_item_inner_content"></div></div></div>';
                    $n++;
            }
        }
        
        

        $output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . ' data-columns="' . $columns . '"><div class="bt_bb_masonry_post_image_content" data-columns="' . $columns . '">' . $output . '</div></div>';
	$output = apply_filters( 'bt_bb_general_output', $output, $atts );
        $output = apply_filters( $shortcode . '_output', $output, $atts );

        return $output;
        
        
}


add_shortcode( 'bt_masonry_image_grid', 'bt_masonry_image_grid_func' );