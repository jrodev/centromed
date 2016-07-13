<?php
/*********************/
require_once ( get_template_directory() . '/inc/matisse-theme-options.php' );
require_once ( get_template_directory() . '/inc/matisse-widgets.php' );
require_once ( get_template_directory() . '/inc/shortcode.php' );
/*********************/
if ( ! isset( $content_width ) )
		$content_width = 620;
/*********************/
add_action( 'after_setup_theme', 'matisse_setup' );

if ( ! function_exists( 'matisse_setup' ) ):
	function matisse_setup() {
/*********************/
		load_theme_textdomain( 'matisse', get_template_directory() . '/languages' );
/*********************/	
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 620, 250, true );
		add_image_size('matisse_single_post', 620, 250, true);
		set_post_thumbnail_size( 500, 340, true );
		add_image_size('matisse_slide', 500, 340, true);
		add_theme_support( 'post-formats', array( 'aside', 'gallery','image','link') );
		add_theme_support( 'automatic-feed-links' );
		/*********************/
		add_editor_style();
		/*********************/
		if (function_exists('wp_nav_menu')) {
		register_nav_menus(array('primary' =>__( 'Primary Navigation', 'matisse' )));
		}
		/*********************/
		function matisse_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
		}
		add_filter( 'wp_page_menu_args', 'matisse_menu_args' );
		/********************Default gallery style*/
		add_filter('use_default_gallery_style', '__return_false');
		/*********************/
		add_custom_background();
		/********************Widgets*/
