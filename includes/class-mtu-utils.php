<?php
/**
* @package meetingium
*
* Utilities for "Meetingium Plugin"
*/

namespace Meetingium\Utils;

defined( 'ABSPATH' ) || exit;

class Utils {
  /**
  * Create logs
  *
  * @param string $msg The log message
  * @param string $logLevel The log level for "Query Monitor" plugin. for more info see https://github.com/johnbillion/query-monitor
  */
  static public function log(string $msg, string $logLevel = "debug") {
    $qmAction = "qm/$logLevel";
    if(has_action($qmAction)) do_action($qmAction, $msg);
    if (defined( 'WP_DEBUG' ) && WP_DEBUG) error_log($msg);
  }
}
