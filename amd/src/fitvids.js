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
* @module theme_legend/fitvids
*/
define(['jquery'], function($) {
    var module = {};

    module.fitVids = function(){
        // Adapted from https://github.com/davatron5000/FitVids.js

        $.fn.fitVids = function(options) {
            var settings = {
              customSelector: null,
              ignore: null,
              maxWidth: true,
              maxWidthDefault: 854,
              fixedAspectRatio: true
            };

            if(!document.getElementById('fit-vids-style')) {
              // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
              var head = document.head || document.getElementsByTagName('head')[0];
              var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;} .max-width-video-wrapper{margin: 0 auto}';
              var div = document.createElement("div");
              div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
              head.appendChild(div.childNodes[1]);
            }

            if ( options ) {
              $.extend( settings, options );
            }

            return this.each(function(){
              var selectors = [
                'iframe[src*="player.vimeo.com"]',
                'iframe[src*="youtube.com"]',
                'iframe[src*="youtube-nocookie.com"]',
                'iframe[src*="kickstarter.com"][src*="video.html"]',
                'iframe[src*="hapyak.com"]',
                'iframe[src*="interlude.fm"]',
                'iframe[src*="helloeko.com"]',
                'object',
                'embed'
              ];

              if (settings.customSelector) {
                selectors.push(settings.customSelector);
              }

              var ignoreList = '.fitvidsignore';

              if(settings.ignore) {
                ignoreList = ignoreList + ', ' + settings.ignore;
              }

              var $allVideos = $(this).find(selectors.join(','));
              $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
              $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

              $allVideos.each(function(){
                var $this = $(this);
                if($this.parents(ignoreList).length > 0) {
                  return; // Disable FitVids on this video.
                }
                if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
                if (settings.fixedAspectRatio) {
                  $this.attr('height', 480);
                  $this.attr('width', 854);
                }
                else if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
                {
                  $this.attr('height', 9);
                  $this.attr('width', 16);
                }
                var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
                    width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
                    aspectRatio = height / width;
                if(!$this.attr('name')){
                  var videoName = 'fitvid' + $.fn.fitVids._count;
                  $this.attr('name', videoName);
                  $.fn.fitVids._count++;
                }
                $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
                if (settings.maxWidth && (width || settings.maxWidthDefault)) {
                  $this.parent('.fluid-width-video-wrapper').wrap('<div class="max-width-video-wrapper"></div>').parent('.max-width-video-wrapper').css('max-width', width ? width + "px" : settings.maxWidthDefault + "px");
                }

                $this.removeAttr('height').removeAttr('width');
              });
            });
        };

        // Internal counter for unique video names.
        $.fn.fitVids._count = 0;

        $('#region-main').fitVids();

        $(document).ajaxComplete(function() {
            $('#region-main').fitVids();
        });
    };

    return module;
});