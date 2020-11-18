<?php
function stag_metabox_team(){

  $meta_box = array(
    'id' => 'stag-metabox-team',
    'title' => __('Team Member Details', 'forest-assistant'),
    'description' => __('Here you can edit team member details.', 'forest-assistant'),
    'page' => 'team',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
      array(
        'name' => __('Member info', 'forest-assistant'),
        'desc' => __('Enter team member information or short bio.', 'forest-assistant'),
        'id' => '_stag_team_info',
        'type' => 'text',
        'std' => ''
        ),
      array(
        'name' => __('Twitter Profile URL', 'forest-assistant'),
        'desc' => __('Enter team member\'s twitter profile URL.', 'forest-assistant'),
        'id' => '_stag_team_twitter',
        'type' => 'text',
        'std' => ''
        ),
      array(
        'name' => __('Google+ Profile URL', 'forest-assistant'),
        'desc' => __('Enter team member\'s google+ profile URL.', 'forest-assistant'),
        'id' => '_stag_team_gplus',
        'type' => 'text',
        'std' => ''
        ),
      array(
        'name' => __('LinkedIN Profile URL', 'forest-assistant'),
        'desc' => __('Enter team member\'s linkedin profile URL.', 'forest-assistant'),
        'id' => '_stag_team_linkedin',
        'type' => 'text',
        'std' => ''
        ),
      )
    );
  stag_add_meta_box($meta_box);
}

add_action('add_meta_boxes', 'stag_metabox_team');