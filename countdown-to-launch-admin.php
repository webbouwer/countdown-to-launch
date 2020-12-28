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
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'countdowntolaunch_option_group' );
                do_settings_sections( 'countdown-to-launch-setting-admin' );
                submit_button();
            ?>
            </form>

            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <!--
                https://stackoverflow.com/questions/65081799/using-jquery-datepicker-in-wordpress-backend
                https://www.jqueryscript.net/time-clock/Bootstrap-4-Date-Time-Picker.html
                https://trentrichardson.com/examples/timepicker/
            -->
            <script>
            $(document).ready(function () {
                $("#datepicker").datepicker({
                    dateFormat: "yy-mm-dd"
                });
            });
            </script>
        </div>

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
            'setting_section_id', // ID
            'Settings', // Title
            array( $this, 'print_section2_info' ), // Callback
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
            'setting_section_id'
        );

        add_settings_field(
            'desc',
            'Description text 1',
            array( $this, 'desc_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'desc2',
            'Description text 2',
            array( $this, 'desc2_callback' ),
            'countdown-to-launch-setting-admin',
            'setting_section_id'
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
        print 'Content and styling';
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
}
