<?php /* WordPress CMS Theme WSC Project. */ get_header(); ?>

	<div id="main">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h1 class="page-title"><?php the_title(); ?></h1>
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">Pages', 'after' => '</div>' ) ); ?>
			<div class="postmetadata"><span class="date">update: <?php the_time('Y/m/d') ?></span><?php the_tags( __( ' tags: ', 'wsc6' ), ', ', ''); ?> | <?php the_category(', ') ?></div>
		</div>
		<?php comments_template(); ?>

		<div class="navigation cf">
			<div class="alignleft cf"><?php previous_post_link('%link',__( 'Previous page' ),'TRUE') ?></div>
			<div class="alignright cf"><?php next_post_link('%link',__( 'Next page' ),'TRUE') ?></div>
		</div>
<?php endwhile; endif; ?>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>