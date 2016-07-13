<?php /* WordPress CMS Theme WSC Project. */ ?>
	<div id="totop"><a href="#header"><?php _e('Top','wsc6') ?></a></div>
</div>

<div id="footer">
<?php if ( is_active_sidebar( 'footer-widget-area' ) ) : ?>
	<div id="footer-wrap">
		<div id="footer-widget-area">
			<?php 	if ( ! dynamic_sidebar( 'footer-widget-area' ) ) : ?>
			<?php endif; ?>
		</div>
		<div class="clear"><hr /></div>
	</div>
<?php endif; ?>			
</div>

			

<div id="footer-bottom" class="cf">
	<?php wp_nav_menu( array( 'container_id' => 'footer-menu', 'theme_location' => 'footer-menu', 'depth' => 1, 'fallback_cb' => 0 ) ); ?>
	<div id="copyright">
	Copyright <?php bloginfo('name'); ?>.
	WordPress CMS Theme <a href="http://www.studiobrain.net/wsc/" target="_blank">WSC Project</a>.
	</div>
</div>

</div>

<?php wp_footer(); ?>

</body>
</html>