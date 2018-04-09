<?php

// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// We will add callbacks here as we add features to our theme.
function theme_legend_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        if ($filearea === 'logo') {
            return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
        } else if ($filearea === 'style') {
            theme_legend_serve_css($args[1]);
        } else if ($filearea === 'headerbackground') {
            return $theme->setting_file_serve('headerbackground', $args, $forcedownload, $options);
        } else if ($filearea === 'pagebackground') {
            return $theme->setting_file_serve('pagebackground', $args, $forcedownload, $options);
        } else if ($filearea === 'favicon') {
            return $theme->setting_file_serve('favicon', $args, $forcedownload, $options);
        } else if (preg_match("/^fontfile(eot|otf|svg|ttf|woff|woff2)(heading|body)$/", $filearea)) { // http://www.regexr.com/.
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else if (preg_match("/^(marketing|slide)[1-9][0-9]*image$/", $filearea)) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else if ($filearea === 'iphoneicon') {
            return $theme->setting_file_serve('iphoneicon', $args, $forcedownload, $options);
        } else if ($filearea === 'iphoneretinaicon') {
            return $theme->setting_file_serve('iphoneretinaicon', $args, $forcedownload, $options);
        } else if ($filearea === 'ipadicon') {
            return $theme->setting_file_serve('ipadicon', $args, $forcedownload, $options);
        } else if ($filearea === 'ipadretinaicon') {
            return $theme->setting_file_serve('ipadretinaicon', $args, $forcedownload, $options);
        } else if ($filearea === 'login_logo') {
            return $theme->setting_file_serve('login_logo', $args, $forcedownload, $options);
        } else if ($filearea === 'login_background_video') {
            return $theme->setting_file_serve('login_background_video', $args, $forcedownload, $options);
        } else if ($filearea === 'login_background_image') {
            return $theme->setting_file_serve('login_background_image', $args, $forcedownload, $options);
        } else if ($filearea === 'login_background_image_mobile') {
            return $theme->setting_file_serve('login_background_image_mobile', $args, $forcedownload, $options);
        } else if ($filearea === 'brandicon') {
            return $theme->setting_file_serve('brandicon', $args, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}

function theme_legend_serve_css($filename) {
    global $CFG;

    if (file_exists("{$CFG->dirroot}/theme/legend/style/")) {
        $thestylepath = $CFG->dirroot . '/theme/legend/style/';
    } else if (!empty($CFG->themedir) && file_exists("{$CFG->themedir}/legend/style/")) {
        $thestylepath = $CFG->themedir . '/legend/style/';
     } else {
        header('HTTP/1.0 404 Not Found');
        die('Essential style folder not found, check $CFG->themedir is correct.');
    }
    $thesheet = $thestylepath . $filename;

    /* http://css-tricks.com/snippets/php/intelligent-php-cache-control/ - rather than /lib/csslib.php as it is a static file who's
      contents should only change if it is rebuilt.  But! There should be no difference with TDM on so will see for the moment if
      that decision is a factor. */

    $etagfile = md5_file($thesheet);
    // File.
    $lastmodified = filemtime($thesheet);
    // Header.
    $ifmodifiedsince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
    $etagheader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

    if ((($ifmodifiedsince) && (strtotime($ifmodifiedsince) == $lastmodified)) || $etagheader == $etagfile) {
        theme_legend_send_unmodified($lastmodified, $etagfile);
    }
    theme_legend_send_cached_css($thestylepath, $filename, $lastmodified, $etagfile);
}

function theme_legend_send_unmodified($lastmodified, $etag) {
    $lifetime = 60 * 60 * 24 * 60;
    header('HTTP/1.1 304 Not Modified');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Content-Type: text/css; charset=utf-8');
    header('Etag: "' . $etag . '"');
    if ($lastmodified) {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    }
    die;
}

function theme_legend_send_cached_css($path, $filename, $lastmodified, $etag) {
    global $CFG;
    require_once($CFG->dirroot . '/lib/configonlylib.php'); // For min_enable_zlib_compression().
    // 60 days only - the revision may get incremented quite often.
    $lifetime = 60 * 60 * 24 * 60;

    header('Etag: "' . $etag . '"');
    header('Content-Disposition: inline; filename="'.$filename.'"');
    if ($lastmodified) {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    }
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Pragma: ');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Accept-Ranges: none');
    header('Content-Type: text/css; charset=utf-8');
    if (!min_enable_zlib_compression()) {
        header('Content-Length: ' . filesize($path . $filename));
    }

    readfile($path . $filename);
    die;
}

function theme_legend_process_css($css, $theme) {
    // Set the theme width.
    $pagewidth = \theme_legend\toolbox::get_setting('pagewidth');
    $css = \theme_legend\toolbox::set_pagewidth($css, $pagewidth);

    // Set the theme font.
    $css = \theme_legend\toolbox::set_font($css, 'heading', \theme_legend\toolbox::get_setting('fontnameheading'));
    $css = \theme_legend\toolbox::set_font($css, 'body', \theme_legend\toolbox::get_setting('fontnamebody'));

    // Set the theme colour.
    $themecolor = \theme_legend\toolbox::get_setting('themeheaderbordercolour');
    $css = \theme_legend\toolbox::set_color($css, $themecolor, '[[setting:themeheaderbordercolour]]', '#943b21');

    // Set the theme colour.
    $themecolor = \theme_legend\toolbox::get_setting('themecolor');
    $css = \theme_legend\toolbox::set_color($css, $themecolor, '[[setting:themecolor]]', '#30add1');

    // Set the theme text colour.
    $themetextcolor = \theme_legend\toolbox::get_setting('themetextcolor');
    $css = \theme_legend\toolbox::set_color($css, $themetextcolor, '[[setting:themetextcolor]]', '#047797');

    // Set the theme url colour.
    $themeurlcolor = \theme_legend\toolbox::get_setting('themeurlcolor');
    $css = \theme_legend\toolbox::set_color($css, $themeurlcolor, '[[setting:themeurlcolor]]', '#3a3a3a');

    // Set the theme p colour.
    $themepcolor = \theme_legend\toolbox::get_setting('themepcolor');
    $css = \theme_legend\toolbox::set_color($css, $themepcolor, '[[setting:themepcolor]]', '#3a3a3a');

    // Set the theme label colour.
    $themelabelcolor = \theme_legend\toolbox::get_setting('themelabelcolor');
    $css = \theme_legend\toolbox::set_color($css, $themelabelcolor, '[[setting:themelabelcolor]]', '#3a3a3a');

    // Set the theme span colour.
    $themespancolor = \theme_legend\toolbox::get_setting('themespancolor');
    $css = \theme_legend\toolbox::set_color($css, $themespancolor, '[[setting:themespancolor]]', '#3a3a3a');

    // Set the theme hover colour.
    $themehovercolor = \theme_legend\toolbox::get_setting('themehovercolor');
    $css = \theme_legend\toolbox::set_color($css, $themehovercolor, '[[setting:themehovercolor]]', '#F32100');

    // Set the theme header text colour.
    $themetextcolor = \theme_legend\toolbox::get_setting('headertextcolor');
    $css = \theme_legend\toolbox::set_color($css, $themetextcolor, '[[setting:headertextcolor]]', '#217a94');

    // Set the theme icon colour.
    $themeiconcolor = \theme_legend\toolbox::get_setting('themeiconcolor');
    $css = \theme_legend\toolbox::set_color($css, $themeiconcolor, '[[setting:themeiconcolor]]', '#30add1');

    // Set the theme navigation colour.
    $themenavcolor = \theme_legend\toolbox::get_setting('themenavcolor');
    $css = \theme_legend\toolbox::set_color($css, $themenavcolor, '[[setting:themenavcolor]]', '#ffffff');

    // Set the footer colour.
    $footercolor = \theme_legend\toolbox::hex2rgba(\theme_legend\toolbox::get_setting('footercolor'), '0.95');
    $css = \theme_legend\toolbox::set_color($css, $footercolor, '[[setting:footercolor]]', '#30add1');

    // Set the footer text colour.
    $footertextcolor = \theme_legend\toolbox::get_setting('footertextcolor');
    $css = \theme_legend\toolbox::set_color($css, $footertextcolor, '[[setting:footertextcolor]]', '#ffffff');

    // Set the footer block background colour.
    $footerheadingcolor = \theme_legend\toolbox::get_setting('footerblockbackgroundcolour');
    $css = \theme_legend\toolbox::set_color($css, $footerheadingcolor, '[[setting:footerblockbackgroundcolour]]', '#cccccc');

    // Set the footer block heading colour.
    $footerheadingcolor = \theme_legend\toolbox::get_setting('footerheadingcolor');
    $css = \theme_legend\toolbox::set_color($css, $footerheadingcolor, '[[setting:footerheadingcolor]]', '#cccccc');

    // Set the footer text colour.
    $footertextcolor = \theme_legend\toolbox::get_setting('footerblocktextcolour');
    $css = \theme_legend\toolbox::set_color($css, $footertextcolor, '[[setting:footerblocktextcolour]]', '#000000');

    // Set the footer block URL colour.
    $footerurlcolor = \theme_legend\toolbox::get_setting('footerblockurlcolour');
    $css = \theme_legend\toolbox::set_color($css, $footerurlcolor, '[[setting:footerblockurlcolour]]', '#000000');

    // Set the footer block hover colour.
    $footerhovercolor = \theme_legend\toolbox::get_setting('footerblockhovercolour');
    $css = \theme_legend\toolbox::set_color($css, $footerhovercolor, '[[setting:footerblockhovercolour]]', '#555555');

    // Set the footer separator colour.
    $footersepcolor = \theme_legend\toolbox::get_setting('footersepcolor');
    $css = \theme_legend\toolbox::set_color($css, $footersepcolor, '[[setting:footersepcolor]]', '#313131');

    // Set the footer URL colour.
    $footerurlcolor = \theme_legend\toolbox::get_setting('footerurlcolor');
    $css = \theme_legend\toolbox::set_color($css, $footerurlcolor, '[[setting:footerurlcolor]]', '#cccccc');

    // Set the footer hover colour.
    $footerhovercolor = \theme_legend\toolbox::get_setting('footerhovercolor');
    $css = \theme_legend\toolbox::set_color($css, $footerhovercolor, '[[setting:footerhovercolor]]', '#bbbbbb');

    // Set the slide header colour.
    $slideshowcolor = \theme_legend\toolbox::get_setting('slideshowcolor');
    $css = \theme_legend\toolbox::set_color($css, $slideshowcolor, '[[setting:slideshowcolor]]', '#30add1');

    // Set the slide header colour.
    $slideheadercolor = \theme_legend\toolbox::get_setting('slideheadercolor');
    $css = \theme_legend\toolbox::set_color($css, $slideheadercolor, '[[setting:slideheadercolor]]', '#30add1');

    // Set the slide caption text colour.
    $slidecaptiontextcolor = \theme_legend\toolbox::get_setting('slidecaptiontextcolor');
    $css = \theme_legend\toolbox::set_color($css, $slidecaptiontextcolor, '[[setting:slidecaptiontextcolor]]', '#ffffff');

    // Set the slide caption background colour.
    $slidecaptionbackgroundcolor = \theme_legend\toolbox::get_setting('slidecaptionbackgroundcolor');
    $css = \theme_legend\toolbox::set_color($css, $slidecaptionbackgroundcolor, '[[setting:slidecaptionbackgroundcolor]]', '#30add1');

    // Set the slide button colour.
    $slidebuttoncolor = \theme_legend\toolbox::get_setting('slidebuttoncolor');
    $css = \theme_legend\toolbox::set_color($css, $slidebuttoncolor, '[[setting:slidebuttoncolor]]', '#30add1');

    // Set the slide button hover colour.
    $slidebuttonhcolor = \theme_legend\toolbox::get_setting('slidebuttonhovercolor');
    $css = \theme_legend\toolbox::set_color($css, $slidebuttonhcolor, '[[setting:slidebuttonhovercolor]]', '#217a94');

    // Set custom progress bar background colour.
    $custom_file_upload_progress_bar_background_colour = \theme_legend\toolbox::get_setting('customfileuploadprogressbarbackgroundcolour');
    $css = \theme_legend\toolbox::set_color($css, $custom_file_upload_progress_bar_background_colour, '[[setting:customfileuploadprogressbarbackgroundcolour]]', '#d9edf7');

    // Set custom progress bar font colour.
    $custom_file_upload_progress_bar_font_colour = \theme_legend\toolbox::get_setting('customfileuploadprogressbarfontcolour');
    $css = \theme_legend\toolbox::set_color($css, $custom_file_upload_progress_bar_font_colour, '[[setting:customfileuploadprogressbarfontcolour]]', '#ffffff');

    if ((get_config('theme_legend', 'enablealternativethemecolors1')) ||
            (get_config('theme_legend', 'enablealternativethemecolors2')) ||
            (get_config('theme_legend', 'enablealternativethemecolors3')) ||
            (get_config('theme_legend', 'enablealternativethemecolors4'))
    ) {
        // Set theme alternative colours.
        $defaultcolors = array('#a430d1', '#d15430', '#5dd130', '#006b94');
        $defaulthovercolors = array('#9929c4', '#c44c29', '#53c429', '#4090af');

        foreach (range(1, 4) as $alternative) {
            $default = $defaultcolors[$alternative - 1];
            $defaulthover = $defaulthovercolors[$alternative - 1];
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'color' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemecolor' . $alternative), $default);
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'textcolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemetextcolor' . $alternative), $default);
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'urlcolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemeurlcolor' . $alternative), $default);
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'iconcolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemeiconcolor' . $alternative), $default);
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'navcolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemenavcolor' . $alternative), $default);
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'hovercolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemehovercolor' . $alternative), $defaulthover);

            $css = \theme_legend\toolbox::set_alternativecolor($css, 'footercolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemefootercolor' . $alternative), '#30add1');

            $css = \theme_legend\toolbox::set_alternativecolor($css, 'footertextcolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemefootertextcolor' . $alternative), '#30add1');
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'footerblockbackgroundcolour' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemefooterblockbackgroundcolour' . $alternative), '#cccccc');
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'footerblocktextcolour' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemefooterblocktextcolour' . $alternative), '#000000');
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'footerblockurlcolour' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemefooterblockurlcolour' . $alternative), '#000000');
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'footerblockhovercolour' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemefooterblockhovercolour' . $alternative), '#555555');
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'footerheadingcolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemefooterheadingcolor' . $alternative), '#cccccc');
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'footersepcolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemefootersepcolor' . $alternative), '#313131');
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'footerurlcolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemefooterurlcolor' . $alternative), '#cccccc');
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'footerhovercolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemefooterhovercolor' . $alternative), '#bbbbbb');

            $css = \theme_legend\toolbox::set_alternativecolor($css, 'slidecaptiontextcolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemeslidecaptiontextcolor' . $alternative), $default);
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'slidecaptionbackgroundcolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemeslidecaptionbackgroundcolor' . $alternative), $default);
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'slidebuttoncolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemeslidebuttoncolor' . $alternative), $default);
            $css = \theme_legend\toolbox::set_alternativecolor($css, 'slidebuttonhovercolor' . $alternative,
                    \theme_legend\toolbox::get_setting('alternativethemeslidebuttonhovercolor' . $alternative), $defaulthover);
        }
    }

    // Set the background image for the logo.
    $logo = $theme->setting_file_url('logo', 'logo');
    $css = \theme_legend\toolbox::set_logo($css, $logo);

    // Set the logo height.
    $logoheight = \theme_legend\toolbox::get_setting('logoheight');
    $css = \theme_legend\toolbox::set_logoheight($css, $logoheight);

    // Set the logo width.
    $logowidth = \theme_legend\toolbox::get_setting('logowidth');
    $css = \theme_legend\toolbox::set_logowidth($css, $logowidth);

    // Set the background image for the header.
    $headerbackground = $theme->setting_file_url('headerbackground', 'headerbackground');
    $css = \theme_legend\toolbox::set_headerbackground($css, $headerbackground);

    // Set the background image for the page.
    $pagebackground = $theme->setting_file_url('pagebackground', 'pagebackground');
    $css = \theme_legend\toolbox::set_pagebackground($css, $pagebackground);

    // Set the background style for the page.
    $pagebgstyle = \theme_legend\toolbox::get_setting('pagebackgroundstyle');
    $css = \theme_legend\toolbox::set_pagebackgroundstyle($css, $pagebgstyle);

    // Set marketing height.
    $marketingheight = \theme_legend\toolbox::get_setting('marketingheight');
    $marketingimageheight = \theme_legend\toolbox::get_setting('marketingimageheight');
    $css = \theme_legend\toolbox::set_marketingheight($css, $marketingheight, $marketingimageheight);

    // Set marketing images.
    $setting = 'marketing1image';
    $marketingimage = $theme->setting_file_url($setting, $setting);
    $css = \theme_legend\toolbox::set_marketingimage($css, $marketingimage, $setting);

    $setting = 'marketing2image';
    $marketingimage = $theme->setting_file_url($setting, $setting);
    $css = \theme_legend\toolbox::set_marketingimage($css, $marketingimage, $setting);

    $setting = 'marketing3image';
    $marketingimage = $theme->setting_file_url($setting, $setting);
    $css = \theme_legend\toolbox::set_marketingimage($css, $marketingimage, $setting);

    // Set custom CSS.
    $customcss = \theme_legend\toolbox::get_setting('customcss');
    $css = \theme_legend\toolbox::set_customcss($css, $customcss);

    // Set icon filter
    $css = theme_legend_set_icon_filter($css);

        // Set the background image for the login page.
    $login_background_image = $theme->setting_file_url('login_background_image', 'login_background_image');
    $css = theme_legend_set_login_background_image($css, $login_background_image);

    // Set the background image for the login page.
    $login_background_image_mobile = $theme->setting_file_url('login_background_image_mobile', 'login_background_image_mobile');
    $css = theme_legend_set_login_background_image_mobile($css, $login_background_image_mobile);

    // Set the logo image for the login page.
    $login_logo = $theme->setting_file_url('login_logo', 'login_logo');
    $css = theme_legend_set_login_logo($css, $login_logo);

    // Set the logo width for the login page.
    $login_logo_width = $theme->settings->login_logo_width;
    $css = theme_legend_set_login_logo_width($css, $login_logo_width);

    // Set the logo height for the login page.
    $login_logo_height = $theme->settings->login_logo_height;
    $css = theme_legend_set_login_logo_height($css, $login_logo_height);

    // Set body background colour
    $body_background_colour = $theme->settings->bodybackgroundcolour;
    $css = theme_legend_set_body_background_colour($css, $body_background_colour);

     // Set card border
    $card_border = $theme->settings->cardborder;
    $css = theme_legend_set_card_border($css, $card_border);

     // Set card border radius
    $card_border_radius = $theme->settings->cardborderradius;
    $css = theme_legend_set_card_border_radius($css, $card_border_radius);

    // Set link colour
    $link_colour = $theme->settings->linkcolour;
    $css = theme_legend_set_link_colour($css, $link_colour);

    // Set link font-weight
    $link_font_weight = $theme->settings->linkfontweight;
    $css = theme_legend_set_link_font_weight($css, $link_font_weight);

    // Set link border-bottom
    $link_border_bottom = $theme->settings->linkborderbottom;
    $css = theme_legend_set_link_border_bottom($css, $link_border_bottom);

    // Set link hover colour
    $link_hover_colour = $theme->settings->linkhovercolour;
    $css = theme_legend_set_link_hover_colour($css, $link_hover_colour);

    // Set link border bottom style
    $link_hover_border_bottom_style = $theme->settings->linkhoverborderbottomstyle;
    $css = theme_legend_set_link_hover_border_bottom_style($css, $link_hover_border_bottom_style);

    // Set link text decoration
    $link_hover_text_decoration = $theme->settings->linkhovertextdecoration;
    $css = theme_legend_set_link_hover_text_decoration($css, $link_hover_text_decoration);

    $unread_notfication_count_colour = $theme->settings->unreadnotificationcountcolour;
    $css = theme_legend_set_unread_notfication_count_colour($css, $unread_notfication_count_colour);

    // Finally return processed CSS.
    return $css;
}

