<?php /* WordPress CMS Theme WSC Project. */ get_header(); ?>

	<div id="main">
		<div id="404">
			<h1><?php _e( 'Page not found' ); ?></h1>
			<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'wsc6' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>