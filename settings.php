<?php

/**
 * Settings for the moodle2_tsc theme
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Hide Settings block
    $name = 'theme_moodle2_tsc/hidesettingsblock';
    $title = get_string('hidesettingsblock','theme_moodle2_tsc');
    $description = get_string('hidesettingsblockdesc', 'theme_moodle2_tsc');
    $default = 1;
    $choices = array(1=>'Yes', 0=>'No');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Hide Navigation block
    $name = 'theme_moodle2_tsc/hidenavigationblock';
    $title = get_string('hidenavigationblock','theme_moodle2_tsc');
    $description = get_string('hidenavigationblockdesc', 'theme_moodle2_tsc');
    $default = 0;
    $choices = array(1=>'Yes', 0=>'No');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);
    
    // Show user profile picture
    $name = 'theme_moodle2_tsc/showuserpicture';
    $title = get_string('showuserpicture','theme_moodle2_tsc');
    $description = get_string('showuserpicturedesc', 'theme_moodle2_tsc');
    $default = 0;
    $choices = array(1=>'Yes', 0=>'No');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Add custom menu to Awesomebar
    $name = 'theme_moodle2_tsc/custommenuinawesomebar';
    $title = get_string('custommenuinawesomebar','theme_moodle2_tsc');
    $description = get_string('custommenuinawesomebardesc', 'theme_moodle2_tsc');
    $default = 0;
    $choices = array(1=>'Yes', 0=>'No');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Place custom menu after Awesomebar
    $name = 'theme_moodle2_tsc/custommenuafterawesomebar';
    $title = get_string('custommenuafterawesomebar','theme_moodle2_tsc');
    $description = get_string('custommenuafterawesomebardesc', 'theme_moodle2_tsc');
    $default = 0;
    $choices = array(0=>'No', 1=>'Yes');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);
   

}