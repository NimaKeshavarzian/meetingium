<?php
/*
* @package meetingium
*
* Handle client requests
*/

use Meetingium\BBB_Api\MTU_BBB_Api;
use Meetingium\Utils\Utils;

defined( 'ABSPATH' ) || exit;

class MTU_Ajax {
    public function __construct() {
        add_action("wp_ajax_mtu_join_meeting", array($this, "joinMeeting"));
        add_action("wp_ajax_mtu_recordings", array($this, "getRecordings"));
    }

    /*
    * Join meetings as normal client
    */
    public function joinMeeting() {
        $postId = $_GET["post_id"];
        $userDisplayName = wp_get_current_user()->display_name;
        $pass = (current_user_can('manage_options')) ? get_post_meta($postId, "_mtu_meeting_moderator_pw", true) : get_post_meta($postId, "_mtu_meeting_attendee_pw", true);

        if(!$postId) return;
        if(!MTU_Meeting::isMeetingRunning($postId) || Utils::checkAccessToMeeting($postId)) Utils::redirect(home_url());
        $url = MTU_Meeting::join($postId, $pass, $userDisplayName);
        if($url["success"]) Utils::redirect($url["data"]);
        wp_die("مشکلی در پیوستن به کلاس پیش آمده است.");
    }

    /*
    * Get recordings
    */
    public function getRecordings() {
        $postId = $_POST["post_id"];

        if(!$postId) {
            echo json_encode(Utils::returnErr("Can't get postId"));
            exit;
        }
        $recordings = MTU_Meeting::getRecordings($postId);
        echo json_encode($recordings);
        exit;
    }
}

return new MTU_Ajax();