function theme_legend_user_flag_and_timezone($data) {
    $countrycode = strtolower($data->country);
    $timezone = $data->timezone;

    $dateTime = new DateTime();

    if (is_numeric($timezone)) {
        $time = time();
    } else {
        $time = $dateTime->setTimeZone(new DateTimeZone($timezone));
    }

    $time = $dateTime->format('H:i');

    $output = '<div class="userpicture-additional">';
    $output .= '<div class="userpicture-time">' . $time . '</div>';

    if (!empty($countrycode)) {
        $output .= '<div class="f16 userpicture-flag"><span class="flag ' . $countrycode . '"></span></div>';
    }

    $output .= '</div>';

    return $output;
}

function theme_legend_page_init(moodle_page $page) {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }

    // Make jQuery globally available
    $page->requires->jquery();
    $page->requires->js_call_amd('theme_legend/fitvids', 'fitVids');
    
    if ($theme->settings->floatingsubmitbuttons) {    
        $page->requires->js_call_amd('theme_legend/add_floating_submit_buttons', 'addFloatingSubmitButtons');
    }

    if ($theme->settings->enable_help_tab) {
        $page->requires->js_amd_inline(populate_js_user_info());
        $page->requires->js_amd_inline(populate_elevio_account_id());
        $page->requires->js_call_amd('theme_legend/elevio_help_tab', 'showHelpTab');
    }

    if ($page->pagetype == 'course-view-topics') {
        $page->requires->js_amd_inline(theme_legend_populate_sectionlink_arrows());
        $page->requires->js_call_amd('theme_legend/trim_section_navigation_links', 'trimSectionNavigationLinks');
        $page->requires->js_amd_inline(fix_course_module_labels());
    }

    if ($page->pagetype == 'mod-hsuforum-discuss') {
        $page->requires->js_call_amd('theme_legend/fix_ratings_line_break', 'fixRatingsLineBreak');
    }

    $page->requires->js_amd_inline(fix_accessibility_block_atags());
    $page->requires->js_call_amd('theme_legend/add_close_button_tooltip', 'addCloseButtonTooltip');

    if ($theme->settings->enablecustomnotifications) {
        $page->requires->js_amd_inline(theme_legend_custom_notification());
    }

    $customfileuploadprogressbarpages = array('mod-assign-editsubmission', 'user-editadvanced', 'mod-assign-viewpluginpage', 'backup-restorefile', 'mod-folder-edit', 'mod-wiki-filesedit', 'mod-wiki-filesedit', 'mod-resource-mod', 'mod-data-edit', 'mod-ouwiki-edit', 'mod-ouwiki-mod', 'mod-workshop-submission');

    if($theme->settings->enablecustomfileuploadprogressbar && in_array($page->pagetype, $customfileuploadprogressbarpages)) {
         $page->requires->js_amd_inline(custom_file_upload_progress_bar());
     }

    if ($theme->settings->enablecustomiconcolour) {
        $page->requires->js_amd_inline(theme_legend_custom_icon_colours());
    }

    if (theme_legend_accessibility_enabled()) {
        if ($page->pagetype == 'mod-questionnaire-complete') {
            $page->requires->js_amd_inline(theme_legend_fix_questionnaire_submit_with_errors());
            $page->requires->js_call_amd('theme_legend/fix_questionnaire_submit_with_errors', 'fixQuestionnaireSubmitWithErrors');
        }

        $page->requires->js_call_amd('theme_legend/fix_tooltip_focus', 'fixTooltipFocus');
        $page->requires->js_call_amd('theme_legend/fix_accessibility_legend_theme', 'fixAccessibilityLegendTheme');
        $page->requires->js_call_amd('theme_legend/fix_accessibility_calendar', 'fixAccessibilityCalendar');

        if ($page->pagetype == 'mod-hsuforum-view') {
            $page->requires->js_call_amd('theme_legend/fix_accessibility_hsuforum', 'fixAccessibilityHsuforum');
        }

        if ($page->pagetype == 'login-change_password') {
            $page->requires->js_call_amd('theme_legend/fix_accessibility_change_password', 'fixAccessibilityChangePassword');
        }
    }
}

