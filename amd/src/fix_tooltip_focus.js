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
* @module theme_legend/fix_ratings_line_break
*/
define(['jquery'], function($) {
  var module = {};
  module.fixTooltipFocus = function(){
    var messages;
    $('[data-toggle="popover"]').each(function(index, messages){
      messages[index] = $(this).attr('data-content');
      var el = $(this);
      el.attr('data-content', '');
      el.on('focus', function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
      });

      el.on('mousedown', function(e) {
        el.attr('data-content', messages[index]);
      });

      el.keydown(function(e){
        el.attr('data-content', '');
        if (e.keyCode === 27) {
          el.attr('data-content', '');
          el.blur();
        } else if (e.keyCode === 13) {
          el.attr('data-content', messages[index]);
          el.blur();
          el.focus();
        } else if (e.keyCode == 9 || (e.shiftKey && e.keyCode  == 9)) {
           el.attr('data-content', '');
        } 
      });
    });
  };
  return module;
});