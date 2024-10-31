<?php
    defined('ABSPATH') || die( 'Direct access is not allowed.' );

    $this->general_options = get_option('wip-general-option');
    $this->template_options = get_option('wip-template-option');
    $this->packing_options = get_option('wip-packing-option');


    /*
     * saving field value for general options
     */
    $this->enabled = (!empty($this->general_options['enabled'])) ? $this->general_options['enabled'] : '';
    $this->email_permission = (!empty($this->general_options['email'])) ? $this->general_options['email'] : '';
    $this->invoice_per_page = (!empty($this->general_options['invoice_per_page'])) ? $this->general_options['invoice_per_page'] : '10';
    $this->email_det = (!empty($this->general_options['email_det'])) ? $this->general_options['email_det'] : 'This is the email that contain your invoice. Please check the attachment bellow.';
    $this->view_det = (!empty($this->general_options['view_det'])) ? $this->general_options['view_det'] : 'download';


    /*
     * saving fields for general template option
     */
    $this->business_name = (!empty($this->template_options['business_name'])) ? $this->template_options['business_name'] : get_bloginfo('name');
    $this->disable_logo = (!empty($this->template_options['disable_logo'])) ? $this->template_options['disable_logo'] : '';
    $this->business_logo = (!empty($this->template_options['business_logo'])) ? $this->template_options['business_logo'] : PDFIM_DIR.'/fpdf/icons/def-logo.jpg';
    $this->paid_Mark = (!empty($this->template_options['paid_Mark'])) ? $this->template_options['paid_Mark'] : PDFIM_DIR.'/fpdf/icons/def-mark.png';
    $this->invoice_title = (!empty($this->template_options['invoice_title'])) ? $this->template_options['invoice_title'] : 'Invoice';
    $this->color_back = (!empty($this->template_options['color'])) ? $this->template_options['color'] : '#000000';
    $this->color_back2 = (!empty($this->template_options['color2'])) ? $this->template_options['color2'] : '#dddddd';
    $this->border_enabled = (!empty($this->template_options['border_enabled'])) ? $this->template_options['border_enabled'] : '';
    $this->border = (!empty($this->template_options['border'])) ? $this->template_options['border'] : '';
    $this->date_format = (!empty($this->template_options['date_format'])) ? $this->template_options['date_format'] : 'Y/m/d';
    $this->paper_size = (!empty($this->template_options['paper_size'])) ? $this->template_options['paper_size'] : 'a4';
    $this->custom_currency = (!empty($this->template_options['custom_currency'])) ? $this->template_options['custom_currency'] : '$';
    $this->enable_fotter = (!empty($this->template_options['enable_fotter'])) ? $this->template_options['enable_fotter'] : '';
    $this->footer_text = (!empty($this->template_options['footer_text'])) ? $this->template_options['footer_text'] : 'Thanks for being with us';
    $this->shop_address = (!empty($this->template_options['shop_address'])) ? $this->template_options['shop_address'] : '';
    $this->mark_as_paid = (!empty($this->template_options['mark_as_paid'])) ? $this->template_options['mark_as_paid'] : '';

    /*
     * saving data for packing slip options
     */
    $this->packing_enabled = (!empty($this->packing_options['packing_enabled'])) ? $this->packing_options['packing_enabled'] : '';
    $this->packing_enable_for_customer = (!empty($this->packing_options['packing_enable_for_customer'])) ? $this->packing_options['packing_enable_for_customer'] : '';
    $this->packing_logo_enable = (!empty($this->packing_options['packing_logo_enable'])) ? $this->packing_options['packing_logo_enable'] : '';
    $this->color_packing_main = (!empty($this->packing_options['color_packing_main'])) ? $this->packing_options['color_packing_main'] : '#ffffff';
    $this->color_packing_main2 = (!empty($this->packing_options['color_packing_main2'])) ? $this->packing_options['color_packing_main2'] : '#dddddd';
    $this->packing_font_size = (!empty($this->packing_options['packing_font_size'])) ? $this->packing_options['packing_font_size'] : '11';
    $this->packing_title = (!empty($this->packing_options['packing_title'])) ? $this->packing_options['packing_title'] : 'Cover Slip';