function theme_legend_fix_questionnaire_submit_with_errors() {
    global $CFG, $PAGE, $USER;

    $js = 'M.questionnaire_submit_url="'.$CFG->wwwroot.'/mod/questionnaire/myreport.php?id='.
            $PAGE->cm->id.'&instance='.$PAGE->cm->instance.'&user='.$USER->id.'&byresponse=0&action=vresp"';
   return $js;
}

function custom_file_upload_progress_bar() {
    /*
    * Moodle has a built in js(ajax) function that handles file uploads. In boost/boost child themes the file upload progress bar is hidden.
    * This script (with some css) shows a custom file upload progress bar based on moodle's file upload progress bar and uses the
    * Mutation Observer class to observe any changes to the actual file upload progress bar. If it detets a change, it applies the same
    * change to the custom file upload progress bar. It uses a custom progress bar because any changes to the actual progress bar breaks moodle's
    * file upload script. The function has a setting that will disable the script if the need arises. The function also provides extra settings to
    * the script for additional functionality.
    */
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }
    $js = 'try {
            MutationObserver = window.MutationObserver || window.WebKitMutationObserver;  var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    var next_element = $(mutation.target).next();
                    var width = $(mutation.target).width();
                    var parent_width = next_element.parent().width();
                    var width_percent = Math.round(width/parent_width*100) + \'%\';
                    next_element.css("width", width_percent);
                    if(width_percent == \'100%\') {
                        next_element.css(\'background-color\', \''.$theme->settings->customfileuploadprogressbarbackgroundcolourcomplete.'\');
                        next_element.css(\'color\', \''.$theme->settings->customfileuploadprogressbarfontcolourcomplete.'\');';

    if ($theme->settings->customfileuploadprogressbartextcompelete == '') {
        $js .= 'next_element.html(\'100%\');';
    } else {
        $js .= 'next_element.html(\''.$theme->settings->customfileuploadprogressbartextcompelete.'\');';
    }

    $js .= '} else {
                        next_element.html(width_percent);
                    }
                });
            });
            $(\'.dndupload-progressbars\').each(function(i, obj) {
                $(this).bind("DOMSubtreeModified",function(){
                    if ($(this).find(\'.dndupload-progress-inner\').length == 1) {
                        if($(this).find(\'.dndupload-progress-inner-custom\').length == 0) {
                            $(this).find(\'.dndupload-progress-outer\').append(\'<span class="dndupload-progress-inner-custom">&nbsp;</span>\');
                        }
                        observer.observe($(this).find(\'.dndupload-progress-inner\')[0], {
                            subtree: true,
                            attributes: true, attributeFilter:[\'style\']
                        });
                    }
                });
            });
         } catch (err) {
            console.log(err)
         } ';

    return  $js;
}

function theme_legend_custom_icon_colours() {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }

    $js = 'try{jQuery("img.activityicon, img.moduleIcon, .block_ned_sidebar img.icon").each(function(){var e=jQuery(this),t=e.attr("id"),a=e.attr("class"),s=e.attr("src");jQuery.get(s,function(s){var r=jQuery(s).find("svg");"undefined"!=typeof t&&(r=r.attr("id",t)),"undefined"!=typeof a&&(r=r.attr("class",a+" replaced-svg")),r=r.removeAttr("xmlns:a"),$svgHtml=r[0].outerHTML.replace(/'.$theme->settings->customiconbasecolour.'|'.strtolower($theme->settings->customiconbasecolour).'/g,"'.$theme->settings->customiconnewcolour.'");var n=window.btoa(unescape(encodeURIComponent($svgHtml)));e.attr("src","data:image/svg+xml;base64,"+n)},"xml")});} catch(err){console.log(err)}';

    return $js;
}

