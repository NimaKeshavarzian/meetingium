<?php
/*
* @package meetingium
* 
* Shortcodes
*/

use Meetingium\Utils\Utils;

defined( 'ABSPATH' ) || exit;

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
    Utils::loadStyle("meetings.css", "mtu_meetings_style");

    ob_start();
    require_once MTU_BASE_PATH . "/templates/mtu-meetings-shortcode.php";
    return ob_get_clean();
  }
}

return new MTU_Shortcode();
