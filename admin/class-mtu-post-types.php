<?php
/*
* @package meetingium
* @subpackage meetingium/admin
*
* Register and manage post types
*/

use Meetingium\Utils\Utils as Utils;

defined( 'ABSPATH' ) || exit;

class MTU_PostTypes {
  public $metaBoxesList = array();

  public function __construct() {
    // Hook into actions to register post types 
    add_action("init", array($this, "registerPostTypes"));

    require_once MTU_BASE_PATH . "/admin/class-mtu-meta-boxes.php";
    $this->setMetaBoxesList();
    MTU_Meeting::addCustomColumns(); // Add custom columns to "meeting" posts list

    add_action("admin_post_mtu_meeting_join", "MTU_Meeting::joinFromAdmin");
    add_action("save_post", array($this, "savePost"));
    add_action("before_delete_post", "MTU_Meeting::delete");
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
    $meetingPostType["supports"] = array("title", "thumbnail");
    if(!post_type_exists("mtu_meeting")) register_post_type("mtu_meeting", $meetingPostType);

  }

  /*
  * Set list of meta boxes based on "add post" page of current post type
  */
  public function setMetaBoxesList() {
    if(isset($_POST["meeting_users"])) $this->metaBoxesList = array("meeting_users", "meeting_time", "meeting_teacher");
  }

  /*
  * Function to run on post saves
  *
  * @param Int $postId
  */
  public function savePost($postId) {
    if(!current_user_can('edit_posts', $postId)) return;

    foreach($this->metaBoxesList as $metaBox) {
      if(!$_POST[$metaBox]) return;
    }

    MTU_MetaBoxes::saveMetaBoxesData($postId, $this->metaBoxesList);
    if(!get_post_meta($postId, "_mtu_meeting_id", true)) {
      MTU_Meeting::create($postId, sanitize_text_field($_POST["post_title"]));
    }
  }
} 

new MTU_PostTypes();
