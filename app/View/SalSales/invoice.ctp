<?php // debug($this->request->data); debug($SalSalesDetail); debug($totals);     ?>
<div role="content">
    <div >
        <div class="row">
            <div class="pull-left" style="padding-left: 15px; font-size: 9px">
                <!--<img src="img/logo.png" width="200" height="70" alt="Logo El Progreso">-->
                <?php echo $this->Html->image('logo-biteran-factura.png', array('height'=>'56', 'width'=>'160')); ?>
                <address>
                    <strong><span style="font-size: 12px;"><?php echo $this->request->data['SalInvoice']['tax_name']; ?></span></strong>
                    <br>
                    <strong><?php echo $this->request->data['SalOffice']['invoice_name']; ?></strong>
                    <br>
                    <?php echo $this->request->data['SalOffice']['address']; ?>
                    <br>
                    <?php echo $this->request->data['SalOffice']['city'] .' - ' . $this->request->data['SalOffice']['country']; ?>
                    <?php if ($this->request->data['SalOffice']['phone'] != ''){echo '<br> Telefono(s): '.$this->request->data['SalOffice']['phone'];} ?>
                    <br>
                    <?php echo $this->request->data['SalOffice']['website']; ?>
                </address>
            </div>

            <div class="pull-right" style="text-align:center;">
                <div style="font-size: 24px">FACTURA</div>
                <strong style="font-size: 10px;">ORIGINAL - CLIENTE</strong> <br>
                <address style="font-size: 9px;">
                    <?php echo $this->request->data['SalInvoice']['description']; ?> <br>
                    ACTIVIDAD: <?php echo $this->request->data['SalInvoice']['main_activity']; ?>
                    <div style="margin-top: 5px">
                        <strong>NIT: </strong><?php echo $this->request->data['SalInvoice']['tax_number']; ?>
                    </div>
                    <div>
                        <strong>FACTURA N°: </strong><?php echo $this->request->data['SalSale']['invoice_number']; ?>
                    </div>
                    <div>
                        <strong>AUTORIZACIÓN N°: </strong><?php echo $this->request->data['SalSale']['authorization_number']; ?>
                    </div>
                </address>
            </div>

        </div>
        <!--<div class="clearfix"></div>-->

        <div class="row">
            <div class="well well-sm" >
                <div style="padding-left: 5px; font-size: 10px;" >
                    <?php
                    $date = $this->request->data['SalSale']['date'];
                    $months = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
                    ?>
                    <strong>Lugar y fecha: </strong> La Paz, <?php echo $date['day'] . ' de ' . $months[$date['month']] . ' de ' . $date['year']; ?><br>
                    <strong>Señor(es): </strong> 
                    <?php
                    $customerNitName = $this->request->data['SalSale']['customer_nit_name'];
                    if ($customerNitName == '') {
                        $customerNitName = 'SIN NOMBRE';
                    } 
                    echo $customerNitName;
                    ?>
                    <br>
                    <strong>NIT/CI: </strong> 
                    <?php
                    $customerNit = $this->request->data['SalSale']['customer_nit'];
                    if ($customerNit == '') {
                        $customerNit = '0';
                    } 
                    echo $customerNit;
                    ?>
                </div>
            </div>
            <?php if ($this->request->data['SalSale']['lc_state'] == 'CANCELED') { ?>
                <div style="font-size: 40px; text-align: center; color: red; font-weight: bold;">ANULADA</div>
            <?php } ?>
        </div>

        <table class="table table-hover table-condensed" style="font-size: 9px;">
            <thead>
                <tr>
                    <th class="text-center">CANT.</th>
                    <th>DETALLE</th>
                    <th>P.UNIT</th>
                    <th>SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($SalSalesDetail as $value) { ?>
                    <tr>
                        <td class="text-center"><strong><?php echo $value['SalSalesDetail']['quantity']; ?></strong></td>
                        <td>
<!--                            --><?php //echo $value['InvProduct']['name'] . ' ' . $value['InvProduct']['measure']; ?>
                            <?php
                            $productName = $value['InvProduct']['name'];
                            if($value['SalSalesDetail']['invoice_alternative_name'] != ''){
                                $productName = $value['SalSalesDetail']['invoice_alternative_name'];
                            }
                            echo $productName;
                            ?>
                            <?php
//                            if($value['InvProduct']['service']){
//                                echo '<br> - '.$value['InvProduct']['description'];
//                            }
                            ?>
                        </td>
                        <td><?php echo number_format($value['SalSalesDetail']['sale_price'], 2, '.', ','); ?></td>
                        <td><?php echo number_format($value['SalSalesDetail']['subtotal'], 2, '.', ','); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="font-weight: bold;text-align: right;padding-right: 20px;">TOTAL Bs.</td>
                    <td><strong><?php echo number_format($totals['total'][0]['total'], 2, '.', ','); ?></strong></td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold;text-align: right;padding-right: 20px;">DESCUENTO Bs.</td>
                    <td><strong><?php echo number_format($totals['discount'], 2, '.', ','); ?></strong></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="1" style="font-weight: bold;text-align: right;padding-right: 20px;">TOTAL NETO Bs.</td>
                    <td><strong><?php echo number_format($totals['totalFinal'], 2, '.', ','); ?></strong></td>
                </tr>
                <tr>
                    <td>
                        <?php
//                        echo $totals['totalFinal'];
                        $qrCodeText = $this->request->data['SalInvoice']['tax_number'] . '|'; //nit emisor
                        $qrCodeText .= $this->request->data['SalSale']['invoice_number'] . '|'; //numero de factura
                        $qrCodeText .= $this->request->data['SalSale']['authorization_number'] . '|'; //numero de autoriazacion
                        $qrCodeText .= $date['day'] . '/' . $date['month'] . '/' . $date['year'] . '|'; //fecha emision
                        $qrCodeText .= number_format($totals['totalFinal'], 2, '.', ',') . '|'; //total
                        $qrCodeText .= number_format($totals['totalFinal'], 2, '.', ',') . '|'; //importe credidto fiscal en este caso mismo que el total
                        $qrCodeText .= $this->request->data['SalSale']['control_code'] . '|'; //codigo de control (dinamico)
                        $qrCodeText .= $customerNit . '|'; //nit del cliente (dinamico)
                        $qrCodeText .= '0.00|'; // ICE/IEDH/TASAS:  no aplica este caso
                        $qrCodeText .= '0.00|'; // Importe ventas no grabadas o grabadas tasa cero:  no aplica este caso
                        $qrCodeText .= '0.00|'; // Importe no sujeto a credito fiscal:  no aplica este caso
                        $qrCodeText .= number_format($totals['discount'], 2, '.', ',');  //Discount
//                        $qrDiscount = $totals['discount'];
//                        $qrDiscount = '0';
//                        if ((float) $totals['discount'] > 0) {
//                            $qrDiscount = number_format($totals['discount'], 2, '.', '');
//                        }
//                        $qrCodeText .= $qrDiscount; // descuentos, bonificaciones y rebajas:  no aplica este caso, si es cero poner 0 sino poner valor.decimal Ej: (2.34)
                        echo $this->Qrcode->text($qrCodeText);
                        ?>
                    </td>
                    <td colspan="3"> 
                        <br>
                        <p><span style="font-weight: bold;">Son: </span> <?php echo $totalFinalToWord; ?> </p>
                        <p><span style="font-weight: bold;">Código de Control: </span><?php echo $this->request->data['SalSale']['control_code']; ?> </p>
                        <p><span style="font-weight: bold;">Fecha Límite de Emisión: </span> <?php echo $this->request->data['SalInvoice']['finish_date']; ?> </p>
                    </td>
                </tr>
            </tfoot>
        </table>



        <div class="invoice-footer">
            <div class="row">
                <div class="col-sm-12" style="text-align:center;">
                    <strong style="font-size: 7px">"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS. EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LEY"</strong>
                    <p class="note" style="font-size: 7px">Ley N°453: "En caso de incumpliemiento a lo ofertado o convenido, el proveedor debe reparar o sustituir el servicio".</p>
                </div>
            </div>
        </div>
    </div>

</div>