<?php get_header();?>
<div class="container_12">
	<section class="grid_12 error">
		<article>
			<h1><?php _e('Error 404 - Not Found', 'matisse');?></h1>
		
		<p>
			<?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'matisse');?>
		</p>
		<?php get_search_form(); ?> 
		</article>
	</section>
<div class="clear"></div>
</div>
<?php wp_footer();?>
</body>
</html>
