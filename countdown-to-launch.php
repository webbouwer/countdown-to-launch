<?php
/*
Plugin Name: Countdown to launch
Plugin URI: https://webbouwer.org
Description: A content cloaking screen with countdown timer to launch the content publication
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( is_admin() ){

    // settings menu and page
    require_once( plugin_dir_path( __FILE__ ) . 'countdown-to-launch-admin.php');
    $countdowntolaunch_settings_page = new CountdownToLaunchSettings();

}




function plugconstruct() {
	return new countDownToLaunch();
}

add_action( 'init', 'plugconstruct' );

class countDownToLaunch {

	function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'wp_ajax_countdowntolaunch', array( $this, 'ajax_callback_countdowntolaunch_function' ) );
	}

	public function load_scripts() {

        wp_enqueue_style( 'custom', plugins_url( 'countdown-to-launch/countdown-to-launch.css', _FILE_ ) );

	    wp_register_script( 'countdowntolaunch',
			plugins_url( 'countdown-to-launch.js', __FILE__ ),
			array( 'jquery' )
		);

         $options = get_option( 'countdowntolaunch_options' );

        wp_localize_script( 'countdowntolaunch', 'params',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'date' => $options['datepicker'],
                'hours' => $options['hours'],
                'minutes' => $options['minutes'],
                'seconds' => $options['seconds'],
                'title' => $options['title'],
                'desc' => $options['desc'],
                'desc2' => $options['desc2'],
            )
        );

		wp_enqueue_script( 'countdowntolaunch' );

    }

    function ajax_callback_countdowntolaunch_function() {

        if ( check_ajax_referer( '_the_nonce', 'security' ) ) {
            $somevar = $_POST['var_from_ajax_post'];
            if ( $somevar === 'false' ) {
              wp_send_json_success( array( 'something' => 12, 'message' => 'AOK' ) );
            } else {
              wp_send_json_error();
            }
        }
    }
    /*
    function add_click_link() {

        $ajax_nonce = wp_create_nonce( "countdowntolaunch" );

        $link       = '<a href="#" class="some-link-class" id="testbutton" '
				    . 'data-nonce="' . $ajax_nonce . '" data-somevar="' . $varX . '">klik here</a>';
		return $link;

	}
    */
}
