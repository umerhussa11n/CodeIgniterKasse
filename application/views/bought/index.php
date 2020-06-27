<?php
if($this->input->get('open_receipt')):
?>
<script type="text/javascript">
var win = window.open('<?=site_url();?>export/print_/<?=$this->input->get('open_receipt');?>', '_blank');
win.focus();
<?php
if($this->input->get('cid')){
?>
top.location.href = '<?=site_url('bought');?>?cid=<?=$this->input->get('cid');?>';
<?php
}else{
?>
top.location.href = '<?=site_url('bought');?>';
<?php
}
?>
</script>
<?php
endif;
?>
<div class="modal fade" id="buy_device">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Køb enhed</h4>
      </div>
      <div class="modal-body">

        <?=form_open(current_url());?>
        <div class="row">

			<?php
			if($this->session->userdata('active_boutique') == 4){
			if (strpos($rank_permissions,'bought_from_company') !== false || strpos($rank_permissions,'all') !== false) {
			?>
        	<div class="col-md-12">
        		<div class="pull-right">
	        		<div class="checkbox">
					  <label>
					    <input type="checkbox" name="bought_from_company" value="1"> Køb af virksomhed
					  </label>
					</div>
        		</div>
        	</div>
			<?php
			}
			}
			?>
	        <div class="col-md-6">
		        <label>Model</label>
		        <select class="form-control calculateBoughtPriceBasedOnValues" id="devices" name="model" required style="margin-bottom: 10px">
		        	<option value="">-</option>
		        	<?php
		        	foreach($products as $product):
		        	?>
		        	<option value="<?=$product->id;?>" controlprice="<?=$product->control_prices;?>"><?=$product->name;?></option>
		        	<?php
		        	endforeach;
		        	?>
		        </select>
	        </div>

	        <div class="col-md-6">
		        <label>GB</label>
		        <select class="form-control calculateBoughtPriceBasedOnValues" id="gbs" name="gb" required style="margin-bottom: 10px">
		        	<option value="">-</option>
		        	<?php
		        	foreach($gbs_list as $gbview):
		        	?>
		        	<option value="<?=$gbview->id;?>" class="<?=$gbview->product_id;?>"><?=$gbview->name;?></option>
		        	<?php
		        	endforeach;
		        	?>
		        </select>
	        </div>

	        <div class="col-md-6">
		        <label>IMEI</label>
		        <input type="text" class="form-control" minlength="15" maxlength="15" name="imei" required name="password" required style="margin-bottom: 10px" />
	        </div>
	        <div class="col-md-6">
		        <label>Farve</label>
		        <input type="text" class="form-control" name="color" required name="password" required style="margin-bottom: 10px" />
	        </div>


	        <div class="clearfix"></div>
	        <hr />

	        <div class="col-md-12">

	        	<input type="checkbox" class="" required="true" name="apple_id_disabled" value="1" />
		        <label>Er Apple ID slået fra?</label>

	        </div>

	        <div class="col-md-12">

		        <label>Evt. fejl og mangler</label>
		        <textarea class="form-control" rows="4" name="errors" style="margin-bottom: 10px"></textarea>

	        </div>

	        <div class="col-md-12">

		        <label>Tilstand</label>
		        <select class="form-control calculateBoughtPriceBasedOnValues" name="condition" style="margin-bottom: 10px" required>
		        	<option value="">-</option>
		        	<option value="1">Helt ny</option>
		        	<option value="2">God stand</option>
		        	<option value="3">Slidt</option>
		        	<option value="4">Defekt</option>
		        </select>

	        </div>

	        <div class="col-md-12">

		        <label>Pris</label>
		       <!-- <div class="pricearea">Prisen vil fremgå her når model, GB og tilstand er valgt</div>-->
		        <input type="text" class="form-control" name="price" required style="margin-bottom: 10px" />

	        </div>

	        <div class="clearfix"></div>

	        <hr />

	         <div class="col-md-6">
		        <label>Sælger firmanavn (valgfrit)</label>
		        <input type="text" class="form-control" <?php if($customer_info): echo 'value="'.$customer_info[0]->company_name.'"'; endif; ?> name="company_name" style="margin-bottom: 10px" />
	        </div>

	        <div class="col-md-6">
		        <label>Sælger navn</label>
		        <input type="text" class="form-control" <?php if($customer_info): echo 'value="'.$customer_info[0]->name.'"'; endif; ?> name="seller_name" required name="password" required style="margin-bottom: 10px" />
	        </div>


	        <div class="col-md-3">
		        <label>Legitimation</label>
		        <select class="form-control" required="true" name="legimation_type">
		        	<option value="">-</option>
		        	<option value="passport">Pas</option>
		        	<option value="driver_license">Kørekort</option>
		        	<option value="police_id">Politi ID</option>
		        	<option value="military_id">Militær ID</option>
		        </select>
	        </div>

	        <div class="col-md-3">
		        <label>Legitimation ID</label>
		        <input type="text" class="form-control" name="seller_id" <?php if($customer_info): echo 'value="'.$customer_info[0]->seller_id.'"'; endif; ?>  required name="password" required placeholder="" style="margin-bottom: 10px" />
	        </div>

	        <div class="col-md-6">
		        <label>Tlf nummer på sælger</label>
		        <input type="text" class="form-control" minlength="8" maxlength="8" name="number" <?php if($customer_info): echo 'value="'.$customer_info[0]->number.'"'; endif; ?>  required name="password" required style="margin-bottom: 10px" />
	        </div>

	        <div class="col-md-6">
		        <label>Email på sælger</label>
		        <input type="email" class="form-control" name="seller_email" <?php if($customer_info): echo 'value="'.$customer_info[0]->seller_email.'"'; endif; ?>  required name="password" required style="margin-bottom: 10px" />
	        </div>

	        <div class="clearfix"></div>
	        <hr />

	        <div class="col-md-12">

	        	<input type="checkbox" class="exchangePrice" name="exchange" value="1" />
		        <label>Byttepris</label>

	        </div>

	        <div class="reg_account_area">
		        <div class="col-md-12">
		        <label>Kontoinformationer</label>
		        </div>

		        <div class="col-md-6">
		        	<input type="text" class="form-control" <?php if($customer_info): echo 'value="'.$customer_info[0]->reg_nr.'"'; endif; ?>  name="reg_nr" minlength="4" maxlength="4" required placeholder="Reg. nr" required style="margin-bottom: 10px" />
		        </div>
		        <div class="col-md-6">
		        	<input type="text" class="form-control" name="account_nr" <?php if($customer_info): echo 'value="'.$customer_info[0]->account_nr.'"'; endif; ?>  maxlength="10" required  placeholder="Konto nr" required style="margin-bottom: 10px" />
		        </div>
	        </div>

	        <div class="col-md-12">

	        	<input type="checkbox" name="unitsFromSamePerson" value="1" />
		        <label>Køb flere enheder fra samme person</label>
		        <br />
		        <small style="font-weight: normal;">(Boksen vil åbne på ny efter du har trykket på knappen herunder, men med kundens oplysninger preudfyldt)</small>

	        </div>

	        <div class="col-md-12">
	        	<input type="submit" class="btn btn-success" name="buy_device" value="Køb" style="margin-top: 20px" />
	        </div>

        </div>
        <?=form_close();?>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="edit_device">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Rediger enhed</h4>
      </div>
      <div class="modal-body">

        <center><img src="<?=base_url();?>assets/images/loader.gif" /></center>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="tested">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Testet af</h4>
      </div>
      <div class="modal-body">

        <center><img src="<?=base_url();?>assets/images/loader.gif" /></center>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="transfer">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Overfør ordre til anden butik</h4>
      </div>
      <div class="modal-body">

        <center><img src="<?=base_url();?>assets/images/loader.gif" /></center>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="parts_used">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Dele brugt til at reparere enhed</h4>
      </div>
      <div class="modal-body">

        <center><img src="<?=base_url();?>assets/images/loader.gif" /></center>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="create_defect">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Opret defekt</h4>
      </div>
      <div class="modal-body">

        <?=form_open(current_url());?>
        <div class="row">

	        <div class="col-md-12">
		        <label>Udfyld ID</label>
		        <input type="text" class="form-control orderid" name="id" required style="margin-bottom: 10px" />
	        </div>

	        <hr />

	        <div class="col-md-12">
	        	<input type="hidden" name="alreadyTested" value="0" />
	        	<input type="submit" class="btn btn-success" name="create_defect" value="Opret defekt" style="margin-top: 20px" />
	        </div>

        </div>
        <?=form_close();?>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="create_fraud">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Opret mistet telefon (svind)</h4>
      </div>
      <div class="modal-body">

        <?=form_open(current_url());?>
        <div class="row">

	        <div class="col-md-12">
		        <label>Udfyld ID</label>
		        <input type="text" class="form-control orderid" name="id" required style="margin-bottom: 10px" />
	        </div>

	        <hr />

	        <div class="col-md-12">
	        	<input type="hidden" name="alreadyTested" value="0" />
	        	<input type="submit" class="btn btn-success" name="create_fraud" value="Opret svind" style="margin-top: 20px" />
	        </div>

        </div>
        <?=form_close();?>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<h1 class="page-header">
	Købte enhed
	<?php
	if($this->global_model->check_permission('create_bought_device',FALSE)){
	?>
	<div class="pull-right">
		<?php
		if (strpos($rank_permissions,'all') !== false) {
		?>
		<a href="#" class="btn btn-danger" data-toggle="modal" data-target="#create_defect">Opret defekt</a>
		<a href="#" class="btn btn-default" data-toggle="modal" data-target="#create_fraud">Opret svind</a>
		<?php
		}
		?>
		<a href="#" class="btn btn-success" data-toggle="modal" data-target="#buy_device">Køb enhed</a>
	</div>
	<?php
	}
	?>
