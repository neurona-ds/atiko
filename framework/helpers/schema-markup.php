<?php
/**
 * Schema markup
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.2
 */

/**
 * Outputs correct schema HTML for sections of the site
 *
 * @since 3.0.0
 */
function wpex_schema_markup( $location ) {
	echo wpex_get_schema_markup( $location );
}

/**
 * Returns correct schema HTML for sections of the site
 *
 * @since 3.0.0
 */
function wpex_get_schema_markup( $location ) {

	// Return nothing if disabled
	if ( ! wpex_get_mod( 'schema_markup_enable', true ) ) {
		return null;
	}

	// Define empty schema by default
	$schema = $itemprop = $itemtype = '';

	// Loop through locations
	if ( 'html' == $location ) {
		if ( is_singular() ) {
			$schema = 'itemscope itemtype="http://schema.org/WebPage"';
		} else {
			$schema = 'itemscope itemtype="http://schema.org/Article"';
		}
	} elseif ( 'body' == $location ) {
		if ( is_singular( 'post' ) ) {
			$itemtype = "Article";
		} elseif ( is_author() ) {
			$itemtype = 'ProfilePage';
		} elseif ( is_search() ) {
			$itemtype = 'SearchResultsPage';
		}
		if ( $itemtype ) {
			$schema = 'itemscope="itemscope" itemtype="'. $itemtype .'"';
		}
	} elseif ( 'header' == $location ) {
		$schema = 'itemscope="itemscope" itemtype="http://schema.org/WPHeader"';
	} elseif ( 'site_navigation' == $location ) {
		$schema = 'itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement"';
	} elseif ( 'main' == $location ) {
		$itemtype = 'http://schema.org/WebPageElement';
		$itemprop = 'mainContentOfPage';
		if ( is_singular( 'post' ) ) {
			$itemprop = '';
			$itemtype = 'http://schema.org/Blog';
		}
		//$schema = 'itemprop="'. $itemprop .'" itemscope="itemscope" itemtype="'. $itemtype .'"';
	} elseif ( 'sidebar' == $location ) {
		$schema = 'itemscope="itemscope" itemtype="http://schema.org/WPSideBar"';
	} elseif ( 'footer' == $location ) {
		$schema = 'itemscope="itemscope" itemtype="http://schema.org/WPFooter"';
	} elseif ( 'footer_bottom' == $location ) {
		$schema = '';
	} elseif ( 'headline' == $location ) {
		$schema = 'itemprop="headline"';
	} elseif ( 'blog_post' == $location ) {
		//$schema = 'itemprop="blogPost" itemscope="itemscope" itemtype="http://schema.org/BlogPosting"';
	} elseif ( 'entry_content' == $location ) {
		$schema = 'itemprop="text"';
	} elseif ( 'publish_date' == $location ) {
		$schema = 'itemprop="datePublished" pubdate';
	} elseif ( 'author_name' == $location ) {
		$schema = 'itemprop="name"';
	} elseif ( 'author_link' == $location ) {
		$schema = 'itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person"';
	} elseif ( 'image' == $location ) {
		$schema = 'itemprop="image"';
	}

	// Apply filters
	$schema = apply_filters( 'wpex_get_schema_markup', $schema );

	// If schema is defined return output
	if ( $schema ) {
		return ' '. $schema;
	}

}