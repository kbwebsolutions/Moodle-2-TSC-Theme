<?php

class theme_moodle2_tsc_core_renderer extends core_renderer {

    /**
     * Outputs the page's footer
     * @return string HTML fragment
     */
    public function footer() {
        global $CFG, $DB;

        $output = $this->container_end_all(true);

        $footer = $this->opencontainers->pop('header/footer');

        if (debugging() and $DB and $DB->is_transaction_started()) {
            // TODO: MDL-20625 print warning - transaction will be rolled back
        }

        // Provide some performance info if required
        $performanceinfo = '';
        if (defined('MDL_PERF') || (!empty($CFG->perfdebug) and $CFG->perfdebug > 7)) {
            $perf = get_performance_info();
            if (defined('MDL_PERFTOLOG') && !function_exists('register_shutdown_function')) {
                error_log("PERF: " . $perf['txt']);
            }
            if (defined('MDL_PERFTOFOOT') || debugging() || $CFG->perfdebug > 7) {
                $performanceinfo = moodle2_tsc_performance_output($perf);
            }
        }

        $perftoken = (property_exists($this, "unique_performance_info_token"))?$this->unique_performance_info_token:self::PERFORMANCE_INFO_TOKEN;
        $endhtmltoken = (property_exists($this, "unique_end_html_token"))?$this->unique_end_html_token:self::END_HTML_TOKEN;

        $footer = str_replace($perftoken, $performanceinfo, $footer);

        $footer = str_replace($endhtmltoken, $this->page->requires->get_end_code(), $footer);

        $this->page->set_state(moodle_page::STATE_DONE);

        return $output . $footer;
    }



    // Copied from core_renderer with one minor change - changed $this->output->render() call to $this->render()
    protected function render_navigation_node(navigation_node $item) {
        $content = $item->get_content();
        $title = $item->get_title();
        if ($item->icon instanceof renderable && !$item->hideicon) {
            $icon = $this->render($item->icon);
            $content = $icon.$content; // use CSS for spacing of icons
        }
        if ($item->helpbutton !== null) {
            $content = trim($item->helpbutton).html_writer::tag('span', $content, array('class'=>'clearhelpbutton'));
        }
        if ($content === '') {
            return '';
        }
        if ($item->action instanceof action_link) {
            //TODO: to be replaced with something else
            $link = $item->action;
            if ($item->hidden) {
                $link->add_class('dimmed');
            }
            $content = $this->render($link);
        } else if ($item->action instanceof moodle_url) {
            $attributes = array();
            if ($title !== '') {
                $attributes['title'] = $title;
            }
            if ($item->hidden) {
                $attributes['class'] = 'dimmed_text';
            }
            $content = html_writer::link($item->action, $content, $attributes);

        } else if (is_string($item->action) || empty($item->action)) {
            $attributes = array();
            if ($title !== '') {
                $attributes['title'] = $title;
            }
            if ($item->hidden) {
                $attributes['class'] = 'dimmed_text';
            }
            $content = html_writer::tag('span', $content, $attributes);
        }
        return $content;
    }

    /**
     * blocks_for_region() overrides core_renderer::blocks_for_region()
     *  in moodlelib.php. Returns a string
     * values ready for use.
     *
     * @return string
     */
    public function blocks_for_region($region) {
        $blockcontents = $this->page->blocks->get_content_for_region($region, $this);
        $output = '';
        foreach ($blockcontents as $bc) {
            if ($bc instanceof block_contents) {
                if (!($bc->attributes['class'] == 'block_settings  block' && $this->page->theme->settings->hidesettingsblock)
                        && !($bc->attributes['class'] == 'block_navigation  block' && $this->page->theme->settings->hidenavigationblock)) {
                    $output .= $this->block($bc, $region);
                }
            } else if ($bc instanceof block_move_target) {
                $output .= $this->block_move_target($bc);
            } else {
                throw new coding_exception('Unexpected type of thing (' . get_class($bc) . ') found in list of block contents.');
            }
        }
        return $output;
    }

}

class theme_moodle2_tsc_topsettings_renderer extends plugin_renderer_base {
	
