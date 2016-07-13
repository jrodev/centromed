<?php
if(!function_exists("matisse_remove_p")) {
	function matisse_remove_p($content) {
		$content = do_shortcode(shortcode_unautop($content));
		$content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
		return $content;
	}
}

function matisse_warning_shortcode($atts, $content = null) {
	return '<span class="mt_warning">' . do_shortcode(matisse_remove_p($content)) . '</span>';
}
add_shortcode('warning', 'matisse_warning_shortcode');

function matisse_alert_shortcode($atts, $content = null) {
	return '<span class="mt_alert">' . do_shortcode(matisse_remove_p($content)) . '</span>';
}
add_shortcode('alert', 'matisse_alert_shortcode');

function matisse_down_shortcode($atts, $content = null) {
	return '<span class="mt_down">' . do_shortcode(matisse_remove_p($content)) . '</span>';
}
add_shortcode('downloads', 'matisse_down_shortcode');

function matisse_info_shortcode($atts, $content = null) {
	return '<span class="mt_info">' . do_shortcode(matisse_remove_p($content)) . '</span>';
}
add_shortcode('info', 'matisse_info_shortcode');
?>