<!DOCTYPE html>
<html <?php	language_attributes();?>>
	<head>
		<meta charset="<?php bloginfo('charset');?>" />
		<title><?php
		global $page, $paged;
		wp_title('|', true, 'right');
		bloginfo('name');

		$site_description = get_bloginfo('description', 'display');
		if ($site_description && (is_home() || is_front_page()))
			echo " | $site_description";

		if ($paged >= 2 || $page >= 2)
			echo ' | ' . sprintf(__('Page %s', 'matisse'), max($paged, $page));
			?></title>
			
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php	bloginfo('stylesheet_url');?>" />
		<link rel="pingback" href="<?php	bloginfo('pingback_url');?>" />
		<?php
			if (is_singular() && get_option('thread_comments'))
				wp_enqueue_script('comment-reply');
			wp_head();
		?>
		<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
		<![endif]-->
	</head>
	<body <?php	body_class();?>>
		<header id="header">
			<hgroup class="container_12 ">
				<?php
				if (function_exists('matisse_header')) { matisse_header();
				}
				?>
				<h3 class="grid_7"><?php bloginfo('description');?></h3>
			</hgroup>
		</header>
		<nav  id="nav" >
			<div class="container_12">
				<?php wp_nav_menu(array('sort_column' => 'menu_order', 'Primary Navigation', 'theme_location' => 'primary', 'container' => ' ', 'menu_class' => 'grid_12 nav'));?>
			</div>
			<div class="clear"></div>
		</nav>