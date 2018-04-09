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

defined('MOODLE_INTERNAL') || die();

/**
 * A login page layout for the boost theme.
 *
 * @package   theme_boost
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$bodyattributes = $OUTPUT->body_attributes();

$videourl = $PAGE->theme->setting_file_url('login_background_video', 'login_background_video');

if($videourl) {
    $videourlhtml = '<video id="login-bg-video" autoplay loop muted poster="'.$videourl.'">
              <source src="'.$videourl.'" type="video/mp4">
            </video>';
    $pageclass = 'bg-video';
}
else {
    $videourlhtml = '';
    $pageclass = 'bg-img';
}

$haslogo = $PAGE->theme->setting_file_url('login_logo', 'login_logo');

$sitelogohtml = '<section id="region-logo" class="span12">';
if (!$haslogo) {
    $sitelogohtml .='<a class="textlogo" href="'.preg_replace("(https?:)", "", $CFG->wwwroot).'">
                        <i id="headerlogo" class="fa fa-home"></i></a>';
} else {
    if (theme_legend_accessibility_enabled()) {
        $sitelogohtml .= '<a href="'.preg_replace("(https?:)", "", $CFG->wwwroot).'" title="'.$SITE->shortname.'"><img class="logo" src="'.$PAGE->theme->setting_file_url('login_logo', 'login_logo').'" focusable="false" alt="" /></a>';
    } else {
        $sitelogohtml .= '<a class="logo" href="'.preg_replace("(https?:)", "", $CFG->wwwroot).'" title=""></a>';
    }
}
$sitelogohtml .= '</section>';

$loginmessagetophtml = '<section id="region-intro" class="span12"><div class="login-message"><div class="content">'.$PAGE->theme->settings->login_message_top.'</div></div></section>';

$loginmessagebottomhtml = '<section id="region-intro" class="span12"><div class="login-message"><div class="content">'.$PAGE->theme->settings->login_message_bottom.'</div></div></section>';

if (theme_legend_accessibility_enabled()) {
    $loginmessagebottomhtml = '<section id="region-bottom" class="span12"><div class="login-message"><div class="content">'.$PAGE->theme->settings->login_message_bottom.'</div></div></section>';
}

$footerhtml = '<div class="col-lg-6">'.theme_legend_get_custom_footer().theme_legend_get_copyright().'</div><div class="col-lg-6">'.(isset($OUTPUT->login_info) ? $OUTPUT->login_info : '').'</div>';

$favicon = theme_legend_get_favion();

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'videourlhtml' => $videourlhtml,
    'footerhtml' => $footerhtml,
    'pageclass' => $pageclass,
    'sitelogohtml' => $sitelogohtml,
    'loginmessagetophtml' => $loginmessagetophtml,
    'loginmessagebottomhtml' => $loginmessagebottomhtml,
    'favicon' => $favicon
];

echo $OUTPUT->render_from_template('theme_legend/login', $templatecontext);

