<?php
/*
* @package meetingium
*
* Create, join & delete meetings using BigBlueButton api and wordpress meta keys
*/

use Meetingium\BBB_Api\MTU_BBB_Api as MTU_BBB_Api;
use Meetingium\Utils\Utils as Utils;

defined( 'ABSPATH' ) || exit;

class MTU_Meeting {
  /*
  * Create meetings
  *
  * @param Int $postId
  * @param String $meetingName Title for the meeting
  */
  public static function create(int $postId, string $meetingName) {
    if(!$postId || !$meetingName) return;
    $meeting = MTU_BBB_Api::createMeeting($meetingName);
    if(!$meeting["success"]) return Utils::returnErr($meeting["message"]);
    $meeting = $meeting["data"];

    // Save meeting info on database as post meta data
    add_post_meta($postId, "_mtu_meeting_id", $meeting->getMeetingId());
    add_post_meta($postId, "_mtu_meeting_mod_pw", $meeting->getModeratorPassword());
    add_post_meta($postId, "_mtu_meeting_attendee_pw", $meeting->getAttendeePassword());

    return Utils::returnData($meeting);
  }

  /*
  * Join meetings
  *
  * @param Int $postId
  * @param String $password Password to join (moderatorPw for admin and attendeePw for normal clients)
  * @param String $displayName User name to display in the meeting
  */
  public static function join(int $postId, string $password, string $displayName) {
    $postMeta = get_post_meta($postId, "", true);
    $postMeta = array_combine(array_keys($postMeta), array_column($postMeta, '0'));
    $meetingData = array(
      "meeting_name" => get_the_title($postId),
      "meeting_id" => $postMeta["_mtu_meeting_id"],
      "mod_pw" => $postMeta["_mtu_meeting_mod_pw"],
      "attendeePw" => $postMeta["_mtu_meeting_attendee_pw"]
    );

    $meeting = MTU_BBB_Api::createMeeting($meetingData["meeting_name"], $meetingData["meeting_id"], $meetingData["mod_pw"], $meetingData["attendeePw"]);
    if(!$meeting["success"]) return Utils::returnErr("Can't join meeting. wrong meeting data");
    $meetingUrl = MTU_BBB_Api::getMeetingUrl($meetingData["meeting_id"], $displayName, $password);
    if(!$meetingUrl["success"]) return Utils::returnErr("can't join meeting. error in join request to bbb server");

    return Utils::returnData($meetingUrl["data"]);
    
  }

  /*
  * Remove meeting
  *
  * @param Int $postId
  */
  public static function delete(int $postId) {
    if(!current_user_can("delete_post", $postId)) return;
    $meetingId = get_post_meta("_mtu_meeting_id", true);
    $moderatorPw = get_post_meta("_mtu_meeting_mod_pw", true);

    $response = MTU_BBB_Api::endMeeting($meetingId, $moderatorPw);
  }


  /*
  * Add custom columns to "meeting" post list
  */
  public static function addCustomColumns() {
    add_filter("manage_mtu_meeting_posts_columns", function($columns) {
      $columns["join_meeting"] = "";
      return $columns;
    });
    add_action("manage_mtu_meeting_posts_custom_column", function($column, $postId) {
      if($column == "join_meeting") echo "<div class=\"mtu_join\"><a href=".esc_url(wp_nonce_url(admin_url("admin-ajax.php?action=mtu_join_meeting&post_id=$postId"), "mtu_join_meeting"))."\">پیوستن</a></div>";
    }, 10, 2);
  }

}
