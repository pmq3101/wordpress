<?php

/**
 * Theme Palace functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Theme Palace
 * @subpackage Great News
 * @since Great News 1.0.0
 */

if (!function_exists('great_news_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function great_news_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Theme Palace, use a find and replace
		 * to change 'greatnews' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('greatnews');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		add_theme_support('responsive-embeds');

		add_theme_support('register_block_pattern');

		add_theme_support('register_block_style');

		// Enable support for footer widgets.
		add_theme_support('footer-widgets', 5);

		// Load Footer Widget Support.
		require_if_theme_supports('footer-widgets', get_template_directory() . '/inc/footer-widgets.php');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');
		set_post_thumbnail_size(600, 450, true);

		// Set the default content width.
		$GLOBALS['content_width'] = 525;

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' 	=> esc_html__('Primary', 'greatnews'),
			'secondary' => esc_html__('Secondary', 'greatnews'),
			'social' 	=> esc_html__('Social', 'greatnews'),
		));

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		));

		// Set up the WordPress core custom background feature.
		add_theme_support('custom-background', apply_filters('great_news_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));

		// This setup supports logo, site-title & site-description
		add_theme_support('custom-logo', array(
			'height'      => 70,
			'width'       => 120,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array('site-title', 'site-description'),
		));

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/*
		 * This theme styles the visual editor to resemble the theme style,
		 * specifically font, colors, icons, and column width.
		 */
		add_editor_style(array('/assets/css/editor-style' . great_news_min() . '.css', great_news_fonts_url()));

		// Gutenberg support
		add_theme_support('editor-color-palette', array(
			array(
				'name' => esc_html__('Blue', 'greatnews'),
				'slug' => 'blue',
				'color' => '#2c7dfa',
			),
			array(
				'name' => esc_html__('Green', 'greatnews'),
				'slug' => 'green',
				'color' => '#07d79c',
			),
			array(
				'name' => esc_html__('Orange', 'greatnews'),
				'slug' => 'orange',
				'color' => '#ff8737',
			),
			array(
				'name' => esc_html__('Black', 'greatnews'),
				'slug' => 'black',
				'color' => '#2f3633',
			),
			array(
				'name' => esc_html__('Grey', 'greatnews'),
				'slug' => 'grey',
				'color' => '#82868b',
			),
		));

		add_theme_support('align-wide');
		add_theme_support('editor-font-sizes', array(
			array(
				'name' => esc_html__('small', 'greatnews'),
				'shortName' => esc_html__('S', 'greatnews'),
				'size' => 12,
				'slug' => 'small'
			),
			array(
				'name' => esc_html__('regular', 'greatnews'),
				'shortName' => esc_html__('M', 'greatnews'),
				'size' => 16,
				'slug' => 'regular'
			),
			array(
				'name' => esc_html__('larger', 'greatnews'),
				'shortName' => esc_html__('L', 'greatnews'),
				'size' => 36,
				'slug' => 'larger'
			),
			array(
				'name' => esc_html__('huge', 'greatnews'),
				'shortName' => esc_html__('XL', 'greatnews'),
				'size' => 48,
				'slug' => 'huge'
			)
		));
		add_theme_support('editor-styles');
		add_theme_support('wp-block-styles');
	}
endif;
add_action('after_setup_theme', 'great_news_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function great_news_content_width()
{

	$content_width = $GLOBALS['content_width'];


	$sidebar_position = great_news_layout();
	switch ($sidebar_position) {

		case 'no-sidebar':
			$content_width = 1170;
			break;

		case 'left-sidebar':
		case 'right-sidebar':
			$content_width = 819;
			break;

		default:
			break;
	}

	if (!is_active_sidebar('sidebar-1')) {
		$content_width = 1170;
	}

	/**
	 * Filter Great News content width of the theme.
	 *
	 * @since Great News 1.0.0
	 *
	 * @param int $content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters('great_news_content_width', $content_width);
}
add_action('template_redirect', 'great_news_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function great_news_widgets_init()
{
	register_sidebar(array(
		'name'          => esc_html__('Sidebar', 'greatnews'),
		'id'            => 'sidebar-1',
		'description'   => esc_html__('Add widgets here.', 'greatnews'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Main Post Wrapper Left Sidebar', 'greatnews'),
		'id'            => 'left-main-post-wrapper',
		'description'   => esc_html__('Add widgets here for left main post section.', 'greatnews'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget-header"><h2 class="widget-title">',
		'after_title'   => '</h2></div>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Main Post Wrapper Right Sidebar', 'greatnews'),
		'id'            => 'right-main-post-wrapper',
		'description'   => esc_html__('Add widgets here for right main post section.', 'greatnews'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget-header"><h2 class="widget-title">',
		'after_title'   => '</h2></div>',
	));

	register_sidebars(4, array(
		'name'          => esc_html__('Optional Sidebar %d', 'greatnews'),
		'id'            => 'optional-sidebar',
		'description'   => esc_html__('Add widgets here.', 'greatnews'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
}
add_action('widgets_init', 'great_news_widgets_init');


if (!function_exists('great_news_fonts_url')) :
	/**
	 * Register Google fonts
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function great_news_fonts_url()
	{
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';

		/* translators: If there are characters in your language that are not supported by Lato, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Lato font: on or off', 'greatnews')) {
			$fonts[] = 'Lato:400,700';
		}

		/* translators: If there are characters in your language that are not supported by Lora, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Lora font: on or off', 'greatnews')) {
			$fonts[] = 'Lora:400,700';
		}

		// Header Options

		/* translators: If there are characters in your language that are not supported by Rajdhani, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Rajdhani font: on or off', 'greatnews')) {
			$fonts[] = 'Rajdhani';
		}

		/* translators: If there are characters in your language that are not supported by Cherry Swash, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Cherry Swash font: on or off', 'greatnews')) {
			$fonts[] = 'Cherry Swash';
		}

		/* translators: If there are characters in your language that are not supported by Philosopher, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Philosopher font: on or off', 'greatnews')) {
			$fonts[] = 'Philosopher';
		}

		/* translators: If there are characters in your language that are not supported by Slabo 27px, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Slabo 27px font: on or off', 'greatnews')) {
			$fonts[] = 'Slabo 27px';
		}

		/* translators: If there are characters in your language that are not supported by Dosis, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Dosis font: on or off', 'greatnews')) {
			$fonts[] = 'Dosis';
		}

		// Body Options

		/* translators: If there are characters in your language that are not supported by |News Cycle, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'News Cycle font: on or off', 'greatnews')) {
			$fonts[] = 'News Cycle';
		}

		/* translators: If there are characters in your language that are not supported by Pontano Sans, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Pontano Sans font: on or off', 'greatnews')) {
			$fonts[] = 'Pontano Sans';
		}

		/* translators: If there are characters in your language that are not supported by Gudea, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Gudea font: on or off', 'greatnews')) {
			$fonts[] = 'Gudea';
		}

		/* translators: If there are characters in your language that are not supported by Quattrocento, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Quattrocento font: on or off', 'greatnews')) {
			$fonts[] = 'Quattrocento';
		}

		/* translators: If there are characters in your language that are not supported by Khand, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Khand font: on or off', 'greatnews')) {
			$fonts[] = 'Khand';
		}

		$query_args = array(
			'family' => urlencode(implode('|', $fonts)),
			'subset' => urlencode($subsets),
		);

		if ($fonts) {
			$fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
		}

		return esc_url_raw($fonts_url);
	}
endif;

/**
 * Add preconnect for Google Fonts.
 *
 * @since Great News 1.0.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function great_news_resource_hints($urls, $relation_type)
{
	if (wp_style_is('great-news-fonts', 'queue') && 'preconnect' === $relation_type) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter('wp_resource_hints', 'great_news_resource_hints', 10, 2);

/**
 * Enqueue scripts and styles.
 */
function great_news_scripts()
{
	$options = great_news_get_theme_options();
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style('great-news-fonts', wptt_get_webfont_url(great_news_fonts_url()), array(), null);

	// slick
	wp_enqueue_style('slick', get_template_directory_uri() . '/assets/css/slick' . great_news_min() . '.css');

	// slick theme
	wp_enqueue_style('slick-theme', get_template_directory_uri() . '/assets/css/slick-theme' . great_news_min() . '.css');

	// blocks
	wp_enqueue_style('great-news-blocks', get_template_directory_uri() . '/assets/css/blocks' . great_news_min() . '.css');

	wp_enqueue_style('great-news-style', get_stylesheet_uri());


	// Load the html5 shiv.
	wp_enqueue_script('great-news-html5', get_template_directory_uri() . '/assets/js/html5' . great_news_min() . '.js', array(), '3.7.3');
	wp_script_add_data('great-news-html5', 'conditional', 'lt IE 9');

	wp_enqueue_script('great-news-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix' . great_news_min() . '.js', array(), '20160412', true);

	wp_enqueue_script('great-news-navigation', get_template_directory_uri() . '/assets/js/navigation' . great_news_min() . '.js', array(), '20151215', true);

	$great_news_l10n = array(
		'quote'          => great_news_get_svg(array('icon' => 'quote-right')),
		'expand'         => esc_html__('Expand child menu', 'greatnews'),
		'collapse'       => esc_html__('Collapse child menu', 'greatnews'),
		'icon'           => great_news_get_svg(array('icon' => 'down', 'fallback' => true)),
	);

	wp_localize_script('great-news-navigation', 'great_news_l10n', $great_news_l10n);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	wp_enqueue_script('jquery-slick', get_template_directory_uri() . '/assets/js/slick' . great_news_min() . '.js', array('jquery'), '', true);

	wp_enqueue_script('theia-sticky', get_template_directory_uri() . '/assets/js/theia-sticky-sidebar' . great_news_min() . '.js', array('jquery'), '', true);

	wp_enqueue_script('packery-pkgd', get_template_directory_uri() . '/assets/js/packery.pkgd' . great_news_min() . '.js', array('jquery'), '', true);

	wp_enqueue_script('great-news-custom', get_template_directory_uri() . '/assets/js/custom' . great_news_min() . '.js', array('jquery'), '20151215', true);

	if ('infinite' == $options['pagination_type']) {
		// infinite scroll js
		wp_enqueue_script('great-news-infinite-scroll', get_template_directory_uri() . '/assets/js/infinite-scroll' . great_news_min() . '.js', array('jquery'), '', true);
	}
}
add_action('wp_enqueue_scripts', 'great_news_scripts');

/**
 * Enqueue editor styles for Gutenberg
 *
 * @since Great News 1.0.0
 */
function great_news_block_editor_styles()
{
	// Block styles.
	wp_enqueue_style('great-news-block-editor-style', get_theme_file_uri('/assets/css/editor-blocks' . great_news_min() . '.css'));
	// Add custom fonts.
	wp_enqueue_style('great-news-fonts', wptt_get_webfont_url(great_news_fonts_url()), array(), null);
}
add_action('enqueue_block_editor_assets', 'great_news_block_editor_styles');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load core file
 */
require get_template_directory() . '/inc/core.php';

function add_custom_birth_date_column_to_wp_users()
{
	global $wpdb;
	$column_name = 'birth_date';
	$table_name = $wpdb->prefix . 'users';

	// Check if the column already exists
	$column_exists = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE '{$column_name}'");

	if (empty($column_exists)) {
		// Add the new column
		$wpdb->query("ALTER TABLE {$table_name} ADD {$column_name} DATE");
	}
}
add_action('init', 'add_custom_birth_date_column_to_wp_users');

function um_save_birth_date_to_wp_users_table($user_id)
{
	if (isset($_POST['birth_date-9']) && !empty($_POST['birth_date-9'])) {
		$birth_date = sanitize_text_field($_POST['birth_date-9']);
		error_log("Birth Date after getting from POST: " . $birth_date);
		// Update the custom expected_output column in the wp_users table
		global $wpdb;
		$wpdb->update(
			$wpdb->users,
			array('birth_date' => $birth_date),
			array('ID' => $user_id)
		);

		// Log success message
		error_log("Birth Date field updated successfully for user ID: " . $user_id);
	} else {
		error_log("Birth Date field is not set or empty.");
	}

	// Update the birth_date field if present in $_POST
	if (isset($_POST['birth_date-9']) && !empty($_POST['birth_date-9'])) {
		$birth_date = sanitize_text_field($_POST['birth_date-9']);

		error_log("Birth Date after getting from POST: " . $birth_date);

		// Update the birth_date column in the wp_users table
		$wpdb->update(
			$wpdb->users,
			array('birth_date' => $birth_date),
			array('ID' => $user_id)
		);

		// Log success message
		error_log("Birth date updated successfully for user ID: " . $user_id);
	} else {
		error_log("Birth date is not set or empty.");
	}
}

function add_custom_expected_output_column_to_wp_users()
{
	global $wpdb;
	$column_name = 'expected_output';
	$table_name = $wpdb->prefix . 'users';

	// Check if the column already exists
	$column_exists = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE '{$column_name}'");

	if (empty($column_exists)) {
		// Add the new column
		$wpdb->query("ALTER TABLE {$table_name} ADD {$column_name} VARCHAR(255)");
	}
}
add_action('init', 'add_custom_expected_output_column_to_wp_users');


function um_save_expected_output_to_wp_users_table($user_id)
{
	if (isset($_POST['expected_output-9']) && !empty($_POST['expected_output-9'])) {
		$expected_output = sanitize_text_field($_POST['expected_output-9']);
		$birth_date = sanitize_text_field($_POST['birth_date-9']);
		// Update the custom expected_output column in the wp_users table
		global $wpdb;
		$wpdb->update(
			$wpdb->users,
			array('expected_output' => $expected_output),
			array('ID' => $user_id)
		);

		// Log success message
		error_log("Expected Output field updated successfully for user ID: " . $user_id);
	} else {
		error_log("Expected Output field is not set or empty.");
	}
}

add_action('um_registration_complete', 'um_save_birth_date_to_wp_users_table', 10, 1);
add_action('um_registration_complete', 'um_save_expected_output_to_wp_users_table', 10, 1);
add_action('um_user_profile_update', 'um_save_expected_output_to_wp_users_table', 10, 1);

function add_phone_number_column_to_wp_users()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'users';

	// Check if the phone_number column already exists
	if (!$wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'mobile_number'")) {
		// Add the phone_number column
		$wpdb->query("ALTER TABLE {$table_name} ADD mobile_number VARCHAR(20)");
	}
}
add_action('init', 'add_phone_number_column_to_wp_users');

function um_save_phone_number_to_wp_users_table($user_id)
{
	global $wpdb;

	// Check and sanitize phone_number
	if (isset($_POST['mobile_number-9']) && !empty($_POST['mobile_number-9'])) {
		$phone_number = sanitize_text_field($_POST['mobile_number-9']);
		error_log("Phone Number after getting from POST: " . $phone_number);

		// Update the phone_number column in the wp_users table
		$wpdb->update(
			$wpdb->users,
			array('mobile_number' => $phone_number),
			array('ID' => $user_id)
		);

		// Log success message
		error_log("Phone Number updated successfully for user ID: " . $user_id);
	} else {
		error_log("Phone Number field is not set or empty.");
	}
}

add_action('um_registration_complete', 'um_save_phone_number_to_wp_users_table', 10, 1);
add_action('um_after_user_updated', 'um_save_phone_number_to_wp_users_table', 10, 1);

function add_school_name_column_to_wp_users()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'users';

	// Check if the school_name column already exists
	if (!$wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'school_name'")) {
		// Add the school_name column
		$wpdb->query("ALTER TABLE {$table_name} ADD school_name VARCHAR(50)");
	}
}
add_action('init', 'add_school_name_column_to_wp_users');

function um_save_school_name_to_wp_users_table($user_id)
{
	global $wpdb;

	// Check and sanitize school_name
	if (isset($_POST['school_name-9']) && !empty($_POST['school_name-9'])) {
		$school_name = sanitize_text_field($_POST['school_name-9']);
		error_log("school_name after getting from POST: " . $school_name);

		// Update the phone_number column in the wp_users table
		$wpdb->update(
			$wpdb->users,
			array('school_name' => $school_name),
			array('ID' => $user_id)
		);

		// Log success message
		error_log("school_name updated successfully for user ID: " . $user_id);
	} else {
		error_log("school_name field is not set or empty.");
	}
}

add_action('um_registration_complete', 'um_save_school_name_to_wp_users_table', 10, 1);
add_action('um_after_user_updated', 'um_save_school_name_to_wp_users_table', 10, 1);

function add_school_check_column_to_wp_users()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'users';

	// Check if the school_check column already exists
	if (!$wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'school_check'")) {
		// Add the school_check column
		$wpdb->query("ALTER TABLE {$table_name} ADD school_check VARCHAR(20)");
	}
}
add_action('init', 'add_school_check_column_to_wp_users');

