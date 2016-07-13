<article <?php post_class() ?>>
	<header>
	<?php if ( is_archive() || is_search() ) :
	?>
	<?php	the_excerpt();?>
	<?php	else :?>
	<?php	the_content(__('Continue reading &rarr;', 'matisse'));?>
	<?php endif;?>
	</header>
	<footer>
		<?php if ( comments_open() && ! post_password_required() ) :
		?>
			<?php edit_post_link(__('Edit This', 'matisse'));?>
		<div class="post-comments">
			<?php	comments_popup_link(__('0', 'matisse'), __('1', 'matisse'), __('% ', 'matisse'));?>
		</div>
		<?php endif;?>
		<?php	matisse_posted_on();?>
	</footer>
</article>