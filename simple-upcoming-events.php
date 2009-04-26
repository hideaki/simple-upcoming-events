<?php
/*
Plugin Name: Simple Upcoming Events
Description: Displays a list of posts for upcoming events. Depends on NO external services.
Version: 1.0.1
Author: Hideaki Hayashi
Author URI: http://tagaabo.com/
*/

function wp_widget_simple_upcoming_events_register() {
  function wp_widget_simple_upcoming_events($args){
    extract($args);
	$options = get_option('widget_simple_upcoming_events');
	$title = empty($options['title']) ? __('Upcoming Events') :apply_filters('widget_title', $options['title']);
	$date_format = empty($options['date_format']) ? 'M j' : $options['date_format'];

    //hidden parameters
    $no_posts = 5; //Number of posts shown
    $hide_pass_post = true; //Hide posts with password
    $event_date_key = 'date'; //Name of Custom Field to specify event date.

    global $wpdb;
    $today = date("Y-m-d",time());
    $now = gmdate("Y-m-d H:i:s",time());
    $request = "SELECT ID, post_title, STR_TO_DATE(meta_value, '%Y-%m-%d') AS event_date FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON ID = post_id WHERE post_status = 'publish' AND meta_key = '$event_date_key' ";
    $request .= "AND STR_TO_DATE(meta_value, '%Y-%m-%d') >= '$today' ";
    if($hide_pass_post) $request .= "AND post_password ='' ";
    $request .= "AND post_type='post' ";
    $request .= "AND post_date_gmt < '$now' ORDER BY event_date LIMIT $no_posts";
    $posts = $wpdb->get_results($request);
    $output = '';
    if($posts) {
      foreach ($posts as $post) {
        $post_title = stripslashes($post->post_title);
        $permalink = get_permalink($post->ID);
        $event_date = date($date_format, strtotime($post->event_date));
        $output .= '<li>' . $event_date . ' <a href="' . $permalink . '">' . htmlspecialchars($post_title) . '</a>';
        $output .= '</li>';
      }
    } else {
      $output .= '<li>No events</li>';
    }
    echo $before_widget;
    echo "<h2>$title</h2>" . '<ul>' . $output . '</ul>';
    echo $after_widget;
  }

  function wp_widget_simple_upcoming_events_control() {
    $options = $newoptions = get_option('widget_simple_upcoming_events');
    if ( isset($_POST["simple_upcoming_events-submit"]) ) {
      $newoptions['title'] = strip_tags(stripslashes($_POST["simple_upcoming_events-title"]));
      $newoptions['date_format'] = strip_tags(stripslashes($_POST["simple_upcoming_events-date_format"]));
    }
    if ( $options != $newoptions ) {
      $options = $newoptions;
      update_option('widget_simple_upcoming_events', $options);
    }
    $title = attribute_escape($options['title']);
    $date_format = attribute_escape($options['date_format']);
?>
            <p><label for="simple_upcoming_events-title"><?php _e('Title:'); ?> <input class="widefat" id="simple_upcoming_events-title" name="simple_upcoming_events-title" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="simple_upcoming_events-date_format"><?php _e('Date Format:'); ?> <a href="http://us.php.net/date"><?php _e('reference'); ?></a><input class="widefat" id="simple_upcoming_events-date_format" name="simple_upcoming_events-date_format" type="text" value="<?php echo $date_format; ?>" /></label></p>
            <input type="hidden" id="simple_upcoming_events-submit" name="simple_upcoming_events-submit" value="1" />
<?php
  }
  $widget_ops = array('classname' => 'widget_simple_upcoming_events', 'description' => __( 'List of posts for upcoming events' ) );
  wp_register_sidebar_widget('simple_upcoming_events', __('Simple Upcoming Events'), 'wp_widget_simple_upcoming_events', $widget_ops);
  wp_register_widget_control('simple_upcoming_events', __('Simple Upcoming Events'), 'wp_widget_simple_upcoming_events_control' );
}
add_action('init', wp_widget_simple_upcoming_events_register);
?>