function theme_legend_custom_notification() {
    global $USER;

    $js[] = 'function mark_notification_as_read(message_id, user_id, uri, redirect, mark_all_notifications) { require([\'core/ajax\'], function(ajax) { var markasread = ajax.call([{
                methodname: \'theme_legend_custom_notifications\',
                args: { messageid: message_id, userid: user_id, markallnotifications: mark_all_notifications }
            }]);
            markasread[0].done(function (response) {
                if (redirect) {
                 window.location = uri;
                }
            }).fail(function(ex){
                console.log(ex);
                window.location = uri;
            });
        })

    };';

    $js[] = 'try { $(document).ready(function() { $(document).on(\'click\', \'.content-item-container.notification.unread a\',function(e) { e.preventDefault(); mark_notification_as_read($(this).closest(\'.content-item-container.notification.unread\').attr(\'data-id\'), '.$USER->id.',$(this).closest(\'.content-item-container.notification.unread a\').attr(\'href\'), true, 0)})});  $(document).ready(function(){$(\'.mark-all-read-button\').click(function(){$(\'.count-container-unread-notifications\').hide(); mark_notification_as_read(0, '.$USER->id.',"" , false, 1);})}) } catch (err) {console.log(err)}';

    return join(' ', $js);

}

function fix_course_module_labels() {

    $js[] = 'try { $(\'.instancename\').each(function(){$(this).contents().filter(function(){ return this.nodeType == 3; }).first().replaceWith(\'<span class="link_with_border">\'+$(this).contents().filter(function(){ return this.nodeType == 3; }).first()[0].nodeValue+\'</span>\');}); } catch (err) {console.log(err)}';

    return join(' ', $js);
}

