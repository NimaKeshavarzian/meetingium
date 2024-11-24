<?php
/*
* @package meetingium
* @subpackage meetingium/admin
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
}
