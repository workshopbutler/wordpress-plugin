<?php
/**
 * The file that defines the class with trainer-related shortcodes
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wsb-page.php';

/**
 * Handles the execution of the shortcodes related to trainers
 *
 * @since      2.0.0
 * @package    WorkshopButler
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Trainer extends WSB_Page {

	/**
	 * Renders the trainer's badges
	 *
	 * @param array $attrs Short code attributes.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function render_badges( $attrs = array() ) {
		$trainer = $this->dict->get_trainer();
		if ( ! is_a( $trainer, 'WorkshopButler\Trainer' ) ) {
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
	 * @param array $attrs Short code attributes.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function render_events( $attrs = array() ) {
		$default_attrs = array( 'future' => true );
		$attrs         = shortcode_atts( $default_attrs, $attrs );

		$caption = 'false' === $attrs['future'] ?
			__( 'sidebar.past', 'wsbintegration' ) :
			__( 'sidebar.future', 'wsbintegration' );

		$id = 'false' === $attrs['future'] ? 'past-events' : 'upcoming-events';

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
	 * @param array $attrs Short code attributes.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function render_statistics( $attrs = array() ) {
		$default_attrs = array(
			'type'         => 'years',
			'show_if_zero' => false,
		);
		$attrs         = shortcode_atts( $default_attrs, $attrs );

		$template = $this->get_statistics_tmpl( $attrs['type'] );
		$trainer  = $this->dict->get_trainer();
		if ( ! is_a( $trainer, 'WorkshopButler\Trainer' ) ) {
			return '';
		}
		$data = $this->get_stat_parameter( $attrs['type'], $trainer );
		if ( 'true' !== $attrs['show_if_zero'] && ! $data['parameter'] ) {
			return '';
		}
		$html = do_shortcode( $template );
		return $this->compile_string( $html, $data );
	}

	/**
	 * Renders a social link of the trainer
	 *
	 * @param array $attrs Short code attributes.
	 *
	 * @since  2.0.0
	 * @return string
	 */
	public function render_social_link( $attrs = array() ) {
		$default_attrs = array( 'type' => 'twitter' );
		$attrs         = shortcode_atts( $default_attrs, $attrs );

		$template = $this->get_social_link_tmpl();
		$trainer  = $this->dict->get_trainer();
		if ( ! is_a( $trainer, 'WorkshopButler\Trainer' ) ) {
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
	 * @param string  $type    Type of the statistical parameter.
	 * @param Trainer $trainer Trainer to render.
	 *
	 * @since  2.0.0
	 * @return array
	 */
	protected function get_stat_parameter( $type, $trainer ) {
		switch ( $type ) {
			case 'events':
				return array(
					'description' => 'trainer.experience.events',
					'parameter'   => $trainer->stats->total->total,
				);
			case 'public-rating':
				return array(
					'parameter'             => $trainer->stats->total->public_stats->evaluations, // used only for visibility check.
					'description'           => 'trainer.experience.rating.public',
					'rating'                => $trainer->stats->total->public_stats->rating,
					'number_of_evaluations' => $trainer->stats->total->public_stats->evaluations,
				);
			case 'private-rating':
				return array(
					'parameter'             => $trainer->stats->total->private_stats->evaluations, // used only for visibility check.
					'description'           => 'trainer.experience.rating.private',
					'rating'                => $trainer->stats->total->private_stats->rating,
					'number_of_evaluations' => $trainer->stats->total->private_stats->evaluations,
				);
			default:
				return array(
					'description' => 'trainer.experience.years',
					'parameter'   => $trainer->stats->years_of_experience,
				);
		}
	}

	/**
	 * Returns data needed to render a social link
	 *
	 * @param string  $type    Type of the social link.
	 * @param Trainer $trainer Trainer to render.
	 *
	 * @since  2.0.0
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
	 * @since  2.0.0
	 * @return string
	 */
	protected function get_social_link_tmpl() {
		return '<a href="{{ link }}" target="_blank">{{ content | raw }}</a>';
	}

	/**
	 * Returns a template based on the type of the requested parameter
	 *
	 * @param string $type Type of the statistical parameter.
	 *
	 * @since  2.0.0
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
	 * @since  2.0.0
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
	 * @since  2.0.0
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
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @since  2.0.0
	 * @return string
	 */
	public static function events( $attrs = array(), $content = null ) {
		$page = new WSB_Trainer();
		return $page->render_events( $attrs );
	}

	/**
	 * Handles 'wsb_trainer_stats' shortcode
	 *
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @since  2.0.0
	 * @return string
	 */
	public static function statistics( $attrs = array(), $content = null ) {
		$page = new WSB_Trainer();
		return $page->render_statistics( $attrs );
	}

	/**
	 * Handles 'wsb_trainer_social_link' shortcode
	 *
	 * @param array  $attrs   Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @since  2.0.0
	 * @return string
	 */
	public static function social_link( $attrs = array(), $content = null ) {
		$page = new WSB_Trainer();
		return $page->render_social_link( $attrs );
	}

}
