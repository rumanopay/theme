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
 * A two column layout for the boost theme.
 *
 * @package   theme_boost
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');

global $PAGE, $DB;

function get_course_navigation_card($course, $urlarray, $logo, $sectioninfo, $cardid, $showcourseblocklogo) {

  if (isset($sectioninfo['name'])) {

        global $OUTPUT;

        $returnhtml = html_writer::start_tag('div', array('class' => 'auto-width offset-lg-2 col-lg-8 col-xs-12'));
        $returnhtml .= html_writer::start_tag('div', array('class' => 'card course_block_'.$cardid));
        $returnhtml .= html_writer::start_tag('div', array('class' => 'card-block'));
        $returnhtml .= html_writer::start_tag('div', array('class' => 'row'));
        $returnhtml .= html_writer::start_tag('div', array('class' => 'col-lg-12 col-xs-12 clear-fix'));
        $returnhtml .= html_writer::start_tag('div', array('class' => 'campus_logo_div'));

        if ($showcourseblocklogo) {
            $returnhtml .= html_writer::start_tag('a', array('class' => 'logo'));
            $returnhtml .= html_writer::end_tag('a');
        }

        $returnhtml .= html_writer::start_tag('div', array('class' => 'titlearea'));
        $returnhtml .= html_writer::tag('a', $course->fullname, array('href' => $urlarray['course_overview_url'], 'class' => 'main-heading', 'id' => 'main-heading_'.$cardid));
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::start_tag('div', array('class' => 'card-title'));
        $returnhtml .= html_writer::start_tag('h6', array('class' => 'current_module'));
        $returnhtml .= 'CURRENT MODULE';
        $returnhtml .= html_writer::end_tag('h6');
        $returnhtml .= html_writer::start_tag('h6', array('class' => 'current_module_name'));
        $returnhtml .= $sectioninfo['name'];
        $returnhtml .= html_writer::end_tag('h6');
        $returnhtml .= html_writer::start_tag('h6', array('class' => 'module_release_date'));
        $returnhtml .=  'Released on '.date("F j, Y, g:i a", $sectioninfo['date']);
        $returnhtml .= html_writer::end_tag('h6');
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::start_tag('div', array('class' => 'course_block_btn_div'));
        $returnhtml .= html_writer::tag('a', 'Go to current module', array('class' => 'btn btn-primary course_block_spaced course_block_button_'.$cardid, 'href' => $urlarray['currentmodule']));
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::tag('hr', '', array('class' => 'hr_margin'));
        $returnhtml .= html_writer::start_tag('div', array('class' => 'card-footer'));
        $returnhtml .= html_writer::start_tag('div', array('class' => 'container-fluid course_block_footer'));
        $returnhtml .= html_writer::start_tag('div', array('class' => 'row row-divided'));
        $returnhtml .= html_writer::start_tag('div', array('class' => 'col-lg-2 col-md-2 col-xs-6 column-one'));
        $returnhtml .= html_writer::start_tag('a', array('href' => $urlarray['gradesurl'], 'id' => 'course_gradeurl_'.$cardid, 'class' => 'border-right'));
        $returnhtml .= html_writer::start_tag('i', array('class' => 'fa fa-fw fa-trophy'));
        $returnhtml .= html_writer::end_tag('i');
        $returnhtml .= html_writer::start_tag('div', array('class' => 'course-block-link'));
        $returnhtml .= get_string('mygrades', 'theme_legend');
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_tag('a');
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::tag('div', '', array('class' => 'vertical-divider'));
        $returnhtml .= html_writer::start_tag('div', array('class' => 'col-lg-2 col-md-2 col-xs-6 column-two'));
        $returnhtml .= html_writer::start_tag('a', array('href' => $urlarray['calurl'], 'id' => 'course_cal_url_'.$cardid, 'class' => 'no-border-right'));
        $returnhtml .= html_writer::start_tag('i', array('class' => 'fa fa-fw fa-calendar'));
        $returnhtml .= html_writer::end_tag('i');
        $returnhtml .= html_writer::start_tag('div', array('class' => 'course-block-link'));
        $returnhtml .= get_string('coursecalendar', 'theme_legend');
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_tag('a');
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_div();
        $returnhtml .= html_writer::end_div();

        return $returnhtml;
    } else {
        return '';
  }
}