</h1>

<div class="sortby">
	<a href="<?=site_url('bought?model='.$this->input->get('model').'&b='.$this->input->get('gb').'');?>">Alle</a> - <a href="<?=site_url('bought?sold=1&gb='.$this->input->get('gb').'&model='.$this->input->get('model').'');?>">Solgte</a> - <a href="<?=site_url('bought?sold=2&gb='.$this->input->get('gb').'&model='.$this->input->get('model').'');?>">Ikke solgte</a>

	<div class="pull-right" style="width: 325px; margin-bottom: 0px">
		<form action="<?=site_url('bought');?>" method="GET">
		<select class="form-control" name="model" style="width: 120px; float: left; margin-right: 10px">
			<option value="">Vælg model</option>
			<?php
			foreach($products as $productinfo){
				echo '<option value="'.$productinfo->id.'"'; if($this->input->get('model') == $productinfo->id): echo 'selected="true"'; endif; echo '>'.$productinfo->name.'</option>';
			}
			?>
		</select>

		<select class="form-control" name="gb" style="width: 120px; float: left; margin-right: 10px">
			<option value="">Vælg GB</option>
			<option <?php if($this->input->get('gb') == 16): echo 'selected="true"'; endif; ?>>16</option>
			<option <?php if($this->input->get('gb') == 32): echo 'selected="true"'; endif; ?>>32</option>
			<option <?php if($this->input->get('gb') == 64): echo 'selected="true"'; endif; ?>>64</option>
			<option <?php if($this->input->get('gb') == 128): echo 'selected="true"'; endif; ?>>128</option>
		</select>

		<input type="hidden" name="sold" value="<?=$this->input->get('sold');?>" />

		<input type="submit" class="btn btn-default" style="margin-right: 0px" value="Sorter" />
		</form>
	</div>

	<div class="clearfix"></div>
