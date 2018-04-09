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
 * This is built using the bootstrapbase pagelate to allow for new theme's using
 * Moodle's new Bootstrap theme engine
 *
 * @package     theme_legend

 */
$settings = null;

defined('MOODLE_INTERNAL') || die;

if (is_siteadmin()) {

    $settings = new theme_boost_admin_settingspage_tabs('themesettinglegend', get_string('configtitle', 'theme_legend'));

    // Each page is a tab - the first is the "General" tab.
    $page = new admin_settingpage('theme_legend_generalsettings', get_string('generalsettings', 'theme_legend'));

    // Icon filter
    $name = 'theme_legend/icon_filter';
    $title = get_string('icon_filter', 'theme_legend');
    $description = get_string('icon_filter_desc', 'theme_legend');
    $default = 'hue-rotate(0deg)';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Fixed or Variable Width.
    $name = 'theme_legend/pagewidth';
    $title = get_string('pagewidth', 'theme_legend');
    $description = get_string('pagewidthdesc', 'theme_legend');
    $default = 1200;
    $choices = array(960 => get_string('fixedwidthnarrow', 'theme_legend'),
        1200 => get_string('fixedwidthnormal', 'theme_legend'),
        1400 => get_string('fixedwidthwide', 'theme_legend'),
        100 => get_string('variablewidth', 'theme_legend'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Hide course name at the top of every course page
    $name = 'theme_legend/display_course_name';
    $title = get_string('display_course_name', 'theme_legend');
    $description = get_string('display_course_name_desc', 'theme_legend');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $page->add($setting);

    // Display Elevio Help Tab
    $name = 'theme_legend/enable_help_tab';
    $title = get_string('enable_help_tab', 'theme_legend');
    $description = get_string('enable_help_tab_desc', 'theme_legend');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Elevio account_id.
    $name = 'theme_legend/elevio_account_id';
    $title = get_string('elevio_account_id', 'theme_legend');
    $description = get_string('elevio_account_id_desc', 'theme_legend');
    $default = '54c0a42da8e62';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Display flag and time
    $name = 'theme_legend/display_flag_and_time';
    $title = get_string('display_flag_and_time', 'theme_legend');
    $description = get_string('display_flag_and_time_desc', 'theme_legend');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Fitvids.
    $name = 'theme_legend/fitvids';
    $title = get_string('fitvids', 'theme_legend');
    $description = get_string('fitvidsdesc', 'theme_legend');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Floating submit buttons.
    $name = 'theme_legend/floatingsubmitbuttons';
    $title = get_string('floatingsubmitbuttons', 'theme_legend');
    $description = get_string('floatingsubmitbuttonsdesc', 'theme_legend');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Favicon.
    $name = 'theme_legend/favicon';
    $title = get_string('favicon', 'theme_legend');
    $description = get_string('favicondesc', 'theme_legend');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Brandicon.
    $name = 'theme_legend/brandicon';
    $title = get_string('brandicon', 'theme_legend');
    $description = get_string('brandicondesc', 'theme_legend');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'brandicon');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_legend_loginpagesettings', get_string('loginpagesettings', 'theme_legend'));

     // Login message setting.
    $name = 'theme_legend/login_message_top';
    $title = get_string('login_message_top', 'theme_legend');
    $description = get_string('login_message_top_desc', 'theme_legend');
    $default = '<h2>Welcome to your Online Campus</h2><p>Earn the recognition you deserve with a flexible, career-focused short course education.</p>';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login bottom message setting.
    $name = 'theme_legend/login_message_bottom';
    $title = get_string('login_message_bottom', 'theme_legend');
    $description = get_string('login_message_bottom_desc', 'theme_legend');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login page background image setting.
    $name = 'theme_legend/login_background_image';
    $title = get_string('login_background_image', 'theme_legend');
    $description = get_string('login_background_image_desc', 'theme_legend');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'login_background_image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login page background image setting.
    $name = 'theme_legend/login_background_image_mobile';
    $title = get_string('login_background_image_mobile', 'theme_legend');
    $description = get_string('login_background_image_mobile_desc', 'theme_legend');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'login_background_image_mobile');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login page background image setting.
    $name = 'theme_legend/login_background_video';
    $title = get_string('login_background_video', 'theme_legend');
    $description = get_string('login_background_video_desc', 'theme_legend');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'login_background_video');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Logo file setting.
    $name = 'theme_legend/login_logo';
    $title = get_string('login_logo', 'theme_legend');
    $description = get_string('loginlogodesc', 'theme_legend');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'login_logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Logo height setting.
    $name = 'theme_legend/login_logo_height';
    $title = get_string('login_logo_height', 'theme_legend');
    $description = get_string('logoheightdesc', 'theme_legend');
    $default = '124px';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Logo width setting.
    $name = 'theme_legend/login_logo_width';
    $title = get_string('login_logo_width', 'theme_legend');
    $description = get_string('loginlogowidthdesc', 'theme_legend');
    $default = '120px';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_legend_headersettings', get_string('headerheading', 'theme_legend'));

    // Logo file setting.
    $name = 'theme_legend/logo';
    $title = get_string('logo', 'theme_legend');
    $description = get_string('logodesc', 'theme_legend');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Logo height setting.
    $name = 'theme_legend/logoheight';
    $title = get_string('logoheight', 'theme_legend');
    $description = get_string('logoheightdesc', 'theme_legend');
    $default = '65px';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Logo width setting.
    $name = 'theme_legend/logowidth';
    $title = get_string('logowidth', 'theme_legend');
    $description = get_string('logowidthdesc', 'theme_legend');
    $default = '80px';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Header title setting.
    $name = 'theme_legend/headertitle';
    $title = get_string('headertitle', 'theme_legend');
    $description = get_string('headertitledesc', 'theme_legend');
    $default = 0;
    $choices = array(
        0 => get_string('notitle', 'theme_legend'),
        1 => get_string('fullname', 'theme_legend'),
        2 => get_string('shortname', 'theme_legend'),
        3 => get_string('fullnamesummary', 'theme_legend'),
        4 => get_string('shortnamesummary', 'theme_legend')
    );
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Header Background Image.
    $name = 'theme_legend/headerbackground';
    $title = get_string('headerbackground', 'theme_legend');
    $description = get_string('headerbackgrounddesc', 'theme_legend');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerbackground');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Header text colour setting.
    $name = 'theme_legend/headertextcolor';
    $title = get_string('headertextcolor', 'theme_legend');
    $description = get_string('headertextcolordesc', 'theme_legend');
    $default = '#969696';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_legend_footersettings', get_string('footersettings', 'theme_legend'));

    // Copyright setting.
    $name = 'theme_legend/copyright';
    $title = get_string('copyright', 'theme_legend');
    $description = get_string('copyrightdesc', 'theme_legend');
    $default = '<div class="row-fluid footerlinks"><div class="span12 copylinks"><span class="copy">© 2017 GetSmarter.</span><span class="footnote">| <a href="http://www.getsmarter.co.za/terms-and-conditions">Terms and conditions</a> | <a href="http://www.getsmarter.co.za/terms-and-conditions-for-students">Terms and conditions for Students</a> | <a href="http://www.getsmarter.co.za/acceptable-use-policy">Acceptable Use</a> | <a href="http://www.getsmarter.co.za/privacy-policy">Privacy policy</a></span></div></div>';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $page->add($setting);

    // Footnote setting.
    $name = 'theme_legend/footnote';
    $title = get_string('footnote', 'theme_legend');
    $description = get_string('footnotedesc', 'theme_legend');
    $default = '<p><img src="https://images.onlinecampus.getsmarter.ac/ICWGS_on_charcoal.png" alt="In partnership with GetSmarter" style="border-bottom: 1px solid #444; margin-bottom: 10px; padding-bottom: 10px;" class="img-responsive" height="65" width="232"></p><p></p><p>Find us on these networks</p><ul class="socials socials-footer"><li><button class="socialicon twitter" aria-label="Twitter" title="Twitter" onclick="window.open(\'https://twitter.com/getting_smarter\')"><i class="fa fa-twitter"></i></button></li><li><button class="socialicon facebook" aria-label="Facebook" title="Facebook" onclick="window.open(\'https://www.facebook.com/pages/Getsmarter/89654856679\')"><i class="fa fa-facebook"></i></button></li><li><button class="socialicon youtube" aria-label="Youtube" title="Youtube" onclick="window.open(\'http://www.youtube.com/channel/UCmWpwVPu1DrFOuA8T7iaSsQ\')"><i class="fa fa-youtube"></i></button></li><li><button class="socialicon website" aria-label="Website" title="Website" onclick="window.open(\'http://www.getsmarter.co.za\')"><i class="fa fa-graduation-cap"></i></button></li></ul>';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_legend_coloursettings', get_string('colorheading', 'theme_legend'));

    // Login page font colour.
    $name = 'theme_legend/bodybackgroundcolour';
    $title = get_string('bodybackgroundcolour', 'theme_legend');
    $description = get_string('bodybackgroundcolourdesc', 'theme_legend');
    $default = '#f2f2f2';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Main theme colour setting.
    $name = 'theme_legend/themecolor';
    $title = get_string('themecolor', 'theme_legend');
    $description = get_string('themecolordesc', 'theme_legend');
    $default = '#30add1';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Main theme text colour setting.
    $name = 'theme_legend/themetextcolor';
    $title = get_string('themetextcolor', 'theme_legend');
    $description = get_string('themetextcolordesc', 'theme_legend');
    $default = '#2f2f2f';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Main theme link colour setting.
    $name = 'theme_legend/themeurlcolor';
    $title = get_string('themeurlcolor', 'theme_legend');
    $description = get_string('themeurlcolordesc', 'theme_legend');
    $default = '#2f2f2f';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Main theme Hover colour setting.
    $name = 'theme_legend/themehovercolor';
    $title = get_string('themehovercolor', 'theme_legend');
    $description = get_string('themehovercolordesc', 'theme_legend');
    $default = '#2f2f2f';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Main theme colour setting.
    $name = 'theme_legend/themeheaderbordercolour';
    $title = get_string('themeheaderbordercolour', 'theme_legend');
    $description = get_string('themeheaderbordercolourdesc', 'theme_legend');
    $default = '#943b21';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Icon colour setting.
    $name = 'theme_legend/themeiconcolor';
    $title = get_string('themeiconcolor', 'theme_legend');
    $description = get_string('themeiconcolordesc', 'theme_legend');
    $default = '#3a3a3a';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Navigation colour setting.
    $name = 'theme_legend/themenavcolor';
    $title = get_string('themenavcolor', 'theme_legend');
    $description = get_string('themenavcolordesc', 'theme_legend');
    $default = '#2f2f2f';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Label colour setting.
    $name = 'theme_legend/themelabelcolor';
    $title = get_string('themelabelcolor', 'theme_legend');
    $description = get_string('themelabelcolordesc', 'theme_legend');
    $default = '#2f2f2f';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Paragraph colour setting.
    $name = 'theme_legend/themepcolor';
    $title = get_string('themepcolor', 'theme_legend');
    $description = get_string('themepdesc', 'theme_legend');
    $default = '#2f2f2f';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Span colour setting.
    $name = 'theme_legend/themespancolor';
    $title = get_string('themespancolor', 'theme_legend');
    $description = get_string('themespancolordesc', 'theme_legend');
    $default = '#3a3a3a';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Dropdown-item colour setting.
    $name = 'theme_legend/themedropdownitemcolor';
    $title = get_string('themedropdownitemcolor', 'theme_legend');
    $description = get_string('themedropdownitemcolorlordesc', 'theme_legend');
    $default = '#2f2f2f';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // This is the descriptor for the Footer
    $name = 'theme_legend/footercolorinfo';
    $heading = get_string('footercolors', 'theme_legend');
    $information = get_string('footercolorsdesc', 'theme_legend');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    // Footer background colour setting.
    $name = 'theme_legend/footercolor';
    $title = get_string('footercolor', 'theme_legend');
    $description = get_string('footercolordesc', 'theme_legend');
    $default = '#30add1';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer text colour setting.
    $name = 'theme_legend/footertextcolor';
    $title = get_string('footertextcolor', 'theme_legend');
    $description = get_string('footertextcolordesc', 'theme_legend');
    $default = '#b1b1b1';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer heading colour setting.
    $name = 'theme_legend/footerheadingcolor';
    $title = get_string('footerheadingcolor', 'theme_legend');
    $description = get_string('footerheadingcolordesc', 'theme_legend');
    $default = '#b1b1b1';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer block background colour setting.
    $name = 'theme_legend/footerblockbackgroundcolour';
    $title = get_string('footerblockbackgroundcolour', 'theme_legend');
    $description = get_string('footerblockbackgroundcolourdesc', 'theme_legend');
    $default = '#cccccc';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer block text colour setting.
    $name = 'theme_legend/footerblocktextcolour';
    $title = get_string('footerblocktextcolour', 'theme_legend');
    $description = get_string('footerblocktextcolourdesc', 'theme_legend');
    $default = '#b1b1b1';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer block URL colour setting.
    $name = 'theme_legend/footerblockurlcolour';
    $title = get_string('footerblockurlcolour', 'theme_legend');
    $description = get_string('footerblockurlcolourdesc', 'theme_legend');
    $default = '#b1b1b1';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer block URL hover colour setting.
    $name = 'theme_legend/footerblockhovercolour';
    $title = get_string('footerblockhovercolour', 'theme_legend');
    $description = get_string('footerblockhovercolourdesc', 'theme_legend');
    $default = '#b1b1b1';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer Seperator colour setting.
    $name = 'theme_legend/footersepcolor';
    $title = get_string('footersepcolor', 'theme_legend');
    $description = get_string('footersepcolordesc', 'theme_legend');
    $default = '#b1b1b1';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer URL colour setting.
    $name = 'theme_legend/footerurlcolor';
    $title = get_string('footerurlcolor', 'theme_legend');
    $description = get_string('footerurlcolordesc', 'theme_legend');
    $default = '#b1b1b1';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer URL hover colour setting.
    $name = 'theme_legend/footerhovercolor';
    $title = get_string('footerhovercolor', 'theme_legend');
    $description = get_string('footerhovercolordesc', 'theme_legend');
    $default = '#b1b1b1';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // This is the descriptor for the user theme colours.
    $name = 'theme_legend/alternativethemecolorsinfo';
    $heading = get_string('alternativethemecolors', 'theme_legend');
    $information = get_string('alternativethemecolorsdesc', 'theme_legend');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    // Login page font colour.
    $name = 'theme_legend/login_text_color';
    $title = get_string('login_text_color', 'theme_legend');
    $description = get_string('logintextcolordesc', 'theme_legend');
    $default = '#ffffff';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_legend_skin_v1_settings', get_string('skin_v1_heading', 'theme_legend'));

    // Course block logo.
    $name = 'theme_legend/showcourseblocklogo';
    $title = get_string('showcourseblocklogo', 'theme_legend');
    $description = get_string('showcourseblocklogodesc', 'theme_legend');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login page font colour.
    $name = 'theme_legend/bodybackgroundcolour';
    $title = get_string('bodybackgroundcolour', 'theme_legend');
    $description = get_string('bodybackgroundcolourdesc', 'theme_legend');
    $default = '#f2f2f2';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Card border
    $name = 'theme_legend/cardborder';
    $title = get_string('cardborder', 'theme_legend');
    $description = get_string('cardborderdesc', 'theme_legend');
    $default = '1px solid #ccc';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Card border-radius
    $name = 'theme_legend/cardborderradius';
    $title = get_string('cardborderradius', 'theme_legend');
    $description = get_string('cardborderradiusdesc', 'theme_legend');
    $default = '3px';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Link colour
    $name = 'theme_legend/linkcolour';
    $title = get_string('linkcolour', 'theme_legend');
    $description = get_string('linkcolourdesc', 'theme_legend');
    $default = '#2f2f2f';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Link font weight
    $name = 'theme_legend/linkfontweight';
    $title = get_string('linkfontweight', 'theme_legend');
    $description = get_string('linkfontweightdesc', 'theme_legend');
    $default = '400';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Link border bottom
    $name = 'theme_legend/linkborderbottom';
    $title = get_string('linkborderbottom', 'theme_legend');
    $description = get_string('linkborderbottomdesc', 'theme_legend');
    $default = '1px dashed #bbb';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Link border bottom
    $name = 'theme_legend/linkhovercolour';
    $title = get_string('linkhovercolour', 'theme_legend');
    $description = get_string('linkhovercolourdesc', 'theme_legend');
    $default = '#8c8c8c';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Link border bottom
    $name = 'theme_legend/linkhoverborderbottomstyle';
    $title = get_string('linkhoverborderbottomstyle', 'theme_legend');
    $description = get_string('linkhoverborderbottomstyledesc', 'theme_legend');
    $default = 'solid';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Link border bottom
    $name = 'theme_legend/linkhovertextdecoration';
    $title = get_string('linkhovertextdecoration', 'theme_legend');
    $description = get_string('linkhovertextdecorationdesc', 'theme_legend');
    $default = 'none';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_legend_custom_notifications', get_string('customnotifications_settings', 'theme_legend'));

    // Enable custom notifictions
    $name = 'theme_legend/enablecustomnotifications';
    $title = get_string('enablecustomnotifications', 'theme_legend');
    $description = get_string('enablecustomnotificationsdesc', 'theme_legend');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Unread notification count badge colour
    $name = 'theme_legend/unreadnotificationcountcolour';
    $title = get_string('unreadnotificationcountcolour', 'theme_legend');
    $description = get_string('unreadnotificationcountcolourdesc', 'theme_legend');
    $default = '#FF1419';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_legend/customnotificationsubjectsubmissiongraded';
    $title = get_string('customnotificationsubjectsubmissiongraded', 'theme_legend');
    $description = get_string('customnotificationsubjectsubmissiongradeddesc', 'theme_legend');
    $default = '{gradinguser} has given feedback for assignment {activityname}';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Enable advanced forum notifictions
    $name = 'theme_legend/enableadvancedforumnotifications';
    $title = get_string('enableadvancedforumnotifications', 'theme_legend');
    $description = get_string('enableadvancedforumnotificationsdesc', 'theme_legend');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Enable advanced forum notifictions
    $name = 'theme_legend/hideadvancedforumpostsnotifications';
    $title = get_string('hideadvancedforumpostsnotifications', 'theme_legend');
    $description = get_string('hideadvancedforumpostsnotificationsdesc', 'theme_legend');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_legend_custom_icon_colour', get_string('customiconcolour_settings', 'theme_legend'));

    // Enable custom icon colours
    $name = 'theme_legend/enablecustomiconcolour';
    $title = get_string('enablecustomiconcolour', 'theme_legend');
    $description = get_string('enablecustomiconcolourdesc', 'theme_legend');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Base icon colour
    $name = 'theme_legend/customiconbasecolour';
    $title = get_string('customiconbasecolour', 'theme_legend');
    $description = get_string('customiconbasecolourdesc', 'theme_legend');
    $default = '#F42684';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // New icon colour
    $name = 'theme_legend/customiconnewcolour';
    $title = get_string('customiconnewcolour', 'theme_legend');
    $description = get_string('customiconnewcolourdesc', 'theme_legend');
    $default = '#F42684';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_legend_custom_file_upload_progress_bar', get_string('customfileuploadprogressbar_settings', 'theme_legend'));

    // Enable custom file upload progress bar
    $name = 'theme_legend/enablecustomfileuploadprogressbar';
    $title = get_string('enablecustomfileuploadprogressbar', 'theme_legend');
    $description = get_string('enablecustomfileuploadprogressbaresc', 'theme_legend');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Progress bar text when complete
    $name = 'theme_legend/customfileuploadprogressbartextcompelete';
    $title = get_string('customfileuploadprogressbartextcompelete', 'theme_legend');
    $description = get_string('customfileuploadprogressbartextcompeletedesc', 'theme_legend');
    $default = '100%';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Progress bar colour
    $name = 'theme_legend/customfileuploadprogressbarbackgroundcolour';
    $title = get_string('customfileuploadprogressbarbackgroundcolour', 'theme_legend');
    $description = get_string('customfileuploadprogressbarbackgroundcolourdesc', 'theme_legend');
    $default = '#545454';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Progress bar colour complete
    $name = 'theme_legend/customfileuploadprogressbarbackgroundcolourcomplete';
    $title = get_string('customfileuploadprogressbarbackgroundcolourcomplete', 'theme_legend');
    $description = get_string('customfileuploadprogressbarbackgroundcolourcompletedesc', 'theme_legend');
    $default = '#545454';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Progress bar font colour
    $name = 'theme_legend/customfileuploadprogressbarfontcolour';
    $title = get_string('customfileuploadprogressbarfontcolour', 'theme_legend');
    $description = get_string('customfileuploadprogressbarfontcolourdesc', 'theme_legend');
    $default = '#ffffff';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Progress bar font colour complete
    $name = 'theme_legend/customfileuploadprogressbarfontcolourcomplete';
    $title = get_string('customfileuploadprogressbarfontcolourcomplete', 'theme_legend');
    $description = get_string('customfileuploadprogressbarfontcolourcompletedesc', 'theme_legend');
    $default = '#ffffff';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_legend_accessibility', get_string('accessibilitysettings', 'theme_legend'));

    // Accessibility switch
    $name = 'theme_legend/enablecustomaccessibility';
    $title = get_string('enablecustomaccessibility', 'theme_legend');
    $description = get_string('enablecustomaccessibilitydesc', 'theme_legend');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}
