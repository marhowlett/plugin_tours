<?php

/**
 * Custom comments HTML output for tour single
 */
if ( ! function_exists( 'boldthemes_theme_comment_tour' ) ) {
	function boldthemes_theme_comment_tour( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				?>
				<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
					<p><?php esc_html_e( 'Pingback:', 'bt_plugin' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'bt_plugin' ), '<span class="edit-link">', '</span>' ); ?></p>
				<?php
				break;
			default :
				global $post;
				$tour_show_rating	= boldthemes_get_option( 'tour_show_rating' );
				$rating			= $tour_show_rating ? intval( get_comment_meta( $comment->comment_ID, 'rating', true ) ) : 0;
				$comment_title		= get_comment_meta( $comment->comment_ID, 'title', $single = true );
				$comment_comment	= $comment->comment_content;
				?>
                                   
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
					<article id="comment-<?php comment_ID(); ?>" class = "">
						<?php $avatar_html = get_avatar( $comment, 140 ); 
							if ( $avatar_html != '' ) {
								echo '<div class="commentAvatar">' . wp_kses_post( $avatar_html ) . '</div>';
							}
						?>
						<div class="commentTxt">
							<div class="vcard divider">
								<?php
									printf( '<h5 class="author"><span class="fn">%1$s</span></h5>', get_comment_author_link($comment->comment_ID) );
									if ( $rating > 0 ) { ?>
										<div itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( esc_html__( 'Rated %d out of 5', 'bt_plugin' ), $rating ) ?>">
											<span style="width:<?php echo wp_kses_post( ( $rating / 5 ) * 100 ); ?>%"><strong><?php echo wp_kses_post( $rating ); ?></strong> 
											<?php esc_html_e( 'out of 5', 'bt_plugin' ); ?></span>
										</div>
									<?php }
								?>
							</div>

							<?php if ( '0' == $comment->comment_approved ) : ?>
								<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'bt_plugin' ); ?></p>
							<?php endif; ?>

							<div class="comment">
								<?php									
									if ( $comment_comment != '' ){
										echo '<p>' . wp_kses_post( $comment_comment ) . '</p>';
									}                                                                        
                                                                        echo '<div class="commentMeta">';
                                                                            if ( comments_open() ) {
                                                                                if ( $tour_show_rating ) {
                                                                                    echo '<div class="commentRatings">';
                                                                                        $tour_user_review_arr = bt_tour_get_user_reviews( $comment->comment_post_ID );
                                                                                        foreach( $tour_user_review_arr as $review ) {
                                                                                            if ( $review != '' ){
                                                                                                $review_sanitize = sanitize_title_with_dashes($review);
                                                                                                $rating = $review_sanitize != '' ? get_comment_meta( $comment->comment_ID, 'rating_'. $review_sanitize, true ) : 0;
                                                                                                if ( intval($rating) > 0 ){
                                                                                                    $score_rating   = intval($rating) > 0 ? intval($rating) * 20 : 0;
                                                                                                    $score_stars    = intval($rating) > 0 ? intval($rating) : 0;
                                                                                                    echo '<div class="commentRatingOption">';
                                                                                                            echo '<span>' . $review . '</span>';
                                                                                                            echo '<div class="star-rating" title="Rated '.$score_stars.' out of 5">&nbsp;';
                                                                                                                    echo '<span style="width:'.$score_rating.'%"><strong>'.$score_stars.'</strong> out of 5</span>';
                                                                                                            echo '</div>';
                                                                                                    echo '</div>';
                                                                                                }

                                                                                            }
                                                                                        }                                                                              
                                                                                    echo '</div>';
                                                                                }
                                                                                echo '<div class="commentOptions">';
                                                                                        echo '<p class="posted"><span>' . sprintf( esc_html__( '%1$s', 'bt_plugin' ), get_comment_date() ) . '</span></p>';
                                                                                        echo '<p class="reply">';
                                                                                                comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'bt_plugin' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
                                                                                        echo '</p>';
                                                                                        edit_comment_link( esc_html__( 'Edit', 'bt_plugin' ), '<p class="edit-link">', '</p>' );
                                                                                echo '</div>';
                                                                            }
                                                                        echo '</div>';
								?>
							</div>
						</div>
					</article>
				<?php
			break;
		endswitch;
	}
}



