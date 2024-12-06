<?php
/*
* @package meetingium
*
* Register post types
*/

defined( 'ABSPATH' ) || exit;

use Meetingium\Utils\Utils as Utils;

class MTU_PostTypes {
  public function __construct() {
    // Hook into actions to register post types 
    add_action("init", array($this, "registerPostTypes"));
  }

  public function registerPostTypes() {
    $meetingPostType = array(
      "public" => true,
      "menu_position" => 25,
      "menu_icon" => "dashicons-align-full-width"
    );
    $meetingPostType["labels"] = array(
      "name" => "کلاس‌ها", 
      "add_new" => "کلاس جدید",
      "add_new_item" => "اضافه کردن کلاس جدید",
      "new_item" => "کلاس جدید",
      "edit_item" => "ویرایش کلاس",
      "all_items" => "همه کلاس‌ها",
      "search_items" => "جست و جو کلاس‌ها",
      "not_found" => "هیچ کلاسی پیدا نشد." 
    );
    $meetingPostType["supports"] = array("title");
    $meetingPostType["taxonomies"] = array("category");
    if(!post_type_exists("mtu_meeting")) register_post_type("mtu_meeting", $meetingPostType);
  }
}

new MTU_PostTypes();