function um_save_school_check_to_wp_users_table($user_id)
{
	global $wpdb;

	// Check and sanitize school_check
	if (isset($_POST['school_check']) && !empty($_POST['school_check'])) {
		$school_check = sanitize_text_field($_POST['school_check']);
		error_log("School Check after getting from POST: " . $school_check);

		// Update the school_check column in the wp_users table
		$wpdb->update(
			$wpdb->users,
			array('school_check' => $school_check),
			array('ID' => $user_id)
		);

		// Log success message
		error_log("School Check updated successfully for user ID: " . $user_id);
	} else {
		error_log("School Check field is not set or empty.");
	}
}

add_action('um_registration_complete', 'um_save_school_check_to_wp_users_table', 10, 1);
add_action('um_after_user_updated', 'um_save_school_check_to_wp_users_table', 10, 1);

function add_job_situation_column_to_wp_users()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'users';

	// Check if the job_situation column already exists
	if (!$wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'job_situation'")) {
		// Add the job_situation column
		$wpdb->query("ALTER TABLE {$table_name} ADD job_situation VARCHAR(20)");
	}
}
add_action('init', 'add_job_situation_column_to_wp_users');

function um_save_job_situation_to_wp_users_table($user_id)
{
	global $wpdb;

	// Check and sanitize job_situation
	if (isset($_POST['situation']) && !empty($_POST['situation'])) {
		$situation = sanitize_text_field($_POST['situation']);
		error_log("Job Situation after getting from POST: " . $situation);

		// Update the job_situation column in the wp_users table
		$wpdb->update(
			$wpdb->users,
			array('job_situation' => $situation),
			array('ID' => $user_id)
		);

		// Log success message
		error_log("Job Situation updated successfully for user ID: " . $user_id);
	} else {
		error_log("Job Situation field is not set or empty.");
	}
}

