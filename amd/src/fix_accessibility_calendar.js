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
* @module theme_legend/fix_accessibility_calendar
*/
define(['jquery'], function($) {
    var module = {};
    module.fixAccessibilityCalendar = function(){
        $('#mform1').bind('change now', function() {
            if ($('#id_duration_1').is(':checked')) {
                $('#page-calendar-event #region-main #id_durationdetails .fdate_time_selector').show();
                $('#page-calendar-event #region-main #id_durationdetails #id_timedurationminutes').hide();
            } else if ($('#id_duration_2').is(':checked')) {
                $('#page-calendar-event #region-main #id_durationdetails #id_timedurationminutes').show();
                $('#page-calendar-event #region-main #id_durationdetails .fdate_time_selector').hide()
            } else {
                $('#page-calendar-event #region-main #id_durationdetails #id_timedurationminutes').hide();
                $('#page-calendar-event #region-main #id_durationdetails .fdate_time_selector').hide();
            }
        }).triggerHandler('now');

        var input = $('#id_duration_1'), label = input.parent();

        label.html('');
        input.appendTo(label);
        label.append(' Until a specific time');

        $('#id_duration_1').parent().next('span').next('br').remove();

        $("table.calendarmonth td.day").each(function() {
            if ($(this).children().length > 1) {
                var t = $(this).children("div.day").find("a"), e = $(this).children("ul").children("li").length, h = $("<a />").attr("href", t.attr("href")).html(e + (e > 1 ? " Events:" : " Event:"));
                $(this).children("div.day").html(t.text()), t.remove(), $(this).children("ul").prepend(h);
            }
        });
    };
    return module;
})
