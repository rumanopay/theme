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
* @module theme_legend/fix_accessibility_legend_theme
*/
define(['jquery'], function($) {
    var module = {};
    
    module.fixAccessibilityLegendTheme = function(){
        function focus_launch_tour_button() {
            if (window.tour_button_clicked){
                $('#reset_user_tour_button').focus();
                window.tour_button_clicked = false;
            }
        }

        $('.popover-region-toggle.nav-link').attr('role', 'button');
        $('.popover-region-toggle.nav-link').removeAttr('aria-role');

        $('#page-grade-report-overview-index #region-main').find('h3').first().replaceWith(function(){
            return "<h1>" + $(this).html() + "</h1>";
        });

        $('#reset_user_tour_button').click(function(){
            window.tour_button_clicked = true;
        });

        $(document).on('click', '[data-role="end"]', function(){
            focus_launch_tour_button();
        });

        $('#page-mod-questionnaire-complete img.req').each(function(){
            var prev_div = $(this).prev('div');
            if (prev_div.hasClass('accesshide') && prev_div.text() == 'Response is required' ) {
                $(this).removeAttr('title');
                $(this).attr('alt', '');
            }
        });

        $('#page-course-view-topics .autocompletion img.icon.smallicon').each(function(){
            var title_attr = $(this).attr('title');
            var alt_attr = $(this).attr('alt');
            if (typeof title_attr !== typeof undefined && title_attr !== false && typeof alt_attr !== typeof undefined && alt_attr !== false) {
                var completion_text = title_attr.split(':')[0];
                $(this).attr('alt', completion_text );
                $(this).attr('title', completion_text);
            }
        });

        $('.block_teaching_team .user_image.defaultuserpic').each(function(){
            var title_attr = $(this).attr('title');
            var alt_attr = $(this).attr('alt');
            if (typeof title_attr !== typeof undefined && title_attr !== false && typeof alt_attr !== typeof undefined && alt_attr !== false) {
                var picture_of = title_attr.split('of ')[1];
                $(this).attr('alt', picture_of);
                $(this).attr('title', picture_of);
            }
        });

        $(document).on('DOMNodeInserted','span[data-flexitour="container"]', function (e) {

            var target = $(document).find('span[data-flexitour="container"]')[0];

            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    $(mutation.target).attr('role', 'alertdialog');
                    $(mutation.target).removeAttr('aria-labelledby');
                });
                observer.disconnect();    
            });

            var config = { attributes: true, childList: false, characterData: false, attributes: true, attributeFilter:['role'] };

            observer.observe(target, config);

            var step = $(this).children('[data-role="flexitour-step"]');

            step.removeAttr('role');
            step.find('button[data-role="next"]').removeAttr('role');
            step.find('button[data-role="previous"]').removeAttr('role');
            step.find('button[data-role="end"]').removeAttr('role');

            $(document).keyup(function(e) {
                if (e.keyCode == 27) { 
                    focus_launch_tour_button();
                }
                console.log(this);
            });
        });


        $(document).on('click', '#page-calendar-view button[data-action="new-event-button"]', function(){
            $(document).on('click', '.moreless-toggler', function(){
                $('.atto_hasmenu').each(function(){
                    $(this).attr('aria-haspopup', 'true');
                    $(this).attr('aria-expandedset', 'false');
                    $(this).attr('title', 'Paragraph styles');

                    $(this).click(function() {
                         if ($(this).attr('aria-expandedset') == 'false') {
                            $(this).attr('aria-expandedset', 'true');
                         } else {
                             $(this).attr('aria-expandedset', 'false');
                         }
                    }); 
                })
            });
        });

        $(window).on('load', function (e) {
            $('.atto_hasmenu').each(function(){
                $(this).attr('aria-haspopup', 'true');
                $(this).attr('aria-expandedset', 'false');
                $(this).attr('title', 'Paragraph styles');

                $(this).click(function() {
                    if ($(this).attr('aria-expandedset') === 'false') {
                        $(this).attr('aria-expandedset', 'true');
                    } else {
                        $(this).attr('aria-expandedset', 'false');
                    }
                });	
            })

            $(document).on('DOMNodeInserted','.atto_menu', function (e) {
                $(this).children('h3.accesshide').remove();
                $(this).children('ul').attr('role', 'menu');
            });
        });

        $('#action-menu-3').keyup(function(e){
            switch(e.keyCode) {
                case 13:
                case 32:
                    $('#action-menu-3-menu').find('a').first().focus();
                    break;
                case 38:
                    if ($(e.target).parent().is(':first-child')) {
                        $(e.target).parent().parent().find(':last-child').has('a').find('a').focus();
                    } else {
                        $(e.target).parent().prev().find('a').first().length ? $(e.target).parent().prev().find('a').first().focus() : $(e.target).parent().prevAll().has('a').first().find('a').focus();
                    }
                    break;
                case 40:
                    if ($(e.target).parent().is(':last-child')) {
                         $(e.target).parent().parent().find(':first-child').has('a').find('a').first().focus();
                    } else {
                        $(e.target).parent().next().find('a').first().length ? $(e.target).parent().next().find('a').first().focus() : $(e.target).parent().nextAll().has('a').first().find('a').focus();
                    }
                    break;
                default:
                    break;
            }
        });

        $('#action-menu-toggle-3').children('img.icon').attr('alt', 'forum actions');
        $('#menudsortkey').prev('label[for="dsortkey"]').attr('for', 'menudsortkey');

        $('abbr[title="Required"]').each(function(){
            parent = $(this).parents('.form-group');
            parent.find('input').each(function(){
                $(this).attr('aria-required', 'true');
            });

            parent.find('select').each(function(){
                $(this).attr('aria-required', 'true');
            });
        });

        $('a[data-toggletype="subscribe"]').each(function(){
            if ($(this).attr('aria-pressed') == 'true') {
                $(this).prepend('<small class="hsuforum_subscribe_small">Subscribed</small>');
            } else {
                $(this).prepend('<small class="hsuforum_subscribe_small">Subscribe</small>');
            }
        });

        $('a[data-toggletype="subscribe"]').on('click', function(){
            var parent = $(this).closest('a[data-toggletype="subscribe"]');
            parent.find('.hsuforum_subscribe_small').remove();
            if ($(this).attr('aria-pressed') == 'true') {
                $(this).prepend('<small class="hsuforum_subscribe_small">Subscribe</small>');
            } else {
               $(this).prepend('<small class="hsuforum_subscribe_small">Subscribed</small>');
            }
        });

        var radio_button_counter = p_tag_counter = 1;
        $('#page-mod-questionnaire-complete .radio-button-legend').find('p').each(function(){
            $(this).attr('id', 'desc-for-'+p_tag_counter);
            p_tag_counter++;
        });

        $('#page-mod-questionnaire-complete .qn-answer').find('input:radio').each(function(){
            if (radio_button_counter == p_tag_counter) {
                radio_button_counter = 1;
            }
            $(this).attr('aria-described-by', 'desc-for-'+radio_button_counter);
            radio_button_counter++;
        });

        $('#action-menu-3-menubar').find('span.currentlink[role="menuitem"]').each(function(){
            $(this).attr('aria-disabled', 'true');
            $(this).on('click', function(e){
                e.preventDefault();
                e.stopPropagation();
            });
            $(this).parent().on('click', function(e){
                e.preventDefault();
                e.stopPropagation();
            });
        });
	};
	return module;
})
