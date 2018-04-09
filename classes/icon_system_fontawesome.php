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
 * Legend is a child theme of Boost
 *
 * @package    theme_legend
 * @copyright  2017 GetSmarter {@link http://www.getsmarter.co.za}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class theme_legend_icon_system_fontawesome extends \core\output\icon_system_fontawesome {
    /**
     * @var array $map Cached map of moodle icon names to font awesome icon names.
     */
    private $map = [];

    public function get_core_icon_map() {
        $iconmap = parent::get_core_icon_map();

        $iconmap['mod_book:nav_exit'] = 'fa-times';
        $iconmap['core:f/folder-24'] = 'fa-folder-open';
        $iconmap['core:e/styleprops'] = 'fa-header';
        $iconmap['core:e/text_color'] = 'fa-font';
        $iconmap['core:e/text_highlight'] = 'fa-paint-brush';
        $iconmap['core:e/clear_formatting'] = 'fa-eraser';
        $iconmap['core:e/screenreader_helper'] = 'fa-assistive-listening-systems';
        $iconmap['core:t/groups'] = 'fa-user';

        return $iconmap;
    }

}
