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
 * The theme_legend web service.
 *
 * @package    theme_legend
 * @copyright  2017 GetSmarter {@link http://www.getsmarter.co.za}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$functions = array(

    'theme_legend_custom_notifications' => array('classname' => 'theme_legend_external', 'methodname' => 'mark_notification_as_read', 'description' => 'marks notification as read for user, and redirects to notification link','type' => array('read, write'),'ajax' => true
    )
);

$services = array('Theme legend web services' => array('functions' => array('theme_legend_custom_notifications'),'restrictedusers' => 0,'enabled' => 1, 'loginrequired' => false));
