<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/sass/child-theme.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );

add_action( 'understrap_site_info', 'understrap_add_site_info' );

/**
 * Add site info content.
 */
function understrap_add_site_info() {
    $the_theme = wp_get_theme();

    $site_info = sprintf(
        '&copy; %1$s <a href="%2$s">%3$s</a><span class="sep"> | </span> %4$s',
        sprintf(
            date('Y')
        ),
        sprintf(
            home_url(),
        ),
        sprintf(
            $the_theme->get( 'Name' ),
        ),
        sprintf( // WPCS: XSS ok.
            /* translators:*/
            esc_html__( 'Designed by %1$s', 'understrap' ),
            '<a href="' . esc_url( __( 'https://spencercreative.co', 'understrap' ) ) . '">Spencer Creative Co.</a>'
        )
    );

    echo apply_filters( 'understrap_site_info_content', $site_info ); // WPCS: XSS ok.
}

register_nav_menus( array(
    'secondary' => __( 'Secondary Menu', 'understrap' ),
) );