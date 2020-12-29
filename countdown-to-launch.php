<?php
/*
Plugin Name: Countdown to launch
Plugin URI: https://webbouwer.org
Description: A content cloaking screen with countdown timer to launch the content publication
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* check admin options */
if( is_admin() ){
    require_once( plugin_dir_path( __FILE__ ) . 'countdown-to-launch-admin.php'); // class code
    $countdowntolaunch_settings_page = new CountdownToLaunchSettings();  // settings in class
}
/* init base class */
function plugconstruct() {
	return new countDownToLaunch();
}
add_action( 'init', 'plugconstruct' );

/* base class */
class countDownToLaunch {

	function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		//add_action( 'wp_ajax_countdowntolaunch', array( $this, 'ajax_callback_countdowntolaunch_function' ) );
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

                'bgcolor' => $options['bgcolor'],
                'titlecolor' => $options['titlecolor'],
                'desc1color' => $options['desc1color'],
                'desc2color' => $options['desc2color'],
                'timernumbercolor' => $options['timernumbercolor'],
                'timertextcolor' => $options['timertextcolor'],
                'timerboxinnercolor' => $options['timerboxinnercolor'],
                'timerboxoutercolor' => $options['timerboxoutercolor'],
            )
        );
		wp_enqueue_script( 'countdowntolaunch' );
    }
    /*
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
    */
}
