<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );
    /**
     * Endpoint HTML content.
     */
        ?>
        <div class="wip_pdf" style="width: 100%">
        <h2>PDF Invoices</h2>
        <?php 	// User call from my-account page
        if ( current_user_can('manage_options') && ! isset( $_GET['my-account'] ) && is_user_logged_in()) {
           ?>

        <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
            <thead>
            <tr>
                <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span
                        class="nobr">Order No.</span></th>
                <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><span
                        class="nobr">Order Date</span></th>
                <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span
                        class="nobr">Order Status</span></th>
                <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total"><span
                        class="nobr">Total</span></th>
                <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions">
                    <span class="nobr">Actions</span></th>
            </tr>
            </thead>
            <tbody><?php


            $filters = array(
                'post_status' => 'any',
                'post_type' => 'shop_order',
                'posts_per_page' => $this->invoice_per_page,
                'paged' => 1,
                'orderby' => 'modified',
                'order' => 'DSC'
            );
            $shop_orders = new WP_Query($filters);
            if ($shop_orders->post_count) {
                $i=0;
                foreach ($shop_orders->posts as $shop_order) {
                    $i++;
                    $order = new WC_Order($shop_order->ID);
                    $time = ($shop_order->post_date);
                    $old_date_timestamp = strtotime($time);
                    $option_format = $this->date_format;
                    $new_date = date($option_format, $old_date_timestamp);
                    $order_id_database = $shop_order->ID;
                    ?>

                    <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-on-hold order">
                    <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number"
                        data-title="Order">
                        <a href="<?php //$link = get_the_permalink($lineItem['order_id']); echo $link;?>"><?php  echo '#' .$order_id_database;  ?></a>

                    </td>
                    <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-status"
                        data-title="Status"><?php echo esc_attr($new_date); ?></td>
                    <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number"
                        data-title="Order">
                        <?php echo esc_attr($order->get_status()); ?>
                    </td>
                    <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total"
                        data-title="Total">
                                        <span class="woocommerce-Price-amount amount"><span
                                                class="woocommerce-Price-currencySymbol">৳&nbsp;</span><?php echo esc_attr($order->get_total()); ?></span>
                    </td>
                    <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-action"
                        data-title="Total">
                        <div class="pdfg_opt_wrapper">

                            <form method="post" action="">
                                <?php
                                if (!empty($this->enabled)) { ?>
                                    <input type="hidden" name="order_id" value="<?php echo esc_attr($shop_order->ID);?>">
                                    <input type="hidden" name="order_date" value="<?php echo esc_attr($new_date);?>">

                                    <input style="color: <?php echo $this->color_back;?>; background: <?php echo $this->color_back2; ?>;<?php if(!empty($this->border_enabled)){echo 'border: 1px solid ';echo $this->border.';';} ?>margin-bottom: 1px"
                                           class="woocommerce-button button invoice" id="" type="submit"
                                           name="invoice" value="Download pdf Invoice">

                                <?php }

                              ?>
                            </form>
                        </div>


                    </td>
                    </tr><?php }
            } ?></tbody>
        </table>
<?php }else{ echo( __( 'Oops! You do not have sufficient permissions to access this page.', PDFIM_TEXTDOMAIN ) );} ?>
        </div>