<?php
locate_template('slides_post-type.php', true);
locate_template('praise_quotes_post-type.php', true);
get_template_part('cpt_slides');
add_theme_support( 'post-thumbnails' );
add_image_size('slide',540,210,true);

/**
 * functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, happycol_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 584;

/**
 * Tell WordPress to run happycol_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'happycol_setup' );

if ( ! function_exists( 'happycol_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override happycol_setup() in a child theme, add your own happycol_setup to your child theme's
 * functions.php file.
 *
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Eleven 1.0
 */
function happycol_setup() {

	// Allow JQuery in theme templates
	function happycol_scripts_method() {
		wp_enqueue_script('jquery');            
		wp_enqueue_script('jquery-ui-core');            
	}    
	 
	add_action('init', 'happycol_scripts_method');
	
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'Main Nav', __( 'Top & Bottom', 'happycol' ) );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

	// Add support for custom backgrounds
	add_custom_background();

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );
	
	//allows the sidebar to be accessed via URL (site.com/sidebar/ or site.com/?post_type=sidebar) for use in responsive design. Must be used in conjunction with archive-sidebar.php
	add_action( 'init', 'register_sidebar_url_type' );
	function register_sidebar_url_type() {
	  $args = array(
		'label' => 'sidebar',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => false, 
		'show_in_menu' => false, 
		'rewrite' => true,
		'has_archive' => true
	  ); 
	  register_post_type('sidebar',$args);
	}
	
	//register a sidebar for widgets
	register_sidebar(array(
		'name'          => 'Main Sidebar',
		'id'            => 'sidebar-$i',
		'description'   => 'The main sidebar displayed on the right',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>' ));
	

	// The next four constants set how Twenty Eleven supports custom headers.

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '000' );

	// By leaving empty, we allow for random image rotation.
	define( 'HEADER_IMAGE', '' );

	// The height and width of your custom header.
	// Add a filter to happycol_header_image_width and happycol_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'happycol_header_image_width', 1000 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'happycol_header_image_height', 288 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Add Twenty Eleven's custom image sizes
	add_image_size( 'large-feature', HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true ); // Used for large feature (header) images
	add_image_size( 'small-feature', 500, 300 ); // Used for featured posts if a large-feature doesn't exist

	// Turn on random header image rotation by default.
	add_theme_support( 'custom-header', array( 'random-default' => true ) );
	
	// Filter out the default wordpress description, in case we forget to change it
	if( ! function_exists( 'happy_col_bloginfo' ) ) {
		function happy_col_bloginfo( $value_from_bloginfo ) {
			switch ($value_from_bloginfo):
				case 'Just another WordPress site':
					return;
					break;
				case 'Just another WordPress blog':
					return;
					break;
				default:
					return $value_from_bloginfo;
					break;
			endswitch;
		}
	}
	add_filter( 'bloginfo', 'happy_col_bloginfo' );

	// Allow or disallow comments throughout the site.
	function happycol_allow_comments() {
		return false;  // change to true for comments
	}
	
	// Use above function to completely shut off comments (including icons)
	if( ! happycol_allow_comments() ) {
		function happycol_override_comments() {
			return false;
		}
		add_filter('comments_open','happycol_override_comments');
	}

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	// commented out, to be used only for clients who need custom headers.
	/*
	register_default_headers( array(
		'hanoi' => array(
			'url' => '%s/images/headers/hanoi.jpg',
			'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
			// translators: header image description 
			'description' => __( 'Hanoi Plant', 'happycol' )
		)
	) );
	*/
}
endif; // happycol_setup

function echo_image_url($post_id = null, $size = 'medium'){
	if($post_id == null){
		global $post;
		$post_id = $post->ID;
	}
	if(has_post_thumbnail( $post_id )){
		$thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),$size);
		$url= $thumb_image[0];
		echo $url;
	}
}


/**
 * Sets the post excerpt length to 30 words.
 */
