<?php get_header();?>
<div class="container_12">
	<section id="content" class="grid_8">
		<?php if ( have_posts() ) :
		?>
		<?php the_post();?>
		<header >
			<h1><?php printf(__('Author Archives: %s', 'matisse'), '<span class="vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta("ID"))) . '" title="' . esc_attr(get_the_author()) . '" rel="me">' . get_the_author() . '</a></span>');?></h1>
		</header>
		<?php rewind_posts();?>
		<?php
if ( get_the_author_meta( 'description' ) ) :
		?>
		<div id="author-info">
			<?php	echo get_avatar(get_the_author_meta('user_email'));?>
			<p>
				<?php	the_author_meta('description');?>
			</p>
			<?php
			if(function_exists('matisse_profile')) :
				matisse_profile();
			endif;
			?>
			<div class="clear"></div>
		</div>
		<?php	endif;?>
		<?php endif;?>
		<?php  while (have_posts()) : the_post();

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
