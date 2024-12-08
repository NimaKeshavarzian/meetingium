<?php
/**
* @package meetingium
*
* The main "Meetingium Plugin" class
*/

defined( 'ABSPATH' ) || exit;

final class Meetingium {

  public function __construct() {
    if (!defined('MTU_BASE_PATH')) define('MTU_BASE_PATH', dirname(__DIR__));
    $this->loadDependencies();
  }

  /*
  * Require dependencies
  */
  private function loadDependencies() {
    require_once MTU_BASE_PATH . "/includes/class-mtu-utils.php";
    require_once MTU_BASE_PATH . "/includes/class-mtu-bbb-api.php";
    require_once MTU_BASE_PATH . "/includes/class-mtu-post-types.php";
    require_once MTU_BASE_PATH . "/includes/class-mtu-meeting.php";
    require_once MTU_BASE_PATH . "/includes/class-mtu-ajax.php";
    require MTU_BASE_PATH . "/includes/class-mtu-shortcode.php";
    if (is_admin()) require_once MTU_BASE_PATH . "/admin/class-mtu-admin.php";
  }

  /**
  * @return self 
  */
  static public function init() {
    static $instance = null;
    if(! $instance) {
      $instance = new Self();
    }
    return $instance;
  }
}
