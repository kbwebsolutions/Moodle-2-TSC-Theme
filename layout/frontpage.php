<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
//$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
//$custommenu = $OUTPUT->custom_menu();
//$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$bodyclasses = array();
/*
if ($hassidepre && !$hassidepost) {
    $bodyclasses[] = 'side-pre-only';
} else if ($hassidepost && !$hassidepre) {
    $bodyclasses[] = 'side-post-only';
} else if (!$hassidepost && !$hassidepre) {
    $bodyclasses[] = 'content-only';
}

if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}
*/
echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <meta name="description" content="<?php p(strip_tags(format_text($SITE->summary, FORMAT_HTML))) ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>
<?php if (isloggedin()){ ?>

<?php } ?>

<div id="page">

<!-- START OF HEADER -->

	<div id="wrapper" class="clearfix">

    	<div id="page-header">
			<div id="page-header-wrapper" class="clearfix">
			<h2>Sheffield College VLE</h2>
	        
	    	    
	    	</div>
	    </div>

        <div class="clearer"></div> <!-- temporarily added on 06/25/10 -->

<!-- END OF HEADER -->

<!-- START OF CONTENT -->

		<div id="page-content-wrapper" class="clearfix">
    		<div id="page-content">
	    	    <div id="region-main-box">
    	    	    <div id="region-post-box">

        	    	    <div id="region-main-wrap">
            	    	    <div id="region-main">
                	    	    <div class="region-content">
                	    	    
                    	    	    <?php echo $OUTPUT->main_content() ?>
	                        	</div>
		                    </div>
    		            </div>

        		        <?php if ($hassidepre) { ?>
            		    <div id="region-pre">
                		    <div class="region-content">
                    		    <?php echo $OUTPUT->blocks_for_region('side-pre') ?>
		                    </div>
    		            </div>
        		        <?php } ?>
<!--
	        	        <?php /* if ($hassidepost) { ?>
    	        	    <div id="region-post">
        	        	    <div class="region-content">
            	        	    <?php echo $OUTPUT->blocks_for_region('side-post') ?>
	                	    </div>
		                </div>
    		            <?php } */?>
-->
	        	    </div>
	    	    </div>
		    </div>
		</div>

<!-- END OF CONTENT -->

<!-- START OF FOOTER -->

	    <div id="page-footer">
    	    <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
<div class="collegeAddress"><h4>The Sheffield College</h4><p>Granville Road<br />Sheffield. S2 2RL</p><p>Tel: 0114 2602600</p></div>
	        <?php
		        echo $OUTPUT->standard_footer_html();
	        ?>
	        
    	</div>
    	
<!-- END OF FOOTER -->

	</div> <!-- END #wrapper -->

</div><!-- END #page -->

<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>