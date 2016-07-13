<?php /**
 * Template Name: Slideshow with posts
 *  */
wp_enqueue_script('matisse-slide');
?>
<?php get_header();?>
<div id="slideshome">
	<div class="container_12">
		<script type="text/javascript">
jQuery(document).ready(function($){
// Set starting slide to 1
var startSlide = 1;
// Initialize Slides
$('#slides').slides({
preload: true,
preloadImage: '<?php echo get_template_directory_uri();?>/images/loading.gif',
	generatePagination: true,
	//play: 5000,
	pause: 2500,
	hoverPause: true,
	effect: 'fade',
	// Get the starting slide
	start: startSlide,
	animationComplete: function(current) {
		// Set the slide number as a hash
		window.location.hash = '#' + current;
	}
	});
	});
		</script>
		<div id="container" class="grid_12 " >
			<div id="example">
				<div  id="slides">
					<div class="slides_container">
						<?php
						$sticky = get_option('sticky_posts');
						$args = array(
						//'posts_per_page' => 2,
						'post__in' => $sticky, 'ignore_sticky_posts' => 0);
						$args_not = array('posts_per_page' => 4);
						if($sticky) :
							query_posts($args);
						else :
							query_posts($args_not);
						endif;
						?>
						<?php   if (have_posts()) : while (have_posts()) : the_post();
						?>
						<div class="slide">
							<?php if ( (function_exists( 'add_theme_support' ))  && ( has_post_thumbnail() )):
the_post_thumbnail('matisse_slide');
							?>
							<div class="captionslide">
								<h2><a href="<?php	the_permalink();?>" title="<?php	echo esc_attr(sprintf(__('Permanent Link to %s', 'matisse'), the_title_attribute('echo=0')));?>" rel="bookmark"><?php	the_title();?></a></h2>
								<?php the_excerpt();?>
							</div>
							<?php else :?>
							<div class="slide-no-image">
								<h2><a href="<?php	the_permalink();?>" title="<?php	echo esc_attr(sprintf(__('Permanent Link to %s', 'matisse'), the_title_attribute('echo=0')));?>" rel="bookmark"><?php	the_title();?></a></h2>
								<?php the_excerpt();?>
							</div>
							<?php endif;?>
						</div>
						<?php endwhile; endif; wp_reset_query(); ?>
					</div>
					<a href="#" class="prev">Prev</a>
					<a href="#" class="next">Next</a>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="container_12">
	<section class="grid_8">
		<?php
$sticky = get_option( 'sticky_posts' );
$args=array(
'paged' =>   get_query_var( 'page' ),
'post__not_in' => $sticky
);
$wp_query = new WP_Query($args);
while ($wp_query->have_posts()) : $wp_query->the_post();
		?>
		<?php get_template_part('loop', get_post_format());?>
		<?php endwhile;?>
	</section>
	<?php get_sidebar();?>
	<?php
	if(function_exists('wp_pagenavi')) { wp_pagenavi();
	} else {  matisse_pagination();
	}
	?>
</div>
<?php get_footer();?>
