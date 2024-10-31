<?php
defined('ABSPATH') || die('Direct access is not allowed.');


// $order = new WC_Order($orders);
//not to show if the order has already bee trashed
if ($order->get_status() == 'trash') {
    return;
}


$order_id = $order->get_order_number();
$admin_pdf_types = $this->all_pdf_enabled();
$download_status = $this->view_det;

if($this->enabled){
    $all_logos[] = PDFIM_URI . '/fpdf/icons/logo.png';
}if($this->packing_enabled){
    $all_logos[] = PDFIM_URI . '/fpdf/icons/logo-pk.png';
}if ($this->email_permission){
    $all_logos[] = PDFIM_URI . '/fpdf/icons/email.png';
}


  //$ult =  $this->output_pdf($order_number, $wip_order_date);
$listing_actions = array(); // place holder for the admin doc type
$x = 0;
foreach ($admin_pdf_types as $admin_pdf_type) {
    $listing_actions[$admin_pdf_type] = array(
        'url' => wp_nonce_url(admin_url("/admin-ajax.php?action=$download_status&pdf_type=$admin_pdf_types[$x]&order_id=$order_id"),'generate_wip_execution' ),
        'img' => $all_logos[$x],
        'alt' => "PDF " . $admin_pdf_type
    );
    $x++;
}


$listing_actions = apply_filters('wip_listing_actions', $listing_actions, $order);
$pdf_type = !empty($pdf_type) ? $pdf_type : '';
$order_number = !empty($order_number) ? $order_number : '';
foreach ($listing_actions as $invoice_type => $data) {

    ?>

    <a href="<?php echo esc_url($data['url']); ?>" class="button tips wpo_wcpdf <?php echo $pdf_type; ?>"
       target="_blank" alt="<?php echo $data['alt']; ?>" data-tip="<?php echo $data['alt'];  ?>" data-order="<?php echo $order_number;  ?>">
        <img src="<?php echo esc_url($data['img']); ?>" alt="<?php echo $data['alt']; ?>" style="width: 18px">
    </a>
<?php }