	// Adds all dropdown menus to Awesomebar
    public function settings_tree(settings_navigation $navigation) {
        global $CFG;
        $content = $this->navigation_node($navigation, array('class' => 'dropdown  dropdown-horizontal'));
        return $content;
    }
    public function settings_search_box() {
        global $CFG;
        $content = "";
        if (has_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM))) {
        	$querytype = "query";
        	$content .= $this->search_form(new moodle_url("$CFG->wwwroot/$CFG->admin/search.php"), optional_param('query', '', PARAM_RAW), $querytype);
        }
        
        $content .= html_writer::empty_tag('br', array('clear' => 'all'));
                
        return $content;
    }

    public function navigation_tree(global_navigation $navigation) {
        global $CFG;
        $content = html_writer::start_tag('ul', array('id' => 'awesomeHomeMenu', 'class' => 'dropdown  dropdown-horizontal'));
       
        $content .= html_writer::start_tag('li');
        $content .= html_writer::start_tag('span', array('id' =>'awesomeNavMenu'));
        $content .= html_writer::empty_tag('img', array('alt' => '', 'src' =>$this->pix_url('user_silhouette', 'theme')));
        $content .= html_writer::end_tag('span');
        if (!has_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM))) {
        	$content .= $this->navigation_node($navigation, array());
        }
        $content .= html_writer::end_tag('li');
        
        $content .= html_writer::end_tag('ul');
        
        

        return $content;
    }

    protected function navigation_node(navigation_node $node, $attrs=array()) {
        global $PAGE;
        $items = $node->children;

        // exit if empty, we don't want an empty ul element
        if ($items->count() == 0) {
            return '';
        }

        // array of nested li elements
        $lis = array();
        $dummypage = new moodle2_tsc_dummy_page();
        $dummypage->set_context(get_context_instance(CONTEXT_SYSTEM));
        $dummypage->set_url($PAGE->url);
        foreach ($items as $item) {
            if (!$item->display) {
                continue;
            }

            $isbranch = ($item->children->count() > 0 || $item->nodetype == navigation_node::NODETYPE_BRANCH);
            $hasicon = (!$isbranch && $item->icon instanceof renderable);

            if ($isbranch) {
                $item->hideicon = true;
            }

            $content = $this->output->render($item);
            if(substr($item->id, 0, 17)=='expandable_branch' && $item->children->count()==0) {
                // Navigation block does this via AJAX - we'll merge it in directly instead
                $subnav = new moodle2_tsc_expand_navigation($dummypage, $item->type, $item->key);
                if (!isloggedin() || isguestuser()) {
                    $subnav->set_expansion_limit(navigation_node::TYPE_COURSE);
                }
                $branch = $subnav->find($item->key, $item->type);
                if($branch!==false) $content .= $this->navigation_node($branch);
            } else {
                $content .= $this->navigation_node($item);
            }


            if($isbranch && !(is_string($item->action) || empty($item->action))) {
                $content = html_writer::tag('li', $content, array('class' => 'clickable-with-children'));
            } else {
                $content = html_writer::tag('li', $content);
            }
            $lis[] = $content;
        }

        if (count($lis)) {
            return html_writer::nonempty_tag('ul', implode("\n", $lis), $attrs);
        } else {
            return '';
        }
    }

    
    // Creates the Search box in awesomebar
    public function search_form(moodle_url $formtarget, $searchvalue, $querytype) {
        global $CFG;

        if (empty($searchvalue)) {
            $searchvalue = 'Search Settings..';
        }
        $content = html_writer::start_tag('form', array('class' => 'topadminsearchform', 'method' => 'get', 'action' => $formtarget));
        $content .= html_writer::start_tag('div', array('class' => 'search-box'));
        $content .= html_writer::tag('label', s(get_string('searchinsettings', 'admin')), array('for' => 'adminsearchquery', 'class' => 'accesshide'));
        $content .= html_writer::empty_tag('input', array('id' => 'topadminsearchquery', 'type' => 'text', 'name' => $querytype, 'value' => s($searchvalue),
                    'onfocus' => "if(this.value == 'Search Settings..') {this.value = '';}",
                    'onblur' => "if (this.value == '') {this.value = 'Search..';}"));
        //$content .= html_writer::empty_tag('input', array('class'=>'search-go','type'=>'submit', 'value'=>''));
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('form');

        return $content;
    }
    
    public function onlineusers() {
	    global $CFG, $DB;
	    
	    $timetoshowusers = 300; //Seconds default - The amount of time to query

        $timefrom = 100 * floor((time() - $timetoshowusers) / 100); // Round to nearest 100 seconds for better query cache
        $usercount = $DB->count_records_select('user', "lastaccess > $timefrom AND id != 1");

        $content = '<span class="onlineusers"><a href="'.$CFG->wwwroot.'/report/loglive/index.php">Users currently online: ' . $usercount .'<a/></span>';
        return $content;
    }


    public function course_search() {
        global $CFG;
        $content = "";
        if (has_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM))) {
        	$querytype = "search";
        	$content .= $this->search_form(new moodle_url("$CFG->wwwroot/course/search.php"), optional_param('search', 'Search Courses...', PARAM_RAW), $querytype);
        }
        
        //$content .= html_writer::empty_tag('br', array('clear' => 'all'));
                
        return $content;
    }


}

?>
