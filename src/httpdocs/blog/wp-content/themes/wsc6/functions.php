<?php /* WordPress CMS Theme WSC Project. */

/*load_theme_textdomain*/
load_theme_textdomain( 'wsc6', TEMPLATEPATH . '/languages'  );

/*register_sidebar*/
if ( function_exists('register_sidebar') )
register_sidebar(array(1,
    'name' => __('side-widget', 'wsc6'),
	'id' => 'side-widget-area',
	'description' => __( 'side-widget', 'wsc6'),
    'before_widget' => '<div id="%1$s" class="side-widget">',
    'after_widget' => '</div>',
    'before_title' => '<p class="widget-title">',
    'after_title' => '</p>',
));
register_sidebar(array(2,
    'name' => __('footer-widget', 'wsc6'),
	'id' => 'footer-widget-area',
	'description' => __( 'footer-widget', 'wsc6'),
    'before_widget' => '<div id="%1$s" class="footer-widget">',
    'after_widget' => '</div>',
    'before_title' => '<p class="widget_title">',
    'after_title' => '</p>',
));


/*post-thumbnails*/
add_theme_support( 'post-thumbnails', array( 'post' ) );


/*automatic-feed-links*/
add_theme_support( 'automatic-feed-links' );


/*content_width*/
if ( ! isset( $content_width ) ) $content_width = 680;


/*register_nav_menus*/
register_nav_menus( array(
	'main-menu' => __( 'main-menu', 'wsc6'),
	'sub-menu' => __( 'sub-menu', 'wsc6'),
	'footer-menu' => __( 'footer-menu', 'wsc6'),
) );


/*add_editor_style*/
add_editor_style();


/*add_custom_background*/
add_custom_background();



?>
