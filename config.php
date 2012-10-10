<?php

$THEME->name = 'moodle2_tsc';

////////////////////////////////////////////////////
// Name of the theme. Most likely the name of
// the directory in which this file resides.
////////////////////////////////////////////////////


$THEME->parents = array('canvas','base');

/////////////////////////////////////////////////////
// Which existing theme(s) in the /theme/ directory
// do you want this theme to extend. A theme can
// extend any number of themes. Rather than
// creating an entirely new theme and copying all
// of the CSS, you can simply create a new theme,
// extend the theme you like and just add the
// changes you want to your theme.
////////////////////////////////////////////////////


$THEME->sheets = array('awesomebar_black', 'core');

////////////////////////////////////////////////////
// Name of the stylesheet(s) you've including in
// this theme's /styles/ directory.
////////////////////////////////////////////////////


$THEME->enable_dock = false;

////////////////////////////////////////////////////
// Do you want to use the new navigation dock?
////////////////////////////////////////////////////


$THEME->editor_sheets = array('editor');

////////////////////////////////////////////////////
// An array of stylesheets to include within the
// body of the editor.
////////////////////////////////////////////////////

// $THEME->csspostprocess

////////////////////////////////////////////////////
// Allows the user to provide the name of a function
// that all CSS should be passed to before being
// delivered.
////////////////////////////////////////////////////

// $THEME->javascripts

////////////////////////////////////////////////////
// An array containing the names of JavaScript files
// located in /javascript/ to include in the theme.
// (gets included in the head)
////////////////////////////////////////////////////

// $THEME->javascripts_footer

////////////////////////////////////////////////////
// As above but will be included in the page footer.
////////////////////////////////////////////////////

// $THEME->larrow

////////////////////////////////////////////////////
// Overrides the left arrow image used throughout
// Moodle
////////////////////////////////////////////////////

// $THEME->rarrow

////////////////////////////////////////////////////
// Overrides the right arrow image used throughout Moodle
////////////////////////////////////////////////////

$THEME->layouts = array(
    'mydashboard' => array(
        'file' => 'my.php',
        'regions' => array('side-pre', 'side-post', 'notices'),
        'defaultregion' => 'side-pre',
    ),
    
    'base' => array(
        'file' => 'base.php',
    ),    
    
    'login' => array(
        'file' => 'login.php',
    ),       
    
    'report' => array(
        'file' => 'report.php',
    ),
    
    'course' => array(
    	'file' => 'course.php',
        'regions' => array('side-pre', 'side-post', 'notices'),
        'defaultregion' => 'side-pre',
    ),

    'coursecategory' => array(
    'file' => 'coursecategory.php',
    ),
    
    'admin' => array(
    	'file' => 'admin.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ),
    
    // The site home page.
    'frontpage' => array(
        'file' => 'frontpage.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu'=>true),
    ),
);

////////////////////////////////////////////////////
// An array setting the layouts for the theme
////////////////////////////////////////////////////

// $THEME->parents_exclude_javascripts

////////////////////////////////////////////////////
// An array of JavaScript files NOT to inherit from
// the themes parents
////////////////////////////////////////////////////

// $THEME->parents_exclude_sheets

////////////////////////////////////////////////////
// An array of stylesheets not to inherit from the
// themes parents
////////////////////////////////////////////////////

// $THEME->plugins_exclude_sheets

////////////////////////////////////////////////////
// An array of plugin sheets to ignore and not
// include.
////////////////////////////////////////////////////

$THEME->rendererfactory = 'theme_overridden_renderer_factory';

////////////////////////////////////////////////////
// Sets a custom render factory to use with the
// theme, used when working with custom renderers.
////////////////////////////////////////////////////

