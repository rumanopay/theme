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
* @module theme_legend/trim_section_navigation_links
*/
define(['jquery'], function($) {

  var module = {};

  module.trimSectionNavigationLinks = function(){
    var sectionlink_arrows = {
      rarrow: typeof M.sectionlinks  !== 'undefined' ? M.sectionlinks.rarrow : '',
      larrow: typeof M.sectionlinks !== 'undefined' ? M.sectionlinks.larrow : '',
    };
    $('.mdl-bottom').children().find('a').each(function () {
      $(this).each(function() {
        var originaltext = $(this).text();
        var textsplit = $(this).text().split(':');
        var sectionlink = '';
        if (typeof textsplit != "undefined" && textsplit != null && textsplit.length != null && textsplit.length > 1) {
            if ($('<textarea />').html(originaltext).text().indexOf($('<textarea />').html(sectionlink_arrows['rarrow']).text()) >= 0) {
                sectionlink = textsplit[0] + $('<textarea />').html(sectionlink_arrows['rarrow']).text();
            }
            else {
                sectionlink = textsplit[0];
            }
        }
        else {
            sectionlink = originaltext;
        }
        $(this).html(sectionlink);
      });
    });
  };
  $('.section-navigation').css('display', 'block');
  return module;
});
