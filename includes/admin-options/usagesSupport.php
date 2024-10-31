<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );
/*
             * send data to the database
             */
register_setting('wip-support-group', 'wip-support-option');


/**
 * section for the settings
 */
add_settings_section(
    'wip-support-section',
    __('Usages & Support', 'pdf-invoice-and-more-for-woocommerce'),
    array($this, 'wip_support_sections'),
    'wip-support');

/**
 * all the fields are going in this place
 */
add_settings_field(
    'wip_support_field',
    __(' ', 'pdf-invoice-and-more-for-woocommerce'),
    array($this, 'wip_support_field'),
    'wip-support',
    'wip-support-section');