<?php

function stag_metabox_slider(){

  $meta_box = array(
    'id'          => 'stag-metabox-portfolio',
    'title'       => __('Slider Settings', 'forest-assistant'),
    'description' => __('Customize slider settings', 'forest-assistant'),
    'page'        => 'slides',
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
      array(
        'name' => __('Choose Slider Image', 'forest-assistant'),
        'desc' => __('Choose the slider image', 'forest-assistant'),
        'id'   => '_stag_slider_image',
        'type' => 'file',
        'std'  => ''
        ),
      array(
        'name' => __('Slider Button Link', 'forest-assistant'),
        'desc' => __('Enter the link for the slider button', 'forest-assistant'),
        'id'   => '_stag_slider_link',
        'type' => 'text',
        'std'  => ''
        ),
      array(
        'name' => __('Slider Button Text', 'forest-assistant'),
        'desc' => __('Enter the text for the slider button', 'forest-assistant'),
        'id'   => '_stag_slider_text',
        'type' => 'text',
        'std'  => ''
        ),
      )
    );
  stag_add_meta_box($meta_box);
}

add_action('add_meta_boxes', 'stag_metabox_slider');
