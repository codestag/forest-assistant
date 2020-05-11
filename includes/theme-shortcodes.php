<?php

/* Theme Shortcode for social links ------------------------------------------------------------------------------------*/
function stag_social_shortcode( $atts ) {
	extract(
		shortcode_atts(
			array(
				'url' => '',
			), $atts
		)
	);

	$output = '<div class="social-icons">';
	$url    = explode( ',', $url );

	foreach ( $url as $u ) {
		$u = trim( $u );

		if ( 'email' === $u || ' email' === $u ) {
			$output .= "<a target='_blank' href='mailto:" . forest_get_thememod_value( 'forest_contact_email' ) . "'><i class='icon icon-{$u}'></i></a>";
		}
		if ( 'rss' === $u || ' rss' === $u ) {
			$output .= "<a target='_blank' href='" . get_bloginfo( 'rss_url' ) . "'><i class='icon icon-{$u}'></i></a>";
		}

		if ( '' !== forest_get_thememod_value( 'forest_social_' . $u ) ) {
			$output .= "<a target='_blank' href='" . forest_get_thememod_value( 'forest_social_' . $u ) . "' target='_blank'><i class='icon icon-{$u}'></i></a>";
		}
	}

	$output .= '</div>';

	return $output;
}
add_shortcode( 'social', 'stag_social_shortcode' );