function matisse_widgets_init() {
		register_sidebar( array(
		'name' => __( 'Sidebar Widget Area', 'matisse'),
		'id' => 'sidebar-widget-area-full',
		'description' => __( 'The sidebar widget area, full width', 'matisse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		) );
		
		register_sidebar( array(
		'name' => __( 'Sidebar Widget Area', 'matisse'),
		'id' => 'sidebar-widget-area-left',
		'description' => __( 'The sidebar widget area, left side', 'matisse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		) );
		
		register_sidebar( array(
		'name' => __( 'Sidebar Widget Area', 'matisse'),
		'id' => 'sidebar-widget-area-right',
		'description' => __( 'The sidebar widget area, right side', 'matisse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		) );

		register_sidebar( array(
		'name' => __( 'Footer Widget Area', 'matisse' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'matisse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		) );

		register_sidebar( array(
		'name' => __( 'Footer Widget Area', 'matisse' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'matisse' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		) );

		}
add_action( 'widgets_init', 'matisse_widgets_init' );
/********************Links format*/
	if(!function_exists('matisse_link_format')) {
		function matisse_link_format() {
			if(!preg_match('/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches))
			return esc_url_raw(get_permalink());
			return esc_url_raw($matches[1]);
		}
	}/*********************/
		function matisse_slide() {
			if ( ! is_admin() ){
		wp_enqueue_script('jquery');
		wp_register_script('matisse-slide', get_template_directory_uri() .'/js/slides.min.jquery.js', array('jquery'));
		}
		}
		add_action('init', 'matisse_slide');
/*********************/
			function matisse_print() {
				if ( ! is_admin() ){
				wp_register_style('print', get_template_directory_uri() . '/print.css', '', '1.0', 'print');
				wp_enqueue_style('print');
			}
			}
			add_action('wp_print_styles', 'matisse_print');
		/*********************/	
		function matisse_fancybox_js() {
				if ( ! is_admin() ){
		wp_enqueue_script('jquery');
		wp_register_script('fancybox', get_template_directory_uri() .'/js/jquery.fancybox-1.3.4.pack.js', array('jquery'));
		wp_enqueue_script('fancybox');
		}
		}
		add_action('init', 'matisse_fancybox_js');
		/*********************/	
		function matisse_fancybox() {
				if ( ! is_admin() ){
		wp_enqueue_script('fancybox');
		$id= get_the_ID();
		echo '<script type="text/javascript">jQuery(document).ready(function($){
		$(".gallery-icon a[href$=\'.jpg\'], .gallery-icon a[href$=\'.jpeg\'], .gallery-icon a[href$=\'.gif\'], .gallery-icon a[href$=\'.png\']").attr("rel", \'gallery_'. esc_attr($id) .'\').fancybox({\'transitionIn\' : \'none\',
		\'transitionOut\' : \'none\', \'titlePosition\' 	: \'over\', \'titleFormat\'		: function(title, currentArray, currentIndex, currentOpts) {
		return \'<span id="fancybox-title-over">'.__('Image','matisse').' \' + (currentIndex + 1) + \' / \' + currentArray.length + (title.length ? \' &nbsp; \' + title : \'\') + \'</span>\';
		}});
		$(".wp-caption a[href$=\'.jpg\'], .wp-caption a[href$=\'.jpeg\'] .wp-caption a[href$=\'.gif\'] .wp-caption a[href$=\'.png\']").fancybox({\'transitionIn\' : \'elastic\',
		\'transitionOut\' : \'elastic\'});});</script>';
		}
		}
		add_action('wp_footer', 'matisse_fancybox');
/*********************/
		function matisse_recent_comments_style() {
		global $wp_widget_factory;
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
		}
		add_action( 'widgets_init', 'matisse_recent_comments_style' );
		/*********************/
		function matisse_excerpt_more( $more ) {
		return '&hellip;' . ' <a class="link_more" href="'. get_permalink() . '">' . __( 'Continue reading &rarr;', 'matisse' ) . '</a>';
		}
		add_filter( 'excerpt_more', 'matisse_excerpt_more' );
/*********************/
		if ( ! function_exists( 'matisse_posted_on' ) ) :
		function matisse_posted_on() {
		if(is_single()) {
			$format_text = __('<p> Posted on %4$s by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s </a></span> </p>', 'matisse' );
			} else {
			$format_text = __('<p> Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time datetime="%3$s" pubdate>%4$s</time></a> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s </a></span> </p>', 'matisse' );
		}
		printf( $format_text,
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'matisse' ), get_the_author() ),
		esc_html( get_the_author() )
		);
		}
		endif;
/*********************/
		if ( ! function_exists( 'matisse_posted_footer' ) ) :
		function matisse_posted_footer() {
		  $categories_list = get_the_category_list(__(', ', 'matisse'));
		  $tag_list = get_the_tag_list('', __(', ', 'matisse'));
		if('' != $tag_list) {
			$utility_text = __('This <a href="%3$s" title="Permalink to %4$s" rel="bookmark">entry</a> was posted in %1$s and tagged %2$s. %7$s', 'matisse');
		} elseif('' != $categories_list) {
			$utility_text = __('This <a href="%3$s" title="Permalink to %4$s" rel="bookmark">entry</a> was posted in %1$s. %7$s', 'matisse');
		} else {
			$utility_text = __('This <a href="%3$s" title="Permalink to %4$s" rel="bookmark">entry</a> was posted by <a href="%6$s">%5$s</a>. %7$s', 'matisse');
		}
		printf($utility_text, $categories_list, $tag_list, esc_url(get_permalink()), the_title_attribute('echo=0'), get_the_author(), esc_url(get_author_posts_url(get_the_author_meta('ID'))), edit_post_link(__('Edit This','matisse')));
		}
		endif;
/*********************/
	if ( ! defined( 'HEADER_IMAGE' ) )
		define( 'HEADER_IMAGE', '%s/images/header.jpg' );
/*********************/
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'matisse_header_image_width', 960 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'matisse_header_image_height', 100 ) );
/*********************/
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );
/*********************/
	if ( ! defined( 'NO_HEADER_TEXT' ) )
		define( 'NO_HEADER_TEXT', true );
/*********************/
function header_style() {
    ?><style type="text/css">#header .container_12{ background: url(<?php header_image(); ?>);}</style><?php
}
/*********************/
function admin_header_style() {
    ?><style type="text/css">
        #header .container_12 {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
            background: no-repeat;
        }
    </style><?php
}
/*********************/
	add_custom_image_header( 'header_style', 'admin_header_style' );
/*********************/
	register_default_headers( array(
		'matisse' => array(
			'url' => '%s/images/headers/matisse.jpg',
			'thumbnail_url' => '%s/images/headers/matisse-thumbnail.jpg',
			'description' => __( 'Matisse', 'matisse' ),
		),
			'matisse-2' => array(
			'url' => '%s/images/headers/matisse-2.jpg',
			'thumbnail_url' => '%s/images/headers/matisse-thumbnail-2.jpg',
			'description' => __( 'Matisse 2', 'matisse' ),
		),
			'matisse-3' => array(
			'url' => '%s/images/headers/matisse-3.jpg',
			'thumbnail_url' => '%s/images/headers/matisse-thumbnail-3.jpg',
			'description' => __( 'Matisse 3', 'matisse' ),
		),
/**********			'matisse-4' => array(
			'url' => '%s/images/headers/matisse-4.jpg',
			'thumbnail_url' => '%s/images/headers/matisse-thumbnail-4.jpg',
			'description' => __( 'Matisse 4', 'matisse' ),
		),
			'matisse-5' => array(
			'url' => '%s/images/headers/matisse-5.jpg',
			'thumbnail_url' => '%s/images/headers/matisse-thumbnail-5.jpg',
			'description' => __( 'Matisse 5', 'matisse' ),
		)***********/	
	) );
}
endif;
/*********************/
// end matisse_setup
/********************Paginations*/
		if ( ! function_exists('matisse_pagination') ) {
		function matisse_pagination() {
		global $wp_query, $wp_rewrite;
		$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

		$pagination = array(
		'base' => @add_query_arg('paged','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => false,
		'mid_size' => 4,
		'end_size'     => 2,
		'type' => 'plain'
		);

		if( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

		if( !empty($wp_query->query_vars['s']) )
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );

		echo '<div class="wp-pagenavi">' .paginate_links($pagination).'</div>' ;
		}
		}
/*********************/
add_action( 'show_user_profile', 'matisse_profile_fields' );
add_action( 'edit_user_profile', 'matisse_profile_fields' );
function matisse_profile_fields( $user) {
?>
    <h3><?php	_e('Social', 'matisse');?></h3>
    <table class="form-table">
        <tr>
            <th><label for="matisse_twitter_url"><?php	_e('Twitter URL', 'matisse');?></label></th>
            <td>
                <input style="width:500px" type="text" name="matisse_twitter_url" value="<?php	echo esc_url(get_the_author_meta('matisse_twitter_url', $user -> ID));?>" /><br />
            </td>
        </tr>
        <tr>
            <th><label for="matisse_facebook_url"><?php	_e('Facebook URL', 'matisse');?></label></th>
            <td>
                <input style="width:500px" type="text" name="matisse_facebook_url" value="<?php	echo esc_url(get_the_author_meta('matisse_facebook_url', $user -> ID));?>" /><br />
            </td>
        </tr>
         <tr>
            <th><label for="matisse_google_url"><?php	_e('Google plus URL', 'matisse');?></label></th>
            <td>
                <input style="width:500px" type="text" name="matisse_google_url" value="<?php	echo esc_url(get_the_author_meta('matisse_google_url', $user -> ID));?>" /><br />
            </td>
        </tr>
    </table>
<?php	}
		/*********************/
		add_action( 'personal_options_update', 'matisse_save_profile_fields' );
		add_action( 'edit_user_profile_update', 'matisse_save_profile_fields' );

		function matisse_save_profile_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) )
		return false;
		update_user_meta( $user_id, 'matisse_twitter_url', $_POST['matisse_twitter_url'] );
		update_user_meta( $user_id, 'matisse_facebook_url', $_POST['matisse_facebook_url'] );
		update_user_meta( $user_id, 'matisse_google_url', $_POST['matisse_google_url'] );
		}
		function matisse_profile() {
		echo'<ul>';
		if ( get_the_author_meta( 'matisse_google_url' ) ) :
		printf( __( '<li id="matisse_w"><a href="%1$s" title="%2$s">%2$s</a></li>', 'matisse' ), esc_url(get_the_author_meta('user_url')),
		esc_attr__(__( 'Website', 'matisse')));
		endif;
		
		if ( get_the_author_meta( 'matisse_twitter_url' ) ) :
		printf( __( '<li><a href="%1$s" title="%2$s">%2$s</a></li>', 'matisse' ), esc_url(get_the_author_meta('matisse_twitter_url')),
		esc_attr__(__( 'Twitter', 'matisse')));
		endif;

		if ( get_the_author_meta( 'matisse_facebook_url' ) ) :
		printf( __( '<li id="matisse_f"><a href="%1$s" title="%2$s">%2$s</a></li>', 'matisse' ), esc_url(get_the_author_meta('matisse_facebook_url')),
		esc_attr__(__( 'Facebook', 'matisse')));
		endif;

		if ( get_the_author_meta( 'matisse_google_url' ) ) :
		printf( __( '<li id="matisse_g"><a href="%1$s" title="%2$s">%2$s</a></li>', 'matisse' ), esc_url(get_the_author_meta('matisse_google_url')),
		esc_attr__(__( 'Google plus', 'matisse')));
		endif;
		echo'<li></li></ul>';
		}			
/*********************/
		function matisse_change_mce_buttons( $initArray ) {
		$initArray['theme_advanced_blockformats'] = 'p,h2,h3,h4,h5,h6,address,pre,code,';
		$initArray['theme_advanced_buttons3'] = 'sub,sup';
		return $initArray;
		}
		add_filter('tiny_mce_before_init', 'matisse_change_mce_buttons');
/*********************/
		if ( ! function_exists( 'matisse_footer_classes' ) ) :
		function matisse_footer_classes( $existing_classes ) {
		if ( is_active_sidebar( 'first-footer-widget-area' ) || is_active_sidebar( 'second-footer-widget-area' ))
		$classes[] = 'footer-widget';
		else
		$classes[] = 'footer-no-widget';
		return array_merge( $existing_classes, $classes );
		}
		add_filter( 'body_class', 'matisse_footer_classes' );
		endif;
/*********************/
		if ( ! function_exists( 'custom_comments' ) ) :
		function custom_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
		case '' :?>
	<li <?php	comment_class();?> id="li-comment-<?php	comment_ID();?>">
		<article id="comment-<?php	comment_ID();?>">
		<footer  class="comment-author vcard">
			<?php	echo get_avatar($comment, 60);?>
				<div class="comment-meta commentmetadata"><a href="<?php	echo esc_url(get_comment_link($comment -> comment_ID));?>">
			<?php				/* translators: 1: date, 2: time */
						printf(__('%1$s at %2$s', 'matisse'), get_comment_date(), get_comment_time());?></a><?php	edit_comment_link(__('(Edit)', 'matisse'), ' ');?><br />
		
			<?php	printf(__('%s <span class="says">says:</span>', 'matisse'), sprintf('<cite class="fn">%s</cite>', get_comment_author_link()));?>
	<div class="reply">
			<?php	comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));?>
		</div><!-- .reply -->
	</div>		

		<!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php	_e('Your comment is awaiting moderation.', 'matisse');?></em>
			<br />
		<?php	endif;?>

		<!-- .comment-meta .commentmetadata -->
</footer>
		<div class="comment-body"><?php	comment_text();?></div>


	</article><!-- #comment-##  -->

	<?php
	break;
	case 'pingback'  :
	case 'trackback' :
?>
	<li <?php	comment_class();?>>
		<p><?php	_e('Pingback:', 'matisse');?> <?php	comment_author_link();?><?php	edit_comment_link(__('(Edit)', 'matisse'), ' ');?></p>
<?php break;
endswitch;
}
endif;
/*********************/
?>