add_action('um_registration_complete', 'um_save_job_situation_to_wp_users_table', 10, 1);
add_action('um_after_user_updated', 'um_save_job_situation_to_wp_users_table', 10, 1);

function add_knowledge_level_column_to_wp_users()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'users';

	// Check if the knowledge_level column already exists
	if (!$wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'knowledge_level'")) {
		// Add the knowledge_level column
		$wpdb->query("ALTER TABLE {$table_name} ADD knowledge_level VARCHAR(20)");
	}
}
add_action('init', 'add_knowledge_level_column_to_wp_users');

function um_save_knowledge_level_to_wp_users_table($user_id)
{
	global $wpdb;

	// Check and sanitize knowledge_level
	if (isset($_POST['knowledge_level']) && !empty($_POST['knowledge_level'])) {
		$knowledge_level = sanitize_text_field($_POST['knowledge_level']);
		error_log("Knowledge Level after getting from POST: " . $knowledge_level);

		// Update the knowledge_level column in the wp_users table
		$wpdb->update(
			$wpdb->users,
			array('knowledge_level' => $knowledge_level),
			array('ID' => $user_id)
		);

		// Log success message
		error_log("Knowledge Level updated successfully for user ID: " . $user_id);
	} else {
		error_log("Knowledge Level field is not set or empty.");
	}
}

add_action('um_registration_complete', 'um_save_knowledge_level_to_wp_users_table', 10, 1);
add_action('um_after_user_updated', 'um_save_knowledge_level_to_wp_users_table', 10, 1);
