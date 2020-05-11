<?php

add_action('add_meta_boxes', 'stag_metabox_team');

function stag_metabox_team(){

  $meta_box = array(
    'id' => 'stag-metabox-team',
    'title' => __('Team Member Details', 'stag'),
    'description' => __('Here you can edit team member details.', 'stag'),
    'page' => 'team',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
      array(
        'name' => __('Member info', 'stag'),
        'desc' => __('Enter team member information or short bio.', 'stag'),
        'id' => '_stag_team_info',
        'type' => 'text',
        'std' => ''
        ),
      array(
        'name' => __('Twitter Profile URL', 'stag'),
        'desc' => __('Enter team member\'s twitter profile URL.', 'stag'),
        'id' => '_stag_team_twitter',
        'type' => 'text',
        'std' => ''
        ),
      array(
        'name' => __('Google+ Profile URL', 'stag'),
        'desc' => __('Enter team member\'s google+ profile URL.', 'stag'),
        'id' => '_stag_team_gplus',
        'type' => 'text',
        'std' => ''
        ),
      array(
        'name' => __('LinkedIN Profile URL', 'stag'),
        'desc' => __('Enter team member\'s linkedin profile URL.', 'stag'),
        'id' => '_stag_team_linkedin',
        'type' => 'text',
        'std' => ''
        ),
      )
    );
  stag_add_meta_box($meta_box);
}

?>