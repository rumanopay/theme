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
 * The theme_legend lib file for webservice
 *
 * @package    theme_legend
 * @copyright  2017 GetSmarter {@link http://www.getsmarter.co.za}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class theme_legend_external extends external_api {

    public static function mark_notification_as_read_parameters() {
        return new external_function_parameters(array('messageid' => new external_value(PARAM_INT, 'message id of message notification) in table message_read'), 'userid' => new external_value(PARAM_INT, 'user id of user message(notification) was sent to'), 'markallnotifications' => new external_value(PARAM_INT, '1 mark all notifications as read, 0 mark single notification read') )
        );
    }

    public static function mark_notification_as_read($messageid, $userid, $markallnotifications = 0) {
 		global $DB, $USER;

        if (isloggedin()) {
            $params = self::validate_parameters(self::mark_notification_as_read_parameters(), array('messageid' => $messageid, 'userid'=> $userid, 'markallnotifications' => $markallnotifications));

            switch ($markallnotifications) {

                case 1:
                    
                    $allunreadnotifications = $DB->get_records('message_read', array('useridto' => $userid , 'timeread' => 0));

                    if ($allunreadnotifications) {
                        foreach ($allunreadnotifications as $unreadnotification) {
                            $updatemessage = new stdClass();
                            $updatemessage->id = $unreadnotification->id;
                            $updatemessage->timeread = time();

                            try {
                                $DB->update_record('message_read', $updatemessage);
                            } catch (Exception $e) {
                                error_log($e);
                            } 
                            unset($updatemessage);          
                        }
                    }
                    break;
                
                case 0:

                    $updatemessage = new stdClass();
                    $updatemessage->id = $messageid;
                    $updatemessage->timeread = time();

                    try {
                        $DB->update_record('message_read', $updatemessage);
                    } catch (Exception $e) {
                        error_log($e);
                        return 0;
                    }
                    break;

                default:
                    return 1;
            }
            return 1;
        } else {
            return 0;
        }
    }

    public static function mark_notification_as_read_returns() {
        return new external_value(PARAM_INT, '1 for success 0 for error');
    }
}
