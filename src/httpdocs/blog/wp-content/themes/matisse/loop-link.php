<article <?php post_class() ?>>
	<header><h2><a href="<?php	echo matisse_link_format();?>"  rel="bookmark"><?php	the_title();?></a></h2>
	</header>
	<footer>
		<?php if ( comments_open() && ! post_password_required() ) :
		?><?php	edit_post_link(__('Edit This', 'matisse'));?>
		<div class="post-comments">
			<?php	comments_popup_link(__('0', 'matisse'), __('1', 'matisse'), __('% ', 'matisse'));?>
		</div>
		<?php endif;?>
		
		<?php	echo matisse_posted_on();?>
	</footer>
</article>