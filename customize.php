<?php
/*
 * Register Our Customizer Stuff Here
 */
function genesischild_register_theme_customizer($wp_customize)
{
    // Create custom panel.
    $wp_customize->add_panel('text_blocks', array(
        'priority' => 500,
        'theme_supports' => '',
        'title' => __('Text Blocks', 'genesischild'),
        'description' => __('Set editable text for certain content.', 'genesischild'),
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
    )
    );
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
    // Add setting
    $wp_customize->add_setting('portfolio_quote_block', array(
        'default' => __('', 'genesischild'),
        'sanitize_callback' => 'sanitize_text',
    ));
    // Add control
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'portfolio_quote_text',
        array(
            'label' => __('Portfolio quotes', 'genesischild'),
            'section' => 'portfolio_quote_text',
            'settings' => 'portfolio_quote_block',
            'type' => 'text',
        )
    )
    );

    // Sanitize text
    function sanitize_text($text)
    {
        return sanitize_text_field($text);
    }
}

add_action('customize_register', 'genesischild_register_theme_customizer');
