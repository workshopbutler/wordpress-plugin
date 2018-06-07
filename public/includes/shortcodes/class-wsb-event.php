<?php
/**
 * The file that defines the class with event-related shortcodes
 * @link       https://workshopbutler.com
 * @since      0.3.0
 *
 * @package    WSB_Integration
 */
require_once plugin_dir_path(dirname(__FILE__) ) . 'class-wsb-page.php';

/**
 * Handles the execution of the shortcodes related to events
 * @since      0.3.0
 * @package    WSB_Integration
 * @author     Sergey Kotlov <sergey@workshopbutler.com>
 */
class WSB_Event extends WSB_Page {
    
    /**
     * Handles a shortcode for only one event attribute
     * @param $name
     * @param $content
     *
     * @since  0.3.0
     * @return string
     */
    protected function event_named_shortcode( $name, $content ) {
        $handler = function($event, $template) use ($name) {
            if (empty($event->$name)) {
                return '';
            }
            $html = do_shortcode($template);
            return $this->engine->compile_string($html, array($name => $event->$name));
        };
    
        return parent::process_event_shortcode( $name, $content, $handler );
    }
    
    /**
     * Renders an event's schedule
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_dates( $attrs = []) {
        $content = <<<EOD
    <div class="wb-info">
        <div class="wb-info__title">{{ __('Date', 'wsbintegration') }}:</div>
        {{ schedule }}
    </div>
EOD;
        return $this->event_named_shortcode('schedule', $content);
    }
    
    /**
     * Renders an event's location
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_location( $attrs = []) {
        $content = <<<EOD
    <div class="wb-info">
        <div class="wb-info__title">{{ __('Location', 'wsbintegration') }}:</div>
        {{ event.location }}
        <div class="wb-info__footer">{{ event.formatted_languages }}</div>
    </div>
EOD;
        $handler = function($event, $template) use($attrs) {
            $html = do_shortcode($template);
            return $this->engine->compile_string($html, array( 'event' => $event ));
        };
        return $this->process_event_shortcode('location', $content, $handler);
    }
    
    /**
     * Renders an event's sharing widget
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_social_links( $attrs = []) {
        $handler = function($event, $template) use($attrs) {
            $html = do_shortcode($template);
            return $this->engine->compile_string($html, array( 'event' => $event ));
        };
        return $this->process_event_shortcode('share', null, $handler);
    }
    
    /**
     * Renders an event's title
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_title( $attrs = []) {
        $content = '<h1>{{ title }}</h1>';
        return $this->event_named_shortcode('title', $content);
    }
    
    
    /**
     * Renders a registration form for the event
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_registration_form( $attrs = []) {
        $handler = function($event, $template) use($attrs) {
            $countries = wsb_get_countries();
            sort($countries);
    
            $html = do_shortcode($template);
            return $this->engine->compile_string($html, array( 'event' => $event, 'countries' => $countries ));
        };
        return $this->process_event_shortcode('registration-form', null, $handler);
    }
    
    /**
     * Renders a registration button
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_registration_button( $attrs = []) {
        $content = <<<EOD
        <div>
            {% if event.is_registration_closed %}
                <button class="wb-sidebar__register" disabled>{{ event.reason_for_closed_registration }}</button>
            {% else %}
                <a href="#" target="_blank" data-registration-button
                        {% if event.tickets.get_active_ticket_id %}
                            data-ticket-id="{{ event.tickets.get_active_ticket_id }}"
                        {% endif %}
                   class="wb-sidebar__register">{{ __('Register Now', 'wsbintegration') }}</a>
            {% endif %}
        </div>
EOD;
    
        $handler = function($event, $template) use($attrs) {
            $html = do_shortcode($template);
            return $this->engine->compile_string($html, array( 'event' => $event ));
        };
        return $this->process_event_shortcode('registration-button', $content, $handler);
    }
    
    /**
     * Renders a trainer's photo
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_description( $attrs = []) {
        $content = <<<EOD
    {% if description %}
        <div class="wb-desc">{{ description }}</div>
    {% endif %}
    
EOD;
        return $this->event_named_shortcode('description', $content);
    }
    
    /**
     * Renders a list of past events for a trainer
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_events( $attrs = []) {
        $html = <<<EOD
    <div class="wb-workshops" id="upcoming-events">
        <div class="wb-workshops__title">
            {{ __('Other events', 'wsbintegration') }}:
        </div>
        <div data-events-list></div>
    </div>
EOD;
        return $html;
    }
    
    /**
     * Renders the event's trainers
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_trainers( $attrs = []) {
        $handler = function($event, $template) use($attrs) {
            $html = do_shortcode($template);
            return $this->engine->compile_string($html, array( 'event' => $event ));
        };
        return $this->process_event_shortcode('event-trainers', null, $handler);
    }
    
    /**
     * Renders event tickets
     *
     * @param array  $attrs   Short code attributes
     *
     * @since  0.3.0
     * @return string
     */
    public function render_tickets( $attrs = []) {
        $handler = function($event, $template) use($attrs) {
            $html = do_shortcode($template);
            return $this->engine->compile_string($html, array( 'event' => $event ));
        };
        return $this->process_event_shortcode('event-tickets', null, $handler);
    }
    
    /**
     * Handles 'wsb_event_registration_button' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function registration_button( $attrs = [], $content = null) {
        $page = new WSB_Event();
        return $page->render_registration_button($attrs);
    }
    
    /**
     * Handles 'wsb_event_dates' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function dates( $attrs = [], $content = null) {
        $page = new WSB_Event();
        return $page->render_dates($attrs);
    }
    
    /**
     * Handles 'wsb_event_location' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function location( $attrs = [], $content = null) {
        $page = new WSB_Event();
        return $page->render_location($attrs);
    }
    
    /**
     * Handles 'wsb_event_social_links' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function social_links( $attrs = [], $content = null) {
        $page = new WSB_Event();
        return $page->render_social_links($attrs);
    }
    
    /**
     * Handles 'wsb_event_title' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function title( $attrs = [], $content = null) {
        $page = new WSB_Event();
        return $page->render_title($attrs);
    }
    
    /**
     * Handles 'wsb_event_registration_form' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function registration_form( $attrs = [], $content = null) {
        $page = new WSB_Event();
        return $page->render_registration_form($attrs);
    }
    
    /**
     * Handles 'wsb_event_events' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function events( $attrs = [], $content = null) {
        $page = new WSB_Event();
        return $page->render_events($attrs);
    }
    
    /**
     * Handles 'wsb_event_description' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function description( $attrs = [], $content = null) {
        $page = new WSB_Event();
        return $page->render_description($attrs);
    }
    
    /**
     * Handles 'wsb_event_trainers' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function trainers( $attrs = [], $content = null) {
        $page = new WSB_Event();
        return $page->render_trainers($attrs);
    }
    
    /**
     * Handles 'wsb_event_tickets' shortcode
     *
     * @param $attrs   array  Shortcode attributes
     * @param $content string Shortcode content
     * @since  0.3.0
     * @return string
     */
    static public function tickets( $attrs = [], $content = null) {
        $page = new WSB_Event();
        return $page->render_tickets($attrs);
    }
    
}
