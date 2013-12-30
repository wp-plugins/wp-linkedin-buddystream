<?php


class WPLinkedInBuddystreamConnection extends WPLinkedInConnection {

	public function __construct($user_id) {
		$this->app_key = get_site_option('buddystream_linkedin_consumer_key');
		$this->app_secret = get_site_option('buddystream_linkedin_consumer_secret');
		$this->user_id = $user_id;
	}

	public function set_cache($key, $value, $expires=0) {
		if ($key == 'wp-linkedin_oauthtoken') {
			$key = 'buddystream_linkedin_token';
			delete_user_option($this->user_id, 'buddystream_linkedin_reauth');
		}

		return update_user_option($this->user_id, $key, $value);
	}

	public function get_cache($key, $default=false) {
		if ($key == 'wp-linkedin_oauthtoken') {
			$key = 'buddystream_linkedin_token';
		}

		$value = get_user_option($key, $this->user_id);
		return ($value !== false) ? $value : $default;
	}

	public function delete_cache($key) {
		if ($key == 'wp-linkedin_oauthtoken') {
			update_user_option($this->user_id, 'buddystream_linkedin_reauth', true);
			return false;
		}

		return delete_user_option($this->user_id, $key);
	}

	public function get_token_process_url() {
		return bp_core_get_user_domain($this->user_id) . BP_SETTINGS_SLUG . '/buddystream-networks/?network=linkedin';
	}

	public function send_invalid_token_email() {
		if (LINKEDIN_SENDMAIL_ON_TOKEN_EXPIRY && !$this->get_cache('wp-linkedin_invalid_token_mail_sent')) {
			$user_info = get_userdata($this->user_id);
			$blog_name = get_option('blogname');
			$user_email = $user_info->user_email;
			$header = array("From: $blog_name <$admin_email>");
			$subject = "[$blog_name] " . __('Invalid or expired LinkedIn access token', 'wp-linkedin-buddystream');

			$message = __("The access token for the LinkedIn API is either invalid or has expired, please click on the following link to renew it.%s\n\nThis link will only be valid for a limited period of time.\n-Thank you.", 'wp-linkedin-buddystream');
			$message = sprintf($message, $this->get_authorization_url());

			$sent = wp_mail($user_email, $subject, $message, $header);
			set_cache('wp-linkedin_invalid_token_mail_sent', $sent);
		}
	}

}
