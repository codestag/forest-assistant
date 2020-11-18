<?php

function stag_metabox_portfolio() {

	$meta_box = array(
		'id'          => 'stag-metabox-portfolio',
		'title'       => __( 'Portfolio Settings', 'forest-assistant' ),
		'description' => __( 'Here you can customize project images, dates etc..', 'forest-assistant' ),
		'page'        => 'portfolio',
		'context'     => 'normal',
		'priority'    => 'high',
		'fields'      => array(
			array(
				'name' => __( 'Sub Title', 'forest-assistant' ),
				'desc' => __( 'Enter the subtitle for this portfolio item', 'forest-assistant' ),
				'id'   => '_stag_portfolio_subtitle',
				'type' => 'text',
				'std'  => '',
			),
			array(
				'name' => __( 'Client Name', 'forest-assistant' ),
				'desc' => __( 'Enter the client name of the project', 'forest-assistant' ),
				'id'   => '_stag_portfolio_client',
				'type' => 'text',
				'std'  => '',
			),
			array(
				'name' => __( 'Project Date', 'forest-assistant' ),
				'desc' => __( 'Choose the project date in mm/dd/yyyy format', 'forest-assistant' ),
				'id'   => '_stag_portfolio_date',
				'type' => 'text',
				'std'  => '',
			),
			array(
				'name' => __( 'Project URL', 'forest-assistant' ),
				'desc' => __( 'Enter the project URL', 'forest-assistant' ),
				'id'   => '_stag_portfolio_url',
				'type' => 'text',
				'std'  => '',
			),
			array(
				'name' => __( 'Project Images', 'forest-assistant' ),
				'desc' => __( 'Choose project images, ideal size 1170px x unlimited.', 'forest-assistant' ),
				'id'   => '_stag_portfolio_images',
				'type' => 'images',
				'std'  => __( 'Upload Images', 'forest-assistant' ),
			),
		),
	);
	stag_add_meta_box( $meta_box );
}

add_action( 'add_meta_boxes', 'stag_metabox_portfolio' );
