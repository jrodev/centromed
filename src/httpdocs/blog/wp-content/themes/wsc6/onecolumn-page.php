<?php /* WordPress CMS Theme WSC Project. */
/* Template Name: One column */ get_header(); ?>

	<div id="main-wide">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php the_content(__('more')); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">Pages', 'after' => '</div>' ) ); ?>
		</div>
		<?php comments_template(); ?>
<?php endwhile; endif; ?>
	</div>
	
<?php get_footer(); ?>