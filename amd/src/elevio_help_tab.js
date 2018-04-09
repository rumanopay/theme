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
* @copyright  2016 GetSmarter {@link http://www.getsmarter.co.za}
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

/**
* @module theme_legend/elevio_help_tab
*/
define(['jquery'], function($) {

  var module = {};

  module.showHelpTab = function(){

    window._elev = {};

    var _elev = window._elev || {};(function() {
      var isInIFrame = (window.location != window.parent.location);
        if(isInIFrame==false){
          var i,e;i=document.createElement("script"),i.type='text/javascript';i.async=1,i.src="https://static.elev.io/js/v3.js",e=document.getElementsByTagName("script")[0],e.parentNode.insertBefore(i,e);
        }
      })();
    window._elev.account_id = typeof M.account_details !== 'undefined' ? M.account_details.elevio_account_id : '';

    window._elev.user = {
      first_name: typeof M.user !== 'undefined' ? M.user.firstname : '',
      last_name: typeof M.user !== 'undefined' ? M.user.lastname : '',
      email: typeof M.user !== 'undefined' ? M.user.email : ''
    };

    window._elev.groups = ['SC'];

  };

  return module;
});