function happycol_excerpt_length() {
	return 30;
}
add_filter( 'excerpt_length', 'happycol_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function happycol_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' .'Continue reading <span class="meta-nav">&rarr;</span>' . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and happycol_continue_reading_link().
 */
function happycol_auto_excerpt_more( $more ) {
	return ' &hellip;' . happycol_continue_reading_link();
}
add_filter( 'excerpt_more', 'happycol_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 */
function happycol_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= happycol_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'happycol_custom_excerpt_more' );

/**
 * Get wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function happycol_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'happycol_page_menu_args' );

/**
 * Register our sidebars and widgetized areas.
 */
function happycol_widgets_init() {
	/*examples from Twenty Eleven:
	register_widget( 'Twenty_Eleven_Ephemera_Widget' );

	register_sidebar( array(
		'name' => 'Main Sidebar',
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	*/
}
add_action( 'widgets_init', 'happycol_widgets_init' );

/**
 * Display navigation to next/previous pages when applicable
 */
function happycol_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>" class="clearfix">
			<h3 class="visuallyhidden"><?php echo 'Post navigation'; ?></h3>
			<span class="nav-previous"><?php previous_posts_link( '<span class="meta-nav">&larr;</span> Newer posts' ); ?></span>
			<span class="nav-next"><?php next_posts_link( 'Older posts <span class="meta-nav">&rarr;</span>' ); ?></span>
		</nav><!-- nav#<?php echo $nav_id; ?> -->
	<?php endif;
}

/**
 * Return the URL for the first link found in the post content.
 */
function happycol_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

