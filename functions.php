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
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    wp_enqueue_script( 'fontawesome-script', 'https://kit.fontawesome.com/dbf6b0bd9d.js', array(), $the_theme->get( 'Version' ), true );
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
        '<div class="small">&copy; 2016-%1$s <a href="%2$s">%3$s</a>. All rights reserved.<span class="sep"> | </span> %4$s</div>',
        sprintf(
            date('Y')
        ),
        sprintf(
            home_url(),
        ),
        sprintf(
            'Absolute Tree Company'
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


// Register and load the widget
function understrap_load_widget() {
	global $blog_id;
	register_widget( 'understrap_cta_widget' );
}
add_action( 'widgets_init', 'understrap_load_widget' );
 
// Creating the widget 
class understrap_cta_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(

		// Base ID of your widget
		'understrap_cta_widget', 

		// Widget name will appear in UI
		__('CTA', 'understrap'), 

		// Widget description
		array( 'description' => __( 'Call to Action Button', 'understrap' ), ) 
		);
	}

	// Creating widget front-end

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$link = $instance['link'];
		$fa = $instance['fa'];

		echo $args['before_widget'];
		
		if ( ! empty( $link ) )
		echo __( '<a href="' . $link . '">', 'understrap' );
		
		if ( ! empty( $fa ) )
		echo __( '<div class="cta-icon"><i class="fa-fw ' . $fa . '"></i></div>', 'understrap' );
		
		if ( ! empty( $title ) )
		echo __( '<span class="cta-title">' . $title . '</span></a>', 'understrap' );
		
		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( '', 'understrap' );
		}
		
		if ( isset( $instance[ 'link' ] ) ) {
			$link = $instance[ 'link' ];
		} else {
			$link = __( '', 'understrap' );
		}
		
		if ( isset( $instance[ 'fa' ] ) ) {
			$fa = $instance[ 'fa' ];
		} else {
			$fa = __( '', 'understrap' );
		}
		// Widget admin form
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'fa' ); ?>"><a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank"><?php _e( 'Font Awesome' ); ?></a> Classes:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'fa' ); ?>" name="<?php echo $this->get_field_name( 'fa' ); ?>" type="text" value="<?php echo esc_attr( $fa ); ?>" />
		</p>
	<?php 
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? strip_tags( $new_instance['link'] ) : '';
		$instance['fa'] = ( ! empty( $new_instance['fa'] ) ) ? strip_tags( $new_instance['fa'] ) : '';
		return $instance;
	}
} // Class citadel_cta_widget ends here
