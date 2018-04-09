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
* Version details
*
* @package    theme_legend
* @copyright  2017 GetSmarter {@link http://www.getsmarter.co.za}
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

/**
* @module theme_legend/add_close_button_tooltip
*/
define(['jquery'], function($) {

    var module = {};

    module.addCloseButtonTooltip = function(){
        $('a[class*="btn btn-link p-a-0"]').each(function(){
            $(this).attr("data-content",'<a href="#" class="close" data-dismiss="popover">×</a><br>'+$(this).attr('data-content'));
        });
    };
    return module;
});