/**
 * @return bool
 * @throws Exception
 * @throws dml_exception
 */
function is_homepage() {
    global $PAGE, $ME;

    $result = false;

    $url = null;
    if ($PAGE->has_set_url()) {
        $url = $PAGE->url;
    } else if ($ME !== null) {
        $url = new moodle_url(str_ireplace('/index.php', '/', $ME));
    }

    if ($url !== null) {
        $result = $url->compare(context_system::instance()->get_url(), URL_MATCH_BASE);
    }
    return $result;
}

$navdraweropen = false;

$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}

$bodyattributes = str_replace('safari ', '', $OUTPUT->body_attributes($extraclasses));

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();

$courselist = '';

if ($PAGE->bodyid == 'page-site-index') {

    if ($studentcoursearry = enrol_get_users_courses($USER->id, true, null, 'visible DESC,sortorder ASC')) {

        $i = 0;

        foreach ($studentcoursearry as $course) {

            $currentsection = theme_legend_get_current_section($course);

            $urlarray['calurl'] = new moodle_url('/calendar/view.php', array('view' => 'month', 'course' => $course->id));
            $urlarray['gradesurl'] = new moodle_url('/course/user.php', array('mode' => 'grade', 'id' => $course->id, 'user' => $USER->id));
            $urlarray['course_overview_url'] = new moodle_url('/course/view.php', array('id' => $course->id));
            $urlarray['currentmodule'] = new moodle_url('/course/view.php', array('id' => $course->id, 'section' => $currentsection['id']));

            $courselist .= get_course_navigation_card($course, $urlarray, $OUTPUT->get_logo_url(), $currentsection, $i, theme_legend_show_course_block_logo());

            $i++;
        }
    }
}

$footerblock = $OUTPUT->blocks('footer');

if(strpos($PAGE->requires->get_end_code(), 'tool_usertours/usertours')) {

    $launchtour = '<p><a href="#" data-action="tool_usertours/resetpagetour" id="reset_user_tour_button">Launch your Online Campus tour</a></p>';
    $accessibilityblock = '<p class="acc_block_title_has_tour_button">Accessibility controls</p>'.$footerblock.'</div>';

    if (theme_legend_accessibility_enabled()) {
        $launchtour = '<p><button data-action="tool_usertours/resetpagetour" id="reset_user_tour_button">Launch your Online Campus tour</button></p>';
        $accessibilityblock = $footerblock.'</div>';
    }
} else {

    $launchtour = '';
    $accessibilityblock = '<p class="acc_block_title">Accessibility controls</p>'.$footerblock.'</div>';

    if (theme_legend_accessibility_enabled()) {
        $accessibilityblock = $footerblock.'</div>';
    }
}

$footerhtml = '<div class="col-lg-8">'.theme_legend_get_custom_footer().theme_legend_get_copyright().'</div><div class="col-lg-4 content-right">'.$launchtour.$accessibilityblock;

if (theme_legend_accessibility_enabled()) {
    $footerhtml = '<div class="col-lg-8">'.theme_legend_get_custom_footer().theme_legend_get_copyright().'</div><div class="col-lg-4 content-right">'.$launchtour;
}

$favicon = theme_legend_get_favion();
$brandicon = theme_legend_get_brandicon();
$unreadnotificationcount = theme_legend_get_unread_notification_count();

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'course_navigation_block' => $courselist,
    'custom_footer_html' => $footerhtml,
    'favicon' => $favicon,
    'brandicon' => $brandicon,
    'unread_notification_count' => $unreadnotificationcount
];

echo $OUTPUT->render_from_template('theme_legend/columns2', $templatecontext);
