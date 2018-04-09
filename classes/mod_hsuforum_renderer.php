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
 * legend is a child theme of Essential
 *
 * @package    theme_legend
 * @copyright  2016 GetSmarter {@link http://www.getsmarter.co.za}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class theme_legend_mod_hsuforum_renderer extends mod_hsuforum_renderer {

      /**
     * Return html for individual post
     *
     * 3 use cases:
     *  1. Standard post
     *  2. Reply to user
     *  3. Private reply to user
     *
     * @param object $p
     * @return string
     */
    public function post_template($p) {
        global $PAGE, $DB, $USER;

        $byuser = $p->fullname;
        if (!empty($p->userurl)) {
            $byuser = html_writer::link($p->userurl, $p->fullname);
        }
        $byline = get_string('postbyx', 'hsuforum', $byuser);
        if ($p->isreply) {
            $parent = $p->parentfullname;
            if (!empty($p->parentuserurl)) {
                $parent = html_writer::link($p->parentuserurl, $p->parentfullname);
            }
            if (empty($p->parentuserpic)) {
                $byline = get_string('replybyx', 'hsuforum', $byuser);
            } else {
                $byline = get_string('postfromx', 'theme_legend', array(
                        'author' => $byuser,
                        'parentpost' => "<a title='".get_string('parentofthispost', 'hsuforum')."' class='hsuforum-parent-post-link disable-router' href='$p->parenturl'><span class='accesshide'>".get_string('parentofthispost', 'hsuforum')."</span>↑</a>"
                ));
            }
            if (!empty($p->privatereply)) {
                if (empty($p->parentuserpic)) {
                    $byline = get_string('privatereplybyx', 'hsuforum', $byuser);
                } else {
                    $byline = get_string('postbyxinprivatereplytox', 'hsuforum', array(
                            'author' => $byuser,
                            'parent' => $p->parentuserpic.$parent
                        ));
                }
            }
        } else if (!empty($p->privatereply)) {
            $byline = get_string('privatereplybyx', 'hsuforum', $byuser);
        }

        $author = s(strip_tags($p->fullname));
        $unread = '';
        $unreadclass = '';
        if ($p->unread) {
            $unread = "<span class='hsuforum-unreadcount'>".get_string('unread', 'hsuforum')."</span>";
            $unreadclass = "hsuforum-post-unread";
        }
        $options = get_string('options', 'hsuforum');
        $datecreated = hsuforum_relative_time($p->rawcreated, array('class' => 'hsuforum-post-pubdate'));


        $postreplies = '';
        if($p->replycount) {
            $postreplies = "<div class='post-reply-count accesshide'>$p->replycount</div>";
        }

        $newwindow = '';
        if ($PAGE->pagetype === 'local-joulegrader-view') {
            $newwindow = ' target="_blank"';
        }

        $revealed = "";
        if ($p->revealed) {
            $nonanonymous = get_string('nonanonymous', 'mod_hsuforum');
            $revealed = '<span class="label label-danger">'.$nonanonymous.'</span>';
        }

        /*
         * Flags and timezone
         * Add users country flag and timezone.
        */
        $flagandtimezone = '';
        if ($PAGE->theme->settings->display_flag_and_time) {
            $userid = $p->userurl->params()['id'];

            $data = $DB->get_record_sql('SELECT timezone, country FROM {user} WHERE id = ?',
                           array($userid));

            $flagandtimezone = theme_legend_user_flag_and_timezone($data);
        }

        /*
         * Start CSS class for role
         * Addition
         * Adds colour to post entry dependant
         * on user that made the post.
        */
        $roleclass = '';
        if($userid = $p->userurl->params()['id']) {
            $courseid = $p->userurl->params()['course'];
            $context = context_course::instance($courseid);
            $roles = get_user_roles($context, $userid);
            foreach ($roles as $role) {
                $roleclass .= $role->shortname.' ';
            }
            $roleclass = rtrim($roleclass);
        }

        /*
         * CSS class for role
         * Addition
         * Adds colour to post entry dependant
         * on user that made the post.
        */
        $loggedinuser = '';
        if($userid = $p->userurl->params()['id']) {
            if($loggedinuserid = $USER->id){
                if($userid === $loggedinuserid) {
                    $loggedinuser = 'loggedinuser';
                }
            }
        }

        if (isset($p->userid)) {
            $useridp = $p->userid;
        } else {
            $useridp = '';
        }

        $tools = $p->tools;

        if (theme_legend_accessibility_enabled()) {
            $tools = '<ul class="hsuforum-thread-tools_list">'.$p->tools.'</ul>';
        }
 return <<<HTML
<div class="hsuforum-post-wrapper hsuforum-post-target clearfix $roleclass $loggedinuser $unreadclass" id="p$p->id" data-postid="$p->id" data-discussionid="$p->discussionid" data-author="$author" data-ispost="true" tabindex="-1">

    <div class="hsuforum-post-figure {$useridp}">
        <a href="$p->userurl">
            <img class="userpicture" src="{$p->imagesrc}" alt="">
        </a>
        $flagandtimezone
    </div>

    <div class="hsuforum-post-body">
        <h6 role="heading" aria-level="6" class="hsuforum-post-byline" id="hsuforum-post-$p->id">
            $unread $byline $revealed
        </h6>
        <small class='hsuform-post-date'><a href="$p->permalink" class="disable-router"$newwindow>$datecreated</a></small>

        <div class="hsuforum-post-content">
            <div class="hsuforum-post-title">$p->subject</div>
            $p->message
        </div>
        <div role="region" class='hsuforum-tools' aria-label='$options'>
            <div class="hsuforum-postflagging">$p->postflags</div>
            $tools
        </div>
        $postreplies
    </div>
</div>
HTML;
    }

 public function discussion_template($d, $forumtype) {
        $replies = '';
        $pinned = '';
        if(!empty($d->replies)) {
            $xreplies = hsuforum_xreplies($d->replies);
            $replies = "<span class='hsuforum-replycount'>$xreplies</span>";
        }
        if ($d->pinned != 0) {
            $pinned = '<span class="pinned"><img src="pix/i/pinned.png" alt="pinned" /></span>';
        }
        if (!empty($d->userurl)) {
            $byuser = html_writer::link($d->userurl, $d->fullname);
        } else {
            $byuser = html_writer::tag('span', $d->fullname);
        }
        $unread = '';
        $unreadclass = '';
        $attrs = '';
        if ($d->unread != '-') {
            $new  = get_string('unread', 'hsuforum');
            $unread  = "<a class='hsuforum-unreadcount disable-router' href='$d->viewurl#unread'>$new</a>";
            $attrs   = 'data-isunread="true"';
            $unreadclass = 'hsuforum-post-unread';
        }

        $author = s(strip_tags($d->fullname));
        $group = '';
        if (!empty($d->group)) {
            $group = '<br>'.$d->group;
        }

        $latestpost = '';
        if (!empty($d->modified) && !empty($d->replies)) {
            $latestpost = '<small class="hsuforum-thread-replies-meta">'.get_string('lastposttimeago', 'hsuforum', hsuforum_relative_time($d->rawmodified)).'</small>';
        }

        $participants = '<div class="hsuforum-thread-participants">'.implode(' ',$d->replyavatars).'</div>';

        $datecreated = hsuforum_relative_time($d->rawcreated, array('class' => 'hsuforum-thread-pubdate'));

        $threadtitle = $d->subject;
        if (!$d->fullthread) {
            $threadtitle = "<a class='disable-router' href='$d->viewurl'>$d->subject</a>";
        }
        $options = get_string('options', 'hsuforum');

        //Add users country flag and timezone to the output.
        $threadmeta  =
            '<div class="hsuforum-thread-meta">'
                .$replies
                .$unread
                .$participants
                .$latestpost
                .$pinned
                .'<div class="hsuforum-thread-flags">'."{$d->subscribe} $d->postflags</div>"
            .'</div>';

        if ($d->fullthread) {
            $tools = '<div role="region" class="hsuforum-tools hsuforum-thread-tools" aria-label="'.$options.'">'.$d->tools.'</div>';
            $blogmeta = '';
            $blogreplies = '';
            if (theme_legend_accessibility_enabled()) {
                $tools = '<div role="region" class="hsuforum-tools hsuforum-thread-tools" aria-label="'.$options.'"><ul class="hsuforum-thread-tools_list">'.$d->tools.'</ul></div>';
            }
        } else {
            $blogreplies = hsuforum_xreplies($d->replies);
            $tools = "<a class='disable-router hsuforum-replycount-link' href='$d->viewurl'>$blogreplies</a>";
            $blogmeta = $threadmeta;
        }

        $revealed = "";
        if ($d->revealed) {
            $nonanonymous = get_string('nonanonymous', 'mod_hsuforum');
            $revealed = '<span class="label label-danger">'.$nonanonymous.'</span>';
        }

        // Flags and timezone
        $flagandtimezone = '';
        global $PAGE, $DB;
        if ($PAGE->theme->settings->display_flag_and_time) {
            $userid = $d->userurl->params()['id'];

            $data = $DB->get_record_sql('SELECT timezone, country FROM {user} WHERE id = ?',
                           array($userid));

            $flagandtimezone = theme_legend_user_flag_and_timezone($data);
        }

        $threadheader = <<<HTML
        <div class="hsuforum-thread-header">
            <div class="hsuforum-thread-title">
                <h4 id='thread-title-{$d->id}' role="heading" aria-level="4">
                    $threadtitle
                </h4>
                <small>$datecreated</small>
            </div>
            $threadmeta
        </div>
HTML;

        return <<<HTML
<article id="p{$d->postid}" class="hsuforum-thread hsuforum-post-target clearfix" role="article"
    data-discussionid="$d->id" data-postid="$d->postid" data-author="$author" data-isdiscussion="true" $attrs>
    <header id="h{$d->postid}" class="clearfix $unreadclass">
        <div class="clearfix">
            <div class="hsuforum-thread-author clearfix">
                <a href="$d->userurl">
                    <img class="userpicture img-circle" src="{$d->imagesrc}" alt="" />
                </a>
                $flagandtimezone
                <p class="hsuforum-thread-byline">
                    $byuser $group $revealed
                </p>
            </div>
            <div class="hsuforum-thread-header-right clearfix">
                $threadheader
                <div class="hsuforum-thread-content" tabindex="0">
                    $d->message
                </div>
                $tools
            </div>
        </div>
    </header>
    <div id="hsuforum-thread-{$d->id}" class="hsuforum-thread-body">
        <!-- specific to blog style -->
        $blogmeta
        $d->posts
        $d->replyform
    </div>
</article>
HTML;
    }
}
