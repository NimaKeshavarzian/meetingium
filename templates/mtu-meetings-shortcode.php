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
<div class="meetings-list">
  <?php foreach ($meetings as $meeting) {
    if (!Utils::checkAccessToMeeting($meeting->ID)) continue;
    $category = get_the_category($meeting->ID);
  ?>
    <div class="item">
      <div class="item-mark"></div>
      <div class="item-main-data">
        <h3 class="meeting-title"><?= $meeting->post_title ?></h3>
        <p class="meeting-category"><?= (isset($category[0]->name)) ? $category[0]->name : "" ?></p>
      </div>
      <div class="item-meta">
        <p class="meta-value"><?= get_post_meta($meeting->ID, "_mtu_meeting_time", true) ?></p>
        <p class="meta-name">زمان برگزاری</p>
      </div>
      <div class="item-meta">
        <p class="meta-value"><?= get_post_meta($meeting->ID, "_mtu_meeting_teacher", true) ?></p>
        <p class="meta-name">مدرس</p>
      </div>
      <a href="<?= $meeting->guid ?>" class="mtu-btn">مشاهده کلاس</a>
    </div>

  <?php } ?>
</div>