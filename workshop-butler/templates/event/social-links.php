<?php
/**
 * Social links of the event page
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Event $event
 * @global WorkshopButler\Single_Event_Config $config
 */

use WorkshopButler\Formatter;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$summary = $event->title . '. ' . Formatter::format( $event->schedule ) . ', ' . Formatter::format( $event->location );
?>
<div class="wsb-events">
	<div class="wsb-events__title">
		<?php echo esc_html__( 'event.share', 'wsbintegration' ); ?>:
	</div>
	<div class="wsb-sharing js-sharing" data-title="<?php echo esc_attr( $event->title ); ?>"
			data-summary="<?php echo esc_attr( $summary ); ?>"
			data-hashtags="training">
		<a href=""
				onclick="window.open('http://twitter.com/intent/tweet?url=' + window.location.href + '&text=' + jQuery(this).parent('div').data('summary'), '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
				class="wsb-sharing__icon type-tw" data-share-link="twitter" target="_blank">Twitter</a>
		<a href=""
				onclick="window.open('https://www.linkedin.com/shareArticle?mini=true&url=' + window.location.href + '&title=' + jQuery(this).parent('div').data('title') + '&summary=' + jQuery(this).parent('div').data('summary'), '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
				class="wsb-sharing__icon type-ld" data-share-link="linkedin" target="_blank">LinkedIn</a>
		<a href=""
				onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + window.location.href + '&t=' + jQuery(this).parent('div').data('title') + '&text=' + jQuery(this).parent('div').data('summary'), '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
				class="wsb-sharing__icon type-fb" data-share-link="facebook" target="_blank">Facebook</a>
	</div>
</div>
