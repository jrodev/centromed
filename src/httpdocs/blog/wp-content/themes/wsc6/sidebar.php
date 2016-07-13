<?php /* WordPress CMS Theme WSC Project. */ ?>
	<div id="side">
	<?php 	if ( ! dynamic_sidebar( 'side-widget-area' ) ) : ?>
	
			<div class="side-widget">
				<?php get_search_form(); ?>
			</div>

			<div class="side-widget">
				<h3 class="widget-title"><?php _e( 'Archives' ); ?></h3>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</div>

			<div class="side-widget">
				<h3 class="widget-title"><?php _e( 'Meta' ); ?></h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</div>

	<?php endif; ?>
	</div>
	<div class="clear"><hr /></div>