function fix_accessibility_block_atags() {

    $js[] = 'try { $(\'#accessibility_controls\').children(\'ul\').each(function(){$(this).children(\'li\').each(function(){$(this).children(\'a\').each(function(){$(this).attr(\'href\', \'#\')})})});} catch (err) {console.log(err)}';

    return join(' ', $js);
}

function populate_js_user_info() {
    global $USER;

    $firstname = isset($USER->firstname) ? $USER->firstname : '';
    $lastname  = isset($USER->lastname) ? $USER->lastname : '';
    $email     = isset($USER->email) ? $USER->email : '';

    $js[] = 'M.user = {};';
    $js[] = 'M.user.firstname = "'.$firstname.'";';
    $js[] = 'M.user.lastname = "'.$lastname.'";';
    $js[] = 'M.user.email = "'.$email.'";';

    return join(' ', $js);
}

function theme_legend_set_icon_filter($css) {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }

    $tag = '[[setting:icon_filter]]';
    if ($theme->settings->icon_filter) {
        $replacement = $theme->settings->icon_filter;
    } else {
        $replacement = 'hue-rotate(0deg)';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function populate_elevio_account_id(){
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }
    $account_id = ($theme->settings->elevio_account_id != '') ? $theme->settings->elevio_account_id : '';
    $js[] = "M.account_details = {};";
    $js[] = "M.account_details.elevio_account_id = '$account_id';";

    return join(' ', $js);
}

