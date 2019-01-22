<?php /* (c)Bittion | Created: 29/06/2015 | Developer:reyro | View: SalSales/sale_report | Description: basic report for sales by product */ ?>

<div role="content">
    <div >
<!--        <div class="row">-->
<!--            <div class="pull-left" style="padding-left: 15px; font-size: 9px">-->
<!--                --><?php //echo $this->Html->image('logo.png', array('alt' => 'Ferreteria El Progreso', 'height'=>'56', 'width'=>'160')); ?>
<!--            </div>-->
<!--        </div>-->
        <br>
        <!--<div class="clearfix"></div>-->
        <div class="row" style="text-align: center; font-weight: bold; font-size: 14px;">
            REPORTE DE VENTAS
        </div>
        <div class="row" style="text-align: center;">
            Desde: <?php echo $startDate;?> - Hasta: <?php echo $endDate;?>
        </div>
        <table class="table table-hover table-condensed" style="font-size: 10px;">
            <thead>
            <tr>
                <th class="text-center">Código</th>
                <th>Nombre</th>
                <th>Cantidad (Uni.)</th>
                <th>SubTotal (Bs)</th>
            </tr>
            </thead>
            <tbody>
<!--            --><?php //debug($report);?>
            <?php foreach ($report as $value) { ?>
                <tr>
                    <td class="text-center"><strong><?php echo $value['product_code']; ?></strong></td>
                    <td><?php echo $value['product']; ?></td>
                    <td><?php echo $value['quantity']; ?></td>
                    <td><?php echo number_format($value['subtotal'], 2, '.', ','); ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot style="font-weight: bold;">
                <tr>
                    <td colspan="2" style="text-align: right;padding-right: 60px;">TOTAL:</td>
                    <td><?php echo $totalQuantity;?></td>
                    <td><?php echo number_format($total, 2, '.', ',');?></td>
                </tr>
            </tfoot>
        </table>

    </div>

</div>
