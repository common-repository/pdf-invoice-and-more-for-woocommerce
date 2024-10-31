<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );
    /*
     * send data to the database
     */
    register_setting('wip-packing-group', 'wip-packing-option');


    /**
     * section for the settings
     */
    add_settings_section(
        'wip-packing-section',
        __('Packing Slip', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_packing_sections'),
        'wip-packing');

    /**
     * all the fields are going in this place
     */

    add_settings_field(
    'wip_enable_field',
    __('Enable Packing Slip', 'pdf-invoice-and-more-for-woocommerce'),
    array($this, 'wip_packing_enable_name'),
    'wip-packing',
    'wip-packing-section');


add_settings_field(
    'wip_enable_packing_logo_field',
    __('Enable Logo in Packing Slip', 'pdf-invoice-and-more-for-woocommerce'),
    array($this, 'wip_packing_logo_enable_name'),
    'wip-packing',
    'wip-packing-section');




    add_settings_field(
        'wip_font_size_field',
        __('Font Size', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_packing_font_size_field'),
        'wip-packing',
        'wip-packing-section');

    add_settings_field(
        'wip_packing_title_field',
        __('Packing Slip Title', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_packing_packing_title_field'),
        'wip-packing',
        'wip-packing-section');