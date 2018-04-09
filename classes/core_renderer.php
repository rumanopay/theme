<?php
class theme_legend_core_renderer extends theme_boost\output\core_renderer {
    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        global $PAGE, $OUTPUT, $CFG, $SITE;
        
        $html = '';

        if ($PAGE->bodyid == 'page-site-index') {
            $logoclass = 'col-lg-12 col-xs-12';
            $haslogo = true;
            $html = html_writer::start_tag('header', array('id' => 'page-header', 'class' => 'clearfix'));
            $html .= html_writer::start_div('container-fluid');
            $html .= html_writer::start_div('row-fluid');
            $html .= html_writer::div($this->context_header_settings_menu(), 'col-xs-2 col-lg-2 pull-xs-right context-header-settings-menu');
            if (theme_legend_accessibility_enabled()) {
             $html .= "<div class='".$logoclass."'>
                            <a href='".preg_replace("(https?:)", "", $CFG->wwwroot)."' title='".$SITE->shortname."'><img class='logo' src='".$PAGE->theme->setting_file_url('logo', 'logo')."' focusable='false' alt='' /></a>
                                <div class='titlearea'>".theme_legend_get_header_title()."</div>
                             </a>
                     </div>";
            } else {
                $html .= "<div class='".$logoclass."'>
                                <a tabindex='-1' class='logo' href='".preg_replace('(https?:)', '', $CFG->wwwroot)."' title=''>
                                    <div class=\"titlearea\">".theme_legend_get_header_title()."</div>
                                </a>
                        </div>";
            }

            $html .= html_writer::end_div();
            $html .= html_writer::end_div();
            $html .= html_writer::end_tag('header');
        }

        $html .= html_writer::start_div('page-navbar');
        $html .= html_writer::start_div('card');
        $html .= html_writer::start_div('card-block');

        if ($PAGE->bodyid != 'page-site-index' && $this->context_header_settings_menu() != '') {
            $html .= html_writer::div($this->context_header_settings_menu(), 'col-xs-2 col-lg-2 pull-xs-right context-header-settings-menu');
        }

        $pageheadingbutton = $this->page_heading_button();

        if (empty($PAGE->layout_options['nonavbar'])) {
            $html .= html_writer::start_div('clearfix col-lg-12', array('id' => 'page-navbar'));
            $html .= html_writer::tag('div', theme_legend_get_custom_navbar(), array('class' => 'col-lg-12 breadcrumb-nav'));
            $html .= html_writer::end_div();
        } else if ($pageheadingbutton) {
            $html .= html_writer::div($pageheadingbutton, 'breadcrumb-button nonavbar col-lg-12');
        } else {
            if ($PAGE->pagetype == 'calendar-view') {
                $navbar = '<nav role="navigation"><ol class="breadcrumb"><li class="breadcrumb-item my_courses">Calendar</li></ol></nav>';
            }
            else if ($PAGE->pagetype == 'grade-report-overview-index') {
                $navbar = '<nav role="navigation"><ol class="breadcrumb"><li class="breadcrumb-item my_courses">'.get_string('gradesbreadcrumb','theme_legend').'</li></ol></nav>';
            }
            else if ($PAGE->pagetype == 'mod-assign-grader') {
                $navbar = '<nav role="navigation"><ol class="breadcrumb"><li class="breadcrumb-item my_courses">Grading page</li></ol></nav>';
            }
            else {
                $navbar = '<nav role="navigation"><ol class="breadcrumb"><li class="breadcrumb-item my_courses">'.get_string('mycoursesbreadcrumb','theme_legend').'</li></ol></nav>';
            }
            $html .= html_writer::start_div('clearfix col-lg-12', array('id' => 'page-navbar'));
            $html .= html_writer::tag('div', $navbar, array('class' => 'col-xs-12 col-lg-12 breadcrumb-nav'));
            $html .= html_writer::end_div();
        }

