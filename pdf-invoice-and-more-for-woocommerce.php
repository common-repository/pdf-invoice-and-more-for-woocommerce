<?php
/**
 * Plugin Name: WooCommerce PDF Invoice and More
 * Plugin URI: https://aazztech.com/product/pdf-invoice-and-more-pro-for-woocommerce/
 * Description: It generates PDF invoices, packing slips etc for WooCommerce store.
 * Version: 1.0.2
 * Author: Aazztech
 * Author URI: http://www.aazztech.com
 * License: GPLv2 or later
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: pdf-invoice-and-more-for-woocommerce
 * WC requires at least: 3.0
 * WC tested up to: 3.5.0
 */
// prevent direct access to the file
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
/*if ( ! in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ) {*/
/**
* Main PDF_IM_WooCommerce Class.
*
* @since 1.0
*/
    final class PDF_IM_WooCommerce{
        /*
         * *Singletone **************************************************
         *
         */
        private static $instance;

        public static $endpoint = 'pdf-invoice';

        public $output_pdf;
        public $output_pdf_packing;

        public $pdf_type_;
        public $pdf_type_admin;
        public $all_pdf_enabled;
        public $check_email_permission;



        /*
         * Define the version of the plugin
         */
        public $version = '1.0';

        /*
         * Get the plugin name from the basename
         */
        public $plugin_basename;







        public static function instance(){
            if ( ! isset( self::$instance ) && ! (self::$instance instanceof PDF_IM_WooCommerce) ){
                self::$instance = new PDF_IM_WooCommerce;

                self::$instance->_define_constant();//define necessary constance
                self::$instance->plugin_basename = plugin_basename(__FILE__); //get the plugin basename



                    add_action( 'plugins_loaded', array(self::$instance, 'wip_load_classes' ), 9 );

                    add_action('admin_menu', array(self::$instance, 'wip_pdf_submenu_callback'));
                    add_action('admin_init', array(self::$instance, 'wip_general_options'));
                    add_action('admin_init', array(self::$instance, 'wip_template_options'));
                    add_action('admin_init', array(self::$instance, 'wip_packing_options'));
                    add_action('admin_init', array(self::$instance, 'wip_support_options'));



                self::$instance->wip_all_data();//store att the data from the database
                add_action('admin_enqueue_scripts', array(self::$instance, 'style_for_admin')); //adding essential scripts and style
                add_action('wp_enqueue_scripts', array(self::$instance, 'style_for_front')); //adding essential scripts and style for front
                add_action('init', array(self::$instance, 'all_php_library')); //pdf generator fpdf library

                /*
                  * add custom menu in my account page
                  */
                // Actions used to insert a new endpoint in the WordPress.
                add_action('init', array(self::$instance, 'add_endpoints'));
                add_filter('query_vars', array(self::$instance, 'add_query_vars'), 0); //return the query var
                add_filter('the_title', array(self::$instance, 'endpoint_title'));// Change the My Accout page title.
                add_filter('woocommerce_account_menu_items', array(self::$instance, 'new_menu_items'));// Insering your new tab/page into the My Account page.
                add_action('woocommerce_account_' . self::$endpoint . '_endpoint', array(self::$instance, 'endpoint_content'));
                add_filter('woocommerce_account_menu_items', array(self::$instance, 'new_menu_items_order'));//change the menu order
                //add_action( 'woocommerce_admin_order_actions_end', array(self::$instance, 'wip_add_order_meta_box_action') );//admin order
                add_action('woocommerce_admin_order_actions_end', array(self::$instance, 'wip_add_order_meta_box_action') );
                add_action( 'admin_head', array(self::$instance,'add_custom_order_actions_button_css' ) );

                // out the pdf when a user clicks on the view button in the admin panel

                    add_action('admin_init',array(self::$instance,'wip_process_get_and_output_pdf' ) );


                /* Add custom link */
                add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(self::$instance,'wip_plugin_action_links' ) );
                add_action('plugin_loaded', array(self::$instance, 'wip_make_it_translatable') );



            }
            return self::$instance;
        }


        public function wip_make_it_translatable(){
            load_plugin_textdomain('pdf-invoice-and-more-for-woocommerce',false,dirname(__FILE).'/languages');

        }




        /**
         * Instantiate classes when woocommerce is activated
         */
        public function wip_load_classes() {
            if ( $this->is_woocommerce_activated() === false ) {
                add_action( 'admin_notices', array ( $this, 'need_woocommerce' ) );
                return;
            }

            if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
                add_action( 'admin_notices', array ( $this, 'required_php_version' ) );
                return;
            }


        }


        public function is_woocommerce_activated() {
            $all_plugins = get_option( 'active_plugins', array() );
            $site_plugins = is_multisite() ? (array) maybe_unserialize( get_site_option('active_sitewide_plugins' ) ) : array();

            if ( in_array( 'woocommerce/woocommerce.php', $all_plugins ) || isset( $site_plugins['woocommerce/woocommerce.php'] ) ) {
                return true;
            } else {
                return false;
            }
        }


        /**
         * WooCommerce not active notice.
         *
         * @return string Fallack notice.
         */

        public function need_woocommerce() {
            $error = sprintf( __( 'PDF Invoice and More for WooCommerce requires %sWooCommerce%s to be installed & activated!' , 'pdf-invoice-and-more-for-woocommerce' ), '<a href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>' );

            $message = '<div class="error notice is-dismissible"><p>' . $error . '</p></div>';
            deactivate_plugins(plugin_basename( __FILE__ ));

            echo $message;
        }



        /**
         * PHP version requirement notice
         */

        public function required_php_version() {
            $error = __( 'PDF Invoice and More for WooCommerce requires PHP 5.4 or higher (5.6 or higher recommended).', 'pdf-invoice-and-more-for-woocommerce' );
            $how_to_update = __( 'Way to update your PHP version', 'pdf-invoice-and-more-for-woocommerce' );
            $message = sprintf('<div class="error"><p>%s</p><p><a href="%s">%s</a></p></div>', $error, 'https://docs.woocommerce.com/document/how-to-update-your-php-version/', $how_to_update);

            echo $message;
        }






        public function wip_plugin_action_links($links){
            $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=wip-pdf-setting') ) .'">Settings</a>';
            $links[] = '<a href="https://aazztech.com/product/pdf-invoice-and-more-pro-for-woocommerce" target="_blank">Pro Version</a>';
            return $links;
        }


        /*
       * Start support sections
       */


        public function wip_support_options(){

                require_once PDFIM_DIR. 'includes/admin-options/usagesSupport.php';//It contains the level for the settings input


        }


        public function wip_support_sections(){

        }


        public function wip_support_field(){
            ?>

            <div class="wrap">
            <div class="wip_dflex">
                <div class="wrap wrap_left">
                    <div class="postbox wpcs-admin-extra">
                        <div class="wip_custom">

                            <h3>Usage</h3>
                            <h4>Option tricks for front</h4>
                            <p>Using PDF Invoice and More for WooCommerce is very convenient to use. Here is the supper easy steps to configure the plugin</p>
                            <p>1. After activating the plugin just save once to you permalink page without modifying nothing.</p>
                            <p>2. Now, your customer will see the one more side menu named 'PDF Invoice' into their my-account page.</p>
                            <p>3. Customer can easily download the invoice with all order details</p>
                            <p>4. As an admin one can disable the invoice download option from my-account page.</p>

                            <h4>Option tricks for backend</h4>
                            <p>4. Enable the action column from admin order page screen option .</p>
                            <p>4. Now you can download invoice or packing slip or send email to your customer with nice template attachment.</p>
                            <br>

                            <h3>Support Forum</h3>
                            <p> If you need any helps, please don't hesitate to post it on <a href="https://wordpress.org/support/plugin/pdf-invoice-and-more-for-woocommerce" target="_blank"> wordpress.org</a> or <a href="https://aazztech.com/product/pdf-invoice-and-more-pro-for-woocommerce/" target="_blank"> aazztech.com</a> support forum.</p>
                            <br>

                            <h3>More features</h3>
                            <p></p><p>Upgrading to the <a href="https://aazztech.com/product/pdf-invoice-and-more-pro-for-woocommerce" target="_blank">Premium Version</a> would unlock more amazing features of the plugin.</p><p></p>
                            <p>
                                <a class="button button-primary" href="https://aazztech.com/demos/plugins/pdf-invoice-and-more-pro-for-woocommerce-screenshots" target="_blank">Screenshots</a>
                                

                                <a class="button button-primary" href="https://aazztech.com/product/pdf-invoice-and-more-pro-for-woocommerce/" target="_blank">Upgrade to Pro</a>
                            </p>

                        </div>
                    </div>
                </div>

        <?php if ($_GET['page'] == 'wip-pdf-setting' && $_GET['tab'] == 'support'){
            ?>
            <style>
                .form-table tbody tr th{
                    display: table-column;
                }
            </style>
                <?php
        }
            ?>
                <div class="wrap wrap_right">

                    <div class="wip_promotion wip_dflex">
                        <div class="promo_img">
                            <p>Do you need auto order printing feature?</p>
                            <img src="<?php echo PDFIM_URI.'asset/print-icon.png'?>" alt="WooCommerce Auto Order Print">
                            <p class="text-center"><a target="_blank" href="https://aazztech.com/product/woocommerce-auto-order-print" class="button button-primary">Click here</a></p>
                        </div>
                    </div>



                    <div class="postbox wpcs-admin-extra adl-upgrade-content-wrapper-right">

                        <h3>Need more features?</h3>
                        <ul class="adl_pro_features">
                            <li>Generate, Download and Print PDF invoice with QR Code</li>
                            <li>Generate, download or print PDFs for shipping level, delivery note and dispatch level</li>
                            <li>Download PDF invoice with 3 different pre-made templates</li>
                            <li>Match the template structure with Latest Accounting Standard</li>
                            <li>Let customer allows to access their Invoice on Thankyou Page</li>
                            <li>Personalize the download button interface on Thankyou Page</li>
                            <li>Invoice mark as paid with custom Paid Mark</li>
                            <li>Generate invoice number as order number or use custom number</li>
                            <li>Automatically add bank details if the order is payable by Direct Bank Transfer</li>
                            <li>Add another footer section containing terms & condition or return policy for your customer</li>
                            <li>Fully integrated with WooCommerce</li>
                            <li>Customizable font color for every PDFs</li>
                            <li>Use custom paper size that fits to your printer</li>
                            <li>QR Code with invoice summary</li>
                            <li>One click PDFs Download</li>
                            <li>QR Code with invoice summary</li>
                            <li>Very light weight PDF generator for WooCommerce</li>
                            <li>And much more feature to increase your store efficiency</li>

                        </ul>
                        <p class="text-center"><a target="_blank" href="https://aazztech.com/product/pdf-invoice-and-more-pro-for-woocommerce/" class="button button-primary">Click here to know more</a></p>
                        <p class="text-center"><a target="_blank" href="https://aazztech.com/product/pdf-invoice-and-more-pro-for-woocommerce/" class="button button-primary">Get the Pro Version Now!</a></p>
                    </div>
                </div>


            </div>
            </div>


            <?php
        }


        /*
         * end support sections
         */




        public function wip_process_get_and_output_pdf()
        {

            // Prepare all the vars from the URL
            //$action = !empty($_GET['action']) ? sanitize_text_field($_GET['action']) : false;
            $pdf_type = !empty($_GET['pdf_type']) ? sanitize_text_field($_GET['pdf_type']) : false;
            $order_id = !empty($_GET['order_id']) ? wc_sanitize_order_id($_GET['order_id']) : false;




            // run the following codes if we have got all the data from the url


            // do whatever you want here. you have got your data.


            //final execution of the pdf
            if ($pdf_type && $pdf_type == 'Invoice') {
                $order = new WC_Order($order_id);
                //get the date of order for pdf
                $time = ($order->get_date_created());
                $old_date_timestamp = strtotime($time);
                $option_format = $this->date_format;

                $new_date = date($option_format, $old_date_timestamp);
                $this->output_pdf($order_id, $new_date);

            }elseif ($pdf_type == 'Packing') {
                $order = new WC_Order($order_id);
                //get the date of order for pdf
                $time = ($order->get_date_created());
                $old_date_timestamp = strtotime($time);
                $option_format = $this->date_format;
                $new_date = date($option_format, $old_date_timestamp);

                $this->output_pdf_packing($order_id, $new_date);

            }elseif ( $pdf_type == 'SendEmail' ){
                $order_id = !empty($_GET['order_id']) ? sanitize_text_field($_GET['order_id']) : false;
                $order = new WC_Order($order_id);
                $time = ($order->get_date_created());
                $old_date_timestamp = strtotime($time);
                $option_format = $this->date_format;
                $new_date = date($option_format, $old_date_timestamp);
                $this->output_pdf($order_id, $new_date);

            }
        }


        public function check_email_permission(){
            if ( $this->email_permission == 'on'){
                return true;
            }
        }
        /*
         * get the all enabled pdf type
         * @return array
         */
        public function all_pdf_enabled(){

            $all_enabled = array();

            if ($this->enabled == 'on'){
                $all_enabled[] = 'Invoice';

            }if ($this->packing_enabled == 'on'){
                $all_enabled[] = 'Packing';

            }if($this->email_permission == 'on'){
                $all_enabled[] = 'SendEmail';
            }

            return $all_enabled;
        }


        /*
         * get the pdf type want to print from the front end
         */
        public function pdf_type_(){
            if ($_GET['pdf_type'] == 'Packing'){
                return "Packing Slip";
            }
            elseif ($_GET['pdf_type'] == 'Invoice'){
                return 'Invoice';
            }
            elseif ($_POST['invoice']){
               return 'Invoice';
            }else{
                return "Packing Slip";
            }
        }

        public function output_pdf_packing($order_id_pk, $wip_order_date_pk)
        {
            if(!@include("packing1.php")){
                require_once PDFIM_DIR. 'templates/packing/packing1.php';
            }
        }

        public function output_pdf($order_id, $wip_order_date)
        {
            if(!@include("invoice1.php")){
                require_once PDFIM_DIR. 'templates/invoice/invoice1.php';
            }
        }



        public function add_custom_order_actions_button_css() {
            echo '<style>.view.tracking::after { font-family: woocommerce; content: "\e005" !important; }</style>';
        }


        /**
         * @param WC_Order $order
         * =
         */
        public function wip_add_order_meta_box_action($order){
            if(!@include("pdf-admin-option.php")){
                include PDFIM_DIR. 'includes/pdf-admin-option.php';
            }
        }


        public function endpoint_content(){
            if(!@include("pdf-front-option.php")){
                require_once PDFIM_DIR. 'includes/pdf-front-option.php';
            }
        }






        /*
         * Rearrange the endpoint order
         */

        public
        function new_menu_items_order()
        {
            $menuOrder = array(
                'dashboard' => __('Dashboard', 'pdf-invoice-and-more-for-woocommerce'),
                'orders' => __('Orders', 'pdf-invoice-and-more-for-woocommerce'),
                'pdf-invoice' => __('PDF Invoice', 'pdf-invoice-and-more-for-woocommerce'),
                'downloads' => __('Download', 'pdf-invoice-and-more-for-woocommerce'),
                'edit-address' => __('Addresses', 'pdf-invoice-and-more-for-woocommerce'),
                'edit-account' => __('Account Details', 'pdf-invoice-and-more-for-woocommerce'),
                'customer-logout' => __('Logout', 'pdf-invoice-and-more-for-woocommerce'),
            );
            return $menuOrder;
        }


        /**
         * Insert the new endpoint into the My Account menu.
         *
         * @param array $items
         * @return array
         */
        public
        function new_menu_items($items)
        {
            // Remove the logout menu item.
            $logout = $items['customer-logout'];
            unset($items['customer-logout']);
            // Insert your custom endpoint.
            $items[self::$endpoint] = _e('PDF Invoice', 'pdf-invoice-and-more-for-woocommerce');
            // Insert back the logout item.
            $items['customer-logout'] = $logout;
            return $items;
        }



        /**
         * Set endpoint title.
         *
         * @param string $title
         * @return string
         */
        public
        function endpoint_title($title)
        {
            global $wp_query;
            $is_endpoint = isset($wp_query->query_vars[self::$endpoint]);
            if ($is_endpoint && !is_admin() && is_main_query() && in_the_loop() && is_account_page()) {
                // New page title.
                $title = _e('PDF Invoice', 'pdf-invoice-and-more-for-woocommerce');
                remove_filter('the_title', array($this, 'endpoint_title'));
            }
            return $title;
        }


        /**
         * Add new query var.
         *
         * @param array $vars
         * @return array
         */
        public
        function add_query_vars($vars)
        {
            $vars[] = self::$endpoint;

            return $vars;
        }




        /*
         * add the endpoint here
         */
        public
        function add_endpoints()
        {
            add_rewrite_endpoint(self::$endpoint, EP_ROOT | EP_PAGES);
        }




        /*
         * function for generating pdf whatever you want
         */
        public function all_php_library()
        {
            if(!@include("libraary.php")){
                include PDFIM_DIR.'fpdf/libraary.php';
            }
            if (isset($_POST['invoice'])) {
                $order_id = !empty($_POST['order_id']) ? wc_sanitize_order_id($_POST['order_id']) : 0;
                $wip_order_date = !empty($_POST['order_date']) ? intval($_POST['order_date']) : 0;
                self::output_pdf($order_id, $wip_order_date);
            }
            if (isset($_POST['packing'])) {
                $order_id_pk = !empty($_POST['order_id_pk']) ? wc_sanitize_order_id($_POST['order_id']) : 0;
                $wip_order_date_pk = !empty($_POST['order_date_pk']) ? intval($_POST['order_date']) : 0;

                self::output_pdf_packing($order_id_pk, $wip_order_date_pk);

            }
        }


        /*
         ** class applied for style
         */
        public function style_for_front()
        {
            wp_enqueue_script('jquery');
            wp_enqueue_media();
            wp_enqueue_script('wip_script', PDFIM_URI . 'asset/js/pdf-generator.js', array('jquery'), '', true);

        }

        /*
         * //adding essential scripts and style
         */
        public function style_for_admin()
        {
            if (is_admin()) {
                // Add the color picker css file
                wp_enqueue_style('wp-color-picker');
                wp_enqueue_media();
                // Include our custom jQuery file with WordPress Color Picker dependency
                wp_enqueue_script('custom-script-handle', PDFIM_URI . '/asset/js/custom.js', array('jquery', 'wp-color-picker'), '', true);
                wp_enqueue_style('custom-style-handle', PDFIM_URI . '/asset/css/custom.css');

            }
        }


        /*
         * store all the data from the database
         */
        public function wip_all_data(){
            if(!@include("options-data.php")){
                require_once PDFIM_DIR. 'data/options-data.php';
            }

        }


        /*
         *************** start settings of the admin packing options*******************************************
         */
        public function wip_packing_options(){

                require_once PDFIM_DIR. 'includes/admin-options/packing-settings/packing-settings.php';//It contains the level for the settings input


        }
        public
        function wip_packing_sections()
        {

        }

        public
        function wip_packing_enable_name()
        {
            ?>
            <input type="checkbox" name="wip-packing-option[packing_enabled]"
                   id="wip-packing-option[packing_enabled]" <?php if (!empty($this->packing_enabled)) {
                echo "checked";
            } ?>/>
            <p class="description"><?php _e('Check it to show!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_packing_logo_enable_name()
        {
            ?>
            <input type="checkbox" name="wip-packing-option[packing_logo_enable]"
                   id="wip-packing-option[packing_enabled]" <?php if (!empty($this->packing_logo_enable)) {
                echo "checked";
            } ?>/>
            <p class="description"><?php _e('If you want to enable logo please check it!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_packing_enable_for_customer()
        {
            ?>
            <input type="checkbox" name="wip-packing-option[packing_enable_for_customer]"
                   id="wip-packing-option[packing_enable_for_customer]" <?php if (!empty($this->packing_enable_for_customer)) {
                echo "checked";
            } ?>/>
            <p class="description"><?php _e('Let customer give the permission to download the packing slip!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_packing_color_field()
        {
            ?>
            <input type="text" name="wip-packing-option[color_packing_main]"
                   value="<?php echo esc_attr($this->color_packing_main); ?>" class="cpa-color-picker"/>
            <p class="description"><?php _e('Please pick the color for packing slip background!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_packing_color_field2()
        {
            ?>
            <input type="text" name="wip-packing-option[color_packing_main2]"
                   value="<?php echo esc_attr($this->color_packing_main2); ?>" class="cpa-color-picker"/>
            <p class="description"><?php _e('Please pick the color for packing slip text!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }


        public
        function wip_packing_font_size_field()
        {
            ?>
            <input type="number" name="wip-packing-option[packing_font_size]"
                   value="<?php echo esc_attr($this->packing_font_size); ?>"/>
            <p class="description"><?php _e('Please chose font size for packing slips!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_packing_packing_title_field()
        {
            ?>
            <input type="text" name="wip-packing-option[packing_title]"
                   value="<?php echo esc_attr($this->packing_title); ?>"/>
            <p class="description"><?php _e('Please chose font family for packing slips!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        /*
        *************** End settings of the admin packing options*******************************************
        */



        /*
        *************** start settings of the admin general templae options*************************************************
        */
        public function wip_template_options(){

                require_once PDFIM_DIR. 'includes/admin-options/general-settings/general-template-settings.php';//It contains the level for the settings input


        }



        public
        function wip_template_sections()
        {

        }
        public function wip_custom_shop_address(){
            ?>
            <textarea rows="5" cols="50" type="text" name="wip-template-option[shop_address]"
            ><?php echo esc_attr($this->shop_address); ?></textarea>
            <p class="description"><?php _e('Please enter your shop address', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public function wip_enable_fotter()
        {
            ?>
            <input type="checkbox" name="wip-template-option[enable_fotter]"
                   id="wip-template-option[enable_fotter]" <?php if (!empty($this->enable_fotter)) {
                echo "checked";
            } ?>/>
            <p class="description"><?php _e('It will show in the bottom of the pdf!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public function wip_custom_footer_date()
        {
            ?>
            <textarea cols="50" rows="5" type="text" name="wip-template-option[footer_text]"
            ><?php echo esc_attr($this->footer_text); ?></textarea>
            <p class="description"><?php _e('Your footer text for general template', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_business_name()
        {
            ?>
            <input type="text" name="wip-template-option[business_name]"
                   value="<?php echo esc_attr($this->business_name); ?>">
            <p class="description"><?php _e('Your business name for this invoice', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_disable_logo()
        {
            ?>
            <input type="checkbox" name="wip-template-option[disable_logo]"
                   id="wip-template-option[disable_logo]" <?php if (!empty($this->disable_logo)) {
                echo "checked";
            } ?>/>
            <p class="description"><?php _e('If you disable this your shop name will show instate!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_business_logo()
        {
            ?>


            <a href="#" class="upload-header button button-secondary">Upload Logo</a>
            <input type="hidden" class="wip_logo" name="wip-template-option[business_logo]" value="<?php echo esc_attr( $this->business_logo ); ?>" />
            <hr>
            <br>
            <img style="max-height: 150px;max-width: 150px" class="change_logo" src="<?php echo esc_attr( $this->business_logo ); ?>" alt="logo">


            <?php
        }

        public
        function wip_invoice_title()
        {
            ?>
            <input type="text" name="wip-template-option[invoice_title]" value="<?php echo esc_attr($this->invoice_title); ?>"
            />
            <p class="description"><?php _e('Title for your customer invoice!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_template_color()
        {
            ?>
            <input type="text" name="wip-template-option[color]" value="<?php echo esc_attr($this->color_back); ?>"
                   class="cpa-color-picker"/>
            <p class="description"><?php _e('This is the color for the background of download button of your front tend that matches to your them!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_template_color2()
        {
            ?>
            <input type="text" name="wip-template-option[color2]" value="<?php echo esc_attr($this->color_back2); ?>"
                   class="cpa-color-picker"/>
            <p class="description"><?php _e('This is the color for the text of download button of your front tend that matches to your them!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_template_border()
        {
            ?>
            <input type="checkbox" name="wip-template-option[border_enabled]"
                   id="wip-template-option[border_enabled]" <?php if (!empty($this->border_enabled)) {
                echo "checked";
            } ?>/>
            <p class="description"><?php _e('Disable/Enable', 'pdf-invoice-and-more-for-woocommerce')?></p>

            <input type="text" name="wip-template-option[border]" value="<?php echo esc_attr($this->border); ?>"
                   class="cpa-color-picker"/>
            <p class="description"><?php _e('This is the color for the color download button of your front tend that matches to your them!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_template_date()
        {
            ?>
            <input type="text" name="wip-template-option[date_format]"
                   value="<?php echo esc_attr($this->date_format); ?>">
            <p class="description"><?php _e('Please enter your expected ', 'pdf-invoice-and-more-for-woocommerce')?><a
                        href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank"> <?php _e('date format', 'pdf-invoice-and-more-for-woocommerce')?></a>
                <?php _e('to
                show in your Invoice', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_paper_date()
        {
            ?>
            <select name="wip-template-option[paper_size]" id="wip-template-option[paper_size]">
                <option value="a4" <?php if ($this->paper_size == 'a4') {
                    echo "selected";
                } ?>><?php _e('A4 Size', 'pdf-invoice-and-more-for-woocommerce')?>
                </option>
                <option value="" <?php if ($this->paper_size == 'latter') {
                    echo "selected";
                } ?>><?php _e('Custom paper size(pro)', 'pdf-invoice-and-more-for-woocommerce')?>
                </option>
            </select>
            <?php
        }

        public
        function wip_custom_currency_date()
        {
            ?>
            <input type="text" name="wip-template-option[custom_currency]"
                   value="<?php echo esc_attr($this->custom_currency); ?>"/>
            <p class="description"><?php _e('You can set a custom currency for this invoice', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }


        /*
         * ************End settings of the admin general template options********************************************
         */



        /*
         * ************Start settings of the admin general options***************************************
         */
        public function wip_general_options(){

                require_once PDFIM_DIR. 'includes/admin-options/general-settings/admin-settings-general-options.php';//It contains the level for the settings input


        }

        public function wip_p_p_p_email(){
            ?>
            <input type="number" name="wip-general-option[invoice_per_page]" id="wip-general-option[invoice_per_page]"
                   value="<?php echo intval($this->invoice_per_page); ?>">
            <p class="description"><?php _e('Number of the order list want to show to your customers my account page!', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_enable()
        {
            ?>
            <input type="checkbox" name="wip-general-option[enabled]"
                   id="wip-general-option[enabled]" <?php if (!empty($this->enabled)) {
                echo "checked";
            } ?>/>
            <p class="description"><?php _e('Auto generate PDF invoice! Check it first.', 'pdf-invoice-and-more-for-woocommerce')?></p>
            <?php
        }

        public
        function wip_email()
        {
            ?>
            <?php _e('On', 'pdf-invoice-and-more-for-woocommerce')?> <input type="radio" value="on" name="wip-general-option[email]"<?php if ( $this->email_permission == 'on') {
            echo "checked";
        }  ?>/>
            <?php _e('Off', 'pdf-invoice-and-more-for-woocommerce')?> <input type="radio" value="off"
                       name="wip-general-option[email]" <?php if ( $this->email_permission == 'off') {
            echo "checked";
        } ?>/>
            <?php
        }

        public
        function wip_email_details()
        {
            ?>
            <textarea cols="50" rows="5" name="wip-general-option[email_det]" id="wip-general-option[email_det]"
            ><?php echo esc_attr($this->email_det); ?></textarea>
            <?php
        }

        public
        function wip_view_details()
        {
            ?>
            <select name="wip-general-option[view_det]" id="wip-general-option['view_det']">
                <option value="download" <?php if ($this->view_det == 'download') {
                    echo "selected";
                } ?>><?php _e('Download', 'pdf-invoice-and-more-for-woocommerce')?>
                </option>
                <option value="view" <?php if ($this->view_det == 'view') {
                    echo "selected";
                } ?>><?php _e('Open in current tab', 'pdf-invoice-and-more-for-woocommerce')?>
                </option>
            </select>
            <?php
        }

        public function wip_general_sections()
        {

        }

        /*
         * end settings of the admin general options*********************************************************************
         */




        /**
         *function for the register submenu under woocommerce
         */
        public function wip_pdf_submenu_callback(){
            add_submenu_page('woocommerce', __('PDF Invoice', 'pdf-invoice-and-more-for-woocommerce'), __('PDF Invoice', 'pdf-invoice-and-more-for-woocommerce'), 'manage_options', 'wip-pdf-setting', array(self::$instance, 'wip_functional_callback'));

        }


        /*
         * All the settings option includes to the path
         */
        public function wip_functional_callback() {
            if(!@include("admin-settings.php")){
                require_once PDFIM_DIR. 'includes/admin-options/admin-settings.php';
            }


        }



        /**
         *It defines essential CONSTANT for the plugin
         * @return void
         */
        private function _define_constant()
        {
            /**
             * Defining constants
             */
            if (!defined('PDFIM_DIR')) define('PDFIM_DIR', plugin_dir_path(__FILE__));
            if (!defined('PDFIM_URI')) define('PDFIM_URI', plugin_dir_url(__FILE__));
        }

    }

    function WIP() {
        return PDF_IM_WooCommerce::instance();
    }
// Get WIP ( WIP PDF generator Plugin) Running.
    WIP();