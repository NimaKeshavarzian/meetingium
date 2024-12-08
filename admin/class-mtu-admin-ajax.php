<?php
/*
* @package meetingium
* @subpackage meetingium/admin
*
* Handle admin ajax requests
*/

use Meetingium\Utils\Utils;

defined( 'ABSPATH' ) || exit;

class MTU_AdminAjax {
  public function __construct() {
    add_action("wp_ajax_mtu_admin_join_meeting", array($this, "joinMeetingFromAdmin"));
  }

  /*
  * Join meetings as admin if request sent to admin-ajax
  */
  public function joinMeetingFromAdmin() {
    $postId = $_GET["post_id"];
    $userDisplayName = wp_get_current_user()->display_name;
    $meetingPass = get_post_meta($postId, "_mtu_meeting_mod_pw", true);

    if(!$postId) return;
    if(!current_user_can("edit_posts", $postId)) return;

    $url = MTU_Meeting::join($postId, $meetingPass, $userDisplayName);
    if($url["success"]) Utils::redirect($url["data"]);
    wp_die("مشکلی در پیوستن به کلاس پیش آمده است.");
  }
}

return new MTU_AdminAjax();