        $html .= html_writer::end_div('page-navbar');
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
        return $html;
    }
  /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');
        if (is_null($user)) {
            $user = $USER;
        }
        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }
        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }
        $returnstr = "";
        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }
        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = get_string('loggedinnot', 'moodle');
            if (!$loginpage) {
                $returnstr .= " (<a href=\"$loginurl\">" . get_string('login') . '</a>)';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }
        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }
        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, $this->page);

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = $user->firstname;
        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $user->firstname,
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }
        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }
        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }
        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }
        $returnstr .= html_writer::span(
            html_writer::span($avatarcontents, $avatarclasses).html_writer::span($usertextcontents, 'usertext'),
            'userbutton'
        );
        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;
        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $key => $value) {
                if ($value->title == 'Dashboard') continue;
                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;
                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;
                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }
                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }
                $idx++;
                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount - 1) {
                    $am->add($divider);
                }
            }
        }
        return html_writer::div($this->render($am),$usermenuclasses);
    }
    /**
     * Helper function to generate sections and activities linked to
     * that section.
     */
    protected function generate_sections_and_activities() {
        global $CFG, $DB, $PAGE;
        require_once($CFG->dirroot.'/course/lib.php');
        if ($this->page->cm) {
            $course = $this->page->course;
            $modinfo = get_fast_modinfo($course);
            $activity = $DB->get_record_sql('SELECT section FROM {course_sections} WHERE id = ?  limit 1 ', array($PAGE->cm->section));
            $cm = $modinfo->get_sections()[$activity->section];
            $cm = array_unique($cm);
            $activityarray = array ();
            $completioninfo = new completion_info($course);
            foreach ($cm as $c) {
                $coursemod = $modinfo->get_cm($c);
                $activity = new stdClass;
                $activity->complete = false;
                $activity->hascomplete = false;
                $activity->id = $coursemod->id;
                $activity->course = $course->id;
                $activity->name = $coursemod->name;
                $activity->icon = $coursemod->icon;
                $activity->iconcomponent = $coursemod->iconcomponent;
                $activity->hidden = (!$coursemod->visible);
                $activity->modname = $coursemod->modname;
                $activity->nodetype = navigation_node::NODETYPE_LEAF;
                $activity->onclick = $coursemod->onclick;
                $activity->type = $coursemod->modname;
                if($coursemod->id == $this->page->cm->id){
                    $activity->current = true;
                } else{
                    $activity->current = false;
                }
                $url = $coursemod->url;
                if (!$url) {
                    $activity->url = null;
                    $activity->display = false;
                } else {
                    $activity->url = $url->out();
                    $activity->display = $coursemod->uservisible ? true : false;
                }
                if ($completioninfo->is_enabled($coursemod) != COMPLETION_TRACKING_NONE) {
                    $completiondata = $completioninfo->get_data($coursemod, true);
                    if ($completiondata->completionstate == COMPLETION_COMPLETE || $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
                        $activity->complete = true;
                    }
                    $activity->hascomplete = true;
                }
                if($activity->name != 'Announcements'){
                    if ($activity->type == 'label') {
                        if (strrpos($activity->name, 'Unit') !== false) {
                            array_push($activityarray, $activity);
                        }
                    }
                    else if ($activity->display) {
                        array_push($activityarray, $activity);
                    }
                }
            }
            return $activityarray;
        } else{
            return false;
        }
    }
    /*
     * This renders the navbar.
     * Uses bootstrap compatible html.
     * Ovewrites the navbar rendered by core
     * render.
     */
    public function navbar() {
        global $PAGE, $CFG;
        $optionsarray = array();
        $attributesarray = array();
        $select = '<select class="form-control" onchange="window.location = this.value">';
        if(isset($PAGE->cm->section)){
            $selected = 0;
            foreach ($this->generate_sections_and_activities() as $activityitem) {
                if ($activityitem->current) {
                    if ($activityitem->hascomplete) {
                        if ($activityitem->complete) {
                            $select .= '<option selected data-image="'.$CFG->wwwroot.'/theme/image.php/legend/core/1491566246/i/completion-auto-y" value="'.$activityitem->url.'">'.$activityitem->name.'</option>';
                        } else {
                            $select .= '<option selected  data-image="'.$CFG->wwwroot.'/theme/image.php/legend/core/1491566246/i/completion-auto-n" value="'.$activityitem->url.'">'.$activityitem->name.'</option>';
                        }
                    } else {
                        $select .= '<option selected value="'.$activityitem->url.'">'.$activityitem->name.'</option>';
                    }
                } else {
                    if ($activityitem->hascomplete) {
                        if ($activityitem->complete) {
                            $select .= '<option data-image="'.$CFG->wwwroot.'/theme/image.php/legend/core/1491566246/i/completion-auto-y" value="'.$activityitem->url.'">'.$activityitem->name.'</option>';
                        } else {
                            $select .= '<option data-image="'.$CFG->wwwroot.'/theme/image.php/legend/core/1491566246/i/completion-auto-n" value="'.$activityitem->url.'">'.$activityitem->name.'</option>';
                        }
                    } else {
                        if ($activityitem->type == 'label') {
                            $select .= '<option disabled="disabled" value="'.$activityitem->url.'">'.$activityitem->name.'</option>';
                        }
                        else {
                            $select .= '<option value="'.$activityitem->url.'">'.$activityitem->name.'</option>';
                        }
                    }
                }
            }

            $select .= '</select>';

            $templatecontext['activity_select'] = html_writer::start_tag('div', array('class' => 'row'));
            $templatecontext['activity_select'] .= html_writer::start_tag('div', array('class' => 'col-lg-12 col-xs-12 select-padding caretbefore'));
            $templatecontext['activity_select'] .= $select;
            $templatecontext['activity_select'] .= html_writer::end_div();
            $templatecontext['activity_select'] .= html_writer::end_div();
        }
        $templatecontext['navbar'] = $this->page->navbar;
        return $this->render_from_template('theme_legend/navbar_custom',  $templatecontext);
    }

    /*
     * This renders the user profile picture.
     * Added the country flag and user timezone.
     * CSS to display none on view your team
     */
    protected function render_user_picture(user_picture $userpicture) {
        global $CFG, $DB;

        $user = $userpicture->user;

        if ($userpicture->alttext) {
            if (!empty($user->imagealt)) {
                $alt = $user->imagealt;
            } else {
                $alt = get_string('pictureof', '', fullname($user));
            }
        } else {
            $alt = '';
        }

        if (empty($userpicture->size)) {
            $size = 35;
        } else if ($userpicture->size === true or $userpicture->size == 1) {
            $size = 100;
        } else {
            $size = $userpicture->size;
        }

        $class = $userpicture->class;

        if ($user->picture == 0) {
            $class .= ' defaultuserpic';
        }

        $src = $userpicture->get_url($this->page, $this);

        $attributes = array('src'=>$src, 'alt'=>$alt, 'title'=>$alt, 'class'=>$class, 'width'=>$size, 'height'=>$size);
        if (!$userpicture->visibletoscreenreaders) {
            $attributes['role'] = 'presentation';
        }

        // get the image html output fisrt
        $output .= html_writer::empty_tag('img', $attributes);

        // then wrap it in link if needed
        if (!$userpicture->link) {
            return $output;
        }

        if (empty($userpicture->courseid)) {
            $courseid = $this->page->course->id;
        } else {
            $courseid = $userpicture->courseid;
        }

        if ($courseid == SITEID) {
            $url = new moodle_url('/user/profile.php', array('id' => $user->id));
        } else {
            $url = new moodle_url('/user/view.php', array('id' => $user->id, 'course' => $courseid));
        }

        $attributes = array('href'=>$url);
        if (!$userpicture->visibletoscreenreaders) {
            $attributes['tabindex'] = '-1';
            $attributes['aria-hidden'] = 'true';
        }

        if ($userpicture->popup) {
            $id = html_writer::random_id('userpicture');
            $attributes['id'] = $id;
            $this->add_action_handler(new popup_action('click', $url), $id);
        }

        // Display country flag and country time under the user avatar
        static $theme;

        if (empty($theme)) {
            $theme = theme_config::load('legend');
        }

        if ($theme->settings->display_flag_and_time) {
            $data = $DB->get_record_sql('SELECT timezone, country FROM {user} WHERE id = ?',
                           array($user->id));

            $output .= theme_legend_user_flag_and_timezone($data);
        }

        $output = '<table class="custom_userpicture_flag"><td>'.$output.'</td>';

        if ($userpicture->includefullname) {
            $output .= '<td>'.fullname($userpicture->user).'</td>';
        }

        $output .= '</table>';

        return html_writer::tag('a', $output, $attributes);
    }
}
