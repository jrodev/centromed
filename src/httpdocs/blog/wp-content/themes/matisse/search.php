<?php	get_header();?>
<div class="container_12">
	<h1 class="grid_12 title"><?php printf(__('Search Results for: %s', 'matisse'), '<span>' . get_search_query() . '</span>');?></h1>
	<section id="content" class="grid_8">
		<?php if ( have_posts() ) : while (have_posts()) : the_post();
		?>
		<?php get_template_part('loop', get_post_format());?>
		<?php endwhile; else :?>
	<article <?php post_class() ?>>
		<header>
			<h1><?php	_e('Not Found', 'matisse');?></h1>
			<p>
				<?php	_e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'matisse');?>
			</p>
		</header>
	</article>
		<?php	endif;?>
	</section>
	<?php get_sidebar();?>
	<?php
	if(function_exists('wp_pagenavi')) { wp_pagenavi();
	} else {  matisse_pagination();
	}
	?>
</div>
<?php get_footer();?>
