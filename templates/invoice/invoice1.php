<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );
$order = new WC_Order($order_id);
$shop_name = get_bloginfo('name');

//$pdf = new PDF_HTML('P', 'mm', array(210.0,	297.0)); //A4 size
$pdf = new PDF_HTML('P', 'mm', 'A4'); //latter size
$pdf->AddPage();

$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(20);
$pdf->SetFont('Arial', '', 12);
//convert color normal to RGB
$hex = $this->template_text_color;
list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
$pdf->SetTextColor($r, $g, $b);




$logo_disable = $this->disable_logo;
$image = $this->business_logo;

$hex = $this->template_text_color;
list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
$pdf->SetTextColor($r, $g, $b);

if (!$logo_disable){
    $pdf->SetFont('Arial', 'B', 25);
    $pdf->Ln(20);
    $pdf->Cell(-40);
    $pdf->Cell(90,0,$shop_name,0 , 0, 0, 0);

}else{
    $pdf->Ln(20);
    $pdf->Image($image, 21, 20, 20, 0);//1.move to right 2.move to bottom 3.zoom in 4.not much necessary
}

$pdf->Ln(-5);
$pdf->Cell(110);
$pdf->Cell(0,0,$shop_name,0 , 0, 'L', 0);


$pdf->SetFont('Arial', '', 9);
$pdf->Ln(5);
$fdsfgidsj= get_bloginfo('description');
$pdf->Cell(110);
$pdf->Cell(0,0,$fdsfgidsj,0 , 0, 'L', 0);
$pdf->Ln(5);
$pdf->Cell(110);
$pdf->Cell(0,0,$this->shop_address,0 , 0, 'L', 0);

$pdf->Ln(15);
$pdf->Cell(0, 0,'Invoice to',0 , 0, 'L', 0);




$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(7);
$pdf->Cell(0, 0,$order->get_formatted_billing_full_name(), 0 , 0, 'L', 0);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(5);
$pdf->Cell(0,0,$order->get_billing_company(),0 , 0, 'L', 0);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(5);

