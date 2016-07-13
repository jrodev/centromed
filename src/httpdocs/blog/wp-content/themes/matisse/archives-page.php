<?php
/**
 * Template Name: Archives Template
 **/
?>

<?php get_header();?>
<div class="container_12">
	<section class="grid_8">
		<?php if (have_posts())  : the_post();
		?>
		<article <?php post_class('post') ?>>
			<header>
				<?php
				if((function_exists('add_theme_support')) && ( has_post_thumbnail())) {
					the_post_thumbnail('matisse_single_post');
				}
				?>
				<?php the_title('<h1 class="title">', '</h1>');?>
			</header>
			<ul  class="widget_archive">
				<?php query_posts('posts_per_page=30');  if ( have_posts() ) : while ( have_posts() ) : the_post();
				?>
				<li>
					<a href="<?php the_permalink() ?>"><?php the_title();?> - <em> <?php the_time( get_option('date_format'))
					?></em></a>
				</li>
				<?php endwhile; endif;?>
			</ul>
		</article>
		<?php comments_template();?>
		<?php  else:?>
		<p>
			<?php _e('Sorry, no posts matched your criteria.', 'matisse');?>
		</p>
		<?php endif;?>
	</section>
	<?php get_sidebar();?>
</div>
<?php get_footer();?>
