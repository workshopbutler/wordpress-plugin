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
</div>
