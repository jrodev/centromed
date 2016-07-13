<?php /* WordPress CMS Theme WSC Project. */ ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<title><?php bloginfo('name'); wp_title(); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css">
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="container" class="cf">
<div id="header">
	<div id="site-title"><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a></div>
	<?php if(is_home()): ?>
	<h1 id="site-description"><?php bloginfo('description'); ?></h1>
	<?php elseif(is_front_page()): ?>
	<h1 id="site-description"><?php bloginfo('description'); ?></h1>
	<?php else: ?>
	<p id="site-description"><?php bloginfo('description'); ?></p>
	<?php endif; ?>
<?php wp_nav_menu( array( 'container_id' => 'sub-menu', 'theme_location' => 'sub-menu', 'depth' => 1, 'fallback_cb' => 0 ) ); ?>
</div>

<div id="main-menu">
<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
</div>

<?php
if ( is_front_page() ) { ?>
	<div id="top-image">
		<div id="top-image-wrap">
			<?php if(function_exists('wp_cycle')) { wp_cycle();} else { ?>
			<img src="<?php echo get_template_directory_uri(); ?>/img/top-image-1.jpg" />
			<?php } ?>
		</div>
	</div>
<?php } else { ?>
	<div id="second-image">
		<div id="breadcrumb">
		<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
		</div>
	</div>
<?php } ?>

<div id="wrap">