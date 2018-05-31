<?php /** @noinspection HtmlUnknownTarget */

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://workshopbutler.com
 * @since      0.2.0
 *
 * @package    WSB_Integration
 * @subpackage WSB_Integration/admin
 */

define('WSB_SETTINGS_NAME', 'wsb_options');

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WSB_Integration
 * @subpackage WSB_Integration/admin
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Integration_Admin {
    
    /**
     * The ID of this plugin.
     *
     * @since    0.2.0
     * @access   private
     * @var      string $WSB_Integration The ID of this plugin.
     */
    private $WSB_Integration;
    
    /**
     * The version of this plugin.
     *
     * @since    0.2.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    0.2.0
     *
     * @param      string $WSB_Integration The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct( $WSB_Integration, $version ) {
        $this->WSB_Integration = $WSB_Integration;
        $this->version         = $version;
    }
    
    
    /**
     * Initializes plugin options
     *
     * @since 0.2.0
     */
    public function init() {
        register_setting( 'wsb-options-group', WSB_SETTINGS_NAME );
        $this->add_general_settings();
        $this->add_event_list_settings();
    }
    
    private function add_general_settings() {
        $section_id = 'wsb_general_section';
        add_settings_section( $section_id,
            __( 'General', 'wsbintegration' ),
            array($this, 'render_general_section'),
            WSB_SETTINGS_NAME );
    
        add_settings_field( 'wsb_field_api_key',
            __( 'Workshop Butler API Key', 'wsbintegration' ),
            array($this, 'render_input_field'), WSB_SETTINGS_NAME, $section_id, [
                'label_for'       => 'wsb_field_api_key',
                'class'           => 'wsb_row',
                'wsb_custom_data' => 'custom'
            ] );
    
        add_settings_field( 'wsb_field_theme',
            __( 'Theme', 'wsbintegration' ),
            array($this, 'render_input_field'), WSB_SETTINGS_NAME, $section_id, [
                'label_for'       => 'wsb_field_theme',
                'class'           => 'wsb_row',
                'wsb_custom_data' => 'custom'
            ] );
    }
    
    private function add_event_list_settings() {
        $section_id = 'wsb_event_list_section';
        add_settings_section( $section_id,
            __( 'Event List', 'wsbintegration' ),
            '',
            WSB_SETTINGS_NAME );
    
        add_settings_field( 'wsb_field_no_events',
            __( 'Customize the text when no events to show', 'wsbintegration' ),
            array($this, 'render_input_field'), WSB_SETTINGS_NAME, $section_id, [
                'label_for'       => 'wsb_field_no_events',
                'class'           => 'wsb_row',
                'wsb_custom_data' => 'custom'
            ] );

        add_settings_field('wsb_field_event_list_type',
            __('Layout', 'wsbintegration'),
            function($args) {
                $options = get_option( 'wsb_options' );
                $field_id = $options[$args['label_for']];
                $select = '<select name="wsb_options[wsb_field_event_list_type]"><option value="table"';
                if ($field_id == 'table') {
                    $select .= ' selected ';
                }
                $select .= '>Table</option><option value="tile"';
                if ($field_id == 'tile') {
                    $select .= ' selected ';
                }
                $select .= '>Tiles</option></select>';
                echo $select;
            },
            WSB_SETTINGS_NAME,
            $section_id,
            [
                'label_for'       => 'wsb_field_event_list_type',
                'class'           => 'wsb_row',
                'wsb_custom_data' => 'custom'
            ] );
    }
    
    public function render_general_section( $args ) {
        echo '<p id="' . esc_attr( $args['id'] ) . '">' . __( 'Read our <a target="_blank" href="https://workshopbutler.com/">documentation</a> how to configure these settings.', 'wsbintegration' ) . '</p>';
    }
    
    public function render_input_field( $args ) {
        // get the value of the setting we've registered with register_setting()
        $options = get_option( 'wsb_options' );
        // output the text input field
        $html = '';
        $html .= '<input type="text" value="' . $options[ $args['label_for'] ] . '" id="' . esc_attr( $args['label_for'] ) . '" data-custom="' . esc_attr( $args['wsb_custom_data'] ) . '" name="wsb_options[' . esc_attr( $args['label_for'] ) . ']">';
        echo $html;
    }
    
    public function update_menu() {
        add_submenu_page( 'options-general.php',
            'Workshop Butler',
            'Workshop Butler',
            'manage_options',
            'wsb-options',
            array($this, 'render_admin_page') );
    }
    
    public function render_admin_page() {
        // check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        echo '<div class="wrap">';
        echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';
        echo '<form action="options.php" method="post">';
        echo settings_fields( 'wsb-options-group' );
        echo do_settings_sections( WSB_SETTINGS_NAME );
        echo submit_button( __( 'Save Settings', 'wsbintegration' ) );
        echo '</form>';
        echo '</div>';
        
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    0.2.0
     */
    public function enqueue_styles() {
        wp_enqueue_style( "wsb_admin_styles", plugin_dir_url( __FILE__) . 'css/wsb-integration-admin.css' );
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since 0.2.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script( "wsb_admin_scripts", plugin_dir_url(__FILE__) . 'js/wsb-integration-admin.js', array( 'jquery' ), false, true );
    }
    
}
