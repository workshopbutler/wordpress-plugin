<?php
/**
 * The file that defines the class with trainer-related shortcodes
 *
 * @link       https://workshopbutler.com
 * @since      0.3.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Handles the execution of the shortcodes related to trainers
 *
 * @since      0.3.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Trainer extends WSB_Page {

	/**
	 * Renders the trainer's badges
	 *
	 * @param array $attrs Short code attributes
	 *
	 * @since  0.3.0
	 * @return string
	 */
	public function render_badges( $attrs = [] ) {
		$trainer = $this->dict->get_trainer();
		if ( ! is_a( $trainer, 'Trainer' ) ) {
			return '';
		}
		$template = $this->get_template( 'badges', null );
		if ( is_null( $template ) ) {
			return '';
		}
		$html             = do_shortcode( $template );
		$attrs['trainer'] = $trainer;
		return $this->compile_string( $html, $attrs );
	}


	/**
	 * Renders a list of past events for a trainer
	 *
	 * @param array $attrs Short code attributes
	 *
	 * @since  0.3.0
	 * @return string
	 */
	public function render_events( $attrs = [] ) {
		$default_attrs = array( 'future' => true );
		$attrs         = shortcode_atts( $default_attrs, $attrs );

		$caption = $attrs['future'] === 'false' ?
			__( 'sidebar.past', 'wsbintegration' ) :
			__( 'sidebar.future', 'wsbintegration' );

		$id = $attrs['future'] === 'false' ? 'past-events' : 'upcoming-events';

		$html = <<<EOD
<div class="wsb-workshops" id="$id">
            <div class="wsb-workshops__title">
                {{ __('$caption', 'wsbintegration') }}
            </div>
            <div data-events-list>
            </div>
        </div>
EOD;
		return $html;
	}

	/**
	 * Renders the statistical parameter
	 *
	 * @param array $attrs Short code attributes
	 *
	 * @since  0.3.0
	 * @return string
	 */
	public function render_statistics( $attrs = [] ) {
		$default_attrs = array(
			'type'         => 'years',
			'show_if_zero' => false,
		);
		$attrs         = shortcode_atts( $default_attrs, $attrs );

		$template = $this->get_statistics_tmpl( $attrs['type'] );
		$trainer  = $this->dict->get_trainer();
		if ( ! is_a( $trainer, 'Trainer' ) ) {
			return '';
		}
		$data = $this->get_stat_parameter( $attrs['type'], $trainer );
		if ( $attrs['show_if_zero'] != 'true' && ! $data['parameter'] ) {
			return '';
		}
		$html = do_shortcode( $template );
		return $this->compile_string( $html, $data );
	}

	/**
	 * Renders a social link of the trainer
	 *
	 * @param array $attrs Short code attributes
	 *
	 * @since  0.3.0
	 * @return string
	 */
	public function render_social_link( $attrs = [] ) {
		$default_attrs = array( 'type' => 'twitter' );
		$attrs         = shortcode_atts( $default_attrs, $attrs );

		$template = $this->get_social_link_tmpl();
		$trainer  = $this->dict->get_trainer();
		if ( ! is_a( $trainer, 'Trainer' ) ) {
			return '';
		}

		$data = $this->get_social_link_data( $attrs['type'], $trainer );
		if ( ! $data['link'] ) {
			return '';
		}
		$html = do_shortcode( $template );
		return $this->compile_string( $html, $data );

	}

	/**
	 * Returns one or two numbers needed to render the parameters
	 *
	 * @param $type    string   Type of the statistical parameter
	 * @param $trainer Trainer
	 *
	 * @since  0.3.0
	 * @return array
	 */
	protected function get_stat_parameter( $type, $trainer ) {
		switch ( $type ) {
			case 'events':
				return array(
					'description' => 'trainer.experience.events',
					'parameter'   => $trainer->number_of_events,
				);
			case 'public-rating':
				return array(
					'parameter'             => $trainer->public_stats->number_of_evaluations, // used only for visibility check
					'description'           => 'trainer.experience.rating.public',
					'rating'                => $trainer->public_stats->rating,
					'number_of_evaluations' => $trainer->public_stats->number_of_evaluations,
				);
			case 'private-rating':
				return array(
					'parameter'             => $trainer->private_stats->number_of_evaluations, // used only for visibility check
					'description'           => 'trainer.experience.rating.private',
					'rating'                => $trainer->private_stats->rating,
					'number_of_evaluations' => $trainer->private_stats->number_of_evaluations,
				);
			default:
				return array(
					'description' => 'trainer.experience.years',
					'parameter'   => $trainer->years_of_experience,
				);
		}
	}

	/**
	 * Returns data needed to render a social link
	 *
	 * @param $type    string   Type of the social link
	 * @param $trainer Trainer
	 *
	 * @since  0.3.0
	 * @return array
	 */
	protected function get_social_link_data( $type, $trainer ) {
		switch ( $type ) {
			case 'facebook':
				$class = 'fab fa-facebook';
				break;
			case 'linkedin':
				$class = 'fab fa-linkedin';
				break;
			case 'google-plus':
				$class = 'fab fa-google';
				break;
			default:
				$class = 'fab fa-twitter';

		}
		$content = '<i class="' . $class . '"></i>';
		switch ( $type ) {
			case 'website':
				return array(
					'content' => 'Website',
					'link'    => $trainer->social_links->website,
				);
			case 'blog':
				return array(
					'content' => 'Blog',
					'link'    => $trainer->social_links->blog,
				);
			case 'facebook':
				return array(
					'content' => $content,
					'link'    => $trainer->social_links->facebook,
				);
			case 'linkedin':
				return array(
					'content' => $content,
					'link'    => $trainer->social_links->linked_in,
				);
			case 'google-plus':
				return array(
					'content' => $content,
					'link'    => $trainer->social_links->google_plus,
				);
			default:
				return array(
					'content' => $content,
					'link'    => $trainer->social_links->twitter,
				);
		}
	}


	/**
	 * Returns a social link template
	 *
	 * @since  0.3.0
	 * @return string
	 */
	protected function get_social_link_tmpl() {
		return '<a href="{{ link }}" target="_blank">{{ content | raw }}</a>';
	}

	/**
	 * Returns a template based on the type of the requested parameter
	 *
	 * @param $type string Type of the statistical parameter
	 *
	 * @since  0.3.0
	 * @return string
	 */
	protected function get_statistics_tmpl( $type ) {
		switch ( $type ) {
			case 'public-rating':
			case 'private-rating':
				return $this->get_rating_tmpl();
			default:
				return $this->get_number_tmpl();
		}
	}

	/**
	 * Returns a Twig template for a single statistical number
	 *
	 * @since  0.3.0
	 * @return string
	 */
	protected function get_number_tmpl() {
		return <<<EOD
    <div class="wsb-trainer-details-fact">
        <span class="wsb-big-number">{{ parameter }}</span>
        <span class="wsb-descr">{{ wsb_t(description) }}</span>
    </div>
EOD;
	}

	/**
	 * Returns a Twig template for a rating
	 *
	 * @since  0.3.0
	 * @return string
	 */
	protected function get_rating_tmpl() {
		return <<<EOD
    <div class="wsb-trainer-details-fact">
        <span class="wsb-big-number">{{ wsb_f(rating) }}</span>&nbsp;<span
                class="wsb-small-number">/&nbsp;10</span>
        <span class="wsb-descr">{{ wsb_t(description) }}</span>
        <span class="wsb-descr__sub">
          {{ wsb_pt('trainer.experience.rating.basedOn', number_of_evaluations) | raw }}
        </span>
    </div>
EOD;

	}

	/**
	 * Handles 'wsb_trainer_events' shortcode
	 *
	 * @param $attrs   array  Shortcode attributes
	 * @param $content string Shortcode content
	 * @since  0.3.0
	 * @return string
	 */
	static public function events( $attrs = [], $content = null ) {
		$page = new WSB_Trainer();
		return $page->render_events( $attrs );
	}

	/**
	 * Handles 'wsb_trainer_stats' shortcode
	 *
	 * @param $attrs   array  Shortcode attributes
	 * @param $content string Shortcode content
	 * @since  0.3.0
	 * @return string
	 */
	static public function statistics( $attrs = [], $content = null ) {
		$page = new WSB_Trainer();
		return $page->render_statistics( $attrs );
	}

	/**
	 * Handles 'wsb_trainer_social_link' shortcode
	 *
	 * @param $attrs   array  Shortcode attributes
	 * @param $content string Shortcode content
	 * @since  0.3.0
	 * @return string
	 */
	static public function social_link( $attrs = [], $content = null ) {
		$page = new WSB_Trainer();
		return $page->render_social_link( $attrs );
	}

}