$pdf->Cell(0, 0,$order->get_billing_address_1(),0 , 0, 'L', 0);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(5);
$pdf->Cell(0, 0,$order->get_billing_address_2(),0 , 0, 'L', 0);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(5);
$pdf->Cell(0, 0,$order->get_billing_city().', '.$order->get_billing_state(),0 , 0, 'L', 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(5);
$pdf->Cell(0, 0,$order->get_billing_postcode(),0 , 0, 'L', 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(5);
$pdf->Cell(0, 0,$order->get_billing_phone(),0 , 0, 'L', 0);







if ($order->get_shipping_address_2() == NULL && $order->get_shipping_city() == NULL && $order->get_shipping_country() == NULL && $order->get_shipping_postcode() == NULL){
    $pdf->SetFont('Arial', '', 9);

    $pdf->Ln(-37);
    $pdf->Cell(110);
    $pdf->Cell(0,0,'Ship to',0 , 0, 'L', 0);
    $pdf->Ln(10);
    $pdf->Cell(80);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(0,0,'Same as Billing address!', 0 , 0, 'C', 0);
    $pdf->Ln(22);
}else{
    /*
*  details of shipping address
*/
    $pdf->SetFont('Arial', '', 9);

    $pdf->Ln(-37);
    $pdf->Cell(110);
    $pdf->Cell(0,0,'Ship to',0 , 0, 'L', 0);

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Ln(7.5);
    $pdf->Cell(110);
    $pdf->Cell(0,0,$order->get_formatted_shipping_full_name(),0 , 0, 'L', 0);
    $pdf->Ln(5);
    $pdf->Cell(110);
    $pdf->Cell(0,0,$order->get_shipping_company(),0 , 0, 'L', 0);
    $pdf->Ln(5);
    $pdf->Cell(110);
    $pdf->Cell(0,0,$order->get_shipping_address_1(),0 , 0, 'L', 0);
    $pdf->Ln(5);
    $pdf->Cell(110);
    $pdf->Cell(0,0,$order->get_shipping_address_2(),0 , 0, 'L', 0);
    $pdf->Ln(5);
    $pdf->Cell(110);
    $pdf->Cell(0,0,$order->get_shipping_city(),0 , 0, 'L', 0);
    $pdf->Ln(5);
    $pdf->Cell(110);
    $pdf->Cell(0,0,$order->get_shipping_postcode(), 0 , 0, 'L', 0);

}

$pdf->Ln(15);
$pdf->Cell(110);
$pdf->SetFont('Arial', 'B', 30);
$pdf->Cell(0,0,$this->invoice_title, 0 , 0, 'L', 0);

$pdf->SetTextColor(212, 87, 4);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(7.5);
$pdf->Cell(110);
$pdf->Cell(0,0,'Order Number: '.$order->get_order_number(), 0 , 0, 'L', 0);

$pdf->Ln(4);
$pdf->Cell(110);
$pdf->Cell(0,0,'Order Date: '.$wip_order_date, 0 , 0, 'L', 0);



$hex = $this->template_text_color;
list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
$pdf->SetTextColor($r, $g, $b);
//put the loop of the details for shipping level
$pdf->Ln(15);


$width_cell = array(0, 40, 28, 40, 60, 80);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(30, 10, 'Product Details', 0, 0, true); // Second header column
$pdf->Cell(60, 10, 'Price', 0, 0, true); // Fourth header column

$pdf->Cell($width_cell[1], 10, 'Quantity', 0, 0, true); // Third header column

$pdf->Cell($width_cell[1], 10, 'Total', 0, 0, true); // Fourth header column
$pdf->Ln(10);
$pdf->Cell(170,0,' ',1 , 0, 1, 0, true);


$pdf->SetFont('Arial', '', $this->fonT_size);
$pdf->Ln(5);


foreach ($order->get_items() as $order_Item) {
    $data = $order_Item->get_data();
    $w_product = wc_get_product($data['product_id']);
    //$pdf->Cell(0,10,'Left text',0,0,'L');

    $pdf->Cell(80, 5, $w_product->get_name(), 0, 0, 1, 0, true); // product name

    $pdf->Cell(60, 5, $this->custom_currency.$data['subtotal'], 0, 0, 1, 0, true); // product suk
    $pdf->Cell(-20);
    $pdf->Cell(60, 5, $order_Item->get_quantity(), 0, 0, 1, 0, true); // product quantity
    $pdf->Cell(-20);
    $pdf->Cell(60, 5, $this->custom_currency.$data['subtotal'], 0, 0, 1, 0, true); // product total

    $pdf->Ln(5);
}

$pdf->SetFont('Arial', 'B', 9);
$pdf->Ln(5);
$pdf->Cell(138);
$pdf->SetTextColor(212, 87, 4);
$subtotal = $order->get_subtotal();
$pdf->Cell(60,6,"Subtotal          $this->custom_currency$subtotal",0 , 0, 1, 0, true);
$pdf->Ln(6);
$pdf->Cell(120);
$pdf->Cell(50,0,' ',1 , 0, 1, 0, true);

$pdf->Ln(1);
$pdf->Cell(138);
$tax = $order->get_total_tax();
$pdf->Cell(60,6,"Tax                  $this->custom_currency$tax",0 , 0, 1, 0, true);
$pdf->Ln(6);
$pdf->Cell(120);
$pdf->Cell(50,0,' ',1 , 0, 1, 0, true);

$pdf->Ln(1);
$pdf->Cell(138);
$discount = $order->get_discount_total();
$pdf->Cell(60,6,"Discount         $this->custom_currency$discount",0 , 0, 1, 0, true);
$pdf->Ln(6);
$pdf->Cell(120);
$pdf->Cell(50,0,' ',1 , 0, 1, 0, true);

$pdf->Ln(1);
$pdf->Cell(138);
$shipping = $order->get_shipping_total();
$pdf->Cell(60,6,"Shipping         $this->custom_currency$shipping",0 , 0, 1, 0, true);

$pdf->Ln(8);
$pdf->Cell(120);
$g_total = $order->get_total();
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(50,6,"Grand Total      $this->custom_currency$g_total",0 , 1, 'R', 1);

if ($order->get_payment_method() == 'bacs'){
    $hex = $this->template_text_color;
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    $pdf->SetTextColor($r, $g, $b);

    $bank = new WC_Gateway_BACS();
    $if_bank = $bank->account_details;
    foreach ($if_bank as $details)
        $pdf->Ln(-20);

    $pdf->Cell(60,0,'Bank Details:',0 , 0, 1, 0, true);
    $pdf->Ln(2);

    $pdf->Cell(45,0,' ',1 , 0, 1, 0, true);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Ln(5);

    $pdf->Cell(60,0,'Account Name: '.$details['account_name'],0 , 0, 1, 0, true);
    $pdf->Ln(5);

    $pdf->Cell(60,0,'Account Number: '.$details['account_number'],0 , 0, 1, 0, true);
    $pdf->Ln(5);

    $pdf->Cell(60,0,'Bank Name: '.$details['bank_name'],0 , 0, 1, 0, true);
    $pdf->Ln(5);

    $pdf->Cell(60,0,'Short Code: '.$details['sort_code'],0 , 0, 1, 0, true);
}



if (!$this->enable_tc_fotter){
    //turm and conditions
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Ln(5);
    $pdf->SetTextColor(249, 2, 2);
    $pdf->Cell(0,0,"Terms & Conditions: $this->footer_text",0 , 0, 'L', 0);
}

if (!$this->enable_fotter){
    $hex = $this->template_text_color;
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    $pdf->SetTextColor($r, $g, $b);
    $pdf->isFinished = true;
}




//creating the directory to save the email and sent it to the customer with the admin order
$upload = wp_upload_dir();
$upload_dir = $upload['basedir'];
$upload_dir_ = $upload_dir . '/wip-invoice';
if (!is_dir($upload_dir_)) {
    mkdir($upload_dir_, 0700);
}
$name = 'Invoice';
$filename = $upload_dir_ . '/'.$name.'.pdf';
$email_send_request = $_GET['pdf_type'];//get email of the customer


if ($this->view_det == 'view') {

    $pdf->Output($filename,'F');//to save into directory to send attachment email

    if ($email_send_request == 'SendEmail'){


        $Sub = 'Invoice by '.$this->business_name;
        $time = ($order->get_date_created());
        $old_date_timestamp = strtotime($time);
        $option_format = $this->date_format;
        $new_date = date($option_format, $old_date_timestamp);
        $subject = $Sub;
        $message = $this->email_det;
        $to = $order->get_billing_email();
        $admin_email = get_bloginfo('admin_email');
        $headers = array();
        $headers[] = ('Content-Type: text/html; charset=UTF-8');
        $headers[] = "From: $this->business_name. <$admin_email> " . "\r\n";
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $attachment = $upload_dir . '/wip-invoice/Invoice.pdf';
        wp_mail($to, $subject, $message, $headers, $attachment);
        wp_redirect( admin_url( '/edit.php?post_type=shop_order' ), 301 );

    }


    $pdf->Output('I', $this->pdf_type_() . '.pdf');
    exit;

} else {

    $pdf->Output($filename,'F');//to save into directory to send attachment email

    if ($email_send_request == 'SendEmail'){

        $Sub = 'Invoice by '.$this->business_name;

        $time = ($order->get_date_created());
        $old_date_timestamp = strtotime($time);
        $option_format = $this->date_format;
        $new_date = date($option_format, $old_date_timestamp);
        $subject = $Sub;
        $message = $this->email_det;
        $to = $order->get_billing_email();
        $admin_email = get_bloginfo('admin_email');
        $headers = array();
        $headers[] = ('Content-Type: text/html; charset=UTF-8');
        $headers[] = "From: $this->business_name. <$admin_email> " . "\r\n";

        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $attachment = $upload_dir . '/wip-invoice/Invoice.pdf';
        wp_mail($to, $subject, $message, $headers, $attachment);
        wp_redirect( admin_url( '/edit.php?post_type=shop_order' ), 301 );

    }

    $pdf->Output('D', $this->pdf_type_() . '.pdf');

    exit;
}
