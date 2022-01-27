<?php
/**
 * Trainer social links
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( !$trainer->social_links->is_empty() ) {
    return;
}

?>

<div class="wsb-trainer__social-networks">
<?php
function render_link($link, $class) {
    if ( $link ) {
        echo '<a href="'.esc_attr( $link ).'" target="_blank"><i class="'.esc_attr( $class ).'"></i></a>';
    }
}

render_link($trainer->social_links->website, 'fas fa-globe');
render_link($trainer->social_links->blog, 'fab fa-wordpress-simple');
render_link($trainer->social_links->twitter, 'fab fa-twitter');
render_link($trainer->social_links->facebook, 'fab fa-facebook');
render_link($trainer->social_links->linked_in, 'fab fa-linkedin');
?>
</div>
