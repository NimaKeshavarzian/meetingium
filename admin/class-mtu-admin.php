<?php
/**
* @package meetingium
* @subpackage meetingium/admin
*
* Handle admin requests and admin panel
*/

use Meetingium\Utils\Utils as Utils;

defined( 'ABSPATH' ) || exit;

class MTU_Admin {
  public function __construct() {
    $this->loadDependencies();
    add_action("admin_menu", array($this, "createAdminMenus"));
    add_action("admin_enqueue_scripts", array($this, "loadAdminStyles"));
  }

  /*
  * Require admin classes
  */
  private function loadDependencies() {
    require_once MTU_BASE_PATH . "/admin/class-mtu-admin-post-types.php";
    require MTU_BASE_PATH . "/admin/class-mtu-admin-ajax.php";
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

    require_once MTU_BASE_PATH . "/admin/templates/mtu-settings-display.php"; 
  }

  /*
  * Load admin custom styles
  */
  public function loadAdminStyles() {
    wp_register_style("mtu_admin", Utils::plguinUrl() . "/assets/css/admin.css");
    wp_enqueue_style("mtu_admin");
  }

}

return new MTU_Admin();
