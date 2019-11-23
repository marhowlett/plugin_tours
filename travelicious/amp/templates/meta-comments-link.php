<?php
$button_link_url = get_permalink( get_the_ID() ) . '#book-now';
?>
<?php if ( $button_link_url ) : ?>
	<?php $button_link_text = __( 'Book Now', 'bt_plugin' ); ?>
	<div class="amp-wp-meta amp-wp-comments-link">
		<a href="<?php echo esc_url( $button_link_url ); ?>">
			<?php echo esc_html( $button_link_text ); ?>
		</a>
	</div>
<?php endif; ?>

