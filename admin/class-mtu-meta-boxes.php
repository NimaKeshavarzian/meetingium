<?php
/*
* @package meetingium
* @subpackage meetingium/admin
*
* add meta boxes for custom post types 
*/

use Meetingium\Utils\Utils;

defined( 'ABSPATH' ) || exit;

class MTU_MetaBoxes {
  public function __construct() {
    add_action("add_meta_boxes", array($this, "addMetaBoxes"));
  }

  public function addMetaBoxes($postType) {
    if($postType == "mtu_meeting") {
      add_meta_box("mtu_meeting_box", "اطلاعات کلاس", array($this, "meetingsHtml"), "mtu_meeting");
    }
  }

  /*
  * Meeting meta boxes html
  */
  public function meetingsHtml($post) {
?>
    <table class="form-table">
    <tbody>
    <tr>
    <th scope="row"><label for="meeting_users">لیست کاربران:</label></th>
    <td><input type="text" name="meeting_users" class="regular-text rtl" value="<?= get_post_meta($post->ID, "_mtu_meeting_users", true); ?>" placeholder="مثال: کاربر۱,کاربر۲,..."> </td>
    </tr>
    <tr>
    <th scope="row"><label for="meeting_time">زمان برگزاری:</label></th>
    <td><input type="text" name="meeting_time" class="regular-text rtl" value="<?= get_post_meta($post->ID, "_mtu_meeting_time", true); ?>" placeholder="مثال: شنبه‌ها ۱۶:۳۰ - دوشنبه‌ها ۱۸:۰۰"></td>
    </tr>
    <tr>
    <th scope="row"><label for="meeting_teacher">مدرس:</label></th>
    <td><input type="text" name="meeting_teacher" class="regular-text rtl" value="<?= get_post_meta($post->ID, "_mtu_meeting_teacher", true); ?>" placeholder="مثال: ابوالفضل شکوری"></td>
    </tr>
    </tbody>
    </table> 
<?php
  }

  /*
  * Save meta boxes values
  *
  * @param Int $postId
  * @param Array $metaBoxes List of meta boxes to save
  */
  public static function saveMetaBoxesData($postId, array $metaBoxes) {
    if(!current_user_can("edit_posts", $postId)) return;

    foreach ($metaBoxes as $metaBox) {
      if(isset($_POST[$metaBox])) {
        update_post_meta($postId, "_mtu_$metaBox", sanitize_text_field($_POST["$metaBox"]));
      }
    }
  }
}

new MTU_MetaBoxes();
