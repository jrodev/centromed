<?php /* WordPress CMS Theme WSC Project. */ get_header(); ?>

	<div id="main">
		<div class="content">
			<?php $post = $posts[0]; ?>
			<?php if (is_category()) { ?>
			<h1 class="pagename"><?php single_cat_title(); ?></h1>
			<?php } elseif( is_tag() ) { ?>
			<h1 class="pagename">Tag &#8216;<?php single_tag_title(); ?>&#8217;</h1>
			<?php } elseif (is_day()) { ?>
			<h1 class="pagename"><?php the_time(__('Y.n.j', 'wsc6')); ?></h1>
			<?php } elseif (is_month()) { ?>
			<h1 class="pagename"><?php the_time(__('Y.n', 'wsc6')); ?></h1>
			<?php } elseif (is_year()) { ?>
			<h1 class="pagename"><?php the_time(__('Y', 'wsc6')); ?></h1>
			<?php } elseif (is_author()) { ?>
			<h1 class="pagename">Author Archive</h1>
			<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<h1 class="pagename">Archives</h1>
			<?php } ?>

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="entry-summary">
					<?php if ( has_post_thumbnail() ) { ?>
						<a href="<?php the_permalink(); ?>" class="thumbnail-align"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
						<?php the_excerpt(); ?>
					<?php } else { ?>
						<?php the_excerpt(); ?>
					<?php } ?>
					<hr />
					</div>
				</div>

<?php endwhile; ?>

	<div class="navigation cf">
		<?php if(function_exists('wp_pagenavi')): ?>
		<?php wp_pagenavi(); ?>
		<?php else : ?>
		<div class="alignleft"><?php previous_posts_link(__( 'Previous page' )) ?></div>
		<div class="alignright"><?php next_posts_link(__( 'Next page' )) ?></div>
		<?php endif; ?>
	</div>

<?php else : ?>
<?php endif; ?>

		</div>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>