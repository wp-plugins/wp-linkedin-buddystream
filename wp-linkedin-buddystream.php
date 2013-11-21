<?php
/*
Plugin Name: WP LinkedIn/BuddyStream integration
Plugin URI: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin-buddystream
Description: This plugin integrates the WP-LinkedIn and BuddyStream plugins and enables showing more than one profile per install.
Author: Claude Vedovini
Author URI: http://vedovini.net/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin-buddystream
Version: 0.1

# The code in this plugin is free software; you can redistribute the code aspects of
# the plugin and/or modify the code under the terms of the GNU Lesser General
# Public License as published by the Free Software Foundation; either
# version 3 of the License, or (at your option) any later version.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#
# See the GNU lesser General Public License for more details.
*/


class WPLinkedInBuddystreamConnection extends WPLinkedInConnection {

	public function __construct($user_id) {
		$this->app_key = get_site_option('buddystream_linkedin_consumer_key');
		$this->app_secret = get_site_option('buddystream_linkedin_consumer_secret');
		$this->user_id = $user_id;
	}

	protected function set_cache($key, $value, $expires=0) {
		if ($key == 'wp-linkedin_oauthtoken') $key = 'buddystream_linkedin_token';
		return update_user_option($this->user_id, $key, $value);
	}

	protected function get_cache($key, $default=false) {
		if ($key == 'wp-linkedin_oauthtoken') $key = 'buddystream_linkedin_token';
		$value = get_user_option($key, $this->user_id);
		return ($value !== false) ? $value : $default;
	}

	protected function delete_cache($key) {
		if ($key == 'wp-linkedin_oauthtoken') return false;
		return delete_user_option($this->user_id, $key);
	}

	protected function send_invalid_token_email() {
		if (LINKEDIN_SENDMAIL_ON_TOKEN_EXPIRY && !get_cache('wp-linkedin_invalid_token_mail_sent')) {
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

function wp_linkedin_buddystream_connection($linkedin) {
	$user_id = bp_displayed_user_id();
	return $user_id ? $linkedin : WPLinkedInBuddystreamConnection($user_id);
}
add_filter('linkedin_connection', 'wp_linkedin_buddystream_connection');
