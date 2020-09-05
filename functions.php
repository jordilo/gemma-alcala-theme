<?php

//Add thumbnail, automatic feed links and title tag support
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');
add_theme_support('title-tag');

/**
 *
 */
$q = "SELECT wp.id FROM {$wpdb->prefix}posts as wp "
    . "LEFT join {$wpdb->prefix}postmeta as mt on mt.post_id = wp.id and mt.meta_key = 'item-order-priority'"
    . "WHERE mt.meta_key IS NULL";
$posts___ = $wpdb->get_results($q);
foreach ($posts___ as $p_) {
    $wpdb->insert("{$wpdb->prefix}postmeta", array("post_id" => $p_->id, "meta_key" => 'item-order-priority', "meta_value" => 0));
}
;

//Add content width (desktop default)
if (!isset($content_width)) {
    $content_width = 768;
}

//Add menu support and register main menu
if (function_exists('register_nav_menus')) {
    register_nav_menus(
        array(
            'main_menu' => 'Main Menu',
        )
    );
}

// filter the Gravity Forms button type
add_filter('gform_submit_button', 'form_submit_button', 8, 2);
function form_submit_button($button, $form)
{
    return "<button class='button btn' id='gform_submit_button_{$form["id"]}'><span>{$form['button']['text']}</span></button>";
}

// Register sidebar
add_action('widgets_init', 'theme_register_sidebar');
function theme_register_sidebar()
{
    if (function_exists('register_sidebar')) {
        register_sidebar(array(
            'id' => 'sidebar-1',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4>',
            'after_title' => '</h4>',
        ));
    }
}

// Bootstrap_Walker_Nav_Menu setup

add_action('after_setup_theme', 'bootstrap_setup');

if (!function_exists('bootstrap_setup')):

    function bootstrap_setup()
{

        add_action('init', 'register_menu');

        function register_menu()
    {
            register_nav_menu('top-bar', 'Bootstrap Top Menu');
        }

        class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu
    {

            function start_lvl(&$output, $depth = 0, $args = array())
        {

                $indent = str_repeat("\t", $depth);
                $output .= "\n$indent<ul class=\"dropdown-menu\">\n";

            }

            function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
        {

                if (!is_object($args)) {
                    return; // menu has not been configured
                }

                $indent = ($depth) ? str_repeat("\t", $depth) : '';

                $li_attributes = '';
                $class_names = $value = '';

                $classes = empty($item->classes) ? array() : (array) $item->classes;
                $classes[] = ($args->has_children) ? 'dropdown' : '';
                $classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
                $classes[] = 'menu-item-' . $item->ID;

                $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
                $class_names = ' class="' . esc_attr($class_names) . '"';

                $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
                $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

                $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

                $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
                $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
                $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
                $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
                $attributes .= ($args->has_children) ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';

                $item_output = $args->before;
                $item_output .= '<a' . $attributes . '>';
                $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
                $item_output .= ($args->has_children) ? ' <b class="caret"></b></a>' : '</a>';
                $item_output .= $args->after;

                $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
            }

            function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
        {

                if (!$element) {
                    return;
                }

                $id_field = $this->db_fields['id'];

                //display this element
                if (is_array($args[0])) {
                    $args[0]['has_children'] = !empty($children_elements[$element->$id_field]);
                } else if (is_object($args[0])) {
                $args[0]->has_children = !empty($children_elements[$element->$id_field]);
            }

            $cb_args = array_merge(array(&$output, $element, $depth), $args);
            call_user_func_array(array(&$this, 'start_el'), $cb_args);

            $id = $element->$id_field;

            // descend only when the depth is right and there are childrens for this element
            if (($max_depth == 0 || $max_depth > $depth + 1) && isset($children_elements[$id])) {

                foreach ($children_elements[$id] as $child) {

                    if (!isset($newlevel)) {
                        $newlevel = true;
                        //start the child delimiter
                        $cb_args = array_merge(array(&$output, $depth), $args);
                        call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
                    }
                    $this->display_element($child, $children_elements, $max_depth, $depth + 1, $args, $output);
                }
                unset($children_elements[$id]);
            }

            if (isset($newlevel) && $newlevel) {
                //end the child delimiter
                $cb_args = array_merge(array(&$output, $depth), $args);
                call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
            }

            //end this element
            $cb_args = array_merge(array(&$output, $element, $depth), $args);
            call_user_func_array(array(&$this, 'end_el'), $cb_args);
        }
    }
}
endif;

// START THEME OPTIONS
// custom theme options for user in admin area - Appearance > Theme Options
function pu_theme_menu()
{
    add_theme_page('Theme Option', 'Theme Options', 'manage_options', 'pu_theme_options.php', 'pu_theme_page');
}
add_action('admin_menu', 'pu_theme_menu');