if ( ! function_exists( 'happycol_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own happycol_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Eleven 1.0
 */
function happycol_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'happycol' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'happycol' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'happycol' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'happycol' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'happycol' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'happycol' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'happycol' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for happycol_comment()

if ( ! function_exists( 'happycol_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function happycol_posted_on() {
	printf( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
		//esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		//sprintf( esc_attr__( 'View all posts by %s', 'happycol' ), get_the_author() ),
		//esc_html( get_the_author() )
	);
}
endif;

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 */
function happycol_body_classes( $classes ) {

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';

	return $classes;
}
add_filter( 'body_class', 'happycol_body_classes' );


/* * * * * 
    The data row-a-lizer 1.1
	by Double D Photo & Design
	
	
*/
//Define terms used for shortcode. For example, if you use the values below,
// you would want your post to look like this in the editor:
// 		[resume][cols title="something" style="class_name"] My Content [/cols][/resume]

// Redefine as required.
$container_shortcode_call = 'resume';
$rowalizer_shortcode_call = 'cols';
$section_title = 'title';
$class_additions = 'style';

//Pretty HTML: comment out the next lines for gross, but concise code.
$DD_line_breaks = "\n";
$DD_tabs = "    ";



//  ******* leave the rest alone unless you know what you are doing... not that I did...

//Will normalize line breaks from user entry
function DD_line_breaks($atts,$content = null) {
	$output = str_replace(array('<p>','<br />','<br>'),"\n",$content);
	$output = str_replace('</p>','',$output);
	while (stristr($output,"\n\n")){
		$output = str_replace("\n\n","\n",$output);
		}
	return $output;
}


//The row-a-lizer shortcodes
add_shortcode("$container_shortcode_call", 'rowalizer_container_shortcode');
add_shortcode("$rowalizer_shortcode_call", 'rowalizer_shortcode');

function rowalizer_container_shortcode($atts,$content = null) {
	global $container_shortcode_call, $DD_line_breaks, $DD_tabs;
	$r = $DD_line_breaks;
	$t = $DD_tabs;
	$output = $r.'<div class="'.$container_shortcode_call.'">' . do_shortcode($content) .$t.'<div class="anchor"></div>'.$r.$t.'</div><!--'.$container_shortcode_call.'-->';
	$output = str_replace(array('<p>','</p>'),'',$output);
	return $output;
}
if(!function_exists('ddprint')){function ddprint($me,$return=false){
	$output = print_r($me,true);
	if($return){
		return $output;
	}
	echo '<pre>'.$output.'</pre>';
}}
function rowalizer_shortcode($atts,$content = null) {
	global  $section_title,
			$class_additions, 
			$container_shortcode_call, 
			$DD_line_breaks, 
			$DD_tabs; 
	
	$r = $DD_line_breaks;
	$t = $DD_tabs;
	
	static $col_instance = 0;
	$col_instance++;

	extract(shortcode_atts(array(
		$section_title		=> '',
		$class_additions	=> ''
	), $atts));
	
	//add a break to begining for use later
	$begining = "\n";
	$content = $begining . $content;
	
	//Turn all forms of line break into a carriage return 
	//and turn all multiple carriage returns into SINGLE carriage return
	$content = DD_line_breaks('',$content);
	
	//Get rid of that first carriage return... and the last one
	$content = trim($content);
	
	//Break into rows
	$content = explode("\n", $content);

	//Break into columns (separated from rows currently)
	$i=0; while($content[$i]) {
		$content[$i] = explode("|", $content[$i]);
		$i++;
	}
	
	//creating a multi-dimensional foreach. By specifying keys ($rows and $cols) I retain NULL values.
	foreach ($content as $rows=>$row) {
		foreach ($row as $cols=>$cell) {
			if($tot_rows<$rows)$tot_rows = $rows;
			if($tot_cols<$cols)$tot_cols = $cols;
		}
	}
	++$tot_rows; ++$tot_cols;
	//echo "number of rows is ".$tot_rows.", and number of cols is ".$tot_cols.".<br/>";
	
	$row=0;
	$cell=0;
	while($tot_rows>0){
		$temp_tot_cols = $tot_cols;
		$output .=  $t . '<div class="row row-'.++$row.(($row%2) ? ' odd' : ' even').'">' . $r;
		while($tot_cols>0){
			$cell = ++$cell;
			//is there content in the cell?
			if( trim($content[($row-1)][($cell-1)]) ){
				$contents = trim($content[($row-1)][($cell-1)]);
				$css_empty = '';
			}else{
				$contents = '&nbsp;';
				$css_empty = ' empty ';
			}
			$output .= $t.$t. '<span class="cell cell-'.$cell.(($cell%2) ? ' odd' : ' even').$css_empty.'">'.
							  $contents.
							  '</span>'.$r;
			$tot_cols--;
		}
		$output .= $t.$t.'<div class="anchor"></div>'.$r;
		$output .= $t.$t.'</div><!-- row-'.$row.' -->'.$r;
		$tot_rows--;
		$tot_cols = $temp_tot_cols;
		$cell=0;
	}
	
	$output = str_replace(array('<p>','</p>'),'',$output);
	if($atts[$section_title]){
		$safe_title = str_replace(' ','_',strtolower(trim($atts[$section_title])));
		$preCols =  '<div class="section section-'.$col_instance.' section_title-'.$safe_title.' '.$atts[$class_additions].'">' . $r . 
					$t . '<div class="section_heading"><span>' . $atts[$section_title] . '</span></div>' . $r .
					$t . '<div class="section_content">' . $r;
	}else{
		$preCols =  '<div class="section section-'.$col_instance.' '.$atts[$class_additions].'">' . $r;
	}
	$postCols = $t . '</div><!--section'.$col_instance.'-->' . $r .
			$t . '</div><!--section_content-->' . $r;

	return $preCols . $output . $postCols;
}
// End rowalizer

/**
 * TinyMCE customization
 * via http://blog.estherswhite.net/2009/11/customizing-tinymce/
 */
 
//add styles
function childtheme_mce_css($wp) {
	return $wp .= ',' . get_bloginfo('stylesheet_directory') . '/css/mce.css';
}
add_filter( 'mce_css', 'childtheme_mce_css' );

//customize buttons
function childtheme_mce_btns2($orig) {
	return array(/*'formatselect', */'styleselect', '|', 'pastetext', 'pasteword', 'removeformat', '|', 'charmap', '|', 'undo', 'redo', 'wp_help', 'mymenubutton' );
}
add_filter( 'mce_buttons_2', 'childtheme_mce_btns2', 999 );

//specify styles instead of import
function childtheme_tiny_mce_before_init( $init_array ) {
	$init_array['theme_advanced_styles'] = 
				"Show Title=show_title" // add ";" in string if adding more styles
				//"2nd Option=classname2;" .
				//"3rd Option=classname3"
				;
	$init_array['theme_advanced_blockformats'] = "p,h4,h5,h6";
				
				
	return $init_array;
}
add_filter( 'tiny_mce_before_init', 'childtheme_tiny_mce_before_init' );

//end TinyMCE stuff


/**
 * Here are some customizations that change text output via the gettext filter.
 * This was intended for translating themes to other languages, but why not
 * use it for more customization?
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 *
 */
add_filter( 'gettext', 'happycol_change_excerpt_name', 20, 3 );
function happycol_change_excerpt_name( $translated_text, $text, $domain ) {

    if( $_GET['post_type'] == 'happycol_slides' ) {

        switch ( $translated_text ) {

            case 'Excerpt' :

                $translated_text = 'Caption';
                break;

            case 'Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="http://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a>' :

                $translated_text = 'This will override any caption you assigned the picture when you first uploaded it into WordPress.';
                break;

        }

    }

    return $translated_text;
}


