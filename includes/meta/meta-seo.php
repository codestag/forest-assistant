<?php

function stag_metabox_seo(){
  $meta_box = array(
    'id' => 'stag_metabox_seo',
    'title' => __('SEO Settings', 'forest-assistant'),
    'description' => __('Customize the SEO settings of your posts/pages', 'forest-assistant'),
    'page' => 'post',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
      array(
        'name' => __('Title', 'forest-assistant'),
        'desc' => __('Enter the post title, most search engines use a maximum of 60 characters.', 'forest-assistant'),
        'id' => '_stag_seo_title',
        'type' => 'text',
        'std' => ''
        ),
      array(
        'name' => __('Description', 'forest-assistant'),
        'desc' => __('Enter the post SEO description, most search engines use a maximum of 160 characters.', 'forest-assistant'),
        'id' => '_stag_seo_description',
        'type' => 'text',
        'std' => ''
        ),
      array(
        'name' => __('Keywords', 'forest-assistant'),
        'desc' => __('A comma separated list of keywords', 'forest-assistant'),
        'id' => '_stag_seo_keywords',
        'type' => 'text',
        'std' => ''
        ),
      array(
        'name' => __('Meta Robots Index', 'forest-assistant'),
        'desc' => __('Do you want robots to index this page?', 'forest-assistant'),
        'id' => '_stag_seo_robots_index',
        'type' => 'radio',
        'std' => 'index',
        'options' => array('index', 'noindex')
        ),
      array(
        'name' => __('Meta Robots Follow', 'forest-assistant'),
        'desc' => __('Do you want robots to follow links from this page?', 'forest-assistant'),
        'id' => '_stag_seo_robots_follow',
        'type' => 'radio',
        'std' => 'follow',
        'options' => array('follow', 'nofollow')
        )
      )
    );
  if(!stag_check_third_party_seo()){
    stag_add_meta_box($meta_box);
    $meta_box['page'] = 'page';
    stag_add_meta_box($meta_box);
  }
}
add_action('add_meta_boxes', 'stag_metabox_seo');


/**
 * Alter WordPress title.
 *
 * @deprecated 2.1.0
 * @param string $title Default WordPress title.
 * @return void
 */
function stag_metabox_seo_title($title){
  global $post;

  if($post && !stag_check_third_party_seo()){
    if(is_home() || is_archive() || is_search()){
      $postid = get_option('page_for_posts');
    }else{
      $postid = $post->ID;
    }

    if( $seo_title = get_post_meta($postid, '_stag_seo_title', true) ){
      return $seo_title;
    }
  }
  return $title;
}
// add_filter('wp_title', 'stag_metabox_seo_title', 15);


function stag_metabox_seo_description(){
  global $post;

  if($post && !stag_check_third_party_seo()){
    if( is_home() || is_archive() || is_search() ){
      $postid = get_option('page_for_posts');
    }else{
      $postid = $post->ID;
    }

    if($seo_description = get_post_meta($postid, '_stag_seo_description', true)){
      echo '<meta name="description" content="'.esc_html(strip_tags($seo_description)).'" />'."\n";
    }
  }
}
add_action('stag_meta_head', 'stag_metabox_seo_description');


function stag_metabox_seo_keywords(){
  global $post;

  if($post && !stag_check_third_party_seo()){
    if( is_home() || is_archive() || is_search() ){
      $postid = get_option('page_for_posts');
    }else{
      $postid = $post->ID;
    }

    if($seo_keywords = get_post_meta($postid, '_stag_seo_keywords', true)){
      echo '<meta name="keywords" content="'.esc_html(strip_tags($seo_keywords)).'" />'."\n";
    }
  }
}
add_action('stag_meta_head', 'stag_metabox_seo_keywords');


function stag_metabox_seo_robots(){
  global $post;

  if($post && !stag_check_third_party_seo()){
    if( is_home() || is_archive() || is_search() ){
      $postid = get_option('page_for_posts');
    }else{
      $postid = $post->ID;
    }

    $seo_index = get_post_meta($postid, '_stag_seo_robots_index', true);
    $seo_follow = get_post_meta($postid, '_stag_seo_robots_follow', true);

    if(!$seo_index) $seo_index = 'index';
    if(!$seo_follow) $seo_follow = 'follow';

    if(!($seo_index == 'index' && $seo_follow == 'follow'))
      echo '<meta name="robots" content="'.$seo_index.', '.$seo_follow.'" />'."\n";
  }
}
add_action('stag_meta_head', 'stag_metabox_seo_robots');
