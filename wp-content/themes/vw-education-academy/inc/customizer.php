<?php
/**
* VW Education Academy Theme Customizer
*
* @package VW Education Academy
*/

/**
* Add postMessage support for site title and description for the Theme Customizer.
*
* @param WP_Customize_Manager $wp_customize Theme Customizer object.
*/
function vw_education_academy_custom_controls() {

load_template( trailingslashit( get_template_directory() ) . '/inc/custom-controls.php' );
}
add_action( 'customize_register', 'vw_education_academy_custom_controls' );

function vw_education_academy_customize_register( $wp_customize ) {

load_template( trailingslashit( get_template_directory() ) . '/inc/icon-picker.php' );

$wp_customize->get_setting( 'blogname' )->transport = 'postMessage'; 
$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

//Selective Refresh
$wp_customize->selective_refresh->add_partial( 'blogname', array( 
	'selector' => '.logo .site-title a', 
 	'render_callback' => 'vw_education_academy_customize_partial_blogname', 
)); 

$wp_customize->selective_refresh->add_partial( 'blogdescription', array( 
	'selector' => 'p.site-description', 
	'render_callback' => 'vw_education_academy_customize_partial_blogdescription', 
));

//add home page setting pannel
$VWEducationAcademyParentPanel = new VW_Education_Academy_WP_Customize_Panel( $wp_customize, 'vw_education_academy_panel_id', array(
	'capability' => 'edit_theme_options',
	'theme_supports' => '',
	'title' => esc_html__( 'VW Settings', 'vw-education-academy' ),
	'priority' => 10,
));

$wp_customize->add_panel( $VWEducationAcademyParentPanel );

$HomePageParentPanel = new VW_Education_Academy_WP_Customize_Panel( $wp_customize, 'vw_education_academy_homepage_panel', array(
	'title' => __( 'Homepage Settings', 'vw-education-academy' ),
	'panel' => 'vw_education_academy_panel_id',
));

$wp_customize->add_panel( $HomePageParentPanel );

//Topbar
$wp_customize->add_section( 'vw_education_academy_topbar', array(
	'title'      => __( 'Topbar Settings', 'vw-education-academy' ),
	'panel' => 'vw_education_academy_homepage_panel'
) );

// Header Background color
$wp_customize->add_setting('vw_education_academy_header_background_color', array(
	'default'           => '#ffbc00',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_header_background_color', array(
	'label'    => __('Header Background Color', 'vw-education-academy'),
	'section'  => 'header_image',
)));

$wp_customize->add_setting('vw_education_academy_header_img_position',array(
  'default' => 'center top',
  'transport' => 'refresh',
  'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_header_img_position',array(
	'type' => 'select',
	'label' => __('Header Image Position','vw-education-academy'),
	'section' => 'header_image',
	'choices' 	=> array(
		'left top' 		=> esc_html__( 'Top Left', 'vw-education-academy' ),
		'center top'   => esc_html__( 'Top', 'vw-education-academy' ),
		'right top'   => esc_html__( 'Top Right', 'vw-education-academy' ),
		'left center'   => esc_html__( 'Left', 'vw-education-academy' ),
		'center center'   => esc_html__( 'Center', 'vw-education-academy' ),
		'right center'   => esc_html__( 'Right', 'vw-education-academy' ),
		'left bottom'   => esc_html__( 'Bottom Left', 'vw-education-academy' ),
		'center bottom'   => esc_html__( 'Bottom', 'vw-education-academy' ),
		'right bottom'   => esc_html__( 'Bottom Right', 'vw-education-academy' ),
	),
));

$wp_customize->add_setting( 'vw_education_academy_search_hide_show',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
)); 
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_search_hide_show',array(
      'label' => esc_html__( 'Show / Hide Search','vw-education-academy' ),
      'section' => 'vw_education_academy_topbar'
)));

