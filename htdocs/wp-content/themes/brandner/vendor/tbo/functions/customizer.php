<?php

// logo
add_action( 'customize_register', 'tbo_customizer_register' );
function tbo_customizer_register( $wp_customize ) {
  
    $wp_customize->add_setting( 'custom-logo' );
    
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'custom-logo', array(
        'label'    => __( 'Logo', 'tbo' ),
        'section'  => 'title_tagline',
        'settings' => 'custom-logo',
    ) ) );
    
}