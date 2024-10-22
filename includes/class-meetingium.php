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

  private function loadDependencies() {
    require_once MTU_BASE_PATH . "/includes/class-mtu-utils.php";
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