function theme_legend_populate_sectionlink_arrows(){
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }
    $js[] = "M.sectionlinks = {};";
    $js[] = "M.sectionlinks.rarrow = '$theme->rarrow';";
    $js[] = "M.sectionlinks.larrow = '$theme->larrow';";

    return join(' ', $js);
}

function theme_legend_get_custom_footer(){
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }

    if ($theme->settings->footnote) {
        return $theme->settings->footnote;
    } else {
        return '';
    }
}

function theme_legend_get_copyright(){
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }

    if ($theme->settings->copyright) {
        return $theme->settings->copyright;
    } else {
        return '';
    }
}

function theme_legend_get_current_section($course) {
    $sectionarray = array();

    $modinfo = get_fast_modinfo($course);
    $sections = $modinfo->get_section_info_all();

    foreach($sections as $sectionkey => $section) {

        if (!$section->uservisible) {
            continue;
        }

        $availability = json_decode($section->availability, true);

        if(isset($availability['c'][0]['t'])) {
            $sectionarray[$section->section]['date'] = strtotime(userdate($availability['c'][0]['t']));
            $sectionarray[$section->section]['name'] = get_section_name($course->id, $section);
            $sectionarray[$section->section]['id'] = $section->section;
        }
    }

    foreach($sectionarray as $sectionkey => $sectionvalue) {
        $interval[$sectionkey] = abs(time() - $sectionvalue['date']);
    }

    if (isset($interval)) {
        asort($interval);
        return $sectionarray[key($interval)];
    }

    return reset($sectionarray);
}

