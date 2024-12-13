<?php
/*
* @package meetingium
* @subpackage meetingium/admin
*
* Manage post types in admin side
*/

defined( 'ABSPATH' ) || exit;

class MTU_AdminPostTypes {
  public $metaBoxesList = array();

  public function __construct() {
    require_once MTU_BASE_PATH . "/admin/class-mtu-meta-boxes.php";
    $this->setMetaBoxesList();
    MTU_Meeting::addCustomColumns(); // Add custom columns to "meeting" posts list

    add_action("save_post", array($this, "savePost"));
    add_action("before_delete_post", "MTU_Meeting::delete");
  }

  /*
  * Set list of meta boxes based on "add post" page of current post type
  */
  public function setMetaBoxesList() {
    if(isset($_POST["meeting_users"])) $this->metaBoxesList = array("meeting_users", "meeting_time", "meeting_teacher");
    if(isset($_POST["related_meeting"])) $this->metaBoxesList = array("related_meeting", "pamphlet_link");
  }

  /*
  * Function to run on post saves
  *
  * @param Int $postId
  */
  public function savePost($postId) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if(!current_user_can('edit_posts', $postId)) return;

    foreach($this->metaBoxesList as $metaBox) {
      if(!$_POST[$metaBox]) return;
    }

    MTU_MetaBoxes::saveMetaBoxesData($postId, $this->metaBoxesList);
    if(!isset($_POST["post_title"])) return;

    // Meeting post type
    // Create meeting on server if doesn't exits
    if(in_array("meeting_users", $this->metaBoxesList) && !get_post_meta($postId, "_mtu_meeting_id", true)) {
      MTU_Meeting::create($postId, sanitize_text_field($_POST["post_title"]));
    }
  }

} 

new MTU_AdminPostTypes();
