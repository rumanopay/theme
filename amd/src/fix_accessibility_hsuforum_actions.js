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
 * @package    local_hsuforum_actions
 * @copyright  2014 GetSmarter {@link http://www.getsmarter.co.za}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * @module local_hsuforum_actions/hsuforum_actions
 */
define(['jquery'], function($) {

    var module = {};

    module.init = function() {
        var discussionId = $('.hsuforum-thread').attr('data-discussionid');

        function getActions(discussionId) {
            $.ajax({
                dataType: "json",
                url: '/local/hsuforum_actions/getactions.php',
                data: 'd=' + discussionId,
                success: function(json) {

                    if(json.result) {
                        hsuforum_populate_post_actions(json.content);
                    }
                    else
                    {
                        window.alert(json.content);
                    }

                }
            });
        }

        if (discussionId) {
            getActions(discussionId);
        }

        var otherUsersModal = '<div id="otherUsersModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">';
        otherUsersModal += '    <div class="modal-dialog" id="yui_3_15_0_3_1401116508872_273">';
        otherUsersModal += '        <div class="modal-content" id="yui_3_15_0_3_1401116508872_272">';
        otherUsersModal += '            <div class="modal-header" id="yui_3_15_0_3_1401116508872_271">';
        otherUsersModal += '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="yui_3_15_0_3_1401116508872_270"> × </button>';
        otherUsersModal += '                <h4 class="modal-title">Permalink</h4>';
        otherUsersModal += '            </div>';
        otherUsersModal += '            <div class="modal-body" style="max-height: 420px;">';
        otherUsersModal += '                <div class="no-overflow">';
        otherUsersModal += '                </div>';
        otherUsersModal += '            </div>';
        otherUsersModal += '            <div class="modal-footer">';
        otherUsersModal += '                <input type="submit" value="Close" class="btn btn-default" data-dismiss="modal" />';
        otherUsersModal += '            </div>';
        otherUsersModal += '        </div>';
        otherUsersModal += '    </div>';
        otherUsersModal += '</div>';

        var permalinkModal = '<div id="permalinkModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">';
        permalinkModal += '    <div class="modal-dialog" id="yui_3_15_0_3_1401116508872_273">';
        permalinkModal += '        <div class="modal-content" id="yui_3_15_0_3_1401116508872_272">';
        permalinkModal += '            <div class="modal-header" id="yui_3_15_0_3_1401116508872_271">';
        permalinkModal += '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="yui_3_15_0_3_1401116508872_270"> × </button>';
        permalinkModal += '                <h4 class="modal-title">Permalink</h4>';
        permalinkModal += '            </div>';
        permalinkModal += '            <div class="modal-body" style="max-height: 420px;">';
        permalinkModal += '                <div class="no-overflow">';
        permalinkModal += '                </div>';
        permalinkModal += '            </div>';
        permalinkModal += '            <div class="modal-footer">';
        permalinkModal += '                <input type="submit" value="Close" class="btn btn-default" data-dismiss="modal" />';
        permalinkModal += '            </div>';
        permalinkModal += '        </div>';
        permalinkModal += '    </div>';
        permalinkModal += '</div>';

        // Add permalink modal
        $('div[role=main]').append(permalinkModal);

        // Add otherUsersModal modal
        $('div[role=main]').append(otherUsersModal);

        function hsuforum_populate_post_actions(posts) {
            for (var p in posts) {

                // Remove actions if already exist
                $('#p' + posts[p].id + 'actions').remove();
                // Add new actions
                $('div#p' + posts[p].id).children('.hsuforum-post-body').append(posts[p].actionHTML);
                $('article#p' + posts[p].id).children('header').append(posts[p].actionHTML);

                // Like and Thanks Buttons
                likeAndThanksButtons(posts[p]);

                // Add permalink
                $('div#p' + posts[p].id).children('.hsuforum-post-body').find('.permalink').remove();
                var url = window.location.protocol + '//' + window.location.hostname + window.location.pathname + '?d=' + discussionId + '#p' + posts[p].id;
                $('div#p' + posts[p].id).children('.hsuforum-post-body').children('.hsuforum-tools').prepend('<ul class="hsuforum-thread-tools_list"><li><a class="permalink" href="' + url + '" onclick="M.local_hsuforum_actions.showPermalinkModal(\'#p' + posts[p].id + '\');" title="' + url + '">Permalink</a></li></ul>');


            }

            // Permalink event handlers
            $('a.other-users-link').on('click', function () {
                if($(this).parent().hasClass('like')) {
                    if ($(this).closest('header').children('.hsuforum-thread-header').length) {
                        $('#otherUsersModal').find('.modal-title').text($(this).closest('header').find('.hsuforum-thread-title h4').text());
                    } else {
                        $('#otherUsersModal').find('.modal-title').text($(this).parents('.hsuforum-post-body').find('.hsuforum-post-title').text());
                    }
                    $('#otherUsersModal').find('.no-overflow').html('<p>Other people that like this post:</p>' + $(this).next('.other-users').html());
                    $('#otherUsersModal').modal('show');
                }
                else if($(this).parent().hasClass('thanks')) {
                    if ($(this).closest('header').children('.hsuforum-thread-header').length) {
                        $('#otherUsersModal').find('.modal-title').text($(this).closest('header').find('.hsuforum-thread-title h4').text());
                    } else {
                        $('#otherUsersModal').find('.modal-title').text($(this).parents('.hsuforum-post-body').find('.hsuforum-post-title').text());
                    }
                    $('#otherUsersModal').find('.no-overflow').html('<p>Other people that said thanks:</p>' + $(this).next('.other-users').html());
                    $('#otherUsersModal').modal('show');
                }
            });
        }

        // Event handlers
        $('a.other-users-link').on('click', function () {
            if($(this).parent().hasClass('like')) {
                $('#otherUsersModal').find('.modal-title').text('People who liked this post: ' + $(this).parents('.hsuforum-post-body').find('.hsuforum-post-title').text());
                $('#otherUsersModal').find('.no-overflow').html($(this).find('.other-users').html());
                $('#otherUsersModal').modal('show');
            }
        });

        document.addEventListener("DOMSubtreeModified", throttle( function() {
            if (!$('.actions').length) {
                    discussionId = $('.hsuforum-thread').attr('data-discussionid');
                    if (discussionId) {
                        getActions(discussionId);
                    }
                }
        }, 50 ), false );

        // This is to ensure that the DOMSubtreeModified event doesn't execute our code over and over.
        // http://stackoverflow.com/questions/11867331/how-to-identify-that-last-domsubtreemodified-is-fired
        function throttle( fn, time ) {
            var t = 0;
            return function() {
                var args = arguments,
                    ctx = this;

                    clearTimeout(t);

                t = setTimeout( function() {
                    fn.apply( ctx, args );
                }, time );
            };
        }
    }

    function likeAndThanksButtons(post) {
        // Remove like and thanks
        $('#p' + post.id + '-likeandthanks').remove();

        // Add like and thanks buttons to replies
        if ($('div#p' + post.id).find('.hsuforum-tools .permalink').length) {
            permalink = $('#p' + post.id).find('.hsuforum-tools .permalink').closest('ul');
            $(post.likeandthanksHTML).insertAfter(permalink);
        } else {
            $('div#p' + post.id).find('.hsuforum-tools').prepend(post.likeandthanksHTML);
        }

        // Add like and thanks buttons to initial post
        if ($('article#p' + post.id).find('header .hsuforum-tools .permalink').length) {
            permalink = $('article#p' + post.id).find('header .hsuforum-tools .permalink').closest('ul');;
            $(post.likeandthanksHTML).insertAfter(permalink);
        } else {
            $('article#p' + post.id).find('header .hsuforum-tools').prepend(post.likeandthanksHTML);
        }
    }

    module.add = function(postId, action) {
        $.ajax({
            dataType: "json",
            url: '/local/hsuforum_actions/addaction.php',
            data: 'p=' + postId + '&action=' + action,
            success: function(json) {
                if(json.result) {
                    // Remove actions if already exist
                    $('#p' + postId + 'actions').remove();
                    // Add new actions
                    $('div#p' + postId).children('.hsuforum-post-body').append(json.content[postId].actionHTML);
                    $('article#p' + postId).children('header').append(json.content[postId].actionHTML);

                    // Like and Thanks Buttons
                    likeAndThanksButtons(json.content[postId]);
                }
                else
                {
                    window.alert(json.content);
                }

            }
        });
    };

    module.remove = function(postId, action) {
        $.ajax({
            dataType: "json",
            url: '/local/hsuforum_actions/removeaction.php',
            data: 'p=' + postId + '&action=' + action,
            success: function(json) {
                if(json.result) {
                    // Remove actions if already exist
                    $('#p' + postId + 'actions').remove();
                    // Add new actions
                    $('div#p' + postId).children('.hsuforum-post-body').append(json.content[postId].actionHTML);
                    $('article#p' + postId).children('header').append(json.content[postId].actionHTML);

                    // Like and Thanks Buttons
                    likeAndThanksButtons(json.content[postId]);
                }
                else
                {
                    window.alert(json.content);
                }

            }
        });
    };

    module.showPermalinkModal = function(pId) {
        var content = '<p>Copy the following URL to link to this post.</p>';
        content += '<input type="text" style="width:500px;" onClick="this.select();" value="' + $(pId).children('.hsuforum-post-body').find('.permalink').attr('title') + '">';

        $('#permalinkModal').find('.modal-title').text($(pId).children('.hsuforum-post-body').find('.hsuforum-post-title').text());
        $('#permalinkModal').find('.no-overflow').html(content);
        $('#permalinkModal').modal('show');
    };

    window.M.local_hsuforum_actions = module;

    return module;
});