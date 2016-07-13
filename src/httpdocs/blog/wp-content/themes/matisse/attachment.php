<?php get_header();?>
<div class="container_12">
	<nav  class="navigation  grid_12">
		<div class="alignleft">
			&larr; <?php previous_image_link(false);?>
		</div>
		<div class="alignright">
			<?php next_image_link(false);?>
			&rarr;
		</div>
	</nav>
	<section class=" grid_12">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post();
		?>
		<article <?php post_class('post');?>>
			<?php	the_title('<h1>', '</h1>');?>
			<div class="entry-content">
				<div class="entry-attachment">
					<?php if ( wp_attachment_is_image() ) :
	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
	foreach ( $attachments as $k => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}
	$k++;
	// If there is more than 1 image attachment in a gallery
	if ( count( $attachments ) > 1 ) {
		if ( isset( $attachments[ $k ] ) )
			// get the URL of the next image attachment
			$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
		else
			// or get the URL of the first image attachment
			$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
	} else {
		// or, if there's only 1 image attachment, get the URL of the image
		$next_attachment_url = wp_get_attachment_url();
	}
?>
						<p><a href="<?php echo $next_attachment_url;?>" title="<?php echo esc_attr(get_the_title());?>" rel="attachment"><?php
						$attachment_size = apply_filters('', 900);
						echo wp_get_attachment_image($post -> ID, array($attachment_size, 9999));
						// filterable image width with, essentially, no limit for image height.
						?></a></p>

						
<?php else :?>
<a href="<?php echo wp_get_attachment_url();?>" title="<?php echo esc_attr(get_the_title());?>" rel="attachment"><?php echo basename(get_permalink());?></a>
<?php endif;?>
<div class="entry-caption"><?php
if(!empty($post -> post_excerpt))
	the_excerpt();
 ?></div>
<?php the_content(__('Continue reading &rarr;', 'matisse'));?>
<?php wp_link_pages();?>
</div>
</div>
</article>


<?php endwhile;?>
</section>

<div class="grid_8"><?php comments_template();?></div>
<div class="grid_4 attachment-meta">
<?php if ( ! empty( $post->post_parent ) ) : ?>
<p><a href="<?php echo get_permalink($post -> post_parent);?>" title="<?php esc_attr(printf(__('Return to %s', 'matisse'), get_the_title($post -> post_parent)));?>" rel="gallery">
<?php printf(__('<span class="meta-nav">&larr;</span> %s', ''), get_the_title($post -> post_parent));?></a></p><?php endif;?>
<?php $imgmeta = wp_get_attachment_metadata($id);
	echo "<ul class=\"entry-meta\">\n";
	if(($imgmeta['image_meta']['title']) != '') { echo _e('<li>Title : ', 'matisse') . $imgmeta['image_meta']['title'] . "</li>\n";
	}
	printf(__('<li><span class="%1$s">Published</span> %2$s', 'matisse'), 'meta-prep meta-prep-entry-date', sprintf('<span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span></li>', esc_attr(get_the_time()), get_the_date()));
	if(wp_attachment_is_image()) { echo ' <li>';
		$metadata = wp_get_attachment_metadata();
		printf(__('Full size is %s pixels', 'matisse'), sprintf('<a id="link-image" href="%1$s" title="%2$s">%3$s &times; %4$s</a>', wp_get_attachment_url(), esc_attr(__('Link to full-size image', 'matisse')), $metadata['width'], $metadata['height']));
	}
?>

					
<?php
if(($imgmeta['image_meta']['caption']) != '') {echo _e('<li>Caption: ', 'matisse') . $imgmeta['caption'] . "</li>\n";
}
if(($imgmeta['image_meta']['camera']) != '') {echo _e('<li>Camera: ', 'matisse') . $imgmeta['image_meta']['camera'] . "</li>\n";
}
if(($imgmeta['image_meta']['aperture']) != '0') {echo _e('<li>Aperture: f/', 'matisse') . $imgmeta['image_meta']['aperture'] . "</li>\n";
}
if(($imgmeta['image_meta']['copyright']) != '') {echo _e('<li>Copyright: ', 'matisse') . $imgmeta['image_meta']['copyright'] . "</li>\n";
}
if(($imgmeta['image_meta']['credit']) != '') {echo _e('<li>Credit: ', 'matisse') . $imgmeta['image_meta']['credit'] . "</li>\n";
}
if(($imgmeta['image_meta']['focal_length']) != '0') {echo _e('<li>Focal Length: ', 'matisse') . $imgmeta['image_meta']['focal_length'] . "mm</li>\n";
}
if(($imgmeta['image_meta']['iso']) != '0') {echo _e('<li>ISO: ', 'matisse') . $imgmeta['image_meta']['iso'] . "</li>\n";
}
if(($imgmeta['image_meta']['shutter_speed']) != '0') {echo _e('<li>Shutter Speed: ', 'matisse') . number_format($imgmeta['image_meta']['shutter_speed'], 4) . " s.</li>\n";
}
echo "</ul>";
					?>
				</div>
				 <?php edit_post_link(__('Edit', 'matisse'));?>
				<div class="clear"></div>
</div>
<?php get_footer();?>
