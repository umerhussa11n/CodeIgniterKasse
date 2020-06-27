<meta charset="UTF-8">
<style type="text/css">
    @media only screen and (max-width: 480px){
        #templateColumns{
            width:100% !important;
        }

        .templateColumnContainer{
            display:block !important;
            width:100% !important;
            font-size: 16px !important;
        }

        .columnImage{
            height:auto !important;
            max-width:480px !important;
            width:100% !important;
        }
		

        .leftColumnContent{
            font-size:16px !important;
            line-height:125% !important;
        }

        .rightColumnContent{
            font-size:16px !important;
            line-height:125% !important;
        }
    }
</style>

<?php
$start_time = strtotime('2016W'.$week_number.' 00:00:00');
$end_time   = strtotime('2016W'.$week_number.' 23:59:59')+518400;

$week_name = date("W",$start_time);

$sale_amount = $this->global_model->get_sale_by_type_cronjob($start_time,$end_time,'sold',false);
$sale_access_amount = $this->global_model->get_sale_by_type_cronjob($start_time,$end_time,'access',false);

$start_time = strtotime("2015-07-01 00:00:00");

$sale_amount_total = $this->global_model->get_sale_by_type_cronjob($start_time,$end_time,'sold',false);
$sale_access_amount_total = $this->global_model->get_sale_by_type_cronjob($start_time,$end_time,'access',false);
?>

<table border="1" cellpadding="8" cellspacing="0" width="600" id="templateColumns">
	<tr>
        <td align="left" colspan="10" class="templateColumnContainer">
            <b>TOTAL</b>
        </td>
    </tr>
    <tr>
        <td align="left"  class="templateColumnContainer">
            Uge <?=$week_name;?>
        </td>
        <td align="left" class="templateColumnContainer">
            Actual
        </td>
        <td align="left" class="templateColumnContainer">
            Budget
        </td>
        <td align="left" class="templateColumnContainer">
            Actual akk
        </td>
        <td align="left" class="templateColumnContainer">
            Budget akk
        </td>
    </tr>
    <tr>
        <td align="left"  class="templateColumnContainer">
            Salg telefoner
        </td>
        <td align="left" class="templateColumnContainer">
            <?=number_format($sale_amount,0,',','.');?>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
        <td align="left" class="templateColumnContainer">
            <?=number_format($sale_amount_total,0,',','.');?>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
    </tr>
    <tr>
        <td align="left"  class="templateColumnContainer">
            Salg tilbehør
        </td>
        <td align="left" class="templateColumnContainer">
            <?=number_format($sale_access_amount,0,',','.');?>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
        <td align="left" class="templateColumnContainer">
            <?=number_format($sale_access_amount_total,0,',','.');?>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
    </tr>
    <tr>
        <td align="left"  class="templateColumnContainer">
            <b>Omsætning i alt</b>
        </td>
        <td align="left" class="templateColumnContainer">
            <b><?=number_format($sale_amount+$sale_access_amount,0,',','.');?></b>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
        <td align="left" class="templateColumnContainer">
            <b><?=number_format($sale_amount_total+$sale_access_amount_total,0,',','.');?></b>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
    </tr>


    <?php
    foreach($boutiques as $boutique):
    
    $start_time = strtotime('2016W'.$week_number.' 00:00:00');
	$end_time   = strtotime('2016W'.$week_number.' 23:59:59')+518400;
	
	$week_name = date("W",$start_time);
    
    $sale_amount = $this->global_model->get_sale_by_type_cronjob($start_time,$end_time,'sold',$boutique->id);
    $sale_access_amount = $this->global_model->get_sale_by_type_cronjob($start_time,$end_time,'access',$boutique->id);
    
    $start_time = strtotime("2015-07-01 00:00:00");
    
    $sale_amount_total = $this->global_model->get_sale_by_type_cronjob($start_time,$end_time,'sold',$boutique->id);
    $sale_access_amount_total = $this->global_model->get_sale_by_type_cronjob($start_time,$end_time,'access',$boutique->id);
    
    ?>
    <tr>
        <td align="left" colspan="10" class="templateColumnContainer">
            <b><?=$boutique->name;?></b>
        </td>
    </tr>
    <tr>
        <td align="left"  class="templateColumnContainer">
            Uge <?=$week_name;?>
        </td>
        <td align="left" class="templateColumnContainer">
            Actual
        </td>
        <td align="left" class="templateColumnContainer">
            Budget
        </td>
        <td align="left" class="templateColumnContainer">
            Actual akk
        </td>
        <td align="left" class="templateColumnContainer">
            Budget akk
        </td>
    </tr>
    
    <tr>
        <td align="left"  class="templateColumnContainer">
            Salg telefoner
        </td>
        <td align="left" class="templateColumnContainer">
            <?=number_format($sale_amount,0,',','.');?>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
        <td align="left" class="templateColumnContainer">
            <?=number_format($sale_amount_total,0,',','.');?>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
    </tr>
    <tr>
        <td align="left"  class="templateColumnContainer">
            Salg tilbehør
        </td>
        <td align="left" class="templateColumnContainer">
            <?=number_format($sale_access_amount,0,',','.');?>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
        <td align="left" class="templateColumnContainer">
            <?=number_format($sale_access_amount_total,0,',','.');?>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
    </tr>
    <tr>
        <td align="left"  class="templateColumnContainer">
            <b>Omsætning i alt</b>
        </td>
        <td align="left" class="templateColumnContainer">
            <b><?=number_format($sale_amount+$sale_access_amount,0,',','.');?></b>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
        <td align="left" class="templateColumnContainer">
            <b><?=number_format($sale_amount_total+$sale_access_amount_total,0,',','.');?></b>
        </td>
        <td align="left" class="templateColumnContainer">
            
        </td>
    </tr>
    <?php
    endforeach;
    ?>
</table>