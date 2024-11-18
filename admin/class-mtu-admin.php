<?php
/**
* @package meetingium
* @subpackage meetingium/admin
*
* Handle admin requests and admin panel
*/

defined( 'ABSPATH' ) || exit;

class MTU_Admin {
  public function __construct() {
    add_action("admin_menu", array($this, "createAdminMenus"));
  }

  /*
  * Create admin panel menu option for Meetingium plugin
  */
  public function createAdminMenus() {
    // Add Top-Level menu
    add_menu_page(
      "میتینگیوم",
      "میتینگیوم",
      "manage_options",
      "meetingium",
      "",
      "dashicons-welcome-learn-more",
      25
    );
    // Add plugin settings sub-menu item
    add_submenu_page(
      "meetingium",
      "تنظیمات میتینگیوم",
      "تنظیمات",
      "manage_options",
      "mtu_settings",
      array($this, "displaySettingsPage")
    );
    // Add Pamphlet sub-menu item
    add_submenu_page(
      "meetingium",
      "جزوات",
      "جزوات",
      "manage_options",
      "mtu_pamphlet",
      ""
    );
  }

  /*
  * Require settings page and display that
  */
  public function displaySettingsPage() {
    // get current settings
    $bbbSettings = array(
      "url" => get_option("meetingium_bbb_url"),
      "salt" => get_option("meetingium_bbb_salt")
    );

    require_once MTU_BASE_PATH . "/admin/partials/mtu-settings-display.php"; 
  }

}

return new MTU_Admin();
