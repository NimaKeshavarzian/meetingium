<?php
/*
* @package Meetingium
* @subpackage Meetingium/templates
*
* Meetings list page
*/

defined("ABSPATH") || exit;

use Meetingium\Utils\Utils;

if (!is_user_logged_in()) Utils::redirect(wp_login_url());

$meetings = MTU_Meeting::getMeetingsList();
?>
<div class="meetings-container">
<?php foreach ($meetings as $meeting) {
    if(!Utils::checkAccessToMeeting($meeting->ID)) continue;
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
