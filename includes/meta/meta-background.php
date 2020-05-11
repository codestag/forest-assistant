<?php

add_action('add_meta_boxes', 'stag_metabox_page_colors');

function stag_metabox_page_colors(){

  $meta_box = array(
	'id' => 'stag-metabox-page-colors',
	'title' => __('Title Background Settings', 'stag'),
	'description' => __('Here you can customize the appearance of the post/page title\'s background.', 'stag'),
	'page' => 'page',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
	  array(
		'name' => __('Background Image', 'stag'),
		'desc' => __(' Choose background image for the title of this post/page.', 'stag'),
		'id' => '_stag_custom_background',
		'type' => 'file',
		'std' => '',
		),
	  array(
		'name' => __('Background Opacity', 'stag'),
		'desc' => __('Choose background image\'s opacity for the title of this post/page e.g. 30 for 30% opacity. Choose 100 for no opacity', 'stag'),
		'id' => '_stag_custom_background_opacity',
		'type' => 'text',
		'std' => '5'
		),
	  array(
		'name' => __('Background Color', 'stag'),
		'desc' => __('Choose background color for the title of this post/page.', 'stag'),
		'id' => '_stag_custom_background_color',
		'type' => 'color',
		'std' => '',
		// 'val' => '#2a2d30'
		),
	  )
	);
	stag_add_meta_box($meta_box);

	$meta_box['page'] = 'portfolio';
	$meta_box['title'] = __('Portfolio Item Background Settings', 'stag');
	$meta_box['description'] = __('Here you can customize the background appearance of the top section of this portfolio page.', 'stag');
	$meta_box['fields'][0]['desc'] = __('Choose background image for the top section.', 'stag');
	$meta_box['fields'][1]['desc'] = __('Choose background image\'s opacity for the top section e.g. 30 for 30% opacity. Choose 100 for no opacity', 'stag');
	$meta_box['fields'][2]['desc'] = __('Choose background color for the top section.', 'stag');
	
	stag_add_meta_box($meta_box);
}

function stag_portfolio_backgrounds($output){
	$output .= "\n/* Custom Post Background Colors and Images */\n";

	$posts = get_posts( array(
		'numberposts' => -1,
		'post_type'   => array('portfolio', 'page'),
		'post_status' => 'any'
	));

	if( empty($posts) ) return $output;

	foreach( $posts as $post ){

		$postid = $post->ID;
		$bg = get_post_meta($postid, '_stag_custom_background', true);
		$color = get_post_meta($postid, '_stag_custom_background_color', true);
		$opacity = get_post_meta($postid, '_stag_custom_background_opacity', true);
		$opacityVal = intval($opacity)/100;

		if($color != '') $output .= ".the-hero-{$postid} { background-color: {$color}; }\n";
		if($bg != '') $output .= ".the-cover-{$postid} {  background-image: url({$bg}); }\n";
		if($opacity != '' && $bg != '') $output .= ".the-cover-{$postid} { opacity: {$opacityVal}; -ms-filter: 'alpha(opacity=".$opacity.")'; }\n\n";

	}

	return $output;
}

add_action( 'stag_custom_styles', 'stag_portfolio_backgrounds', 200);