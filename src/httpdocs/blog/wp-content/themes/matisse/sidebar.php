<aside class="grid_4 sidebar">
	<?php	if ( is_active_sidebar( 'sidebar-widget-area-full' ) ) {
	?>
	<ul class="widget">
		<?php dynamic_sidebar('sidebar-widget-area-full');?>
	</ul>
	<?php } else {?>
	<div class="widget widget_full">
		<?php get_search_form();?>
	</div>
	<?php }?>
<div class="widget widget_full">
	<?php	if ( is_active_sidebar( 'sidebar-widget-area-left' ) ) {
	?>
	<ul class="widget_min">
		<?php dynamic_sidebar('sidebar-widget-area-left');?>
	</ul>
	<?php } else {?>
	<ul class="widget_min min_l">
		<li>
			<h3><?php _e('Categories', 'matisse');?></h3>
			<ul class="widget_categories">
				<?php wp_list_categories('show_count=0&title_li=&show_last_update=1&use_desc_for_title=1');?>
			</ul>
		</li>
	</ul>
	<?php }?>

	<?php	if ( is_active_sidebar( 'sidebar-widget-area-right' ) ) {
	?>
	<ul class="widget_min">
		<?php dynamic_sidebar('sidebar-widget-area-right');?>
	</ul>
	<?php } else {?>
	<ul class="widget_min">
		<li>
			<h3><?php _e('Tags', 'matisse');?></h3>
			<div class="widget_tags">
				<?php wp_tag_cloud('smallest=8&largest=22');?>
			</div>
		</li>
	</ul>
	<?php }?>
	</div>
</aside>