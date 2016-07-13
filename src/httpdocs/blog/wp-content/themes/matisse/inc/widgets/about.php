<?php
/***********ABOUT**********/
class matisse_about_widget extends WP_Widget {
function matisse_about_widget() {
$widget_ops = array('classname' => 'vcard', 'description' => 'About' );
$this->WP_Widget('', 'About', $widget_ops);
}

function widget($args, $instance) {
extract($args, EXTR_SKIP);

$name = empty($instance['name']) ? '' : apply_filters('widget_title', $instance['name']);
$photo = empty($instance['photo']) ? '' : apply_filters('widget_entry_title', $instance['photo']);
$desc = empty($instance['desc']) ? '' : apply_filters('widget_entry_title', $instance['desc']);

echo $before_widget;
if (!empty($title)) {echo $before_title . $title . $after_title;}

echo '<ul>';
if (!empty($name)) {echo '<li class="fn"><h3>'.$name.'</h3></li>';}
if (!empty($photo)) {echo '<li class="photo"><img src="'.$photo.'" alt="'.$photo.'" width="80" height="80" /></li>';}
if (!empty($desc)) {echo '<li class="note">'.$desc.'</li>';} else {bloginfo('description');}
echo '</ul>';

echo $after_widget;
}

function update($new_instance, $old_instance) {
$instance = $old_instance;
$instance['name'] = strip_tags($new_instance['name']);
$instance['photo'] = strip_tags($new_instance['photo']);
$instance['desc'] = strip_tags($new_instance['desc']);

return $instance;
}

function form($instance) {
$instance = wp_parse_args( (array) $instance, array( 'name' => '', 'photo' => '', 'desc' => '' ) );
$name = strip_tags($instance['name']);
$photo = strip_tags($instance['photo']);
$desc = strip_tags($instance['desc']);
?>
<p>
	<label for="<?php echo $this -> get_field_id('name');?>"> <?php _e('Name:', 'matisse');?><input class="widefat" id="<?php echo $this -> get_field_id('name');?>" name="<?php echo $this -> get_field_name('name');?>" type="text" value="<?php echo esc_attr($name);?>" /></label>
</p>
<p>
	<label for="<?php echo $this -> get_field_id('photo');?>"><?php _e('Link to your image(80x80px):', 'matisse');?>
		<input class="widefat" id="<?php echo $this -> get_field_id('photo');?>" name="<?php echo $this -> get_field_name('photo');?>" type="text" value="<?php echo esc_attr($photo);?>" />
	</label>
</p>
<p>
	<label for="<?php echo $this -> get_field_id('desc');?>"><?php _e('Description:', 'matisse');?> 				<textarea  rows="5" cols="30" class="widefat" id="<?php echo $this -> get_field_id('desc');?>" name="<?php echo $this -> get_field_name('desc');?>"  value="<?php echo esc_attr($desc);?>" ><?php echo esc_attr($desc);?></textarea></label>
</p>
<?php
}
}
register_widget('matisse_about_widget');
?>
