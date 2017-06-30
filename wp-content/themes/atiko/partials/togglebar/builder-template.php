<?php
/**
 * Template used for the VC live editor when modifying your togglebar builder content
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.0
 */ ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?><?php wpex_schema_markup( 'html' ); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?><?php wpex_schema_markup( 'body' ); ?>>

	<div class="container clr">
		<?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
	</div><!-- .container -->

<?php wp_footer(); ?>

</body>
</html>