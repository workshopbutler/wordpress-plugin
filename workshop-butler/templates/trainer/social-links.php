<?php
/**
 * Trainer social links
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();

function render_link($link, $content) {
    if ( $link ) {
        echo '<a href="'.$link.'" target="_blank">'.$content.'</a>';
    }
}

render_link($trainer->social_links->website, 'Website');
render_link($trainer->social_links->blog, 'Blog');
render_link($trainer->social_links->twitter, '<i class="fab fa-twitter"></i>');
render_link($trainer->social_links->facebook, '<i class="fab fa-facebook"></i>');
render_link($trainer->social_links->linked_in, '<i class="fab fa-linkedin"></i>');

?>