</div>

<hr />

<div class="clearfix"></div>

<div class="table-responsive">
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Enhed</th>
      <th>Køber</th>
      <th>IMEI</th>
      <th>Dato</th>
      <th>Tlf nummer</th>
      <th>Pris</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  	<?php
  	foreach($orders as $order):

  	?>
    <tr <?php if($order->sold == 1): echo 'style="background: #e5443f; color: #fff;"'; else: echo 'style="background: #91bb22; color: #fff;"'; endif; ?>>
      <td><a style="color: #fff" href="<?=site_url('orders/show/'.$order->id);?>"><?=$order->id;?></a></td>
      <td>
      	<?=$order->product;?><br />
      	<?=$order->gb;?>GB<br />
      	<?=$order->color;?>
      </td>
      <td>
      	<?=$order->name;?><br />
      	<?=$order->address;?>
      </td>
      <td><?=$order->imei;?></td>
      <td><?=date("d/m/Y H:i",$order->created_timestamp);?></td>
      <td><?=$order->number;?></td>
      <td><?=number_format($order->price,2,',','.');?> kr</td>
      <td width="220px">
      	<a href="#" data-toggle="modal" data-id="<?=$order->id;?>" data-target="#edit_device" class="btn btn-info btn-xs">Rediger</a>
      	<a href="#" data-toggle="modal" data-id="<?=$order->id;?>" data-target="#parts_used" class="btn btn-warning btn-xs">Dele brugt</a>
      	<?php
      	if($order->sold == 1){
          $sold_order_id = "";
          $sold_order = $this->db->select('order_id')->where('bought_from_order_id',$order->id)->get('order_item');
          if($sold_order->num_rows()){
            $sold_order_id = $sold_order->row()->order_id;
          }else{
            $sold_order = $this->db->select('id')->where('bought_from_order_id',$order->id)->get('orders');
            if($sold_order->num_rows()){
              $sold_order_id = $sold_order->row()->id;
            }
          }

          if($sold_order_id){
            $sold_order_print = site_url('export/print_/'.$sold_order_id);
          }else{
            $sold_order_print = "#";
          }

	    ?>
	    <a href="<?=$sold_order_print;?>" target="_blank" class="btn btn-success btn-xs">Solgt</a>
	    <?php
      	}else{
      	$lasttwohours = strtotime("-2 hours");
      	if($lasttwohours > $order->created_timestamp){}else{
      	?>
      	<a href="<?=site_url('bought/cancel/'.$order->id);?>" class="btn btn-default btn-xs confirm">Annuller</a>
      	<?php
      	}
      	}
      	?>
      	<br />
      	<a href="<?=site_url('export/print_/'.$order->id);?>" target="_blank" class="btn btn-default btn-xs" style="margin-top: 5px">Kvittering</a>

      	<a href="#" data-toggle="modal" data-id="<?=$order->id;?>" data-target="#transfer" class="btn btn-default btn-xs" style="margin-top: 5px">Overfør</a>

      	<?php
      	$this->db->where('order_id',$order->id);
      	$tested = $this->db->get('tests')->result();

      	if(!$tested && $order->already_tested == 0):
      	?>
      	<a href="<?=site_url('bought/test/'.$order->id);?>" class="btn btn-info btn-xs" style="margin-top: 5px">Test</a>
      	<?php
      	else:
      	?>
      	<a href="#" class="btn btn-default btn-xs" data-toggle="modal" data-id="<?=$order->id;?>" data-target="#tested" style="margin-top: 5px">Testet</a>
      	<?php
      	endif;
      	?>
      </td>
    </tr>
    <?php
    endforeach;
    ?>
  </tbody>
</table>

<center><?php echo $this->pagination->create_links(); ?></center>

</div>
