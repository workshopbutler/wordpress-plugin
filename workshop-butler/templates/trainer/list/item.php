<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$trainer = WSB()->dict->get_trainer();
is_a( $trainer, 'WorkshopButler\Trainer' ) || exit();
?>

<div class="wsb-trainer-tile" data-trainer-obj
     data-trainer-location="<?= esc_attr(implode(",", $trainer->works_in)) ?>"
     data-trainer-language="<?= esc_attr(implode(",", $trainer->languages)) ?>"
     data-trainer-rating=<?= esc_attr($trainer->stats->total->public_stats->rating) ?>
     data-trainer-badge="<?= esc_attr(implode(",", $trainer->name_of_badges())) ?>"
     data-trainer-trainer="<?= esc_attr($trainer->get_full_name()) ?>">
    <?php do_action( 'wsb_trainer_list_item_photo' ); ?>
    <div class="wsb-trainer-tile__info">
        <?php do_action( 'wsb_trainer_list_item_name' ); ?>
        <?php do_action( 'wsb_trainer_list_item_country' ); ?>
        <?php do_action( 'wsb_trainer_list_item_badge' ); ?>
        <?php do_action( 'wsb_trainer_list_item_rating' ); ?>
    </div>
</div>
