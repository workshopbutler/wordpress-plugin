<?php
if ( count( $filters ) > 0 ) { ?>
    <div class="wsb-filters"> <?php
		foreach ( $filters as $filter ) { ?>
            <select class="wsb-filter" data-filter data-name="<?= esc_attr( $filter->name ); ?>"> <?php
				foreach ( $filter->values as $value ) { ?>
                    <option class="wsb-filter"
                            value="<?= esc_attr( $value->value ) ?>"><?= $value->name ?></option>
				<?php }
				?> </select>
		<?php } ?>
    </div>
<?php } ?>
