<?php
/**
 * The file that defines WSB_Sidebar class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Represents a sidebar events widget
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class Sidebar_Widget extends \WP_Widget {

	/**
	 * Requests entity
	 *
	 * @var WSB_Requests
	 */
	private $requests;

	/**
	 * Plugin settings
	 *
	 * @access  protected
	 * @since   2.0.0
	 * @var     WSB_Options $settings Plugin settings
	 */
	protected $settings;

	/**
	 * List of widget fields
	 *
	 * @var Sidebar_Field[] $fields
	 * @since 2.0.0
	 */
	private $fields;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->requests = new WSB_Requests();
		$options        = array(
			'description' => 'Display a list of workshops',
		);
		$this->init_fields();
		parent::__construct( 'wsb_workshop_widget', 'Workshop Butler Events', $options );
	}

	/**
	 * Load the required dependencies for this class.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . '/../../admin/includes/class-sidebar-field.php';
		require_once plugin_dir_path( __FILE__ ) . 'view/class-formatter.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-wsb-requests.php';
		require_once plugin_dir_path( __FILE__ ) . 'models/class-event-list.php';

		/**
		 * The class responsible for all plugin-related options
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '../includes/class-wsb-options.php';

		$this->settings = new WSB_Options();
	}

	/**
	 * Renders a widget's settings form
	 *
	 * @param array $instance Widget's instance.
	 *
	 * @since 2.0.0
	 */
	public function form( $instance ) {
		foreach ( $this->fields as $name => $field ) {
			switch ( $name ) {

				case 'eventtype':
					?>
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"><?php echo esc_html( $field->description ); ?></label>
                        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"
                               name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>"
                               type="<?php echo esc_attr( $this->get_field_name( $field->type ) ); ?>"
                               value="<?php echo esc_attr( isset( $instance[ $name ] ) ? $instance[ $name ] : $field->default_value ); ?>"/>
                    </p>
					<?php
					break;
				default:
					?>
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"><?php echo esc_html( $field->description ); ?></label>
                        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"
                               name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>"
                               type="<?php echo esc_attr( $this->get_field_name( $field->type ) ); ?>"
                               value="<?php echo esc_attr( isset( $instance[ $name ] ) ? $instance[ $name ] : $field->default_value ); ?>"/>
                    </p>
				<?php
			}
		}
	}

	/**
	 * Renders a widget on the page
	 *
	 * @param array $args Widget's arguments.
	 * @param array $instance Settings for the current instance of the widget.
	 *
	 * @since 2.0.0
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo $this->render( $instance );
		echo $args['after_widget'];
	}

	/**
	 * Updating widget by replacing the old instance with new
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $old_instance Old widget instance.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		foreach ( array_keys( $this->fields ) as $name ) {
			$instance[ $name ] = strip_tags( $new_instance[ $name ] );
		}

		return $instance;
	}


	/**
	 * Initialises a widget's settings fields
	 *
	 * @since 2.0.0
	 */
	private function init_fields() {
		if ( ! is_array( $this->fields ) ) {
			$this->fields = array();
		}
		$this->fields['title']     = new Sidebar_Field( 'text', 'Title', '' );
		$this->fields['length']    = new Sidebar_Field( 'number', 'Number of events', 3 );
		$this->fields['eventType'] = new Sidebar_Field( 'text', 'Event types 	(Example: 2,12)', '' );
	}

	/**
	 * Retrieves the sidebar data and renders it
	 *
	 * @param array $instance Settings for the current instance of the widget.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	public function render( $instance ) {
		$method = 'events';
		$fields = 'type,title,location,hashed_id,schedule,free,spoken_languages';

		$query = array(
			'dates'    => 'future',
			'public'   => true,
			'fields'   => $fields,
			'per_page' => $instance['length'],
		);

		if ( $instance['eventType'] ) {
			$query['typeIds'] = $instance['eventType'];
		}
		$response = $this->requests->get( $method, $query );

		return $this->render_list( $response, $instance );
	}

	/**
	 * Renders the list of events
	 *
	 * @param WSB_Response $response Workshop Butler API response.
	 * @param array        $instance Settings for the current instance of the widget.
	 *
	 * @return string
	 * @since  2.0.0
	 */
	private function render_list( $response, $instance ) {
		if ( $response->is_error() ) {
			$html = '<h2>' . __( 'Workshop Butler API: Request failed', 'wsbintegration' ) . '</h2>';
			$html .= '<p>' . __( 'Reason : ', 'wsbintegration' ) . $response->error . '</p>';

			return $html;
		}

		$events  = Event_List::from_json( $response->body->data, $this->settings, false );
		$content = '<ul>';
		foreach ( $events as $event ) {
			$target = '';
			if ( $event->is_url_external() ) {
				$target = ' target="_blank" ';
			}
			$content .= '<li>' .
						Formatter::format( $event->schedule, 'full_short' ) . ', ' .
						Formatter::format( $event->location ) . '<br>' .
						'<a href="' . $event->get_url() . '" ' . $target . '>' .
						$event->title . '</a></li>';
		}
		$content .= '</ul>';

		return $content;
	}

	/**
	 * Registers the widget
	 *
	 * @since 2.0.0
	 */
	public static function init() {
		register_widget( 'WorkshopButler\Sidebar_Widget' );
	}
}