/************ COMMENT FORM ****************/
/* https://www.smashingmagazine.com/2012/05/adding-custom-fields-in-wordpress-comment-form/
/*
/******************************************/

add_action( 'comment_form_logged_in_after', 'bt_comment_form_additional_fields_logged_in_after' );
function bt_comment_form_additional_fields_logged_in_after () {

            $current_post_type = get_post_type();
            if (bt_is_forbiden_current_post_type($current_post_type))
                return;
         
          
          
          echo '<div class="pcItem review-by">'.
                        '<label>'. __('Review Rating *','bt_plugin') . '</label>
                        <span class="commentratingbox">';
                                for( $i=1; $i <= 5; $i++ )
                                echo '<span class="commentrating"><input type="radio" name="rating" id="rating'. $i .'" value="'. $i .'"/><label for="rating'. $i .'">'. $i .'</label></span>';
                         echo'</span>';
           echo'</div>';
           
           $tour_show_rating	= boldthemes_get_option( 'tour_show_rating' );  
           if ( $tour_show_rating ) {
                $tour_user_review_arr = bt_tour_get_user_reviews();
                foreach( $tour_user_review_arr as $review ) {
                    if ( $review != '' ){
                         $review_sanitize = sanitize_title_with_dashes($review);
                         echo '<div class="pcItem review-by">'.
                                 '<label>' . $review . '</label>
                                 <span class="commentratingbox">';
                                         for( $i=1; $i <= 5; $i++ )
                                         echo '<span class="commentrating"><input type="radio" name="rating_' . $review_sanitize . '" id="rating'. $i .'_' . $review_sanitize . '" value="'. $i .'"/><label for="rating'. $i .'_' . $review_sanitize . '">'. $i .'</label></span>';
                                  echo'</span>';
                         echo'</div>';
                    }
                }
           }

           echo '<div class="pcItem btComment"><p><textarea id="comment" name="comment" tabindex="4" cols="30" rows="8" placeholder="' . esc_attr( 'Comment *','bt_plugin' ) . '"
           aria-required="true">' .'</textarea></p></div>';
}

add_action( 'comment_form_before_fields', 'bt_comment_form_additional_fields' );
function bt_comment_form_additional_fields () {
          $current_post_type = get_post_type();
          if (bt_is_forbiden_current_post_type($current_post_type))
                return;

          echo '<div class="pcItem review-by">'.
                        '<label>'. __('Review Rating *','bt_plugin') . '</label>
                        <span class="commentratingbox">';
                                for( $i=1; $i <= 5; $i++ )
                                echo '<span class="commentrating"><input type="radio" name="rating" id="rating'. $i .'" value="'. $i .'"/><label for="rating'. $i .'">'. $i .'</label></span>';
                         echo'</span>';
           echo'</div>';
           
           $tour_show_rating	= boldthemes_get_option( 'tour_show_rating' ); 
           if ( $tour_show_rating ) {
                $tour_user_review_arr = bt_tour_get_user_reviews();
                foreach( $tour_user_review_arr as $review ) {
                    if ( $review != '' ){
                        $review_sanitize = sanitize_title_with_dashes($review);
                         echo '<div class="pcItem review-by">'.
                                 '<label>'. $review . '</label>
                                 <span class="commentratingbox">';
                                         for( $i=1; $i <= 5; $i++ )
                                         echo '<span class="commentrating"><input type="radio" name="rating_' . $review_sanitize . '" id="rating'. $i .'_' . $review_sanitize . '" value="'. $i .'"/><label for="rating'. $i .'_' . $review_sanitize . '">'. $i .'</label></span>';
                                  echo'</span>';
                         echo'</div>';
                    }
               }
          }
}

