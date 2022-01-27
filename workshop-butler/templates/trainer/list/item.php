<?php
/**
 * Trainer list item
 *
 * @version 3.0.0
 * @package WorkshopButler\Templates
 * @global WorkshopButler\Trainer $trainer
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="wsb-trainer-tile" data-trainer-obj
     data-trainer-location="<?php echo esc_attr(implode(",", $trainer->works_in)) ?>"
     data-trainer-language="<?php echo esc_attr(implode(",", $trainer->languages)) ?>"
     data-trainer-rating=<?php echo esc_attr($trainer->get_displayed_public_rating()) ?>
     data-trainer-badge="<?php echo esc_attr(implode(",", $trainer->name_of_badges())) ?>"
     data-trainer-trainer="<?php echo esc_attr($trainer->get_full_name()) ?>">
    <?php do_action( 'wsb_trainer_list_item_photo' ); ?>
    <?php do_action( 'wsb_trainer_list_item_rating' ); ?>
    <div class="wsb-trainer-tile__info">
        <?php do_action( 'wsb_trainer_list_item_name' ); ?>
        <?php do_action( 'wsb_trainer_list_item_country' ); ?>
        <?php do_action( 'wsb_trainer_list_item_badge' ); ?>

    </div>
</div>
