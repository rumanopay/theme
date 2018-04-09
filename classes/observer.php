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
 * The theme_legend custom notification observer.
 *
 * @package    theme_legend
 * @copyright  2017 GetSmarter {@link http://www.getsmarter.co.za}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_legend_observer
{
    public static function message_viewed(core\event\message_viewed $message_viewed){
        global $DB;
        static $theme_config;
    
        if (empty($theme_config)) {
            $theme_config = theme_config::load('legend');
        }

        if ($theme_config->settings->enableadvancedforumnotifications) {

            $updatemessageread = new stdClass();
            $updatemessageread->id = $message_viewed->objectid;
            $updatemessageread->timeread = '';
            
            if ($message_viewed->relateduserid != -10) {
                $currentmessage = $DB->get_record('message_read', array('id' => $message_viewed->objectid));
                if ($currentmessage) {
                  
                    $usernotifcations = $DB->get_records_select('message_read', 'useridto = '.$currentmessage->useridto.' and eventtype = "instantmessage" and notification = 1 and timecreated >= unix_timestamp(now() - interval 60 minute)');

                    if ($usernotifcations && $theme_config->settings->hideadvancedforumpostsnotifications) {
                        foreach ($usernotifcations as $notification) {
                            if ($notification->contexturl === $currentmessage->contexturl) {
                                    $updatemessageread->timeread = time();
                                    $updatemessageread->useridto = -10;
                                    break;
                            }
                        }
                    }
                }
            }

            if ($message_viewed->relateduserid == $message_viewed->userid) {
                $updatemessageread->timeread = time();
                $updatemessageread->useridto = -10;
            }

            try {
                $DB->update_record('message_read', $updatemessageread);
            } catch (Exception $e) {
                error_log(print_r($e, true));
            }
        }
    }

    public static function notification_mention_hsu(\mod_hsuforum\event\assessable_uploaded $event) {
        global $CFG, $DB;

        $other = (object)$event->other;
        $content = $other->content;

        $id_array = self::parse_id($content);

        foreach ($id_array as $id) {

            $taggeduser = $DB->get_record('user', array('id' => $id));
            $taggedusername = '@'.$taggeduser->firstname.' '.$taggeduser->lastname;

            if (strpos($content, $taggedusername) !== false ) {

                $discussion_id = $other->discussionid;
                $post_id = $event->objectid;
                $course_id = $event->courseid;
                
                $course_name = $DB->get_field("course", "fullname", array("id"=>$course_id));

                $link = $CFG->wwwroot . '/mod/hsuforum/discuss.php?d=' . $discussion_id . '#p' . $post_id;

                $subject = get_config('local_mention_users', 'defaultproperties_subject');
                $subject = str_replace("{course_fullname}", $course_name, $subject);

                $eventdata = new \core\message\message();
                $eventdata->component        = 'moodle';
                $eventdata->name             = 'instantmessage';
                $eventdata->userfrom         = -10;
                $eventdata->userto           = $id;
                $eventdata->subject          = $subject;
                $eventdata->courseid        = $event->courseid;
                $eventdata->fullmessage      = '';
                $eventdata->fullmessageformat = FORMAT_PLAIN;
                $eventdata->fullmessagehtml  = '';
                $eventdata->notification = 1;
                $eventdata->replyto = '';

                $contexturl = new moodle_url('/mod/hsuforum/discuss.php', array('d' => $discussion_id), 'p' . $post_id);
                $eventdata->contexturl = $contexturl->out();
                $eventdata->contexturlname = (isset($discussion->name) ? $discussion->name : '');
                        
                try {
                    message_send($eventdata);
                } catch (Exception $e) {
                    error_log($e);
                }
            }
        }
    }

    public static function parse_id($content) {
        $string_array = explode('userid="',$content);
        $id_array = array();

        for ($x = 1; $x < count($string_array); $x++) {
           $string = $string_array[$x];
           $id = explode('">', $string)[0];
           array_push($id_array, $id);
        }
        return $id_array;
    }  
}
