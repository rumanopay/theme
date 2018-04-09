 <?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * legend is a child theme of boost
 *
 * @package    theme_legend
 * @copyright  2016 GetSmarter {@link http://www.getsmarter.co.za}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot . '/message/renderer.php');

class theme_legend_core_message_renderer extends core_message_renderer {
    /**
    * Display the interface for notification preferences
    *
    * @param object $user instance of a user
    * @return string The text to render
    */
    public function render_user_notification_preferences($user) {
        $processors = get_message_processors();
        $providers = array_filter(message_get_providers_for_user($user->id), function($provider) {
            return $provider->component === 'mod_hsuforum';
        });

        $preferences = \core_message\api::get_all_message_preferences($processors, $providers, $user);
        $notificationlistoutput = new \core_message\output\preferences\notification_list($processors, $providers,
            $preferences, $user);

        return $this->render_from_template('message/notification_preferences',
            $notificationlistoutput->export_for_template($this));
    }
}
