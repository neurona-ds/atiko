<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wrap wpex-demo-import-wrap">

	<h1><?php esc_html_e( 'Demo Importer', 'total' ); ?></h1>
	
	<?php if ( ! empty( $this->categories ) && is_array( $this->categories ) ) : ?>
		<div class="wpex-demos-filter wpex-clr">
			<div class="wpex-demos-categories">
				<select>
					<?php
					// Loop through categories
					echo '<option value="all">'. esc_html__( 'Filter by Category', 'total' ) .'</option>';

					// Add the 'other' category at the end of the array
					if ( isset( $this->categories[ 'other' ] ) ) {
						$value = $this->categories[ 'other' ];
						unset( $this->categories[ 'other' ] );
						$this->categories[ 'other' ] = $value;
					}

					// Loop through categories and display them at the top
					foreach ( $this->categories as $category_key => $category_value ) {
						echo '<option value="'. esc_attr( $category_key ) . '">' . esc_html( $category_value ) . '</option>';
					} ?>
				</select>
			</div>
			<input name="demo-search-box" class="wpex-demos-search-box" type="text" placeholder="<?php esc_attr_e( 'Search demos...', 'total' ); ?>"></input>
		</div>
	<?php endif; ?>

	<div class="wpex-demos-select theme-browser wpex-clr">

		<?php
		if ( ! empty( $this->demos ) && is_array( $this->demos ) ) {

			// Loop through demos
			foreach ( $this->demos as $demo_key => $demo_data ) {
				$categories = '';

				// Store the demo's categories in a data attribute
				if ( isset( $demo_data['categories'] ) ) {
					foreach ( $demo_data['categories'] as $category_key => $category_value ) {
						$categories .= $categories === '' ? $category_value : ', ' . $category_value ;
					}
				}
			?>
				<div class="wpex-demo theme wpex-clr" data-demo="<?php echo esc_attr( $demo_data['demo_slug'] ); ?>" data-categories="<?php echo esc_attr( $categories ); ?>">
					
					<div class="theme-screenshot">
						<img class="wpex-lazyload" data-original="<?php echo esc_url( $demo_data['screenshot'] ); ?>" alt="<?php _e( 'Screenshot', 'total' ); ?>" />
						<span class="spinner wpex-demo-spinner"></span>
					</div>

					<h3 class="theme-name">
						<span class="wpex-demo-name"><?php echo esc_html( $demo_data['name'] ); ?></span>
						<div class="theme-actions">
							<?php
							// Get preview URL
							$demo_preview = ! empty( $demo_data['demo_slug'] ) ? 'http://totaltheme.wpengine.com/'. $demo_data['demo_slug'] .'/' : '';
							$demo_preview = ! empty( $demo_data['demo_url'] ) ? $demo_data['demo_url'] : $demo_preview; ?>
							<a href="<?php echo esc_url( $demo_preview ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Live Preview', 'total' ); ?></a>
						</div>
					</h3>

				</div>
			
			<?php } ?>

		<?php } ?>

	</div>

	<div class="wpex-submit-popup-wrap">
		<div class="wpex-submit-popup wpex-clr">
			<div class="wpex-submit-popup-content wpex-clr"></div>
		</div>
	</div>

</div><!-- .wrap -->