<?php
/*
* @package meetingium
*
* Register post types
*/

use Meetingium\Utils\Utils;

defined( 'ABSPATH' ) || exit;

class MTU_PostTypes {
  public function __construct() {
    // Hook into actions to register post types 
    add_action("init", array($this, "registerPostTypes"));
    // Load single pages templates
    add_filter("template_include", array($this, "loadSinglePageTemplate"));
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

    $pamphletPostType = array(
      "public" => true,
      "show_in_menu" => false,
      "supports" => array("title")
    );
    $pamphletPostType["labels"] = array(
      "name" => "جزوات",
      "add_new" => "جزوه جدید",
      "not_found" => "هیچ جزوه‌ای پیدا نشد."
    );

    if(!post_type_exists("mtu_meeting")) register_post_type("mtu_meeting", $meetingPostType);
    if(!post_type_exists("mtu_pamphlet")) register_post_type("mtu_pamphlet", $pamphletPostType);
  }

  /*
  * Load single page templates for custom post types
  */
  public function loadSinglePageTemplate($template) {
    if(is_singular("mtu_meeting")) {
      $template = MTU_BASE_PATH . "/templates/single-mtu_meeting.php";
    }
    return $template;
  }
}

new MTU_PostTypes();
