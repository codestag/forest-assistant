<?php

add_action('add_meta_boxes', 'stag_metabox_slider');

function stag_metabox_slider(){

  $meta_box = array(
    'id'          => 'stag-metabox-portfolio',
    'title'       => __('Slider Settings', 'stag'),
    'description' => __('Customize slider settings', 'stag'),
    'page'        => 'slides',
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
      array(
        'name' => __('Choose Slider Image', 'stag'),
        'desc' => __('Choose the slider image', 'stag'),
        'id'   => '_stag_slider_image',
        'type' => 'file',
        'std'  => ''
        ),
      array(
        'name' => __('Slider Button Link', 'stag'),
        'desc' => __('Enter the link for the slider button', 'stag'),
        'id'   => '_stag_slider_link',
        'type' => 'text',
        'std'  => ''
        ),
      array(
        'name' => __('Slider Button Text', 'stag'),
        'desc' => __('Enter the text for the slider button', 'stag'),
        'id'   => '_stag_slider_text',
        'type' => 'text',
        'std'  => ''
        ),
      )
    );
  stag_add_meta_box($meta_box);
}

?>