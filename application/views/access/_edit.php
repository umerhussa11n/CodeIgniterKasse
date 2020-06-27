<?=form_open(current_url());?>
<div class="row sell_unit">
    <div class="col-md-6">
        <label>Tilbehør tilhører telefon</label>
        <select class="form-control" id="devices" name="model" required style="margin-bottom: 10px">
        	<option value="">-</option>
        	<?php
        	foreach($products as $product):
        	?>
        	<option value="<?=$product->id;?>"  <?php if($order->product_id == $product->id): echo 'selected="true"'; endif; ?>><?=$product->name;?></option>
        	<?php
        	endforeach;
        	?>
        </select>
    </div>
    <div class="col-md-6">
        <label>Tilbehør</label>
        <select class="form-control" id="gbs" name="access" required style="margin-bottom: 10px">
        	<option value="">-</option>
        	<?php
        	foreach($access as $access_info):
        	?>
        	<option value="<?=$access_info->id;?>"  <?php if($order->part_id == $access_info->id): echo 'selected="true"'; endif; ?> class="<?=$access_info->product_id;?>"><?=$access_info->name;?></option>
        	<?php
        	endforeach;
        	?>
        </select>
    </div>

    <div class="col-md-12"><hr /></div>

    <div class="col-md-12">
    
        <label>Betalingsmetode</label>
		<select class="form-control" name="payment_type" required style="margin-bottom: 10px">
        	<option value="" <?php if($order->payment_type == ''): echo 'selected="true"'; endif; ?> >-</option>
        	<option value="cash" <?php if($order->payment_type == 'cash'): echo 'selected="true"'; endif; ?> >Kontant</option>
        	<option value="webshop" <?php if($order->payment_type == 'webshop'): echo 'selected="true"'; endif; ?> >Webshop</option>
        	<option value="card" <?php if($order->payment_type == 'card'): echo 'selected="true"'; endif; ?> >Kort</option>
        	<option value="mobilepay" <?php if($order->payment_type == 'mobilepay'): echo 'selected="true"'; endif; ?> >Mobilepay</option>
        </select>
    
    </div>
    
    <div class="name_number" style="display: <?php if($order->payment_type == 'mobilepay' || $order->payment_type == 'card' || $order->payment_type == 'cash'): echo 'block'; else: echo 'none'; endif; ?>">
        <div class="col-md-6">
	        <label>Navn</label>
	        <input type="text"  class="form-control" name="buyer_name" value="<?=$order->name;?>" style="margin-bottom: 10px" />
        </div>
        <div class="col-md-6">
	        <label>Tlf. nummer</label>
	        <input type="text"  class="form-control" name="number" value="<?=$order->number;?>" style="margin-bottom: 10px" />
        </div>
    </div>
    
    <div class="order_id_webshop" style="display: <?php if($order->payment_type == 'webshop'): echo 'block'; else: echo 'none'; endif; ?>">
        <div class="col-md-12">
	        <label>Ordre ID fra webshop</label>
	        <input type="text" class="form-control" name="order_id" value="<?=$order->webshop_id;?>" style="margin-bottom: 10px" />
        </div>
    </div>
	
    <div class="col-md-12">
    
        <label>Pris</label>
        <input type="text" class="form-control" value="<?=$order->price;?>" name="price" required  style="margin-bottom: 10px" />
    
    </div>
    
    <div class="col-md-12">
    	<input type="hidden" name="id" value="<?=$order->id;?>" />
    	<input type="submit" class="btn btn-success" name="edit_access" value="Sælg" style="margin-top: 20px" />
    </div>

</div>
<?=form_close();?>