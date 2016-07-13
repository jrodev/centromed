<?php /* WordPress CMS Theme WSC Project. */ get_header(); ?>

	<div id="main">
		<div class="content">
			<h1><?php _e('Search Results')?></h1>
<?php if (have_posts()) : ?>
				<p>Keyword: <?php the_search_query(); ?> ...</p>
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

	<div class="navigation">
		<?php if(function_exists('wp_pagenavi')): ?>
		<?php wp_pagenavi(); ?>
		<?php else : ?>
		<div class="alignleft"><?php previous_posts_link(__( 'Previous page' )) ?></div>
		<div class="alignright"><?php next_posts_link(__( 'Next page' )) ?></div>
		<?php endif; ?>
	</div>

<?php else : ?>
				<p>No Match Keyword: <?php the_search_query(); ?></p>
<?php endif; ?>
		</div>
	</div>
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>