function pu_theme_page()
{
    ?>
    <div class="section panel">
      <h1>Custom Theme Options</h1>
      <form method="post" enctype="multipart/form-data" action="options.php">
      <hr>
        <?php

    settings_fields('pu_theme_options');

    do_settings_sections('pu_theme_options.php');
    echo '<hr>';
    ?>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes')?>" />
            </p>
      </form>
    </div>
    <?php
}

add_action('admin_init', 'pu_register_settings');

/**
 * Function to register the settings
 */
function pu_register_settings()
{
    // Register the settings with Validation callback
    register_setting('pu_theme_options', 'pu_theme_options');

    // Add settings section
    add_settings_section('pu_text_section', 'Social Links', 'pu_display_section', 'pu_theme_options.php');

    // Create textbox field
    $field_args = array(
        'type' => 'text',
        'id' => 'twitter_link',
        'name' => 'twitter_link',
        'desc' => 'Twitter Link - Example: http://twitter.com/username',
        'std' => '',
        'label_for' => 'twitter_link',
        'class' => 'css_class',
    );

    // Add twitter field
    add_settings_field('twitter_link', 'Twitter', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args);

    $field_args = array(
        'type' => 'text',
        'id' => 'facebook_link',
        'name' => 'facebook_link',
        'desc' => 'Facebook Link - Example: http://facebook.com/username',
        'std' => '',
        'label_for' => 'facebook_link',
        'class' => 'css_class',
    );

    // Add facebook field
    add_settings_field('facebook_link', 'Facebook', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args);

    $field_args = array(
        'type' => 'text',
        'id' => 'gplus_link',
        'name' => 'gplus_link',
        'desc' => 'Google+ Link - Example: http://plus.google.com/user_id',
        'std' => '',
        'label_for' => 'gplus_link',
        'class' => 'css_class',
    );

    // Add Google+ field
    add_settings_field('gplus_link', 'Google+', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args);

    $field_args = array(
        'type' => 'text',
        'id' => 'youtube_link',
        'name' => 'youtube_link',
        'desc' => 'Youtube Link - Example: https://www.youtube.com/channel/channel_id',
        'std' => '',
        'label_for' => 'youtube_link',
        'class' => 'css_class',
    );

    // Add youtube field
    add_settings_field('youtube_ink', 'Youtube', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args);

    $field_args = array(
        'type' => 'text',
        'id' => 'linkedin_link',
        'name' => 'linkedin_link',
        'desc' => 'LinkedIn Link - Example: http://linkedin.com/in/username',
        'std' => '',
        'label_for' => 'linkedin_link',
        'class' => 'css_class',
    );

    // Add LinkedIn field
    add_settings_field('linkedin_link', 'LinkedIn', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args);

    $field_args = array(
        'type' => 'text',
        'id' => 'instagram_link',
        'name' => 'instagram_link',
        'desc' => 'Instagram Link - Example: http://instagram.com/username',
        'std' => '',
        'label_for' => 'instagram_link',
        'class' => 'css_class',
    );

    // Add Instagram field
    add_settings_field('instagram_link', 'Instagram', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args);

    // Add settings section title here
    add_settings_section('section_name_here', 'Section Title Here', 'pu_display_section', 'pu_theme_options.php');

    // Create textarea field
    $field_args = array(
        'type' => 'textarea',
        'id' => 'settings_field_1',
        'name' => 'settings_field_1',
        'desc' => 'Setting Description Here',
        'std' => '',
        'label_for' => 'settings_field_1',
    );

    // section_name should be same as section_name above (line 116)
    add_settings_field('settings_field_1', 'Setting Title Here', 'pu_display_setting', 'pu_theme_options.php', 'section_name_here', $field_args);

    // Copy lines 118 through 129 to create additional field within that section
    // Copy line 116 for a new section and then 118-129 to create a field in that section
}

// allow wordpress post editor functions to be used in theme options
function pu_display_setting($args)
{
    extract($args);

    $option_name = 'pu_theme_options';

    $options = get_option($option_name);

    switch ($type) {
        case 'text':
            $options[$id] = stripslashes($options[$id]);
            $options[$id] = esc_attr($options[$id]);
            echo "<input class='regular-text$class' type='text' id='$id' name='" . $option_name . "[$id]' value='$options[$id]' />";
            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
            break;
        case 'textarea':
            $options[$id] = stripslashes($options[$id]);
            //$options[$id] = esc_attr( $options[$id]);
            $options[$id] = esc_html($options[$id]);

            printf(
                wp_editor($options[$id], $id,
                    array('textarea_name' => $option_name . "[$id]",
                        'style' => 'width: 200px',
                    ))
            );
            // echo "<textarea id='$id' name='" . $option_name . "[$id]' rows='10' cols='50'>".$options[$id]."</textarea>";
            // echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
            break;
    }
}

