<?php	get_header();?>
<div class="container_12">
	<section id="content" class="grid_8">
	<h1 class="title"><?php
	if(is_category()) :
		printf(__('Category Archives: <span>%s</span>', 'matisse'), single_cat_title('', false));
	elseif(is_tag()) :
		printf(__('Tag Archives: <span>%s</span>', 'matisse'), single_tag_title('', false));
	elseif(is_day()) :
		printf(__('Daily Archives: <span>%s</span>', 'matisse'), get_the_date());
	elseif(is_month()) :
		printf(__('Monthly Archives: <span>%s</span>', 'matisse'), get_the_date('F Y'));
	elseif(is_year()) :
		printf(__('Yearly Archives: <span>%s</span>', 'matisse'), get_the_date('Y'));
	elseif(has_post_format('gallery')) :
		_e('Gallery', 'matisse');
	elseif(has_post_format('link')) :
		_e('Links', 'matisse');
	elseif(has_post_format('aside')) :
		_e('Aside', 'matisse');
	elseif(has_post_format('image')) :
		_e('Images', 'matisse');
	else :
		_e('Blog Archives', 'matisse');
	endif;
	?></h1>
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
	<?php	get_sidebar();?>
	<?php
	if(function_exists('wp_pagenavi')) { wp_pagenavi();
	} else {  matisse_pagination();
	}
	?>
</div>
<?php get_footer();?>
