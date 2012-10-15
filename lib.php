<?php

/**
 * get_performance_output() override get_peformance_info()
 *  in moodlelib.php. Returns a string
 * values ready for use.
 *
 * @return string
 */
function moodle2_tsc_performance_output($param) {
	
    $html = '<div class="performanceinfo"><p>';
	if (isset($param['realtime'])) $html .= 'Load Time: '.$param['realtime'].' secs -- ';
	if (isset($param['memory_total'])) $html .= 'Memory Used: '.display_size($param['memory_total']).' -- ';
    if (isset($param['includecount'])) $html .='' .$param['includecount'].' Files Included -- ';
    if (isset($param['dbqueries'])) $html .= 'DB Read/Writes: '.$param['dbqueries'].' -- ';
    if (isset($param['serverload'])) $html .= 'Server Load: '.$param['serverload'].' -- ';
    if (isset($param['sessionsize'])) $html .= 'session size: '.$param['sessionsize'];
    $html .= '</p></div>';

    return $html;
}

function moodle2_tsc_initialise_awesomebar(moodle_page $page) {
    $page->requires->yui_module('moodle-theme_moodle2_tsc-awesomebar', 'M.theme_moodle2_tsc.initAwesomeBar');
}

class moodle2_tsc_expand_navigation extends global_navigation {

    /** @var array */
    protected $expandable = array();

    /**
     * Constructs the navigation for use in AJAX request
     */
    public function __construct($page, $branchtype, $id) {
        $this->page = $page;
        $this->cache = new navigation_cache(NAVIGATION_CACHE_NAME);
        $this->children = new navigation_node_collection();
        $this->branchtype = $branchtype;
        $this->instanceid = $id;
        $this->initialise();
    }
    /**
     * Initialise the navigation given the type and id for the branch to expand.
     *
     * @param int $branchtype One of navigation_node::TYPE_*
     * @param int $id
     * @return array The expandable nodes
     */
    public function initialise() {
        global $CFG, $DB, $SITE;

        if ($this->initialised || during_initial_install()) {
            return $this->expandable;
        }
        $this->initialised = true;

        $this->rootnodes = array();
        $this->rootnodes['site']      = $this->add_course($SITE);
        $this->rootnodes['courses'] = $this->add(get_string('courses'), null, self::TYPE_ROOTNODE, null, 'courses');
        
        $this->expand($this->branchtype, $this->instanceid);
    }

    public function expand($branchtype, $id) {
        global $CFG, $DB, $PAGE;
        static $moodle2_tsc_expanded_courses = array();
        // Branchtype will be one of navigation_node::TYPE_*
        switch ($branchtype) {
            case self::TYPE_CATEGORY :
                $this->load_all_categories($id);
                $limit = 20;
                if (!empty($CFG->navcourselimit)) {
                    $limit = (int)$CFG->navcourselimit;
                }
                $courses = $DB->get_records('course', array('category' => $id), 'sortorder','*', 0, $limit);
                foreach ($courses as $course) {
                    $this->add_course($course);
                }
                break;
            case self::TYPE_COURSE :
                $course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
                try {
                    moodle2_tsc_require_course_login($course);
                    //$this->page = $PAGE;
                    $this->page->set_context(get_context_instance(CONTEXT_COURSE, $course->id));
                    $coursenode = $this->add_course($course);
                    $this->add_course_essentials($coursenode, $course);
                    if ($this->format_display_course_content($course->format) && $PAGE->course->id == $course->id) {
                        $this->load_course_sections($course, $coursenode);
                    }
                } catch(require_login_exception $rle) {
                    $coursenode = $this->add_course($course);
                }
                break;
            case self::TYPE_SECTION :
                $sql = 'SELECT c.*, cs.section AS sectionnumber
                        FROM {course} c
                        LEFT JOIN {course_sections} cs ON cs.course = c.id
                        WHERE cs.id = ?';
                $course = $DB->get_record_sql($sql, array($id), MUST_EXIST);
                try {
                    moodle2_tsc_require_course_login($course);
                    //$this->page = $PAGE;
                    $this->page->set_context(get_context_instance(CONTEXT_COURSE, $course->id));
                    if(!array_key_exists($course->id, $moodle2_tsc_expanded_courses)) {
                        $coursenode = $this->add_course($course);
                        $this->add_course_essentials($coursenode, $course);
                        $moodle2_tsc_expanded_courses[$course->id] = $this->load_course_sections($course, $coursenode);
                    }
                    $sections = $moodle2_tsc_expanded_courses[$course->id];
                    if(method_exists($this,'generate_sections_and_activities')) {
                        list($sectionarray, $activities) = $this->generate_sections_and_activities($course);
                        $activitynodes = $this->load_section_activities($sections[$course->sectionnumber]->sectionnode, $course->sectionnumber, $activities);
                    } else {
                        // pre-Moodle 2.1
                        $activitynodes = $this->load_section_activities($sections[$course->sectionnumber]->sectionnode, $course->sectionnumber, get_fast_modinfo($course));
                    }
                    foreach ($activitynodes as $id=>$node) {
                        // load all section activities now
                        $cm_stub = new stdClass();
                        $cm_stub->id = $id;
                        $this->load_activity($cm_stub, $course, $node);
                    }
                } catch(require_login_exception $rle) {
                    $coursenode = $this->add_course($course);
                }
                break;
            case self::TYPE_ACTIVITY :
                // Now expanded above, as part of the section expansion
                break;
            default:
                throw new Exception('Unknown type');
                return $this->expandable;
        }
        $this->find_expandable($this->expandable);
        return $this->expandable;
    }

    public function get_expandable() {
        return $this->expandable;
    }
}

class moodle2_tsc_dummy_page extends moodle_page {
    /**
     * REALLY Set the main context to which this page belongs.
     * @param object $context a context object, normally obtained with get_context_instance.
     */
    public function set_context($context) {
        if ($context === null) {
            // extremely ugly hack which sets context to some value in order to prevent warnings,
            // use only for core error handling!!!!
            if (!$this->_context) {
                $this->_context = get_context_instance(CONTEXT_SYSTEM);
            }
            return;
        }
        $this->_context = $context;
    }
}