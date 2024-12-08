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
    }

    /*
    * Join meetings as normal client
    */
    public function joinMeeting() {
        $postId = $_GET["post_id"];
        $userDisplayName = wp_get_current_user()->display_name;
        $meetingPass = get_post_meta($postId, "_mtu_meeting_attendee_pw", true);

        if(!$postId) return;
        if(!MTU_Meeting::isMeetingRunning($postId)) Utils::redirect(home_url()); 
        $url = MTU_Meeting::join($postId, $meetingPass, $userDisplayName);
        if($url["success"]) Utils::redirect($url["data"]);
        wp_die("مشکلی در پیوستن به کلاس پیش آمده است.");
    }
}

return new MTU_Ajax();