$wp_customize->add_setting('vw_education_academy_search_icon',array(
	'default'	=> 'fas fa-search',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_search_icon',array(
	'label'	=> __('Add Search Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_topbar',
	'setting'	=> 'vw_education_academy_search_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting('vw_education_academy_search_close_icon',array(
	'default'	=> 'fa fa-window-close',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_search_close_icon',array(
	'label'	=> __('Add Search Close Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_topbar',
	'setting'	=> 'vw_education_academy_search_close_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting('vw_education_academy_search_font_size',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_search_font_size',array(
	'label'	=> __('Search Font Size','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_topbar',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_search_padding_top_bottom',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_search_padding_top_bottom',array(
	'label'	=> __('Search Padding Top Bottom','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_topbar',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_search_padding_left_right',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_search_padding_left_right',array(
	'label'	=> __('Search Padding Left Right','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_topbar',
	'type'=> 'text'
));

$wp_customize->add_setting( 'vw_education_academy_search_border_radius', array(
	'default'              => "",
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_search_border_radius', array(
	'label'       => esc_html__( 'Search Border Radius','vw-education-academy' ),
	'section'     => 'vw_education_academy_topbar',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) );

//Selective Refresh
$wp_customize->selective_refresh->add_partial('vw_education_academy_header_call', array( 
	'selector' => '#topbar span', 
	'render_callback' => 'vw_education_academy_customize_partial_vw_education_academy_header_call', 
));

$wp_customize->add_setting('vw_education_academy_call_icon',array(
	'default'	=> 'fas fa-phone',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_call_icon',array(
	'label'	=> __('Add Phone Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_topbar',
	'setting'	=> 'vw_education_academy_call_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting('vw_education_academy_header_call',array(
	'default'=> '',
	'sanitize_callback'	=> 'vw_education_academy_sanitize_phone_number'
));
$wp_customize->add_control('vw_education_academy_header_call',array(
	'label'	=> __('Add Phone Number','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '+00 123 125 4568', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_topbar',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_email_icon',array(
	'default'	=> 'fas fa-envelope-open',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_email_icon',array(
	'label'	=> __('Add Email Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_topbar',
	'setting'	=> 'vw_education_academy_email_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting('vw_education_academy_header_email',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_email'
));
$wp_customize->add_control('vw_education_academy_header_email',array(
	'label'	=> __('Add Email Address','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'example@gmail.com', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_topbar',
	'type'=> 'text'
));

//Menus Settings
$wp_customize->add_section( 'vw_education_academy_menu_section' , array(
	'title' => __( 'Menus Settings', 'vw-education-academy' ),
	'panel' => 'vw_education_academy_homepage_panel'
) );

$wp_customize->add_setting('vw_education_academy_navigation_menu_font_size',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_navigation_menu_font_size',array(
	'label'	=> __('Menus Font Size','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_menu_section',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_navigation_menu_font_weight',array(
    'default' => 600,
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_navigation_menu_font_weight',array(
    'type' => 'select',
    'label' => __('Menus Font Weight','vw-education-academy'),
    'section' => 'vw_education_academy_menu_section',
    'choices' => array(
    	'100' => __('100','vw-education-academy'),
        '200' => __('200','vw-education-academy'),
        '300' => __('300','vw-education-academy'),
        '400' => __('400','vw-education-academy'),
        '500' => __('500','vw-education-academy'),
        '600' => __('600','vw-education-academy'),
        '700' => __('700','vw-education-academy'),
        '800' => __('800','vw-education-academy'),
        '900' => __('900','vw-education-academy'),
    ),
) );

// text trasform
$wp_customize->add_setting('vw_education_academy_menu_text_transform',array(
	'default'=> 'Uppercase',
	'sanitize_callback'	=> 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_menu_text_transform',array(
	'type' => 'radio',
	'label'	=> __('Menus Text Transform','vw-education-academy'),
	'choices' => array(
        'Uppercase' => __('Uppercase','vw-education-academy'),
        'Capitalize' => __('Capitalize','vw-education-academy'),
        'Lowercase' => __('Lowercase','vw-education-academy'),
    ),
	'section'=> 'vw_education_academy_menu_section',
));

$wp_customize->add_setting('vw_education_academy_menus_item_style',array(
    'default' => '',
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_menus_item_style',array(
    'type' => 'select',
    'section' => 'vw_education_academy_menu_section',
	'label' => __('Menu Item Hover Style','vw-education-academy'),
	'choices' => array(
        'None' => __('None','vw-education-academy'),
        'Zoom In' => __('Zoom In','vw-education-academy'),
    ),
) );

$wp_customize->add_setting('vw_education_academy_header_menus_color', array(
	'default'           => '',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_header_menus_color', array(
	'label'    => __('Menus Color', 'vw-education-academy'),
	'section'  => 'vw_education_academy_menu_section',
)));

$wp_customize->add_setting('vw_education_academy_header_menus_hover_color', array(
	'default'           => '',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_header_menus_hover_color', array(
	'label'    => __('Menus Hover Color', 'vw-education-academy'),
	'section'  => 'vw_education_academy_menu_section',
)));

$wp_customize->add_setting('vw_education_academy_header_submenus_color', array(
	'default'           => '',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_header_submenus_color', array(
	'label'    => __('Sub Menus Color', 'vw-education-academy'),
	'section'  => 'vw_education_academy_menu_section',
)));

$wp_customize->add_setting('vw_education_academy_header_submenus_hover_color', array(
	'default'           => '',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_header_submenus_hover_color', array(
	'label'    => __('Sub Menus Hover Color', 'vw-education-academy'),
	'section'  => 'vw_education_academy_menu_section',
)));

//Social links
$wp_customize->add_section(
	'vw_education_academy_social_links', array(
	'title'		=>	__('Social Links', 'vw-education-academy'),
	'priority'	=>	null,
	'panel'		=>	'vw_education_academy_homepage_panel'
));

$wp_customize->add_setting('vw_education_academy_social_icons',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_social_icons',array(
	'label' =>  __('Steps to setup social icons','vw-education-academy'),
	'description' => __('<p>1. Go to Dashboard >> Appearance >> Widgets</p>
		<p>2. Add Vw Social Icon Widget in Top Bar Social Media area.</p>
		<p>3. Add social icons url and save.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_social_links',
	'type'=> 'hidden'
));
$wp_customize->add_setting('vw_education_academy_social_icon_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_social_icon_btn',array(
	'description' => "<a target='_blank' href='". admin_url('widgets.php') ." '>Setup Social Icons</a>",
	'section'=> 'vw_education_academy_social_links',
	'type'=> 'hidden'
));

//Slider
$wp_customize->add_section( 'vw_education_academy_slidersettings' , array(
	'title'      => __( 'Slider Section', 'vw-education-academy' ),
	'description' => __('Free theme has 3 slides options, For unlimited slides and more options </br> <a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/themes/academic-wordpress-theme/">GET PRO</a>','vw-education-academy'),
	'panel' => 'vw_education_academy_homepage_panel'
) );

$wp_customize->add_setting( 'vw_education_academy_slider_hide_show',array(
  'default' => 0,
  'transport' => 'refresh',
  'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));  
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_slider_hide_show',array(
  'label' => esc_html__( 'Show / Hide Slider','vw-education-academy' ),
  'section' => 'vw_education_academy_slidersettings'
)));

$wp_customize->add_setting('vw_education_academy_slider_type',array(
    'default' => 'Default slider',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
) );
$wp_customize->add_control('vw_education_academy_slider_type', array(
    'type' => 'select',
    'label' => __('Slider Type','vw-education-academy'),
    'section' => 'vw_education_academy_slidersettings',
    'choices' => array(
        'Default slider' => __('Default slider','vw-education-academy'),
        'Advance slider' => __('Advance slider','vw-education-academy'),
    ),
));

$wp_customize->add_setting('vw_education_academy_advance_slider_shortcode',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_advance_slider_shortcode',array(
	'label'	=> __('Add Slider Shortcode','vw-education-academy'),
	'section'=> 'vw_education_academy_slidersettings',
	'type'=> 'text',
	'active_callback' => 'vw_education_academy_advance_slider'
));

//Selective Refresh
$wp_customize->selective_refresh->add_partial('vw_education_academy_slider_hide_show',array(
	'selector'        => '#slider .inner_carousel h1',
	'render_callback' => 'vw_education_academy_customize_partial_vw_education_academy_slider_hide_show',
));

for ( $count = 1; $count <= 3; $count++ ) {
	$wp_customize->add_setting( 'vw_education_academy_slider_page' . $count, array(
		'default'           => '',
		'sanitize_callback' => 'vw_education_academy_sanitize_dropdown_pages'
	) );
	$wp_customize->add_control( 'vw_education_academy_slider_page' . $count, array(
		'label'    => __( 'Select Slider Page', 'vw-education-academy' ),
		'description' => __('Slider image size (1500 x 590)','vw-education-academy'),
		'section'  => 'vw_education_academy_slidersettings',
		'type'     => 'dropdown-pages',
		'active_callback' => 'vw_education_academy_default_slider'
	) );
}

$wp_customize->add_setting('vw_education_academy_slider_button_text',array(
	'default'=> 'Read More',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_slider_button_text',array(
	'label'	=> __('Add Slider Button Text','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Read More', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_slidersettings',
	'type'=> 'text',
	'active_callback' => 'vw_education_academy_default_slider'
));

$wp_customize->add_setting('vw_education_academy_contact_link',array(
	'default'	=> '',
	'sanitize_callback'	=> 'esc_url_raw'
));

$wp_customize->add_control('vw_education_academy_contact_link',array(
	'label'	=> __('Button Url','vw-education-academy'),
	'section'	=> 'vw_education_academy_slidersettings',
	'type'		=> 'url',
	'active_callback' => 'vw_education_academy_default_slider'
));

$wp_customize->add_setting('vw_education_academy_slider_button_icon',array(
	'default'	=> 'fa fa-angle-right',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_slider_button_icon',array(
	'label'	=> __('Add Slider button Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_slidersettings',
	'setting'	=> 'vw_education_academy_slider_button_icon',
	'type'		=> 'icon',
	'active_callback' => 'vw_education_academy_default_slider'
)));

//content layout
$wp_customize->add_setting('vw_education_academy_slider_content_option',array(
    'default' => 'Center',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control(new VW_Education_Academy_Image_Radio_Control($wp_customize, 'vw_education_academy_slider_content_option', array(
    'type' => 'select',
    'label' => __('Slider Content Layouts','vw-education-academy'),
    'section' => 'vw_education_academy_slidersettings',
    'choices' => array(
        'Left' => esc_url(get_template_directory_uri()).'/assets/images/slider-content1.png',
        'Center' => esc_url(get_template_directory_uri()).'/assets/images/slider-content2.png',
        'Right' => esc_url(get_template_directory_uri()).'/assets/images/slider-content3.png',
),'active_callback' => 'vw_education_academy_default_slider'
)));

//Slider content padding
$wp_customize->add_setting('vw_education_academy_slider_content_padding_top_bottom',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_slider_content_padding_top_bottom',array(
	'label'	=> __('Slider Content Padding Top Bottom','vw-education-academy'),
	'description'	=> __('Enter a value in %. Example:20%','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '50%', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_slidersettings',
	'type'=> 'text',
	'active_callback' => 'vw_education_academy_default_slider'
));

$wp_customize->add_setting('vw_education_academy_slider_content_padding_left_right',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_slider_content_padding_left_right',array(
	'label'	=> __('Slider Content Padding Left Right','vw-education-academy'),
	'description'	=> __('Enter a value in %. Example:20%','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '50%', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_slidersettings',
	'type'=> 'text',
	'active_callback' => 'vw_education_academy_default_slider'
));

//Slider excerpt
$wp_customize->add_setting( 'vw_education_academy_slider_excerpt_number', array(
	'default'              => 30,
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_slider_excerpt_number', array(
	'label'       => esc_html__( 'Slider Excerpt length','vw-education-academy' ),
	'section'     => 'vw_education_academy_slidersettings',
	'type'        => 'range',
	'settings'    => 'vw_education_academy_slider_excerpt_number',
	'input_attrs' => array(
		'step'             => 5,
		'min'              => 0,
		'max'              => 50,
	),'active_callback' => 'vw_education_academy_default_slider'
) );

//Slider height
$wp_customize->add_setting('vw_education_academy_slider_height',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_slider_height',array(
	'label'	=> __('Slider Height','vw-education-academy'),
	'description'	=> __('Specify the slider height (px).','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '500px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_slidersettings',
	'type'=> 'text',
	'active_callback' => 'vw_education_academy_default_slider'
));

$wp_customize->add_setting( 'vw_education_academy_slider_speed', array(
	'default'  => 4000,
	'sanitize_callback'	=> 'vw_education_academy_sanitize_float'
) );
$wp_customize->add_control( 'vw_education_academy_slider_speed', array(
	'label' => esc_html__('Slider Transition Speed','vw-education-academy'),
	'section' => 'vw_education_academy_slidersettings',
	'type'  => 'number',
	'active_callback' => 'vw_education_academy_default_slider'
) );

//Opacity
$wp_customize->add_setting('vw_education_academy_slider_opacity_color',array(
  'default'              => 0.5,
  'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));

$wp_customize->add_control( 'vw_education_academy_slider_opacity_color', array(
	'label'       => esc_html__( 'Slider Image Opacity','vw-education-academy' ),
	'section'     => 'vw_education_academy_slidersettings',
	'type'        => 'select',
	'settings'    => 'vw_education_academy_slider_opacity_color',
	'choices' => array(
      '0' =>  esc_attr('0','vw-education-academy'),
      '0.1' =>  esc_attr('0.1','vw-education-academy'),
      '0.2' =>  esc_attr('0.2','vw-education-academy'),
      '0.3' =>  esc_attr('0.3','vw-education-academy'),
      '0.4' =>  esc_attr('0.4','vw-education-academy'),
      '0.5' =>  esc_attr('0.5','vw-education-academy'),
      '0.6' =>  esc_attr('0.6','vw-education-academy'),
      '0.7' =>  esc_attr('0.7','vw-education-academy'),
      '0.8' =>  esc_attr('0.8','vw-education-academy'),
      '0.9' =>  esc_attr('0.9','vw-education-academy')
),'active_callback' => 'vw_education_academy_default_slider'
));

$wp_customize->add_setting( 'vw_education_academy_slider_image_overlay',array(
	'default' => 1,
  	'transport' => 'refresh',
  	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_slider_image_overlay',array(
  	'label' => esc_html__( 'Show / Hide Slider Image Overlay','vw-education-academy' ),
  	'section' => 'vw_education_academy_slidersettings',
  	'active_callback' => 'vw_education_academy_default_slider'
)));

$wp_customize->add_setting('vw_education_academy_slider_image_overlay_color', array(
	'default'           => '#111111',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_slider_image_overlay_color', array(
	'label'    => __('Slider Image Overlay Color', 'vw-education-academy'),
	'section'  => 'vw_education_academy_slidersettings',
	'active_callback' => 'vw_education_academy_default_slider'
)));

//About us section
$wp_customize->add_section( 'vw_education_academy_about_section' , array(
	'title'      => __( 'About us', 'vw-education-academy' ),
	'description' => __('For more options of the about us section </br> <a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/themes/academic-wordpress-theme/">GET PRO</a>','vw-education-academy'),
	'priority'   => null,
	'panel' => 'vw_education_academy_homepage_panel'
) );

//Selective Refresh
$wp_customize->selective_refresh->add_partial( 'vw_education_academy_section_title', array( 
	'selector' => '#about-section h2', 
	'render_callback' => 'vw_education_academy_customize_partial_vw_education_academy_section_title',
));

$wp_customize->add_setting('vw_education_academy_about_title_icon',array(
	'default'	=> 'fas fa-graduation-cap',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_about_title_icon',array(
	'label'	=> __('Add About Title Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_about_section',
	'setting'	=> 'vw_education_academy_about_title_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting('vw_education_academy_section_title',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_section_title',array(
	'label'	=> __('Add Section Title','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'ABOUT US', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_about_section',
	'type'=> 'text'
));

$wp_customize->add_setting( 'vw_education_academy_about_page', array(
	'default'           => '',
	'sanitize_callback' => 'vw_education_academy_sanitize_dropdown_pages'
) );
$wp_customize->add_control( 'vw_education_academy_about_page', array(
	'label'    => __( 'Select About Page', 'vw-education-academy' ),
	'section'  => 'vw_education_academy_about_section',
	'type'     => 'dropdown-pages'
) );

//About excerpt
$wp_customize->add_setting( 'vw_education_academy_about_excerpt_number', array(
	'default'              => 30,
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_about_excerpt_number', array(
	'label'       => esc_html__( 'About Excerpt length','vw-education-academy' ),
	'section'     => 'vw_education_academy_about_section',
	'type'        => 'range',
	'settings'    => 'vw_education_academy_about_excerpt_number',
	'input_attrs' => array(
		'step'             => 5,
		'min'              => 0,
		'max'              => 50,
	),
) );

$wp_customize->add_setting('vw_education_academy_about_button_text',array(
	'default'=> 'Read More',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_about_button_text',array(
	'label'	=> __('Add About Button Text','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Read More', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_about_section',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_about_button_icon',array(
	'default'	=> 'fa fa-angle-right',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_about_button_icon',array(
	'label'	=> __('Add About Button Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_about_section',
	'setting'	=> 'vw_education_academy_about_button_icon',
	'type'		=> 'icon'
)));

//popular courses Section
$wp_customize->add_section('vw_education_academy_popular_courses', array(
	'title'       => __('Popular Courses Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_popular_courses_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_popular_courses_text',array(
	'description' => __('<p>1. More options for popular courses section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for popular courses section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_popular_courses',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_popular_courses_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_popular_courses_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_popular_courses',
	'type'=> 'hidden'
));

//Services Section
$wp_customize->add_section('vw_education_academy_services', array(
	'title'       => __('Services Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_services_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_services_text',array(
	'description' => __('<p>1. More options for services section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for services section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_services',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_services_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_services_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_services',
	'type'=> 'hidden'
));

//Gallery Section
$wp_customize->add_section('vw_education_academy_gallery', array(
	'title'       => __('Gallery Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_gallery_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_gallery_text',array(
	'description' => __('<p>1. More options for gallery section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for gallery section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_gallery',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_gallery_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_gallery_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_gallery',
	'type'=> 'hidden'
));

//Events Section
$wp_customize->add_section('vw_education_academy_events', array(
	'title'       => __('Events Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_events_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_events_text',array(
	'description' => __('<p>1. More options for events section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for events section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_events',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_events_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_events_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_events',
	'type'=> 'hidden'
));

//Choose Us Section
$wp_customize->add_section('vw_education_academy_choose_us', array(
	'title'       => __('Choose Us Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_choose_us_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_choose_us_text',array(
	'description' => __('<p>1. More options for choose us section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for choose us section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_choose_us',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_choose_us_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_choose_us_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_choose_us',
	'type'=> 'hidden'
));

// Records Section
$wp_customize->add_section('vw_education_academy_records', array(
	'title'       => __('Records Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_records_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_records_text',array(
	'description' => __('<p>1. More options for records section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for records section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_records',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_records_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_records_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_records',
	'type'=> 'hidden'
));

//register now Section
$wp_customize->add_section('vw_education_academy_register_now', array(
	'title'       => __('Register Now Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_register_now_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_register_now_text',array(
	'description' => __('<p>1. More options for register now section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for register now section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_register_now',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_register_now_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_register_now_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_register_now',
	'type'=> 'hidden'
));

//Academic Teacher Section
$wp_customize->add_section('vw_education_academy_academic_teacher', array(
	'title'       => __('Academic Teacher Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_academic_teacher_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_academic_teacher_text',array(
	'description' => __('<p>1. More options for academic teacher section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for academic teacher section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_academic_teacher',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_academic_teacher_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_academic_teacher_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_academic_teacher',
	'type'=> 'hidden'
));

//Happy Students Section
$wp_customize->add_section('vw_education_academy_happy_students', array(
	'title'       => __('Happy Students Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_happy_students_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_happy_students_text',array(
	'description' => __('<p>1. More options for happy students section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for happy students section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_happy_students',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_happy_students_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_happy_students_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_happy_students',
	'type'=> 'hidden'
));

//Academic News Section
$wp_customize->add_section('vw_education_academy_academic_news', array(
	'title'       => __('Academic News Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_academic_news_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_academic_news_text',array(
	'description' => __('<p>1. More options for academic news section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for academic news section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_academic_news',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_academic_news_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_academic_news_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_academic_news',
	'type'=> 'hidden'
));

//Newsletter Section
$wp_customize->add_section('vw_education_academy_newsletter', array(
	'title'       => __('Newsletter Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_newsletter_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_newsletter_text',array(
	'description' => __('<p>1. More options for newsletter section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for newsletter section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_newsletter',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_newsletter_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_newsletter_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_newsletter',
	'type'=> 'hidden'
));

//partners Section
$wp_customize->add_section('vw_education_academy_partners', array(
	'title'       => __('Partners Section', 'vw-education-academy'),
	'description' => __('<p class="premium-opt">Premium Theme Features</p>','vw-education-academy'),
	'priority'    => null,
	'panel'       => 'vw_education_academy_homepage_panel',
));

$wp_customize->add_setting('vw_education_academy_partners_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_partners_text',array(
	'description' => __('<p>1. More options for partners section.</p>
		<p>2. Unlimited images options.</p>
		<p>3. Color options for partners section.</p>','vw-education-academy'),
	'section'=> 'vw_education_academy_partners',
	'type'=> 'hidden'
));

$wp_customize->add_setting('vw_education_academy_partners_btn',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_partners_btn',array(
	'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=vw_education_academy_guide') ." '>More Info</a>",
	'section'=> 'vw_education_academy_partners',
	'type'=> 'hidden'
));

	//Footer Text
	$wp_customize->add_section('vw_education_academy_footer',array(
		'title'	=> __('Footer','vw-education-academy'),
		'description' => __('For more options of the footer section </br> <a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/themes/academic-wordpress-theme/">GET PRO</a>','vw-education-academy'),
		'panel' => 'vw_education_academy_homepage_panel',
	));	

	$wp_customize->add_setting( 'vw_education_academy_footer_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'vw_education_academy_switch_sanitization'
	));
	$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_footer_hide_show',array(
		'label' => esc_html__( 'Show / Hide Footer','vw-education-academy' ),
		'section' => 'vw_education_academy_footer'
	)));

	// font size
	$wp_customize->add_setting('vw_education_academy_button_footer_font_size',array(
		'default'=> 30,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_education_academy_button_footer_font_size',array(
		'label'	=> __('Footer Heading Font Size','vw-education-academy'),
  		'type'        => 'number',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'vw_education_academy_footer',
	));

	$wp_customize->add_setting('vw_education_academy_button_footer_heading_letter_spacing',array(
		'default'=> 1,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('vw_education_academy_button_footer_heading_letter_spacing',array(
		'label'	=> __('Heading Letter Spacing','vw-education-academy'),
  		'type'        => 'number',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
	),
		'section'=> 'vw_education_academy_footer',
	));

	// text trasform
	$wp_customize->add_setting('vw_education_academy_button_footer_text_transform',array(
		'default'=> 'Capitalize',
		'sanitize_callback'	=> 'vw_education_academy_sanitize_choices'
	));
	$wp_customize->add_control('vw_education_academy_button_footer_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Heading Text Transform','vw-education-academy'),
		'choices' => array(
      'Uppercase' => __('Uppercase','vw-education-academy'),
      'Capitalize' => __('Capitalize','vw-education-academy'),
      'Lowercase' => __('Lowercase','vw-education-academy'),
    ),
		'section'=> 'vw_education_academy_footer',
	));

	$wp_customize->add_setting('vw_education_academy_footer_heading_weight',array(
        'default' => 600,
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_education_academy_sanitize_choices'
	));
	$wp_customize->add_control('vw_education_academy_footer_heading_weight',array(
        'type' => 'select',
        'label' => __('Heading Font Weight','vw-education-academy'),
        'section' => 'vw_education_academy_footer',
        'choices' => array(
        	'100' => __('100','vw-education-academy'),
            '200' => __('200','vw-education-academy'),
            '300' => __('300','vw-education-academy'),
            '400' => __('400','vw-education-academy'),
            '500' => __('500','vw-education-academy'),
            '600' => __('600','vw-education-academy'),
            '700' => __('700','vw-education-academy'),
            '800' => __('800','vw-education-academy'),
            '900' => __('900','vw-education-academy'),
        ),
	) );

	$wp_customize->add_setting('vw_education_academy_footer_template',array(
	  'default'	=> esc_html('vw_education_academy-footer-one'),
	  'sanitize_callback'	=> 'vw_education_academy_sanitize_choices'
	));
	$wp_customize->add_control('vw_education_academy_footer_template',array(
	      'label'	=> esc_html__('Footer style','vw-education-academy'),
	      'section'	=> 'vw_education_academy_footer',
	      'setting'	=> 'vw_education_academy_footer_template',
	      'type' => 'select',
	      'choices' => array(
	          'vw_education_academy-footer-one' => esc_html__('Style 1', 'vw-education-academy'),
	          'vw_education_academy-footer-two' => esc_html__('Style 2', 'vw-education-academy'),
	          'vw_education_academy-footer-three' => esc_html__('Style 3', 'vw-education-academy'),
	          'vw_education_academy-footer-four' => esc_html__('Style 4', 'vw-education-academy'),
	          'vw_education_academy-footer-five' => esc_html__('Style 5', 'vw-education-academy'),
	          )
	));

$wp_customize->add_setting('vw_education_academy_footer_background_color', array(
	'default'           => '#111111',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_footer_background_color', array(
	'label'    => __('Footer Background Color', 'vw-education-academy'),
	'section'  => 'vw_education_academy_footer',
)));

$wp_customize->add_setting('vw_education_academy_footer_background_image',array(
	'default'	=> '',
	'sanitize_callback'	=> 'esc_url_raw',
));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'vw_education_academy_footer_background_image',array(
    'label' => __('Footer Background Image','vw-education-academy'),
    'section' => 'vw_education_academy_footer'
)));

$wp_customize->add_setting('vw_education_academy_footer_img_position',array(
  'default' => 'center center',
  'transport' => 'refresh',
  'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_footer_img_position',array(
	'type' => 'select',
	'label' => __('Footer Image Position','vw-education-academy'),
	'section' => 'vw_education_academy_footer',
	'choices' 	=> array(
		'left top' 		=> esc_html__( 'Top Left', 'vw-education-academy' ),
		'center top'   => esc_html__( 'Top', 'vw-education-academy' ),
		'right top'   => esc_html__( 'Top Right', 'vw-education-academy' ),
		'left center'   => esc_html__( 'Left', 'vw-education-academy' ),
		'center center'   => esc_html__( 'Center', 'vw-education-academy' ),
		'right center'   => esc_html__( 'Right', 'vw-education-academy' ),
		'left bottom'   => esc_html__( 'Bottom Left', 'vw-education-academy' ),
		'center bottom'   => esc_html__( 'Bottom', 'vw-education-academy' ),
		'right bottom'   => esc_html__( 'Bottom Right', 'vw-education-academy' ),
	),
));

// Footer
$wp_customize->add_setting('vw_education_academy_img_footer',array(
	'default'=> 'scroll',
	'sanitize_callback'	=> 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_img_footer',array(
	'type' => 'select',
	'label'	=> __('Footer Background Attatchment','vw-education-academy'),
	'choices' => array(
        'fixed' => __('fixed','vw-education-academy'),
        'scroll' => __('scroll','vw-education-academy'),
    ),
	'section'=> 'vw_education_academy_footer',
));

$wp_customize->add_setting('vw_education_academy_footer_widgets_heading',array(
    'default' => 'Left',
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_footer_widgets_heading',array(
    'type' => 'select',
    'label' => __('Footer Widget Heading','vw-education-academy'),
    'section' => 'vw_education_academy_footer',
    'choices' => array(
    	'Left' => __('Left','vw-education-academy'),
        'Center' => __('Center','vw-education-academy'),
        'Right' => __('Right','vw-education-academy')
    ),
) );

$wp_customize->add_setting('vw_education_academy_footer_widgets_content',array(
    'default' => 'Left',
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_footer_widgets_content',array(
    'type' => 'select',
    'label' => __('Footer Widget Content','vw-education-academy'),
    'section' => 'vw_education_academy_footer',
    'choices' => array(
    	'Left' => __('Left','vw-education-academy'),
        'Center' => __('Center','vw-education-academy'),
        'Right' => __('Right','vw-education-academy')
    ),
) );

// footer padding
$wp_customize->add_setting('vw_education_academy_footer_padding',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_footer_padding',array(
	'label'	=> __('Footer Top Bottom Padding','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
  'placeholder' => __( '10px', 'vw-education-academy' ),
),
	'section'=> 'vw_education_academy_footer',
	'type'=> 'text'
));

// footer social icon
	$wp_customize->add_setting( 'vw_education_academy_footer_icon',array(
	'default' => false,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
	$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_footer_icon',array(
	'label' => esc_html__( 'Show / Hide Footer Social Icon','vw-education-academy' ),
	'section' => 'vw_education_academy_footer'
)));

//Selective Refresh
$wp_customize->selective_refresh->add_partial('vw_education_academy_footer_text', array( 
	'selector' => '.copyright p', 
	'render_callback' => 'vw_education_academy_customize_partial_vw_education_academy_footer_text', 
));

$wp_customize->add_setting( 'vw_education_academy_copyright_hide_show',array(
  'default' => 1,
  'transport' => 'refresh',
  'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_copyright_hide_show',array(
  'label' => esc_html__( 'Show / Hide Copyright','vw-education-academy' ),
  'section' => 'vw_education_academy_footer'
)));

$wp_customize->add_setting('vw_education_academy_copyright_background_color', array(
	'default'           => '#ffbc00',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_copyright_background_color', array(
	'label'    => __('Copyright Background Color', 'vw-education-academy'),
	'section'  => 'vw_education_academy_footer',
)));

$wp_customize->add_setting('vw_education_academy_footer_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control('vw_education_academy_footer_text',array(
	'label'	=> __('Copyright Text','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Copyright 2019, .....', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_footer',
	'type'=> 'text'
));	

$wp_customize->add_setting('vw_education_academy_copyright_font_size',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_copyright_font_size',array(
	'label'	=> __('Copyright Font Size','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_footer',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_copyright_padding_top_bottom',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_copyright_padding_top_bottom',array(
	'label'	=> __('Copyright Padding Top Bottom','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_footer',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_copyright_alignment',array(
    'default' => 'center',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control(new VW_Education_Academy_Image_Radio_Control($wp_customize, 'vw_education_academy_copyright_alignment', array(
    'type' => 'select',
    'label' => __('Copyright Alignment','vw-education-academy'),
    'section' => 'vw_education_academy_footer',
    'settings' => 'vw_education_academy_copyright_alignment',
    'choices' => array(
        'left' => esc_url(get_template_directory_uri()).'/assets/images/copyright1.png',
        'center' => esc_url(get_template_directory_uri()).'/assets/images/copyright2.png',
        'right' => esc_url(get_template_directory_uri()).'/assets/images/copyright3.png'
))));

$wp_customize->add_setting( 'vw_education_academy_hide_show_scroll',array(
	'default' => 1,
  	'transport' => 'refresh',
  	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));  
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_hide_show_scroll',array(
  	'label' => esc_html__( 'Show / Hide Scroll To Top','vw-education-academy' ),
  	'section' => 'vw_education_academy_footer'
)));

//Selective Refresh
$wp_customize->selective_refresh->add_partial('vw_education_academy_scroll_to_top_icon', array( 
	'selector' => '.scrollup i', 
	'render_callback' => 'vw_education_academy_customize_partial_vw_education_academy_scroll_to_top_icon', 
));

$wp_customize->add_setting('vw_education_academy_scroll_to_top_icon',array(
	'default'	=> 'fas fa-long-arrow-alt-up',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_scroll_to_top_icon',array(
	'label'	=> __('Add Scroll to Top Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_footer',
	'setting'	=> 'vw_education_academy_scroll_to_top_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting('vw_education_academy_scroll_to_top_font_size',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_scroll_to_top_font_size',array(
	'label'	=> __('Icon Font Size','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_footer',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_scroll_to_top_padding',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_scroll_to_top_padding',array(
	'label'	=> __('Icon Top Bottom Padding','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_footer',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_scroll_to_top_width',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_scroll_to_top_width',array(
	'label'	=> __('Icon Width','vw-education-academy'),
	'description'	=> __('Enter a value in pixels Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_footer',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_scroll_to_top_height',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_scroll_to_top_height',array(
	'label'	=> __('Icon Height','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_footer',
	'type'=> 'text'
));

$wp_customize->add_setting( 'vw_education_academy_scroll_to_top_border_radius', array(
	'default'              => '',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_scroll_to_top_border_radius', array(
	'label'       => esc_html__( 'Icon Border Radius','vw-education-academy' ),
	'section'     => 'vw_education_academy_footer',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) );

$wp_customize->add_setting('vw_education_academy_scroll_top_alignment',array(
    'default' => 'Right',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control(new VW_Education_Academy_Image_Radio_Control($wp_customize, 'vw_education_academy_scroll_top_alignment', array(
    'type' => 'select',
    'label' => __('Scroll To Top','vw-education-academy'),
    'section' => 'vw_education_academy_footer',
    'settings' => 'vw_education_academy_scroll_top_alignment',
    'choices' => array(
        'Left' => esc_url(get_template_directory_uri()).'/assets/images/layout1.png',
        'Center' => esc_url(get_template_directory_uri()).'/assets/images/layout2.png',
        'Right' => esc_url(get_template_directory_uri()).'/assets/images/layout3.png'
))));


//Blog Post
$wp_customize->add_panel( $VWEducationAcademyParentPanel );

$BlogPostParentPanel = new VW_Education_Academy_WP_Customize_Panel( $wp_customize, 'blog_post_parent_panel', array(
	'title' => __( 'Blog Post Settings', 'vw-education-academy' ),
	'panel' => 'vw_education_academy_panel_id',
));

$wp_customize->add_panel( $BlogPostParentPanel );

// Add example section and controls to the middle (second) panel
$wp_customize->add_section( 'vw_education_academy_post_settings', array(
	'title' => __( 'Post Settings', 'vw-education-academy' ),
	'panel' => 'blog_post_parent_panel',
));

//Blog layout
$wp_customize->add_setting('vw_education_academy_blog_layout_option',array(
    'default' => 'Default',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control(new VW_Education_Academy_Image_Radio_Control($wp_customize, 'vw_education_academy_blog_layout_option', array(
    'type' => 'select',
    'label' => __('Blog Layouts','vw-education-academy'),
    'section' => 'vw_education_academy_post_settings',
    'choices' => array(
        'Default' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout1.png',
        'Center' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout2.png',
        'Left' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout3.png',
))));

// Add Settings and Controls for Layout
$wp_customize->add_setting('vw_education_academy_theme_options',array(
    'default' => 'Right Sidebar',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'	        
) );
$wp_customize->add_control('vw_education_academy_theme_options', array(
    'type' => 'select',
    'label' => __('Post Sidebar Layout','vw-education-academy'),
    'description' => __('Here you can change the sidebar layout for posts. ','vw-education-academy'),
    'section' => 'vw_education_academy_post_settings',
    'choices' => array(
        'Left Sidebar' => __('Left Sidebar','vw-education-academy'),
        'Right Sidebar' => __('Right Sidebar','vw-education-academy'),
        'One Column' => __('One Column','vw-education-academy'),
        'Three Columns' => __('Three Columns','vw-education-academy'),
        'Four Columns' => __('Four Columns','vw-education-academy'),
        'Grid Layout' => __('Grid Layout','vw-education-academy')
    ),
));

//Selective Refresh
$wp_customize->selective_refresh->add_partial('vw_education_academy_toggle_postdate', array( 
	'selector' => '.post-main-box h2 a', 
	'render_callback' => 'vw_education_academy_customize_partial_vw_education_academy_toggle_postdate', 
));

$wp_customize->add_setting('vw_education_academy_toggle_postdate_icon',array(
	'default'	=> 'fas fa-calendar-alt',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_toggle_postdate_icon',array(
	'label'	=> __('Add Post Date Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_post_settings',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_toggle_postdate',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_toggle_postdate',array(
    'label' => esc_html__( 'Show / Hide Post Date','vw-education-academy' ),
    'section' => 'vw_education_academy_post_settings'
)));

$wp_customize->add_setting('vw_education_academy_toggle_author_icon',array(
	'default'	=> 'far fa-user',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_toggle_author_icon',array(
	'label'	=> __('Add Author Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_post_settings',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_toggle_author',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_toggle_author',array(
	'label' => esc_html__( 'Show / Hide Author','vw-education-academy' ),
	'section' => 'vw_education_academy_post_settings'
)));

$wp_customize->add_setting('vw_education_academy_toggle_comments_icon',array(
	'default'	=> 'fas fa-comments',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_toggle_comments_icon',array(
	'label'	=> __('Add Comments Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_post_settings',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_toggle_comments',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_toggle_comments',array(
	'label' => esc_html__( 'Show / Hide Comments','vw-education-academy' ),
	'section' => 'vw_education_academy_post_settings'
)));

$wp_customize->add_setting('vw_education_academy_toggle_time_icon',array(
	'default'	=> 'far fa-clock',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_toggle_time_icon',array(
	'label'	=> __('Add Time Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_post_settings',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_toggle_time',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_toggle_time',array(
	'label' => esc_html__( 'Show / Hide Time','vw-education-academy' ),
	'section' => 'vw_education_academy_post_settings'
)));

$wp_customize->add_setting( 'vw_education_academy_featured_image_hide_show',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_featured_image_hide_show', array(
	'label' => esc_html__( 'Show / Hide Featured Image','vw-education-academy' ),
	'section' => 'vw_education_academy_post_settings'
)));

$wp_customize->add_setting( 'vw_education_academy_featured_image_border_radius', array(
	'default'              => '0',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_featured_image_border_radius', array(
	'label'       => esc_html__( 'Featured Image Border Radius','vw-education-academy' ),
	'section'     => 'vw_education_academy_post_settings',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) ); 

$wp_customize->add_setting( 'vw_education_academy_featured_image_box_shadow', array(
	'default'              => '0',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_featured_image_box_shadow', array(
	'label'       => esc_html__( 'Featured Image Box Shadow','vw-education-academy' ),
	'section'     => 'vw_education_academy_post_settings',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) );

//Featured Image
$wp_customize->add_setting('vw_education_academy_blog_post_featured_image_dimension',array(
   'default' => 'default',
   'sanitize_callback'	=> 'vw_education_academy_sanitize_choices'
));
	$wp_customize->add_control('vw_education_academy_blog_post_featured_image_dimension',array(
 'type' => 'select',
 'label'	=> __('Blog Post Featured Image Dimension','vw-education-academy'),
 'section'	=> 'vw_education_academy_post_settings',
 'choices' => array(
      'default' => __('Default','vw-education-academy'),
      'custom' => __('Custom Image Size','vw-education-academy'),
  ),
	));

$wp_customize->add_setting('vw_education_academy_blog_post_featured_image_custom_width',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
	));
$wp_customize->add_control('vw_education_academy_blog_post_featured_image_custom_width',array(
	'label'	=> __('Featured Image Custom Width','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
	'placeholder' => __( '10px', 'vw-education-academy' ),),
	'section'=> 'vw_education_academy_post_settings',
	'type'=> 'text',
	'active_callback' => 'vw_education_academy_blog_post_featured_image_dimension'
	));

$wp_customize->add_setting('vw_education_academy_blog_post_featured_image_custom_height',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_blog_post_featured_image_custom_height',array(
	'label'	=> __('Featured Image Custom Height','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
	'placeholder' => __( '10px', 'vw-education-academy' ),),
	'section'=> 'vw_education_academy_post_settings',
	'type'=> 'text',
	'active_callback' => 'vw_education_academy_blog_post_featured_image_dimension'
));

$wp_customize->add_setting( 'vw_education_academy_excerpt_number', array(
	'default'              => 30,
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_excerpt_number', array(
	'label'       => esc_html__( 'Excerpt length','vw-education-academy' ),
	'section'     => 'vw_education_academy_post_settings',
	'type'        => 'range',
	'settings'    => 'vw_education_academy_excerpt_number',
	'input_attrs' => array(
		'step'             => 5,
		'min'              => 0,
		'max'              => 50,
	),
) );

$wp_customize->add_setting('vw_education_academy_meta_field_separator',array(
	'default'=> '|',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_meta_field_separator',array(
	'label'	=> __('Add Meta Separator','vw-education-academy'),
	'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','vw-education-academy'),
	'section'=> 'vw_education_academy_post_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_blog_page_posts_settings',array(
    'default' => 'Into Blocks',
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_blog_page_posts_settings',array(
    'type' => 'select',
    'label' => __('Display Blog Posts','vw-education-academy'),
    'section' => 'vw_education_academy_post_settings',
    'choices' => array(
    	'Into Blocks' => __('Into Blocks','vw-education-academy'),
        'Without Blocks' => __('Without Blocks','vw-education-academy')
    ),
) );

$wp_customize->add_setting('vw_education_academy_excerpt_settings',array(
    'default' => 'Excerpt',
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_excerpt_settings',array(
    'type' => 'select',
    'label' => __('Post Content','vw-education-academy'),
    'section' => 'vw_education_academy_post_settings',
    'choices' => array(
    	'Content' => __('Content','vw-education-academy'),
        'Excerpt' => __('Excerpt','vw-education-academy'),
        'No Content' => __('No Content','vw-education-academy')
    ),
) );

$wp_customize->add_setting('vw_education_academy_excerpt_suffix',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_excerpt_suffix',array(
	'label'	=> __('Add Excerpt Suffix','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '[...]', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_post_settings',
	'type'=> 'text'
));

$wp_customize->add_setting( 'vw_education_academy_blog_pagination_hide_show',array(
  'default' => 1,
  'transport' => 'refresh',
  'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));  
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_blog_pagination_hide_show',array(
  'label' => esc_html__( 'Show / Hide Blog Pagination','vw-education-academy' ),
  'section' => 'vw_education_academy_post_settings'
)));

$wp_customize->add_setting( 'vw_education_academy_blog_pagination_type', array(
    'default'			=> 'blog-page-numbers',
    'sanitize_callback'	=> 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control( 'vw_education_academy_blog_pagination_type', array(
    'section' => 'vw_education_academy_post_settings',
    'type' => 'select',
    'label' => __( 'Blog Pagination', 'vw-education-academy' ),
    'choices'		=> array(
        'blog-page-numbers'  => __( 'Numeric', 'vw-education-academy' ),
        'next-prev' => __( 'Older Posts/Newer Posts', 'vw-education-academy' ),
)));

// Button Settings
$wp_customize->add_section( 'vw_education_academy_button_settings', array(
	'title' => __( 'Button Settings', 'vw-education-academy' ),
	'panel' => 'blog_post_parent_panel',
));

//Selective Refresh
$wp_customize->selective_refresh->add_partial('vw_education_academy_button_text', array( 
	'selector' => '.post-main-box .content-bttn a', 
	'render_callback' => 'vw_education_academy_customize_partial_vw_education_academy_button_text', 
));

$wp_customize->add_setting('vw_education_academy_button_text',array(
	'default'=> 'Read More',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_button_text',array(
	'label'	=> __('Add Button Text','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Read More', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_button_settings',
	'type'=> 'text'
));

// font size button
$wp_customize->add_setting('vw_education_academy_button_font_size',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_button_font_size',array(
	'label'	=> __('Button Font Size','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
  	'placeholder' => __( '10px', 'vw-education-academy' ),
),
	'type'        => 'text',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
	'section'=> 'vw_education_academy_button_settings',
));

$wp_customize->add_setting( 'vw_education_academy_button_border_radius', array(
	'default'              => '',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_button_border_radius', array(
	'label'       => esc_html__( 'Button Border Radius','vw-education-academy' ),
	'section'     => 'vw_education_academy_button_settings',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) );

$wp_customize->add_setting('vw_education_academy_blog_button_icon',array(
	'default'	=> 'fa fa-angle-right',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_blog_button_icon',array(
	'label'	=> __('Add Button Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_button_settings',
	'setting'	=> 'vw_education_academy_blog_button_icon',
	'type'		=> 'icon'
)));


$wp_customize->add_setting('vw_education_academy_button_padding_top_bottom',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_button_padding_top_bottom',array(
	'label'	=> __('Padding Top Bottom','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_button_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_button_padding_left_right',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_button_padding_left_right',array(
	'label'	=> __('Padding Left Right','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_button_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_button_letter_spacing',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_button_letter_spacing',array(
	'label'	=> __('Button Letter Spacing','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
  	'placeholder' => __( '10px', 'vw-education-academy' ),
),
	'type'        => 'text',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
	'section'=> 'vw_education_academy_button_settings',
));

// text trasform
$wp_customize->add_setting('vw_education_academy_button_text_transform',array(
	'default'=> 'Uppercase',
	'sanitize_callback'	=> 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_button_text_transform',array(
	'type' => 'radio',
	'label'	=> __('Button Text Transform','vw-education-academy'),
	'choices' => array(
        'Uppercase' => __('Uppercase','vw-education-academy'),
        'Capitalize' => __('Capitalize','vw-education-academy'),
        'Lowercase' => __('Lowercase','vw-education-academy'),
    ),
	'section'=> 'vw_education_academy_button_settings',
));

// Related Post Settings
$wp_customize->add_section( 'vw_education_academy_related_posts_settings', array(
	'title' => __( 'Related Posts Settings', 'vw-education-academy' ),
	'panel' => 'blog_post_parent_panel',
));

//Selective Refresh
$wp_customize->selective_refresh->add_partial('vw_education_academy_related_post_title', array( 
	'selector' => '.related-post h3', 
	'render_callback' => 'vw_education_academy_customize_partial_vw_education_academy_related_post_title', 
));

$wp_customize->add_setting( 'vw_education_academy_related_post',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_related_post',array(
	'label' => esc_html__( 'Show / Hide Related Post','vw-education-academy' ),
	'section' => 'vw_education_academy_related_posts_settings'
)));

$wp_customize->add_setting('vw_education_academy_related_post_title',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_related_post_title',array(
	'label'	=> __('Add Related Post Title','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Related Post', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_related_posts_settings',
	'type'=> 'text'
));

	$wp_customize->add_setting('vw_education_academy_related_posts_count',array(
	'default'=> '3',
	'sanitize_callback'	=> 'vw_education_academy_sanitize_float'
));
$wp_customize->add_control('vw_education_academy_related_posts_count',array(
	'label'	=> __('Add Related Post Count','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '3', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_related_posts_settings',
	'type'=> 'number'
));

$wp_customize->add_setting( 'vw_education_academy_related_posts_excerpt_number', array(
	'default'              => 20,
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_related_posts_excerpt_number', array(
	'label'       => esc_html__( 'Related Posts Excerpt length','vw-education-academy' ),
	'section'     => 'vw_education_academy_related_posts_settings',
	'type'        => 'range',
	'settings'    => 'vw_education_academy_related_posts_excerpt_number',
	'input_attrs' => array(
		'step'             => 5,
		'min'              => 0,
		'max'              => 50,
	),
) );

// Single Posts Settings
$wp_customize->add_section( 'vw_education_academy_single_blog_settings', array(
	'title' => __( 'Single Post Settings', 'vw-education-academy' ),
	'panel' => 'blog_post_parent_panel',
));

$wp_customize->add_setting('vw_education_academy_single_postdate_icon',array(
	'default'	=> 'fas fa-calendar-alt',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_single_postdate_icon',array(
	'label'	=> __('Add Post Date Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_single_blog_settings',
	'setting'	=> 'vw_education_academy_single_postdate_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_single_postdate',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_single_postdate',array(
    'label' => esc_html__( 'Show / Hide Date','vw-education-academy' ),
   'section' => 'vw_education_academy_single_blog_settings'
)));

$wp_customize->add_setting('vw_education_academy_single_author_icon',array(
	'default'	=> 'fas fa-user',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_single_author_icon',array(
	'label'	=> __('Add Author Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_single_blog_settings',
	'setting'	=> 'vw_education_academy_single_author_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_single_author',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_single_author',array(
    'label' => esc_html__( 'Show / Hide Author','vw-education-academy' ),
    'section' => 'vw_education_academy_single_blog_settings'
)));

$wp_customize->add_setting('vw_education_academy_single_comments_icon',array(
	'default'	=> 'fa fa-comments',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_single_comments_icon',array(
	'label'	=> __('Add Comments Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_single_blog_settings',
	'setting'	=> 'vw_education_academy_single_comments_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_single_comments',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_single_comments',array(
    'label' => esc_html__( 'Show / Hide Comments','vw-education-academy' ),
    'section' => 'vw_education_academy_single_blog_settings'
)));

$wp_customize->add_setting('vw_education_academy_single_time_icon',array(
	'default'	=> 'fas fa-clock',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_single_time_icon',array(
	'label'	=> __('Add Time Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_single_blog_settings',
	'setting'	=> 'vw_education_academy_single_time_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_single_time',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );

$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_single_time',array(
    'label' => esc_html__( 'Show / Hide Time','vw-education-academy' ),
    'section' => 'vw_education_academy_single_blog_settings'
)));

$wp_customize->add_setting( 'vw_education_academy_single_post_breadcrumb',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_single_post_breadcrumb',array(
	'label' => esc_html__( 'Show / Hide Breadcrumb','vw-education-academy' ),
	'section' => 'vw_education_academy_single_blog_settings'
)));

// Single Posts Category
	$wp_customize->add_setting( 'vw_education_academy_single_post_category',array(
	'default' => true,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
	$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_single_post_category',array(
	'label' => esc_html__( 'Show / Hide Category','vw-education-academy' ),
	'section' => 'vw_education_academy_single_blog_settings'
)));

$wp_customize->add_setting( 'vw_education_academy_toggle_tags',array(
	'default' => 0,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_toggle_tags', array(
	'label' => esc_html__( 'Show / Hide Tags','vw-education-academy' ),
	'section' => 'vw_education_academy_single_blog_settings'
)));

$wp_customize->add_setting( 'vw_education_academy_single_blog_post_navigation_show_hide',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_single_blog_post_navigation_show_hide', array(
	'label' => esc_html__( 'Show / Hide Post Navigation','vw-education-academy' ),
	'section' => 'vw_education_academy_single_blog_settings'
)));

//navigation text
$wp_customize->add_setting('vw_education_academy_single_blog_prev_navigation_text',array(
	'default'=> 'PREVIOUS',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_single_blog_prev_navigation_text',array(
	'label'	=> __('Post Navigation Text','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'PREVIOUS', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_single_blog_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_single_blog_next_navigation_text',array(
	'default'=> 'NEXT',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_single_blog_next_navigation_text',array(
	'label'	=> __('Post Navigation Text','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'NEXT', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_single_blog_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_single_blog_comment_title',array(
	'default'=> 'Leave a Reply',
	'sanitize_callback'	=> 'sanitize_text_field'
));

$wp_customize->add_control('vw_education_academy_single_blog_comment_title',array(
	'label'	=> __('Add Comment Title','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Leave a Reply', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_single_blog_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_single_blog_comment_button_text',array(
	'default'=> 'Post Comment',
	'sanitize_callback'	=> 'sanitize_text_field'
));

$wp_customize->add_control('vw_education_academy_single_blog_comment_button_text',array(
	'label'	=> __('Add Comment Button Text','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Post Comment', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_single_blog_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_single_blog_comment_width',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_single_blog_comment_width',array(
	'label'	=> __('Comment Form Width','vw-education-academy'),
	'description'	=> __('Enter a value in %. Example:50%','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '100%', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_single_blog_settings',
	'type'=> 'text'
));

// Grid layout setting
$wp_customize->add_section( 'vw_education_academy_grid_layout_settings', array(
	'title' => __( 'Grid Layout Settings', 'vw-education-academy' ),
	'panel' => 'blog_post_parent_panel',
));

$wp_customize->add_setting('vw_education_academy_grid_postdate_icon',array(
	'default'	=> 'fas fa-calendar-alt',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_grid_postdate_icon',array(
	'label'	=> __('Add Post Date Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_grid_layout_settings',
	'setting'	=> 'vw_education_academy_grid_postdate_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_grid_postdate',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_grid_postdate',array(
    'label' => esc_html__( 'Show / Hide Post Date','vw-education-academy' ),
    'section' => 'vw_education_academy_grid_layout_settings'
)));

$wp_customize->add_setting('vw_education_academy_grid_author_icon',array(
	'default'	=> 'fas fa-user',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_grid_author_icon',array(
	'label'	=> __('Add Author Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_grid_layout_settings',
	'setting'	=> 'vw_education_academy_grid_author_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_grid_author',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_grid_author',array(
	'label' => esc_html__( 'Show / Hide Author','vw-education-academy' ),
	'section' => 'vw_education_academy_grid_layout_settings'
)));

$wp_customize->add_setting('vw_education_academy_grid_comments_icon',array(
	'default'	=> 'fa fa-comments',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_grid_comments_icon',array(
	'label'	=> __('Add Comments Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_grid_layout_settings',
	'setting'	=> 'vw_education_academy_grid_comments_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting( 'vw_education_academy_grid_comments',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_grid_comments',array(
	'label' => esc_html__( 'Show / Hide Comments','vw-education-academy' ),
	'section' => 'vw_education_academy_grid_layout_settings'
)));

$wp_customize->add_setting('vw_education_academy_grid_post_meta_field_separator',array(
	'default'=> '|',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_grid_post_meta_field_separator',array(
	'label'	=> __('Add Meta Separator','vw-education-academy'),
	'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','vw-education-academy'),
	'section'=> 'vw_education_academy_grid_layout_settings',
	'type'=> 'text'
));  

$wp_customize->add_setting('vw_education_academy_display_grid_posts_settings',array(
	'default' => 'Into Blocks',
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_display_grid_posts_settings',array(
	'type' => 'select',
	'label' => __('Display Grid Posts','vw-education-academy'),
	'section' => 'vw_education_academy_grid_layout_settings',
	'choices' => array(
		'Into Blocks' => __('Into Blocks','vw-education-academy'),
	  	'Without Blocks' => __('Without Blocks','vw-education-academy')
	  ),
) );

$wp_customize->add_setting('vw_education_academy_grid_button_text',array(
	'default'=> esc_html__('Read More','vw-education-academy'),
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_grid_button_text',array(
	'label'	=> esc_html__('Add Button Text','vw-education-academy'),
	'input_attrs' => array(
    'placeholder' => esc_html__( 'Read More', 'vw-education-academy' ),
  ),
	'section'=> 'vw_education_academy_grid_layout_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_grid_button_icon',array(
	'default'	=> 'fa fa-angle-right',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_grid_button_icon',array(
	'label'	=> __('Add Button Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_grid_layout_settings',
	'setting'	=> 'vw_education_academy_grid_button_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting('vw_education_academy_grid_excerpt_suffix',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_grid_excerpt_suffix',array(
	'label'	=> __('Add Excerpt Suffix','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '[...]', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_grid_layout_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_grid_excerpt_settings',array(
        'default' => 'Excerpt',
        'transport' => 'refresh',
        'sanitize_callback' => 'vw_education_academy_sanitize_choices'
	));
	$wp_customize->add_control('vw_education_academy_grid_excerpt_settings',array(
        'type' => 'select',
        'label' => esc_html__('Grid Post Content','vw-education-academy'),
        'section' => 'vw_education_academy_grid_layout_settings',
        'choices' => array(
        	'Content' => esc_html__('Content','vw-education-academy'),
            'Excerpt' => esc_html__('Excerpt','vw-education-academy'),
            'No Content' => esc_html__('No Content','vw-education-academy')
        ),
	) );

// other settings
$OtherParentPanel = new VW_Education_Academy_WP_Customize_Panel( $wp_customize, 'vw_education_academy_other_panel_id', array(
	'title' => __( 'Others Settings', 'vw-education-academy' ),
	'panel' => 'vw_education_academy_panel_id',
));

$wp_customize->add_panel( $OtherParentPanel );

// Layout
$wp_customize->add_section( 'vw_education_academy_left_right', array(
	'title'      => esc_html__( 'General Settings', 'vw-education-academy' ),
	'panel' => 'vw_education_academy_other_panel_id'
) );

$wp_customize->add_setting('vw_education_academy_width_option',array(
    'default' => 'Full Width',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control(new VW_Education_Academy_Image_Radio_Control($wp_customize, 'vw_education_academy_width_option', array(
    'type' => 'select',
    'label' => __('Width Layouts','vw-education-academy'),
    'description' => __('Here you can change the width layout of Website.','vw-education-academy'),
    'section' => 'vw_education_academy_left_right',
    'choices' => array(
        'Full Width' => esc_url(get_template_directory_uri()).'/assets/images/full-width.png',
        'Wide Width' => esc_url(get_template_directory_uri()).'/assets/images/wide-width.png',
        'Boxed' => esc_url(get_template_directory_uri()).'/assets/images/boxed-width.png',
))));

$wp_customize->add_setting('vw_education_academy_page_layout',array(
    'default' => 'One Column',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_page_layout',array(
    'type' => 'select',
    'label' => __('Page Sidebar Layout','vw-education-academy'),
    'description' => __('Here you can change the sidebar layout for pages. ','vw-education-academy'),
    'section' => 'vw_education_academy_left_right',
    'choices' => array(
        'Left Sidebar' => __('Left Sidebar','vw-education-academy'),
        'Right Sidebar' => __('Right Sidebar','vw-education-academy'),
        'One Column' => __('One Column','vw-education-academy')
    ),
) );

$wp_customize->add_setting( 'vw_education_academy_single_page_breadcrumb',array(
	'default' => 0,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_single_page_breadcrumb',array(
	'label' => esc_html__( 'Show / Hide Page Breadcrumb','vw-education-academy' ),
	'section' => 'vw_education_academy_left_right'
)));

//Wow Animation
$wp_customize->add_setting( 'vw_education_academy_animation',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_animation',array(
    'label' => esc_html__( 'Show / Hide Animation ','vw-education-academy' ),
    'description' => __('Here you can disable overall site animation effect','vw-education-academy'),
    'section' => 'vw_education_academy_left_right'
)));

$wp_customize->add_setting('vw_education_academy_reset_all_settings',array(
  'sanitize_callback'	=> 'sanitize_text_field',
	));
	$wp_customize->add_control(new VW_Education_Academy_Reset_Custom_Control($wp_customize, 'vw_education_academy_reset_all_settings',array(
  'type' => 'reset_control',
  'label' => __('Reset All Settings', 'vw-education-academy'),
  'description' => 'vw_education_academy_reset_all_settings',
  'section' => 'vw_education_academy_left_right'
	)));

//Pre-Loader
$wp_customize->add_setting( 'vw_education_academy_loader_enable',array(
    'default' => 0,
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_loader_enable',array(
    'label' => esc_html__( 'Show / Hide Pre-Loader','vw-education-academy' ),
    'section' => 'vw_education_academy_left_right'
)));

$wp_customize->add_setting('vw_education_academy_preloader_bg_color', array(
	'default'           => '#ffbc00',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_preloader_bg_color', array(
	'label'    => __('Pre-Loader Background Color', 'vw-education-academy'),
	'section'  => 'vw_education_academy_left_right',
)));

$wp_customize->add_setting('vw_education_academy_preloader_border_color', array(
	'default'           => '#ffffff',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_preloader_border_color', array(
	'label'    => __('Pre-Loader Border Color', 'vw-education-academy'),
	'section'  => 'vw_education_academy_left_right',
)));

$wp_customize->add_setting('vw_education_academy_preloader_bg_img',array(
	'default'	=> '',
	'sanitize_callback'	=> 'esc_url_raw',
));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'vw_education_academy_preloader_bg_img',array(
    'label' => __('Preloader Background Image','vw-education-academy'),
    'section' => 'vw_education_academy_left_right'
)));

//404 Page Setting
$wp_customize->add_section('vw_education_academy_404_page',array(
	'title'	=> __('404 Page Settings','vw-education-academy'),
	'panel' => 'vw_education_academy_other_panel_id',
));	

$wp_customize->add_setting('vw_education_academy_404_page_title',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));

$wp_customize->add_control('vw_education_academy_404_page_title',array(
	'label'	=> __('Add Title','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '404 Not Found', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_404_page',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_404_page_content',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));

$wp_customize->add_control('vw_education_academy_404_page_content',array(
	'label'	=> __('Add Text','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Looks like you have taken a wrong turn, Dont worry, it happens to the best of us.', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_404_page',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_404_page_button_text',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_404_page_button_text',array(
	'label'	=> __('Add Button Text','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Return to the home page', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_404_page',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_404_page_button_icon',array(
	'default'	=> 'fa fa-angle-right',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_404_page_button_icon',array(
	'label'	=> __('Add Button Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_404_page',
	'setting'	=> 'vw_education_academy_404_page_button_icon',
	'type'		=> 'icon'
)));

//No Result Page Setting
$wp_customize->add_section('vw_education_academy_no_results_page',array(
	'title'	=> __('No Results Page Settings','vw-education-academy'),
	'panel' => 'vw_education_academy_other_panel_id',
));	

$wp_customize->add_setting('vw_education_academy_no_results_page_title',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));

$wp_customize->add_control('vw_education_academy_no_results_page_title',array(
	'label'	=> __('Add Title','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Nothing Found', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_no_results_page',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_no_results_page_content',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));

$wp_customize->add_control('vw_education_academy_no_results_page_content',array(
	'label'	=> __('Add Text','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_no_results_page',
	'type'=> 'text'
));

//Social Icon Setting
$wp_customize->add_section('vw_education_academy_social_icon_settings',array(
	'title'	=> __('Social Icons Settings','vw-education-academy'),
	'panel' => 'vw_education_academy_other_panel_id',
));	

$wp_customize->add_setting('vw_education_academy_social_icon_font_size',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_social_icon_font_size',array(
	'label'	=> __('Icon Font Size','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_social_icon_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_social_icon_padding',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_social_icon_padding',array(
	'label'	=> __('Icon Padding','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_social_icon_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_social_icon_width',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_social_icon_width',array(
	'label'	=> __('Icon Width','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_social_icon_settings',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_social_icon_height',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_social_icon_height',array(
	'label'	=> __('Icon Height','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_social_icon_settings',
	'type'=> 'text'
));

$wp_customize->add_setting( 'vw_education_academy_social_icon_border_radius', array(
	'default'              => '',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_social_icon_border_radius', array(
	'label'       => esc_html__( 'Icon Border Radius','vw-education-academy' ),
	'section'     => 'vw_education_academy_social_icon_settings',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) );

//Responsive Media Settings
$wp_customize->add_section('vw_education_academy_responsive_media',array(
	'title'	=> __('Responsive Media','vw-education-academy'),
	'panel' => 'vw_education_academy_other_panel_id',
));

$wp_customize->add_setting( 'vw_education_academy_resp_slider_hide_show',array(
  'default' => 1,
  'transport' => 'refresh',
  'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));  
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_resp_slider_hide_show',array(
  'label' => esc_html__( 'Show / Hide Slider','vw-education-academy' ),
  'section' => 'vw_education_academy_responsive_media'
)));

$wp_customize->add_setting( 'vw_education_academy_sidebar_hide_show',array(
  'default' => 1,
  'transport' => 'refresh',
  'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));  
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_sidebar_hide_show',array(
  'label' => esc_html__( 'Show / Hide Sidebar','vw-education-academy' ),
  'section' => 'vw_education_academy_responsive_media'
)));

$wp_customize->add_setting( 'vw_education_academy_resp_scroll_top_hide_show',array(
  'default' => 1,
  'transport' => 'refresh',
  'sanitize_callback' => 'vw_education_academy_switch_sanitization'
));  
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_resp_scroll_top_hide_show',array(
  'label' => esc_html__( 'Show / Hide Scroll To Top','vw-education-academy' ),
  'section' => 'vw_education_academy_responsive_media'
)));

$wp_customize->add_setting('vw_education_academy_resp_menu_toggle_btn_bg_color', array(
	'default'           => '',
	'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'vw_education_academy_resp_menu_toggle_btn_bg_color', array(
	'label'    => __('Toggle Button Bg Color', 'vw-education-academy'),
	'section'  => 'vw_education_academy_responsive_media',
)));

$wp_customize->add_setting('vw_education_academy_res_open_menu_icon',array(
	'default'	=> 'fas fa-bars',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_res_open_menu_icon',array(
	'label'	=> __('Add Open Menu Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_responsive_media',
	'setting'	=> 'vw_education_academy_res_open_menu_icon',
	'type'		=> 'icon'
)));

$wp_customize->add_setting('vw_education_academy_res_close_menus_icon',array(
	'default'	=> 'fas fa-times',
	'sanitize_callback'	=> 'sanitize_text_field'
));	
$wp_customize->add_control(new VW_Education_Academy_Fontawesome_Icon_Chooser(
    $wp_customize,'vw_education_academy_res_close_menus_icon',array(
	'label'	=> __('Add Close Menu Icon','vw-education-academy'),
	'transport' => 'refresh',
	'section'	=> 'vw_education_academy_responsive_media',
	'setting'	=> 'vw_education_academy_res_close_menus_icon',
	'type'		=> 'icon'
)));


//Woocommerce settings
$wp_customize->add_section('vw_education_academy_woocommerce_section', array(
	'title'    => __('WooCommerce Layout', 'vw-education-academy'),
	'priority' => null,
	'panel'    => 'woocommerce',
));

//Shop Page Featured Image
$wp_customize->add_setting( 'vw_education_academy_shop_featured_image_border_radius', array(
	'default'              => '0',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_shop_featured_image_border_radius', array(
	'label'       => esc_html__( 'Shop Page Featured Image Border Radius','vw-education-academy' ),
	'section'     => 'vw_education_academy_woocommerce_section',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) );

$wp_customize->add_setting( 'vw_education_academy_shop_featured_image_box_shadow', array(
	'default'              => '0',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_shop_featured_image_box_shadow', array(
	'label'       => esc_html__( 'Shop Page Featured Image Box Shadow','vw-education-academy' ),
	'section'     => 'vw_education_academy_woocommerce_section',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) ); 

//Selective Refresh
$wp_customize->selective_refresh->add_partial( 'vw_education_academy_woocommerce_shop_page_sidebar', array( 'selector' => '.post-type-archive-product .sidebar', 
	'render_callback' => 'vw_education_academy_customize_partial_vw_education_academy_woocommerce_shop_page_sidebar', ) );

//Woocommerce Shop Page Sidebar
$wp_customize->add_setting( 'vw_education_academy_woocommerce_shop_page_sidebar',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_woocommerce_shop_page_sidebar',array(
	'label' => esc_html__( 'Show / Hide Shop Page Sidebar','vw-education-academy' ),
	'section' => 'vw_education_academy_woocommerce_section'
)));

$wp_customize->add_setting('vw_education_academy_shop_page_layout',array(
    'default' => 'Right Sidebar',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_shop_page_layout',array(
    'type' => 'select',
    'label' => __('Shop Page Sidebar Layout','vw-education-academy'),
    'section' => 'vw_education_academy_woocommerce_section',
    'choices' => array(
        'Left Sidebar' => __('Left Sidebar','vw-education-academy'),
        'Right Sidebar' => __('Right Sidebar','vw-education-academy'),
    ),
) );

//Selective Refresh
$wp_customize->selective_refresh->add_partial( 'vw_education_academy_woocommerce_single_product_page_sidebar', array( 'selector' => '.single-product .sidebar', 
	'render_callback' => 'vw_education_academy_customize_partial_vw_education_academy_woocommerce_single_product_page_sidebar', ) );

//Woocommerce Single Product page Sidebar
$wp_customize->add_setting( 'vw_education_academy_woocommerce_single_product_page_sidebar',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_woocommerce_single_product_page_sidebar',array(
	'label' => esc_html__( 'Show / Hide Single Product Sidebar','vw-education-academy' ),
	'section' => 'vw_education_academy_woocommerce_section'
)));

$wp_customize->add_setting('vw_education_academy_single_product_layout',array(
    'default' => 'Right Sidebar',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_single_product_layout',array(
    'type' => 'select',
    'label' => __('Single Product Sidebar Layout','vw-education-academy'),
    'section' => 'vw_education_academy_woocommerce_section',
    'choices' => array(
        'Left Sidebar' => __('Left Sidebar','vw-education-academy'),
        'Right Sidebar' => __('Right Sidebar','vw-education-academy'),
    ),
) );

//Products per page
$wp_customize->add_setting('vw_education_academy_products_per_page',array(
	'default'=> '9',
	'sanitize_callback'	=> 'vw_education_academy_sanitize_float'
));
$wp_customize->add_control('vw_education_academy_products_per_page',array(
	'label'	=> __('Products Per Page','vw-education-academy'),
	'description' => __('Display on shop page','vw-education-academy'),
	'input_attrs' => array(
        'step'             => 1,
		'min'              => 0,
		'max'              => 50,
    ),
	'section'=> 'vw_education_academy_woocommerce_section',
	'type'=> 'number',
));

//Products per row
$wp_customize->add_setting('vw_education_academy_products_per_row',array(
	'default'=> '3',
	'sanitize_callback'	=> 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_products_per_row',array(
	'label'	=> __('Products Per Row','vw-education-academy'),
	'description' => __('Display on shop page','vw-education-academy'),
	'choices' => array(
        '2' => '2',
		'3' => '3',
		'4' => '4',
    ),
	'section'=> 'vw_education_academy_woocommerce_section',
	'type'=> 'select',
));

//Products padding
$wp_customize->add_setting('vw_education_academy_products_padding_top_bottom',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_products_padding_top_bottom',array(
	'label'	=> __('Products Padding Top Bottom','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_woocommerce_section',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_products_padding_left_right',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_products_padding_left_right',array(
	'label'	=> __('Products Padding Left Right','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_woocommerce_section',
	'type'=> 'text'
));

//Products box shadow
$wp_customize->add_setting( 'vw_education_academy_products_box_shadow', array(
	'default'              => '',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_products_box_shadow', array(
	'label'       => esc_html__( 'Products Box Shadow','vw-education-academy' ),
	'section'     => 'vw_education_academy_woocommerce_section',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) );

//Products border radius
$wp_customize->add_setting( 'vw_education_academy_products_border_radius', array(
	'default'              => '0',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_products_border_radius', array(
	'label'       => esc_html__( 'Products Border Radius','vw-education-academy' ),
	'section'     => 'vw_education_academy_woocommerce_section',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) );

$wp_customize->add_setting('vw_education_academy_products_btn_padding_top_bottom',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_products_btn_padding_top_bottom',array(
	'label'	=> __('Products Button Padding Top Bottom','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_woocommerce_section',
	'type'=> 'text'
));

$wp_customize->add_setting('vw_education_academy_products_btn_padding_left_right',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_products_btn_padding_left_right',array(
	'label'	=> __('Products Button Padding Left Right','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_woocommerce_section',
	'type'=> 'text'
));

$wp_customize->add_setting( 'vw_education_academy_products_button_border_radius', array(
	'default'              => '0',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_products_button_border_radius', array(
	'label'       => esc_html__( 'Products Button Border Radius','vw-education-academy' ),
	'section'     => 'vw_education_academy_woocommerce_section',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) );

//Products Sale Badge
$wp_customize->add_setting('vw_education_academy_woocommerce_sale_position',array(
    'default' => 'right',
    'sanitize_callback' => 'vw_education_academy_sanitize_choices'
));
$wp_customize->add_control('vw_education_academy_woocommerce_sale_position',array(
    'type' => 'select',
    'label' => __('Sale Badge Position','vw-education-academy'),
    'section' => 'vw_education_academy_woocommerce_section',
    'choices' => array(
        'left' => __('Left','vw-education-academy'),
        'right' => __('Right','vw-education-academy'),
    ),
) );

$wp_customize->add_setting('vw_education_academy_woocommerce_sale_font_size',array(
	'default'=> '',
	'sanitize_callback'	=> 'sanitize_text_field'
));
$wp_customize->add_control('vw_education_academy_woocommerce_sale_font_size',array(
	'label'	=> __('Sale Font Size','vw-education-academy'),
	'description'	=> __('Enter a value in pixels. Example:20px','vw-education-academy'),
	'input_attrs' => array(
        'placeholder' => __( '10px', 'vw-education-academy' ),
    ),
	'section'=> 'vw_education_academy_woocommerce_section',
	'type'=> 'text'
));

$wp_customize->add_setting( 'vw_education_academy_woocommerce_sale_border_radius', array(
	'default'              => '0',
	'transport' 		   => 'refresh',
	'sanitize_callback'    => 'vw_education_academy_sanitize_number_range'
) );
$wp_customize->add_control( 'vw_education_academy_woocommerce_sale_border_radius', array(
	'label'       => esc_html__( 'Sale Border Radius','vw-education-academy' ),
	'section'     => 'vw_education_academy_woocommerce_section',
	'type'        => 'range',
	'input_attrs' => array(
		'step'             => 1,
		'min'              => 1,
		'max'              => 50,
	),
) );

//Related Products
$wp_customize->add_setting( 'vw_education_academy_related_product_show_hide',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'vw_education_academy_switch_sanitization'
) );
$wp_customize->add_control( new VW_Education_Academy_Toggle_Switch_Custom_Control( $wp_customize, 'vw_education_academy_related_product_show_hide',array(
    'label' => esc_html__( 'Show / Hide Related product','vw-education-academy' ),
    'section' => 'vw_education_academy_woocommerce_section'
)));

// Has to be at the top
$wp_customize->register_panel_type( 'VW_Education_Academy_WP_Customize_Panel' );
$wp_customize->register_section_type( 'VW_Education_Academy_WP_Customize_Section' );
}

add_action( 'customize_register', 'vw_education_academy_customize_register' );

load_template( trailingslashit( get_template_directory() ) . '/inc/logo/logo-resizer.php' );

if ( class_exists( 'WP_Customize_Panel' ) ) {
	class VW_Education_Academy_WP_Customize_Panel extends WP_Customize_Panel {
    public $panel;
    public $type = 'vw_education_academy_panel';
    public function json() {

      $array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel', ) );
      $array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
      $array['content'] = $this->get_content();
      $array['active'] = $this->active();
      $array['instanceNumber'] = $this->instance_number;
      return $array;
	}
	}
}

if ( class_exists( 'WP_Customize_Section' ) ) {
	class VW_Education_Academy_WP_Customize_Section extends WP_Customize_Section {
    public $section;
    public $type = 'vw_education_academy_section';
    public function json() {

      $array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section', ) );
      $array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
      $array['content'] = $this->get_content();
      $array['active'] = $this->active();
      $array['instanceNumber'] = $this->instance_number;

      if ( $this->panel ) {
        $array['customizeAction'] = sprintf( 'Customizing &#9656; %s', esc_html( $this->manager->get_panel( $this->panel )->title ) );
      } else {
        $array['customizeAction'] = 'Customizing';
      }
      return $array;
	}
	}
}

// Enqueue our scripts and styles
function vw_education_academy_customize_controls_scripts() {
wp_enqueue_script( 'customizer-controls', get_theme_file_uri( '/assets/js/customizer-controls.js' ), array(), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'vw_education_academy_customize_controls_scripts' );

/**
* Singleton class for handling the theme's customizer integration.
*
* @since  1.0.0
* @access public
*/
final class VW_Education_Academy_Customize {

/**
 * Returns the instance.
 *
 * @since  1.0.0
 * @access public
 * @return object
 */
public static function get_instance() {

	static $instance = null;

	if ( is_null( $instance ) ) {
		$instance = new self;
		$instance->setup_actions();
	}

	return $instance;
}

/**
 * Constructor method.
 *
 * @since  1.0.0
 * @access private
 * @return void
 */
private function __construct() {}

/**
 * Sets up initial actions.
 *
 * @since  1.0.0
 * @access private
 * @return void
 */
private function setup_actions() {

	// Register panels, sections, settings, controls, and partials.
	add_action( 'customize_register', array( $this, 'sections' ) );

	// Register scripts and styles for the controls.
	add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
}

/**
 * Sets up the customizer sections.
 *
 * @since  1.0.0
 * @access public
 * @param  object  $manager
 * @return void
 */
public function sections( $manager ) {

	// Load custom sections.
	load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

	// Register custom section types.
	$manager->register_section_type( 'VW_Education_Academy_Customize_Section_Pro' );

	// Register sections.
	$manager->add_section(new VW_Education_Academy_Customize_Section_Pro($manager,'vw_education_academy_upgrade_pro_link',array(
		'priority'   => 1,
		'title'    => esc_html__( 'VW Education PRO', 'vw-education-academy' ),
		'pro_text' => esc_html__( 'UPGRADE PRO', 'vw-education-academy' ),
		'pro_url'  => esc_url('https://www.vwthemes.com/themes/academic-wordpress-theme/'),
	)));

	// Register sections.
	$manager->add_section(new VW_Education_Academy_Customize_Section_Pro($manager,'vw_education_academy_get_started_link',array(
		'priority'   => 1,
		'title'    => esc_html__( 'DOCUMENTATION', 'vw-education-academy' ),
		'pro_text' => esc_html__( 'DOCS', 'vw-education-academy' ),
		'pro_url'  => esc_url('https://preview.vwthemesdemo.com/docs/free-vw-education-academy/'),
	)));
}


/**
 * Loads theme customizer CSS.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
public function enqueue_control_scripts() {

	wp_enqueue_script( 'vw-education-academy-customize-controls', trailingslashit( esc_url(get_template_directory_uri()) ) . '/assets/js/customize-controls.js', array( 'customize-controls' ) );

	wp_enqueue_style( 'vw-education-academy-customize-controls', trailingslashit( esc_url(get_template_directory_uri()) ) . '/assets/css/customize-controls.css' );

	wp_localize_script(
	'vw-education-academy-customize-controls',
	'vw_education_academy_customizer_params',
	array(
		'ajaxurl' =>	admin_url( 'admin-ajax.php' )
	));
}
}

// Doing this customizer thang!
VW_Education_Academy_Customize::get_instance();