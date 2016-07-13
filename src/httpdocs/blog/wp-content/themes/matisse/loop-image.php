<article <?php post_class() ?>>
	<?php if ( post_password_required() ) :
	?>
	<?php the_content(__('Continue reading &rarr;', 'matisse'));?>

	<?php else :?>
	<?php
if((function_exists('add_theme_support')) && ( has_post_thumbnail())) {
	?>
	<figure class="image-thumb">
		<a href="<?php the_permalink();?>"><?php the_post_thumbnail('large');?></a>
		<figcaption>
			<h2><a href="<?php	the_permalink();?>" title="<?php	echo esc_attr(sprintf(__('Permanent Link to %s', 'matisse'), the_title_attribute('echo=0')));?>" rel="bookmark"><?php	the_title();?></a></h2>
			<?php if ( comments_open() && ! post_password_required() ) :
			?>
			<div class="post-comments">
			<?php	comments_popup_link(__('0', 'matisse'), __('1', 'matisse'), __('% ', 'matisse'));?>
			</div>
			<?php endif;?>

			<div class="clear"></div>
		</figcaption>
	</figure>
				<?php edit_post_link(__('Edit This', 'matisse'));?>
				
	<?php	} else {?>
		
	<?php
$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
if ( $images ) :
$total_images = count( $images );
$image = array_shift( $images );
$image_img_tag = wp_get_attachment_image( $image->ID, 'large' );

	?>
	<figure class="image-thumb">
		<a href="<?php the_permalink();?>"><?php echo $image_img_tag;?></a>
		<figcaption>
			<h2><a href="<?php	the_permalink();?>" title="<?php	echo esc_attr(sprintf(__('Permanent Link to %s', 'matisse'), the_title_attribute('echo=0')));?>" rel="bookmark"><?php	the_title();?></a></h2>
			<?php if ( comments_open() && ! post_password_required() ) :
			?>
			<div class="post-comments">
				<?php	comments_popup_link(__('0', 'matisse'), __('1 ', 'matisse'), __('%', 'matisse'));?>
			</div>
			<?php endif;?>

			<div class="clear"></div>
		</figcaption>
	</figure>
				<?php edit_post_link(__('Edit This', 'matisse'));?>
	<?php endif;?>

	<?php } endif;?>
</article>