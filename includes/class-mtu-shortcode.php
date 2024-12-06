<?php
/*
* @package meetingium
* 
* Shortcodes
*/

defined( 'ABSPATH' ) || exit;

use Meetingium\Utils\Utils as Utils;

class MTU_Shortcode {
  public function __construct() {
    add_action("init", function() {
      if(!shortcode_exists("mtu_meetings")) add_shortcode("mtu_meetings", array($this, "meetingsShortcode")); 
    });
  }
  /*
  * Return meetings List shortcode
  */
  public function meetingsShortcode() {
    $this->loadStyle("meetings.css");

    ob_start();
    require_once MTU_BASE_PATH . "/templates/mtu-meeting-shortcode.php";
    return ob_get_clean();
  }

  /*
  * Load css styles for shortcodes
  *
  * @param String $cssFileName name of css file to load
  */
  public function loadStyle(string $cssFileName) {
    wp_register_style("mtu_front", Utils::plguinUrl() . "/assets/css/$cssFileName");
    wp_enqueue_style("mtu_front");
  }
}

return new MTU_Shortcode();
