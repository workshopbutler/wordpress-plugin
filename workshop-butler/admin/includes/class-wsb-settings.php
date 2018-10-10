<?php
/**
 * This file contains a class for admin Redux panel
 *
 * @since   2.0.0
 * @package WSB_Integration
 */

/**
 * General plugin settings
 *
 * @since   2.0.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Settings {

	/**
	 * Name of the element in WordPress $options array
	 *
	 * @since 0.3.0
	 * @var   $opt_name string Name of the element in WordPress $options array
	 */
	protected $opt_name;

	/**
	 * Initialises a new object
	 *
	 * @param $opt_name string   Name of the element in WordPress $options array
	 *
	 * @since 0.3.0
	 */
	public function __construct( $opt_name ) {
		$this->opt_name = $opt_name;
		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies
	 *
	 * @since    0.3.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for all plugin-related options
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '../includes/class-wsb-options.php';
	}

	/**
	 * Initialises Redux admin panel
	 *
	 * @return mixed
	 */
	public function init() {
		Redux::setArgs( $this->opt_name, $this->get_arguments( true ) );

		Redux::setSection(
			$this->opt_name,
			array(
				'title'            => __( 'General', 'wsbintegration' ),
				'id'               => 'general',
				'customizer_width' => '400px',
				'icon'             => 'el el-home',
				'fields'           => $this->get_general_settings(),
			)
		);

		Redux::setSection(
			$this->opt_name,
			array(
				'title'            => __( 'Events', 'wsbintegration' ),
				'id'               => 'events',
				'customizer_width' => '400px',
				'icon'             => 'el el-list-alt',
				'fields'           => $this->get_event_settings(),
			)
		);
		Redux::setSection(
			$this->opt_name,
			array(
				'title'            => __( 'Trainers', 'wsbintegration' ),
				'id'               => 'trainers',
				'customizer_width' => '400px',
				'icon'             => 'el el-child',
				'fields'           => $this->get_trainer_settings(),
			)
		);
		Redux::setSection(
			$this->opt_name,
			array(
				'title'            => __( 'Pages', 'wsbintegration' ),
				'id'               => 'pages',
				'customizer_width' => '500px',
				'icon'             => 'el el-edit',
			)
		);

		$this->get_pages_settings();

		Redux::setSection(
			$this->opt_name,
			array(
				'title'            => __( 'Custom CSS', 'wsbintegration' ),
				'id'               => 'css-editor',
				'customizer_width' => '500px',
				'icon'             => 'el el-edit',
				'fields'           => array(
					array(
						'id'         => WSB_Options::CUSTOM_CSS,
						'type'       => 'ace_editor',
						'mode'       => 'css',
						'full_width' => true,
						'theme'      => 'chrome',
						'options'    => array(
							'minLines'                  => 20,
							'enableBasicAutocompletion' => true,
							'maxLines'                  => 60,
						),
					),
				),
			)
		);

	}

	protected function get_pages_settings() {

		Redux::setSection(
			$this->opt_name,
			array(
				'title'      => __( 'Schedule', 'wsbintegration' ),
				'id'         => 'schedule',
				// 'icon'  => 'el el-home'
				'subsection' => true,
				'fields'     => array(
					array(
						'id'         => WSB_Options::SCHEDULE_TEMPLATE,
						'type'       => 'ace_editor',
						'mode'       => 'WSB_Twig',
						'default'    => $this->get_template( 'schedule-page' ),
						'full_width' => true,
						'theme'      => 'chrome',
					),
				),
			)
		);
		Redux::setSection(
			$this->opt_name,
			array(
				'title'      => __( 'Event Page', 'wsbintegration' ),
				'id'         => 'event',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'         => WSB_Options::EVENT_TEMPLATE,
						'type'       => 'ace_editor',
						'mode'       => 'WSB_Twig',
						'default'    => $this->get_template( 'event-page' ),
						'full_width' => true,
						'theme'      => 'chrome',
					),
				),
			)
		);
		Redux::setSection(
			$this->opt_name,
			array(
				'title'      => __( 'Registration Page', 'wsbintegration' ),
				'id'         => 'registration-page',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'         => WSB_Options::REGISTRATION_TEMPLATE,
						'type'       => 'ace_editor',
						'mode'       => 'WSB_Twig',
						'default'    => $this->get_template( 'registration-page' ),
						'full_width' => true,
						'theme'      => 'chrome',
					),
				),
			)
		);
		Redux::setSection(
			$this->opt_name,
			array(
				'title'      => __( 'Trainer List', 'wsbintegration' ),
				'id'         => 'trainer-list',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'         => WSB_Options::TRAINER_LIST_TEMPLATE,
						'type'       => 'ace_editor',
						'mode'       => 'WSB_Twig',
						'default'    => $this->get_template( 'trainer-list-page' ),
						'full_width' => true,
						'theme'      => 'chrome',
					),
				),
			)
		);
		Redux::setSection(
			$this->opt_name,
			array(
				'title'      => __( 'Trainer Profile', 'wsbintegration' ),
				'id'         => 'trainer',
				'subsection' => true,
				'fields'     => array(
					array(
						'id'         => WSB_Options::TRAINER_TEMPLATE,
						'type'       => 'ace_editor',
						'mode'       => 'WSB_Twig',
						'default'    => $this->get_template( 'trainer-page' ),
						'full_width' => true,
						'theme'      => 'chrome',
					),
				),
			)
		);
	}

	/**
	 * Returns the set of fields for General tab
	 *
	 * @return array
	 */
	protected function get_general_settings() {
		return array(
			array(
				'id'         => WSB_Options::API_KEY,
				'type'       => 'text',
				'title'      => __( 'Workshop Butler API Key', 'wsbintegration' ),
				'desc'       => __( 'Log in to <a href="https://app.workshopbutler.com" target="_blank">Workshop Butler</a> to get your API key', 'wsbintegration' ),
				'validation' => 'not_empty',
			),
			array(
				'id'      => WSB_Options::THEME,
				'type'    => 'select',
				'title'   => __( 'Theme', 'wsbintegration' ),
				'desc'    => __( 'Choose a preferred theme. If you added your own theme, select <i>Custom</i> and type its name', 'wsbintegration' ),
				'options' => array(
					'alfred'  => 'Alfred',
					'britton' => 'Britton',
					'custom'  => 'Custom',
					'dacota'  => 'Dacota',
					'hayes'   => 'Hayes',
					'gatsby'  => 'Gatbsy',
				),
				'default' => 'alfred',
			),
			array(
				'id'       => WSB_Options::CUSTOM_THEME,
				'type'     => 'text',
				'title'    => __( 'Custom theme', 'wsbintegration' ),
				'desc'     => __( 'Enter the name of your theme', 'wsbintegration' ),
				'required' => array( WSB_Options::THEME, 'equals', 'custom' ),
			),
		);
	}

	private function get_trainer_settings() {
		return $this->get_trainer_page_settings();
	}

	/**
	 * Returns the settings for Trainer Profile
	 *
	 * @return array
	 */
	private function get_trainer_page_settings() {
		return array(
			array(
				'id'       => WSB_Options::TRAINER_MODULE,
				'type'     => 'switch',
				'title'    => 'Trainer Module',
				'subtitle' => 'Switch to ON to have the list of trainers and their profiles on your website',
				'default'  => true,
			),
			array(
				'id'       => WSB_Options::TRAINER_PROFILE_PAGE,
				'type'     => 'select',
				'data'     => 'pages',
				'title'    => 'Trainer Profile',
				'required' => array( WSB_Options::TRAINER_MODULE, '=', true ),
				'desc'     => 'To make it work, add [wsb_trainer] shortcode to the page when you complete the setup',
			),
		);
	}


	/**
	 * Returns settings for event-related pages
	 *
	 * @return array
	 */
	private function get_event_settings() {
		return array_merge( $this->get_event_list_settings(), $this->get_event_page_settings() );
	}

	/**
	 * Returns the settings for the list of events
	 *
	 * @return array
	 */
	private function get_event_list_settings() {
		return array(
			array(
				'id'      => WSB_Options::SCHEDULE_NO_EVENTS,
				'type'    => 'text',
				'title'   => __( 'Customize the text when no events to show', 'wsbintegration' ),
				'default' => __( 'No events found', 'wsbintegration' ),
			),
			array(
				'id'      => WSB_Options::SCHEDULE_LAYOUT,
				'type'    => 'radio',
				'title'   => __( 'Schedule Layout', 'wsbintegration' ),
				'desc'    => __( 'This parameter defines how your events are presented on the schedule', 'wsbintegration' ),
				'options' => array(
					'table' => 'Table',
					'tile'  => 'Tiles',
				),
				'default' => 'table',
			),
		);
	}

	/**
	 * Returns the settings for Event Details
	 *
	 * @return array
	 */
	private function get_event_page_settings() {
		return array(
			array(
				'id'       => WSB_Options::CUSTOM_EVENT_DETAILS,
				'type'     => 'switch',
				'title'    => 'Integrated Event Page',
				'subtitle' => 'Switch to ON to have an unique page for each event on your website',
				'default'  => true,
			),
			array(
				'id'       => WSB_Options::EVENT_PAGE,
				'type'     => 'select',
				'data'     => 'pages',
				'title'    => 'Event Page',
				'required' => array( WSB_Options::CUSTOM_EVENT_DETAILS, '=', true ),
				'desc'     => 'To make it work, add [wsb_event] shortcode to the page when you complete the setup',
			),
			array(
				'id'       => WSB_Options::REGISTRATION_PAGE,
				'type'     => 'select',
				'data'     => 'pages',
				'title'    => 'Registration Page',
				'required' => array( WSB_Options::CUSTOM_EVENT_DETAILS, '=', true ),
				'desc'     => 'To make it work, add [wsb_registration] shortcode to the page when you complete the setup',
			),
			array(
				'id'       => WSB_Options::SHOW_EXPIRED_TICKETS,
				'type'     => 'switch',
				'title'    => 'Show all types of tickets',
				'required' => array( WSB_Options::CUSTOM_EVENT_DETAILS, '=', true ),
				'subtitle' => 'Switch to OFF to hide types of tickets which are ended or sold out',
				'default'  => true,
			),
			array(
				'id'       => WSB_Options::SHOW_NUMBER_OF_TICKETS,
				'type'     => 'switch',
				'title'    => 'Show number of tickets left',
				'required' => array( WSB_Options::CUSTOM_EVENT_DETAILS, '=', true ),
				'subtitle' => 'Switch to OFF to hide the number of tickets left for each ticket type',
				'default'  => true,
			),
		);
	}

	/**
	 * Returns list of arguments for Redux::setArgs method
	 *
	 * @param $save_ajax boolean True when the changes should be saved via Ajax
	 *
	 * @since 0.3.0
	 * @return array
	 */
	protected function get_arguments( $save_ajax ) {

		$args = array(
			// TYPICAL -> Change these values as you need/desire
			'opt_name'             => $this->opt_name,
			// This is where your data is stored in the database and also becomes your global variable name.
			'display_name'         => 'Workshop Butler',
			// Name that appears at the top of your panel
			'display_version'      => WSB_INTEGRATION_VERSION,
			// Version that appears at the top of your panel
			'menu_type'            => 'submenu',
			// Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
			'allow_sub_menu'       => false,
			// Show the sections below the admin menu item or not
			'menu_title'           => __( 'Workshop Butler', 'wsbintegration' ),
			'page_title'           => __( 'Workshop Butler', 'wsbintegration' ),
			// Set it you want google fonts to update weekly. A google_api_key value is required.
			'google_update_weekly' => false,
			// Must be defined to add google fonts to the typography module
			'async_typography'     => true,
			// Use a asynchronous font on the front end or font string
			// 'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
			'admin_bar'            => false,
			// Show the panel pages on the admin bar
			'admin_bar_icon'       => 'dashicons-portfolio',
			// Choose an icon for the admin bar menu
			'admin_bar_priority'   => 50,
			// Choose an priority for the admin bar menu
			'global_variable'      => '',
			// Set a different name for your global variable other than the opt_name
			'dev_mode'             => false,
			// Show the time the page took to load, etc
			'update_notice'        => true,
			// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
			'customizer'           => true,
			// Enable basic customizer support
			// 'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
			// 'disable_save_warn' => true,                    // Disable the save warning when a user changes a field
			// OPTIONAL -> Give you extra features
			'page_priority'        => null,
			// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
			'page_parent'          => 'options-general.php',
			// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
			'page_permissions'     => 'manage_options',
			// Permissions needed to access the options panel.
			'menu_icon'            => '',
			// Specify a custom URL to an icon
			'last_tab'             => '',
			// Force your panel to always open to a specific tab (by id)
			'page_icon'            => 'icon-themes',
			// Icon displayed in the admin panel next to your menu_title
			'page_slug'            => '',
			// Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
			'save_defaults'        => true,
			// On load save the defaults to DB before user clicks save or not
			'default_show'         => false,
			// If true, shows the default value next to each field that is not the default value.
			'default_mark'         => '',
			// What to print by the field's title if the value shown is default. Suggested: *
			'show_import_export'   => false,
			// Shows the Import/Export panel when not used as a field.
			// CAREFUL -> These options are for advanced use only
			'transient_time'       => 60 * MINUTE_IN_SECONDS,
			'output'               => true,
			// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
			'output_tag'           => true,
			// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
			// 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
			// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
			'database'             => '',
			// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
			'use_cdn'              => true,
			// If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
			'ajax_save'            => $save_ajax,
			// HINTS
			'hints'                => array(
				'icon'          => 'el el-question-sign',
				'icon_position' => 'right',
				'icon_color'    => 'lightgray',
				'icon_size'     => 'normal',
				'tip_style'     => array(
					'color'   => 'red',
					'shadow'  => true,
					'rounded' => false,
					'style'   => '',
				),
				'tip_position'  => array(
					'my' => 'top left',
					'at' => 'bottom right',
				),
				'tip_effect'    => array(
					'show' => array(
						'effect'   => 'slide',
						'duration' => '500',
						'event'    => 'mouseover',
					),
					'hide' => array(
						'effect'   => 'slide',
						'duration' => '500',
						'event'    => 'click mouseleave',
					),
				),
			),
		);

		// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
		$args['share_icons'][] = array(
			'url'   => 'https://www.facebook.com/WorkshopButler',
			'title' => 'Like us on Facebook',
			'icon'  => 'el el-facebook',
		);
		$args['share_icons'][] = array(
			'url'   => 'http://twitter.com/workshopbutler',
			'title' => 'Follow us on Twitter',
			'icon'  => 'el el-twitter',
		);
		$args['share_icons'][] = array(
			'url'   => 'http://www.linkedin.com/company/workshop-butler',
			'title' => 'Find us on LinkedIn',
			'icon'  => 'el el-linkedin',
		);

		return $args;
	}

	/**
	 * Returns the named template or an empty string if it doesn't exist
	 *
	 * @param $name    string       Name of the template
	 *
	 * @since  0.3.0
	 * @return null|string
	 */
	protected function get_template( $name ) {
		$filename = plugin_dir_path( dirname( __FILE__ ) ) . '../views/' . $name . '.twig';
		$content  = file_get_contents( $filename );
		if ( ! $content ) {
			return '';
		}
		return $content;
	}

}
