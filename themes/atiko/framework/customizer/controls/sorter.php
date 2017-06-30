<?php
/**
 * Customizer Sorter Control
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Customize_Control_Sorter' ) ) {

	class WPEX_Customize_Control_Sorter extends WP_Customize_Control {

		public function enqueue() {
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );
		}

		public function render_content() { ?>
			<div class="wpex-sortable">
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php if ( '' != $this->description ) { ?>
						<span class="description customize-control-description"><?php echo $this->description; ?></span>
					<?php } ?>
				</label>
				<?php
				// Get values and choices
				$choices = $this->choices;
				$values  = $this->value();
				// Turn values into array
				if ( ! is_array( $values ) ) {
					$values = explode( ',', $values );
				} ?>
				<ul id="<?php echo $this->id; ?>_sortable">
					<?php
					// Loop through values
					foreach ( $values as $val ) :
						// Get label
						$label = isset( $choices[$val] ) ? $choices[$val] : '';
						if ( $label ) : ?>
							<li data-value="<?php echo esc_attr( $val ); ?>" class="wpex-sortable-li">
								<?php echo esc_html( $label ); ?>
								<span class="wpex-hide-sortee fa fa-toggle-on"></span>
							</li>
						<?php
						// End if label check
						endif;
						// Remove item from choices array - so only disabled items are left
						unset( $choices[$val] );
					// End val loop
					endforeach;
					// Loop through disabled items (disabled items have been removed alredy from choices)
					foreach ( $choices as $val => $label ) { ?>
						<li data-value="<?php echo esc_attr( $val ); ?>" class="wpex-sortable-li wpex-hide">
							<?php echo esc_html( $label ); ?>
							<span class="wpex-hide-sortee fa fa-toggle-on fa-rotate-180"></span>
						</li>
					<?php } ?>
				</ul>
			</div><!-- .wpex-sortable -->
			<div class="clear:both"></div>
			<?php
			// Return values as comma seperated string for input
			if ( is_array( $values ) ) {
				$values = array_keys( $values );
				$values = implode( ',', $values );
			} ?>
			<input id="<?php echo esc_attr( $this->id ); ?>_input" type='hidden' name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $values ); ?>" <?php echo $this->get_link(); ?> />
			<script>
			jQuery(document).ready( function($) {
				"use strict";
				// Define variables
				var sortableUl = $( '#<?php echo $this->id; ?>_sortable' );

				// Create sortable
				sortableUl.sortable()
				sortableUl.disableSelection();

				// Update values on sortstop
				sortableUl.on( "sortstop", function( event, ui ) {
					wpexUpdateSortableVal();
				} );

				// Toggle classes
				sortableUl.find( 'li' ).each( function() {
					$( this ).find( '.wpex-hide-sortee' ).click( function() {
						$( this ).toggleClass( 'fa-rotate-180' ).parents( 'li:eq(0)' ).toggleClass( 'wpex-hide' );
					} );
				})
				// Update Sortable when hidding/showing items
				$( '#<?php echo $this->id; ?>_sortable span.wpex-hide-sortee' ).click( function() {
					wpexUpdateSortableVal();
				} );
				// Used to update the sortable input value
				function wpexUpdateSortableVal() {
					var values = [];
					sortableUl.find( 'li' ).each( function() {
						if ( ! $( this ).hasClass( 'wpex-hide' ) ) {
							values.push( $( this ).attr( 'data-value' ) );
						}
					} );
					$( '#<?php echo $this->id; ?>_input' ).val(values).trigger( 'change' );
				}
			} );
			</script>
			<?php
		}
	}

}