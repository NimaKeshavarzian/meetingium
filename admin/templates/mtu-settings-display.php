<?php
// check user capabilities
if (!current_user_can('manage_options')) return;

if(isset($_POST["submit"]) && $_POST["submit"]) {
  update_option("meetingium_bbb_url", esc_url($_POST["bbb_url"]), false);
  update_option("meetingium_bbb_salt", sanitize_text_field($_POST["bbb_salt"]), false);
}
?>
<div class="wrap">
  <h1><?= esc_html(get_admin_page_title()); ?></h1>

  <form action="" method="post">
    <table class="form-table">
      <tbody>
        <tr>
					<th scope="row">
						<label for="bbb_url">آدرس سرور: </label>
					</th>
					<td>
          <input type="text" class="regular-text ltr" id="bbb_url" name="bbb_url" value="<?= $bbbSettings["url"] ?>">
					</td>
				</tr>
        <tr>
					<th scope="row">
						<label for="bbb_salt">رمز اشتراک:</label>
					</th>
					<td>
          <input type="text" class="regular-text ltr" id="bbb_salt" name="bbb_salt" value="<?= $bbbSettings["salt"] ?>">
					</td>
				</tr>
      </tbody>
    </table>
    <?php
    submit_button("ذخیره تغییرات");
    ?>
  </form>
</div>
