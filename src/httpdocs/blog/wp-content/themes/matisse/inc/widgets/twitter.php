<?php
/***********TWITTER**********/
class matisse_twitter_widget extends WP_Widget {
function matisse_twitter_widget() {
$widget_ops = array('classname' => 'twitter_bg', 'description' =>
'A widget to enable people to follow you on Twitter' );
$this->WP_Widget('', 'Twitter', $widget_ops);
$this->twitt_numbers = array(
"1" => "1",
"2" => "2",
"3" => "3",
"4" => "4",
"5" => "5",
"6" => "6",
);
}

function widget($args, $instance) {
extract($args, EXTR_SKIP);

$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
$twitter = empty($instance['twitter']) ? '' : apply_filters('widget_entry_title', $instance['twitter']);
$numbertwitter = $instance['numbertwitter'];

echo $before_widget;
if (!empty($title)) {echo $before_title . $title . $after_title;   }

echo '<div class="twitterbar"><div id="twitter_div">
<ul id="twitter_update_list"><li>&nbsp;</li></ul>
<div id="twi"></div>
</div>
<span id="twitter-follow"><a id="twitter-link" href="http://twitter.com/'.$twitter.'">follow me on Twitter</a></span>
<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/'.$twitter.'.json?callback=twitterCallback2&amp;count='.$numbertwitter.'"></script>
</div>';

echo $after_widget;
}

function update($new_instance, $old_instance) {
$instance = $old_instance;
$instance['title'] = strip_tags($new_instance['title']);
$instance['twitter'] = strip_tags($new_instance['twitter']);
$instance['numbertwitter'] = $new_instance['numbertwitter'];

return $instance;
}

function form($instance) {
$instance = wp_parse_args( (array) $instance, array( 'title' => 'Twitter', 'twitter' => '', 'numbertwitter' => '3' ) );
$title = strip_tags($instance['title']);
$twitter = strip_tags($instance['twitter']);
$numbertwitter = $instance['numbertwitter'];
?>
<p>
	<label for="<?php echo $this -> get_field_id('title');?>"> <?php _e('Title:', 'matisse');?><input class="widefat" id="<?php echo $this -> get_field_id('title');?>" name="<?php echo $this -> get_field_name('title');?>" type="text" value="<?php echo esc_attr($title);?>" /></label>
</p>
<p>
	<label for="<?php echo $this -> get_field_id('twitter');?>"><?php _e('User:', 'matisse');?>
		<input class="widefat" id="<?php echo $this -> get_field_id('twitter');?>" name="<?php echo $this -> get_field_name('twitter');?>" type="text" value="<?php echo esc_attr($twitter);?>" />
	</label>
</p>
<p>
	<label for="<?php echo $this -> get_field_id('numbertwitter');?>"><?php _e('Number of Tweets to display:', 'matisse');?></label>
	<select name="<?php echo $this -> get_field_name('numbertwitter');?>" id="<?php echo $this -> get_field_id('numbertwitter');?>" class="widefat">
		<?php foreach ($this->twitt_numbers as $key => $nmb) {
		?>
		<option value="<?php echo $key;?>" <?php
		if($instance['numbertwitter'] == $key) { echo " selected ";
		}
		?>><?php echo $nmb;?></option>
		<?php }?>
	</select>
</p>
<?php
}
}
register_widget('matisse_twitter_widget');
?>
