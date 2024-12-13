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
        add_action("wp_ajax_mtu_pamphlets", array($this, "getPamphlets"));
    }

    /*
    * Join meetings as normal client
    */
    public function joinMeeting() {
        $postId = $_GET["post_id"];
        $isUserAdmin = current_user_can('manage_options');
        $userDisplayName = wp_get_current_user()->display_name;
        $pass = ($isUserAdmin) ? get_post_meta($postId, "_mtu_meeting_mod_pw", true) : get_post_meta($postId, "_mtu_meeting_attendee_pw", true);

        if(!$postId) return;
        if(!Utils::checkAccessToMeeting($postId)) Utils::redirect(home_url());
        if(!$isUserAdmin && !MTU_Meeting::isMeetingRunning($postId)) Utils::redirect(home_url());
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

    /*
    * Get pamphlets
    */
    public function getPamphlets() {
        $postId = $_POST["post_id"];
        
        if(!$postId) {
            echo json_encode(Utils::returnErr("Can't get postId"));
            exit;
        }

        $queryArgs = array(
            "post_type" => "mtu_pamphlet",
            "meta_query" => [
                "key" => "_mtu_related_meeting",
                "value" => $postId
            ],
            "posts_per_page" => -1
        );
        $query = new WP_Query($queryArgs);
        $pamphlets = $query->get_posts();
        $res = array();

        foreach($pamphlets as $pamphlet) {
            array_push($res, [
                "title" => $pamphlet->post_title,
                "url" => get_post_meta($pamphlet->ID, "_mtu_pamphlet_link", true)
            ]);
        }

        echo json_encode(Utils::returnData($res));
        exit;
    }
}

return new MTU_Ajax();