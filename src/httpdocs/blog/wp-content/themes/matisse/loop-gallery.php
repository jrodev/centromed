<article <?php post_class() ?>>
	<header>
		<h2><a href="<?php	the_permalink();?>" title="<?php	echo esc_attr(sprintf(__('Permanent Link to %s', 'matisse'), the_title_attribute('echo=0')));?>" rel="bookmark"><?php	the_title();?></a></h2>
		<?php	matisse_posted_on();?>
			<?php if ( comments_open() && ! post_password_required() ) :
			?>
			<div class="post-comments">
			<?php	comments_popup_link(__('0', 'matisse'), __('1', 'matisse'), __('% ', 'matisse'));?>
			</div>
			<?php endif;?>
	</header>
	<?php if ( is_archive() || is_search() ) :
	?>
	<?php	the_excerpt();?>
	<?php	else :?>
				<?php if ( post_password_required() ) : ?>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'matisse' ) ); ?>

			<?php else : ?>
				<?php
					$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) :
						$total_images = count( $images );
						$image = array_shift( $images );
						$image_img_tag = wp_get_attachment_image( $image->ID, 'medium' );
				?>

				<figure class="gallery-thumb">
					<a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
				</figure>
			<?php endif; ?>
			<?php the_excerpt(); ?>
			<?php endif; ?>
	<?php endif;?>

	<footer>
		<?php	wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'matisse'), 'after' => '</div>'));?>
		<?php
		matisse_posted_footer();
		?>
	</footer>
</article>