function theme_legend_set_course_name_display_style($css) {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }

    $tag = '[[setting:display_course_name]]';
    if ($theme->settings->display_course_name) {
        $replacement = 'inline-block';
    } else {
        $replacement = 'none';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_login_background_image($css, $login_background_image) {
    $tag = '[[setting:login_background_image]]';
    if (!($login_background_image)) {
        $replacement = 'none';
    } else {
        $replacement = 'url(\'' . $login_background_image . '\')';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_login_background_image_mobile($css, $login_background_image_mobile) {
    $tag = '[[setting:login_background_image_mobile]]';
    if (!($login_background_image_mobile)) {
        $replacement = 'none';
    } else {
        $replacement = 'url(\'' . $login_background_image_mobile . '\')';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_login_logo($css, $login_logo) {
    $tag = '[[setting:login_logo]]';
    if (!($login_logo)) {
        $replacement = 'none';
    } else {
        $replacement = 'url(\'' .$login_logo. '\')';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_login_logo_width($css, $login_logo_width) {
    $tag = '[[setting:login_logo_width]]';
    if (!($login_logo_width)) {
        $replacement = '0';
    } else {
        $replacement = $login_logo_width;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_login_logo_height($css, $login_logo_height) {
    $tag = '[[setting:login_logo_height]]';
    if (!($login_logo_height)) {
        $replacement = '0';
    } else {
        $replacement = $login_logo_height;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_login_text_colour($css, $login_text_colour) {
    $tag = '[[setting:login_text_colour]]';
    if (!($login_text_colour)) {
        $replacement = '0';
    } else {
        $replacement = $login_text_colour;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_body_background_colour($css, $bodybackgroundcolour) {
    $tag = '[[setting:bodybackgroundcolour]]';
    if (!($bodybackgroundcolour)) {
        $replacement = '#f4f4f4';
    } else {
        $replacement = $bodybackgroundcolour;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_card_border($css, $cardborder) {
    $tag = '[[setting:cardborder]]';
    if (!($cardborder)) {
        $replacement = '1px solid rgba(238,238,238,1)';
    } else {
        $replacement = $cardborder;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_card_border_radius($css, $cardborderradius) {
    $tag = '[[setting:cardborderradius]]';
    if (!($cardborderradius)) {
        $replacement = '0';
    } else {
        $replacement = $cardborderradius;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_link_colour($css, $linkcolour) {
    $tag = '[[setting:linkcolour]]';
    if (!($linkcolour)) {
        $replacement = '2f2f2f';
    } else {
        $replacement = $linkcolour;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_link_font_weight($css, $linkfontweight) {
    $tag = '[[setting:linkfontweight]]';
    if (!($linkfontweight)) {
        $replacement = '600';
    } else {
        $replacement = $linkfontweight;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_link_border_bottom($css, $linkborderbottom) {
    $tag = '[[setting:linkborderbottom]]';
    if (!($linkborderbottom)) {
        $replacement = '1px dashed #bbb';
    } else {
        $replacement = $linkborderbottom;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_link_hover_colour($css, $linkhovercolour) {
    $tag = '[[setting:linkhovercolour]]';
    if (!($linkhovercolour)) {
        $replacement = '#8c8c8c';
    } else {
        $replacement = $linkhovercolour;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_link_hover_border_bottom_style($css, $linkhoverborderbottom) {
    $tag = '[[setting:linkhoverborderbottomstyle]]';
    if (!($linkhoverborderbottom)) {
        $replacement = 'solid';
    } else {
        $replacement = $linkhoverborderbottom;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_set_link_hover_text_decoration($css, $linkhovertextdecoration) {
    $tag = '[[setting:linkhovertextdecoration]]';
    if (!($linkhovertextdecoration)) {
        $replacement = 'none';
    } else {
        $replacement = $linkhovertextdecoration;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_get_header_title() {
    global $CFG, $SITE;
    $title = '';

    switch (\theme_legend\toolbox::get_setting('headertitle')) {
        case 0:
            return $title;
            break;
        case 1:
            $title = '<h1 id="title">' . format_string($SITE->fullname, true,
                            array('context' => context_course::instance(SITEID))) . '</h1>';
            break;
        case 2:
            $title = '<h1 id="title">' . format_string($SITE->shortname, true,
                            array('context' => context_course::instance(SITEID))) . '</h1>';
            break;
        case 3:
            $title = '<h1 id="smalltitle">' . format_string($SITE->fullname, true,
                            array('context' => context_course::instance(SITEID))) . '</h2>';
            $title .= '<h2 id="subtitle">' . strip_tags($SITE->summary) . '</h3>';
            break;
        case 4:
            $title = '<h1 id="smalltitle">' . format_string($SITE->shortname, true,
                            array('context' => context_course::instance(SITEID))) . '</h2>';
            $title .= '<h2 id="subtitle">' . strip_tags($SITE->summary) . '</h3>';
            break;
        default:
            break;
    }

    return $title;
}

function theme_legend_get_favion() {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }
    return $theme->setting_file_url('favicon', 'favicon');
}

function theme_legend_show_course_block_logo() {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }
    if ($theme->settings->showcourseblocklogo) {
        return $theme->settings->showcourseblocklogo;
    } else {
        return false;
    }
}

function theme_legend_get_custom_navbar() {
    global $PAGE;

    $items = $PAGE->navbar->get_items();
    $itemcount = count($items);

    if ($itemcount === 0) {
        return '';
    }

    $navbarcontent = '<nav role="navigation"><ol class="breadcrumb">';

    if (theme_legend_accessibility_enabled()) {
        $navbarcontent = '<nav role="navigation" aria-label="breadcrumbs"><ol class="breadcrumb">';
    }

    for ($i=0; $i < $itemcount; $i++) {
        $item = $items[$i];
        $item->hideicon = true;
        if ($i === 0 || $item->text === 'Dashboard' || $item->text === 'My courses') {
            continue;
        } else if ($i === ($itemcount - 1)) {
            $navbarcontent .= '<li class="breadcrumb-item no-link">'.strip_tags(($item->shorttext != NULL && $item->text == $item->shorttext) ? (($item->title == '') ? $item->shorttext : $item->title) : $item->text).'</li>';
        } else {
            $navbarcontent .= '<li class="breadcrumb-item"><a href="'.strip_tags($item->action).'">'.strip_tags(($item->shorttext != NULL && $item->text == $item->shorttext) ? (($item->title == '') ? $item->shorttext : $item->title) : $item->text).'</a></li>';
        }
    }
    $navbarcontent.= '</ol></nav>';

    return $navbarcontent;
}

function theme_legend_get_brandicon() {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }
    return $theme->setting_file_url('brandicon', 'brandicon');
}

function theme_legend_get_unread_notification_count() {
    global $DB, $USER;
    static $theme;

    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }

    $unreadnotficationcounthtml = '';

    $unreadnotficationcount = $DB->count_records_select('message_read', "useridto = ? AND notification = 1 AND timeread = 0", array($USER->id, "COUNT('id')"));

    if ($unreadnotficationcount > 0 && $theme->settings->enablecustomnotifications) {
        $unreadnotficationcounthtml = '<div class="count-container-unread-notifications" data-region="count-container">'.$unreadnotficationcount.'</div>';
    }

    return $unreadnotficationcounthtml;
}

function theme_legend_set_unread_notfication_count_colour($css, $unreadnotificationcountcolour) {
    $tag = '[[setting:unreadnotificationcountcolour]]';
    if (!($unreadnotificationcountcolour)) {
        $replacement = '#545454';
    } else {
        $replacement = $unreadnotificationcountcolour;
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_legend_accessibility_enabled() {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('legend');
    }
    return $theme->settings->enablecustomaccessibility;
}
