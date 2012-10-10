<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$bodyclasses = array();

if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page">

<!-- START OF HEADER -->

    <?php if ($hasheading || $hasnavbar) { ?>
    <div id="wrapper" class="clearfix">

        <div id="page-header">
            <div id="page-header-wrapper" class="clearfix">
                   <?php if ($hasheading) { ?>
                   <h2><img id="site_logo" src="<?php echo $OUTPUT->pix_url('sheffmood_logo', 'theme'); ?>" alt="Moodle and the Sheffield College" /></h2>
                
                
                <?php } ?>
            </div>
        </div>
	<?php if ($hascustommenu) { ?>
 	<div id="custommenu"><?php echo $custommenu; ?><?php echo $OUTPUT->login_info(); ?></div>
	<?php } ?>
        <?php if ($hasnavbar) { ?>
            <div class="navbar clearfix">
                <div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
                <div class="navbutton"> <?php echo $PAGE->button; ?></div>
            </div>
        <?php } ?>

<?php } ?>

<!-- END OF HEADER -->

<!-- START OF CONTENT -->

        <div id="page-content-wrapper" class="clearfix">
            <div id="page-content">
            
            

                                    <?php echo $OUTPUT->main_content() ?>

            </div>
        </div>

<!-- END OF CONTENT -->

<!-- START OF FOOTER -->

        <?php if ($hasfooter) { ?>
        <div id="page-footer" class="clearfix">
            <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
            <div class="collegeAddress"><h4>The Sheffield College</h4><p>Granville Road<br />Sheffield. S2 2RL</p><p>Tel: 0114 2602600</p></div>
            <?php
                echo $OUTPUT->standard_footer_html();
            ?>
        </div>
        <?php } ?>

    <?php if ($hasheading || $hasnavbar) { ?>
        </div> <!-- END #wrapper -->
    <?php } ?>

</div> <!-- END #page -->

<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>