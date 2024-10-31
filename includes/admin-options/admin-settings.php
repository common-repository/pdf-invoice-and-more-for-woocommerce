<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );
?>
<div class="wrap">
    <h1>PDF Setting Options</h1>

    <h2 class="nav-tab-wrapper"><?php
        $tab = (isset($_GET['tab'])) ? $_GET['tab'] : '';
        $page = (isset($_GET['page'])) ? $_GET['page'] : '';

        ?><a href="?page=wip-pdf-setting&tab=general"
             class="nav-tab <?php if (empty($tab) && $page == 'wip-pdf-setting' || $tab == 'general') {
                 echo 'nav-tab-active';
             } ?>">General Settings</a>
        <a href="?page=wip-pdf-setting&tab=template" class="nav-tab <?php if ($tab == 'template') {
            echo 'nav-tab-active';
        } ?>">General Invoice settings</a>
        <a href="?page=wip-pdf-setting&tab=packing" class="nav-tab <?php if ($tab == 'packing') {
            echo 'nav-tab-active';
        } ?>">Packing Slip</a>
        <a href="?page=wip-pdf-setting&tab=support" class="nav-tab <?php if ($tab == 'support') {
            echo 'nav-tab-active';
        } ?>">Usages & Support</a>

    </h2>


    <form method="post" action="options.php" enctype="multipart/form-data">

        <?php
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'general') {
                // display all sections for general-options page
                settings_fields('wip-general-group');

                do_settings_sections('wip-general');
            }
            if ($_GET['tab'] == 'template') {
                // display all sections for template-options page
                settings_fields('wip-template-group');

                do_settings_sections('wip-template');
            }
            if ($_GET['tab'] == 'packing') {
                // display all sections for packing-options page
                settings_fields('wip-packing-group');

                do_settings_sections('wip-packing');
            }

            if ($_GET['tab'] == 'support') {
                // display all sections for delivery-options page
                settings_fields('wip-support-group');

                do_settings_sections('wip-support');
            }

        } else {
            // display all sections for general-options page
            settings_fields('wip-general-group');

            do_settings_sections('wip-general');
        }


        submit_button();
        ?>
    </form>
</div>
