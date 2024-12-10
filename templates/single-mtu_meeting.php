<?php
/*
* @package Meetingium
* @subpackage Meetingium/templates
*
* Meetings single page template
*/

defined("ABSPATH") || exit;

use Meetingium\BBB_Api\MTU_BBB_Api;
use Meetingium\Utils\Utils;

get_header();
Utils::loadStyle("meeting-single.css", "mtu_meeting_single");

while (have_posts()) :
    the_post();

    $postId = get_the_ID();
    $category = get_the_category();
    if (!Utils::checkAccessToMeeting($postId)) Utils::redirect(home_url());
?>
    <div class="meeting-single">
        <h2 class="meeting-title"><?= the_title(); ?></h2>
        <div class="divider"></div>
        <div class="item">
            <div class="item-mark"></div>
            <div class="item-main-data">
                <h3 class="meeting-title"><?= the_title(); ?></h3>
                <p class="meeting-category"><?= (isset($category[0]->name)) ? $category[0]->name : "" ?></p>
            </div>
            <div class="item-meta meeting-status">
                <p class="meta-value">
                    <?= (MTU_Meeting::isMeetingRunning($postId)) ? "درحال برگزاری" : get_post_meta($postId, "_mtu_meeting_time", true); ?>
                </p>
                <p class="meta-name">زمان برگزاری</p>
            </div>
            <a href="<?= esc_url(wp_nonce_url(admin_url("admin-ajax.php?action=mtu_join_meeting&post_id=$postId"), "mtu_join_meeting")) ?>" class="mtu-btn">ورود به کلاس</a>
        </div>

        <h3 class="recordings-header">جلسات برگزار شده</h3>
        <div class="divider"></div>
        <div class="recordings" id="recordings-container">
            <h4 id="recordings-placeholder">درحال دریافت جلسات ضبط شده...</h4>
        </div>
    </div>

<?php
endwhile;

// Load js to send ajax requests
wp_register_script("mtu-meeting-script", Utils::plguinUrl() . "/assets/js/meeting.js");
wp_enqueue_script("mtu-meeting-script", Utils::plguinUrl() . "/assets/js/meeting.js");
wp_localize_script("mtu-meeting-script", "recordingsObject", array(
    "ajaxUrl" => admin_url('admin-ajax.php'),
    "nonce" => wp_create_nonce("recordings"),
    "postId" => $postId
));

get_footer();
?>