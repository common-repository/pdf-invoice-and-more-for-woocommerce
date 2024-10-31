<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );
    /**
     * register option for saving data to database
     */
    register_setting('wip-general-group', 'wip-general-option');


    /**
     * section for the settings
     */
    add_settings_section(
        'wip-general-section',
        __('General Settings', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_general_sections'),
        'wip-general');

    /**
     * all the fields are going in this place
     */

    add_settings_field(
        'wip_enable_field',
        __('Enable', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_enable'),
        'wip-general',
        'wip-general-section');

    add_settings_field(
        'wip_email_field',
        __('Email Option', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_email'),
        'wip-general',
        'wip-general-section');

    add_settings_field(
        'wip_P_P_P_field',
        __('Invoice per page to show', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_p_p_p_email'),
        'wip-general',
        'wip-general-section');



    add_settings_field(
        'wip_email_details_field',
        __('Email body with attachment', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_email_details'),
        'wip-general',
        'wip-general-section');

    add_settings_field(
        'wip_view_field',
        __('View or Download', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_view_details'),
        'wip-general',
        'wip-general-section');

