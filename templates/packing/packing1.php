<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );
$order = new WC_Order($order_id_pk);
$pdf = new PDF_HTML('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(40);
$pdf->SetFont('Arial', 'I', 14);
$pdf->Cell(0, 5, $this->packing_title.' -'.$order->get_order_number(), 0, 0, 'C');
// Add a line break



// logo
$image = $this->business_logo;
if ($this->packing_logo_enable == 'on'){
    $pdf->Image($image, 20, 20, 40, 20);
}


$pdf->Ln(5);


$pdf->SetTextColor(60, 60, 60  );

// Select Arial bold 15
$pdf->SetFont('Arial', '', 15);
// Move to the right

$pdf->Ln(1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(60, 60, 60  );
$pdf->Cell(120);
//$default_name = get_bloginfo('name');
$pdf->Cell(190,10,$this->business_name, 20, 50);
$pdf->Ln(-4);
$pdf->Cell(120);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(190,10,$this->shop_address, 20, 50);
$pdf->Ln(5);








/*
 * details of billing address
 */
if ($order->get_shipping_address_1() == NULL && $order->get_shipping_address_2() == NULL && $order->get_shipping_city() == NULL && $order->get_shipping_state() == NULL && $order->get_shipping_postcode() == NULL){

    $pdf->Ln(10);
    $pdf->Cell(-1);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(190,10,'Billing & Shipping Address', 20, 50);


    $pdf->Cell(1);
    $pdf->SetFont('Arial', '', 10); //font details for the address

    $pdf->Cell(190,10,$order->get_billing_address_1(), 20, 50);
    $pdf->Ln(-5);

    $pdf->Cell(190,10,$order->get_billing_address_2(), 20, 50);

    $pdf->Ln(-5);
    $pdf->Cell(190,10,$order->get_billing_city().', '.$order->get_billing_state(), 20, 50);
    $pdf->Ln(-5);
    $pdf->Cell(190,10,$order->get_billing_postcode(), 20, 50);
}else {

    $pdf->Ln(10);
    $pdf->Cell(-1);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(190,10,'Shipping Address', 20, 50);


    $pdf->Cell(1);
    $pdf->SetFont('Arial', '', 10); //font details for the address

    $pdf->Cell(190, 10, $order->get_shipping_address_1(), 20, 50);
    $pdf->Ln(-5);

    $pdf->Cell(190, 10, $order->get_shipping_address_2(), 20, 50);

    $pdf->Ln(-5);
    $pdf->Cell(190, 10, $order->get_shipping_city() . ', ' . $order->get_shipping_state(), 20, 50);
    $pdf->Ln(-5);
    $pdf->Cell(190, 10, $order->get_shipping_postcode(), 20, 50);

}

$pdf->Ln(10);
$width_cell = array(20, 80, 50, 40, 40, 40);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell($width_cell[2], 10, 'Product Name', 0, 0, true); // Second header column

$pdf->Cell($width_cell[1], 10, 'Quantity', 0, 0, true); // Third header column


$pdf->Ln(10);
$pdf->SetFont('Arial', '', $this->packing_font_size);
$pdf->SetLineWidth(0.1); // Set our line width of our border to 1mm
$pdf->SetDrawColor(221, 221, 221); // Set the border colour to Aqua
// loop through all the products of this order and fill the info below

foreach ($order->get_items() as $order_Item) {

    $data = $order_Item->get_data();
    $w_product = wc_get_product($data['product_id']);
    //$pdf->Cell(0,10,'Left text',0,0,'L');
    $pdf->Cell($width_cell[1], 10, $w_product->get_name(), 1, 0, true); // product name
    $pdf->Cell($width_cell[1], 10, $order_Item->get_quantity(), 1, 0, true);// Product quantity


    // shipping cost

    $pdf->Ln(10);
}

$pdf->Ln(10);


$pdf->Ln(5);
$pdf->SetFontSize(9);
$pdf->WriteHTML('Order Date: '.$wip_order_date_pk);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 9);
$pdf->WriteHTML('Note: '.$this->footer_text);//footer text




if ($this->view_det == 'view') {
    $pdf->Output('I', $this->pdf_type_() . '.pdf');
    exit;
} else {
    $pdf->Output('D', $this->pdf_type_() . '.pdf');
    exit;
}