<?php

function matisse_admin_menu() {
    $page = add_theme_page( __('Theme Options','matisse'), __('Theme Options','matisse'), 'edit_theme_options', 'matisse-theme-options', 'matisse_theme_options' );
    add_action( 'admin_print_styles-' . $page, 'matisse_admin_scripts' );
}
add_action( 'admin_menu', 'matisse_admin_menu' );

function matisse_admin_scripts() {
    wp_enqueue_style( 'farbtastic' );
	wp_enqueue_style( 'twentyeleven-theme-options', get_template_directory_uri() . '/inc/theme-options.css');
    wp_enqueue_script( 'farbtastic' );
    wp_enqueue_script( 'matisse-theme-options', get_template_directory_uri() . '/js/theme-options.js', array( 'farbtastic', 'jquery' ) );
}

function matisse_theme_options() {

?>
<div class="wrap">
	<h2><?php _e('Matisse Theme Options','matisse')
	?></h2>
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options');?>
		<?php settings_fields('matisse-theme-options');?>
		<?php do_settings_sections('matisse-theme-options');?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php _e('Save Changes','matisse') ?>" />
			</p >
	</form>
</div>
<?php	}
	function matisse_admin_init() {
	register_setting( 'matisse-theme-options', 'matisse-theme-options','matisse_options_validate' );
	add_settings_section( 'section_general',__('General Settings','matisse') , 'matisse_section_general', 'matisse-theme-options' );
	add_settings_field( 'logo', __('Logo URL','matisse'), 'matisse_logo_setting', 'matisse-theme-options', 'section_general' );
	add_settings_field( 'color', __('Color links','matisse'), 'matisse_setting_color', 'matisse-theme-options', 'section_general' );
	}
	add_action('admin_init', 'matisse_admin_init' );

	function matisse_section_general() {
	_e( 'The general section.' ,'matisse');
	}

	function matisse_setting_color() {
	$options = get_option( 'matisse-theme-options' );
	$default = '#A90000';
?>

<fieldset>
	<legend class="screen-reader-text">
		<span><?php _e('Link Color', 'matisse');?></span>
	</legend>
	<input  type="text" name="matisse-theme-options[color]" id="link-color" value="<?php echo matisse_color_valid();?>" />
	<a href="#" class="pickcolor hide-if-no-js" id="link-color-example"></a>
	<input type="button" class="pickcolor button hide-if-no-js" value="<?php esc_attr_e('Select a Color', 'matisse');?>" />
	<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
	<br />
	<span><?php printf(__('Default color: %s', 'matisse'), '<span id="default-color">' . $default . '</span>');?></span>
</fieldset>
</div> <?php
}

function matisse_logo_setting(){
$options = get_option( 'matisse-theme-options' );
?>
<div style="position: relative;">
	<input style="width:400px;" id="logo" type="text" name="matisse-theme-options[logo]" value="<?php echo esc_url($options['logo']);?>" />
	<?php printf(__('<a href="%s" target="_blank"><br />Upload your logo</a>', 'matisse'), home_url() . '/wp-admin/media-new.php');?> <?php _e('(max 360px x 120px) using WordPress Media Library and insert its URL here', 'matisse');?>
	<br/>
	<?php if ($options['logo'] != '' ) {
	?><img src="<?php echo esc_url($options['logo']);?>" /><?php }?>
</div>
<?php
}

function matisse_color_valid() {
$options = get_option( 'matisse-theme-options' );
$color = $options['color'];
$default = preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $color );
if( $color  != $default):
$color;
else:
$color = '#a90000';
endif;
return $color;
}

function matisse_options_validate( $input ) {
if ( isset( $input['logo'] ) )
$input['logo'] = esc_url( $input['logo'] );

if ( isset( $input['color'] ))
$input['color'] = '#' . strtolower( ltrim( $input['color'], '#') );

return $input;
}

function matisse_header(){
$options = get_option('matisse-theme-options' );
if ($options['logo'] <> '' ) :
$typo_heading_tag = (is_home() || is_front_page()) ?'h1' : 'h2';
?>
<<?php	echo $typo_heading_tag;?>
 class="grid_5"> <a id="logo" href="<?php	echo site_url();?>"> <img src="<?php echo esc_url($options['logo']);?>" alt="<?php	bloginfo('name');?>" title="<?php	bloginfo('name');?>" /></a></<?php	echo $typo_heading_tag;?>>

<?php	else :?>

<?php	$typo_heading_tag = (is_home() || is_front_page()) ? 'h1' : 'h2';?>
<<?php	echo $typo_heading_tag;?>
 class="grid_5"> <a href="<?php	echo site_url();?>"><?php	bloginfo('name');?></a></<?php echo $typo_heading_tag;?>>
<?php endif;
	}

function matisse_wp_head() {
	$options = get_option('matisse-theme-options' );
	$color = matisse_color_valid();
	$default = '#a90000';
	if( $color != $default)
	echo '<style type="text/css"> a { color: ' . $color . ' }</style>';
	}
	add_action( 'wp_head', 'matisse_wp_head' );

function matisse_login_head() {
	$options = get_option( 'matisse-theme-options' );
	if ($options['logo'] != '' ) {
?>
<style>
	body.login #login h1 a {
	background: url("<?php echo esc_url($options['logo']) ?>") no-repeat scroll center top transparent;
	}
</style>
<?php
add_filter ('login_headerurl', 'matisse_login_url');
}
}
add_action('login_head', 'matisse_login_head');

function matisse_login_url () {
echo site_url();
}

?>
