<?php
/*
 * @package Meetingium
 * @subpackage Meetingium/templates
 *
 * Meetings list page
 */

defined("ABSPATH") || exit();
use Meetingium\Utils\Utils as Utils;
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit();
}

$meetings = MTU_Meeting::getMeetingsList();
?>
<div class="meeting-container">
<?php foreach ($meetings as $meeting) {
    $meetingUsers = explode(",", get_post_meta($meeting->ID, "_mtu_meeting_users", true));
    if(!in_array(wp_get_current_user()->display_name, $meetingUsers)) continue;
?>
  <div class="meeting-card">
    <div class="meeting-details">
      <h3><?= $meeting->post_title ?></h3>
      <p><?= get_post_meta($meeting->ID, "_mtu_meeting_time", true) ?></p>
      <p><?= get_post_meta($meeting->ID, "_mtu_meeting_teacher", true) ?></p>
    </div>
  <a href="<?= $meeting->guid; ?>" class="view-meeting">مشاهده کلاس</a>
  </div>
<?php } ?>
</div>
