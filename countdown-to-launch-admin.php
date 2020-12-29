<?php



class CountdownToLaunchSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'Countdown to launch',
            'manage_options',
            'countdown-to-launch-setting-admin',
            array( $this, 'create_admin_page' )
        );

        // load color picker - https://www.stacktips.com/tutorials/wordpress/how-to-implement-color-picker-in-wordpress
        function wpse_80236_Colorpicker(){
            wp_enqueue_style( 'wp-color-picker');
            wp_enqueue_script( 'wp-color-picker');
        }
        add_action('admin_enqueue_scripts', 'wpse_80236_Colorpicker');

        // insert datepicker css & js
        /* <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> */
        wp_enqueue_script( 'jquery-1.12.4', 'https://code.jquery.com/jquery-1.12.4.js' );
        wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js' );
        wp_enqueue_style( 'jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );

        // insert frontend base css
        wp_enqueue_style( 'custom', plugins_url( 'countdown-to-launch/countdown-to-launch.css', _FILE_ ) );
    }



    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'countdowntolaunch_options' );



        ?>
        <div class="wrap">


            <h1>Countdown to launch</h1>


            <form method="post" action="options.php">


            <div class="settingspanel column">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'countdowntolaunch_option_group' );
                do_settings_sections( 'countdown-to-launch-setting-admin' );
                submit_button();
            ?>
            </div>

            <div class="stylingpanel column">

                <div id="previewbox">

                    <div id="cloak-page-container" style="position: absolute; text-align: center; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgb(0, 220, 255); color: rgb(0, 28, 51);">
                        <div class="contentbox">
                        <h1 class="cloak-page-title" style="color:#f97c00">Hang on!</h1>
                        <p class="cloak-page-desc">We are ready in</p>
                        <div id="clockdiv" style="color:#ffffff;"><div style="background-color:#1c3d89"><span class="days" style="color:#ffffff;background-color:#173270">15</span><div class="smalltext">Days</div></div><div style="background-color:#1c3d89"><span class="hours" style="color:#ffffff;background-color:#173270">07</span><div class="smalltext">Hours</div></div><div style="background-color:#1c3d89"><span class="minutes" style="color:#ffffff;background-color:#173270">36</span><div class="smalltext">Minutes</div></div><div style="background-color:#1c3d89"><span class="seconds" style="color:#ffffff;background-color:#173270">27</span><div class="smalltext">Seconds</div></div></div>
                        <p class="cloak-page-desc2" style="color:#001c33">Nice to see you again!</p></div>
                    </div>

                </div>

            </div>

            </form>

        </div>


            <!-- Datepicker
                https://stackoverflow.com/questions/65081799/using-jquery-datepicker-in-wordpress-backend
                https://www.jqueryscript.net/time-clock/Bootstrap-4-Date-Time-Picker.html
                https://trentrichardson.com/examples/timepicker/
            -->
            <script>
            $(document).ready(function () {
                $("#datepicker").datepicker({
                    dateFormat: "yy-mm-dd"
                });
                // Add Color Picker to all inputs that have 'color-field' class
                $('.color-field').wpColorPicker();
            });
            </script>

            <!-- Preview -->
            <script>
            $(document).ready(function () {

                // update preview
                function updatePreview(){
                     alert('check!');
                }

                $(".wrap form").on('change', function(){
                    updatePreview();
                });

                $(".wrap form button").on('click', function(){
                    if( !$(this).parent().hasClass('wp-picker-active') ){
                       updatePreview();
                    }
                });


            });
            </script>
            <style>
            @media only screen and (min-width: 943px) {
                .wrap .column {
                        width:50%;
                        float:left;
                }
            }

            #previewbox{
                position:relative;
                width:90%;
                height:80vh;
                margin:5% auto;
                border:2px solid black;
            }
            </style>

        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {


        register_setting(
            'countdowntolaunch_option_group', // Option group
            'countdowntolaunch_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );



        add_settings_section(
            'setting_section_timer', // ID
            'Timer', // Title
            array( $this, 'print_section1_info' ), // Callback
            'countdown-to-launch-setting-admin' // Page
        );

        add_settings_section(
            'setting_section_content', // ID
            'Settings', // Title
            array( $this, 'print_section2_info' ), // Callback
            'countdown-to-launch-setting-admin' // Page
        );

        add_settings_section(
            'setting_section_style', // ID
            'Styling', // Title
            array( $this, 'print_section3_info' ), // Callback
            'countdown-to-launch-setting-admin' // Page
        );



        add_settings_field(
            'datepicker',
            'Select day and time',
            array( $this, 'datepicker_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_timer'
        );
        add_settings_field(
            'title',
            'Title',
            array( $this, 'title_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_content'
        );

        add_settings_field(
            'desc',
            'Description text 1',
            array( $this, 'desc_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_content'
        );

        add_settings_field(
            'desc2',
            'Description text 2',
            array( $this, 'desc2_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_content'
        );


        add_settings_field(
            'bgcolor',
            'Cloak background color',
            array( $this, 'bg_colour_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_style'
        );
        add_settings_field(
            'titlecolor',
            'Title color',
            array( $this, 'title_colour_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_style'
        );
        add_settings_field(
            'desc1color',
            'Description text 1 color',
            array( $this, 'desc1_colour_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_style'
        );
        add_settings_field(
            'desc2color',
            'Description text 2 color',
            array( $this, 'desc2_colour_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_style'
        );
        add_settings_field(
            'timernumbercolor',
            'Timer number color',
            array( $this, 'timernumber_colour_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_style'
        );
        add_settings_field(
            'timertextcolor',
            'Timer text color',
            array( $this, 'timertext_colour_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_style'
        );
        add_settings_field(
            'timerboxinnercolor',
            'Timer box inner color',
            array( $this, 'timerboxinner_colour_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_style'
        );
        add_settings_field(
            'timerboxoutercolor',
            'Timer box outer color',
            array( $this, 'timerboxouter_colour_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_style'
        );





    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['hours'] ) )
            $new_input['hours'] = absint( $input['hours'] );
        if( isset( $input['minutes'] ) )
            $new_input['minutes'] = absint( $input['minutes'] );
        if( isset( $input['seconds'] ) )
            $new_input['seconds'] = absint( $input['seconds'] );


        if( isset( $input['datepicker'] ) )
            $new_input['datepicker'] = sanitize_text_field( $input['datepicker'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        if( isset( $input['desc'] ) )
            $new_input['desc'] = sanitize_text_field( $input['desc'] );

          if( isset( $input['desc2'] ) )
            $new_input['desc2'] = sanitize_text_field( $input['desc2'] );

          if( isset( $input['bgcolor'] ) )
            $new_input['bgcolor'] = sanitize_text_field( $input['bgcolor'] );

          if( isset( $input['titlecolor'] ) )
            $new_input['titlecolor'] = sanitize_text_field( $input['titlecolor'] );

          if( isset( $input['desc1color'] ) )
            $new_input['desc1color'] = sanitize_text_field( $input['desc1color'] );

          if( isset( $input['desc2color'] ) )
            $new_input['desc2color'] = sanitize_text_field( $input['desc2color'] );

          if( isset( $input['timernumbercolor'] ) )
            $new_input['timernumbercolor'] = sanitize_text_field( $input['timernumbercolor'] );

          if( isset( $input['timertextcolor'] ) )
            $new_input['timertextcolor'] = sanitize_text_field( $input['timertextcolor'] );

          if( isset( $input['timerboxinnercolor'] ) )
            $new_input['timerboxinnercolor'] = sanitize_text_field( $input['timerboxinnercolor'] );

          if( isset( $input['timerboxoutercolor'] ) )
            $new_input['timerboxoutercolor'] = sanitize_text_field( $input['timerboxoutercolor'] );


        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section1_info()
    {
        print 'Set launch time';
    }
    public function print_section2_info()
    {
        print 'Content';
    }
    public function print_section3_info()
    {
        print 'Styling';
    }

    /**
     * Get the settings option array and print one of its values

    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="my_option_name[id_number]" value="%s" />',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
        );
    }
     */

        /**
     * Get the settings option array and print one of its values
     */
    public function datepicker_callback()
    {
        printf(
            //'<input type="text" id="title" name="countdowntolaunch_options[title]" value="%s" />',
            '<input type="text" id="datepicker" name="countdowntolaunch_options[datepicker]" value="%s"/>',
            isset( $this->options['datepicker'] ) ? esc_attr( $this->options['datepicker']) : ''
        );

        printf(
            //'<input type="text" id="title" name="countdowntolaunch_options[title]" value="%s" />',
            '<input type="text" id="hours" size="2" name="countdowntolaunch_options[hours]" value="%s"/>',
            isset( $this->options['hours'] ) ? esc_attr( $this->options['hours']) : ''
        );
        printf(
            //'<input type="text" id="title" name="countdowntolaunch_options[title]" value="%s" />',
            '<input type="text" id="minutes" size="2" name="countdowntolaunch_options[minutes]" value="%s"/>',
            isset( $this->options['minutes'] ) ? esc_attr( $this->options['minutes']) : ''
        );
        printf(
            //'<input type="text" id="title" name="countdowntolaunch_options[title]" value="%s" />',
            '<input type="text" id="seconds" size="2" name="countdowntolaunch_options[seconds]" value="%s"/>',
            isset( $this->options['seconds'] ) ? esc_attr( $this->options['seconds']) : ''
        );
    }



    /**
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="countdowntolaunch_options[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }

        /**
     * Get the settings option array and print one of its values
     */
    public function desc_callback()
    {
        printf(
            '<textarea type="text" id="desc" name="countdowntolaunch_options[desc]">%s</textarea>',
            isset( $this->options['desc'] ) ? esc_attr( $this->options['desc']) : ''
        );
    }
        /**
     * Get the settings option array and print one of its values
     */
    public function desc2_callback()
    {
        printf(
            '<textarea type="text" id="desc2" name="countdowntolaunch_options[desc2]">%s</textarea>',
            isset( $this->options['desc2'] ) ? esc_attr( $this->options['desc2']) : ''
        );
    }


    /**
     * Colorpickers
     */

    public function bg_colour_callback()
    {
        printf(
            '<input type="text" id="bgcolor" class="color-field" name="countdowntolaunch_options[bgcolor]" value="%s" />',
            isset( $this->options['bgcolor'] ) ? esc_attr( $this->options['bgcolor']) : ''
        );
    }
    public function title_colour_callback()
    {
        printf(
            '<input type="text" id="titlecolor" class="color-field" name="countdowntolaunch_options[titlecolor]" value="%s" />',
            isset( $this->options['titlecolor'] ) ? esc_attr( $this->options['titlecolor']) : ''
        );
    }
    public function desc1_colour_callback()
    {
        printf(
            '<input type="text" id="desc1color" class="color-field" name="countdowntolaunch_options[desc1color]" value="%s" />',
            isset( $this->options['desc1color'] ) ? esc_attr( $this->options['desc1color']) : ''
        );
    }
    public function desc2_colour_callback()
    {
        printf(
            '<input type="text" id="desc2color" class="color-field" name="countdowntolaunch_options[desc2color]" value="%s" />',
            isset( $this->options['desc2color'] ) ? esc_attr( $this->options['desc2color']) : ''
        );
    }
    public function timernumber_colour_callback()
    {
        printf(
            '<input type="text" id="timernumbercolor" class="color-field" name="countdowntolaunch_options[timernumbercolor]" value="%s" />',
            isset( $this->options['timernumbercolor'] ) ? esc_attr( $this->options['timernumbercolor']) : ''
        );
    }
    public function timertext_colour_callback()
    {
        printf(
            '<input type="text" id="timertextcolor" class="color-field" name="countdowntolaunch_options[timertextcolor]" value="%s" />',
            isset( $this->options['timertextcolor'] ) ? esc_attr( $this->options['timertextcolor']) : ''
        );
    }
    public function timerboxinner_colour_callback()
    {
        printf(
            '<input type="text" id="timerboxinnercolor" class="color-field" name="countdowntolaunch_options[timerboxinnercolor]" value="%s" />',
            isset( $this->options['timerboxinnercolor'] ) ? esc_attr( $this->options['timerboxinnercolor']) : ''
        );
    }

    public function timerboxouter_colour_callback()
    {
        printf(
            '<input type="text" id="timerboxoutercolor" class="color-field" name="countdowntolaunch_options[timerboxoutercolor]" value="%s" />',
            isset( $this->options['timerboxoutercolor'] ) ? esc_attr( $this->options['timerboxoutercolor']) : ''
        );
    }

}