function pu_validate_settings($input)
{
    foreach ($input as $k => $v) {
        $newinput[$k] = trim($v);

        // Check the input is a letter or a number
        if (!preg_match('/^[A-Z0-9 _]*$/i', $v)) {
            $newinput[$k] = '';
        }
    }

    return $newinput;
}

// Add custom styles to theme options area
add_action('admin_head', 'custom_style');

function custom_style()
{
    echo '<style>
    .appearance_page_pu_theme_options .wp-editor-wrap {
      width: 75%;
    }
    .regular-textcss_class {
    	width: 50%;
    }
    .appearance_page_pu_theme_options h3 {
    	font-size: 2em;
    	padding-top: 40px;
    }
  </style>';
}

// END THEME OPTIONS

/**
 * Load site scripts.
 */
function bootstrap_theme_enqueue_scripts()
{
    $template_url = get_template_directory_uri();

    // jQuery.
    wp_enqueue_script('jquery');

    // wp_register_script('lighbox_gallery', $template_url . '/js/lightbox-plus-jquery.min.js', array('jquery') , null , true);

    // Bootstrap
    wp_enqueue_script('bootstrap-script', $template_url . '/js/bootstrap.min.js', array('jquery'), null, true);

    wp_enqueue_style('bootstrap-style', $template_url . '/css/bootstrap.min.css');

    //Light box
    wp_enqueue_script('lighbox_gallery', get_template_directory_uri() . '/js/lightbox-plus-jquery.min.js', array('jquery'));

    //JqueryUI
    wp_enqueue_script('jquery_ui', get_template_directory_uri() . '/js/jquery-ui.min.js', array('jquery'));

    wp_enqueue_style('lightbox_gallery_style', get_template_directory_uri() . '/css/lightbox.min.css');

    //Main Style
    wp_enqueue_style('main-style', get_stylesheet_uri());

    // Load Thread comments WordPress script.
    if (is_singular() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'bootstrap_theme_enqueue_scripts', 1);

function addMyScript()
{
    wp_enqueue_style('mytheme', get_bloginfo('template_directory') . '/css/index.css', array(), '', 'screen, projection');
}
add_action('wp_head', 'addMyScript');

add_theme_support('custom-logo');

function mytheme_widgets_init()
{
    register_sidebar(array(
        'name' => __('Footer column 1', 'textdomain'),
        'id' => 'footer-column-1',
        'description' => __('Widgets in this area will be shown under your single posts, before comments.', 'textdomain'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Footer column 2', 'textdomain'),
        'id' => 'footer-column-2',
        'description' => __('Widgets in this area will be shown under your single posts, before comments.', 'textdomain'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Footer column 3', 'textdomain'),
        'id' => 'footer-column-3',
        'description' => __('Widgets in this area will be shown under your single posts, before comments.', 'textdomain'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Footer column 4', 'textdomain'),
        'id' => 'footer-column-4',
        'description' => __('Widgets in this area will be shown under your single posts, before comments.', 'textdomain'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Portfolio sidebar', 'textdomain'),
        'id' => 'portfolio-sidebar',
        'description' => __('Widgets in this area will be shown under your single posts, before comments.', 'textdomain'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Portfolio list sidebar', 'textdomain'),
        'id' => 'main-portfolio-sidebar',
        'description' => __('Widgets in this area will be shown under your single posts, before comments.', 'textdomain'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Page sidebar', 'textdomain'),
        'id' => 'page-sidebar',
        'description' => __('Widgets in this area will be shown under your single posts, before comments.', 'textdomain'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Blog list sidebar', 'textdomain'),
        'id' => 'main-blog-sidebar',
        'description' => __('Widgets in this area will be shown under your single posts, before comments.', 'textdomain'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Blog single sidebar', 'textdomain'),
        'id' => 'blog-sidebar',
        'description' => __('Widgets in this area will be shown under your single posts, before comments.', 'textdomain'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Secorndary menu', 'textdomain'),
        'id' => 'secondary-menu',
        'description' => __('Widgets in this area will be shown under your single posts, before comments.', 'textdomain'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
}
add_action('widgets_init', 'mytheme_widgets_init');

add_action('customize_register', 'genesischild_register_theme_customizer');
/*
 * Register Our Customizer Stuff Here
 */
function genesischild_register_theme_customizer($wp_customize)
{
    // Create custom panel.
    $wp_customize->add_panel('text_blocks', array(
        'priority' => 500,
        'theme_supports' => '',
        'title' => __('Portfolio settings', 'genesischild'),
        'description' => __('Set editable text for certain content.', 'genesischild'),
    ));

    // Add columns to show in portfolio archive
    // Add section.
    $wp_customize->add_section('display_options', array(
        'title' => __('Itemps options', 'genesischild'),
        'panel' => 'text_blocks',
        'priority' => 10,
    ));
    $wp_customize->add_setting('display_options_columns', array(
        'default' => __('3', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'display_options_columns_input',
        array(
            'label' => __('Columns to show', 'genesischild'),
            'section' => 'display_options',
            'settings' => 'display_options_columns',
            'type' => 'select',
            'choices' => array(
                '1' => __('1 Column'),
                '2' => __('2 Columns'),
                '3' => __('3 Columns'),
                '4' => __('4 Columns'),
                '6' => __('6 Columns'),
            ),
        )
    ));
    $wp_customize->add_setting('display_options_text', array(
        'default' => __('', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'display_options_text_input',
        array(
            'label' => __('Text after columns', 'genesischild'),
            'section' => 'display_options',
            'settings' => 'display_options_text',
            'type' => 'text'
        )
    ));

    // Add Colors Text
    // Add section.
    $wp_customize->add_section('custom_theme_colors', array(
        'title' => __('Theme colors', 'genesischild'),
        'panel' => 'text_blocks',
        'priority' => 10,
    ));
    /**
     * Main color
     */
    // Add setting
    $wp_customize->add_setting('custom_theme_color_main', array(
        'default' => __('#e80000', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'custom_theme_colors',
        array(
            'label' => __('Main color', 'genesischild'),
            'section' => 'custom_theme_colors',
            'settings' => 'custom_theme_color_main',
            'type' => 'text',
        )
    ));
    /**
     * Secondary color
     */
    // Add setting
    $wp_customize->add_setting('custom_theme_color_secondary', array(
        'default' => __('#f2f0e6', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'secondary_control',
        array(
            'label' => __('Secondary color', 'genesischild'),
            'section' => 'custom_theme_colors',
            'settings' => 'custom_theme_color_secondary',
            'type' => 'text',
        )
    ));
    /**
     * Text color
     */
    // Add setting
    $wp_customize->add_setting('custom_theme_color_text', array(
        'default' => __('#656565', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'text_control',
        array(
            'label' => __('Text color', 'genesischild'),
            'section' => 'custom_theme_colors',
            'settings' => 'custom_theme_color_text',
            'type' => 'text',
        )
    ));
    /**
     * Main text color
     */
    // Add setting
    $wp_customize->add_setting('custom_theme_color_main_text', array(
        'default' => __('#454545', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'main_text_control',
        array(
            'label' => __('Main text color', 'genesischild'),
            'section' => 'custom_theme_colors',
            'settings' => 'custom_theme_color_main_text',
            'type' => 'text',
        )
    ));
    /**
     * Background  color
     */
    // Add setting
    $wp_customize->add_setting('custom_theme_color_background', array(
        'default' => __('#ffffff', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'background_control',
        array(
            'label' => __('Background color', 'genesischild'),
            'section' => 'custom_theme_colors',
            'settings' => 'custom_theme_color_background',
            'type' => 'text',
        )
    ));

    // Add Footer Text
    // Add section.
    $wp_customize->add_section('custom_footer_text', array(
        'title' => __('Change Footer Text', 'genesischild'),
        'panel' => 'text_blocks',
        'priority' => 10,
    ));
    // Add setting
    $wp_customize->add_setting('footer_text_block', array(
        'default' => __('default text', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'custom_footer_text',
        array(
            'label' => __('Footer Text', 'genesischild'),
            'section' => 'custom_footer_text',
            'settings' => 'footer_text_block',
            'type' => 'text',
        )
    ));

    // Add Main page Slider
    // Add section.
    $wp_customize->add_section('custom_slider_text', array(
        'title' => __('Slider', 'genesischild'),
        'panel' => 'text_blocks',
        'priority' => 10,
    ));
    // Add setting
    $wp_customize->add_setting('main_text_block', array(
        'default' => __('', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'custom_slider_text',
        array(
            'label' => __('MainPage slider', 'genesischild'),
            'section' => 'custom_slider_text',
            'settings' => 'main_text_block',
            'type' => 'text',
        )
    ));

    // Add section.
    $wp_customize->add_section('google_analytics_block', array(
        'title' => __('Google Analytics', 'genesischild'),
        'panel' => 'text_blocks',
        'priority' => 10,
    ));

    // Add Google Analytics
    // Add setting

    // Add SEO
    $wp_customize->add_section('seo_block', array(
        'title' => __('SEO', 'genesischild'),
        'panel' => 'text_blocks',
        'priority' => 10,
    ));

    $wp_customize->add_setting('google_track_text', array(
        'default' => __('', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'google_analytics_block',
        array(
            'label' => __('Google track code', 'genesischild'),
            'section' => 'seo_block',
            'settings' => 'google_track_text',
            'type' => 'text',
        )
    ));

    // Add setting
    $wp_customize->add_setting('seo_description_settings', array(
        'default' => __('', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'seo_description_control',
        array(
            'label' => __('Description', 'genesischild'),
            'section' => 'seo_block',
            'settings' => 'seo_description_settings',
            'type' => 'textarea',
        )
    ));
    // // Add setting
    $wp_customize->add_setting('seo_keywords_settings', array(
        'default' => __('', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'seo_keywords_control',
        array(
            'label' => __('Keywords', 'genesischild'),
            'section' => 'seo_block',
            'settings' => 'seo_keywords_settings',
            'type' => 'textarea',
        )
    ));
    // // Add setting
    $wp_customize->add_setting('seo_robots_settings', array(
        'default' => __('Index, Follow', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'seo_robots_control',
        array(
            'label' => __('Keywords', 'genesischild'),
            'section' => 'seo_block',
            'settings' => 'seo_robots_settings',
            'type' => 'select',
            'choices' => array(
                'Index, Follow' => __('Index, Follow'),
                'NoIndex, Follow' => __('NoIndex, Follow'),
                'Index, NoFollow' => __('Index, NoFollow'),
                'NoIndex, NoFollow' => __('NoIndex, NoFollow'),
            ),
        )
    ));

    // Add Twiiter card
    // Add setting
    $wp_customize->add_setting('twitter_cards', array(
        'default' => __('', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'twitter_card_block',
        array(
            'label' => __('Twitter account', 'genesischild'),
            'section' => 'seo_block',
            'settings' => 'twitter_cards',
            'type' => 'text',
        )
    ));

    // Add Facebook card
    // Add setting
    $wp_customize->add_setting('facebook_id', array(
        'default' => __('', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'facebook_id_block',
        array(
            'label' => __('Facebook app ID', 'genesischild'),
            'section' => 'seo_block',
            'settings' => 'facebook_id',
            'type' => 'text',
        )
    ));

    // Create custom panel.
    $wp_customize->add_panel('portfolio_quotes_panel', array(
        'priority' => 505,
        'theme_supports' => '',
        'title' => __('Portfolio Quotes', 'genesischild'),
        'description' => __('Set editable text for certain content.', 'genesischild'),
    ));

    // Add Portfolio quote
    // Add section.
    $wp_customize->add_section('portfolio_quote_text', array(
        'title' => __('Portfolio quote', 'genesischild'),
        'panel' => 'portfolio_quotes_panel',
        'priority' => 10,
    ));
    global $q_config;
    $available_languages = $q_config['enabled_languages'];
    if (!count($available_languages)) {
        $available_languages = array(get_locale());
    }
    foreach ($available_languages as $lang) {
        // Add setting
        $wp_customize->add_setting('portfolio_quote_block_' . $lang, array(
            'default' => __('', 'genesischild'),
            'sanitize_callback' => 'sanitize_text',
        ));

        // Add control
        $wp_customize->add_control(new WP_Customize_Control(
            $wp_customize,
            'portfolio_quote_text_' . $lang,
            array(
                'label' => __('Portfolio quotes ' . $q_config['language_name'][$lang], 'genesischild'),
                'section' => 'portfolio_quote_text',
                'settings' => 'portfolio_quote_block_' . $lang,
                'type' => 'text',
            )
        )
        );
    }

    // Sanitize text
    function sanitize_text($text)
    {
        return sanitize_text_field($text);
    }
}
// Show posts of 'post', 'portfolio' and 'lighting' post types on home page

function my_pre_get_posts($query)
{
    // validate
    if (is_admin()) {
        return $query;
    }

    if (isset($query->query_vars['post_type']) && ($query->query_vars['post_type'] == 'portfolio' || $query->query_vars['post_type'] == 'lighting')) {
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'item-order-priority');
        $query->set('order', 'DESC');
    }
    // always return
    return $query;

}

add_action('pre_get_posts', 'my_pre_get_posts');

include 'portfolio.php';
include 'helpers/hex-to-hsl.php';
?>