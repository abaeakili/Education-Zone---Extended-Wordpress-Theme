<?php
/**
 * Education Zone Theme Customizer.
 *
 * @package Education_Zone
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function education_zone_customize_register( $wp_customize ) {

    /* Option list of all post */ 
    $options_posts = array();
    $options_posts_obj = get_posts('posts_per_page=-1');
    $options_posts[''] = __( 'Choose Post', 'education-zone' );
    foreach ( $options_posts_obj as $posts ) {
        $options_posts[$posts->ID] = $posts->post_title;
    }
     /* Option list of all post */ 
    $options_pages = array();
    $options_pages_obj = get_posts('post_type=page&posts_per_page=-1');
    $options_pages[''] = __( 'Choose Page', 'education-zone' );
    foreach ( $options_pages_obj as $education_pages ) {
        $options_pages[$education_pages->ID] = $education_pages->post_title;
    }

    /* Option list of all categories */
    $args = array(
       'type'                     => 'post',
       'orderby'                  => 'name',
       'order'                    => 'ASC',
       'hide_empty'               => 1,
       'hierarchical'             => 1,
       'taxonomy'                 => 'category'
    ); 
    $option_categories = array();
    $category_lists = get_categories( $args );
    $option_categories[''] = __( 'Choose Category', 'education-zone' );
    foreach( $category_lists as $category ){
        $option_categories[$category->term_id] = $category->name;
    }


    /** Default Settings */    
    $wp_customize->add_panel( 
        'wp_default_panel',
         array(
            'priority' => 10,
            'capability' => 'edit_theme_options',
            'theme_supports' => '',
            'title' => __( 'Default Settings', 'education-zone' ),
            'description' => __( 'Default section provided by wordpress customizer.', 'education-zone' ),
        ) 
    );
    
    $wp_customize->get_section( 'title_tagline' )->panel     = 'wp_default_panel';
    $wp_customize->get_section( 'colors' )->panel            = 'wp_default_panel';
    $wp_customize->get_section( 'header_image' )->panel      = 'wp_default_panel';
    $wp_customize->get_section( 'background_image' )->panel  = 'wp_default_panel';
 
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport = 'refresh';
    $wp_customize->get_setting( 'background_image' )->transport = 'refresh';


     /** Top Header Settings */
    $wp_customize->add_section(
        'education_zone_top_header_settings',
        array(
            'title' => __( 'Top Header Settings', 'education-zone' ),
            'priority' => 10,
            'capability' => 'edit_theme_options',
        )
    );
    
    /** Email */
    $wp_customize->add_setting(
        'education_zone_email',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_email',
        array(
            'label'   => __( 'Email', 'education-zone' ),
            'section' => 'education_zone_top_header_settings',
            'type'    => 'text',
        )
    );
    
    /** Phone */
    $wp_customize->add_setting(
        'education_zone_phone',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_phone',
        array(
            'label'   => __( 'Phone', 'education-zone' ),
            'section' => 'education_zone_top_header_settings',
            'type'    => 'text',
        )
    );
    
    /** Top Menu Label */
    $wp_customize->add_setting(
        'education_zone_top_menu_label',
        array(
            'default'           => __( 'Quick Links', 'education-zone' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_top_menu_label',
        array(
            'label'   => __( 'Top Menu Label', 'education-zone' ),
            'section' => 'education_zone_top_header_settings',
            'type'    => 'text',
        )
    );

        /** Home Page Settings */
    $wp_customize->add_panel( 
        'education_zone_home_page_settings',
         array(
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'title' => __( 'Home Page Settings', 'education-zone' ),
            'description' => __( 'Customize Home Page Settings', 'education-zone' ),
        ) 
    );
    
    /** Banner Settings */
    $wp_customize->add_section(
        'education_zone_banner_settings',
        array(
            'title' => __( 'Banner Section', 'education-zone' ),
            'priority' => 21,
            'capability' => 'edit_theme_options',
            'panel' => 'education_zone_home_page_settings'
        )
    );
    
   
    $wp_customize->add_setting(
        'education_zone_ed_slider_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_slider_section',
        array(
            'label' => __( 'Enable Banner Section', 'education-zone' ),
            'section' => 'education_zone_banner_settings',
            'type' => 'checkbox',
        )
    );

    /** select post for Banner Image*/
    $wp_customize->add_setting(
        'education_zone_banner_post',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_banner_post',
        array(
            'label' => __( 'Select Post', 'education-zone' ),
            'section' => 'education_zone_banner_settings',
            'type' => 'select',
            'choices' => $options_posts,
        )
    );
	
	/** Add Override Image for Homepage **/
	$wp_customize->add_setting( 'education_zone_banner_image',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_image',
        )
    );
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( $wp_customize, 'education_zone_banner_image', 
		array(
			'label'    => __( 'Override Banner Image (optional)', 'education-zone' ),
			'section'  => 'education_zone_banner_settings',
			'settings' => 'education_zone_banner_image',
	) ) );
	
	/** Post Title Override */
    $wp_customize->add_setting(
        'education_zone_banner_title',
        array(
            'default'=> __( '', 'education-zone' ),
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize->add_control(
        'education_zone_banner_title',
        array(
              'label' => __( 'Banner Title Override', 'education-zone' ),
              'section' => 'education_zone_banner_settings', 
              'type' => 'text',
            ));
    
    /** Read More Text */
    $wp_customize->add_setting(
        'education_zone_banner_read_more',
        array(
            'default'=> __( 'Read More', 'education-zone' ),
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize->add_control(
        'education_zone_banner_read_more',
        array(
              'label' => __( 'Read More Texts', 'education-zone' ),
              'section' => 'education_zone_banner_settings', 
              'type' => 'text',
            ));
    
    /** Information Section */
    $wp_customize->add_section(
        'education_zone_information_settings',
        array(
            'title' => __( 'Information Section', 'education-zone' ),
            'priority' => 30,
            'panel' => 'education_zone_home_page_settings',
        )
    );
    
    /** Enable/Disable Information Section */
    $wp_customize->add_setting(
        'education_zone_ed_info_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_info_section',
        array(
            'label' => __( 'Enable Information Section', 'education-zone' ),
            'section' => 'education_zone_information_settings',
            'type' => 'checkbox',
        )
    );
    
    /** info One Post */
    $wp_customize->add_setting(
        'education_zone_info_one_post',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_info_one_post',
        array(
            'label' => __( 'Info One Post', 'education-zone' ),
            'section' => 'education_zone_information_settings',
            'type' => 'select',
            'choices' => $options_posts
        )
    );
    

    /** info Second Post */
    $wp_customize->add_setting(
        'education_zone_info_second_post',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_info_second_post',
        array(
            'label' => __( 'Info Second Post', 'education-zone' ),
            'section' => 'education_zone_information_settings',
            'type' => 'select',
            'choices' => $options_posts
        )
    );
    
    
    /** info third Post */
    $wp_customize->add_setting(
        'education_zone_info_third_post',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_info_third_post',
        array(
            'label' => __( 'Info Third Post', 'education-zone' ),
            'section' => 'education_zone_information_settings',
            'type' => 'select',
            'choices' => $options_posts
        )
    );
    
    
    /** Info Fourth Post */
    $wp_customize->add_setting(
        'education_zone_info_fourth_post',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_info_fourth_post',
        array(
            'label' => __( 'Info Fourth Post', 'education-zone' ),
            'section' => 'education_zone_information_settings',
            'type' => 'select',
            'choices' => $options_posts
        )
    );
    
    /** Welcome Section Settings */
    $wp_customize->add_section(
        'education_zone_welcome_section_settings',
        array(
            'title' => __( 'Welcome Section', 'education-zone' ),
            'priority' => 40,
            'capability' => 'edit_theme_options',
            'panel' => 'education_zone_home_page_settings'
        )
    );
    
    /** Enable Welcome Section */   
    $wp_customize->add_setting(
        'education_zone_ed_welcome_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_welcome_section',
        array(
            'label' => __( 'Enable Welcome Section', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'checkbox',
        )
    );

    /** Section Title */
    $wp_customize->add_setting(
        'education_zone_welcome_section_title',
        array(
            'default'=> '',
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize->add_control(
        'education_zone_welcome_section_title',
        array(
              'label' => __('Select Page','education-zone'),
              'type' => 'select',
              'choices' => $options_pages,
              'section' => 'education_zone_welcome_section_settings', 
              
            ));
    
    
    /** First Stat Counter Number */
    $wp_customize->add_setting(
        'education_zone_first_stats_number',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_number_absint',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_first_stats_number',
        array(
            'label' => __( 'First Stat Counter Number', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'number',
        )
    );
	
	/** First Stat Counter Prefix */
	
	$wp_customize->add_setting(
        'education_zone_first_stats_prefix',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_first_stats_prefix',
        array(
            'label' => __( 'First Stat Counter Number Prefix', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'text',
        )
    );
	
	/** First Stat Counter Suffix */
	
	$wp_customize->add_setting(
        'education_zone_first_stats_suffix',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_first_stats_suffix',
        array(
            'label' => __( 'First Stat Counter Number Suffix', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'text',
        )
    );
    
    /** First Stat Counter Title */
    $wp_customize->add_setting(
        'education_zone_first_stats_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_first_stats_title',
        array(
            'label' => __( 'First Stat Counter Title', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'text',
        )
    );
    
    /** Second Stat Counter Number */
    $wp_customize->add_setting(
        'education_zone_second_stats_number',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_number_absint',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_second_stats_number',
        array(
            'label' => __( 'Second Stat Counter Number', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'number',
        )
    );
    
    /** Second Stat Counter Title */
    $wp_customize->add_setting(
        'education_zone_second_stats_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_second_stats_title',
        array(
            'label' => __( 'Second Stat Counter Title', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'text',
        )
    );
    
    /** Third Stat Counter Number */
    $wp_customize->add_setting(
        'education_zone_third_stats_number',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_number_absint',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_third_stats_number',
        array(
            'label' => __( 'Third Stat Counter Number', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'number',
        )
    );
    
    /** Third Stat Counter Title */
    $wp_customize->add_setting(
        'education_zone_third_stats_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_third_stats_title',
        array(
            'label' => __( 'Third Stat Counter Title', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'text',
        )
    );
    
    /** Fourth Stat Counter Number */
    $wp_customize->add_setting(
        'education_zone_fourth_stats_number',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_number_absint',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_fourth_stats_number',
        array(
            'label' => __( 'Fourth Stat Counter Number', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'number',
        )
    );
    
    /** Fourth Stat Counter Title */
    $wp_customize->add_setting(
        'education_zone_fourth_stats_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_fourth_stats_title',
        array(
            'label' => __( 'Fourth Stat Counter Title', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'text',
        )
    );
	
	/** Fourth Stat Counter Suffix */
	
	$wp_customize->add_setting(
        'education_zone_fourth_stats_suffix',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_fourth_stats_suffix',
        array(
            'label' => __( 'Fourth Stat Counter Number Suffix', 'education-zone' ),
            'section' => 'education_zone_welcome_section_settings',
            'type' => 'text',
        )
    );
    /** welcome Section Ends */
    
    /** Featured Courses Section Settings */
    $wp_customize->add_section(
        'education_zone_featured_courses_section_settings',
        array(
            'title' => __( 'Featured Courses Section', 'education-zone' ),
            'priority' => 50,
            'capability' => 'edit_theme_options',
            'panel' => 'education_zone_home_page_settings'
        )
    );
    
   
    $wp_customize->add_setting(
        'education_zone_ed_courses_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_courses_section',
        array(
            'label' => __( 'Enable Featured Courses Section', 'education-zone' ),
            'section' => 'education_zone_featured_courses_section_settings',
            'type' => 'checkbox',
        )
    );

    /** Section Title */
    $wp_customize->add_setting(
        'education_zone_courses_section_title',
        array(
            'default'=> '',
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize-> add_control(
        'education_zone_courses_section_title',
        array(
              'label' => __('Select Page','education-zone'),
              'type' => 'select',
              'choices' => $options_pages,
              'section' => 'education_zone_featured_courses_section_settings', 
            ));
    
   
    
    /** Featured Course Post First */
    $wp_customize->add_setting(
        'education_zone_featured_courses_post_one',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_featured_courses_post_one',
        array(
            'label' => __( 'Featured Course Post First', 'education-zone' ),
            'section' => 'education_zone_featured_courses_section_settings',
            'type' => 'select',
            'choices' => $options_posts
        ));
		
	/** Add Override Image for First Course **/
	$wp_customize->add_setting( 'education_zone_featured_courses_image_one',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_image',
        )
    );
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( $wp_customize, 'education_zone_featured_courses_image_one', 
		array(
			'label'    => __( 'Override 1st Image (optional)', 'education-zone' ),
			'section'  => 'education_zone_featured_courses_section_settings',
			'settings' => 'education_zone_featured_courses_image_one',
	) ) );
  
  	/** Add Override Text for First Course */
    $wp_customize->add_setting(
        'education_zone_featured_courses_text_one',
        array(
            'default'=> __( '', 'education-zone' ),
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize->add_control(
        'education_zone_featured_courses_text_one',
        array(
              'label' => __( 'Override 1st Post Text (optional)', 'education-zone' ),
              'section' => 'education_zone_featured_courses_section_settings', 
              'type' => 'text',
            ));
    
    /** Featured Course Post Second */
    $wp_customize->add_setting(
        'education_zone_featured_courses_post_two',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        ));
    
    $wp_customize->add_control(
        'education_zone_featured_courses_post_two',
        array(
            'label' => __( 'Featured Course Post Second', 'education-zone' ),
            'section' => 'education_zone_featured_courses_section_settings',
            'type' => 'select',
            'choices' => $options_posts
        ));
		
	/** Add Override Image for Second Course **/
	$wp_customize->add_setting( 'education_zone_featured_courses_image_two',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_image',
        )
    );
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( $wp_customize, 'education_zone_featured_courses_image_two', 
		array(
			'label'    => __( 'Override 2nd Image (optional)', 'education-zone' ),
			'section'  => 'education_zone_featured_courses_section_settings',
			'settings' => 'education_zone_featured_courses_image_two',
	) ) );
  
    /** Add Override Text for Second Course */
    $wp_customize->add_setting(
        'education_zone_featured_courses_text_two',
        array(
            'default'=> __( '', 'education-zone' ),
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize->add_control(
        'education_zone_featured_courses_text_two',
        array(
              'label' => __( 'Override 2nd Post Text (optional)', 'education-zone' ),
              'section' => 'education_zone_featured_courses_section_settings', 
              'type' => 'text',
            ));

    /** Featured Course Post Third */
    $wp_customize->add_setting(
        'education_zone_featured_courses_post_three',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        ));
    
    $wp_customize->add_control(
        'education_zone_featured_courses_post_three',
        array(
            'label' => __( 'Featured Course Post Third', 'education-zone' ),
            'section' => 'education_zone_featured_courses_section_settings',
            'type' => 'select',
            'choices' => $options_posts
        ));
		
	/** Add Override Image for Third Course **/
	$wp_customize->add_setting( 'education_zone_featured_courses_image_three',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_image',
        )
    );
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( $wp_customize, 'education_zone_featured_courses_image_three', 
		array(
			'label'    => __( 'Override 3rd Image (optional)', 'education-zone' ),
			'section'  => 'education_zone_featured_courses_section_settings',
			'settings' => 'education_zone_featured_courses_image_three',
	) ) );
  
    	/** Add Override Text for Third Course */
    $wp_customize->add_setting(
        'education_zone_featured_courses_text_three',
        array(
            'default'=> __( '', 'education-zone' ),
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize->add_control(
        'education_zone_featured_courses_text_three',
        array(
              'label' => __( 'Override 3rd Post Text (optional)', 'education-zone' ),
              'section' => 'education_zone_featured_courses_section_settings', 
              'type' => 'text',
            ));

    /** Featured Course Post Fourth */
    $wp_customize->add_setting(
        'education_zone_featured_courses_post_four',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        ));
    
    $wp_customize->add_control(
        'education_zone_featured_courses_post_four',
        array(
            'label' => __( 'Featured Course Post Fourth', 'education-zone' ),
            'section' => 'education_zone_featured_courses_section_settings',
            'type' => 'select',
            'choices' => $options_posts
        ));
		
	/** Add Override Image for Fourth Course **/
	$wp_customize->add_setting( 'education_zone_featured_courses_image_four',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_image',
        )
    );
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( $wp_customize, 'education_zone_featured_courses_image_four', 
		array(
			'label'    => __( 'Override 4th Image (optional)', 'education-zone' ),
			'section'  => 'education_zone_featured_courses_section_settings',
			'settings' => 'education_zone_featured_courses_image_four',
	) ) );
  
    	/** Add Override Text for Fourth Course */
    $wp_customize->add_setting(
        'education_zone_featured_courses_text_four',
        array(
            'default'=> __( '', 'education-zone' ),
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize->add_control(
        'education_zone_featured_courses_text_four',
        array(
              'label' => __( 'Override 4th Post Text (optional)', 'education-zone' ),
              'section' => 'education_zone_featured_courses_section_settings', 
              'type' => 'text',
            ));


    /** Extra Info Section Settings */
    $wp_customize->add_section(
        'education_zone_extra_info_section_settings',
        array(
            'title' => __( 'Extra Info Section', 'education-zone' ),
            'priority' => 60,
            'capability' => 'edit_theme_options',
            'panel' => 'education_zone_home_page_settings'
        )
    );
    
    /** Enable Extra Info Section */   
    $wp_customize->add_setting(
        'education_zone_ed_extra_info_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_extra_info_section',
        array(
            'label' => __( 'Enable Extra Info Section', 'education-zone' ),
            'section' => 'education_zone_extra_info_section_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Section Title */
    $wp_customize->add_setting(
        'education_zone_extra_info_section_title',
        array(
            'default'=> '',
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize-> add_control(
        'education_zone_extra_info_section_title',
        array(
              'label' => __('Select Page','education-zone'),
              'type' => 'select',
              'choices' => $options_pages,
              'section' => 'education_zone_extra_info_section_settings', 
              
            ));
    

    /** CTA First Button */
    $wp_customize->add_setting(
        'education_zone_extra_info_section_button_one',
        array(
            'default'=> '',
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize-> add_control(
        'education_zone_extra_info_section_button_one',
        array(
              'label' => __('CTA First Button','education-zone'),
              'section' => 'education_zone_extra_info_section_settings', 
              'type' => 'text',
            ));

    /** CTA First Button Link */
    $wp_customize->add_setting(
        'education_zone_extra_info_button_one_url',
        array(
            'default'=> '',
            'sanitize_callback'=> 'esc_url_raw'
            )
        );
    
     $wp_customize-> add_control(
        'education_zone_extra_info_button_one_url',
        array(
              'label' => __('CTA First Button Link','education-zone'),
              'section' => 'education_zone_extra_info_section_settings', 
              'type' => 'text',
            ));
     
     /** CTA Second Button */ 
     $wp_customize->add_setting(
        'education_zone_extra_info_section_button_two',
        array(
            'default'=> '',
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize-> add_control(
        'education_zone_extra_info_section_button_two',
        array(
              'label' => __('CTA Second Button','education-zone'),
              'section' => 'education_zone_extra_info_section_settings', 
              'type' => 'text',
            ));

    /** CTA Second Button Link */
    $wp_customize->add_setting(
        'education_zone_extra_info_button_two_url',
        array(
            'default'=> '',
            'sanitize_callback'=> 'esc_url_raw'
            ));
    $wp_customize-> add_control(
        'education_zone_extra_info_button_two_url',
        array(
              'label' => __('CTA Second Button Link','education-zone'),
              'section' => 'education_zone_extra_info_section_settings', 
              'type' => 'text',
            ));

    /** Why Choose Us Section Settings */
    $wp_customize->add_section(
        'education_zone_choose_us_section_settings',
        array(
            'title' => __( 'Why Choose Us Section', 'education-zone' ),
            'priority' => 70,
            'capability' => 'edit_theme_options',
            'panel' => 'education_zone_home_page_settings'
        )
    );
    
   /** Enable Why Choose Us Section */
    $wp_customize->add_setting(
        'education_zone_ed_choose_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_choose_section',
        array(
            'label' => __( 'Enable Why Choose Us Section', 'education-zone' ),
            'section' => 'education_zone_choose_us_section_settings',
            'type' => 'checkbox',
        )
    );

    /** Section Title */
    $wp_customize->add_setting(
        'education_zone_choose_section_title',
        array(
            'default'=> '',
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize-> add_control(
        'education_zone_choose_section_title',
        array(
              'label' => __('Select Page','education-zone'),
              'type' => 'select',
              'choices' => $options_pages,
              'section' => 'education_zone_choose_us_section_settings', 
            
            ));
    

    /** Choose First Post */
    $wp_customize->add_setting(
        'education_zone_why_choose_post_one',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_why_choose_post_one',
        array(
            'label' => __( 'Choose First Post', 'education-zone' ),
            'section' => 'education_zone_choose_us_section_settings',
            'type' => 'select',
            'choices' => $options_posts
        ));
    
    /** Choose Second Post */
    $wp_customize->add_setting(
        'education_zone_why_choose_post_two',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        ));
    
    $wp_customize->add_control(
        'education_zone_why_choose_post_two',
        array(
            'label' => __( 'Choose Second Post', 'education-zone' ),
            'section' => 'education_zone_choose_us_section_settings',
            'type' => 'select',
            'choices' => $options_posts
        ));

    /** Choose Third Post */
    $wp_customize->add_setting(
        'education_zone_why_choose_post_three',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        ));
    
    $wp_customize->add_control(
        'education_zone_why_choose_post_three',
        array(
            'label' => __( 'Choose Third Post', 'education-zone' ),
            'section' => 'education_zone_choose_us_section_settings',
            'type' => 'select',
            'choices' => $options_posts
        ));
		
	/** Add Override Image for Third Post **/
	$wp_customize->add_setting( 'education_zone_why_choose_image_three',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_image',
        )
    );
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( $wp_customize, 'education_zone_why_choose_image_three', 
		array(
			'label'    => __( 'Override 3rd Image (optional)', 'education-zone' ),
			'section'  => 'education_zone_choose_us_section_settings',
			'settings' => 'education_zone_why_choose_image_three',
	) ) );

    /** Choose Fourth Post */
    $wp_customize->add_setting(
        'education_zone_why_choose_post_four',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        ));
    
    $wp_customize->add_control(
        'education_zone_why_choose_post_four',
        array(
            'label' => __( 'Choose Fourth Post', 'education-zone' ),
            'section' => 'education_zone_choose_us_section_settings',
            'type' => 'select',
            'choices' => $options_posts
        ));

    
    /** Testimonials  Section Settings */
    $wp_customize->add_section(
        'education_zone_testimonials_section_settings',
        array(
            'title' => __( 'Testimonials  Section', 'education-zone' ),
            'priority' => 70,
            'capability' => 'edit_theme_options',
            'panel' => 'education_zone_home_page_settings'
        )
    );

    /** Enable Testimonials  Section */   
    $wp_customize->add_setting(
        'education_zone_ed_testimonials_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_testimonials_section',
        array(
            'label' => __( 'Enable Testimonials Section', 'education-zone' ),
            'section' => 'education_zone_testimonials_section_settings',
            'type' => 'checkbox',
        ));
    
    /** Section Title */
    $wp_customize->add_setting(
        'education_zone_testimonials_section_title',
        array(
            'default'=> '',
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    $wp_customize-> add_control(
        'education_zone_testimonials_section_title',
        array(
              'label' => __('Select Page','education-zone'),
              'type' => 'select',
              'choices' => $options_pages,
              'section' => 'education_zone_testimonials_section_settings', 
         
            ));

    /** Choose Testimonials Category */
    $wp_customize->add_setting(
        'education_zone_testimonial_category',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        ));
    
    $wp_customize->add_control(
        'education_zone_testimonial_category',
        array(
            'label' => __( 'Choose Testimonials Category', 'education-zone' ),
            'section' => 'education_zone_testimonials_section_settings',
            'type' => 'select',
            'choices' => $option_categories
        ));

    /** Blog Section Settings */
    $wp_customize->add_section(
        'education_zone_blog_section_settings',
        array(
            'title' => __( 'Blog Section', 'education-zone' ),
            'priority' => 71,
            'capability' => 'edit_theme_options',
            'panel' => 'education_zone_home_page_settings'
			        )
    );
    
   /** Enable Blog Section */
    $wp_customize->add_setting(
        'education_zone_ed_blog_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_blog_section',
        array(
            'label' => __( 'Enable Blog Section', 'education-zone' ),
            'section' => 'education_zone_blog_section_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Show/Hide Blog Date */
    $wp_customize->add_setting(
        'education_zone_ed_blog_date',
        array(
            'default' => '1',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_blog_date',
        array(
            'label' => __( 'Show Blog Date', 'education-zone' ),
            'section' => 'education_zone_blog_section_settings',
            'type' => 'checkbox',
        )
    );
     
    /** Blog Section Title */
    $wp_customize->add_setting(
        'education_zone_blog_section_title',
        array(
            'default'=> '',
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize-> add_control(
        'education_zone_blog_section_title',
        array(
              'label' => __('Select Page','education-zone'),
              'type' => 'select',
              'choices' => $options_pages,
              'section' => 'education_zone_blog_section_settings', 
          
            ));

    /** Blog Section Read More Text */
    $wp_customize->add_setting(
        'education_zone_blog_section_readmore',
        array(
            'default' => __( 'Read More', 'education-zone' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_blog_section_readmore',
        array(
            'label' => __( 'Blog Section Read More Text', 'education-zone' ),
            'section' => 'education_zone_blog_section_settings',
            'type' => 'text',
        )
    );
	
	    /** Blog Section Add Post Category */
    $wp_customize->add_setting(
        'education_zone_blog_section_category',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_blog_section_category',
        array(
            'label' => __( 'Which Category of Posts do you want shown?', 'education-zone' ),
            'section' => 'education_zone_blog_section_settings',
            'type' => 'select',
            'choices' => $option_categories,
        )
    );
    /** Blog Section Ends */
	
	/** News Section Settings */
    $wp_customize->add_section(
        'education_zone_news_section_settings',
        array(
            'title' => __( 'News Section', 'education-zone' ),
            'priority' => 71,
            'capability' => 'edit_theme_options',
            'panel' => 'education_zone_home_page_settings'
        )
    );
	
	/** Enable News Section */
    $wp_customize->add_setting(
        'education_zone_ed_news_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_news_section',
        array(
            'label' => __( 'Enable News Section', 'education-zone' ),
            'section' => 'education_zone_news_section_settings',
            'type' => 'checkbox',
        )
    );
	
	/** News Section Title */
    $wp_customize->add_setting(
        'education_zone_news_section_title',
        array(
            'default'=> '',
            'sanitize_callback'=> 'sanitize_text_field'
            )
        );
    
    $wp_customize-> add_control(
        'education_zone_news_section_title',
        array(
              'label' => __('Select Page','education-zone'),
              'type' => 'select',
              'choices' => $options_pages,
              'section' => 'education_zone_news_section_settings', 
          
            ));
	
	/** News Section Add Post Category */
    $wp_customize->add_setting(
        'education_zone_news_section_category',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_news_section_category',
        array(
            'label' => __( 'Show Latest Post from Which Category?', 'education-zone' ),
            'section' => 'education_zone_news_section_settings',
            'type' => 'select',
            'choices' => $option_categories,
        )
    );
  
  	/** Link to Category page? */
    $wp_customize->add_setting(
        'education_zone_ed_news_section_link_one',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_news_section_link_one',
        array(
            'label' => __( 'URL Link to Category Page?', 'education-zone' ),
            'section' => 'education_zone_news_section_settings',
            'type' => 'checkbox',
        )
    );
  
  	/** News Section Add Second Post Category */
    $wp_customize->add_setting(
        'education_zone_news_section_category_two',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_news_section_category_two',
        array(
            'label' => __( 'Show Latest Post from Which Category?', 'education-zone' ),
            'section' => 'education_zone_news_section_settings',
            'type' => 'select',
            'choices' => $option_categories,
        )
    );
  
    	/** Link to 2nd Category page? */
    $wp_customize->add_setting(
        'education_zone_ed_news_section_link_two',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_news_section_link_two',
        array(
            'label' => __( 'URL Link to Category Page?', 'education-zone' ),
            'section' => 'education_zone_news_section_settings',
            'type' => 'checkbox',
        )
    );
	
	/** News Section Ends **/

    /** Gallery Section Settings */
    $wp_customize->add_section(
        'education_zone_gallery_section_settings',
        array(
            'title' => __( 'Gallery Section', 'education-zone' ),
            'priority' => 72,
            'capability' => 'edit_theme_options',
            'panel' => 'education_zone_home_page_settings'
        )
    );
    
    /** Enable Gallery Section */
    $wp_customize->add_setting(
        'education_zone_ed_gallery_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_gallery_section',
        array(
            'label' => __( 'Enable Gallery Section', 'education-zone' ),
            'section' => 'education_zone_gallery_section_settings',
            'type' => 'checkbox',
        )
    );

    /** select post for gallery */
    $wp_customize->add_setting(
        'education_zone_gallery_post',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_gallery_post',
        array(
            'label' => __( 'Select gallery Post', 'education-zone' ),
            'section' => 'education_zone_gallery_section_settings',
            'type' => 'select',
            'choices' => $options_posts,
        )
    );
	
	/** select gallery link url */
    $wp_customize->add_setting(
        'education_zone_gallery_link',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_gallery_link',
        array(
            'label' => __( 'Select gallery Link URL', 'education-zone' ),
            'section' => 'education_zone_gallery_section_settings',
            'type' => 'text',
        )
    );
	
	
	
    /** Search Section Settings */
    $wp_customize->add_section(
        'education_zone_search_section_settings',
        array(
            'title' => __( 'Search Section', 'education-zone' ),
            'priority' => 73,
            'capability' => 'edit_theme_options',
            'panel' => 'education_zone_home_page_settings'
        )
    );
    
    /** Enable Search Section */
    $wp_customize->add_setting(
        'education_zone_ed_search_section',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_search_section',
        array(
            'label' => __( 'Enable Search Section', 'education-zone' ),
            'section' => 'education_zone_search_section_settings',
            'type' => 'checkbox',
        )
    );
   
    /** Search Info */
    $wp_customize->add_setting(
        'education_zone_search_section_content',
        array(
            'default' => __( 'Can we help you find what you are looking for?', 'education-zone' ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_search_section_content',
        array(
            'label' => __( 'Search Info', 'education-zone' ),
            'section' => 'education_zone_search_section_settings',
            'type' => 'textarea',
        )
    );

    /** BreadCrumb Settings */
    $wp_customize->add_section(
        'education_zone_breadcrumb_settings',
        array(
            'title' => __( 'Breadcrumb Settings', 'education-zone' ),
            'priority' => 50,
            'capability' => 'edit_theme_options',
        )
    );
   

    /** Enable/Disable BreadCrumb */
    $wp_customize->add_setting(
        'education_zone_ed_breadcrumb',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_breadcrumb',
        array(
            'label' => __( 'Enable Breadcrumb', 'education-zone' ),
            'section' => 'education_zone_breadcrumb_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Show/Hide Current */
    $wp_customize->add_setting(
        'education_zone_ed_current',
        array(
            'default' => '1',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_current',
        array(
            'label' => __( 'Show current', 'education-zone' ),
            'section' => 'education_zone_breadcrumb_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Home Text */
    $wp_customize->add_setting(
        'education_zone_breadcrumb_home_text',
        array(
            'default' => __( 'Home', 'education-zone' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_breadcrumb_home_text',
        array(
            'label' => __( 'Breadcrumb Home Text', 'education-zone' ),
            'section' => 'education_zone_breadcrumb_settings',
            'type' => 'text',
        )
    );
    
    /** Breadcrumb Separator */
    $wp_customize->add_setting(
        'education_zone_breadcrumb_separator',
        array(
            'default' => __( '>', 'education-zone' ),
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_breadcrumb_separator',
        array(
            'label' => __( 'Breadcrumb Separator', 'education-zone' ),
            'section' => 'education_zone_breadcrumb_settings',
            'type' => 'text',
        )
    );
    /** BreadCrumb Settings Ends */
    
    /** Social Settings */
    $wp_customize->add_section(
        'education_zone_social_settings',
        array(
            'title' => __( 'Social Settings', 'education-zone' ),
            'description' => __( 'Leave blank if you do not want to show the social link.', 'education-zone' ),
            'priority' => 60,
            'capability' => 'edit_theme_options',
        )
    );
    
    /** Enable Social Links */   
    $wp_customize->add_setting(
        'education_zone_ed_social',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_ed_social',
        array(
            'label' => __( 'Enable Social Links', 'education-zone' ),
            'section' => 'education_zone_social_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Facebook */
    $wp_customize->add_setting(
        'education_zone_facebook',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_facebook',
        array(
            'label' => __( 'Facebook', 'education-zone' ),
            'section' => 'education_zone_social_settings',
            'type' => 'text',
        )
    );
    
    /** Twitter */
    $wp_customize->add_setting(
        'education_zone_twitter',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_twitter',
        array(
            'label' => __( 'Twitter', 'education-zone' ),
            'section' => 'education_zone_social_settings',
            'type' => 'text',
        )
    );
    
    /** youtube */
    $wp_customize->add_setting(
        'education_zone_youtube',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_youtube',
        array(
            'label' => __( 'Youtube', 'education-zone' ),
            'section' => 'education_zone_social_settings',
            'type' => 'text',
        )
    );
    
    /** Google Plus */
    $wp_customize->add_setting(
        'education_zone_gplus',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_gplus',
        array(
            'label' => __( 'Google Plus', 'education-zone' ),
            'section' => 'education_zone_social_settings',
            'type' => 'text',
        )
    );
    
    /** Instagram */
    $wp_customize->add_setting(
        'education_zone_instagram',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_instagram',
        array(
            'label' => __( 'Instagram', 'education-zone' ),
            'section' => 'education_zone_social_settings',
            'type' => 'text',
        )
    );
    
    /** LinkedIn */
    $wp_customize->add_setting(
        'education_zone_linkedin',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_linkedin',
        array(
            'label' => __( 'LinkedIn', 'education-zone' ),
            'section' => 'education_zone_social_settings',
            'type' => 'text',
        )
    );
    
    /** Pinterest */
    $wp_customize->add_setting(
        'education_zone_pinterest',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_pinterest',
        array(
            'label' => __( 'Pinterest', 'education-zone' ),
            'section' => 'education_zone_social_settings',
            'type' => 'text',
        )
    );
	
	 /** Miscellaneous Settings */
    $wp_customize->add_section(
        'education_zone_misc_settings',
        array(
            'title' => __( 'Misc Settings', 'education-zone' ),
            'priority' => 70,
            'capability' => 'edit_theme_options',
        )
    );
	
	/** Toggle Metadata on Posts **/
	
	$wp_customize->add_setting(
        'education_zone_metadata',
        array(
            'default' => '',
            'sanitize_callback' => 'education_zone_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'education_zone_metadata',
        array(
            'label' => __( 'Do not show metadata on posts', 'education-zone' ),
            'section' => 'education_zone_misc_settings',
            'type' => 'checkbox',
        )
    );
    
    /**
     * Sanitization Functions
     * 
     * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php 
     */ 
    function education_zone_sanitize_checkbox( $checked ){
        // Boolean check.
	   return ( ( isset( $checked ) && true == $checked ) ? true : false );
    }

    function education_zone_sanitize_select( $input, $setting ) {
        // Ensure input is a slug.
        $input = sanitize_key( $input );
        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control( $setting->id )->choices;
        // If the input is a valid key, return it; otherwise, return the default.
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
    }
    
    function education_zone_sanitize_number_absint( $number, $setting ) {
        // Ensure $number is an absolute integer (whole number, zero or greater).
        $number = absint( $number );
        // If the input is an absolute integer, return it; otherwise, return the default
        return ( $number ? $number : $setting->default );
    }
	
	function education_zone_sanitize_image( $image, $setting ) {
	/*
	 * Array of valid image file types.
	 *
	 * The array includes image mime types that are included in wp_get_mime_types()
	 */
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon'
    );
	// Return an array with file extension and mime_type.
    $file = wp_check_filetype( $image, $mimes );
	// If $image has a valid mime_type, return it; otherwise, return the default.
    return ( $file['ext'] ? $image : $setting->default );
	}
    
    
}
add_action( 'customize_register', 'education_zone_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function education_zone_customize_preview_js() {
	wp_enqueue_script( 'education_zone_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'education_zone_customize_preview_js' );

