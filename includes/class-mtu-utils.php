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
  * Get plugin base url
  *
  * @return String pugin url
  */
  public static function plguinUrl() {
    return plugin_dir_url(dirname(__FILE__));
  }
  
  /**
  * Create logs
  *
  * @param String $msg The log message
  * @param String $logLevel The log level for "Query Monitor" plugin. for more info see https://github.com/johnbillion/query-monitor
  */
  public static function log(string $msg, string $logLevel = "debug") {
    $qmAction = "qm/$logLevel";
    if(has_action($qmAction)) do_action($qmAction, $msg);
    if (defined( 'WP_DEBUG' ) && WP_DEBUG) error_log($msg);
  }

  /*
  * Return data as a formatted array
  *
  * @param Any $inData Data to return
  * @return Array $data A formatted array
  */
  public static function returnData($inData) {
    $data = array("success" => true, "data" => $inData);
    return $data;
  }

  /*
  * Return errors as a formatted array and send log
  *
  * @param String $msg Error massage
  * @return Array $error A formatted array
  */
  public static function returnErr(string $msg) {
    $error = array("success" => false, "message" => $msg);
    self::log("Error: ".$msg, "error");
    return $error;
  }

  /**
  * Generate random string
  *
  * @param Int $length Length of generated random string
  * @return string $randomString
  */
  public static function genRandomStr(int $length) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $randomString = "";

    for ($i = 0; $i < $length; $i++) {
      $randomIndex = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$randomIndex];
    }
    return $randomString;
  }
}
