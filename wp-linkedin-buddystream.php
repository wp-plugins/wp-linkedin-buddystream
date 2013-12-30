<?php
/*
Plugin Name: WP LinkedIn/BuddyStream integration
Plugin URI: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin-buddystream
Description: This plugin integrates the WP-LinkedIn and BuddyStream plugins and enables showing more than one profile per install.
Author: Claude Vedovini
Author URI: http://vedovini.net/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin-buddystream
Version: 1.0.2

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


function wp_linkedin_buddystream_connection($conn) {
	global $bp;
	$user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] :
					!empty($bp->displayed_user->id) ? $bp->displayed_user->id :
					!empty($bp->loggedin_user->id) ? $bp->loggedin_user->id : false;

	if ($user_id) {
		require_once 'class-linkedin-connection.php';
		return new WPLinkedInBuddystreamConnection($user_id);
	}

	return $conn;
}
add_filter('linkedin_connection', 'wp_linkedin_buddystream_connection');


function wp_linkedin_buddystream_admin_notices() {
	if (!function_exists('buddystream_init') || !function_exists('wp_linkedin_connection')) {
		echo '<div class="error" style="font-weight:bold;">';
		echo __('The WP LinkedIn/BuddyStream integration plugin needs both the BuddyStream plugin and the WP LinkedIn plugin to be installed and activated.', 'wp-linkedin-buddystream');
		echo '</div>';
	}
}
add_action('admin_notices', 'wp_linkedin_buddystream_admin_notices');
