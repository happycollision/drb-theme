<?php
/* ------------------- THEME FORCE ---------------------- */

/*
 * EVENTS FUNCTION (CUSTOM POST TYPE) - GPL & all that good stuff obviously...
 *
 * If you intend to use this, please:
 * -- Amend your paths (CSS, JS, Images, etc.)
 * -- Rename functions, unless you're down with the force ;)
 *
 * This is not a plug-in on purpose, it's meant to be it's on file within your theme.
 * http://www.noeltock.com/web-design/wordpress/custom-post-types-slides-pt1/
 */


// 0. Base

add_action('admin_init', 'happycol_functions_css');

function happycol_functions_css() {
	wp_enqueue_style('happycol-functions-css', get_bloginfo('template_directory') . '/css/happycol-functions.css');
}

// No media in slide descriptions, please
add_action('admin_init', 'remove_all_media_buttons');
function remove_all_media_buttons() {
	if($_GET['post_type']=='happycol_slides'){
		remove_all_actions('media_buttons');
	}
}

// 1. Custom Post Type Registration (Slides)

add_action( 'init', 'create_slide_postype' );

function create_slide_postype() {

$labels = array(
    'name' => _x('Slides', 'post type general name'),
    'singular_name' => _x('Slide', 'post type singular name'),
    'add_new' => _x('Add New', 'slides'),
    'add_new_item' => __('Add New Slide'),
    'edit_item' => __('Edit Slide'),
    'new_item' => __('New Slide'),
    'view_item' => __('View Slide'),
    'search_items' => __('Search Slides'),
    'not_found' =>  __('No slides found'),
    'not_found_in_trash' => __('No slides found in Trash'),
    'parent_item_colon' => '',
);

$args = array(
    'label' => __('Slides'),
    'labels' => $labels,
    'public' => true,
    'can_export' => true,
    'show_ui' => true,
    '_builtin' => false,
    '_edit_link' => 'post.php?post=%d', // ?
    'capability_type' => 'post',
    //'menu_icon' => get_bloginfo('template_url').'/images/slide_16.png',
    'hierarchical' => false,
    'rewrite' => array( "slug" => "slides" ),
    'supports'=> array('title', 'thumbnail', 'excerpt') ,
    'show_in_nav_menus' => true
);

register_post_type( 'happycol_slides', $args);

}

// 2. Custom Taxonomy Registration (Slide Types)


// 3. Show Columns

add_filter ("manage_edit-happycol_slides_columns", "happycol_slides_edit_columns");
add_action ("manage_posts_custom_column", "happycol_slides_custom_columns");

function happycol_slides_edit_columns($columns) {

    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Slide",
        "happycol_col_sl_thumb" => "Thumbnail",
        "happycol_col_sl_desc" => "Description"
        );

    return $columns;

}

function happycol_slides_custom_columns($column) {

    global $post;
    $custom = get_post_custom();
    switch ($column)

        {
            case "happycol_col_sl_thumb":
                // - show thumb -
                $post_image_id = get_post_thumbnail_id(get_the_ID());
                if ($post_image_id) {
                    $thumbnail = wp_get_attachment_image_src( $post_image_id, 'slide', false);
                    if ($thumbnail) (string)$thumbnail = $thumbnail[0];
                    echo '<img src="';
                    echo bloginfo('template_url');
                    echo '/timthumb/timthumb.php?src=';
                    echo $thumbnail;
                    echo '&h=60&w=120&zc=3" alt="" />';
                    //echo '" alt="" />';
                }
            break;
            case "happycol_col_sl_desc";
                global $post;
                echo $post->post_excerpt;
            break;

        }
}

// 3a. Sort the column headers


// 4. Show Meta-Box


// 5. Save Data


// 6. Customize Update Messages

add_filter('post_updated_messages', 'slides_updated_messages');

function slides_updated_messages( $messages ) {

  global $post, $post_ID;

  $messages['happycol_slides'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Slide updated. <a href="%s">View item</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Slide updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Slide restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Slide published. <a href="%s">View slide</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Slide saved.'),
    8 => sprintf( __('Slide submitted. <a target="_blank" href="%s">Preview slide</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Slide scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview slide</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Slide draft updated. <a target="_blank" href="%s">Preview slide</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

// 7. JS Datepicker UI


?>