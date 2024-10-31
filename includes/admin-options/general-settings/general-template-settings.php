<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );
    /*
     * send data to the database
     */
    register_setting('wip-template-group', 'wip-template-option');


    /**
     * section for the settings
     */
    add_settings_section(
        'wip-template-section',
        __('General Template Settings', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_template_sections'),
        'wip-template');

    /**
     * all the fields are going in this place
     */

    add_settings_field(
        'wip_name_field',
        __('Company Name', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_business_name'),
        'wip-template',
        'wip-template-section');
add_settings_field(
    'wip_disable_logo_field',
    __('Check to Enable the Logo', 'pdf-invoice-and-more-for-woocommerce'),
    array($this, 'wip_disable_logo'),
    'wip-template',
    'wip-template-section');

    add_settings_field(
        'wip_logo_field',
        __('Company Logo', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_business_logo'),
        'wip-template',
        'wip-template-section');

    add_settings_field(
        'wip_invoice_title_field',
        __('Invoice title', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_invoice_title'),
        'wip-template',
        'wip-template-section');

    add_settings_field(
        'wip_color_field',
        __('Download button text color', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_template_color'),
        'wip-template',
        'wip-template-section');

    add_settings_field(
        'wip_color_field2',
        __('Background color of Download button', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_template_color2'),
        'wip-template',
        'wip-template-section');

    add_settings_field(
        'wip_border_field',
        __('Button Border Options', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_template_border'),
        'wip-template',
        'wip-template-section');

    add_settings_field(
        'wip_date_field',
        __('Date Format to show', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_template_date'),
        'wip-template',
        'wip-template-section');

    add_settings_field(
        'wip_paper_size_field',
        __('Paper Size', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_paper_date'),
        'wip-template',
        'wip-template-section');

    add_settings_field(
        'wip_custom_currency_field',
        __('Custom Currency', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_custom_currency_date'),
        'wip-template',
        'wip-template-section');

add_settings_field(
    'wip_enable_fotter_field',
    __('Disable Footer', 'pdf-invoice-and-more-for-woocommerce'),
    array($this, 'wip_enable_fotter'),
    'wip-template',
    'wip-template-section');

    add_settings_field(
        'wip_custom_footer_field',
        __('Footer text (It include terms & condition)', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_custom_footer_date'),
        'wip-template',
        'wip-template-section');



add_settings_field(
        'wip_custom_address_field',
        __('Shop Address', 'pdf-invoice-and-more-for-woocommerce'),
        array($this, 'wip_custom_shop_address'),
        'wip-template',
        'wip-template-section');

   



