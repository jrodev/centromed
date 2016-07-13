<footer id="footer">
	<div class="container_12">
		<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) :
		?>
		<div class="grid_6">
			<ul class="widget">
				<?php dynamic_sidebar('first-footer-widget-area');?>
			</ul>
		</div>
		<?php  endif?>
		<?php	if ( is_active_sidebar( 'second-footer-widget-area' ) ) :
		?>
		<div class="grid_6 alignright">
			<ul class="widget">
				<?php dynamic_sidebar('second-footer-widget-area');?>
			</ul>
		</div>
		<?php  endif?>

		<ul id="footermenu" class="grid_12">
			<li>
				<?php wp_register('', ', ');?>
			</li>
			<li>
				<?php wp_loginout();?>,
			</li>
			<li>
				<a href="<?php echo esc_url(__('http://wordpress.org/', 'matisse'));?>" title="<?php _e('Powered by WordPress, state-of-the-art semantic personal publishing platform.', 'matisse');?>"><abbr title="WordPress">WP</abbr></a>,
			</li>
			<li>
				<a href="<?php echo esc_url(__('http://tommek.eu', 'matisse'));?>"> tommek.eu </a> 2011
			</li>
			<?php wp_meta();?>
		</ul>
		<div class="clear"></div>
	</div>
</footer>
<?php wp_footer();?>
</body>
</html>