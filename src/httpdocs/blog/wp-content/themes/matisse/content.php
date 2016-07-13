<?php if (have_posts()) : the_post();
?>
<section id="content" class=" grid_8">
	<article <?php post_class('post') ?>>
		<header>
			<?php	the_title('<h1 class="title">', '</h1>');?>
			<?php if ( comments_open() && ! post_password_required() ) :
			?>
			<div class="post-comments">
				<?php	comments_popup_link(__('0', 'matisse'), __('1 ', 'matisse'), __('% ', 'matisse'));?>
			</div>
			<?php endif;?>
			<?php	matisse_posted_on();?>
			<?php /* IMAGE */ if ( has_post_format( 'image' )) :
			
			if((function_exists('add_theme_support')) && ( has_post_thumbnail())) {
				the_post_thumbnail('large');
			}
			?>
			<?php			else :
				if((function_exists('add_theme_support')) && ( has_post_thumbnail())) {
				the_post_thumbnail('matisse_single_post');
				}
				endif;?>
		</header>
		<?php	the_content(__('Continue reading &rarr;', 'matisse'));?>
		<footer>
			<?php	wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'matisse'), 'after' => '</div>'));?>
		<?php
		matisse_posted_footer();
		?>
			<?php
if ( get_the_author_meta( 'description' ) ) :
			?>
			<div id="author-info">
				<?php	echo get_avatar(get_the_author_meta('user_email'));?>

				<h2><?php	printf(__('About %s', 'matisse'), get_the_author());?></h2>
				<p>
					<?php	the_author_meta('description');?> <br />
					<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID')));?>" rel="author"> <?php printf(__('View all posts by %s &rarr;', 'matisse'), get_the_author());?></a>
				</p>
				<?php
				if(function_exists('matisse_profile')) :
					matisse_profile();
				endif;
				?>
				<div class="clear"></div>
			</div>
			<?php	endif;?>
		</footer>
	</article>
	<?php comments_template('', true);?>
</section>
<?php  endif;?>
<?php get_sidebar();?>