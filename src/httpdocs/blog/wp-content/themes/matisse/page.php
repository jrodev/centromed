<?php get_header();?>
<div class="container_12">
	<section id="content" class="prefix_2 grid_8 suffix_2">
		<?php if (have_posts())  : the_post();
		?>
		<article <?php post_class('post') ?>>
			<header>
				<?php
				if((function_exists('add_theme_support')) && ( has_post_thumbnail())) {
					the_post_thumbnail('matisse_single_post');
				}
				?>
				<?php if ( is_front_page() ) {
				?>
				<?php the_title('<h2 class="title">', '</h2>');?>
				<?php } else {?>
				<?php the_title('<h1 class="title">', '</h1>');?>
				<?php }?>
			</header>
			<?php the_content(__('(more...)', 'matisse'));?>
			<?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'matisse'), 'after' => '</div>'));?>
			<?php edit_post_link(__('Edit This', 'matisse'));?>
		</article>
		<?php comments_template();?>
		<?php  else:?>
		<article <?php post_class('post') ?>>
		  <p>
			<?php _e('Sorry, no posts matched your criteria.', 'matisse');?>
		  </p>
		</article>
		<?php endif;?>
	</section>
	<div class="clear"></div>
</div>
<?php get_footer();?>
