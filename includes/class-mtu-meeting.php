<?php
/*
 * @package meetingium
 *
 * Create, join & delete meetings using BigBlueButton api and wordpress meta keys
 */

use Meetingium\BBB_Api\MTU_BBB_Api as MTU_BBB_Api;
use Meetingium\Utils\Utils;
use Morilog\Jalali\Jalalian;

defined("ABSPATH") || exit;

class MTU_Meeting
{
    /*
     * Create meetings
     *
     * @param Int $postId
     * @param String $meetingName Title for the meeting
     */
    public static function create(int $postId, string $meetingName)
    {
        if (!$postId || !$meetingName) {
            return;
        }
        $meeting = MTU_BBB_Api::createMeeting($meetingName);
        if (!$meeting["success"]) {
            return Utils::returnErr($meeting["message"]);
        }
        $meeting = $meeting["data"];

        // Save meeting info on database as post meta data
        add_post_meta($postId, "_mtu_meeting_id", $meeting->getMeetingId());
        add_post_meta(
            $postId,
            "_mtu_meeting_mod_pw",
            $meeting->getModeratorPassword()
        );
        add_post_meta(
            $postId,
            "_mtu_meeting_attendee_pw",
            $meeting->getAttendeePassword()
        );

        return Utils::returnData($meeting);
    }

    /*
     * Join meetings
     *
     * @param Int $postId
     * @param String $password Password to join (moderatorPw for admin and attendeePw for normal clients)
     * @param String $displayName User name to display in the meeting
     */
    public static function join(
        int $postId,
        string $password,
        string $displayName
    ) {
        $postMeta = get_post_meta($postId, "", true);
        $postMeta = array_combine(
            array_keys($postMeta),
            array_column($postMeta, "0")
        );
        $meetingData = [
            "meeting_name" => get_the_title($postId),
            "meeting_id" => $postMeta["_mtu_meeting_id"],
            "mod_pw" => $postMeta["_mtu_meeting_mod_pw"],
            "attendeePw" => $postMeta["_mtu_meeting_attendee_pw"],
        ];

        $meeting = MTU_BBB_Api::createMeeting(
            $meetingData["meeting_name"],
            $meetingData["meeting_id"],
            $meetingData["mod_pw"],
            $meetingData["attendeePw"]
        );
        if (!$meeting["success"]) {
            return Utils::returnErr("Can't join meeting. wrong meeting data");
        }
        $meetingUrl = MTU_BBB_Api::getMeetingUrl(
            $meetingData["meeting_id"],
            $displayName,
            $password
        );
        if (!$meetingUrl["success"]) {
            return Utils::returnErr(
                "can't join meeting. error in join request to bbb server"
            );
        }

        return Utils::returnData($meetingUrl["data"]);
    }

    /*
     * Remove meeting
     *
     * @param Int $postId
     */
    public static function delete(int $postId)
    {
        if (!current_user_can("delete_post", $postId)) {
            return;
        }
        $meetingId = get_post_meta("_mtu_meeting_id", true);
        $moderatorPw = get_post_meta("_mtu_meeting_mod_pw", true);

        $response = MTU_BBB_Api::endMeeting($meetingId, $moderatorPw);
    }

    /*
     * Add custom columns to "meeting" post list
     */
    public static function addCustomColumns()
    {
        add_filter("manage_mtu_meeting_posts_columns", function ($columns) {
            $columns["join_meeting"] = "";
            return $columns;
        });
        add_action(
            "manage_mtu_meeting_posts_custom_column",
            function ($column, $postId) {
                if ($column == "join_meeting") {
                    echo "<div class=\"mtu_join\"><a href=" .
                        esc_url(
                            wp_nonce_url(
                                admin_url(
                                    "admin-ajax.php?action=mtu_admin_join_meeting&post_id=$postId"
                                ),
                                "mtu_admin_join_meeting"
                            )
                        ) .
                        "\">پیوستن</a></div>";
                }
            },
            10,
            2
        );
    }

    /*
     * Get meetings that current user can join them
     *
     * @return array $meetings
     */
    public static function getMeetingsList()
    {
        $userName = wp_get_current_user()->display_name;
        if (!$userName) return;
        
        $queryArgs = [
            "post_type" => "mtu_meeting",
            "meta_query" => [
                [
                    "key" => "_mtu_meeting_users",
                    "value" => $userName,
                    "compare" => "LIKE",
                ],
            ],
            "posts_per_page" => -1,
        ];
        $query = new WP_Query($queryArgs);
        $meetings = $query->get_posts();

        return $meetings;
    }

    /*
    * Get meeting recordings
    *
    * @param Int $postId
    */
    public static function getRecordings(int $postId) {
        $meetingId = get_post_meta($postId, "_mtu_meeting_id", true);
        $recordings = MTU_BBB_Api::getRecordings($meetingId);
        $res = array();

        if(!$recordings["success"]) return $recordings;
        foreach($recordings["data"] as $recording) {
            array_push($res, [
                "url" => $recording->playback->format->url,
                "title" => "جلسه ". Utils::replaceEnNum(Jalalian::forge($recording->startTime/1000)->format("%A %d %B")),
                "date" => Utils::replaceEnNum(Jalalian::forge($recording->startTime/1000)->format("%Y/%m/%d"))
            ]);
        }

        return Utils::returnData($res);
    }

    /*
    * Check is meeting running
    *
    * @param Int $postId
    */
    public static function isMeetingRunning(int $postId) {
        $meetingId = get_post_meta($postId, "_mtu_meeting_id", true);
        return MTU_BBB_Api::isMeetingRunning($meetingId);
    }
}
