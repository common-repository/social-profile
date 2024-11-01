<?php
/*
Plugin Name: Social Profile
Plugin URI:  http://avothemes.com/social-profile
Description: The plugin allows you to display single or multiple Twitter profiles using shortcode
Author: avoThemes
Version: 1.0.2
Author URI:  http://avothemes.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Direct access is forbidden
if (!defined('WPINC'))
{
  exit;
}

/**
 * Main class of the plugin
 */
class SocialProfilePlugin
{

  public function __construct()
  {

    // Register [twitter_profile] shortcode
    add_shortcode('twitter_profile', array($this, 'shortcode'));

    // Add link to Twitter API Settings in Plugins page
    if (is_admin())
    {
      $this->add_twitter_api_link(); 
    }
  }
  
  /**
   * Render html code for specific profile
   *
   * @param array User's profile data
   * @return string HTML code for the profile
   */  
  public function render_profile($profile)
  {
    // Load html file
    $template = file_get_contents(dirname(__FILE__) . '/card.html');

    // Replace {{tags}} with values
    $fields = array();
    $values = array();
        foreach ($profile as $field => $value)
    {
      // Format numbers
      if (stristr($field, '_count'))
      {
        $value = $this->number_format($value);
      }
      $fields[] = '{{'.mb_strtolower($field).'}}';
      $values[] = $value;
    }
    
    $html = str_replace($fields, $values, $template);
    return $html;
  }

  /**
   * Get profile data using Twitter API
   *
   * @param string Twitter username of the profile
   * @return array User's profile data
  */  
  public function get_profile($username)
  {
    $username = trim($username);

    if (empty($username))
    {
      return false;
    }

    // Cache requests with dynamic TTL - to avoid refreshing all profiles in the same time
    twitter_api_enable_cache(rand(14400,28800));  // 4 - 8 hours

    // Make API request
    try
    {
      $data = twitter_api_get('users/show', array('screen_name' => $username));
    }
    catch (Exception $e)
    {
      return false;
    }

    // List of fields from response we want to store
    $fields = array('name', 'screen_name', 'location', 'description', 'profile_image_url', 'followers_count', 'friends_count', 'statuses_count');

    // Get these fields
    $profile = array();
    foreach ($fields as $field)
    {
      if (isset($data[$field]))
      {
        // Take bigger profile image 
        if ($field == 'profile_image_url')
        {
         $data[$field] = str_replace('_normal', '_bigger', $data[$field]);
        }
        $profile[$field] = $data[$field];
      }
    }

    return $profile;
  }

  /**
   * Return HTML code for Twitter profiles
   *
   * @param array Array of Twitter usernames
   * @return string HTML code
  */  
  public function render_profiles($usernames = array())
  {

    // Do not do anything when no usernames provided
    if (empty($usernames))
    {
      return null;
    }

    // Load Twitter API library
    if (!function_exists('twitter_api_get'))
    {
      require_once dirname(__FILE__).'/api/twitter-api.php';
    }

    // Check if API is configured
    if (!twitter_api_configured())
    {
      return null;
    }

    // Get profiles and html code for each of them
    $profiles = array();
    foreach ($usernames as $username)
    {
      $profile = $this->get_profile($username);
      if ($profile)
      {
        $profiles[] = $this->render_profile($profile);
      }
    }

    // Include styles from style.css - we don't want to make additional request, as the file is small
    $html = '<style>'.PHP_EOL.file_get_contents(dirname(__FILE__) . '/style.css').PHP_EOL.'</style>';

    // Return HTML code
    $html .= '<div class="twitter_profiles">';
    $html .= implode(PHP_EOL, $profiles);
    $html .= '</div>';
    return $html;
  } 


  /**
   * Parse shortcode attributes and render shortcode
   *
   * @param array Shortcode attributes
   * @return string HTML code
  */  
  public function shortcode($atts)
  {
    // List of usernames should be separated by comma 
    $profiles = isset($atts['users']) ? explode(',', $atts['users']) : array();

    // Render profiles
    return $this->render_profiles($profiles);
  }

  /**
   * Format thousands and millions
   *
   * @param integer Number to format
   * @return string Formatted numbet with suffix
  */  
  public function number_format($value)
  {

    // Thousands
    if ($value > 999 && $value <= 999999)
    {
      return number_format($value/1000 , 1, '.', '') . 'K';
    }

    // Milions
    if ($value > 999999)
    {
      return number_format($value/1000000 , 1, '.', '') . 'M';
    }

    return $value;
  }


  /**
   * Add link to Twitter API configuration in Plugins page
  */  
  public function add_twitter_api_link()
  {
    if (!function_exists('twitter_api_get'))
    {
      require_once dirname(__FILE__).'/api/twitter-api.php';
    }
    
    function social_profile_plugin_row_meta($links, $file)
    {
      if (false !== strpos($file, '/social-profile.php'))
      {
        $links[] = '<a href="options-general.php?page=twitter-api-admin"><strong>'.esc_attr__('Connect to Twitter').'</strong></a>';
      } 
      return $links;
    }
    
    add_action('plugin_row_meta', 'social_profile_plugin_row_meta', 10, 2);
  }
}

// Create instance of the Plugin
$SocialProfilePlugin = new SocialProfilePlugin();