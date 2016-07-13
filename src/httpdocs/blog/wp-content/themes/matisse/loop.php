<article <?php post_class() ?>>
	<header>
		<h2><a href="<?php	the_permalink();?>" title="<?php	echo esc_attr(sprintf(__('Permanent Link to %s', 'matisse'), the_title_attribute('echo=0')));?>" rel="bookmark"><?php	the_title();?></a></h2>
		<?php if ( comments_open() && ! post_password_required() ) :
		?>
		<div class="post-comments">
			<?php	comments_popup_link(__('0', 'matisse'), __('1', 'matisse'), __('% ', 'matisse'));?>
		</div>
		<?php endif;?>
		<?php	matisse_posted_on();?>

		<?php
		if((function_exists('add_theme_support')) && ( has_post_thumbnail())) {
			the_post_thumbnail('matisse_single_post');
		}
		?>
	</header>
	<?php if ( is_archive() || is_search() ) :
	?>
	<?php	the_excerpt();?>
	<?php	else :?>
	<?php	the_content(__('<span class="link_more">Continue reading &rarr;</span>', 'matisse'));?>
	<?php endif;?>

	<footer>
		<?php	wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'matisse'), 'after' => '</div>'));?>
		<?php
		matisse_posted_footer();
		?>
	</footer>
</article>
