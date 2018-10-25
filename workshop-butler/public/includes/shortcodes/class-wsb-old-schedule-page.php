<?php
/**
 * The file that defines the Old Schedule class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Old Schedule page class which handles the rendering and logic for the list of events
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Old_Schedule_Page extends WSB_Page {

	/**
	 * Retrieves the page data and renders it
	 *
	 * @param array       $attrs   Shortcode attributes.
	 * @param string|null $content Shortcode content.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function render_page( $attrs = [], $content = null ) {

		$token = get_option( 'wb_token' );
		if ( empty( $token ) ) {
			return '';
		}

		if ( ! is_array( $attrs ) ) {
			$attrs = array();
		}
		extract( shortcode_atts( array(
			'theme' => '',
			'url'   => '',
		), $attrs ) );
		$params = '';
		foreach ( $attrs as $key => $value ) {
			if ( 'widget_id' === $key ) {
				$params .= 'widgetId: "' . $value . '", ';
			} else {
				$params .= $key . ': "' . $value . '", ';
			}
		}

		$title = get_option( 'wb_title' );
		$file  = 'https://integrations.workshopbutler.com/?api_key=' . $token;

		$str = '
        <div id="hmt_widget_content"></div>
        <script>
            window.wb_config = {
                token: "' . $token . '",
                title: "' . $title . '",
                ' . $params . '                
            };
            (function(){var script = document.createElement(\'script\');script.type = \'text/javascript\';script.async = true;
                script.src = "' . $file . '&v=' . rand() . '";
                document.getElementsByTagName(\'head\')[0].appendChild(script);})();
        </script>
    ';

		return $str;
	}

	/**
	 * Handles 'wb_content' shortcode
	 *
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @since  2.0.0
	 * @return string
	 */
	static public function page( $attrs, $content, $tag ) {
		$page = new WSB_Old_Schedule_Page();
		return $page->render_page( $attrs, $content );
	}
}