add_action( 'comment_post', 'bt_save_comment_meta_data' );
function bt_save_comment_meta_data( $comment_id ) {

        $current_post_type = get_post_type( get_comment($comment_id)->comment_post_ID );
        if (bt_is_forbiden_current_post_type($current_post_type))
               return;

        if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') ) {
            $title = wp_filter_nohtml_kses($_POST['title']);
            add_comment_meta( $comment_id, 'title', $title );
        }

        if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') ) {
            $rating = wp_filter_nohtml_kses($_POST['rating']);
            add_comment_meta( $comment_id, 'rating', $rating );
        }
         
        $comment          = get_comment( $comment_id ); 
        $comment_post_id  = $comment->comment_post_ID ;
        $tour_user_review_arr    = bt_tour_get_user_reviews( $comment_post_id );
        foreach( $tour_user_review_arr as $review ) {
            if ( $review != '' ){
                $review_sanitize = sanitize_title_with_dashes($review);
                 if ( ( isset( $_POST['rating_' . $review_sanitize] ) ) && ( $_POST['rating_' . $review_sanitize] != '') ) {
                    $rating = wp_filter_nohtml_kses($_POST['rating_' . $review_sanitize]);
                    add_comment_meta( $comment_id, 'rating_' . $review_sanitize, $rating );
                }
            }
        }
}

add_filter( 'preprocess_comment', 'bt_verify_comment_meta_data' );
	function bt_verify_comment_meta_data( $commentdata ) {

		 $current_post_type = get_post_type( $commentdata["comment_post_ID"] );
		 if (bt_is_forbiden_current_post_type($current_post_type))
			 return $commentdata;

		  if ( ! isset( $_POST['rating'] ) )
		  wp_die( __( 'Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.','bt_plugin' ) );
		  return $commentdata;
	}

add_filter( 'comment_text', 'bt_modify_comment');
	function bt_modify_comment( $text ){
		 $current_post_type = get_post_type();
		 if (bt_is_forbiden_current_post_type($current_post_type))
			return $text;
                 
                  $comment_id       = get_comment_ID();
                  $comment          = get_comment( $comment_id ); 
                  $comment_post_id  = $comment->comment_post_ID ;
                  
                  $text_added = '';

		  if( $commenttitle = get_comment_meta( get_comment_ID(), 'title', true ) ) {
			$commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';
			$text_added .= $commenttitle;
		  } 

		  if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
				$commentrating = '<p class="comment-rating">Rating: <strong>'. $commentrating .' / 5</strong></p>';
				$text_added .= $commentrating;
		  } 
                  
                  $text_added2 = '';
                 
                  $tour_user_review_arr    = bt_tour_get_user_reviews( $comment_post_id );
                  foreach( $tour_user_review_arr as $review ) {
                        $review_sanitize = sanitize_title_with_dashes($review);
                        if( $commentrating = get_comment_meta( get_comment_ID(), 'rating_' .$review_sanitize , true ) ) {
                                      $commentrating = '<p class="comment-rating">Rating for:' .$review . ' <strong>'. $commentrating .' / 5</strong></p>';
                                      $text_added2 .= $commentrating;
                        } 
                  }
                 
                 return $text . $text_added . $text_added2;
	}

