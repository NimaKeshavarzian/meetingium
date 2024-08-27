<?php
/**
* @package meetingium
*
* The main "Meetingium Plugin" class
*/

defined( 'ABSPATH' ) || exit;

use Meetingium\Utils\Utils as Utils;


final class Meetingium {

  /**
  * Meetingium Constructor
  */
  public function __construct() {
    $this->defineConstants();
    $this->loadDependencies();
    $this->initHooks();
  }

  /**
  * Define Meetingium Constants
  */
  private function defineConstants() {
    define("MTU_BASE_PATH", dirname(__DIR__));
  }

  private function loadDependencies() {
    require_once MTU_BASE_PATH . "/includes/class-utils.php";
  }

  /**
  * Initialize hooks to wordpress hooks and actions
  */
  private function initHooks() {}

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