add_action( 'add_meta_boxes_comment', 'bt_extend_comment_add_meta_box' );
	function bt_extend_comment_add_meta_box() {
                 $comment_id = get_comment_ID();
                 $comment = get_comment( $comment_id );
		 $current_post_type = get_post_type( $comment->comment_post_ID );
		 if (bt_is_forbiden_current_post_type($current_post_type))
			return;

		 add_meta_box( 'title', __( 'Extended Comment','bt_plugin' ), 'bt_extend_comment_meta_box', 'comment', 'normal', 'high' );
		 remove_meta_box( 'woocommerce-rating',  'comment', 'normal');
	}

	function bt_extend_comment_meta_box ( $comment ) {

		$current_post_type = get_post_type( $comment->comment_post_ID );
		if (bt_is_forbiden_current_post_type($current_post_type))
			return;
                                 
                $comment_post_id = $comment->comment_post_ID ;

		$title	= get_comment_meta( $comment->comment_ID, 'title', true );
		$rating = get_comment_meta( $comment->comment_ID, 'rating', true );
		wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
		?>
		<table class="form-table editcomment" style="width: 100%;">
			<tbody>
			<tr>
				<td class="first" style="width: 10%;"><label for="title"><?php _e( 'Title' ); ?>:</label></td>
				<td><input type="text" name="title" value="<?php echo esc_attr( $title ); ?>" id="name" style="width: 98%;"></td>
			</tr>
			<tr>
				<td class="first" style="width: 10%;"><label for="rating"><?php _e( 'Rating: ' ); ?>:</label></td>
				<td>
					 <span class="commentratingbox">
						  <?php for( $i=1; $i <= 5; $i++ ) {
							echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"';
							if ( $rating == $i ) echo ' checked="checked"';
								echo ' />'. $i .' </span>';
							}
						  ?>
					  </span>
				</td>
			</tr>
                        <?php
                            $tour_user_review_arr    = bt_tour_get_user_reviews( $comment_post_id );
                            foreach( $tour_user_review_arr as $review ) {
                                if ( $review != '' ){

                                    $review_sanitize = sanitize_title_with_dashes($review);
                                    $rating = get_comment_meta( $comment->comment_ID, 'rating_'. $review_sanitize, true );
                                    ?>
                                        <tr>
                                                <td class="first" style="width: 10%;"><label for="rating"><?php _e( 'Rating for ' ); ?><?php echo $review;?>:</label></td>
                                                <td>
                                                         <span class="commentratingbox">
                                                                  <?php for( $i=1; $i <= 5; $i++ ) {
                                                                        echo '<span class="commentrating"><input type="radio" name="rating_' . $review_sanitize . '" id="rating'. $i .'_' . $review_sanitize . '" value="'. $i .'"';
                                                                        if ( $rating == $i ) echo ' checked="checked"';
                                                                                echo ' />'. $i .' </span>';
                                                                        }
                                                                  ?>
                                                          </span>
                                                </td>
                                        </tr>
                                    <?php
                                }
                            }
                        ?>
			</tbody>
		</table>
		<?php
	}


add_action( 'edit_comment', 'bt_extend_comment_edit_metafields' );
	function bt_extend_comment_edit_metafields( $comment_id ) {

                $current_post_type = get_post_type( get_comment($comment_id)->comment_post_ID );
                if (bt_is_forbiden_current_post_type($current_post_type))
                       return;

                if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;

                if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') ){
                        $title = wp_filter_nohtml_kses($_POST['title']);
                        update_comment_meta( $comment_id, 'title', $title );
                } else {
                        delete_comment_meta( $comment_id, 'title');
                }

                if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') ){
                        $rating = wp_filter_nohtml_kses($_POST['rating']);
                        update_comment_meta( $comment_id, 'rating', $rating );
                } else {
                        delete_comment_meta( $comment_id, 'rating');
                }

                $comment          = get_comment( $comment_id ); 
                $comment_post_id  = $comment->comment_post_ID ;
                $tour_user_review_arr    = bt_tour_get_user_reviews( $comment_post_id );
                foreach( $tour_user_review_arr as $review ) {
                    if ( $review != '' ){
                         $review_sanitize = sanitize_title_with_dashes($review);
                         if ( ( isset( $_POST['rating_' . $review_sanitize] ) ) && ( $_POST['rating_' . $review_sanitize] != '') ) {
                            $rating = wp_filter_nohtml_kses($_POST['rating_' . $review_sanitize]);
                            update_comment_meta( $comment_id, 'rating_' . $review_sanitize, $rating );
                        }else{
                            delete_comment_meta( $comment_id, 'rating_' . $review_sanitize);
                        }
                    }
                }
	}

/**
 * Remove the original comment field because it's added to the default fields in /comments.php
 */
add_filter( 'comment_form_defaults', 'bt_remove_comment_form_defaults', 10, 1 );
	function bt_remove_comment_form_defaults( $defaults ) {
		if ( isset( $defaults[ 'comment_field' ] ) ) {
			$defaults[ 'comment_field' ] = '';
		}
		return $defaults;
	}



function bt_is_forbiden_current_post_type($current_post_type) {
	$current_post_type = !empty( $current_post_type ) ? $current_post_type : "";
	$approved_post_types = array( 'tour' );
	if ( in_array( $current_post_type , $approved_post_types) )
		return 0;
	else
		return 1;
}

/************ /COMMENT FORM ****************/

